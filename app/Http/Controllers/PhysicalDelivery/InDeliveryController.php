<?php

namespace App\Http\Controllers\PhysicalDelivery;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use App\Helpers\RajaOngkir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InDeliveryController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'deliveryService' => QueryAPI::get("select * from jasa_pengiriman") ?? [],
                'content' => 'physical-delivery.in-delivery',
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
            'l.letter_id',
            null,
            'l.letter_number',
            'l.letter_date',
            'l.receipt_no',
            'jp.name',
            'b.name',
            null,
            null,
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "l.status in ('DALAM PENGIRIMAN')";
        $whereCondition[] = "l.penerbit_id = " . session('id');

        if ($request->receipt_no) {
            $receiptNo = strtoupper($request->receipt_no);
            $whereCondition[] = "upper(l.receipt_no) like '%$receiptNo%'";
        }

        if ($request->delivery_service_id) {
            $whereCondition[] = "l.jasa_pengiriman_id = $request->delivery_service_id";
        }

        if ($request->branch_id) {
            $whereCondition[] = "l.branch_id = $request->branch_id";
        }

        if ($request->date) {
            $explodeDate = explode(' - ', $request->date);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $whereCondition[] = "(l.$request->date_type >= to_date('$startDate', 'YYYY-MM-DD') and l.$request->date_type < to_date('$endDate', 'YYYY-MM-DD') + 1)";
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
                letter
            where
                status in ('DALAM PENGIRIMAN') and
                penerbit_id = " . session('id') . "
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                letter l
            left join
                jasa_pengiriman jp on jp.id = l.jasa_pengiriman_id
            left join
                branchs b on b.id = l.branch_id
            $whereClause
        ", true)->TOTAL ?? 0;

        $queryData = QueryAPI::get("
            select
                *
            from
                (
                    select
                        rownum as rnum,
                        data.*
                    from
                        (
                            select
                                l.letter_id,
                                l.letter_number,
                                l.letter_date,
                                l.receipt_no,
                                b.name as name_branch,
                                jp.name as name_jasa_pengiriman,
                                case
                                    when l.status in ('DALAM PENGIRIMAN')
                                    then coalesce(td.total_eks_delivery, 0)
                                    else 0
                                end as total_eks_delivery,
                                case
                                    when l.status in ('DALAM PENGIRIMAN')
                                    then coalesce(td.total_title_delivery, 0)
                                    else 0
                                end as total_title_delivery
                            from
                                letter l
                            left join
                                jasa_pengiriman jp on jp.id = l.jasa_pengiriman_id
                            left join
                                branchs b on b.id = l.branch_id
                            left join
                                (
                                    select
                                        letter_id,
                                        sum(copy) as total_eks_delivery,
                                        sum(quantity) as total_title_delivery
                                    from
                                        letter_detail
                                    group by
                                        letter_id
                                ) td on td.letter_id = l.letter_id
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
                    <a href="' . url('physical-delivery/in-delivery/detail/' . $val->LETTER_ID) . '" class="btn btn-primary btn-sm text-nowrap">
                        <i class="ph-info me-1"></i>
                        Detail
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->LETTER_NUMBER,
                    Carbon::parse($val->LETTER_DATE)->isoFormat('D MMMM Y'),
                    $val->RECEIPT_NO,
                    $val->NAME_JASA_PENGIRIMAN,
                    $val->NAME_BRANCH,
                    $val->TOTAL_TITLE_DELIVERY,
                    $val->TOTAL_EKS_DELIVERY,
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

    public function detail($id)
    {
        $letterSql = "
            select
                letter.*,
                jasa_pengiriman.name as name_jasa_pengiriman,
                jasa_pengiriman.code as code_jasa_pengiriman,
                branchs.name as name_branch
            from
                letter
            left join
                jasa_pengiriman on jasa_pengiriman.id = letter.jasa_pengiriman_id
            left join
                branchs on branchs.id = letter.branch_id
            where
                letter.letter_id = $id and
                letter.status in ('DALAM PENGIRIMAN') and
                penerbit_id = " . session('id') . "
        ";

        $letter = QueryAPI::get($letterSql, true);

        if (!$letter) {
            abort(404, 'Letter not found');
        }

        $letterDetail = QueryAPI::get("
            select
                *
            from
                letter_detail
            where
                letter_id = $id
        ");

        $buildQuery = http_build_query([
            'awb' => $letter->RECEIPT_NO ?? '',
            'courier' => $letter->CODE_JASA_PENGIRIMAN ?? ''
        ]);

        $receipt = RajaOngkir::post('track/waybill?' . $buildQuery);

        return view('layouts.index', [
            'data' => [
                'letter' => $letter,
                'letterDetail' => $letterDetail,
                'receipt' => $receipt,
                'content' => 'physical-delivery.in-delivery-detail',
                'plugins' => [
                    'datatable',
                    'lightbox',
                ]
            ]
        ]);
    }
}
