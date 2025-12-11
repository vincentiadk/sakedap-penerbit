<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPenerbit;
use Illuminate\Http\Request;
use OTPHP\TOTP;
use QueryAPI;
use Illuminate\Support\Facades\Validator;

class GoogleAuthenticatorController extends Controller
{
    public function getDataPenerbit($id): mixed{
        $sql = 'SELECT * FROM PENERBIT WHERE ID = ' . $id;

        $dataPenerbit = QueryAPI::get($sql, true);

        return $dataPenerbit;
    }


    public function getDataAdmin($id): mixed{
        $sql = 'SELECT * FROM USERS WHERE ID = ' . $id;

        $dataPenerbit = QueryAPI::get($sql, true);

        return $dataPenerbit;
    }


    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penerbit_id' => 'required',
            'is_admin' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }



        if($request->input('is_admin') == 1){
            $user = $this->getDataAdmin($request->penerbit_id);
        } else {
            $user = $this->getDataPenerbit($request->penerbit_id);;
        }

        if($user == []) {
            return response()->json(['error' => 'Penerbit not found'], 404);
        }

        $email = $request->input('is_admin') == 1 ? $user->EMAILADDRESS : $user->EMAIL1;

        $totp = TOTP::create();
        $totp->setLabel($email);
        $totp->setIssuer("E-Deposit");

        $result =  [
            "OTP_GOOGLE_AUTH" =>  $totp->getSecret(),
        ];

        if($request->input('is_admin') == 1){
            QueryAPI::update('USERS', $request->penerbit_id, $result, false);
        } else {
            QueryAPI::update('PENERBIT', $request->penerbit_id, $result, false);
        }

        return response()->json([
            'secret'       => $totp->getSecret(),
            'otpauth_url'  => $totp->getProvisioningUri()
        ]);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penerbit_id' => 'required',
            'is_admin'    => 'nullable|boolean',
            'otp'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $otp = $request->otp;

        if($request->input('is_admin') == 1){
            $user = $this->getDataAdmin($request->penerbit_id);
        } else {
            $user = $this->getDataPenerbit($request->penerbit_id);;
        }

        if($user == []) {
            return response()->json(['error' => 'Penerbit not found'], 404);
        }

        if (!$user->OTP_GOOGLE_AUTH) {
            return response()->json([
                'status'  => false,
                'message' => 'Verifikasi berhasil. Selamat datang.'
            ], 400);
        }

        $totp = TOTP::create($user->OTP_GOOGLE_AUTH);

        $verified = $totp->verify($otp, null, 1);

        if (!$verified) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa'
            ], 422);
        }

        $result =  [
            'GOOGLE_AUTH_ENABLED' => 1
        ];

        if($request->input('is_admin') == 1){
            QueryAPI::update('USERS', $request->penerbit_id, $result, false);
        } else {
            QueryAPI::update('PENERBIT', $request->penerbit_id, $result, false);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Google Authenticator berhasil diaktifkan',
            'data' => '1'
        ]);
    }

}
