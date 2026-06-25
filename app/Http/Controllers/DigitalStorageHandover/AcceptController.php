<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use App\Helpers\Main;
use App\Helpers\QueryAPI;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Milon\Barcode\DNS2D;

class AcceptController extends Controller
{
    private $worksheetCategory;

    public function __construct()
    {
        $this->worksheetCategory = Main::COLLECTION_DIGITAL;
    }

    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'media' => QueryAPI::get("select * from collectionmedias where (isdelete = 0 or isdelete is null) and worksheet_id in (20,142) and depositformat_code is not null") ?? [],
                'content' => 'digital-storage-handover.accept',
                'plugins' => [
                    'datatable',
                    'daterangepicker',
                    'select2',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'catalogs.id',
            null,
            'penerbit.name',
            'catalogs.title',
            'collectionmedias.name',
            'catalogs.isbn',
            'catalogs.createdate',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $length = $start + intval($request->length ?? 10);;

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "
            (
                catalogs.isdelete = 0 or
                catalogs.isdelete is null
            ) and
            worksheets.category = '$this->worksheetCategory' and
            catalogs.edeposit_col_id is not null and
            catalogs.penerbit_id = " . $request->executor_id . "
        ";

        if ($request->title) {
            $title = strtoupper($request->title);
            $whereCondition[] = "upper(catalogs.title) like '%$title%'";
        }

        if ($request->year) {
            $whereCondition[] = "catalogs.publishyear = $request->year";
        }

        if ($request->code) {
            $code = str_replace('-', '', $request->code);
            $whereCondition[] = "catalogs.isbn = $code";
        }

        if ($request->media_id) {
            $whereCondition[] = "e_collections.collection_media_id = $request->media_id";
        }

        if ($request->date) {
            $explodeDate = explode(' - ', $request->date);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $whereCondition[] = "(catalogs.createdate >= to_date('$startDate', 'YYYY-MM-DD') and catalogs.createdate < to_date('$endDate', 'YYYY-MM-DD') + 1)";
        }

        if ($search) {
            $terms = [];

            foreach ($column as $c) {
                if ($c) {
                    $terms[] = "upper($c) like '%$search%'";
                }
            }

            $whereCondition[] = '(' . implode(' or ', $terms) . ')';
        }

        if ($whereCondition) {
            $whereClause = "where " . implode(' and ', $whereCondition);
        }

        if ($order) {
            $orderColumnIndex = $order[0]['column'];
            $orderDir = $order[0]['dir'];
            $orderBy = "order by " . $column[$orderColumnIndex] . " $orderDir";
        }

        $totalData = QueryAPI::get("
            select
                count(*) as total
            from
                catalogs
            left join
                worksheets on worksheets.id = catalogs.worksheet_id
            where
                (
                    catalogs.isdelete = 0 or
                    catalogs.isdelete is null
                ) and
                worksheets.category = '$this->worksheetCategory' and
                catalogs.edeposit_col_id is not null and
                catalogs.penerbit_id = " . $request->executor_id . "
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                catalogs
            left join
                e_collections on catalogs.edeposit_col_id = e_collections.id
            left join
                kabupaten on kabupaten.id = e_collections.kabupaten_id
            left join
                worksheets on worksheets.id = catalogs.worksheet_id
            left join
                penerbit on penerbit.id = catalogs.penerbit_id
            left join
                collectionmedias on collectionmedias.id = e_collections.collection_media_id
            $whereClause
        ", true)->TOTAL ?? 0;

        $queryData = QueryAPI::get("
            select
                *
            from (
                    select
                        rownum as rnum,
                        data.*
                    from
                        (
                            select
                                catalogs.id,
                                catalogs.title,
                                catalogs.isbn,
                                e_collections.received_at,
                                penerbit.name as name_penerbit,
                                collectionmedias.name as name_media,
                                case
                                    when e_collections.code_type = 1 then 'ISBN'
                                    when e_collections.code_type = 3 then 'ISRC'
                                    when e_collections.code_type = 2 then ' ISSN'
                                end code_type
                            from
                                catalogs
                            left join
                                e_collections on catalogs.edeposit_col_id  = e_collections.id
                            left join
                                kabupaten on kabupaten.id = e_collections.kabupaten_id
                            left join
                                worksheets on worksheets.id = catalogs.worksheet_id
                            left join
                                penerbit on penerbit.id = catalogs.penerbit_id
                            left join
                                collectionmedias on collectionmedias.id = e_collections.collection_media_id
                            $whereClause
                            $orderBy
                        ) data
                    where
                        rownum <= $length
                )
            where
                rnum > $start
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $action = '
                    <a href="' . url('digital-storage-handover/accept/detail/' . $val->ID) . '" class="btn btn-primary btn-sm">
                        <i class="ph-info me-1"></i>
                        Detail
                    </a>
                    <a href="' . url('digital-storage-handover/accept/receipt/' . $val->ID) . '" class="btn btn-success btn-sm" target="_blank">
                        <i class="ph-printer me-1"></i>
                        Tanda Terima
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->NAME_PENERBIT,
                    $val->TITLE,
                    $val->NAME_MEDIA,
                    $val->CODE_TYPE . ' ' . $val->ISBN,
                    Carbon::parse($val->RECEIVED_AT)->isoFormat('dddd, D MMMM Y'),
                ];

                $start++;
            }
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function detail(Request $request, $id)
    {
        $collection = QueryAPI::get("
            select
                c.*,
                penerbit.name as name_penerbit,
                k.namakab as namakab,
                pr.namapropinsi as namapropinsi,
                ec.collection_media_id as cm_id_e_col,
                ec.code_type as code_type_e_collection,
                ec.serial as serial_e_collection,
                ec.received_at as received_at_e_collection,
                ec.price as price_e_collection,
                ec.jilid as jilid_e_collection,
                ec.description as description_e_collection,
                ec.jenis_isi as jenis_isi_e_collection,
                ec.jenis_wadah as jenis_wadah_e_collection,
                ec.jenis_media as jenis_media_e_collection,
                ec.currency as currency_e_collection,
                ec.jumlah_eks as jumlah_eks_e_collection,
                ec.physical_description as pd_e_collection,
                ec.collection_media_id as e_col_media_id,
                par.title as title_parent,
                ccr.id as id_catalogcovers,
                ccr.fileurl as fileurl_catalogcovers,
                ccr.hash as hash_catalogcovers,
                ccr.mime as mime_catalogcovers,
                ccr.file_size as file_size_catalogcovers,
                ccr.method as method_catalogcovers,
                cfr.id as id_catalogfiles,
                cfr.fileurl as fileurl_catalogfiles,
                cfr.hash as hash_catalogfiles,
                cfr.mime as mime_catalogfiles,
                cfr.file_size as file_size_catalogfiles,
                cfr.method as method_catalogfiles,
                w.alias as alias_worksheet,
                w.category as category_worksheet,
                cm.name as collection_media_name
            from
                catalogs c
            left join
                e_collections ec on ec.id = c.edeposit_col_id
            left join
                e_collections par on par.id = ec.parent_id
            left join
                kabupaten k on k.id = ec.kabupaten_id
            left join
                propinsi pr on pr.id = k.propinsiid
            left join
                worksheets w on w.id = c.worksheet_id
            left join
                penerbit on penerbit.id = c.penerbit_id
            left join
                collectionmedias cm on cm.id = ec.collection_media_id
            left join
                (
                    select
                        *
                    from (
                        select
                            cf.catalog_id,
                            cf.id,
                            cf.fileurl,
                            cf.hash,
                            cf.mime,
                            cf.file_size,
                            cf.method,
                            row_number() over (order by cf.id desc) as rn
                        from
                            catalogfiles cf
                        where
                            cf.catalog_id = $id
                    ) where rn = 1
                ) cfr on cfr.catalog_id = c.id
            left join
                (
                    select
                        *
                    from (
                        select
                            cc.catalog_id,
                            cc.id,
                            cc.fileurl,
                            cc.hash,
                            cc.mime,
                            cc.file_size,
                            cc.method,
                            row_number() over (order by cc.id desc) as rn
                        from
                            catalogcovers cc
                        where
                            cc.catalog_id = $id
                    ) where rn = 1
                ) ccr on ccr.catalog_id = c.id
            where
                nvl(c.isdelete, 0) = 0
                and c.id = $id
        ", true);

        if ($request->ajax()) {
            $validation = Validator::make($request->all(), [
                'access' => 'required',
            ], [
                'access.required' => 'Akses tidak boleh kosong',
            ]);

            if ($validation->fails()) {
                $response = [
                    'code' => 400,
                    'error' => $validation->errors()->all(),
                ];
            } else {
                try {
                    $baseCollectionData = [
                        'akses' => $request->access,
                        'preview' => $request->preview,
                        'updateby' => session('username'),
                        'updatedate' => date('Y-m-d H:i:s'),
                        'updateterminal' => $request->ip(),
                    ];

                    $updateCollection = QueryAPI::update('catalogs', $id, $baseCollectionData, false);

                    if (!$updateCollection) {
                        throw new \Exception('Gagal membuat data koleksi');
                    }

                    $response = [
                        'code' => 200,
                        'message' => 'Data telah di update'
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    ];
                }
            }

            return response()->json($response);
        }

        $collectionCategory = [];
        $collectionId = $collection->EDEPOSIT_COL_ID ?? 0;

        $dataCollectionCategory = QueryAPI::get("
            select
                *
            from
                e_collection_categories
            where
                collection_id = $collectionId
        ");

        if ($dataCollectionCategory) {
            foreach ($dataCollectionCategory as $dcc) {
                $collectionCategory[] = $dcc->CATEGORY_ID;
            }
        }

        return view('layouts.index', [
            'data' => [
                'media' => QueryAPI::get("select * from collectionmedias where (isdelete = 0 or isdelete is null) and worksheet_id in (20,142) and depositformat_code is not null") ?? [],
                'category' => QueryAPI::get("select * from e_categories where deleted_at is null") ?? [],
                'collection' => $collection,
                'collectionCategory' => $collectionCategory,
                'collectionContributor' => explode(';', ($collection->AUTHOR ?? '')),
                'physicalDescription' => json_decode($collection->PD_E_COLLECTION ?? ''),
                'content' => 'digital-storage-handover.accept-detail',
                'plugins' => [
                    'select2',
                    'datatable',
                    'epubjs',
                    'videojs',
                    'pdfjs',
                    'howlerjs',
                ]
            ]
        ]);
    }

    public function receipt($id)
    {
        try {
            $collection = QueryAPI::get("
                select c.id, c.createdate, c.title, c.controlnumber, c.isbn,
                    cfr.hash as hash_catalogfiles, cfr.mime as mime_catalogfiles,
                    cfr.file_size as file_size_catalogfiles
                from catalogs c
                left join (
                    select * from (
                        select cf.catalog_id, cf.id, cf.hash, cf.mime, cf.file_size,
                            row_number() over (order by cf.id desc) as rn
                        from catalogfiles cf
                        where cf.catalog_id = $id
                    ) where rn = 1
                ) cfr on cfr.catalog_id = c.id
                where nvl(c.isdelete, 0) = 0 and c.id = $id
            ", true);

            if (!$collection) {
                return "Data Koleksi dengan ID $id tidak ditemukan di database.";
            }

            $settings = QueryAPI::get("select * from e_settings where slug in ('Header','Footer','KoleksiTervalidasi')");
            $templateEmailContent = null;
            $templateEmailHeader = null;
            $templateEmailFooter = null;

            if ($settings) {
                foreach ($settings as $setting) {
                    $slug = $setting->SLUG ?? $setting->slug;

                    if ($slug == 'KoleksiTervalidasi') $templateEmailContent = $setting;
                    elseif ($slug == 'Header' && $setting->PROVINCE_ID == 31) $templateEmailHeader = $setting;
                    elseif ($slug == 'Footer' && $setting->PROVINCE_ID == 31) $templateEmailFooter = $setting;
                }
            }

            $imgHeader = '';
            $imgFooter = '';

            if ($templateEmailHeader) {
                $hId = $templateEmailHeader->ID ?? $templateEmailHeader->id;
                $hCont = $templateEmailHeader->CONTENT ?? $templateEmailHeader->content;
                $imgHeader = QueryAPI::getFileBase64([
                    'type' => 'gambar_template',
                    'id' => $hId            ?? '',
                    'filename' => $hCont,
                ]);
                
            }

            if ($templateEmailFooter) {
                $fId = $templateEmailFooter->ID ?? $templateEmailFooter->id;
                $fCont = $templateEmailFooter->CONTENT ?? $templateEmailFooter->content;
                $imgFooter = QueryAPI::getFileBase64([
                    'type' => 'gambar_template',
                    'id' => $fId            ?? '',
                    'filename' => $fCont,
                ]);
            }

            $branchId = 37;
            $dateNow = date('Y-m-d');

            $leader = QueryAPI::get("
                select * from penanggung_jawab
                where branch_id = $branchId and
                (tanggal_awal <= to_date('$dateNow', 'YYYY-MM-DD') and tanggal_akhir >= to_date('$dateNow', 'YYYY-MM-DD') + 1)
            ", true);

            if (!$leader) {
                return '
                    <script>
                        alert("Tidak ada data direktur / pimpinan yang aktif (Branch ID: ' . $branchId . ')");
                        window.close();
                    </script>
                ';
            }

            $lId = $leader->ID ?? $leader->id;
            $lTtd = $leader->TTD_FILE_NAME ?? $leader->ttd_file_name;
            $ttdUrl = url('stream-file') . '?type=gambar_ttd&id=' . $lId . '&filename=' . $lTtd;
            $imgTtd = Main::base64File($ttdUrl) ?: '';
            $imgTagTtd = (strlen($imgTtd) > 100) ? '<img src="' . $imgTtd . '" height="60">' : '<br><br><br>';

            $signatureTable = '
                <table border="0" style="text-align:center; width:100%;">
                    <tr><td>' . ($leader->JABATAN ?? $leader->jabatan ?? 'Pejabat') . '</td></tr>
                    <tr><td style="height:70px;">' . $imgTagTtd . '</td></tr>
                    <tr><td>' . ($leader->NAMA ?? $leader->nama ?? '') . '</td></tr>
                    <tr><td style="font-weight:bold;">NIP. ' . ($leader->NIP ?? $leader->nip ?? '-') . '</td></tr>
                </table>
            ';

            $qrGenerator = new DNS2D();
            $cId = $collection->ID ?? $collection->id;
            $qrCodeBody = config('system.fo_url') . '/collections/detail/' . $cId;
            $qrBase64Raw = $qrGenerator->getBarcodePNG((string) $qrCodeBody, 'QRCODE', 4, 4);
            $emptyHeader = '';

            $dataParseTemplate = [
                'publisher' => session('name'),
                'createdate' => Carbon::parse($collection->CREATEDATE ?? $collection->createdate)->isoFormat('D MMMM Y'),
                'title' => $collection->TITLE ?? $collection->title,
                'identifier' => $collection->CONTROLNUMBER ?? $collection->controlnumber,
                'mimes' => $collection->MIME_CATALOGFILES ?? $collection->mime_catalogfiles,
                'hash' => $collection->HASH_CATALOGFILES ?? $collection->hash_catalogfiles,
                'size' => Main::formatFileSize($collection->FILE_SIZE_CATALOGFILES ?? $collection->file_size_catalogfiles),
                'code' => $collection->ISBN ?? $collection->isbn,
                'director' => $signatureTable,
                'header' => !empty($imgHeader) ? '<div style="text-align:center;"><img src="' . $imgHeader . '" width="500" style="width:100%;"></div>' : $emptyHeader,
                'footer' => !empty($imgFooter) ? '<br><br><br><br><br><br><br><br><div style="text-align:center;"><img src="' . $imgFooter . '" width="650" style="width:100%;"></div>' : '',
                'qr' => '<img src="data:image/png;base64,' . $qrBase64Raw . '" style="height:100px; width:100px">',
            ];

            $htmlContent = Main::parseTemplateEmail($dataParseTemplate, $templateEmailContent);

            if (ob_get_contents()) ob_end_clean();

            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(15, 10, 15);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage();
            $pdf->writeHTML($htmlContent, true, false, true, false, '');

            $cNumber = $collection->CONTROLNUMBER ?? $collection->controlnumber ?? $id;
            $filename = Str::slug('TandaTerimaKoleksiDigitalSakedap-' . $cNumber) . '.pdf';

            return $pdf->Output($filename, 'I');
        } catch (\Exception $e) {
            return "Terjadi Error: " . $e->getMessage() . " di baris " . $e->getLine();
        }
    }
}
