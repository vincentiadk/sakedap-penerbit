<?php

namespace App\Http\Controllers\PhysicalHandover;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use App\Helpers\RajaOngkir;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DeliveryMonitoringController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'deliveryService' => QueryAPI::get("select * from jasa_pengiriman where id != 1") ?? [],
                'content' => 'physical-handover.delivery-monitoring',
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
            'p.name',
            'l.status',
            'l.letter_number',
            'l.letter_date',
            'b.name',
            'l.receipt_no',
            'jp.name',
            'l.sender',
            'l.phone',
            null,
            null,
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $length = $start + intval($request->length ?? 10);;

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "l.status in ('DIKIRIM', 'DALAM PENGIRIMAN')";
        $whereCondition[] = "l.penerbit_id = " . $request->executor_id;
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
                penerbit_id = " . $request->executor_id . " and
                status in ('DIKIRIM', 'DALAM PENGIRIMAN') and
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
                penerbit p on p.id = l.penerbit_id
            left join
                jasa_pengiriman jp on jp.id = l.jasa_pengiriman_id
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
                            select distinct
                                l.*,
                                b.name as name_branch,
                                p.name as name_penerbit,
                                jp.name as name_jasa_pengiriman,
                                case
                                    when l.status in ('DIKIRIM', 'DALAM PENGIRIMAN')
                                    then nvl(td.total_eks_delivery, 0)
                                    else 0
                                end as total_eks_delivery,
                                case
                                    when l.status in ('DIKIRIM', 'DALAM PENGIRIMAN')
                                    then nvl(td.total_title_delivery, 0)
                                    else 0
                                end as total_title_delivery
                            from
                                letter l
                            left join
                                branchs b on b.id = l.branch_id
                            left join
                                penerbit p on p.id = l.penerbit_id
                            left join
                                jasa_pengiriman jp on jp.id = l.jasa_pengiriman_id
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
                    where
                        rownum <= $length
                )
            where
                rnum > $start
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $action = '
                    <a href="' . url('physical-handover/delivery-monitoring/detail/' . $val->LETTER_ID) . '" class="btn btn-primary btn-sm text-nowrap">
                        <i class="ph-info me-1"></i>
                        Detail
                    </a>
                    <a href="javascript:void(0);" class="btn btn-teal btn-sm text-nowrap" onclick="showData(' . $val->LETTER_ID . ')">
                        <i class="ph-notepad me-1"></i>
                        Resi
                    </a>
                    <a href="' . url('physical-handover/delivery-monitoring/print-label/' . $val->LETTER_ID) . '" class="btn btn-success btn-sm text-nowrap" target="_blank">
                        <i class="ph-printer me-1"></i>
                        Cetak Label
                    </a>
                    <a href="javascript:void(0);" class="btn btn-warning btn-sm text-nowrap" onclick="destroyData(' . $val->LETTER_ID . ')">
                        <i class="ph-info me-1"></i>
                        Hapus dan Batalkan
                    </a>
                ';

                if ($val->STATUS == 'DIKIRIM') {
                    $action .= '
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm text-nowrap" onclick="destroyData(' . $val->LETTER_ID . ')">
                            <i class="ph-trash me-1"></i>
                            Hapus
                        </a>
                    ';
                }

                $letterDate = '
                    <div>' . Carbon::parse($val->LETTER_DATE)->isoFormat('D MMM Y') . '</div>
                    <small>Jam : ' . Carbon::parse($val->LETTER_DATE)->format('H.i') . ' WIB</small>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->NAME_PENERBIT,
                    $val->STATUS,
                    $val->LETTER_NUMBER,
                    ($val->LETTER_DATE ?: null) ? $letterDate : '',
                    $val->NAME_BRANCH,
                    $val->RECEIPT_NO,
                    $val->NAME_JASA_PENGIRIMAN,
                    $val->SENDER,
                    $val->PHONE,
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

    public function showData(Request $request)
    {
        $id = $request->id;
        $data = QueryAPI::get("select * from letter where letter_id = $id", true) ?? [];

        return response()->json($data);
    }

    public function updateData(Request $request)
    {
        $id = $request->table_id;
        $validation = Validator::make($request->all(), [
            'receipt_no' => 'required',
            'delivery_service_id' => 'required',
            'delivery_fee' => 'required',
            'sender_name' => 'required',
        ], [
            'receipt_no.required' => 'No resi tidak boleh kosong',
            'delivery_service_id.required' => 'Jasa kirim tidak boleh kosong',
            'delivery_fee.required' => 'Biaya kirim tidak boleh kosong',
            'sender_name.required' => 'Nama pengirim tidak boleh kosong',
        ]);

        if ($validation->fails()) {
            $response = [
                'code' => 400,
                'error' => $validation->errors()->all(),
            ];
        } else {
            try {
                $receiptNo = $request->receipt_no;
                $deliveryServiceId = $request->delivery_service_id;
                $deliveryService = QueryAPI::get("select * from jasa_pengiriman where id = $deliveryServiceId", true);

                $buildQuery = http_build_query([
                    'awb' => $receiptNo,
                    'courier' => $deliveryService->CODE ?? ''
                ]);

                $receipt = RajaOngkir::post('track/waybill?' . $buildQuery);

                QueryAPI::update('letter', $id, [
                    'type_of_delivery' => ($deliveryService->NAME ?? null) ? 'Pos' : 'Datang Langsung',
                    'receipt_no' => $receiptNo,
                    'biaya_kirim' => $request->delivery_fee,
                    'jasa_pengiriman_id' => $deliveryServiceId,
                    'sender' => $request->sender_name,
                    'berat' => $receipt->details->weight ?? ($request->weight ?? 0),
                    'status' => 'DALAM PENGIRIMAN'
                ], false);

                $response = [
                    'code' => 200,
                    'message' => 'Data resi telah diubah'
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

    public function detail(Request $request, $id)
    {
        if (!is_numeric($id)) {
            abort(404, 'Invalid letter ID');
        }

        try {
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
                    letter.status in ('DIKIRIM', 'DALAM PENGIRIMAN') and
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
                    'content' => 'physical-handover.delivery-monitoring-detail',
                    'plugins' => [
                        'select2',
                        'datatable',
                        'lightbox',
                    ]
                ]
            ]);
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

    public function printLabel(Request $request, $id)
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
                    letter.status in ('DIKIRIM', 'DALAM PENGIRIMAN') and
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

    public function destroyData(Request $request)
    {
        $id = $request->id;

        try {
            QueryAPI::delete('letter', $id);

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
