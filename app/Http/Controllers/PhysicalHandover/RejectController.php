<?php

namespace App\Http\Controllers\PhysicalHandover;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RejectController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'deliveryService' => QueryAPI::get("select * from jasa_pengiriman") ?? [],
                'content' => 'physical-handover.reject',
                'plugins' => [
                    'datatable',
                    'select2',
                    'daterangepicker',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            null,
            'letter_detail.letter_detail_id',
            null,
            'penerbit.name',
            'letter.accept_date',
            'letter.letter_date',
            'letter_detail.title',
            'branchs.name',
            'jasa_pengiriman.name',
            'letter.receipt_no',
            'letter_detail.qty_reject',
            'letter_detail.jenis_media',
            'letter_detail.remark',
            'letter.proses_by',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "letter.status in ('DITERIMA PENUH', 'DITERIMA PARSIAL', 'CEK FISIK', 'TERKIRIM')";
        $whereCondition[] = "letter_detail.qty_hibah is null";
        $whereCondition[] = "letter_detail.qty_retur is null";
        $whereCondition[] = "letter_detail.qty_reject > 0";
        $whereCondition[] = "letter.penerbit_id = " . $request->executor_id;

        if ($request->delivery_service_id) {
            $whereCondition[] = "letter.jasa_pengiriman_id = $request->delivery_service_id";
        }

        if ($request->date) {
            $explodeDate = explode(' - ', $request->date);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $whereCondition[] = "(letter.$request->date_type >= to_date('$startDate', 'YYYY-MM-DD') and letter.$request->date_type < to_date('$endDate', 'YYYY-MM-DD') + 1)";
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
                letter_detail
            left join
                letter on letter.letter_id = letter_detail.letter_id
            where
                letter.status in ('DITERIMA PENUH', 'DITERIMA PARSIAL', 'CEK FISIK', 'TERKIRIM') and
                letter_detail.qty_hibah is null and
                letter_detail.qty_retur is null and
                letter_detail.qty_reject > 0 and
                letter.penerbit_id = " . $request->executor_id . "
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                letter_detail
            left join
                letter on letter.letter_id = letter_detail.letter_id
            left join
                jasa_pengiriman on jasa_pengiriman.id = letter.jasa_pengiriman_id
            left join
                branchs on branchs.id = letter.branch_id
            left join
                penerbit on penerbit.id = letter.penerbit_id
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
                                letter_detail.*,
                                jasa_pengiriman.name as name_jasa_pengiriman,
                                branchs.name as name_branch,
                                penerbit.name as name_penerbit,
                                letter.receipt_no as receipt_no_letter,
                                letter.status as status_letter,
                                letter.proses_by as proses_by_letter,
                                letter.accept_date as accept_date_letter,
                                letter.letter_date as letter_date_letter
                            from
                                letter_detail
                            left join
                                letter on letter.letter_id = letter_detail.letter_id
                            left join
                                jasa_pengiriman on jasa_pengiriman.id = letter.jasa_pengiriman_id
                            left join
                                branchs on branchs.id = letter.branch_id
                            left join
                                penerbit on penerbit.id = letter.penerbit_id
                            $whereClause
                            $orderBy
                        ) data
                )
            where
                rnum > $start and rownum <= $length
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $action = '
                    <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="grant(' . $val->LETTER_DETAIL_ID . ')">
                        <i class="ph-gift me-1"></i>
                        Hibahkan
                    </a>
                    <a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="retur(' . $val->LETTER_DETAIL_ID . ')">
                        <i class="ph-cube me-1"></i>
                        Ambil Kembali
                    </a>
                ';

                $dataRemark = explode(';', $val->REMARK ?? '');
                $listRemark = '';

                if ($dataRemark) {
                    foreach ($dataRemark as $key => $dr) {
                        $listRemark .= '<div>' . $key + 1 . '. ' . $dr . '</div>';
                    }
                }

                $inputHidden = '
                    <input type="hidden" name="data" data-id="' . $val->LETTER_DETAIL_ID . '" data-title="' . $val->TITLE . '" data-qty-reject="' . $val->QTY_REJECT . '" data-receipt="' . $val->RECEIPT_NO_LETTER . '">
                ';

                $timeAutoGrant = '';
                $acceptDate = $val->ACCEPT_DATE_LETTER;

                if ($acceptDate) {
                    $future = Carbon::parse($acceptDate)->addDays(config('system.limit_grant'));
                    $timeAutoGrant = $future->diffForHumans();
                }

                $data[] = [
                    $inputHidden,
                    $start + 1,
                    $action,
                    $val->NAME_PENERBIT,
                    $timeAutoGrant,
                    Carbon::parse($val->LETTER_DATE_LETTER)->isoFormat('D MMMM Y'),
                    $val->TITLE,
                    $val->NAME_BRANCH,
                    $val->NAME_JASA_PENGIRIMAN,
                    $val->RECEIPT_NO_LETTER,
                    $val->QTY_REJECT,
                    $val->JENIS_MEDIA,
                    $listRemark,
                    $val->PROSES_BY_LETTER,
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

    public function grant(Request $request)
    {
        $id = $request->id ?? [];
        $idImplode = implode(',', $id);

        $dataLetterDetail = QueryAPI::get("
            select
                *
            from
                letter_detail
            where
                letter_detail_id in ($idImplode)
        ");

        if ($dataLetterDetail) {
            foreach ($dataLetterDetail as $dld) {
                QueryAPI::update('letter_detail', $dld->LETTER_DETAIL_ID, [
                    'qty_hibah' => $dld->QTY_REJECT,
                    'qty_retur' => null,
                    'diambil' => null,
                ], false);

                QueryAPI::create('hibah_detail', [
                    'judul' => $dld->TITLE,
                    'penerbit' => $dld->PUBLISHER,
                    'isbn' => $dld->ISBN,
                    'tahun_terbit' => $dld->PUBLISH_YEAR,
                    'jumlah_eksemplar' => $dld->QTY_REJECT,
                    'harga' => $dld->PRICE,
                    'total_nilai' => (float) ($dld->PRICE ?? 0) * (float) ($dld->QTY_REJECT ?? 0),
                    'createby' => session('username'),
                    'createdate' => date('Y-m-d H:i:s'),
                    'createterminal' => $request->ip(),
                    'updateby' => session('username'),
                    'updatedate' => date('Y-m-d H:i:s'),
                    'updateterminal' => $request->ip(),
                    'deskripsi_fisik' => $dld->DESKRIPSIFISIK,
                    'jenis_isi' => $dld->JENIS_ISI,
                    'jenis_wadah' => $dld->JENIS_WADAH,
                    'jenis_media' => $dld->JENIS_MEDIA,
                    'source_id' => 6,
                    'source_sub_id' => 3,
                    'ketersediaan_id' => 1,
                    'partner_id' => 9687,
                    'kala_terbit' => $dld->KALA_TERBIT,
                    'letter_detail_id' => $dld->LETTER_DETAIL_ID,
                ], false);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'Koleksi berhasil dihibahkan'
        ]);
    }

    public function retur(Request $request)
    {
        $id = $request->id ?? [];
        $idImplode = implode(',', $id);

        $dataLetterDetail = QueryAPI::get("
            select
                *
            from
                letter_detail
            where
                letter_detail_id in ($idImplode)
        ");

        if ($dataLetterDetail) {
            $planning = null;
            $planningValue = $request->retur_planning;

            if ($planningValue) {
                $planning = 'Diambil pada ' . Carbon::parse($planningValue)->isoFormat('D MMMM Y') . ', pada jam ' . Carbon::parse($planningValue)->format('H.i');
            }

            foreach ($dataLetterDetail as $dld) {
                QueryAPI::update('letter_detail', $dld->LETTER_DETAIL_ID, [
                    'qty_retur' => $dld->QTY_REJECT,
                    'qty_hibah' => null,
                    'diambil' => 0,
                    'rencana_ambil' => $planning,
                    'kontak' => $request->contact,
                    'nama_pengambil' => $request->retur_name,
                ], false);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'Koleksi berhasil dikembalikan'
        ]);
    }
}
