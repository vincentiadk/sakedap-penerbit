<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    private $worksheetCategoryAnalog;
    private $worksheetCategoryDigital;
    private $worksheetCategoryPrinted;

    public function __construct()
    {
        $this->worksheetCategoryAnalog = Main::COLLECTION_ANALOG;
        $this->worksheetCategoryDigital = Main::COLLECTION_DIGITAL;
        $this->worksheetCategoryPrinted = Main::COLLECTION_PRINTED;
    }

    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'dashboard',
                'plugins' => [
                    'daterangepicker',
                    'echart',
                ]
            ]
        ]);
    }

    public function dataMediaType(Request $request)
    {
        $parts = explode(' - ', $request->date);

        if (count($parts) < 2) {
            return response()->json([]);
        }

        try {
            $startDate = Carbon::parse($parts[0])->format('Y-m-d');
            $endDate = Carbon::parse($parts[1])->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $executorId = (int) session('id');

        if (!$executorId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $catDigital = addslashes($this->worksheetCategoryDigital);
        $catPrinted = addslashes($this->worksheetCategoryPrinted);
        $catAnalog = addslashes($this->worksheetCategoryAnalog);

        $query = "
            select
                cm.name,
                count(ec.id) as total
            from
                collectionmedias cm
            inner join
                worksheets w on w.id = cm.worksheet_id
                and w.category in ('$catDigital', '$catPrinted', '$catAnalog')
            inner join
                e_collections ec on ec.collection_media_id = cm.id
                and ec.created_at >= to_date('$startDate', 'YYYY-MM-DD')
                and ec.created_at <= to_date('$endDate', 'YYYY-MM-DD') + 1
                and ec.penerbit_id = $executorId
            group by
                cm.name
            order by
                cm.name
        ";

        $data = QueryAPI::get($query);

        if (empty($data)) {
            return response()->json([]);
        }

        $response = [];

        foreach ($data as $item) {
            if (isset($item->TOTAL) && $item->TOTAL > 0) {
                $response[] = [
                    'name'  => $item->NAME ?? 'Unknown',
                    'value' => (int) $item->TOTAL
                ];
            }
        }

        return response()->json($response);
    }

    public function dataWorksheet(Request $request)
    {
        $parts = explode(' - ', $request->date);

        if (count($parts) < 2) {
            return response()->json([]);
        }

        try {
            $startDate = Carbon::parse($parts[0])->format('Y-m-d');
            $endDate = Carbon::parse($parts[1])->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $executorId = (int) session('id');

        if (!$executorId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $catDigital = addslashes($this->worksheetCategoryDigital);
        $catPrinted = addslashes($this->worksheetCategoryPrinted);
        $catAnalog = addslashes($this->worksheetCategoryAnalog);

        $query = "
            select
                w.name,
                count(ec.id) as total
            from
                worksheets w
            inner join
                e_collections ec on ec.worksheet_id = w.id
            where
                w.category in ('$catDigital', '$catPrinted', '$catAnalog')
                and ec.created_at >= to_date('$startDate', 'YYYY-MM-DD')
                and ec.created_at <= to_date('$endDate', 'YYYY-MM-DD') + 1
                and ec.penerbit_id = $executorId
            group by
                w.name
            order by
                w.name
        ";

        $data = QueryAPI::get($query);

        if (empty($data)) {
            return response()->json([]);
        }

        $response = [];

        foreach ($data as $item) {
            if (isset($item->TOTAL) && $item->TOTAL > 0) {
                $response[] = [
                    'name'  => $item->NAME ?? 'Unknown',
                    'value' => (int) $item->TOTAL
                ];
            }
        }

        return response()->json($response);
    }

    public function dataCollectionStatus(Request $request)
    {
        $parts = explode(' - ', $request->date);

        if (count($parts) < 2) {
            return response()->json(['label' => [], 'data' => []]);
        }

        try {
            $startDate = Carbon::parse($parts[0])->format('Y-m-d');
            $endDate = Carbon::parse($parts[1])->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $executorId = (int) session('id');

        if (!$executorId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = "
            select
                sum(case when status = '1' then 1 else 0 end) as total_1,
                sum(case when status = '2' then 1 else 0 end) as total_2,
                sum(case when status = '3' then 1 else 0 end) as total_3,
                sum(case when status = '5' then 1 else 0 end) as total_5
            from
                e_collections
            where
                created_at >= to_date('$startDate', 'YYYY-MM-DD')
                and created_at <= to_date('$endDate', 'YYYY-MM-DD') + 1
                and penerbit_id = $executorId
        ";

        $data = QueryAPI::get($query, true);

        $response = [
            'label' => [
                'Ditinjau',
                'Diterima',
                'Bermasalah',
                'Ditolak',
            ],
            'data' => [
                (int) ($data->TOTAL_1 ?? 0),
                (int) ($data->TOTAL_2 ?? 0),
                (int) ($data->TOTAL_3 ?? 0),
                (int) ($data->TOTAL_5 ?? 0),
            ]
        ];

        return response()->json($response);
    }

    public function dataTotalWorks(Request $request)
    {
        $parts = explode(' - ', $request->date);

        if (count($parts) < 2) {
            return response()->json([
                'TOTAL_DIGITAL' => 0,
                'TOTAL_ANALOG' => 0,
                'TOTAL_PRINTED' => 0
            ]);
        }

        try {
            $startDate = Carbon::parse($parts[0])->format('Y-m-d');
            $endDate = Carbon::parse($parts[1])->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $executorId = (int) session('id');

        if (!$executorId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $catDigital = addslashes($this->worksheetCategoryDigital);
        $catAnalog = addslashes($this->worksheetCategoryAnalog);
        $catPrinted = addslashes($this->worksheetCategoryPrinted);

        $totalDigital = "
            select
                sum(case when w.category = '$catDigital' then 1 else 0 end) as total_digital
            from
                e_collections ec
            inner join
                worksheets w on w.id = ec.worksheet_id
                and w.category in ('$catDigital')
            where
                ec.created_at >= to_date('$startDate', 'YYYY-MM-DD')
                and ec.created_at <= to_date('$endDate', 'YYYY-MM-DD') + 1
                and ec.penerbit_id = $executorId
        ";

        $totalAnalogPrinted = "
            select
                sum(case when w.category = '$catAnalog' then 1 else 0 end) as total_analog,
                sum(case when w.category = '$catPrinted' then 1 else 0 end) as total_printed
            from
                collections c
            inner join
                worksheets w on w.id = c.worksheet_id
                and w.category in ('$catAnalog', '$catPrinted')
            where
                c.createdate >= to_date('$startDate', 'YYYY-MM-DD')
                and c.createdate <= to_date('$endDate', 'YYYY-MM-DD') + 1
                and c.penerbit_id = $executorId
        ";

        $dataDigital = QueryAPI::get($totalDigital, true);
        $dataAnalogPrinted = QueryAPI::get($totalAnalogPrinted, true);

        $response = [
            'TOTAL_DIGITAL' => (int) ($dataDigital->TOTAL_DIGITAL ?? 0),
            'TOTAL_ANALOG'  => (int) ($dataAnalogPrinted->TOTAL_ANALOG ?? 0),
            'TOTAL_PRINTED' => (int) ($dataAnalogPrinted->TOTAL_PRINTED ?? 0),
        ];

        return response()->json($response);
    }

    public function dataActivity()
    {
        $username = session('username');

        if (!$username) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $username = addslashes($username);

        $query = "
            select
                *
            from (
                select
                    *
                from
                    historydata
                where
                    actionby = '$username'
                order by
                    actiondate desc
            )
            where
                rownum <= 10
        ";

        $data = QueryAPI::get($query);

        return response()->json($data ?? []);
    }
}
