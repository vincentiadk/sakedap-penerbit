<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use Carbon\Carbon;
use App\Helpers\ISBN;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SingleUploadISBNController extends Controller
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
                'content' => 'digital-storage-handover.single-upload-isbn',
                'plugins' => [
                    'datatable',
                    'daterangepicker',
                    'select2',
                    'fileinput',
                ]
            ]
        ]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'ec.id',
            null,
            'ec.status_upload_isbn',
            'ec.title',
            'ec.code',
            'ec.created_at',
            'ccr.id',
            'cfr.id',
            null,
            'ec.description',
            'ec.city_id',
            'ec.preview',
            'ec.access',
        ];

        $draw = intval($request->draw ?? 0);
        $start = intval($request->start ?? 0);
        $length = $start + intval($request->length ?? 0);

        $data = [];
        $search = strtoupper($request->search['value']);

        $orderBy = '';
        $order = $request->order;

        $whereClause = '';
        $whereCondition[] = "(ec.status = '4' and ec.deleted_at is null)";
        $whereCondition[] = "ec.created_by = " . session('id');
        $whereCondition[] = "ec.code_type = '1'";
        $whereCondition[] = "ec.code is not null";
        $whereCondition[] = "w.category = '" . $this->worksheetCategory . "'";

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
                (e_collections.status = '4' and e_collections.deleted_at is null) and
                e_collections.created_by = " . session('id') . " and
                worksheets.category = '" . $this->worksheetCategory . "' and
                e_collections.code_type = '1' and
                e_collections.code is not null
        ", true)->TOTAL ?? 0;

        $totalFiltered = QueryAPI::get("
            select
                count(*) as total
            from
                e_collections ec
            left join
                worksheets w on w.id = ec.worksheet_id
            left join
                (
                    select
                        cf.e_col_id, cf.id,
                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                    from
                        catalogfiles cf
                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
            left join
                (
                    select
                        cc.e_col_id, cc.id,
                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                    from
                        catalogcovers cc
                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
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
                                ec.*,
                                ccr.id as id_catalogcovers,
                                ccr.fileurl as fileurl_catalogcovers,
                                cfr.id as id_catalogfiles,
                                cfr.fileurl as fileurl_catalogfiles
                            from
                                e_collections ec
                            left join
                                worksheets w on w.id = ec.worksheet_id
                            left join
                                (
                                    select
                                        cf.e_col_id, cf.id, cf.fileurl,
                                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                                    from
                                        catalogfiles cf
                                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
                            left join
                                (
                                    select
                                        cc.e_col_id, cc.id, cc.fileurl,
                                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                                    from
                                        catalogcovers cc
                                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
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
                    <a href="' . url('digital-storage-handover/single-upload-isbn/update-data/' . $val->ID) . '" class="btn btn-warning btn-sm">
                        <i class="ph-pen me-1"></i>
                        Ubah Data
                    </a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="destroyData(' . $val->ID . ')">
                        <i class="ph-trash-simple me-1"></i>
                        Hapus Data
                    </a>
                ';

                if (($val->ID_CATALOGCOVERS ?: null)) {
                    $badgeCover = '
                        <a href="' . url('stream-file?type=cover&id=' . $val->ID_CATALOGCOVERS . '&filename=' . $val->FILEURL_CATALOGCOVERS) . '" target="_blank">Lihat File</a>
                    ';
                } else {
                    $badgeCover = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->ID_CATALOGFILES ?: null)) {
                    $badgeContent = '
                        <a href="' . url('stream-file?type=konten_digital&id=' . $val->ID_CATALOGFILES . '&filename=' . $val->FILEURL_CATALOGFILES) . '" target="_blank">Lihat File</a>
                    ';
                } else {
                    $badgeContent = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->PUBLICATION_DAY ?: null) && ($val->PUBLICATION_MONTH ?: null) && ($val->PUBLICATION_YEAR ?: null)) {
                    $badgePublish = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgePublish = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->DESCRIPTION ?: null)) {
                    $badgeDescription = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgeDescription = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->CITY_ID ?: null)) {
                    $badgeCity = '<span class="badge bg-success"><i class="ph-check"></i></span>';
                } else {
                    $badgeCity = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->PREVIEW ?: null)) {
                    $badgePreview = $val->PREVIEW;
                } else {
                    $badgePreview = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                if (($val->AKSES ?: null)) {
                    $badgeAccess = $val->AKSES;
                } else {
                    $badgeAccess = '<span class="badge bg-danger"><i class="ph-x"></i></span>';
                }

                $data[] = [
                    $start + 1,
                    $action,
                    $val->STATUS_UPLOAD_ISBN,
                    ($val->TITLE ?? $val->TITLE_ORI),
                    $val->CODE,
                    Carbon::parse($val->CREATED_AT)->isoFormat('dddd, D MMMM Y'),
                    $badgeCover,
                    $badgeContent,
                    $badgePublish,
                    $badgeDescription,
                    $badgeCity,
                    $badgePreview,
                    $badgeAccess,
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

    public function uploaded(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'mimes:jpg,jpeg,png,pdf,epub|max:204800'
        ], [
            'files.required' => 'File tidak boleh kosong',
            'files.array' => 'File harus array',
            'files.*.mimes' => 'File yang di upload harus jpg, jpeg, png, pdf, epub',
            'files.*.max' => 'Per file yang di upload maksimal 200 MB',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'error' => $validation->errors()->all(),
            ]);
        }

        $files = $request->file('files');
        $groupedFiles = [];

        foreach ($files as $file) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $key = Str::slug($filename);
            $mime = $file->getMimeType();

            if (!isset($groupedFiles[$key])) {
                $groupedFiles[$key]['original_name'] = $filename;
            }

            if ($mime === 'application/pdf') {
                $groupedFiles[$key]['pdf'] = $file;
            } else if ($mime === 'application/epub+zip' || $file->getClientOriginalExtension() === 'epub') {
                $groupedFiles[$key]['epub'] = $file;
            } else if (str_starts_with($mime, 'image/')) {
                $groupedFiles[$key]['cover'] = $file;
            }
        }

        $successCount = 0;
        $errors = [];

        foreach ($groupedFiles as $key => $group) {
            $isbn = $group['original_name'];
            $isbnReplace = str_replace(['-', '_'], '', $isbn);

            $existingData = QueryAPI::get("
                select
                    id,
                    slug
                from
                    e_collections
                where
                    replace(code, '-', '') = '$isbnReplace'
            ", true);

            if ($existingData) {
                $collectionId = $existingData->ID;
                $collectionSlug = $existingData->SLUG;
                $processed = false;

                if (isset($group['cover'])) {
                    $this->cleanUpOldFile($collectionId, 'cover');
                    $this->uploadFileToApi($collectionId, $collectionSlug, $group['cover'], 'cover');

                    $processed = true;
                }

                if (isset($group['pdf'])) {
                    $this->cleanUpOldFile($collectionId, 'konten_digital');
                    $this->uploadFileToApi($collectionId, $collectionSlug, $group['pdf'], 'konten_digital');

                    $processed = true;
                } elseif (isset($group['epub'])) {
                    $this->cleanUpOldFile($collectionId, 'konten_digital');
                    $this->uploadFileToApi($collectionId, $collectionSlug, $group['epub'], 'konten_digital');

                    $processed = true;
                }

                if ($processed) {
                    $successCount++;
                } else {
                    $errors[] = "File <strong>{$group['original_name']}</strong> dilewati: Tidak ada file valid untuk diupdate.";
                }
            } else {
                $getISBN = ISBN::get('search', ['code' => $isbnReplace], true);

                if (!$getISBN) {
                    $errors[] = "File <strong>{$group['original_name']}</strong> dilewati: Data ISBN tidak ditemukan.";

                    continue;
                }

                if (($getISBN->jenis_media ?? '') != 'cetak') {
                    $executorId = $getISBN->penerbit_id ?? null;
                    $executor = QueryAPI::get("select * from penerbit where id = $executorId", true);

                    $physicalDescription = [
                        'paging' => $getISBN->jml_hlm ?? '',
                        'paging_flag' => 'Halaman'
                    ];

                    $createCollection = QueryAPI::create('e_collections', [
                        'id_old' => 0,
                        'city_id' => $executor->CITY_ID ?? session('city_id'),
                        'publisher_id' => $executorId,
                        'title_ori' => $getISBN->title ?? '',
                        'slug' => Str::slug($getISBN->title ?? '', '-'),
                        'series' => $getISBN->seri ?? '',
                        'code' => $getISBN->isbn ?? $isbn,
                        'code_type' => 1,
                        'publication_month' => ($getISBN->tanggal_terbit ?? '') ? date('m', ($getISBN->tanggal_terbit ?? '')) : null,
                        'publication_year' => ($getISBN->tanggal_terbit ?? '') ? date('Y', ($getISBN->tanggal_terbit ?? '')) : null,
                        'publication_day' => ($getISBN->tanggal_terbit ?? '') ? date('d', ($getISBN->tanggal_terbit ?? '')) : null,
                        'physical_description' => json_encode($physicalDescription),
                        'sync' => 0,
                        'manual' => 1,
                        'akses' => $request->access,
                        'status' => 4,
                        'created_by' => session('id'),
                        'updated_by' => session('id'),
                        'copyright' => Main::copyright($executorId ?? session('id')),
                        'worksheet_id' => 20,
                        'collection_media_id' => 141,
                        'penerbit_id' => $executorId,
                        'kabupaten_id' => $executor->CITY_ID ?? session('city_id'),
                        'title' => $getISBN->title ?? '',
                        'author' => str_replace(', ', ';', ($getISBN->kepeng ?? '')),
                        'description' => $getISBN->sinopsis ?? '',
                        'edition' => $getISBN->edisi ?? '',
                        'status_upload_isbn' => 'ISBN Ditemukan'
                    ]);

                    if ($createCollection) {
                        $collectionId = $createCollection->ID;
                        $collectionSlug = $createCollection->SLUG;

                        if (isset($group['cover'])) {
                            $this->uploadFileToApi($collectionId, $collectionSlug, $group['cover'], 'cover');
                        }

                        if (isset($group['pdf'])) {
                            $this->uploadFileToApi($collectionId, $collectionSlug, $group['pdf'], 'konten_digital');
                        } else if (isset($group['epub'])) {
                            $this->uploadFileToApi($collectionId, $collectionSlug, $group['epub'], 'konten_digital');
                        }

                        $successCount++;
                    } else {
                        $errors[] = "Gagal membuat database untuk <strong>{$group['original_name']}</strong>.";
                    }
                } else {
                    $errors[] = "File <strong>{$group['original_name']}</strong> dilewati: ISBN terdaftar sebagai media cetak.";
                }
            }
        }

        $code = ($successCount > 0) ? 200 : 404;
        $message = ($successCount > 0) ? "Berhasil memproses <strong>$successCount</strong> item." : 'Tidak ada file yang berhasil diproses.';

        return response()->json([
            'code' => $code,
            'error' => $errors,
            'message' => $message
        ]);
    }

    private function cleanUpOldFile($collectionId, $type)
    {
        $targetTable = '';

        if ($type === 'cover') {
            $targetTable = 'catalogcovers';
        } else if ($type === 'konten_digital') {
            $targetTable = 'catalogfiles';
        } else {
            return;
        }

        $oldFiles = QueryAPI::get("
            select
                id
            from
                $targetTable
            where
                e_col_id = $collectionId
        ", true);

        if ($oldFiles) {
            $oldFiles = [$oldFiles];

            foreach ($oldFiles as $file) {
                QueryAPI::removeFile(['type' => $type, 'id' => $file->ID]);
                QueryAPI::delete($targetTable, $file->ID);
            }
        }
    }

    private function uploadFileToApi($id, $slug, $file, $type)
    {
        $prefix = ($type == 'cover') ? 'FILE-COVER-' : 'FILE-KONTEN-';

        return QueryAPI::uploadFile([
            'type' => $type,
            'id' => $id,
            'status' => 1,
            'hash' => md5($prefix . $slug),
            'mime' => $file->getMimeType(),
            'filesize' => $file->getSize(),
            'method' => 3,
            'iszip' => false,
            'file' => $file,
        ]);
    }

    public function submission()
    {
        $data = QueryAPI::get("
            select
                ec.*
            from
                e_collections ec
            inner join
                (
                    select
                        cf.e_col_id, cf.id,
                        row_number() over (partition by cf.e_col_id order by cf.id desc) as rn
                    from
                        catalogfiles cf
                ) cfr on cfr.e_col_id = ec.id and cfr.rn = 1
            inner join
                (
                    select
                        cc.e_col_id, cc.id,
                        row_number() over (partition by cc.e_col_id order by cc.id desc) as rn
                    from
                        catalogcovers cc
                ) ccr on ccr.e_col_id = ec.id and ccr.rn = 1
            where
                ec.deleted_at is null and
                ec.status = '4' and
                ec.created_by = " . session('id') . " and
                ec.code_type = 1 and
                ec.code is not null and
                ec.city_id is not null and
                ec.publication_day is not null and
                ec.publication_month is not null and
                ec.publication_year is not null and
                ec.preview is not null and
                ec.akses is not null and
                cfr.id is not null and
                ccr.id is not null
        ");

        if ($data) {
            foreach ($data as $d) {
                QueryAPI::update('e_collections', $d->ID, ['status' => 1]);
            }

            $response = [
                'code' => 200,
                'message' => 'Data berhasil diajukan'
            ];
        } else {
            $response = [
                'code' => 404,
                'message' => 'Tidak ada data yang diajukan'
            ];
        }

        return response()->json($response);
    }

    public function updateData(Request $request, $id)
    {
        $sqlCollection = "
            select
                ec.*,
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
                ec.status = '4' and
                w.category = '" . $this->worksheetCategory . "' and
                ec.code_type = 1 and
                ec.code is not null
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
                        'publisher_id' => $executorId,
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
                        'copyright' => Main::copyright($executorId),
                        'collection_media_id' => $request->collection_media_id,
                        'penerbit_id' => $executorId,
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
                            'hash' => md5('FILE-COVER-' . ($collection->SLUG ?? '')),
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
                            'hash' => md5('FILE-KONTEN-' . ($collection->SLUG ?? '')),
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
                'content' => 'digital-storage-handover.single-upload-isbn-detail',
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

    public function destroyData(Request $request)
    {
        $id = $request->id;

        try {
            QueryAPI::update('e_collections', $id, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

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
