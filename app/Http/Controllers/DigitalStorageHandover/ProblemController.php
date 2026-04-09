<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use Carbon\Carbon;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProblemController extends Controller
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
                'media' => QueryAPI::get("select * from collectionmedias where (isdelete = 0 or isdelete is null) and worksheet_id in (20,142) and depositformat_code is not null") ?? [],
                'content' => 'digital-storage-handover.problem',
                'plugins' => [
                    'datatable',
                    'daterangepicker',
                    'select2',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'e_collections.id',
            null,
            'penerbit.name',
            'e_collections.title',
            'collectionmedias.name',
            'e_collections.code',
            null,
            'e_collections.problem',
            'e_collections.rejected_at',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $length = $start + intval($request->length ?? 10);;

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "(e_collections.status = '3' and e_collections.deleted_at is null)";
        $whereCondition[] = "e_collections.penerbit_id = " . $request->executor_id;
        $whereCondition[] = "worksheets.category = '" . $this->worksheetCategory . "'";

        if ($request->title) {
            $title = strtoupper($request->title);
            $whereCondition[] = "(upper(e_collections.title_ori) like '%$title%' or upper(e_collections.title) like '%$title%')";
        }

        if ($request->code) {
            $code = str_replace('-', '', $request->code);
            $whereCondition[] = "e_collections.code = '$code'";
        }

        if ($request->year) {
            $whereCondition[] = "e_collections.publication_year = $request->year";
        }

        if ($request->media_id) {
            $whereCondition[] = "e_collections.collection_media_id = $request->media_id";
        }

        if ($request->date) {
            $explodeDate = explode(' - ', $request->date);
            $startDate = Carbon::parse($explodeDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($explodeDate[1])->format('Y-m-d');

            $whereCondition[] = "(e_collections.rejected_at >= to_date('$startDate', 'YYYY-MM-DD') and e_collections.rejected_at < to_date('$endDate', 'YYYY-MM-DD') + 1)";
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
                e_collections
            left join
                worksheets on worksheets.id = e_collections.worksheet_id
            where
                (e_collections.status = '3' and e_collections.deleted_at is null) and
                e_collections.penerbit_id = " . $request->executor_id . " and
                e_collections.worksheets.category = '" . $this->worksheetCategory . "'
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                e_collections
            left join
                kabupaten on kabupaten.id = e_collections.kabupaten_id
            left join
                worksheets on worksheets.id = e_collections.worksheet_id
            left join
                collectionmedias on collectionmedias.id = e_collections.collection_media_id
            left join
                penerbit on penerbit.id = e_collections.penerbit_id
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
                                e_collections.*,
                                collectionmedias.name as name_media,
                                penerbit.name as name_penerbit
                            from
                                e_collections
                            left join
                                kabupaten on kabupaten.id = e_collections.kabupaten_id
                            left join
                                worksheets on worksheets.id = e_collections.worksheet_id
                            left join
                                collectionmedias on collectionmedias.id = e_collections.collection_media_id
                            left join
                                penerbit on penerbit.id = e_collections.penerbit_id
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
                    <a href="' . url('digital-storage-handover/problem/detail/' . $val->ID) . '" class="btn btn-primary btn-sm">
                        <i class="ph-info me-1"></i>
                        Detail
                    </a>
                ';

                $listProblem = '';
                $dataProblem = QueryAPI::get("
                    select
                        e_problems.name as name_problem
                    from
                        e_collection_problems
                    join
                        e_problems on e_problems.id = e_collection_problems.problem_id
                    where
                        e_collection_problems.collection_id = $val->ID
                ");

                if ($dataProblem) {
                    foreach ($dataProblem as $dp) {
                        $listProblem .= '<li>' . $dp->NAME_PROBLEM . '</li>';
                    }
                }

                $listProblem = '
                    <ul class="p-0 mb-0 ps-2">
                        ' . $listProblem . '
                    </ul>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->NAME_PENERBIT,
                    ($val->TITLE ?? $val->TITLE_ORI),
                    $val->NAME_MEDIA,
                    $val->CODE,
                    $listProblem,
                    $val->PROBLEM,
                    Carbon::parse($val->REJECTED_AT)->isoFormat('dddd, D MMMM Y'),
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

    public function detail(Request $request, $id)
    {
        $sqlCollection = "
            select
                ec.*,
                penerbit.name as name_penerbit,
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
                penerbit on penerbit.id = ec.penerbit_id
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
                ec.status = '3' and
                w.category = '" . $this->worksheetCategory . "'
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
                        'collection_media_id' => $request->collection_media_id,
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
                            'hash' => md5_file($fileCover->getRealPath()),
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
                            'hash' => md5_file($fileContent->getRealPath()),
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
                'content' => 'digital-storage-handover.problem-detail',
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
}
