<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\ISBN;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillISBNController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'bill-isbn',
                'plugins' => [
                    'daterangepicker',
                    'select2',
                    'datatable',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $draw = intval($request->draw ?? 0);
        $search = strtoupper($request->search['value']);
        $start = intval($request->start ?? 0);
        $length = intval($request->length ?? 10);

        $filter = [
            'start' => $start,
            'length' => $length,
            'penerbit_id' => $request->executor_id ?? session('id'),
        ];

        if ($search) {
            $filter['search'] = $search;
        }

        if ($request->title) {
            $filter['title'] = $request->title;
        }

        if ($request->author) {
            $filter['kepeng'] = $request->author;
        }

        if ($request->year) {
            $filter['tahun_terbit'] = $request->year;
        }

        if ($request->code) {
            $filter['code'] = $request->code;
        }

        if ($request->subject) {
            $filter['subjek'] = $request->subject;
        }

        if ($request->media) {
            $filter['jenis_media'] = $request->media;
        }

        if ($request->sinopsis_class) {
            $filter['sinopsis_class'] = $request->sinopsis_class;
        }

        if ($request->call_number) {
            $filter['call_number'] = $request->call_number;
        }

        if ($request->is_perpusnas) {
            $filter['is_perpusnas'] = $request->is_perpusnas == 1 ? 1 : 0;
        }

        if ($request->is_province) {
            $filter['is_province'] = $request->is_province == 1 ? 1 : 0;
        }

        if ($request->received_date_kckr) {
            $explodeDate = explode(' - ', $request->received_date_kckr);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $filter['received_date_kckr_from'] = $startDate;
            $filter['received_date_kckr_to'] = $endDate;
        }

        if ($request->received_date_province) {
            $explodeDate = explode(' - ', $request->received_date_province);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $filter['received_date_prov_from'] = $startDate;
            $filter['received_date_prov_to'] = $endDate;
        }

        $data = [];
        $result = ISBN::get('search', $filter);
        $resultData = $result->data ?? [];

        if (count($resultData) > 0) {
            foreach ($resultData as $val) {
                if (isset($val->sinopsis)) {
                    $sinopsis = '
                        <button type="button" class="btn btn-light btn-sm" onclick="onPopover(this, ' . "'$val->sinopsis'" . ')">Lihat</button>
                    ';
                } else {
                    $sinopsis = '
                        <button type="button" class="btn btn-light btn-sm" disabled>Tidak Ada</button>
                    ';
                }

                if (isset($val->kepeng)) {
                    $author = '
                        <button type="button" class="btn btn-light btn-sm" onclick="onPopover(this, ' . "'$val->kepeng'" . ')">Lihat</button>
                    ';
                } else {
                    $author = '
                        <button type="button" class="btn btn-light btn-sm" disabled>Tidak Ada</button>
                    ';
                }

                $isbn = isset($val->isbn) ? $val->isbn : '';

                $status = '
                    <button type="button" class="btn btn-danger btn-sm disabled">
                        <i class="ph-x me-1"></i>
                        Belum Diterima
                    </button>
                ';

                $letterDetail = QueryAPI::get("
                    select
                        letter_id
                    from
                        letter_detail
                    where
                        isbn = '$isbn' and
                        qty_accept > 0 and
                        rownum = 1
                ", true);

                $collection = QueryAPI::get("
                    select
                        letter_id
                    from
                        letter_detail
                    where
                        isbn = '$isbn' and
                        rownum = 1
                ", true);

                if ($letterDetail || $collection) {
                    $status = '
                        <a href="' . url('physical-handover/delivery-accept/print/' . ($letterDetail ? ($letterDetail->LETTER_ID ?? 0) : ($collection->LETTER_ID ?? 0))) . '" class="btn btn-success btn-sm" target="_blank">
                            <i class="ph-check me-1"></i>
                            Sudah Diterima
                        </a>
                    ';
                }

                $data[] = [
                    $start +  1,
                    $status,
                    isset($val->nama_penerbit) ? $val->nama_penerbit : '',
                    isset($val->title) ? $val->title : '',
                    $author,
                    isset($val->tahun_terbit) ? $val->tahun_terbit : '',
                    $isbn,
                    isset($val->jenis_media) ? ucwords($val->jenis_media) : '',
                    isset($val->jenis_pustaka) ? ucwords($val->jenis_pustaka) : '',
                    isset($val->received_date_kckr) ? Carbon::parse($val->received_date_kckr)->isoFormat('dddd, D MMMM Y') : '',
                    isset($val->received_date_prov) ? Carbon::parse($val->received_date_prov)->isoFormat('dddd, D MMMM Y') : '',
                    $sinopsis,
                    isset($val->acceptdate) ? Carbon::parse($val->acceptdate)->isoFormat('dddd, D MMMM Y') : '',
                    isset($val->createdate) ? Carbon::parse($val->createdate)->isoFormat('dddd, D MMMM Y') : '',
                    isset($val->updatedate) ? Carbon::parse($val->updatedate)->isoFormat('dddd, D MMMM Y') : '',
                ];

                $start++;
            }
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $result->recordsTotal ?? 0,
            'recordsFiltered' => $result->recordsFiltered ?? 0,
            'data' => $data,
        ]);
    }

    public function loadSummary(Request $request)
    {
        $data = ISBN::get('tagihan_isbn', [
            'penerbit_id' => $request->executor_id,
        ]);

        return response()->json($data);
    }
}
