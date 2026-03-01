<?php

namespace App\Http\Controllers\DigitalStorageHandover;

use App\Helpers\ISBN;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SingleUploadNonISBNController extends Controller
{
    private $worksheetCategory;

    public function __construct()
    {
        $this->worksheetCategory = Main::COLLECTION_DIGITAL;
    }

    public function index(Request $request)
    {
        $uploadIDCover = $request->upload_id_cover;
        $uploadIDContent = $request->upload_id_content;

        return view('layouts.index', [
            'data' => [
                'worksheet' => QueryAPI::get("select * from worksheets where category = '$this->worksheetCategory'") ?? [],
                'media' => QueryAPI::get("select * from collectionmedias where (isdelete = 0 or isdelete is null) and worksheet_id in (20,142) and depositformat_code is not null") ?? [],
                'category' => QueryAPI::get("select * from e_categories where deleted_at is null") ?? [],
                'uploadIDCover' => $uploadIDCover,
                'uploadIDContent' => $uploadIDContent,
                'content' => 'digital-storage-handover.single-upload-non-isbn',
                'plugins' => [
                    'select2',
                    'daterangepicker',
                    'datatable',
                    'fileinput',
                ]
            ]
        ]);
    }

    public function catalogParent(Request $request)
    {
        $id = $request->id;
        $data = QueryAPI::get("
            select
                catalogs.*,
                penerbit.name as name_penerbit,
                kabupaten.namakab as namakab,
                propinsi.namapropinsi as namapropinsi,
                e_collections.code_type as code_type_e_collection,
                e_collections.serial as serial_e_collection,
                e_collections.currency as currency_e_collection,
                e_collections.price as price_e_collection,
                e_collections.jilid as jilid_e_collection,
                e_collections.description as description_e_collection
            from
                catalogs
            left join
                penerbit on penerbit.id = catalogs.penerbit_id
            left join
                e_collections on e_collections.id = catalogs.edeposit_col_id
            left join
                kabupaten on kabupaten.id = e_collections.kabupaten_id
            left join
                propinsi on propinsi.id = kabupaten.propinsiid
            where
                catalogs.id = $id
        ", true);

        return response()->json($data);
    }

    public function submitted(Request $request)
    {
        $response = [];

        if ($request->ajax()) {
            $validation = Validator::make($request->all(), [
                'worksheet_id' => 'required',
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
                'worksheet_id.required' => 'Jenis bahan tidak boleh kosong',
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
                    $catalogId = $request->catalog_id;
                    $catalog = QueryAPI::get("select edeposit_col_id from catalogs where id = $catalogId", true);
                    $executorId = $request->executor_id;
                    $executor = QueryAPI::get("select * from penerbit where id = $executorId", true);

                    $uploadIDCover = $request->upload_id_cover;
                    $uploadIDContent = $request->upload_id_content;

                    $baseCollectionData = [
                        'id_old' => 0,
                        'parent_id' => $catalog->EDEPOSIT_COL_ID ?? null,
                        'publisher_id' => $executorId,
                        'city_id' => $executor->CITY_ID ?? session('city_id'),
                        'title_ori' => $request->title,
                        'album' => $request->album,
                        'slug' => Str::slug($request->title, '-'),
                        'series' => $request->series,
                        'serial' => $request->serial,
                        'deposit' => Main::generateNumberDeposit(),
                        'code' => $request->code,
                        'code_type' => $request->code_type ?? 0,
                        'publication_month' => date('m', $publishTime),
                        'publication_year' => date('Y', $publishTime),
                        'publication_day' => date('d', $publishTime),
                        'preview' => $request->preview,
                        'physical_description' => json_encode($request->physical_description),
                        'sync' => 0,
                        'manual' => 1,
                        'akses' => $request->access,
                        'status' => 1,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'price' => str_replace([',', '.'], '', $request->price),
                        'copyright' => Main::copyright($executorId),
                        'worksheet_id' => $request->worksheet_id,
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

                    $createCollection = QueryAPI::create('e_collections', $baseCollectionData);

                    if (!$createCollection) {
                        throw new \Exception('Gagal membuat data koleksi');
                    }

                    if ($request->category && is_array($request->category)) {
                        $categoryData = [];

                        foreach ($request->category as $categoryId) {
                            $categoryData[] = [
                                'collection_id' => $createCollection->ID,
                                'category_id' => $categoryId
                            ];
                        }

                        foreach ($categoryData as $data) {
                            QueryAPI::create('e_collection_categories', $data);
                        }
                    }

                    if ($request->cc_edition && $request->has_edition) {
                        $filesToUpload = [];

                        foreach ($request->cc_edition as $key => $cce) {
                            $editionTitle = $request->cc_edition_title[$key] ?? null;
                            $editionDate = $request->cc_edition_date[$key] ?? null;
                            $editionCover = null;
                            $editionContent = null;

                            if ($request->hasFile('cc_edition_cover') && isset($request->file('cc_edition_cover')[$key])) {
                                $editionCover = $request->file('cc_edition_cover')[$key];
                            }
                            if ($request->hasFile('cc_edition_content') && isset($request->file('cc_edition_content')[$key])) {
                                $editionContent = $request->file('cc_edition_content')[$key];
                            }

                            if ($editionTitle && $editionDate && $editionCover && $editionContent) {
                                $editionData = $baseCollectionData;
                                $editionData['deposit'] = Main::generateNumberDeposit();
                                $editionData['parent_id'] = $createCollection->ID;
                                $editionData['edition'] = $editionTitle;
                                $editionData['edition_date'] = $editionDate;
                                $editionData['publication_month'] = date('m', strtotime($editionDate));
                                $editionData['publication_year'] = date('Y', strtotime($editionDate));
                                $editionData['publication_day'] = date('d', strtotime($editionDate));
                                $createEdition = QueryAPI::create('e_collections', $editionData);

                                if ($createEdition) {
                                    $filesToUpload[] = [
                                        'collection_id' => $createEdition->ID,
                                        'slug' => $createEdition->SLUG,
                                        'cover' => $editionCover,
                                        'content' => $editionContent,
                                        'jilid' => $request->binding,
                                        'currency' => $request->currency,
                                    ];
                                }
                            }
                        }

                        foreach ($filesToUpload as $fileData) {
                            QueryAPI::uploadFile([
                                'type' => 'cover',
                                'id' => $fileData['collection_id'],
                                'status' => 1,
                                'hash' => md5('FILE-COVER-' . $fileData['slug']),
                                'mime' => $fileData['cover']->getMimeType(),
                                'filesize' => $fileData['cover']->getSize(),
                                'method' => 3,
                                'iszip' => false,
                                'file' => $fileData['cover'],
                            ]);

                            QueryAPI::uploadFile([
                                'type' => 'konten_digital',
                                'id' => $fileData['collection_id'],
                                'status' => 1,
                                'hash' => md5('FILE-KONTEN-' . $fileData['slug']),
                                'mime' => $fileData['content']->getMimeType(),
                                'filesize' => $fileData['content']->getSize(),
                                'method' => 3,
                                'iszip' => false,
                                'file' => $fileData['content'],
                            ]);
                        }
                    }

                    $fileCover = $request->file('file_cover');
                    $fileContent = $request->file('file_content');

                    if ($fileCover) {
                        QueryAPI::uploadFile([
                            'type' => 'cover',
                            'id' => $createCollection->ID,
                            'status' => 1,
                            'hash' => md5('FILE-COVER-' . $createCollection->SLUG),
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
                            'id' => $createCollection->ID,
                            'status' => 1,
                            'hash' => md5('FILE-KONTEN-' . $createCollection->SLUG),
                            'mime' => $fileContent->getMimeType(),
                            'filesize' => $fileContent->getSize(),
                            'method' => 3,
                            'iszip' => false,
                            'file' => $fileContent,
                        ]);
                    }

                    if ($uploadIDCover && $uploadIDContent) {
                        QueryAPI::query("update catalogcovers set e_col_id = $createCollection->ID where upload_id = $uploadIDCover");
                        QueryAPI::query("update catalogfiles set e_col_id = $createCollection->ID where upload_id = $uploadIDContent");
                    }

                    $response = [
                        'code' => 200,
                        'message' => 'Data telah ditambahkan'
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    ];
                }
            }
        }

        return response()->json($response);
    }
}
