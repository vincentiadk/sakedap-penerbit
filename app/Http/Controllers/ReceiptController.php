<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
    public function openFromAdmin($filename)
    {
        $relativePath = $filename;

        if (!Storage::disk('admin_receipt')->exists($relativePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = Storage::disk('admin_receipt')->path($relativePath);

        return response()->file($fullPath);
    }

}