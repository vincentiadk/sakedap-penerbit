<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RequestFileController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'request-file',
                'plugins' => [
                    'datatable',
                    'select2',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'e_collection_requests.id',
            'catalogs.title',
            'e_collection_requests.status',
            'e_collection_requests.count_download',
            'e_collection_requests.request_letter',
            null
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition = ['catalogs.penerbit_id = ' . session('id')];

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
                e_collection_requests
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                e_collection_requests
            inner join
                catalogs on catalogs.id = e_collection_requests.catalog_id
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
                                e_collection_requests.*,
                                catalogs.title as title_catalog
                            from
                                e_collection_requests
                            inner join
                                catalogs on catalogs.id = e_collection_requests.catalog_id
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
                $linkDownload = '
                    <span class="badge bg-danger">
                        <i class="ph-x me-1"></i>
                        Tidak Tersedia
                    </span>
                ';

                if ($val->STATUS == 1) {
                    $status = '
                        <span class="text-primary fw-semibold">Diajukan</span>
                    ';
                } else if ($val->STATUS == 2) {
                    $status = '
                        <span class="text-success fw-semibold">Diterima</span>
                    ';

                    $urlDownload = config('system.admin_url') . '/download/request-file?param=' . $val->CATALOG_ID . '&token=' . $val->TOKEN_DOWNLOAD;
                    $linkDownload = '
                        <a href="' . $urlDownload . '" target="_blank" class="badge bg-success">
                            <i class="ph-download me-1"></i>
                            Download
                        </a>
                    ';
                } else {
                    $status = '
                        <span class="text-danger fw-semibold">Ditolak</span>
                    ';
                }

                $letterRequest = '
                    <a href="' . url('stream-file?type=collection_request_letter&id=' . $val->ID . '&filename=' . $val->REQUEST_LETTER) . '" class="text-primary" target="_blank">
                        <i class="ph-file me-1"></i>
                        Lihat
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $val->TITLE_CATALOG,
                    $status,
                    $val->COUNT_DOWNLOAD,
                    $letterRequest,
                    Carbon::parse($val->CREATED_AT)->isoFormat('dddd, D MMMM Y'),
                    $linkDownload,
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

    public function datatableCollection(Request $request)
    {
        $column = [
            'id',
            null,
            'title',
            'isbn',
            'createdate',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition = ['penerbit_id = ' . session('id')];

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
            where
                penerbit_id = " . session('id') . "
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                catalogs
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
                                *
                            from
                                catalogs
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
                    <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="praCreate(' . $val->ID . ')">
                        <i class="ph-plus me-1"></i>
                        Ajukan
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->TITLE,
                    $val->ISBN,
                    Carbon::parse($val->CREATEDATE)->isoFormat('D MMMM Y'),
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

    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'catalog_id' => 'required',
            'request_letter' => 'required|file|mimes:pdf|max:5120',
        ], [
            'catalog_id.required' => 'Katalog tidak boleh kosong',
            'request_letter.required' => 'Surat pernyataan tidak boleh kosong',
            'request_letter.image' => 'Surat pernyataan tidak valid',
            'request_letter.mimes' => 'Surat pernyataan harus pdf',
            'request_letter.max' => 'Surat pernyataan maksimal 5MB',
        ]);

        if ($validation->fails()) {
            $response = [
                'code' => 400,
                'error' => $validation->errors()->all(),
            ];
        } else {
            try {
                $createData = QueryAPI::create('e_collection_requests', [
                    'collection_id' => $request->catalog_id,
                    'status' => 1,
                    'catalog_id' => $request->catalog_id,
                ]);

                if ($createData) {
                    $uploadFile = QueryAPI::uploadFile([
                        'type' => 'collection_request_letter',
                        'id' => $createData->ID,
                        'iszip' => 0,
                        'file' => $request->file('request_letter'),
                    ]);

                    if ($uploadFile) {
                        QueryAPI::update('e_collection_requests', $createData->ID, [
                            'request_letter' => $uploadFile->FileName
                        ], false);
                    }
                }

                $response = [
                    'code' => 200,
                    'message' => 'Data telah diajukan'
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
}
