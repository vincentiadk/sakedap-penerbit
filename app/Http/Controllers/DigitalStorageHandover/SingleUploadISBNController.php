<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use Carbon\Carbon;
use App\Helpers\ISBN;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SingleUploadISBNController extends Controller
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
                'content' => 'digital-storage-handover.single-upload-isbn',
                'plugins' => [
                    'datatable',
                    'daterangepicker',
                    'select2',
                    'fileinput',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'ec.id',
            null,
            'ec.status_upload_isbn',
            'ec.title',
            'ec.code',
            'ec.created_at',
            'ccr.id',
            'cfr.id',
            null,
            'ec.description',
            'ec.kabupaten_id',
            'ec.preview',
            'ec.akses',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $length = $start + intval($request->length ?? 10);;

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = 'order by ec.id desc';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "(ec.status = '4' and ec.deleted_at is null)";
        $whereCondition[] = "(ec.created_by = " . session('id') . ' or ' . 'ec.penerbit_id = ' . session('id') . ')';
        $whereCondition[] = "ec.code_type = '1'";
        $whereCondition[] = "ec.code is not null";
        $whereCondition[] = "w.category = '" . $this->worksheetCategory . "'";

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
                e_collections
            left join
                worksheets on worksheets.id = e_collections.worksheet_id
            where
                (e_collections.status = '4' and e_collections.deleted_at is null) and
                e_collections.created_by = " . session('id') . " and
                worksheets.category = '" . $this->worksheetCategory . "' and
                e_collections.code_type = '1' and
                e_collections.code is not null
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                e_collections ec
            left join
                worksheets w on w.id = ec.worksheet_id
            left join
                (
                    select
                        cf.e_col_id, cf.id,
                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                    from
                        catalogfiles cf
                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
            left join
                (
                    select
                        cc.e_col_id, cc.id,
                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                    from
                        catalogcovers cc
                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
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
                                ec.*,
                                ccr.id as id_catalogcovers,
                                ccr.fileurl as fileurl_catalogcovers,
                                cfr.id as id_catalogfiles,
                                cfr.fileurl as fileurl_catalogfiles
                            from
                                e_collections ec
                            left join
                                worksheets w on w.id = ec.worksheet_id
                            left join
                                (
                                    select
                                        cf.e_col_id, cf.id, cf.fileurl,
                                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                                    from
                                        catalogfiles cf
                                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
                            left join
                                (
                                    select
                                        cc.e_col_id, cc.id, cc.fileurl,
                                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                                    from
                                        catalogcovers cc
                                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
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
                    <a href="' . url('digital-storage-handover/single-upload-isbn/update-data/' . $val->ID) . '" class="btn btn-warning btn-sm">
                        <i class="ph-pen me-1"></i>
                        Ubah Data
                    </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="destroyData(' . $val->ID . ')">
                        <i class="ph-trash-simple me-1"></i>
                        Hapus Data
                    </a>
                ';

                if (($val->ID_CATALOGCOVERS ?: null)) {
                    $badgeCover = '
                        <a href="' . url('stream-file?type=cover&id=' . $val->ID_CATALOGCOVERS . '&filename=' . $val->FILEURL_CATALOGCOVERS) . '" target="_blank">Lihat File</a>
                    ';
                } else {
                    $badgeCover = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->ID_CATALOGFILES ?: null)) {
                    $badgeContent = '
                        <a href="' . url('stream-file?type=konten_digital&id=' . $val->ID_CATALOGFILES . '&filename=' . $val->FILEURL_CATALOGFILES) . '" target="_blank">Lihat File</a>
                    ';
                } else {
                    $badgeContent = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->PUBLICATION_DAY ?: null) && ($val->PUBLICATION_MONTH ?: null) && ($val->PUBLICATION_YEAR ?: null)) {
                    $badgePublish = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgePublish = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->DESCRIPTION ?: null)) {
                    $badgeDescription = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgeDescription = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->KABUPATEN_ID ?: null)) {
                    $badgeCity = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgeCity = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->PREVIEW ?: null)) {
                    $badgePreview = $val->PREVIEW;
                } else {
                    $badgePreview = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->AKSES ?: null)) {
                    $badgeAccess = $val->AKSES;
                } else {
                    $badgeAccess = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                $data[] = [
                    $start + 1,
                    $action,
                    $val->STATUS_UPLOAD_ISBN,
                    ($val->TITLE ?? $val->TITLE_ORI),
                    $val->CODE,
                    Carbon::parse($val->CREATED_AT)->isoFormat('dddd, D MMMM Y'),
                    $badgeCover,
                    $badgeContent,
                    $badgePublish,
                    $badgeDescription,
                    $badgeCity,
                    $badgePreview,
                    $badgeAccess,
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

    public function uploaded(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'files'   => 'required|array',
            'files.*' => 'required|file',
        ], [
            'files.required'   => 'File tidak boleh kosong',
            'files.array'      => 'File harus array',
            'files.*.required' => 'File tidak boleh kosong',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code'  => 400,
                'error' => $validation->errors()->all(),
                'message' => 'Validasi gagal.',
            ]);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'epub', 'mp3', 'mp4'];

        $STATUS_DRAFT      = 4;
        $STATUS_REVIEW     = 1;
        $STATUS_DITERIMA   = 2;
        $STATUS_BERMASALAH = 3;

        $files = $request->file('files');

        $successCreate = 0;
        $successUpdate = 0;
        $rejectedCount = 0;
        $errors = [];

        foreach ($files as $idx => $file) {
            try {
                $ext = strtolower($file->getClientOriginalExtension() ?? '');
                $mime = $file->getMimeType() ?? '';
                $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                if (!in_array($ext, $allowedExtensions)) {
                    $rejectedCount++;
                    $errors[] = "[SKIP] <strong>{$basename}</strong>: Ekstensi <strong>.{$ext}</strong> tidak didukung.";
                    continue;
                }

                $isbnDigits = preg_replace('/[^0-9]/', '', (string) $basename);

                if (strlen($isbnDigits) < 10) {
                    $rejectedCount++;
                    $errors[] = "[SKIP] <strong>{$basename}</strong>: Nama file tidak mengandung ISBN yang valid.";

                    continue;
                }

                $uploadType = null;
                if ($ext === 'pdf' || $mime === 'application/pdf') {
                    $uploadType = 'konten_digital';
                } elseif ($ext === 'epub' || $mime === 'application/epub+zip') {
                    $uploadType = 'konten_digital';
                } elseif ($ext === 'mp4' || $mime === 'application/mp4') {
                    $uploadType = 'konten_digital';
                } elseif ($ext === 'mp3' || $mime === 'application/mpeg') {
                    $uploadType = 'konten_digital';
                } elseif (in_array($ext, ['jpg', 'jpeg', 'png']) || str_starts_with($mime, 'image/')) {
                    $uploadType = 'cover';
                } else {
                    $rejectedCount++;
                    $errors[] = "[SKIP] <strong>{$basename}</strong>: Tipe file tidak dikenali.";
                    continue;
                }

                $sql = "
                    select id, slug, status
                    from e_collections
                    where replace(trim(code), '-', '') = '{$isbnDigits}'
                    and deleted_at is null
                ";
                $existing = QueryAPI::get($sql, true);
                if ($existing) {
                    $collectionId   = (int) $existing->ID;
                    $collectionSlug = (string) $existing->SLUG;
                    $status         = (int) ($existing->STATUS ?? 0);

                    if ($status === $STATUS_DITERIMA) {
                        $rejectedCount++;
                        $errors[] = "[REJECT] <strong>{$basename}</strong>: Sudah <strong>DITERIMA</strong>. Upload ditolak.";
                        continue;
                    }

                    if ($status === $STATUS_REVIEW) {
                        $rejectedCount++;
                        $errors[] = "[REJECT] <strong>{$basename}</strong>: Sedang <strong>REVIEW</strong>. Upload ditolak.";
                        continue;
                    }

                    if ($status === $STATUS_BERMASALAH) {
                        $rejectedCount++;
                        $errors[] = "[REJECT] <strong>{$basename}</strong>: Status <strong>BERMASALAH</strong>. Perbaiki lewat fitur Bermasalah.";
                        continue;
                    }

                    if ($status !== $STATUS_DRAFT) {
                        $rejectedCount++;
                        $errors[] = "[REJECT] <strong>{$basename}</strong>: Status koleksi tidak mengizinkan update.";
                        continue;
                    }

                    $this->cleanUpOldFile($collectionId, $uploadType);
                    $this->uploadFileToApi($collectionId, $collectionSlug, $file, $uploadType);

                    $successUpdate++;
                    $errors[] = "[OK] <strong>{$basename}</strong>: Draft ditemukan, <strong>{$uploadType}</strong> berhasil diupdate.";
                    continue;
                }

                $getISBN = ISBN::get('search', ['code' => $isbnDigits], true);
                if (!$getISBN) {
                    $rejectedCount++;
                    $errors[] = "[SKIP] <strong>{$basename}</strong>: Data ISBN tidak ditemukan di database pusat.";
                    continue;
                }

                if (($getISBN->jenis_media ?? '') === 'cetak') {
                    $rejectedCount++;
                    $errors[] = "[SKIP] <strong>{$basename}</strong>: ISBN terdaftar sebagai <strong>media cetak</strong>.";
                    continue;
                }

                $executorId = (int) ($getISBN->penerbit_id ?? 0);
                $executor   = $executorId ? QueryAPI::get("select * from penerbit where id = {$executorId}", true) : null;

                $physicalDescription = [
                    'paging' => $getISBN->jml_hlm ?? '',
                    'paging_flag' => 'Halaman',
                ];

                $createCollection = QueryAPI::create('e_collections', [
                    'id_old' => 0,
                    'city_id' => $executor->CITY_ID ?? session('city_id'),
                    'publisher_id' => $executorId ?: null,
                    'title_ori' => $getISBN->title ?? '',
                    'slug' => Str::slug($getISBN->title ?? '', '-'),
                    'series' => $getISBN->seri ?? '',
                    'deposit' => Main::generateNumberDeposit(),
                    'code' => $getISBN->isbn ?? $isbnDigits,
                    'code_type' => 1,
                    'publication_month' => ($getISBN->tanggal_terbit ?? '') ? date('m', strtotime($getISBN->tanggal_terbit)) : null,
                    'publication_year' => ($getISBN->tanggal_terbit ?? '') ? date('Y', strtotime($getISBN->tanggal_terbit)) : null,
                    'publication_day' => ($getISBN->tanggal_terbit ?? '') ? date('d', strtotime($getISBN->tanggal_terbit)) : null,
                    'physical_description' => json_encode($physicalDescription),
                    'sync' => 0,
                    'manual' => 1,
                    'akses' => $request->access,
                    'status' => $STATUS_DRAFT,
                    'created_by' => (int) session('id'),
                    'updated_by' => (int) session('id'),
                    'copyright' => Main::copyright($executorId ?: session('id')),
                    'worksheet_id' => 20,
                    'collection_media_id' => 141,
                    'penerbit_id' => $executorId ?: null,
                    'kabupaten_id' => $executor->CITY_ID ?? session('city_id'),
                    'title' => $getISBN->title ?? '',
                    'author' => str_replace(', ', ';', $getISBN->kepeng ?? ''),
                    'description' => $getISBN->sinopsis ?? '',
                    'edition' => $getISBN->edisi ?? '',
                    'status_upload_isbn' => 'ISBN Ditemukan',
                ]);

                if (!$createCollection) {
                    $rejectedCount++;
                    $errors[] = "[ERROR] <strong>{$basename}</strong>: Gagal membuat data draft.";
                    continue;
                }

                $collectionId   = (int) $createCollection->ID;
                $collectionSlug = (string) $createCollection->SLUG;

                $this->uploadFileToApi($collectionId, $collectionSlug, $file, $uploadType);

                $successCreate++;
                $errors[] = "[OK] <strong>{$basename}</strong>: Draft dibuat, <strong>{$uploadType}</strong> berhasil diupload.";
            } catch (\Exception $e) {
                $rejectedCount++;
                $errors[] = "[ERROR] <strong>{$basename}</strong>: " . $e->getMessage();
            }
        }

        $totalOk = $successCreate + $successUpdate;
        $messageParts = [];

        if ($successCreate > 0) $messageParts[] = "<strong>{$successCreate}</strong> draft baru dibuat";
        if ($successUpdate > 0) $messageParts[] = "<strong>{$successUpdate}</strong> draft diperbarui";
        if ($rejectedCount > 0) $messageParts[] = "<strong>{$rejectedCount}</strong> file ditolak/di-skip";

        return response()->json([
            'code'    => $totalOk > 0 ? 200 : 404,
            'logs'    => $errors,
            'message' => $messageParts,
            'errors'  => $totalOk > 0 ? [] : $errors,
        ]);
    }
    private function cleanUpOldFile($collectionId, $type)
    {
        $collectionId = (int) $collectionId;
        $targetTable  = match ($type) {
            'cover' => 'catalogcovers',
            'konten_digital' => 'catalogfiles',
            default => null,
        };

        if (!$targetTable) return;

        $oldFiles = QueryAPI::get("select id from $targetTable where e_col_id = $collectionId");

        if ($oldFiles) {
            foreach ($oldFiles as $file) {
                QueryAPI::removeFile(['type' => $type, 'id' => $file->ID]);
                QueryAPI::delete($targetTable, $file->ID);
            }
        }
    }

    private function uploadFileToApi($id, $slug, $file, $type)
    {
        $prefix = ($type == 'cover') ? 'FILE-COVER-' : 'FILE-KONTEN-';
        $resp =  QueryAPI::uploadFile([
            'type' => $type,
            'id' => $id,
            'status' => 1,
            'hash' => md5($prefix . $slug),
            'mime' => $file->getMimeType(),
            'filesize' => $file->getSize(),
            'method' => 3,
            'iszip' => false,
            'file' => $file,
        ]);
        return $resp;
    }

    public function submission()
    {
        $data = QueryAPI::get("
            select
                ec.*
            from
                e_collections ec
            join
                worksheets w on w.id = ec.worksheet_id
            join (
                select
                    cf.e_col_id,
                    cf.id,
                    row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                from
                    catalogfiles cf
            ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
            join (
                select
                    cc.e_col_id,
                    cc.id,
                    row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                from
                    catalogcovers cc
            ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
            where
                ec.deleted_at is null and
                ec.status = '4' and
                ec.created_by = " . (int) session('id') . " and
                ec.code_type = 1 and
                ec.code is not null and
                ec.kabupaten_id is not null and
                ec.publication_day is not null and
                ec.publication_month is not null and
                ec.publication_year is not null and
                ec.preview is not null and
                ec.akses is not null and
                w.category = '" . $this->worksheetCategory . "' and
                cfr.id is not null and
                ccr.id is not null
        ");

        if ($data) {
            foreach ($data as $d) {
                QueryAPI::update('e_collections', $d->ID, ['status' => 1]);
            }

            $response = [
                'code' => 200,
                'message' => 'Data berhasil diajukan'
            ];
        } else {
            $response = [
                'code' => 404,
                'message' => 'Tidak ada data yang diajukan'
            ];
        }

        return response()->json($response);
    }

    public function updateData(Request $request, $id)
    {
        $sqlCollection = "
            select
                ec.*,
                kabupaten.namakab as namakab,
                w.alias as alias_worksheet,
                w.category as category_worksheet,
                propinsi.namapropinsi as namapropinsi,
                parents.title as title_parent,
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
                cfr.method as method_catalogfiles
            from
                e_collections ec
            left join
                kabupaten on kabupaten.id = ec.kabupaten_id
            left join
                propinsi on propinsi.id = kabupaten.propinsiid
            left join
                e_collections parents on parents.id = ec.parent_id
            left join
                worksheets w on w.id = ec.worksheet_id
            left join
                (
                    select
                        cf.e_col_id, cf.id, cf.fileurl, cf.hash, cf.mime, cf.file_size, cf.method,
                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                    from
                        catalogfiles cf
                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
            left join
                (
                    select
                        cc.e_col_id, cc.id, cc.fileurl, cc.hash, cc.mime, cc.file_size, cc.method,
                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                    from
                        catalogcovers cc
                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
            where
                ec.id = $id and
                ec.deleted_at is null and
                ec.status = '4' and
                w.category = '" . $this->worksheetCategory . "' and
                ec.code_type = 1 and
                ec.code is not null
        ";

        $collection = QueryAPI::get($sqlCollection, true);

        if (!$collection) {
            abort(404);
        }

        if ($request->ajax()) {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'collection_media_id' => 'required',
                'access' => 'required',
                'file_cover' => 'nullable|image|mimes:png,jpg,jpeg|max:' . config('system.catalog_cover_max_upload'),
                'file_content' => 'nullable|file|mimes:pdf,epub,mp3,mp4,wav|max:' . config('system.catalog_content_max_upload'),
                'description' => 'required|string|min:500',
                'price' => 'required',
                'publish_time' => 'required',
                'preview' => 'required',
                'author' => 'required|array|min:1',
            ], [
                'title.required' => 'Judul tidak boleh kosong',
                'collection_media_id.required' => 'Jenis koleksi tidak boleh kosong',
                'access.required' => 'Akses tidak boleh kosong',
                'file_cover.image' => 'File cover tidak valid',
                'file_cover.mimes' => 'File cover harus png, jpg, jpeg',
                'file_cover.max' => 'File cover maksimal ' . Main::formatFileSize((int) config('system.catalog_cover_max_upload')),
                'file_content.file' => 'File konten tidak valid',
                'file_content.mimes' => 'File konten harus pdf, epub, mp3, mp4, wav',
                'file_content.max' => 'File konten maksimal ' . Main::formatFileSize((int) config('system.catalog_content_max_upload')),
                'description.required' => 'Sinopsis tidak boleh kosong',
                'description.min' => 'Sinopsis minimal 500 karakter',
                'price.required' => 'Harga jual tidak boleh kosong',
                'publish_time.required' => 'Waktu publikasi tidak boleh kosong',
                'preview.required' => 'Preview tidak boleh kosong',
                'author.required' => 'Kontributor tidak boleh kosong',
                'author.array' => 'Kontributor tidak valid',
                'author.min' => 'Kontributor minimal 1',
            ]);

            if ($validation->fails()) {
                $response = [
                    'code' => 400,
                    'error' => $validation->errors()->all(),
                ];
            } else {
                try {
                    $userId = session('id');
                    $publishTime = strtotime($request->publish_time);
                    $status = $request->param;
                    $executorId = $collection->PENERBIT_ID ?? null;
                    $executor = QueryAPI::get("select * from penerbit where id = $executorId", true);

                    $baseCollectionData = [
                        'id_old' => 0,
                        'publisher_id' => $executorId,
                        'city_id' => $executor->CITY_ID ?? session('city_id'),
                        'title_ori' => $request->title,
                        'album' => $request->album,
                        'slug' => Str::slug($request->title, '-'),
                        'series' => $request->series,
                        'serial' => $request->serial,
                        'publication_month' => date('m', $publishTime),
                        'publication_year' => date('Y', $publishTime),
                        'publication_day' => date('d', $publishTime),
                        'preview' => $request->preview,
                        'physical_description' => json_encode($request->physical_description),
                        'sync' => 0,
                        'manual' => 1,
                        'akses' => $request->access,
                        'status' => $status,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'price' => str_replace([',', '.'], '', $request->price),
                        'copyright' => Main::copyright($executorId),
                        'collection_media_id' => $request->collection_media_id,
                        'penerbit_id' => $executorId,
                        'kabupaten_id' => $executor->CITY_ID ?? session('city_id'),
                        'title' => $request->title,
                        'author' => implode(';', ($request->author ?? [])),
                        'jilid' => $request->binding,
                        'currency' => $request->currency,
                        'description' => $request->description,
                        'edition' => $request->edition,
                        'edition_date' => date('Y-m-d H:i:s', strtotime($request->edition_date)),
                        'qrcbn' => $request->qrcbn,
                    ];

                    $updateCollection = QueryAPI::update('e_collections', $id, $baseCollectionData);

                    if (!$updateCollection) {
                        throw new \Exception('Gagal membuat data koleksi');
                    }

                    $collection = QueryAPI::get($sqlCollection, true);

                    $collectionCategory = QueryAPI::get("
                        select
                            *
                        from
                            e_collection_categories
                        where
                            collection_id = $id
                    ");

                    foreach ($collectionCategory ?? [] as $cc) {
                        QueryAPI::delete('e_collection_categories', $cc->ID);
                    }

                    if ($request->category && is_array($request->category)) {
                        $categoryData = [];

                        foreach ($request->category as $categoryId) {
                            $categoryData[] = [
                                'collection_id' => $id,
                                'category_id' => $categoryId
                            ];
                        }

                        foreach ($categoryData as $data) {
                            QueryAPI::create('e_collection_categories', $data);
                        }
                    }

                    $fileCover = $request->file('file_cover');
                    $fileContent = $request->file('file_content');

                    if ($fileCover) {
                        QueryAPI::uploadFile([
                            'type' => 'cover',
                            'id' => $id,
                            'status' => 1,
                            'hash' => md5('FILE-COVER-' . ($collection->SLUG ?? '')),
                            'mime' => $fileCover->getMimeType(),
                            'filesize' => $fileCover->getSize(),
                            'method' => 3,
                            'iszip' => false,
                            'file' => $fileCover,
                        ]);
                    }

                    if ($fileContent) {
                        QueryAPI::uploadFile([
                            'type' => 'konten_digital',
                            'id' => $id,
                            'status' => 1,
                            'hash' => md5('FILE-KONTEN-' . ($collection->SLUG ?? '')),
                            'mime' => $fileContent->getMimeType(),
                            'filesize' => $fileContent->getSize(),
                            'method' => 3,
                            'iszip' => false,
                            'file' => $fileContent,
                        ]);
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
        $dataCollectionCategory = QueryAPI::get("
            select
                *
            from
                e_collection_categories
            where
                collection_id = $id
        ");

        if ($dataCollectionCategory) {
            foreach ($dataCollectionCategory as $dcc) {
                $collectionCategory[] = $dcc->CATEGORY_ID;
            }
        }

        $collectionProblemHistory = QueryAPI::get("
            select
                e_collection_problems.*,
                e_problems.name as name_problem
            from
                e_collection_problems
            left join
                e_problems on e_problems.id = e_collection_problems.problem_id
            where
                e_collection_problems.collection_id = $id
        ");

        return view('layouts.index', [
            'data' => [
                'media' => QueryAPI::get("select * from collectionmedias where (isdelete = 0 or isdelete is null) and worksheet_id in (20,142) and depositformat_code is not null") ?? [],
                'category' => QueryAPI::get("select * from e_categories where deleted_at is null") ?? [],
                'collection' => $collection,
                'collectionCategory' => $collectionCategory,
                'collectionContributor' => explode(';', ($collection->AUTHOR ?? '')),
                'collectionProblemHistory' => $collectionProblemHistory,
                'physicalDescription' => json_decode($collection->PHYSICAL_DESCRIPTION ?? ''),
                'content' => 'digital-storage-handover.single-upload-isbn-detail',
                'plugins' => [
                    'select2',
                    'daterangepicker',
                    'datatable',
                    'epubjs',
                    'videojs',
                    'pdfjs',
                    'howlerjs',
                ]
            ]
        ]);
    }

    public function destroyData(Request $request)
    {
        $id = $request->id;

        try {
            QueryAPI::update('e_collections', $id, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            $response = [
                'code' => 200,
                'message' => 'Data telah dihapus'
            ];
        } catch (\Exception $e) {
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return response()->json($response);
    }
}
