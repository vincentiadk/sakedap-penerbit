<?php

namespace App\Http\Controllers\PhysicalDelivery;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PrintLabelController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'physical-delivery.print-label',
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
            'b.name',
            'l.letter_number',
            'l.letter_date',
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
        $whereCondition[] = "l.status in ('DIKIRIM')";
        $whereCondition[] = "l.penerbit_id = " . session('id');
        $whereCondition[] = "l.order_no is null";

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
                penerbit_id = " . session('id') . " and
                status in ('DIKIRIM') and
                order_no is null
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(distinct l.letter_id) as total
            from
                letter l
            left join
                branchs b on b.id = l.branch_id
            left join
                letter_detail ld on ld.letter_id = l.letter_id
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
                                b.name as name_branch,
                                case
                                    when l.status in ('DIKIRIM')
                                    then coalesce(td.total_eks_delivery, 0)
                                    else 0
                                end as total_eks_delivery,
                                case
                                    when l.status in ('DIKIRIM')
                                    then coalesce(td.total_title_delivery, 0)
                                    else 0
                                end as total_title_delivery
                            from
                                letter l
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
                            left join
                                letter_detail ld on ld.letter_id = l.letter_id
                            $whereClause
                            $orderBy
                        ) data
                )
            where
                rnum > $start and rnum <= $length
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $action = '
                    <a href="' . url('physical-delivery/print-label/print/' . $val->LETTER_ID) . '" class="btn btn-success btn-sm" target="_blank">
                        <i class="ph-printer me-1"></i>
                        Cetak
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->LETTER_NUMBER,
                    Carbon::parse($val->LETTER_DATE)->isoFormat('D MMMM Y'),
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

    public function print(Request $request, $id)
    {
        if (!is_numeric($id)) {
            abort(404, 'Invalid letter ID');
        }

        try {
            $letterSql = "
                select
                    letter.*,
                    branchs.name as branch_name,
                    branchs.alamat as branch_alamat,
                    branchs.phone as branch_phone,
                    branchs.kode_pos as branch_kode_pos,
                    propinsi.namapropinsi as namapropinsi
                from
                    letter
                left join
                    branchs on branchs.id = letter.branch_id
                left join
                    propinsi on propinsi.id = branchs.province_id
                where
                    letter.letter_id = $id and
                    letter.penerbit_id = " . session('id') . " and
                    letter.status in ('DIKIRIM') and
                    letter.order_no is null
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
            ", false);

            $pdf = Pdf::setOptions([
                'dpi' => 61,
                'adminUsername' => session('username')
            ])->loadView('pdf.delivery-label', [
                'title' => 'Label Pengiriman - ' . $id,
                'letter' => $letter,
                'letterDetail' => $letterDetail,
            ])->setWarnings(false);

            return $pdf->stream('Label Pengiriman - ' . $id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error in detail function: ' . $e->getMessage(), [
                'letter_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
                ], 500);
            }

            abort(500, 'Terjadi kesalahan sistem');
        }
    }
}
