<?php

namespace App\Http\Controllers\PhysicalHandover;

use App\Helpers\ISBN;
use App\Helpers\Main;
use App\Helpers\Komship;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AddDeliveryFormController extends Controller
{
    private const PERPUSNAS_DATA = [
        'name' => 'Perpustakaan Nasional Republik Indonesia',
        'phone' => '0213152171',
        'address' => 'Jl. Salemba Raya No.28A, Jakarta Pusat, DKI Jakarta 10430',
        'email' => 'depbangkol@gmail.com',
    ];

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
                'content' => 'physical-handover.add-delivery-form',
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
        $executorId = $request->executor_id;

        $data = ISBN::get('search', [
            'code' => $code,
            'penerbit_id' => $executorId,
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
                worksheets.alias as alias_worksheet,
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
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request'
            ]);
        }

        $validationRules = $this->getValidationRules($request);
        $validation = Validator::make($request->all(), $validationRules['rules'], $validationRules['messages']);

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'error' => $validation->errors()->all(),
            ]);
        }

        try {
            $now = now()->format('Y-m-d H:i:s');
            $auditData = $this->buildAuditData($now, $request);
            $baseLetterData = $this->buildBaseLetterData($request, $now, $auditData);

            if ($request->type_delivery == 1) {
                $this->handleSelfDelivery($request, $baseLetterData, $auditData);
            } elseif ($request->type_delivery == 2) {
                $this->handleCourierDelivery($request, $baseLetterData, $auditData);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Data berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['password', 'token'])
            ]);

            return response()->json([
                'code' => $e->getCode() ?: 500,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem'
            ]);
        }
    }

    private function getValidationRules(Request $request)
    {
        $rules = [
            'type_delivery' => 'required|in:1,2',
            'phone' => 'required|numeric|digits_between:8,13',
            'sender_name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:1',
            'destination' => 'required|in:1,2,3',
        ];

        $messages = [
            'type_delivery.required' => 'Metode pengiriman tidak boleh kosong',
            'type_delivery.in' => 'Metode pengiriman tidak valid',
            'phone.required' => 'Telepon tidak boleh kosong',
            'phone.digits_between' => 'Telepon harus antara 8-13 digit',
            'phone.numeric' => 'Telepon harus berupa angka',
            'sender_name.required' => 'Nama pengirim tidak boleh kosong',
            'sender_name.max' => 'Nama pengirim maksimal 255 karakter',
            'weight.required' => 'Berat paket tidak boleh kosong',
            'weight.numeric' => 'Berat paket harus berupa angka',
            'weight.min' => 'Berat paket minimal 1',
            'destination.required' => 'Tujuan tidak boleh kosong',
            'destination.in' => 'Tujuan tidak valid',
        ];

        if (config('system.delivery_method') === 'expedition' && $request->type_delivery == 2) {
            $additionalRules = $this->getExpeditionValidationRules($request->destination);
            $rules = array_merge($rules, $additionalRules['rules']);
            $messages = array_merge($messages, $additionalRules['messages']);
        }

        return ['rules' => $rules, 'messages' => $messages];
    }

    private function getExpeditionValidationRules($destination)
    {
        $rules = [];
        $messages = [];

        if (in_array($destination, [1, 3])) {
            $rules['perpusnas_delivery'] = 'required|string';
            $messages['perpusnas_delivery.required'] = 'Mohon memilih ekspedisi perpusnas';
        }

        if (in_array($destination, [2, 3])) {
            $rules['province_delivery'] = 'required|string';
            $messages['province_delivery.required'] = 'Mohon memilih ekspedisi provinsi';
        }

        return ['rules' => $rules, 'messages' => $messages];
    }

    private function buildAuditData($now, Request $request)
    {
        $currentUser = session('username');
        $currentIp = $request->ip();

        return [
            'create_date' => $now,
            'create_by' => $currentUser,
            'create_terminal' => $currentIp,
            'update_date' => $now,
            'update_by' => $currentUser,
            'update_terminal' => $currentIp,
        ];
    }

    private function buildBaseLetterData(Request $request, $now, $auditData)
    {
        return array_merge([
            'letter_date' => $now,
            'letter_number' => $request->cover_letter_number,
            'sender' => $request->sender_name,
            'publisher_id' => $request->executor_id,
            'lang' => 'id',
            'penerbit_id' => $request->executor_id,
            'berat' => $request->weight * 1000,
            'phone' => $request->phone,
        ], $auditData);
    }

    private function handleSelfDelivery(Request $request, $baseLetterData, $auditData)
    {
        if ($request->destination == 1 || $request->destination == 3) {
            $this->createSelfDeliveryLetter($request, $baseLetterData, $auditData, Main::getBranch()->ID ?? null, 1);
        }

        if ($request->destination == 2 || $request->destination == 3) {
            $this->createSelfDeliveryLetter($request, $baseLetterData, $auditData, 37, 2);
        }
    }

    private function createSelfDeliveryLetter(Request $request, $baseLetterData, $auditData, $branchId, $copyType)
    {
        $letterData = array_merge($baseLetterData, [
            'branch_id' => $branchId,
            'receipt_no' => 'LSG' . now()->format('YmdHis'),
            'status' => 'TERKIRIM',
            'biaya_kirim' => 0,
        ], $auditData);

        $letter = QueryAPI::create('letter', $letterData, false);

        if (!$letter || !isset($letter->LETTER_ID)) {
            throw new \Exception('Gagal membuat surat');
        }

        $createLetterDetail = $this->buildLetterDetail($request, $letter, $copyType);

        QueryAPI::update('letter', $letter->LETTER_ID, [
            'jumlah_paket' => $createLetterDetail->total_package ?? 0,
        ], false);
    }

    private function handleCourierDelivery(Request $request, $baseLetterData, $auditData)
    {
        if (config('system.delivery_method') === 'expedition') {
            $this->handleExpeditionDelivery($request, $baseLetterData, $auditData);
        } elseif (config('system.delivery_method') === 'manual') {
            $this->handleManualDelivery($request, $baseLetterData, $auditData);
        }
    }

    private function handleExpeditionDelivery(Request $request, $baseLetterData, $auditData)
    {
        $origin = $this->getOriginDestination(session('postal_code'));

        if ($request->perpusnas_delivery && in_array($request->destination, [2, 3])) {
            $this->createExpeditionOrder($request, $baseLetterData, $auditData, $request->perpusnas_delivery, 37, 2, $origin, self::PERPUSNAS_DATA, 10430);
        }

        if ($request->province_delivery && in_array($request->destination, [1, 3])) {
            $branch = Main::getBranch();
            $provinceData = [
                'name' => $branch->NAME ?? '',
                'phone' => $branch->PHONE ?? '',
                'address' => $branch->ALAMAT ?? '',
                'email' => session('email'),
            ];

            $this->createExpeditionOrder($request, $baseLetterData, $auditData, $request->province_delivery, $branch->ID ?? null, 1, $origin, $provinceData, $branch->KODE_POS ?? 0);
        }
    }

    private function getOriginDestination($postalCode)
    {
        return Komship::get('tariff/api/v1/destination/search', [
            'keyword' => $postalCode,
        ]) ?? [];
    }

    private function createExpeditionOrder(Request $request, $baseLetterData, $auditData, $deliveryData, $branchId, $copyType, $origin, $receiverData, $postalCode)
    {
        $deliveryParts = explode(';', $deliveryData);
        $letter = QueryAPI::create('letter', $baseLetterData, false);

        if (!$letter || !isset($letter->LETTER_ID)) {
            throw new \Exception('Gagal membuat surat');
        }

        $createLetterDetail = $this->buildLetterDetail($request, $letter, $copyType);
        $destination = $this->getOriginDestination($postalCode);

        $orderData = [
            'order_date' => now()->format('Y-m-d'),
            'brand_name' => session('name'),
            'shipper_name' => session('name'),
            'shipper_phone' => Main::formatPhoneKomship($request->phone),
            'shipper_address' => session('address'),
            'shipper_email' => session('email'),
            'shipper_destination_id' => $origin[0]->id ?? 0,
            'receiver_name' => $receiverData['name'],
            'receiver_phone' => Main::formatPhoneKomship($receiverData['phone']),
            'receiver_destination_id' => $destination[0]->id ?? 0,
            'receiver_address' => $receiverData['address'],
            'receiver_email' => $receiverData['email'],
            'shipping' => $deliveryParts[0] ?? '',
            'shipping_type' => $deliveryParts[1] ?? '',
            'payment_method' => 'BANK TRANSFER',
            'shipping_cost' => (float) ($deliveryParts[3] ?? 0),
            'grand_total' => (float) ($deliveryParts[2] ?? 0),
            'order_details' => $createLetterDetail->collection ?? [],
        ];

        $createOrderKomerce = Komship::post('order/api/v1/orders/store', $orderData);

        QueryAPI::update('letter', $letter->LETTER_ID, [
            'type_of_delivery' => $deliveryParts[0] ?? '',
            'branch_id' => $branchId,
            'status' => 'DIKIRIM',
            'receipt_no' => $createOrderKomerce->order_no ?? '',
            'order_no' => $createOrderKomerce->order_no ?? '',
            'biaya_kirim' => $deliveryParts[3] ?? 0,
            'jumlah_paket' => $createLetterDetail->total_package ?? 0,
        ], false);
    }

    private function handleManualDelivery(Request $request, $baseLetterData, $auditData)
    {
        if (in_array($request->destination, [1, 3])) {
            $this->createManualDeliveryLetter($request, $baseLetterData, $auditData, Main::getBranch()->ID ?? null, 1);
        }

        if (in_array($request->destination, [2, 3])) {
            $this->createManualDeliveryLetter($request, $baseLetterData, $auditData, 37, 2);
        }
    }

    private function createManualDeliveryLetter(Request $request, $baseLetterData, $auditData, $branchId, $copyType)
    {
        $letterData = array_merge($baseLetterData, [
            'branch_id' => $branchId,
            'status' => 'DIKIRIM',
        ], $auditData);

        $letter = QueryAPI::create('letter', $letterData, false);

        if ($letter) {
            $this->buildLetterDetail($request, $letter, $copyType);
        }
    }

    private function buildLetterDetail(Request $request, $letter, $copyType)
    {
        $totalPackage = 0;
        $collection = [];

        $itemTypes = [
            'ci' => 'processCIItems',
            'cni' => 'processCNIItems',
            'cp' => 'processCPItems',
        ];

        foreach ($itemTypes as $itemType => $method) {
            if ($request->has($itemType) && !empty($request->$itemType)) {
                $result = $this->$method($request, $letter, $copyType);
                $totalPackage += $result['total'];
                $collection = array_merge($collection, $result['collection']);
            }
        }

        return (object) [
            'letter_id' => $letter->LETTER_ID ?? null,
            'total_package' => $totalPackage,
            'collection' => $collection
        ];
    }

    private function processCIItems(Request $request, $letter, $copyType)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->ci as $key => $ci) {
            $code = $request->ci_code[$key] ?? null;

            if (!$code) {
                continue;
            }

            $isbn = Cache::remember("isbn:{$code}", 60, fn() => ISBN::get('search', ['code' => $code], true));

            if (!$isbn) {
                continue;
            }

            $qrcbn = $request->ci_qrcbn[$key] ?? 0;
            $isbd = $request->ci_isbd[$key] ?? 0;
            $catalog = null;

            if ($isbn->is_kdt_valid) {
                $catalog = Cache::remember("catalog:{$isbn->catalog_id}", 60, fn() => QueryAPI::get("SELECT * FROM catalogs WHERE id = {$isbn->catalog_id}", true));
            }

            $letterDetailData = [
                'title' => $isbn->title,
                'copy' => $copyType,
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
                'penerbit_id' => $isbn->PENERBIT_ID ?? $request->executor_id,
                'nomorpanggiljilid' => $isbn->keterangan,
                'qrcbn' => $qrcbn,
                'isbd' => $isbd,
            ];

            QueryAPI::create('letter_detail', $letterDetailData, false);

            $totalPackage++;
            $collection[] = [
                'product_name' => $isbn->title,
                'qty' => $copyType,
            ];
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function processCNIItems(Request $request, $letter, $copyType)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->cni as $key => $cni) {
            $catalogId = $request->cni_catalog_id[$key] ?? null;
            $catalog = null;

            if ($catalogId) {
                $catalog = Cache::remember("catalog:detail:{$catalogId}", 60, fn() => $this->getCatalogDetail($catalogId));
            }

            $title = $request->cni_title[$key] ?? null;
            $media = strtoupper($request->cni_type[$key] ?? '');
            $getCollectionMedia = null;

            if ($media) {
                $getCollectionMedia = QueryAPI::get("SELECT * FROM collectionmedias WHERE UPPER(name) = '{$media}'", true);
            }

            $letterDetailData = [
                'title' => $title,
                'copy' => $copyType,
                'quantity' => 1,
                'price' => str_replace(',', '', $request->cni_price[$key] ?? 0),
                'letter_id' => $letter->LETTER_ID ?? null,
                'author' => $request->cni_author[$key] ?? null,
                'publisher' => $catalog->NAME_PENERBIT ?? ($request->cni_executor[$key] ?? null),
                'publisher_address' => $catalog->ALAMAT_PENERBIT ?? null,
                'publish_year' => $request->cni_year[$key] ?? null,
                'publisher_city' => $catalog->NAMAKAB ?? null,
                'is_receivedate' => 1,
                'catalog_id' => $catalogId,
                'province_id' => $catalog->PROPINSIID ?? null,
                'kab_id' => $catalog->CITY_ID ?? null,
                'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? ($getCollectionMedia->ID ?? null),
                'deskripsifisik' => $request->cni_physical_description[$key] ?? null,
                'jenis_media' => $getCollectionMedia->NAME ?? null,
                'penerbit_id' => $catalog->PENERBIT_ID ?? $request->executor_id,
                'nomorpanggiljilid' => $request->cni_binding[$key] ?? null,
                'qrcbn' => $request->cni_qrcbn[$key] ?? null,
                'isbd' => $request->cni_isbd[$key] ?? null,
            ];

            QueryAPI::create('letter_detail', $letterDetailData, false);

            $totalPackage++;
            $collection[] = [
                'product_name' => $title,
                'qty' => $copyType,
            ];
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function processCPItems(Request $request, $letter, $copyType)
    {
        $totalPackage = 0;
        $collection = [];

        foreach ($request->cp as $key => $cp) {
            $catalogId = $request->cp_catalog_id[$key] ?? null;
            $catalogTitle = $request->cp_manual_title[$key] ?? null;
            $catalog = null;

            if ($catalogId && empty($catalogTitle)) {
                $catalog = Cache::remember("catalog:detail:{$catalogId}", 60, fn() => $this->getCatalogDetail($catalogId));
            }

            if (!isset($request->cpe[$key]) || !is_array($request->cpe[$key])) {
                continue;
            }

            foreach ($request->cpe[$key] as $keys => $cpe) {
                $letterDetailData = [
                    'title' => $catalog->TITLE ?? $catalogTitle,
                    'copy' => $copyType,
                    'quantity' => 1,
                    'price' => $catalog->PRICE ?? null,
                    'letter_id' => $letter->LETTER_ID ?? null,
                    'author' => $catalog->AUTHOR ?? null,
                    'publisher' => $catalog->NAME_PENERBIT ?? null,
                    'publisher_address' => $catalog->ALAMAT_PENERBIT ?? null,
                    'publish_year' => $catalog->PUBLISHYEAR ?? null,
                    'publisher_city' => $catalog->NAMAKAB ?? null,
                    'is_receivedate' => 1,
                    'edisi_serial' => $request->cpe_edition[$key][$keys] ?? null,
                    'ttes_awal' => $request->cpe_first_ttes[$key][$keys] ?? null,
                    'ttes_akhir' => $request->cpe_end_ttes[$key][$keys] ?? null,
                    'catalog_id' => $catalogId,
                    'province_id' => $catalog->PROPINSIID ?? null,
                    'kab_id' => $catalog->CITY_ID ?? null,
                    'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? null,
                    'penerbit_id' => $catalog->PENERBIT_ID ?? $request->executor_id,
                ];

                QueryAPI::create('letter_detail', $letterDetailData, false);

                $totalPackage++;
                $collection[] = [
                    'product_name' => $request->cpe_edition[$key][$keys] ?? null,
                    'qty' => $copyType,
                ];
            }
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function getCatalogDetail($catalogId)
    {
        $query = "
            SELECT
                catalogs.*,
                penerbit.name AS name_penerbit,
                penerbit.alamat AS alamat_penerbit,
                kabupaten.namakab AS namakab,
                kabupaten.propinsiid AS propinsiid
            FROM
                catalogs
            LEFT JOIN
                penerbit ON penerbit.id = catalogs.penerbit_id
            LEFT JOIN
                kabupaten ON kabupaten.id = penerbit.city_id
            WHERE
                catalogs.id = :catalog_id
        ";

        return QueryAPI::get($query, true, ['catalog_id' => $catalogId]);
    }
}
