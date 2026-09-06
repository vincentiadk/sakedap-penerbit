<?php

namespace App\Http\Controllers\PhysicalHandover;

/**
 * Versi 2 dari form pengiriman serah simpan fisik.
 *
 * Hanya tampilannya yang baru. Seluruh alur pencarian ISBN, perhitungan kuota,
 * validasi, dan pembuatan surat diwarisi apa adanya dari versi 1 supaya kedua
 * versi tidak pernah berbeda perilaku - perbaikan pada satu pipeline berlaku
 * untuk keduanya.
 */
class AddDeliveryFormV2Controller extends AddDeliveryFormController
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'physical-handover.add-delivery-form-v2',
                'plugins' => [
                    'select2',
                    'lightbox',
                ]
            ]
        ]);
    }
}
