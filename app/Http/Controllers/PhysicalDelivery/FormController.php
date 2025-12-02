<?php

namespace App\Http\Controllers\PhysicalDelivery;

use App\Helpers\ISBN;
use App\Helpers\Main;
use App\Helpers\Komship;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index()
    {
        $worksheetAnalog = Main::COLLECTION_ANALOG;
        $worksheetPrinted = Main::COLLECTION_PRINTED;

        $media = QueryAPI::get("
            select
                collectionmedias.*
            from
                collectionmedias
            join
                worksheets on worksheets.id = collectionmedias.worksheet_id
            where
                worksheets.category in ('$worksheetAnalog','$worksheetPrinted') and
                collectionmedias.depositformat_code is not null
        ");

        return view('layouts.index', [
            'data' => [
                'content' => 'physical-delivery.form',
                'media' => $media ?? [],
                'plugins' => [
                    'select2',
                    'datatable',
                    'daterangepicker',
                    'lightbox',
                ]
            ]
        ]);
    }

    public function searchISBN(Request $request)
    {
        $code = str_replace('-', '', $request->code);

        $data = ISBN::get('search', [
            'code' => $code,
        ], true);

        $linkCover = asset('assets/no-file.jpg');
        $title = $data->title ?? '';

        if ($data) {
            if (isset($data->cover_file_name)) {
                if ($data->cover_file_name) {
                    $linkCover = $data->cover_file_name;
                }
            }
        }

        $fileCover = '
            <a href="' . $linkCover . '" data-lightbox="cover-' . $code . '" data-title="' . $title . '">
                <img src="' . $linkCover . '" class="img img-fluid img-thumbnail" style="max-width:70px;">
            </a>
        ';

        return response()->json([
            'data' => $data,
            'fileCover' => $fileCover,
        ]);
    }

    public function selectCatalog(Request $request)
    {
        $id = $request->id;
        $data = QueryAPI::get("
            select
                catalogs.*,
                worksheets.name as name_worksheet,
                penerbit.name as name_penerbit
            from
                catalogs
            left join
                worksheets on worksheets.id = catalogs.worksheet_id
            left join
                penerbit on penerbit.id = catalogs.penerbit_id
            where
                catalogs.id = $id
        ", true);

        return response()->json($data);
    }

    public function calculateCost(Request $request)
    {
        $weight = $request->weight;
        $destination = $request->destination;

        $response = [];
        $response['perpusnas'] = [];
        $response['province'] = [];

        $destinationPerpusnas = null;
        $destinationProvince = null;

        if ($destination == 1) {
            $postalCode = 10430;

            $destinationPerpusnas = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => $postalCode,
            ]);
        } else if ($destination == 2) {
            $branch = Main::getBranch();
            $postalCode = $branch->KODE_POS ?? 0;

            $destinationProvince = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => $postalCode,
            ]);
        } else if ($destination == 3) {
            $postalCodePerpusnas = 10430;
            $branch = Main::getBranch();
            $postalCodeProvince = $branch->KODE_POS ?? 0;

            $destinationPerpusnas = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => $postalCodePerpusnas,
            ]);

            $destinationProvince = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => $postalCodeProvince,
            ]);
        }

        $origin = Komship::get('tariff/api/v1/destination/search', [
            'keyword' => session('postal_code'),
        ]);

        if (($destinationPerpusnas || $destinationProvince) && $origin) {
            if ($destinationPerpusnas) {
                $response['perpusnas'] = Komship::get('tariff/api/v1/calculate', [
                    'shipper_destination_id' => $origin[0]->id ?? 0,
                    'receiver_destination_id' => $destinationPerpusnas[0]->id ?? 0,
                    'weight' => $weight,
                    'item_value' => 10000,
                    'cod' => 'no',
                ]);
            }

            if ($destinationProvince) {
                $response['province'] = Komship::get('tariff/api/v1/calculate', [
                    'shipper_destination_id' => $origin[0]->id ?? 0,
                    'receiver_destination_id' => $destinationProvince[0]->id ?? 0,
                    'weight' => $weight,
                    'item_value' => 10000,
                    'cod' => 'no',
                ]);
            }
        }

        return response()->json($response);
    }

    public function submitted(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['code' => 400, 'message' => 'Invalid request']);
        }

        $addValidationRule = [];
        $addValidationMessage = [];

        if (config('system.delivery_method') == 'expedition' && $request->type_delivery == 2) {
            if ($request->destination == 1) {
                $addValidationRule = [
                    'perpusnas_delivery' => 'required',
                ];

                $addValidationMessage = [
                    'perpusnas_delivery.required' => 'Mohon memilih ekspedisi perpusnas',
                ];
            } else if ($request->destination == 2) {
                $addValidationRule = [
                    'province_delivery' => 'required',
                ];

                $addValidationMessage = [
                    'province_delivery.required' => 'Mohon memilih ekspedisi provinsi',
                ];
            } else if ($request->destination == 3) {
                $addValidationRule = [
                    'perpusnas_delivery' => 'required',
                    'province_delivery' => 'required',
                ];

                $addValidationMessage = [
                    'perpusnas_delivery.required' => 'Mohon memilih ekspedisi perpusnas',
                    'province_delivery.required' => 'Mohon memilih ekspedisi provinsi',
                ];
            }
        }

        $validation = Validator::make($request->all(), array_merge($addValidationRule, [
            'type_delivery' => 'required',
            'phone' => 'required|min_digits:8|max_digits:13|numeric',
            'sender_name' => 'required',
            'weight' => 'required|numeric|min:1',
            'destination' => 'required',
        ]), array_merge($addValidationMessage, [
            'type_delivery.required' => 'Jenis pengiriman tidak boleh kosong',
            'phone.required' => 'Telepon tidak boleh kosong',
            'phone.min_digits' => 'Telepon minimal 8 digit',
            'phone.max_digits' => 'Telepon maksimal 13 digit',
            'phone.numeric' => 'Telepon harus angka',
            'sender_name.required' => 'Nama pengirim tidak boleh kosong',
            'weight.required' => 'Berat paket tidak boleh kosong',
            'weight.numeric' => 'Berat paket harus angka',
            'weight.min' => 'Berat paket minimal 1',
            'destination.required' => 'Tujuan tidak boleh kosong',
        ]));

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'error' => $validation->errors()->all(),
            ]);
        }

        try {
            $now = now()->format('Y-m-d H:i:s');
            $currentUser = session('username');
            $currentIp = $request->ip();
            $weight = $request->weight;
            $letterDate = $now;

            $auditData = [
                'create_date' => $now,
                'create_by' => $currentUser,
                'create_terminal' => $currentIp,
                'update_date' => $now,
                'update_by' => $currentUser,
                'update_terminal' => $currentIp,
            ];

            $baseLetterData = [
                'letter_date' => $letterDate,
                'letter_number' => $request->cover_letter_number,
                'sender' => $request->sender_name,
                'publisher_id' => session('id'),
                'lang' => 'id',
                'penerbit_id' => session('id'),
                'berat' => $weight * 1000,
                'phone' => $request->phone,
            ];

            if ($request->type_delivery == 1) {
                $this->handleSelfDelivery($request, $baseLetterData, $auditData);
            } else if ($request->type_delivery == 2) {
                $this->handleCourierDelivery($request, $baseLetterData, $auditData);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Data berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'code' => $e->getCode() ?: 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function handleSelfDelivery($request, $baseLetterData, $auditData)
    {
        if ($request->destination == 1 || $request->destination == 3) {
            $letterData = array_merge($baseLetterData, [
                'branch_id' => 37,
                'receipt_no' => 'LSG' . date('YmdHis'),
                'status' => 'TERKIRIM',
                'biaya_kirim' => 0,
            ], $auditData);

            $createLetterDetail = $this->buildLetterDetail($request, $letterData, 1);

            QueryAPI::update('letter', $createLetterDetail->letter_id ?? 0, [
                'jumlah_paket' => $createLetterDetail->total_package ?? 0,
            ], false);
        }

        if ($request->destination == 2 || $request->destination == 3) {
            $letterData = array_merge($baseLetterData, [
                'branch_id' => Main::getBranch()->ID ?? null,
                'receipt_no' => 'LSG' . date('YmdHis'),
                'status' => 'TERKIRIM',
                'biaya_kirim' => 0,
            ], $auditData);

            $createLetterDetail = $this->buildLetterDetail($request, $letterData, 2);

            QueryAPI::update('letter', $createLetterDetail->letter_id ?? 0, [
                'jumlah_paket' => $createLetterDetail->total_package ?? 0,
            ], false);
        }
    }

    private function handleCourierDelivery($request, $baseLetterData, $auditData)
    {
        if (config('system.delivery_method') == 'expedition') {
            $perpusnasDelivery = $request->perpusnas_delivery;
            $provinceDelivery = $request->province_delivery;

            $origin = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => session('postal_code'),
            ]);

            if ($perpusnasDelivery) {
                $dataPerpusnasDelivery = explode(';', $perpusnasDelivery);
                $letterData = array_merge($baseLetterData, $auditData);
                $letter = QueryAPI::create('letter', $letterData, false);

                if (!$letter) {
                    return response()->json([
                        'code' => 500,
                        'message' => 'Gagal membuat surat'
                    ]);
                }

                $createLetterDetail = $this->buildLetterDetail($request, $letter, 1);

                $destinationPerpusnas = Komship::get('tariff/api/v1/destination/search', [
                    'keyword' => 10430,
                ]);

                $createOrderKomerce = Komship::post('order/api/v1/orders/store', [
                    'order_date' => date('Y-m-d'),
                    'brand_name' => session('name'),
                    'shipper_name' => session('name'),
                    'shipper_phone' => Main::formatPhoneKomship($request->phone),
                    'shipper_address' => session('address'),
                    'shipper_email' => session('email'),
                    'shipper_destination_id' => $origin[0]->id ?? 0,
                    'receiver_name' => 'Perpustakaan Nasional Republik Indonesia',
                    'receiver_phone' => Main::formatPhoneKomship('0213152171'),
                    'receiver_destination_id' => $destinationPerpusnas[0]->id ?? 0,
                    'receiver_address' => 'Jl. Salemba Raya No.28A, Jakarta Pusat, DKI Jakarta 10430',
                    'receiver_email' => 'depbangkol@gmail.com',
                    'shipping' => $dataPerpusnasDelivery[0] ?? '',
                    'shipping_type' => $dataPerpusnasDelivery[1] ?? '',
                    'payment_method' => 'BANK TRANSFER',
                    'shipping_cost' => (float) ($dataPerpusnasDelivery[3] ?? ''),
                    'grand_total' => (float) ($dataPerpusnasDelivery[2] ?? ''),
                    'order_details' => $createLetterDetail->collection ?? [],
                ]);

                QueryAPI::update('letter', $letter->LETTER_ID, [
                    'type_of_delivery' => $dataPerpusnasDelivery[0] ?? '',
                    'branch_id' => 37,
                    'status' => 'DIKIRIM',
                    'receipt_no' => $createOrderKomerce->order_no ?? '',
                    'order_no' => $createOrderKomerce->order_no ?? '',
                    'biaya_kirim' => $dataPerpusnasDelivery[3] ?? 0,
                    'jumlah_paket' => $createLetterDetail->total_package ?? 0,
                ], false);
            }

            if ($provinceDelivery) {
                $dataProvinceDelivery = explode(';', $provinceDelivery);
                $letterData = array_merge($baseLetterData, $auditData);
                $letter = QueryAPI::create('letter', $letterData, false, 2);

                if (!$letter) {
                    return response()->json([
                        'code' => 500,
                        'message' => 'Gagal membuat surat'
                    ]);
                }

                $createLetterDetail = $this->buildLetterDetail($request, $letter, 2);

                $destinationProvince = Komship::get('tariff/api/v1/destination/search', [
                    'keyword' => Main::getBranch()->KODE_POS ?? 0,
                ]);

                $createOrderKomerce = Komship::post('order/api/v1/orders/store', [
                    'order_date' => date('Y-m-d'),
                    'brand_name' => session('name'),
                    'shipper_name' => session('name'),
                    'shipper_phone' => Main::formatPhoneKomship($request->phone),
                    'shipper_address' => session('address'),
                    'shipper_email' => session('email'),
                    'shipper_destination_id' => $origin[0]->id ?? 0,
                    'receiver_name' => Main::getBranch()->NAME ?? '',
                    'receiver_phone' => Main::formatPhoneKomship(Main::getBranch()->PHONE ?? ''),
                    'receiver_destination_id' => $destinationProvince[0]->id ?? 0,
                    'receiver_address' => Main::getBranch()->ALAMAT ?? '',
                    'shipping' => $dataProvinceDelivery[0] ?? '',
                    'shipping_type' => $dataProvinceDelivery[1] ?? '',
                    'payment_method' => 'BANK TRANSFER',
                    'shipping_cost' => (float) ($dataProvinceDelivery[3] ?? ''),
                    'grand_total' => (float) ($dataProvinceDelivery[2] ?? ''),
                    'order_details' => $createLetterDetail->collection ?? [],
                ]);

                QueryAPI::update('letter', $letter->LETTER_ID, [
                    'type_of_delivery' => $dataProvinceDelivery[0] ?? '',
                    'branch_id' => Main::getBranch()->ID ?? null,
                    'status' => 'DIKIRIM',
                    'receipt_no' => $createOrderKomerce->order_no ?? '',
                    'order_no' => $createOrderKomerce->order_no ?? '',
                    'biaya_kirim' => $dataProvinceDelivery[3] ?? 0,
                    'jumlah_paket' => $createLetterDetail->total_package ?? 0,
                ], false);
            }
        }

        if (config('system.delivery_method') == 'manual') {
            if ($request->destination == 1 || $request->destination == 3) {
                $letterData = array_merge($baseLetterData, [
                    'branch_id' => 37,
                    'status' => 'DIKIRIM',
                ], $auditData);

                $letter = QueryAPI::create('letter', $letterData, false);
                $createLetterDetail = $this->buildLetterDetail($request, $letter, 1);

                $this->buildLetterDetail($request, $createLetterDetail, 1);
            }

            if ($request->destination == 2 || $request->destination == 3) {
                $letterData = array_merge($baseLetterData, [
                    'branch_id' => Main::getBranch()->ID ?? null,
                    'status' => 'DIKIRIM',
                ], $auditData);

                $letter = QueryAPI::create('letter', $letterData, false);
                $createLetterDetail = $this->buildLetterDetail($request, $letter, 1);

                $this->buildLetterDetail($request, $letterData, 2);
            }
        }
    }

    private function buildLetterDetail($request, $letter, $allotment)
    {
        $cacheDuration = 60;
        $totalPackage = 0;
        $collection = [];

        if ($request->ci) {
            $result = $this->processCIItems($request, $letter, $allotment, $cacheDuration);
            $totalPackage += $result['total'];
            $collection = array_merge($collection, $result['collection']);
        }

        if ($request->cni) {
            $result = $this->processCNIItems($request, $letter, $allotment, $cacheDuration);
            $totalPackage += $result['total'];
            $collection = array_merge($collection, $result['collection']);
        }

        if ($request->cp) {
            $result = $this->processCPItems($request, $letter, $allotment, $cacheDuration);
            $totalPackage += $result['total'];
            $collection = array_merge($collection, $result['collection']);
        }

        return (object) [
            'letter_id' => $letter->LETTER_ID ?? null,
            'total_package' => $totalPackage,
            'collection' => $collection
        ];
    }

    private function processCIItems($request, $letter, $allotment, $cacheDuration)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->ci as $key => $ci) {
            $code = $request->ci_code[$key] ?? null;
            if (!$code) continue;

            $isbnCacheKey = "isbn:{$code}";
            $isbn = Cache::remember($isbnCacheKey, $cacheDuration, function () use ($code) {
                return ISBN::get('search', ['code' => $code], true);
            });

            if (!$isbn) continue;

            $qrcbn = ($request->ci_qrcbn[$key] ?? 0);
            $isbd = ($request->ci_isbd[$key] ?? 0);

            $totalPackage++;
            $catalog = null;

            if ($isbn->is_kdt_valid) {
                $catalogId = $isbn->catalog_id;
                $catalogCacheKey = "catalog:{$catalogId}";

                $catalog = Cache::remember($catalogCacheKey, $cacheDuration, function () use ($catalogId) {
                    return QueryAPI::get("select * from catalogs where id = {$catalogId}", true);
                });
            }

            $letterDetailData = [
                'title' => $isbn->title,
                'copy' => $allotment == 1 ? 1 : 2,
                'quantity' => 1,
                'letter_id' => $letter->LETTER_ID ?? null,
                'author' => $isbn->kepeng,
                'publisher' => $isbn->nama_penerbit,
                'isbn' => $code,
                'publish_year' => $isbn->tahun_terbit,
                'isbn_status' => 'berISBN',
                'is_receivedate' => 1,
                'penerbit_isbn_id' => $isbn->penerbit_id,
                'catalog_id' => $isbn->is_kdt_valid == 1 ? $isbn->catalog_id : null,
                'province_id' => $isbn->province_id,
                'kab_id' => $catalog->CITY_ID ?? null,
                'deskripsifisik' => $catalog->DESCRIPTION ?? null,
                'sinopsis' => $isbn->sinopsis,
                'cleaning_note' => $isbn->keterangan,
                'jenis_media' => $isbn->jenis_media,
                'collection_type_id' => 2,
                'penerbit_terbitan_id' => $isbn->ptid,
                'penerbit_id' => $isbn->PENERBIT_ID ?? null,
                'nomorpanggiljilid' => $isbn->keterangan,
                'qrcbn' => $qrcbn,
                'isbd' => $isbd,
            ];

            QueryAPI::create('letter_detail', $letterDetailData, false);

            $collection[] = [
                'product_name' => $isbn->title,
                'qty' => $allotment == 1 ? 1 : 2,
            ];
        }

        return [
            'total' => $totalPackage,
            'collection' => $collection
        ];
    }

    private function processCNIItems($request, $letter, $allotment, $cacheDuration)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->cni as $key => $cni) {
            $totalPackage++;

            $catalogId = $request->cni_catalog_id[$key] ?? null;
            $catalog = null;

            if ($catalogId) {
                $catalogCacheKey = "catalog:detail:{$catalogId}";

                $catalog = Cache::remember($catalogCacheKey, $cacheDuration, function () use ($catalogId) {
                    $catalogQuery = "
                    select
                        catalogs.*,
                        penerbit.name as name_penerbit,
                        penerbit.alamat as alamat_penerbit,
                        kabupaten.namakab as namakab,
                        kabupaten.propinsiid as propinsiid
                    from
                        catalogs
                    left join
                        penerbit on penerbit.id = catalogs.penerbit_id
                    left join
                        kabupaten on kabupaten.id = penerbit.city_id
                    where
                        catalogs.id = $catalogId
                ";

                    return QueryAPI::get($catalogQuery, true);
                });
            }

            $title = $request->cni_title[$key] ?? null;
            $author = $request->cni_author[$key] ?? null;
            $year = $request->cni_year[$key] ?? null;
            $physicalDescription = $request->cni_physical_description[$key] ?? null;
            $executor = $request->cni_executor[$key] ?? ($letterExecutor->NAME ?? null);
            $binding = $request->cni_binding[$key] ?? null;
            $qrcbn = $request->cni_qrcbn[$key] ?? null;
            $isbd = $request->cni_isbd[$key] ?? null;
            $media = strtoupper($request->cni_type[$key] ?? '');
            $getCollectionMedia = null;

            if ($media) {
                $getCollectionMedia = QueryAPI::get("select * from collectionmedias where upper(name) = '$media'", true);
            }

            $letterDetailData = [
                'title' => $title,
                'copy' => $allotment == 1 ? 1 : 2,
                'quantity' => 1,
                'price' => str_replace(',', '', ($request->cni_price[$key] ?? 0)),
                'letter_id' => $letter->LETTER_ID ?? null,
                'author' => $author,
                'publisher' => $catalog->NAME_PENERBIT ?? $executor,
                'publisher_address' => $catalog->ALAMAT_PENERBIT ?? null,
                'publish_year' => $year,
                'publisher_city' => $catalog->NAMAKAB ?? null,
                'is_receivedate' => 1,
                'catalog_id' => $catalogId,
                'province_id' => $catalog->PROPINSIID ?? null,
                'kab_id' => $catalog->CITY_ID ?? null,
                'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? ($getCollectionMedia->ID ?? null),
                'deskripsifisik' => $physicalDescription,
                'jenis_media' => $getCollectionMedia->NAME ?? null,
                'penerbit_id' => $catalog->PENERBIT_ID ?? session('id'),
                'nomorpanggiljilid' => $binding,
                'qrcbn' => $qrcbn,
                'isbd' => $isbd,
            ];

            QueryAPI::create('letter_detail', $letterDetailData, false);

            $collection[] = [
                'product_name' => $title,
                'qty' => $allotment == 1 ? 1 : 2,
            ];
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function processCPItems($request, $letter, $allotment, $cacheDuration)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->cp as $key => $cp) {
            $catalogId = $request->cp_catalog_id[$key] ?? null;

            if (!$catalogId) continue;

            foreach ($request->cpe[$key] as $keys => $cpe) {
                $totalPackage++;

                $catalogCacheKey = "catalog:detail:{$catalogId}";

                $catalog = Cache::remember($catalogCacheKey, $cacheDuration, function () use ($catalogId) {
                    $catalogQuery = "
                    select
                        catalogs.*,
                        penerbit.name as name_penerbit,
                        penerbit.alamat as alamat_penerbit,
                        kabupaten.namakab as namakab,
                        kabupaten.propinsiid as propinsiid
                    from
                        catalogs
                    left join
                        penerbit on penerbit.id = catalogs.penerbit_id
                    left join
                        kabupaten on kabupaten.id = penerbit.city_id
                    where
                        catalogs.id = $catalogId
                ";

                    return QueryAPI::get($catalogQuery, true);
                });

                $edition = $request->cpe_edition[$key][$keys] ?? null;
                $firstTTES = $request->cpe_first_ttes[$key][$keys] ?? null;
                $endTTES = $request->cpe_end_ttes[$key][$keys] ?? null;

                $letterDetailData = [
                    'title' => $catalog->TITLE ?? null,
                    'copy' => $allotment == 1 ? 1 : 2,
                    'quantity' => 1,
                    'price' => $catalog->PRICE ?? null,
                    'letter_id' => $letter->LETTER_ID ?? null,
                    'author' => $catalog->AUTHOR ?? null,
                    'publisher' => $catalog->NAME_PENERBIT ?? ($letterExecutor->NAME ?? null),
                    'publisher_address' => $catalog->ALAMAT_PENERBIT ?? null,
                    'publish_year' => $catalog->PUBLISHYEAR ?? null,
                    'publisher_city' => $catalog->NAMAKAB ?? null,
                    'is_receivedate' => 1,
                    'edisi_serial' => $edition,
                    'ttes_awal' => $firstTTES,
                    'ttes_akhir' => $endTTES,
                    'catalog_id' => $catalogId,
                    'province_id' => $catalog->PROPINSIID ?? null,
                    'kab_id' => $catalog->CITY_ID ?? null,
                    'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? null,
                    'penerbit_id' => $catalog->PENERBIT_ID ?? session('id'),
                ];

                QueryAPI::create('letter_detail', $letterDetailData, false);

                $collection[] = [
                    'product_name' => $edition,
                    'qty' => $allotment == 1 ? 1 : 2,
                ];
            }
        }

        return [
            'total' => $totalPackage,
            'collection' => $collection
        ];
    }
}
