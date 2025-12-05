<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Main;
use App\Helpers\Fonnte;
use App\Helpers\QueryAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (session('id')) {
            return redirect('home');
        }

        if ($request->_token == csrf_token()) {
            $rateLimitMaxAttempt = config('system.retry_login');
            $rateLimitInterval = config('system.retry_login_interval');
            $rateLimitIntervalSecond = $rateLimitInterval * 60 * 60;
            $rateLimitKey = $request->ip();
            $retriesLeft = RateLimiter::retriesLeft($rateLimitKey, $rateLimitMaxAttempt);

            if (RateLimiter::tooManyAttempts($rateLimitKey, $rateLimitMaxAttempt)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                $retryAt = Carbon::now()->addSeconds($seconds);
                $retryTime = $retryAt->diffForHumans();

                return redirect('/')->with('failed', "Terlalu banyak upaya login ($rateLimitMaxAttempt kali). Anda dapat mencoba kembali pada $retryTime");
            }

            $validation = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
                'g-recaptcha-response' => 'required|captcha',
            ], [
                'username.required' => 'Username tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
                'g-recaptcha-response.required' => 'Terdeteksi robot',
                'g-recaptcha-response.captcha' => 'Captcha tidak valid',
            ]);

            if ($validation->fails()) {
                RateLimiter::hit($rateLimitKey, $rateLimitIntervalSecond);

                return redirect('/')->withErrors($validation);
            } else {
                $username = $request->username;
                $password = $request->password;
                $login = Main::login($username, $password);

                if ($login) {
                    RateLimiter::clear($rateLimitKey);

                    return redirect()->intended('home');
                }

                RateLimiter::hit($rateLimitKey, $rateLimitIntervalSecond);

                return redirect('/')->with(['failed' => 'Kredensial tidak ditemukan, sisa percobaan login ' . $retriesLeft . 'x lagi']);
            }
        }

        return view('login');
    }

    public function notVerified(Request $request)
    {
        $sqlQuery = "
            select
                penerbit.*,
                propinsi.namapropinsi as namapropinsi,
                kabupaten.namakab as namakab,
                kecamatan.namakec as namakec,
                kelurahan.namakel as namakel,
                penerbit_kategori.name as name_penerbit_kategori,
                penerbit_jenis.name as name_penerbit_jenis
            from
                penerbit
            left join
                penerbit_kategori on penerbit_kategori.id = penerbit.kategori_id
            left join
                penerbit_jenis on penerbit_jenis.id = penerbit.jenis_id
            left join
                propinsi on propinsi.id = penerbit.province_id
            left join
                kabupaten on kabupaten.id = penerbit.city_id
            left join
                kecamatan on kecamatan.id = penerbit.district_id
            left join
                kelurahan on kelurahan.id = penerbit.village_id
            where
                penerbit.id = " . session('id') . "
        ";

        $executor = QueryAPI::get($sqlQuery, true);

        if ($request->_token == csrf_token()) {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'location_id' => 'required',
                'address' => 'required',
                'postal_code' => 'required|digits:5',
                'contact1' => 'required',
                'email2' => 'nullable|email',
                'phone2' => 'nullable|string|max:20',
                'fax1' => 'nullable|string|max:20',
                'fax2' => 'nullable|string|max:20',
                'website' => 'nullable|url',
                'file_deed' => 'nullable|file|mimes:pdf|max:5120',
                'file_statement' => 'nullable|file|mimes:pdf|max:5120',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'location_id.required' => 'Wilayah tidak boleh kosong',
                'address.required' => 'Alamat tidak boleh kosong',
                'postal_code.required' => 'Kode pos tidak boleh kosong',
                'postal_code.digits' => 'Kode pos harus 5 digit',
                'contact1.required' => 'Nama admin utama tidak boleh kosong',
                'email2.email' => 'Email alternatif tidak valid',
                'phone2.max' => 'No telp alternatif maksimal 20 karakter',
                'fax1.max' => 'No fax utama maksimal 20 karakter',
                'fax2.max' => 'No fax alternatif maksimal 20 karakter',
                'website.url' => 'Website tidak valid url',
                'file_deed.file' => 'File akta tidak valid',
                'file_deed.file' => 'File akta harus pdf',
                'file_deed.file' => 'File akta maksimal 5MB',
                'file_statement.file' => 'File pernyataan tidak valid',
                'file_statement.file' => 'File pernyataan harus pdf',
                'file_statement.file' => 'File pernyataan maksimal 5MB',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            } else {
                try {
                    $locationId = $request->location_id;
                    $location = Main::locationById($locationId, 'village');

                    $change = QueryAPI::update('penerbit', $executor->ID ?? '', [
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'province_id' => $location->PROPINSIID ?? null,
                        'city_id' => $location->KABUPATENID ?? null,
                        'district_id' => $location->KECAMATAN_ID ?? null,
                        'village_id' => $location->ID ?? null,
                        'alamat' => $request->address,
                        'kodepos' => $request->postal_code,
                        'kontak1' => $request->contact1,
                        'kontak2' => $request->contact2,
                        'email2' => $request->email2,
                        'telp2' => $request->phone2,
                        'fax1' => $request->fax1,
                        'fax2' => $request->fax2,
                        'website' => $request->website,
                        'nama_gedung' => $request->building_name,
                        'rata_terbitan' => $request->avg_publication,
                        'lembaga_penaung' => $request->shelter_institution,
                        'updateby' => session('username'),
                        'updatedate' => date('Y-m-d H:i:s'),
                        'updateterminal' => $request->ip(),
                        'status' => 1,
                    ], false);

                    if ($change) {
                        $executor = QueryAPI::get($sqlQuery, true);

                        if ($executor) {
                            $fileDeed = $request->file('file_deed');
                            $fileStatement = $request->file('file_statement');

                            if ($fileDeed) {
                                QueryAPI::uploadFile([
                                    'type' => 'penerbit_akte_notaris',
                                    'id' => session('id'),
                                    'iszip' => false,
                                    'file' => $fileDeed,
                                ]);
                            }

                            if ($fileStatement) {
                                QueryAPI::uploadFile([
                                    'type' => 'penerbit_surat_pernyataan',
                                    'id' => session('id'),
                                    'iszip' => false,
                                    'file' => $fileStatement,
                                ]);
                            }
                        }

                        $message = ['success' => 'Data berhasil diajukan kembali'];
                    } else {
                        $message = ['failed' => 'Data gagal diajukan'];
                    }

                    return redirect('auth/not-verified')->with($message);
                } catch (\Exception $e) {
                    return redirect()->back()->with([
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $problemHistory = QueryAPI::get("
            select
                *
            from
                penerbit_registrasi_masalah
            where
                penerbit_id = " . session('id') . "
        ");

        return view('not-verified', [
            'executor' => $executor,
            'problemHistory' => $problemHistory ?? [],
        ]);
    }

    public function changePassword(Request $request)
    {
        if ($request->_token == csrf_token()) {
            $validation = Validator::make($request->all(), [
                'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s]).+$/',
                'confirm_password' => 'required|same:new_password'
            ], [
                'new_password.required' => 'Password baru tidak boleh kosong',
                'new_password.string' => 'Password baru harus text',
                'new_password.min' => 'Password baru minimal 8 karakter',
                'new_password.regex' => 'Password baru harus mengandung, 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol',
                'confirm_password.required' => 'Konfirmasi password tidak boleh kosong',
                'confirm_password.same' => 'Konfirmasi password harus sama dengan password baru'
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            } else {
                try {
                    $change = QueryAPI::update('penerbit', session('id'), [
                        'isbn_password1' => md5($request->new_password),
                        'isbn_password2' => Main::AESCrypt($request->new_password, config('inlis.aes_key'), config('inlis.aes_iv')),
                        'updateby' => session('username'),
                        'updatedate' => date('Y-m-d H:i:s'),
                        'updateterminal' => $request->ip(),
                    ], false);

                    if ($change) {
                        $message = ['success' => 'Password berhasil diganti'];
                    } else {
                        $message = ['failed' => 'Password gagal diganti'];
                    }

                    return redirect('auth/change-password')->with($message);
                } catch (\Exception $e) {
                    return redirect()->back()->with([
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return view('layouts.index', [
            'data' => [
                'content' => 'change-password'
            ]
        ]);
    }

    public function profile(Request $request)
    {
        $sqlQuery = "
            select
                penerbit.*,
                propinsi.namapropinsi as namapropinsi,
                kabupaten.namakab as namakab,
                kecamatan.namakec as namakec,
                kelurahan.namakel as namakel,
                penerbit_kategori.name as name_penerbit_kategori,
                penerbit_jenis.name as name_penerbit_jenis
            from
                penerbit
            left join
                penerbit_kategori on penerbit_kategori.id = penerbit.kategori_id
            left join
                penerbit_jenis on penerbit_jenis.id = penerbit.jenis_id
            left join
                propinsi on propinsi.id = penerbit.province_id
            left join
                kabupaten on kabupaten.id = penerbit.city_id
            left join
                kecamatan on kecamatan.id = penerbit.district_id
            left join
                kelurahan on kelurahan.id = penerbit.village_id
            where
                penerbit.id = " . session('id') . "
        ";

        $executor = QueryAPI::get($sqlQuery, true);

        if ($request->_token == csrf_token()) {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'location_id' => 'required',
                'address' => 'required',
                'postal_code' => 'required|digits:5',
                'contact1' => 'required',
                'email1' => 'required|email',
                'email2' => 'nullable|email',
                'phone1' => 'required|string|max:20',
                'phone2' => 'nullable|string|max:20',
                'fax1' => 'nullable|string|max:20',
                'fax2' => 'nullable|string|max:20',
                'website' => 'nullable|url',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'location_id.required' => 'Wilayah tidak boleh kosong',
                'address.required' => 'Alamat tidak boleh kosong',
                'postal_code.required' => 'Kode pos tidak boleh kosong',
                'postal_code.digits' => 'Kode pos harus 5 digit',
                'contact1.required' => 'Nama admin utama tidak boleh kosong',
                'email1.required' => 'Email utama tidak boleh kosong',
                'email1.email' => 'Email utama tidak valid',
                'email2.email' => 'Email alternatif tidak valid',
                'phone1.required' => 'No telp utama tidak boleh kosong',
                'phone1.max' => 'No telp utama maksimal 20 karakter',
                'phone2.max' => 'No telp alternatif maksimal 20 karakter',
                'fax1.max' => 'No fax utama maksimal 20 karakter',
                'fax2.max' => 'No fax alternatif maksimal 20 karakter',
                'website.url' => 'Website tidak valid url',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            } else {
                if ($request->email1 !== ($executor->EMAIL1 ?? '')) {
                    $verifiedKey = "otp_verified:email:{$request->email1}";

                    if (!Redis::get($verifiedKey)) {
                        return response()->json([
                            'code' => 422,
                            'message' => 'Email belum diverifikasi'
                        ]);
                    }

                    Redis::del($verifiedKey);
                }

                if ($request->phone1 !== ($executor->TELP1 ?? '')) {
                    $formattedPhone = $this->formatPhoneNumber($request->phone1);
                    $verifiedKey = "otp_verified:phone:{$formattedPhone}";

                    if (!Redis::get($verifiedKey)) {
                        return response()->json([
                            'code' => 422,
                            'message' => 'Nomor telepon belum diverifikasi'
                        ]);
                    }

                    Redis::del($verifiedKey);
                }

                try {
                    $locationId = $request->location_id;
                    $location = Main::locationById($locationId, 'village');

                    $change = QueryAPI::update('penerbit', session('id'), [
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'province_id' => $location->PROPINSIID ?? null,
                        'city_id' => $location->KABUPATENID ?? null,
                        'district_id' => $location->KECAMATAN_ID ?? null,
                        'village_id' => $location->ID ?? null,
                        'alamat' => $request->address,
                        'kodepos' => $request->postal_code,
                        'kontak1' => $request->contact1,
                        'kontak2' => $request->contact2,
                        'email1' => $request->email1,
                        'email2' => $request->email2,
                        'telp1' => $request->phone1,
                        'telp2' => $request->phone2,
                        'fax1' => $request->fax1,
                        'fax2' => $request->fax2,
                        'website' => $request->website,
                        'nama_gedung' => $request->building_name,
                        'rata_terbitan' => $request->avg_publication,
                        'lembaga_penaung' => $request->shelter_institution,
                        'updateby' => session('username'),
                        'updatedate' => date('Y-m-d H:i:s'),
                        'updateterminal' => $request->ip(),
                    ], false);

                    if ($change) {
                        Redis::del("otp_attempt:email:{$request->email1}");
                        Redis::del("otp_attempt:phone:{$this->formatPhoneNumber($request->phone1)}");

                        $executor = QueryAPI::get($sqlQuery, true);

                        if ($executor) {
                            session([
                                'id' => $executor->ID,
                                'username' => $executor->ISBN_USER_NAME,
                                'name' => $executor->NAME,
                                'email' => $executor->EMAIL1,
                                'province_id' => $executor->PROVINCE_ID ?: 31,
                                'province_name' => $executor->NAMAPROPINSI ?: 'DKI Jakarta',
                                'phone' => $executor->TELP1,
                                'postal_code' => $executor->KODEPOS,
                                'address' => $executor->ALAMAT,
                                'api_key' => $executor->X_API_KEY,
                                'api_status' => $executor->IS_API_ENABLE ?: 0,
                                'status' => $executor->STATUS ?: 1,
                                'is_isbn' => $executor->IS_ISBN ?: 0,
                            ]);
                        }

                        $message = ['success' => 'Profil berhasil diganti'];
                    } else {
                        $message = ['failed' => 'Profil gagal diganti'];
                    }

                    return redirect('auth/profile')->with($message);
                } catch (\Exception $e) {
                    return redirect()->back()->with([
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $data = [
            'executor' => $executor,
            'content' => 'profile',
            'plugins' => [
                'select2',
            ]
        ];

        return view('layouts.index', ['data' => $data]);
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        if (!preg_match('/^62[0-9]{9,13}$/', $phone)) {
            return false;
        }

        return $phone;
    }

    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Data tidak valid'
            ]);
        }

        $type = $request->type;
        $value = $request->value;

        if ($type === 'phone') {
            $phone = $this->formatPhoneNumber($value);

            if (!$phone) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Format nomor telepon tidak valid'
                ]);
            }

            $value = $phone;
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $redisKey = "otp:{$type}:{$value}";

        Redis::setex($redisKey, 300, $otp);

        try {
            if ($type === 'email') {
                $this->sendOTPEmail($value, $otp);
            } else {
                $result = $this->sendOTPWA($value, $otp);

                if (($result->code ?? '') != 201) {
                    return response()->json([
                        'code' => 500,
                        'message' => $result->message ?? ''
                    ]);
                }
            }

            return response()->json([
                'code' => 200,
                'message' => 'Kode OTP telah dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());

            return response()->json([
                'code' => 500,
                'message' => 'Gagal mengirim OTP. Silakan coba lagi.'
            ]);
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'value' => 'required',
            'otp_code' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Data tidak valid'
            ]);
        }

        $type = $request->type;
        $value = $request->value;
        $otpCode = $request->otp_code;

        if ($type === 'phone') {
            $value = $this->formatPhoneNumber($value);
        }

        $redisKey = "otp:{$type}:{$value}";
        $storedOtp = Redis::get($redisKey);

        if (!$storedOtp) {
            return response()->json([
                'code' => 422,
                'message' => 'Kode OTP telah kadaluarsa'
            ]);
        }

        if ($storedOtp !== $otpCode) {
            return response()->json([
                'code' => 400,
                'message' => 'Kode OTP tidak valid'
            ]);
        }

        $verifiedKey = "otp_verified:{$type}:{$value}";

        Redis::setex($verifiedKey, 600, 'verified');
        Redis::del($redisKey);

        return response()->json([
            'code' => 200,
            'message' => 'Verifikasi berhasil'
        ]);
    }

    private function sendOTPEmail($email, $otp)
    {
        Mail::send('email.otp', ['otp' => $otp], function ($message) use ($email) {
            $message->to($email)->subject('Kode Verifikasi OTP - Perubahan Email SAKEDAP');
        });
    }

    private function sendOTPWA($phone, $otp)
    {
        try {
            $message = "*VERIFIKASI OTP*\n\n";
            $message .= "Kode OTP Anda: *{$otp}*\n\n";
            $message .= "Kode ini berlaku selama *5 menit*.\n";
            $message .= "Jangan bagikan kode ini kepada siapapun.\n\n";
            $message .= "Jika Anda tidak meminta kode ini, abaikan pesan ini.\n\n";
            $message .= "_Pesan otomatis, mohon tidak membalas._";

            return Fonnte::send($phone, $message);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode() ?? 500,
                'message' => $e->getMessage()
            ];
        }
    }

    public function resetPasswordRequest(Request $request)
    {
        if (session('id')) {
            return redirect('home');
        }

        if ($request->_token == csrf_token()) {
            $email = $request->email;
            $checkEmail = QueryAPI::get("select * from penerbit where email1 = '$email'", true);
            $templateEmail = QueryAPI::get("select * from e_settings where slug = 'ResetPassword'", true);

            if ($checkEmail) {
                $createRequest = QueryAPI::create('e_password_resets', [
                    'email' => $email,
                    'token' => Str::random(40),
                    'created_at' => date('Y-m-d H:i:s'),
                    'expired_at' => date('Y-m-d H:i:s', strtotime('+' . config('system.limit_reset_password') . ' hours')),
                ], false);

                if ($createRequest) {
                    try {
                        $tokenUrl = url('reset-password-action?token=' . $createRequest->TOKEN . '&email=' . urlencode($email));
                        $payloadEmail = [
                            'name' => $checkEmail->NAME,
                            'email' => $email,
                            'link' => '<a href="' . $tokenUrl . '">' . $tokenUrl . '</a>',
                        ];

                        if ($templateEmail) {
                            Mail::send([], [], function ($message) use ($payloadEmail, $templateEmail) {
                                $message->to($payloadEmail['email'], $payloadEmail['name'])
                                    ->subject('Permintaan Reset Password')
                                    ->from(config('mail.from.address'), config('mail.from.name'))
                                    ->html(Main::parseTemplateEmail($payloadEmail, $templateEmail), 'text/html');
                            });
                        }

                        return redirect('reset-password-request')->with('success', 'Kami telah mengirim email ke ' . $email);
                    } catch (\Exception $e) {
                        return redirect('reset-password-request')->with('failed', $e->getMessage());
                    }
                }
            } else {
                return redirect('reset-password-request')->with('failed', 'Email tidak terdaftar');
            }
        }

        return view('reset-password-request');
    }

    public function resetPasswordAction(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $check = QueryAPI::get("select * from e_password_resets where email = '$email' and token = '$token'", true);
        $user = QueryAPI::get("select * from penerbit where email1 = '$email'", true);

        if ($check && $user) {
            $currentTime = strtotime(date('Y-m-d H:i:s'));
            $expiredTime = strtotime(date('Y-m-d H:i:s', strtotime($check->EXPIRED_AT)));
            $diff = $expiredTime - $currentTime;
            $minutes = floor($diff / 60);

            if ($minutes < 0) {
                abort(419);
            }

            if ($request->_token == csrf_token()) {
                $validation = Validator::make($request->all(), [
                    'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s]).+$/',
                    'confirm_password' => 'required|same:new_password'
                ], [
                    'new_password.required' => 'Password baru tidak boleh kosong',
                    'new_password.string' => 'Password baru harus text',
                    'new_password.min' => 'Password baru minimal 8 karakter',
                    'new_password.regex' => 'Password baru harus mengandung, 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol',
                    'confirm_password.required' => 'Konfirmasi password tidak boleh kosong',
                    'confirm_password.same' => 'Konfirmasi password harus sama dengan password baru'
                ]);

                if ($validation->fails()) {
                    return redirect()->back()->withErrors($validation);
                } else {
                    try {
                        QueryAPI::update('penerbit', $user->ID, [
                            'isbn_password1' => md5($request->new_password),
                            'isbn_password2' => Main::AESCrypt($request->new_password, config('inlis.aes_key'), config('inlis.aes_iv')),
                            'updateby' => $user->ISBN_USER_NAME,
                            'updatedate' => date('Y-m-d H:i:s'),
                            'updateterminal' => $request->ip(),
                        ], false);

                        $settings = QueryAPI::get("
                            select
                                *
                            from
                                e_settings
                            where
                                slug = 'GantiPassword' or
                                (
                                    slug in ('Header','Footer') and
                                    province_id = " . session('province_id') . "
                                )
                        ");

                        $templateEmailContent = null;
                        $templateEmailHeader = null;
                        $templateEmailFooter = null;

                        if ($settings) {
                            foreach ($settings as $setting) {
                                if ($setting->SLUG == 'GantiPassword') {
                                    $templateEmailContent = $setting;
                                } elseif ($setting->SLUG == 'Header') {
                                    $templateEmailHeader = $setting;
                                } elseif ($setting->SLUG == 'Footer') {
                                    $templateEmailFooter = $setting;
                                }
                            }
                        }

                        $bodyEmail = [
                            'name' => $user->NAME,
                            'email' => $user->EMAIL1,
                            'header' => '<img src="' . Main::base64File(url('stream-file?type=gambar_template&id=' . ($templateEmailHeader->ID ?? '') . '&filename=' . ($templateEmailHeader->CONTENT ?? ''))) . '" style="max-width:100%;">',
                            'footer' => '<img src="' . Main::base64File(url('stream-file?type=gambar_template&id=' . ($templateEmailFooter->ID ?? '') . '&filename=' . ($templateEmailFooter->CONTENT ?? ''))) . '" style="max-width:100%; margin-bottom:10px">',
                        ];

                        Mail::send([], [], function ($message) use ($bodyEmail, $templateEmailContent) {
                            $message->to($bodyEmail['email'], $bodyEmail['name'])
                                ->subject('Berhasil Reset Password')
                                ->from(config('mail.from.address'), config('mail.from.name'))
                                ->html(Main::parseTemplateEmail($bodyEmail, $templateEmailContent), 'text/html');
                        });

                        return redirect('/')->with([
                            'success' => 'Password berhasil direset'
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->back()->with([
                            'failed' => $e->getMessage()
                        ]);
                    }
                }
            }

            return view('reset-password-action');
        } else {
            abort(404);
        }
    }

    public function checkAjaxPassword(Request $request)
    {
        $username = session('username');
        $password = $request->password;
        $login = QueryAPI::login($username, $password);

        $response = [
            'code' => 500,
            'message' => 'Verifikasi ditolak, masukan password yang benar'
        ];

        if (($login->Status ?? '') == 'Success') {
            $userId = $login->Data->Id ?? null;
            $user = QueryAPI::get("
                select
                    penerbit.*,
                    propinsi.namapropinsi as namapropinsi
                from
                    penerbit
                left join
                    propinsi on propinsi.id = penerbit.province_id
                where
                    penerbit.id = $userId
            ", true);

            if ($user) {
                $response = [
                    'code' => 200,
                    'message' => 'Verifikasi diterima'
                ];
            }
        }

        return response()->json($response);
    }

    public function logout()
    {
        session()->flush();
        session()->regenerate();

        return redirect('/')->with('success', 'Anda telah berhasil logout');
    }
}
