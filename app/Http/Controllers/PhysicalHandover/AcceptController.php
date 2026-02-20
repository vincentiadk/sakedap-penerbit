<?php

namespace App\Http\Controllers\PhysicalHandover;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcceptController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'deliveryService' => QueryAPI::get("select * from jasa_pengiriman") ?? [],
                'content' => 'physical-handover.accept',
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
            'letter_detail.letter_detail_id',
            'penerbit.name',
            'letter.accept_date',
            'letter.letter_date',
            'letter_detail.title',
            'branchs.name',
            'jasa_pengiriman.name',
            'letter.receipt_no',
            'letter_detail.qty_accept',
            'letter_detail.jenis_media',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $length = $start + intval($request->length ?? 10);;

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "letter.status in ('DITERIMA PENUH', 'DITERIMA PARSIAL', 'CEK FISIK', 'TERKIRIM', 'DITERIMA')";
        $whereCondition[] = "(letter_detail.qty_accept > 0 OR letter_detail.qty_accept is null )";
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
                letter.status in ('DITERIMA PENUH', 'DITERIMA PARSIAL', 'CEK FISIK', 'TERKIRIM', 'DITERIMA') and
                (letter_detail.qty_accept > 0 or letter_detail.qty_accept is null) and
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
                                letter.receipt_no as receipt_no_letter,
                                letter.status as status_letter,
                                letter.accept_date as accept_date_letter,
                                letter.letter_date as letter_date_letter,
                                penerbit.name as name_penerbit
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
                    where
                        rownum <= $length
                )
            where
                rnum > $start
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $letterDate = '
                    <div>' . Carbon::parse($val->LETTER_DATE_LETTER)->isoFormat('D MMM Y') . '</div>
                    <small>Jam : ' . Carbon::parse($val->LETTER_DATE_LETTER)->format('H.i') . ' WIB</small>
                ';

                $acceptDate = '
                    <div>' . Carbon::parse($val->ACCEPT_DATE_LETTER)->isoFormat('D MMM Y') . '</div>
                    <small>Jam : ' . Carbon::parse($val->ACCEPT_DATE_LETTER)->format('H.i') . ' WIB</small>
                ';
                $identifier = "";
                if ($val->ISBN != "") {
                    $identifier .= "<br/>ISBN : " . $val->ISBN;
                }
                if ($val->ISSN != "") {
                    $identifier .= "<br/>ISSN : " . $val->ISSN;
                }
                if ($val->QRCBN != "") {
                    $identifier .= "<br/>QRCBN : " . $val->QRCBN;
                }
                if ($val->ISRC != "") {
                    $identifier .= "<br/>ISRC : " . $val->ISRC;
                }
                $data[] = [
                    $start + 1,
                    $val->NAME_PENERBIT,
                    ($val->LETTER_DATE_LETTER ?: null) ? $letterDate : '',
                    ($val->ACCEPT_DATE_LETTER ?: null) ? $acceptDate : '',
                    $val->TITLE . $identifier,
                    $val->NAME_BRANCH,
                    $val->NAME_JASA_PENGIRIMAN,
                    $val->RECEIPT_NO_LETTER,
                    $val->QTY_ACCEPT,
                    $val->JENIS_MEDIA,
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
