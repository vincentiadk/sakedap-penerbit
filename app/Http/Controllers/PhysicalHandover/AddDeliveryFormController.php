<?php

namespace App\Http\Controllers\PhysicalHandover;

use Carbon\Carbon;
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
                    'readmore',
                ]
            ]
        ]);
    }

    public function searchISBN(Request $request)
    {
        $code = str_replace('-', '', $request->code);
        $executorId = $request->executor_id;
        $currentBranchId = Main::getBranch()->ID ?? 0;
        $publishDate = '';

        $qtyPerpusnas = 2;
        $qtyProvince = 1;

        $data = ISBN::get('search', [
            'code' => $code,
            'penerbit_id' => $executorId,
        ], true);

        $linkCover = asset('assets/no-file.jpg');
        $title = '';

        if (!$data) {
            return response()->json([
                'data' => null,
                'fileCover' => $this->generateFileCoverHtml($linkCover, $code, $title),
                'qty_perpusnas' => 0,
                'qty_province' => 0,
            ]);
        }

        if (!empty($data->cover_file_name)) {
            $linkCover = $data->cover_file_name;
        }

        if ($data->tanggal_terbit) {
            $publishDate = Carbon::parse($data->tanggal_terbit)->format('Y-m-d');
        }

        $title = $data->title ?? '';

        $sql = "
            select
                nvl(sum(case when branch_id = 37 then collection_count else 0 end), 0) as perpusnas_collection,
                nvl(sum(case when branch_id = 37 then letter_detail_copy else 0 end), 0) as perpusnas_letter_detail,
                nvl(sum(case when branch_id = $currentBranchId then collection_count else 0 end), 0) as province_collection,
                nvl(sum(case when branch_id = $currentBranchId then letter_detail_copy else 0 end), 0) as province_letter_detail
            from (
                select
                    letter.branch_id,
                    count(collections.id) as collection_count,
                    0 as letter_detail_copy
                from
                    collections
                left join
                    letter_detail on letter_detail.letter_detail_id = collections.letter_detail_id
                left join
                    letter on letter.letter_id = collections.letter_id
                where
                    letter.branch_id in (37, $currentBranchId) and
                    replace(collections.isbn, '-', '') = $code
                group by
                    letter.branch_id
                union all
                select
                    letter.branch_id,
                    0 as collection_count,
                    nvl(sum(letter_detail.copy), 0) as letter_detail_copy
                from
                    letter_detail
                left join
                    letter on letter.letter_id = letter_detail.letter_id
                where
                    letter.branch_id in (37, $currentBranchId) and
                    replace(letter_detail.isbn, '-', '') = $code
                group by
                    letter.branch_id
            )
        ";

        $quantities = QueryAPI::get($sql, true, [
            'code' => $code,
            'branch_id' => $currentBranchId
        ]);

        if ($quantities) {
            $checkOnLetterDetailPerpusnas = (int) ($quantities->PERPUSNAS_LETTER_DETAIL ?? 0);
            $checkOnCollectionPerpusnas = (int) ($quantities->PERPUSNAS_COLLECTION ?? 0);

            if ($checkOnLetterDetailPerpusnas > 0) {
                $qtyPerpusnas = $checkOnLetterDetailPerpusnas >= 2 ? 0 : 1;
            } elseif ($checkOnCollectionPerpusnas > 0) {
                $qtyPerpusnas = $checkOnCollectionPerpusnas >= 2 ? 0 : 1;
            }

            $checkOnLetterDetailProvince = (int) ($quantities->PROVINCE_LETTER_DETAIL ?? 0);
            $checkOnCollectionProvince = (int) ($quantities->PROVINCE_COLLECTION ?? 0);

            if ($checkOnLetterDetailProvince > 0) {
                $qtyProvince = $checkOnLetterDetailProvince >= 1 ? 0 : 1;
            } elseif ($checkOnCollectionProvince > 0) {
                $qtyProvince = $checkOnCollectionProvince >= 1 ? 0 : 1;
            }
        }

        return response()->json([
            'data' => $data,
            'fileCover' => $this->generateFileCoverHtml($linkCover, $code, $title),
            'qtyPerpusnas' => $qtyPerpusnas,
            'qtyProvince' => $qtyProvince,
            'publishDate' => $publishDate,
        ]);
    }

    private function generateFileCoverHtml($linkCover, $code, $title)
    {
        return sprintf('
            <a href="%s" data-lightbox="cover-%s" data-title="%s">
                <img src="%s" class="img img-fluid img-thumbnail" style="max-width:70px;">
            </a>
        ', e($linkCover), e($code), e($title), e($linkCover));
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
            } else if ($request->type_delivery == 2) {
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
            $this->createSelfDeliveryLetter($request, $baseLetterData, $auditData, 37, 2);
        }

        if ($request->destination == 2 || $request->destination == 3) {
            $this->createSelfDeliveryLetter($request, $baseLetterData, $auditData, Main::getBranch()->ID ?? null, 1);
        }
    }

    private function createSelfDeliveryLetter(Request $request, $baseLetterData, $auditData, $branchId, $copyType)
    {
        $letterData = array_merge($baseLetterData, [
            'branch_id' => $branchId,
            'receipt_no' => 'LSG' . now()->format('YmdHis'),
            'status' => 'TERKIRIM',
            'sent_date' => date('Y-m-d H:i:s'),
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

        if (empty($origin)) {
            throw new \Exception('Gagal mendapatkan data origin lokasi pengiriman');
        }

        if ($request->perpusnas_delivery && in_array($request->destination, [1, 3])) {
            $branch = Main::getBranch(37);

            if (!$branch) {
                throw new \Exception('Data cabang Perpusnas tidak ditemukan');
            }

            $perpusnasData = [
                'name' => $branch->NAME ?? 'Perpustakaan Nasional RI',
                'phone' => $branch->PHONE ?? '021-3193-6133',
                'address' => $branch->ALAMAT ?? 'Jl. Salemba Raya No.28A, Jakarta Pusat',
                'email' => $branch->EMAIL ?? 'perpusnas@perpusnas.go.id',
            ];

            $this->createExpeditionOrder(
                $request,
                $baseLetterData,
                $auditData,
                $request->perpusnas_delivery,
                $branch->ID ?? 37,
                1,
                $origin,
                $perpusnasData,
                $branch->KODE_POS ?? 10430
            );
        }

        if ($request->province_delivery && in_array($request->destination, [2, 3])) {
            $branch = Main::getBranch();

            if (!$branch) {
                throw new \Exception('Data cabang provinsi tidak ditemukan');
            }

            $provinceData = [
                'name' => $branch->NAME ?? '',
                'phone' => $branch->PHONE ?? '',
                'address' => $branch->ALAMAT ?? '',
                'email' => $branch->EMAIL ?? '',
            ];

            $postalCode = session('postal_code');

            if (empty($postalCode)) {
                throw new \Exception('Kode pos asal pengiriman tidak ditemukan');
            }

            $this->createExpeditionOrder(
                $request,
                $baseLetterData,
                $auditData,
                $request->province_delivery,
                37,
                2,
                $origin,
                $provinceData,
                $postalCode
            );
        }
    }

    private function getOriginDestination($postalCode)
    {
        try {
            $result = Komship::get('tariff/api/v1/destination/search', [
                'keyword' => $postalCode,
            ]);

            return $result ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to get destination: ' . $e->getMessage());

            return [];
        }
    }

    private function createExpeditionOrder(Request $request, $baseLetterData, $auditData, $deliveryData, $branchId, $copyType, $origin, $receiverData, $postalCode)
    {
        if (empty($deliveryData)) {
            throw new \Exception('Data pengiriman tidak valid');
        }

        $deliveryParts = explode(';', $deliveryData);

        if (count($deliveryParts) < 4) {
            throw new \Exception('Format data pengiriman tidak valid');
        }

        $letterData = array_merge($baseLetterData, [
            'branch_id' => $branchId,
            'status' => 'DIKIRIM',
        ], $auditData);

        $letter = QueryAPI::create('letter', $letterData, false);

        if (!$letter || !isset($letter->LETTER_ID)) {
            throw new \Exception('Gagal membuat surat');
        }

        $createLetterDetail = $this->buildLetterDetail($request, $letter, $copyType);
        $destination = $this->getOriginDestination($postalCode);

        if (empty($origin) || !isset($origin[0]->id)) {
            throw new \Exception('Data origin tidak valid');
        }

        if (empty($destination) || !isset($destination[0]->id)) {
            throw new \Exception('Data destination tidak valid');
        }

        $requiredFields = ['name', 'phone', 'address', 'email'];

        foreach ($requiredFields as $field) {
            if (empty($receiverData[$field])) {
                throw new \Exception("Data penerima tidak lengkap: {$field}");
            }
        }

        $orderData = [
            'order_date' => now()->format('Y-m-d'),
            'brand_name' => session('name'),
            'shipper_name' => session('name'),
            'shipper_phone' => Main::formatPhoneKomship($request->phone),
            'shipper_address' => session('address'),
            'shipper_email' => session('email'),
            'shipper_destination_id' => $origin[0]->id,
            'receiver_name' => $receiverData['name'],
            'receiver_phone' => Main::formatPhoneKomship($receiverData['phone']),
            'receiver_destination_id' => $destination[0]->id,
            'receiver_address' => $receiverData['address'],
            'receiver_email' => $receiverData['email'],
            'shipping' => $deliveryParts[0] ?? '',
            'shipping_type' => $deliveryParts[1] ?? '',
            'payment_method' => 'BANK TRANSFER',
            'shipping_cost' => (float) ($deliveryParts[3] ?? 0),
            'grand_total' => (float) ($deliveryParts[2] ?? 0),
            'order_details' => $createLetterDetail->collection ?? [],
        ];

        try {
            $createOrderKomerce = Komship::post('order/api/v1/orders/store', $orderData);

            if (!$createOrderKomerce || !isset($createOrderKomerce->order_no)) {
                throw new \Exception('Komship API tidak mengembalikan order number');
            }

            QueryAPI::update('letter', $letter->LETTER_ID, [
                'type_of_delivery' => $deliveryParts[0] ?? '',
                'branch_id' => $branchId,
                'status' => 'DIKIRIM',
                'receipt_no' => $createOrderKomerce->order_no,
                'order_no' => $createOrderKomerce->order_no,
                'biaya_kirim' => $deliveryParts[3] ?? 0,
                'jumlah_paket' => $createLetterDetail->total_package ?? 0,
            ], false);
        } catch (\Exception $e) {
            Log::error('Failed to create Komship order: ' . $e->getMessage());

            QueryAPI::update('letter', $letter->LETTER_ID, [
                'type_of_delivery' => $deliveryParts[0] ?? '',
                'branch_id' => $branchId,
                'status' => 'PENDING',
                'biaya_kirim' => $deliveryParts[3] ?? 0,
                'jumlah_paket' => $createLetterDetail->total_package ?? 0,
            ], false);

            throw new \Exception('Gagal membuat order pengiriman: ' . $e->getMessage());
        }
    }

    private function handleManualDelivery(Request $request, $baseLetterData, $auditData)
    {
        if (in_array($request->destination, [1, 3])) {
            $this->createManualDeliveryLetter($request, $baseLetterData, $auditData, 37, 2);
        }

        if (in_array($request->destination, [2, 3])) {
            $this->createManualDeliveryLetter($request, $baseLetterData, $auditData, Main::getBranch()->ID ?? null, 1);
        }
    }

    private function createManualDeliveryLetter(Request $request, $baseLetterData, $auditData, $branchId, $copyType)
    {
        $letterData = array_merge($baseLetterData, [
            'branch_id' => $branchId,
            'status' => 'DIKIRIM',
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

        if (!$request->has('ci') || !is_array($request->ci) || empty($request->ci)) {
            return ['total' => $totalPackage, 'collection' => $collection];
        }

        $ciArray = array_values($request->ci);
        $ciCodeArray = $request->has('ci_code') && is_array($request->ci_code) ? array_values($request->ci_code) : [];
        $ciQrcbnArray = $request->has('ci_qrcbn') && is_array($request->ci_qrcbn) ? array_values($request->ci_qrcbn) : [];
        $ciIsbdArray = $request->has('ci_isbd') && is_array($request->ci_isbd) ? array_values($request->ci_isbd) : [];
        $ciQtyPerpusnasArray = $request->has('ci_qty_perpusnas') && is_array($request->ci_qty_perpusnas) ? array_values($request->ci_qty_perpusnas) : [];
        $ciQtyProvinceArray = $request->has('ci_qty_province') && is_array($request->ci_qty_province) ? array_values($request->ci_qty_province) : [];
        $ciPublishDateArray = $request->has('ci_publish_date') && is_array($request->ci_publish_date) ? array_values($request->ci_publish_date) : [];

        foreach ($ciArray as $key => $ci) {
            $code = isset($ciCodeArray[$key]) ? $ciCodeArray[$key] : null;

            if (!$code) {
                Log::warning("CI item skipped: missing code at index {$key}");

                continue;
            }

            try {
                $isbn = Cache::remember("isbn:{$code}", 60, function () use ($code) {
                    return ISBN::get('search', ['code' => $code], true);
                });

                if (!$isbn) {
                    Log::warning("ISBN not found: {$code}");

                    continue;
                }

                $qrcbn = isset($ciQrcbnArray[$key]) ? $ciQrcbnArray[$key] : 0;
                $isbd = isset($ciIsbdArray[$key]) ? $ciIsbdArray[$key] : 0;
                $qtyPerpusnas = isset($ciQtyPerpusnasArray[$key]) ? $ciQtyPerpusnasArray[$key] : 0;
                $qtyProvince = isset($ciQtyProvinceArray[$key]) ? $ciQtyProvinceArray[$key] : 0;
                $publishDate = isset($ciPublishDateArray[$key]) ? $ciPublishDateArray[$key] : null;
                $catalog = null;

                if ($isbn->is_kdt_valid == 1) {
                    $catalog = Cache::remember("catalog:{$isbn->catalog_id}", 60, function () use ($isbn) {
                        $query = "SELECT * FROM catalogs WHERE id = {$isbn->catalog_id}";

                        return QueryAPI::get($query, true);
                    });
                }

                $letterDetailData = [
                    'title' => $isbn->title ?? null,
                    'copy' => $copyType == 2 ? $qtyPerpusnas : $qtyProvince,
                    'quantity' => 1,
                    'letter_id' => $letter->LETTER_ID ?? null,
                    'author' => $isbn->kepeng ?? null,
                    'publisher' => $isbn->nama_penerbit ?? null,
                    'isbn' => $code,
                    'publish_year' => $isbn->tahun_terbit ?? null,
                    'isbn_status' => 'berISBN',
                    'is_receivedate' => 1,
                    'penerbit_isbn_id' => $isbn->penerbit_id ?? null,
                    'catalog_id' => $isbn->is_kdt_valid == 1 ? $isbn->catalog_id : null,
                    'province_id' => $isbn->province_id ?? null,
                    'kab_id' => $catalog->CITY_ID ?? null,
                    'deskripsifisik' => $catalog->DESCRIPTION ?? null,
                    'sinopsis' => $isbn->sinopsis ?? null,
                    'cleaning_note' => $isbn->keterangan ?? null,
                    'jenis_media' => $isbn->jenis_media ?? null,
                    'collection_type_id' => 2,
                    'penerbit_terbitan_id' => $isbn->ptid ?? null,
                    'penerbit_id' => $isbn->PENERBIT_ID ?? $request->executor_id,
                    'nomorpanggiljilid' => $isbn->keterangan ?? null,
                    'qrcbn' => $qrcbn,
                    'isbd' => $isbd,
                    'tanggal_terbit' => $publishDate,
                ];

                QueryAPI::create('letter_detail', $letterDetailData, false);

                $totalPackage++;

                $collection[] = [
                    'product_name' => $isbn->title ?? 'Untitled',
                    'qty' => $copyType,
                ];
            } catch (\Exception $e) {
                Log::error("Error processing ISBN {$code}: " . $e->getMessage());

                continue;
            }
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function processCNIItems(Request $request, $letter, $copyType)
    {
        $totalPackage = 0;
        $collection = [];

        if (!$request->has('cni') || !is_array($request->cni) || empty($request->cni)) {
            return ['total' => $totalPackage, 'collection' => $collection];
        }

        $cniArray = array_values($request->cni);
        $cniTitleArray = $request->has('cni_title') && is_array($request->cni_title) ? array_values($request->cni_title) : [];
        $cniCatalogIdArray = $request->has('cni_catalog_id') && is_array($request->cni_catalog_id) ? array_values($request->cni_catalog_id) : [];
        $cniExecutorArray = $request->has('cni_executor') && is_array($request->cni_executor) ? array_values($request->cni_executor) : [];
        $cniAuthorArray = $request->has('cni_author') && is_array($request->cni_author) ? array_values($request->cni_author) : [];
        $cniPhysicalDescArray = $request->has('cni_physical_description') && is_array($request->cni_physical_description) ? array_values($request->cni_physical_description) : [];
        $cniYearArray = $request->has('cni_year') && is_array($request->cni_year) ? array_values($request->cni_year) : [];
        $cniBindingArray = $request->has('cni_binding') && is_array($request->cni_binding) ? array_values($request->cni_binding) : [];
        $cniTypeArray = $request->has('cni_type') && is_array($request->cni_type) ? array_values($request->cni_type) : [];
        $cniQrcbnArray = $request->has('cni_qrcbn') && is_array($request->cni_qrcbn) ? array_values($request->cni_qrcbn) : [];
        $cniIsbdArray = $request->has('cni_isbd') && is_array($request->cni_isbd) ? array_values($request->cni_isbd) : [];
        $cniPriceArray = $request->has('cni_price') && is_array($request->cni_price) ? array_values($request->cni_price) : [];

        foreach ($cniArray as $key => $cni) {
            $title = isset($cniTitleArray[$key]) ? $cniTitleArray[$key] : null;

            if (empty($title)) {
                Log::warning("CNI item skipped: missing title at index {$key}");

                continue;
            }

            $catalogId = isset($cniCatalogIdArray[$key]) ? $cniCatalogIdArray[$key] : null;
            $catalog = null;

            if ($catalogId) {
                try {
                    $catalog = Cache::remember("catalog:detail:{$catalogId}", 60, function () use ($catalogId) {
                        return $this->getCatalogDetail($catalogId);
                    });
                } catch (\Exception $e) {
                    Log::error("Error fetching catalog {$catalogId}: " . $e->getMessage());
                }
            }

            $media = isset($cniTypeArray[$key]) ? strtoupper($cniTypeArray[$key]) : '';
            $getCollectionMedia = null;

            if ($media) {
                try {
                    $getCollectionMedia = QueryAPI::get("SELECT * FROM collectionmedias WHERE UPPER(name) = '{$media}'", true);
                } catch (\Exception $e) {
                    Log::error("Error fetching collection media {$media}: " . $e->getMessage());
                }
            }

            $price = isset($cniPriceArray[$key]) ? str_replace([',', '.'], '', $cniPriceArray[$key]) : 0;

            $letterDetailData = [
                'title' => $title,
                'copy' => $copyType,
                'quantity' => 1,
                'price' => is_numeric($price) ? $price : 0,
                'letter_id' => $letter->LETTER_ID ?? null,
                'author' => isset($cniAuthorArray[$key]) ? $cniAuthorArray[$key] : null,
                'publisher' => $catalog->NAME_PENERBIT ?? (isset($cniExecutorArray[$key]) ? $cniExecutorArray[$key] : null),
                'publisher_address' => $catalog->ALAMAT_PENERBIT ?? null,
                'publish_year' => isset($cniYearArray[$key]) ? $cniYearArray[$key] : null,
                'publisher_city' => $catalog->NAMAKAB ?? null,
                'is_receivedate' => 1,
                'catalog_id' => $catalogId,
                'province_id' => $catalog->PROPINSIID ?? null,
                'kab_id' => $catalog->CITY_ID ?? null,
                'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? ($getCollectionMedia->ID ?? null),
                'deskripsifisik' => isset($cniPhysicalDescArray[$key]) ? $cniPhysicalDescArray[$key] : null,
                'jenis_media' => $getCollectionMedia->NAME ?? null,
                'penerbit_id' => $catalog->PENERBIT_ID ?? $request->executor_id,
                'nomorpanggiljilid' => isset($cniBindingArray[$key]) ? $cniBindingArray[$key] : null,
                'qrcbn' => isset($cniQrcbnArray[$key]) ? $cniQrcbnArray[$key] : null,
                'isbd' => isset($cniIsbdArray[$key]) ? $cniIsbdArray[$key] : null,
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

        if (!$request->has('cp') || !is_array($request->cp) || empty($request->cp)) {
            return ['total' => $totalPackage, 'collection' => $collection];
        }

        foreach ($request->cp as $cpIndex => $cpValue) {
            $catalogId = null;
            $catalogTitle = null;

            if (isset($request->cp_catalog_id[$cpIndex])) {
                $catalogId = $request->cp_catalog_id[$cpIndex];
            }

            if (isset($request->cp_manual_title[$cpIndex])) {
                $catalogTitle = $request->cp_manual_title[$cpIndex];
            }

            $catalog = null;

            if ($catalogId && empty($catalogTitle)) {
                try {
                    $catalog = Cache::remember("catalog:detail:{$catalogId}", 60, function () use ($catalogId) {
                        return $this->getCatalogDetail($catalogId);
                    });
                } catch (\Exception $e) {
                    Log::error("Error fetching catalog {$catalogId}: " . $e->getMessage());
                }
            }

            if (!isset($request->cpe[$cpIndex]) || !is_array($request->cpe[$cpIndex]) || empty($request->cpe[$cpIndex])) {
                Log::warning("CPE data for index {$cpIndex} not found or invalid");

                continue;
            }

            $cpeArray = array_values($request->cpe[$cpIndex]);
            $cpeEditionArray = isset($request->cpe_edition[$cpIndex]) && is_array($request->cpe_edition[$cpIndex]) ? array_values($request->cpe_edition[$cpIndex]) : [];
            $cpeFirstTtesArray = isset($request->cpe_first_ttes[$cpIndex]) && is_array($request->cpe_first_ttes[$cpIndex]) ? array_values($request->cpe_first_ttes[$cpIndex]) : [];
            $cpeEndTtesArray = isset($request->cpe_end_ttes[$cpIndex]) && is_array($request->cpe_end_ttes[$cpIndex]) ? array_values($request->cpe_end_ttes[$cpIndex]) : [];

            foreach ($cpeArray as $editionKey => $cpe) {
                $title = $catalog->TITLE ?? $catalogTitle;

                if (empty($title)) {
                    Log::warning("Periodical skipped: missing title at cp:{$cpIndex}, edition:{$editionKey}");
                    continue;
                }

                $letterDetailData = [
                    'title' => $title,
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
                    'edisi_serial' => isset($cpeEditionArray[$editionKey]) ? $cpeEditionArray[$editionKey] : null,
                    'ttes_awal' => isset($cpeFirstTtesArray[$editionKey]) ? $cpeFirstTtesArray[$editionKey] : null,
                    'ttes_akhir' => isset($cpeEndTtesArray[$editionKey]) ? $cpeEndTtesArray[$editionKey] : null,
                    'catalog_id' => $catalogId,
                    'province_id' => $catalog->PROPINSIID ?? null,
                    'kab_id' => $catalog->CITY_ID ?? null,
                    'collection_type_id' => $catalog->COLLECTIONMEDIA_ID ?? null,
                    'penerbit_id' => $catalog->PENERBIT_ID ?? $request->executor_id,
                ];

                QueryAPI::create('letter_detail', $letterDetailData, false);

                $totalPackage++;

                $collection[] = [
                    'product_name' => isset($cpeEditionArray[$editionKey]) ? $cpeEditionArray[$editionKey] : $title,
                    'qty' => $copyType,
                ];
            }
        }

        return ['total' => $totalPackage, 'collection' => $collection];
    }

    private function getCatalogDetail($catalogId)
    {
        try {
            $query = "
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

            return QueryAPI::get($query, true);
        } catch (\Exception $e) {
            Log::error("Error fetching catalog detail {$catalogId}: " . $e->getMessage());

            return null;
        }
    }
}
