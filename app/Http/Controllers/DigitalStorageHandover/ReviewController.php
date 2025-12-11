<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use Carbon\Carbon;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
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
                'content' => 'digital-storage-handover.review',
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
            'e_collections.updated_at',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "(e_collections.status = '1' and e_collections.deleted_at is null)";
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

            $whereCondition[] = "(e_collections.updated_at >= to_date('$startDate', 'YYYY-MM-DD') and e_collections.updated_at < to_date('$endDate', 'YYYY-MM-DD') + 1)";
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
                (e_collections.status = '1' and e_collections.deleted_at is null) and
                e_collections.penerbit_id = " . $request->executor_id . " and
                worksheets.category = '" . $this->worksheetCategory . "'
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
                )
            where
                rnum > $start and rownum <= $length
        ");

        if ($queryData) {
            foreach ($queryData as $val) {
                $action = '
                    <a href="' . url('digital-storage-handover/review/detail/' . $val->ID) . '" class="btn btn-primary btn-sm">
                        <i class="ph-info me-1"></i>
                        Detail
                    </a>
                ';

                $data[] = [
                    $start + 1,
                    $action,
                    $val->NAME_PENERBIT,
                    ($val->TITLE ?? $val->TITLE_ORI),
                    $val->NAME_MEDIA,
                    $val->CODE,
                    Carbon::parse($val->UPDATED_AT)->isoFormat('dddd, D MMMM Y'),
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
                ec.status = '1' and
                w.category = '" . $this->worksheetCategory . "'
        ";

        $collection = QueryAPI::get($sqlCollection, true);

        if (!$collection) {
            abort(404);
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
                'content' => 'digital-storage-handover.review-detail',
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
