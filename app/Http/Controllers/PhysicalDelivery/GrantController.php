<?php

namespace App\Http\Controllers\PhysicalDelivery;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GrantController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'deliveryService' => QueryAPI::get("select * from jasa_pengiriman") ?? [],
                'content' => 'physical-delivery.grant',
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
            'hibah_detail.id',
            'letter.letter_date',
            'hibah_detail.createdate',
            'hibah_detail.judul',
            'branchs.name',
            'jasa_pengiriman.name',
            'letter.receipt_no',
            'letter_detail.qty_hibah',
            'letter_detail.jenis_media',
            'collectionsources.name',
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
        $whereCondition[] = "letter.penerbit_id = " . session('id');

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
                hibah_detail
            left join
                letter_detail on letter_detail.letter_detail_id = hibah_detail.letter_detail_id
            left join
                letter on letter.letter_id = letter_detail.letter_id
            where
                letter.penerbit_id = " . session('id') . "
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                hibah_detail
            left join
                collectionsources on collectionsources.id = hibah_detail.source_id
            left join
                letter_detail on letter_detail.letter_detail_id = hibah_detail.letter_detail_id
            left join
                letter on letter.letter_id = letter_detail.letter_id
            left join
                jasa_pengiriman on jasa_pengiriman.id = letter.jasa_pengiriman_id
            left join
                branchs on branchs.id = letter.branch_id
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
                                hibah_detail.*,
                                collectionsources.name as name_collectionsource,
                                letter_detail.qty_hibah as qty_hibah_letter_detail,
                                letter_detail.remark as remark_letter_detail,
                                letter_detail.jenis_media as jenis_media_letter_detail,
                                jasa_pengiriman.name as name_jasa_pengiriman,
                                branchs.name as name_branch,
                                letter.receipt_no as receipt_no_letter,
                                letter.status as status_letter,
                                letter.proses_by as proses_by_letter,
                                letter.letter_date as letter_date_letter
                            from
                                hibah_detail
                            left join
                                collectionsources on collectionsources.id = hibah_detail.source_id
                            left join
                                letter_detail on letter_detail.letter_detail_id = hibah_detail.letter_detail_id
                            left join
                                letter on letter.letter_id = letter_detail.letter_id
                            left join
                                jasa_pengiriman on jasa_pengiriman.id = letter.jasa_pengiriman_id
                            left join
                                branchs on branchs.id = letter.branch_id
                            $whereClause
                            $orderBy
                        ) data
                )
            where
                rnum > $start and rownum <= $length
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $dataRemark = explode(';', $val->REMARK_LETTER_DETAIL ?? '');
                $listRemark = '';

                if ($dataRemark) {
                    foreach ($dataRemark as $key => $dr) {
                        $listRemark .= '<div>' . $key + 1 . '. ' . $dr . '</div>';
                    }
                }

                $remark = '
                    <button type="button" class="btn btn-light btn-sm" onclick="onPopover(this, ' . "'$listRemark'" . ')">Lihat</button>
                ';

                $data[] = [
                    $start + 1,
                    Carbon::parse($val->LETTER_DATE_LETTER)->isoFormat('dddd, D MMMM Y'),
                    Carbon::parse($val->CREATEDATE)->isoFormat('dddd, D MMMM Y'),
                    $val->JUDUL,
                    $val->NAME_BRANCH,
                    $val->NAME_JASA_PENGIRIMAN,
                    $val->RECEIPT_NO_LETTER,
                    $val->QTY_HIBAH_LETTER_DETAIL,
                    $val->JENIS_MEDIA_LETTER_DETAIL,
                    $val->NAME_COLLECTIONSOURCE,
                    $remark,
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
}
