<?php

namespace App\Http\Controllers\Documentation;

use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccessAPIController extends Controller
{
    public function index()
    {
        return view('layouts.index', [
            'data' => [
                'content' => 'documentation.access-api',
            ]
        ]);
    }

    public function generateNewToken(Request $request)
    {
        $token = Str::random(40);

        QueryAPI::update('penerbit', session('id'), [
            'x_api_key' => $token,
            'updatedate' => date('Y-m-d H:i:s'),
            'updateterminal' => $request->ip(),
            'updateby' => session('username'),
        ]);

        session(['api_key' => $token]);

        return response()->json([
            'code' => 200,
            'message' => 'API Key berhasil di generate',
            'data' => [
                'token' => $token
            ]
        ]);
    }
}
