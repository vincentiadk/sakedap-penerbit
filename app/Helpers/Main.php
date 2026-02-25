<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class Main
{
    const BARANTUM_TEMPLATE_ID_OTP = 'ef80f681-be61-4c32-a98f-9e492e8f2f77';
    const COLLECTION_DIGITAL = 'KRD';
    const COLLECTION_PRINTED = 'KC';
    const COLLECTION_ANALOG = 'KRA';
    const CACHE_NAME_CONFIG_APP = 'app_configuration_ps';
    const CONFIG_PARAM = [
        'EPercobaanLogin',
        'EPercobaanLoginInterval',
        'EAesKey',
        'EAesIV',
        'EAesInlisKey',
        'EAesInlisIV',
        'EIFrameDomain',
        'EBatasResetPassword',
        'EBatasFileOriginal',
        'ETglKepatuhanPenerbit',
        'ERedisClient',
        'ERedisHost',
        'ERedisUsername',
        'ERedisPassword',
        'ERedisPort',
        'ESessionDriver',
        'ESessionLifeTime',
        'ESessionEncrypt',
        'EKatalogCoverMaxUpload',
        'EKatalogContentMaxUpload',
        'EBatasSerahKCKR',
        'EBatasHibah',
        'EBatasPengambilan',
        'EWaktuWajibKaryaCetak',
        'EWaktuWajibKaryaRekam',
        'EMaksJumlahPembinaan',
        'ECaptchaSecret',
        'ECaptchaSite',
        'EAPIISBNToken',
        'EAPIISBNBaseUrl',
        'EAPIRajaOngkirToken',
        'EAPIRajaOngkirBaseUrl',
    ];

    /**
     * generateNumberDeposit
     *
     * @return void
     */
    public static function generateNumberDeposit()
    {
        $seq = 1;
        $yearNow = date('Y');

        $data = QueryAPI::get("
            select
                max(substr(deposit, -5)) as unique_code
            from
                e_collections
            where
                deposit is not null and
                to_char(created_at, 'YYYY') = '$yearNow'
        ", true);

        if ($data) {
            $seq = (int) $data->UNIQUE_CODE;
            $seq += 1;
            $seq = sprintf('%05d', $seq);
        }

        $numbering = 'DEP' . date('Ymd') . $seq;

        return $numbering;
    }

    /**
     * generateNumberCopy
     *
     * @return void
     */
    public static function generateNumberCopy()
    {
        $date = date('Ymd');
        $seq = 1;

        $data = QueryAPI::get("
            select
                max(substr(code, 8)) as unique_code
            from
                e_collection_copies
            where
                code like '%C$date%'
        ", true);

        if ($data) {
            $seq = (int) $data->UNIQUE_CODE;
            $seq += 1;
        }

        return 'C' . $date . sprintf('%05s', $seq);
    }

    /**
     * locationById
     *
     * @param  mixed $id
     * @param  mixed $for
     * @return void
     */
    public static function locationById($id, $for)
    {
        $data = null;

        if ($for == 'province') {
            $data = QueryAPI::get("
                select
                    *
                from
                    propinsi
                where
                    id = $id
            ", true);
        } else if ($for == 'city') {
            $data = QueryAPI::get("
                select
                    kabupaten.*,
                    propinsi.namapropinsi as namapropinsi
                from
                    kabupaten
                join
                    propinsi on propinsi.id = kabupaten.propinsiid
                where
                    kabupaten.id = $id
            ", true);
        } else if ($for == 'district') {
            $data = QueryAPI::get("
                select
                    kecamatan.*,
                    kabupaten.namakab as namakab,
                    propinsi.namapropinsi as namapropinsi,
                    propinsi.id as propinsiid
                from
                    kecamatan
                join
                    kabupaten on kabupaten.id = kecamatan.kabupatenid
                join
                    propinsi on propinsi.id = kabupaten.propinsiid
                where
                    kecamatan.id = $id
            ", true);
        } else if ($for == 'village') {
            $data = QueryAPI::get("
                select
                    kelurahan.*,
                    kecamatan.namakec as namakec,
                    kabupaten.namakab as namakab,
                    kabupaten.id as kabupatenid,
                    propinsi.namapropinsi as namapropinsi,
                    propinsi.id as propinsiid
                from
                    kelurahan
                join
                    kecamatan on kecamatan.id = kelurahan.kecamatanid
                join
                    kabupaten on kabupaten.id = kecamatan.kabupatenid
                join
                    propinsi on propinsi.id = kabupaten.propinsiid
                where
                    kelurahan.id = $id
            ", true);
        }

        return $data;
    }

    /**
     * contentTypeFile
     *
     * @param  mixed $filename
     * @return void
     */
    public static function contentTypeFile($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if ($extension == 'pdf') {
            $content = 'application/pdf';
        } else if (in_array($extension, ['jpg', 'jpeg'])) {
            $content = 'image/jpeg';
        } else if ($extension == 'png') {
            $content = 'image/png';
        } else if ($extension == 'docx') {
            $content = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        } else if ($extension == 'doc') {
            $content = 'application/msword';
        } else if ($extension == 'xlsx') {
            $content = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } else if ($extension == 'xls') {
            $content = 'application/vnd.ms-excel';
        } else if ($extension == 'epub') {
            $content = 'application/epub+zip';
        } else if ($extension == 'mp3') {
            $content = 'audio/mpeg';
        } else if ($extension == 'mp4') {
            $content = 'video/mp4';
        } else if ($extension == 'wav') {
            $content = 'audio/wav';
        } else if ($extension == 'zip') {
            $content = 'application/zip';
        } else if ($extension == 'rar') {
            $content = 'application/vnd.rar';
        } else {
            $content = 'application/octet-stream';
        }

        return $content;
    }

    /**
     * copyright
     *
     * @param  mixed $executorId
     * @return void
     */
    public static function copyright($executorId = null)
    {
        $text = '';

        if ($executorId) {
            $executor = QueryAPI::get("
                select
                    *
                from
                    penerbit
                where
                    id = $executorId
            ", true);

            if ($executor) {
                $text = 'Copyrights (c) ' . date('Y') . ' ' . $executor->NAME;
            }
        }

        return $text;
    }

    /**
     * parseTemplateEmail
     *
     * @param  mixed $payload
     * @param  mixed $template
     * @return void
     */
    public static function parseTemplateEmail($payload, $template)
    {
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($payload) {
            list($shortCode, $index) = $matches;

            if (isset($payload[$index])) {
                return $payload[$index];
            } else {
                return $shortCode;
            }
        }, $template->CONTENT);

        return (string) $parsed;
    }

    /**
     * AESCrypt
     *
     * @param  mixed $text
     * @return void
     */
    public static function AESCrypt($text, $key = null, $iv = null)
    {
        $cipher = 'aes-256-cbc';
        $key = $key ?? config('system.aes_key');
        $iv = $iv ?? config('system.aes_iv');
        $encrypted = @openssl_encrypt($text, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encrypted);
    }

    /**
     * AESDecrypt
     *
     * @param  mixed $text
     * @return void
     */
    public static function AESDecrypt($text, $key = null, $iv = null)
    {
        $cipher = 'aes-256-cbc';
        $key = $key ?? config('system.aes_key');
        $iv = $iv ?? config('system.aes_iv');
        $decoded = base64_decode($text);

        $decrypted = @openssl_decrypt($decoded, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        return $decrypted;
    }

    /**
     * login
     *
     * @param  mixed $username
     * @param  mixed $password
     * @return void
     */
    public static function login($username, $password)
    {
        $response = false;
        $login = QueryAPI::login($username, $password);

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
                session([
                    'id' => $user->ID,
                    'username' => $user->ISBN_USER_NAME,
                    'name' => $user->NAME,
                    'email' => $user->EMAIL1,
                    'province_id' => $user->PROVINCE_ID ?: 31,
                    'city_id' => $user->CITY_ID ?: null,
                    'province_name' => $user->NAMAPROPINSI ?: 'DKI Jakarta',
                    'phone' => $user->TELP1,
                    'postal_code' => $user->KODEPOS,
                    'address' => $user->ALAMAT,
                    'api_key' => $user->X_API_KEY,
                    'api_status' => $user->IS_API_ENABLE ?: 0,
                    'status' => $user->STATUS ?: 1,
                    'is_isbn' => $user->IS_ISBN ?: 0,
                ]);

                Cache::forget('executor_group_' . $user->ID);

                $response = true;
            }
        }

        return $response;
    }

    /**
     * credentialInlisIFrame
     *
     * @return void
     */
    public static function credentialInlisIFrame()
    {
        $userId = session('id');
        $encFrameInlis = static::AESCrypt(
            "userid=$userId;auth=1",
            base64_decode(config('inlis.aes_key')),
            base64_decode(config('inlis.aes_iv'))
        );

        return $encFrameInlis;
    }

    /**
     * base64File
     *
     * @param  mixed $url
     * @return void
     */
    public static function base64File($url)
    {
        $getContent = file_get_contents($url);
        $base64 = base64_encode($getContent);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $getContent);
        $link = "data:$mimeType;base64," . $base64;

        return $link;
    }

    /**
     * formatFileSize
     *
     * @param  mixed $bytes
     * @param  mixed $precision
     * @return void
     */
    public static function formatFileSize($bytes, $precision = 2)
    {
        $bytes = (int) $bytes;
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        if ($bytes == 0) {
            return '0 ' . $units[0];
        }

        $power = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $power), $precision) . ' ' . $units[$power];
    }

    /**
     * method
     *
     * @param  mixed $value
     * @return void
     */
    public static function method($value)
    {
        $text = '';

        if ($value == 1) {
            $text = 'API';
        } else if ($value == 2) {
            $text = 'SFTP';
        } else if ($value == 3) {
            $text = 'Mandiri';
        } else if ($value == 4) {
            $text = 'Manual';
        } else if ($value == 5) {
            $text = 'Sistem';
        } else if ($value == 6) {
            $text = 'Bulk Penerbit';
        } else if ($value == 7) {
            $text = 'Bulk Admin';
        }

        return $text;
    }

    /**
     * getBranch
     *
     * @param  mixed $provinceId
     * @return void
     */
    public static function getBranch($provinceId = null)
    {
        $provinceId = $provinceId ?? session('province_id');
        $cacheKey = 'branch_data_province_' . ($provinceId ?? 'global');

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $branch = QueryAPI::get("
            select
                *
            from
                branchs
            where
                province_id = $provinceId and
                isprovince = 1
        ", true);

        if ($branch) {
            Cache::put($cacheKey, $branch, 60 * 60);
        }

        return $branch;
    }

    /**
     * mappingETD
     *
     * @param  mixed $value
     * @return void
     */
    public static function mappingETD($value)
    {
        $etd = trim(strtolower($value));

        if ($etd === '' || $etd === '-' || $etd === null) {
            return 'Estimasi tidak tersedia';
        }

        if (preg_match('/(\d+)\s*-\s*(\d+)\s*hour/', $etd, $m)) {
            return "{$m[1]}–{$m[2]} Jam";
        }

        if (preg_match('/(\d+)\s*hour/', $etd, $m)) {
            return "{$m[1]} Jam";
        }

        if (preg_match('/(\d+)\s*-\s*(\d+)\s*day/', $etd, $m)) {
            return "{$m[1]}–{$m[2]} Hari";
        }

        if (preg_match('/(\d+)\s*day/', $etd, $m)) {
            return "{$m[1]} Hari";
        }

        return ucwords($etd);
    }

    /**
     * formatPhoneKomship
     *
     * @param  mixed $phone
     * @return void
     */
    public static function formatPhoneKomship($phone)
    {
        $number = preg_replace('/[^0-9]/', '', $phone);

        if (empty($number)) {
            return '';
        }

        if (str_starts_with($number, '0')) {
            $number = substr($number, 1);
        }

        if (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * getExecutorGroup
     *
     * @return void
     */
    public static function getExecutorGroup($forceRefresh = false)
    {
        $id = session('id');

        if (!$id) {
            return [];
        }

        $cacheKey = 'executor_group_' . $id;

        if ($forceRefresh) {
            Cache::forget($cacheKey);

            session()->forget('group');
        }

        $result = Cache::rememberForever($cacheKey, function () use ($id) {
            $dataGroup = QueryAPI::get("
                select
                    e_publisher_access.publisher_group_id,
                    e_publisher_groups.name
                from
                    e_publisher_access
                inner join
                    e_publisher_groups on e_publisher_groups.id = e_publisher_access.publisher_group_id
                where
                    e_publisher_access.publisher_id = $id
                    and e_publisher_access.deleted_at is null
                    and rownum = 1
            ", true);

            if (!$dataGroup) {
                return [
                    'group_name' => null,
                    'executors' => []
                ];
            }

            $groupId = $dataGroup->PUBLISHER_GROUP_ID ?? $dataGroup->publisher_group_id;
            $groupName = $dataGroup->NAME ?? $dataGroup->name;

            $executors = QueryAPI::get("
                select
                    distinct penerbit.*
                from
                    penerbit
                inner join
                    e_publisher_access on e_publisher_access.publisher_id = penerbit.id
                inner join
                    e_publisher_groups on e_publisher_groups.id = e_publisher_access.publisher_group_id
                where
                    e_publisher_access.publisher_group_id = $groupId
                    and e_publisher_access.deleted_at IS NULL
            ");

            return [
                'group_name' => $groupName,
                'executors' => $executors ?? []
            ];
        });

        session(['group' => $result['group_name']]);

        return $result['executors'] ?? [];
    }

    /**
     * phoneFormat
     *
     * @param  mixed $value
     * @return void
     */
    public static function phoneFormat($value = '')
    {
        if (empty($value)) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9]/', '', $value);

        if (empty($cleaned)) {
            return null;
        }

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * getCoverISBN
     *
     * @param  mixed $fileUrl
     * @return void
     */
    public static function getCoverISBN($fileUrl = null)
    {
        $baseUrl = config('inlis.base_url');

        if ($fileUrl) {
            $link = $baseUrl . $fileUrl;
        } else {
            $link = asset('assets/no-file.jpg');
        }

        return $link;
    }
}
