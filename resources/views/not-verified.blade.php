<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="url" content="{{ url('/') }}">
    <meta name="user-id" content="{{ session('id') }}">
    <title>SAKEDAP | Verifikasi Akun Pelaksana Serah Panel</title>
    <link rel="shortcut icon" href="{{ asset('assets/icon.png') }}?v={{ uniqid() }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/icon.png') }}?v={{ uniqid() }}">
    <link href="{{ asset('themes/fonts/inter/inter.css') }}?v={{ uniqid() }}" rel="stylesheet">
    <link href="{{ asset('themes/icons/phosphor/styles.min.css') }}?v={{ uniqid() }}" rel="stylesheet">
    <link href="{{ asset('plugins/waitMe/waitMe.min.css') }}?v={{ uniqid() }}" rel="stylesheet">
    <link href="{{ asset('themes/css/ltr/all.min.css') }}?v={{ uniqid() }}" id="stylesheet" rel="stylesheet">
    <script src="{{ asset('themes/js/bootstrap/bootstrap.bundle.min.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('themes/js/jquery/jquery.min.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('plugins/waitMe/waitMe.min.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('themes/js/vendor/notifications/sweet_alert.min.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('themes/js/vendor/forms/selects/select2.min.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('themes/js/vendor/forms/selects/select2-lang/id.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('themes/js/app.js') }}?v={{ uniqid() }}"></script>
    <script src="{{ asset('plugins/custom.js') }}?v={{ uniqid() }}"></script>

    <style>
        .verification-status {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .verification-status.pending {
            border-color: #ffc107;
        }

        .verification-status.revision {
            border-color: #dc3545;
        }

        .timeline-item {
            position: relative;
            padding-left: 35px;
            padding-bottom: 25px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item:last-child:before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 8px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .file-preview {
            transition: all 0.2s ease;
        }

        .file-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="navbar navbar-expand-xl navbar-static shadow-sm">
        <div class="container-fluid">
            <div class="navbar-brand flex-1">
                <a href="{{ url('home') }}" class="d-inline-flex align-items-center">
                    <img src="{{ asset('assets/icon.png') }}" alt="Logo" class="me-2">
                    <span class="fs-4 pt-1 text-dark fw-bold">SAKEDAP</span>
                </a>
            </div>
            <ul class="nav gap-2 flex-xl-1 justify-content-end order-0 order-xl-1">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="navbar-nav-link align-items-center rounded-pill p-1">
                        <img src="{{ asset('assets/user.png') }}" class="w-32px h-32px rounded-pill" alt="">
                        <span class="d-none d-md-inline-block mx-md-2">{{ session('name') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="navbar-nav-link text-danger rounded" onclick="logout()">
                        <i class="ph-sign-out me-1"></i>
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content pt-4 pb-5">
                    <div class="row">
                        <div class="col-xl-10 offset-xl-1">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <i class="ph-x-circle ph-2x me-3"></i>
                                        <div class="flex-fill">
                                            <h6 class="alert-heading fw-semibold mb-2">Terjadi Kesalahan:</h6>
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <i class="ph-check-circle ph-2x me-3"></i>
                                        <div class="flex-fill">
                                            <h6 class="alert-heading fw-semibold mb-1">Berhasil!</h6>
                                            <p class="mb-0">{{ session('success') }}</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <i class="ph-x-circle ph-2x me-3"></i>
                                        <div class="flex-fill">
                                            <h6 class="alert-heading fw-semibold mb-1">Gagal!</h6>
                                            <p class="mb-0">{{ session('error') }}</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            @endif
                            @php
                                $status = $executor->STATUS ?: 3;
                                $isPending = $status == 1;
                                $isRevision = $status == 3;
                            @endphp
                            @if($isPending)
                                <div class="card verification-status pending border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-4">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                                    <i class="ph-clock-clockwise text-warning" style="font-size: 48px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <h5 class="mb-3 fw-bold">
                                                    <i class="ph-hourglass-medium me-1 text-warning"></i>
                                                    Akun Sedang Dalam Proses Verifikasi
                                                </h5>
                                                <p class="mb-3 text-muted">
                                                    Terima kasih telah melakukan pendaftaran di sistem SAKEDAP.
                                                    Data yang Anda kirimkan saat ini sedang dalam proses verifikasi oleh Tim Perpustakaan Nasional Republik Indonesia.
                                                </p>
                                                <div class="alert alert-warning border-0 mb-0">
                                                    <h6 class="alert-heading fw-semibold">
                                                        <i class="ph-info me-1"></i>
                                                        Informasi Penting
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-clock text-warning me-1 mt-1"></i>
                                                                <div>
                                                                    <strong>Waktu Verifikasi</strong>
                                                                    <p class="mb-0 fs-sm">Proses biasanya memakan waktu 1-3 hari kerja</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-envelope text-warning me-1 mt-1"></i>
                                                                <div>
                                                                    <strong>Notifikasi Email</strong>
                                                                    <p class="mb-0 fs-sm">Anda akan menerima notifikasi setelah verifikasi selesai</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-envelope-open text-warning me-1 mt-1"></i>
                                                                <div>
                                                                    <strong>Email Aktif</strong>
                                                                    <p class="mb-0 fs-sm">Pastikan email yang Anda daftarkan dapat menerima pesan</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-question text-warning me-1 mt-1"></i>
                                                                <div>
                                                                    <strong>Bantuan</strong>
                                                                    <p class="mb-0 fs-sm">Hubungi: <a href="mailto:depbangkol@gmail.com" class="fw-semibold">depbangkol@gmail.com</a></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($isRevision)
                                <div class="card verification-status revision border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-4">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                                    <i class="ph-warning-circle text-danger" style="font-size: 48px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <h5 class="mb-3 fw-bold">
                                                    <i class="ph-pencil-line me-1 text-danger"></i>
                                                    Akun Memerlukan Perbaikan Data
                                                </h5>
                                                <p class="mb-3 text-muted">
                                                    Berdasarkan hasil verifikasi oleh Tim Perpustakaan Nasional, terdapat beberapa data yang perlu diperbaiki atau dilengkapi.
                                                    Silakan periksa catatan di bawah dan lakukan perbaikan yang diperlukan.
                                                </p>
                                                <div class="alert alert-danger border-0 mb-0">
                                                    <h6 class="alert-heading fw-semibold">
                                                        <i class="ph-warning me-1"></i>
                                                        Tindakan yang Diperlukan
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-list-checks text-danger me-1"></i>
                                                                <span class="mb-0 fs-sm">Periksa <strong>Histori Masalah</strong> untuk detail perbaikan</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-pencil text-danger me-1"></i>
                                                                <span class="mb-0 fs-sm">Perbaiki data sesuai catatan yang diberikan</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-files text-danger me-1"></i>
                                                                <span class="mb-0 fs-sm">Lengkapi semua dokumen pendukung dengan benar</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-start">
                                                                <i class="ph-paper-plane-tilt text-danger me-1"></i>
                                                                <span class="mb-0 fs-sm">Klik <strong>"Simpan & Ajukan Ulang"</strong> setelah selesai</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-danger text-white border-0">
                                        <h6 class="mb-0">
                                            <i class="ph-warning-octagon me-1"></i>
                                            Histori Masalah & Catatan Verifikasi
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-start border-danger border-3 mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-lightbulb text-warning fs-2 me-3"></i>
                                                <div>
                                                    <strong>Petunjuk:</strong> Berikut adalah catatan dari tim verifikator mengenai data yang perlu diperbaiki.
                                                    Harap baca dengan teliti dan lakukan perbaikan sesuai instruksi.
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($problemHistory) > 0)
                                            <div class="timeline">
                                                @foreach($problemHistory as $key => $ph)
                                                    <div class="timeline-item">
                                                        <div class="timeline-dot"></div>
                                                        <div class="card border-0">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                                    <span class="badge bg-danger">Revisi #{{ $key + 1 }}</span>
                                                                    <small class="text-muted">
                                                                        <i class="ph-calendar-blank me-1"></i>
                                                                        {{ \Carbon\Carbon::parse($ph->CREATEDATE)->format('d/m/Y H:i') }} WIB
                                                                    </small>
                                                                </div>
                                                                <div class="d-flex align-items-start">
                                                                    <i class="ph-chat-circle-text text-danger me-1 mt-1"></i>
                                                                    <p class="mb-0">{{ $ph->MASALAH }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-5">
                                                <i class="ph-check-circle text-success mb-3" style="font-size: 64px;"></i>
                                                <p class="mb-0 fw-semibold">Tidak ada catatan masalah</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($isRevision)
                                <form method="POST" enctype="multipart/form-data" onsubmit="onLoading('show', 'body')">
                                    @csrf
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-user-circle me-1 text-primary"></i>
                                                <h6 class="mb-0 fw-semibold">Informasi Umum</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-user me-1"></i>
                                                        Nama Lengkap
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $executor->NAME) }}" placeholder="Masukkan nama lengkap pelaksana serah" required>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-identification-card me-1"></i>
                                                        Alias
                                                    </label>
                                                    <input type="text" class="form-control" name="alias" id="alias" value="{{ old('alias', $executor->ALIAS) }}" placeholder="Nama singkat atau alias">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-tag me-1"></i>
                                                        Kategori
                                                    </label>
                                                    <input type="text" class="form-control bg-light" value="{{ $executor->NAME_PENERBIT_KATEGORI ?? '-' }}" readonly readonly>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-list-bullets me-1"></i>
                                                        Jenis
                                                    </label>
                                                    <input type="text" class="form-control bg-light" value="{{ $executor->NAME_PENERBIT_JENIS ?? '-' }}" readonly readonly>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-buildings me-1"></i>
                                                        Lembaga Penaung
                                                    </label>
                                                    <input type="text" class="form-control" name="shelter_institution" id="shelter_institution" value="{{ old('shelter_institution', $executor->LEMBAGA_PENAUNG) }}" placeholder="Nama lembaga penaung (jika ada)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-map-pin me-1 text-primary"></i>
                                                <h6 class="mb-0 fw-semibold">Alamat & Lokasi</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-map-trifold me-1"></i>
                                                        Wilayah
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" name="location_id" id="location_id" data-placeholder="Pilih Kelurahan" required>
                                                        @if($executor->NAMAPROPINSI && $executor->NAMAKAB && $executor->NAMAKEC && $executor->NAMAKEL)
                                                            <option value="{{ $executor->VILLAGE_ID }}" selected>
                                                                {{ $executor->NAMAPROPINSI }} ->
                                                                {{ $executor->NAMAKAB }} ->
                                                                {{ $executor->NAMAKEC }} ->
                                                                {{ $executor->NAMAKEL }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-note me-1"></i>
                                                        Kode Pos
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{ old('postal_code', $executor->KODEPOS) }}" placeholder="contoh: 60119" maxlength="5" required>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-signpost me-1"></i>
                                                        Alamat Lengkap
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('address', $executor->ALAMAT) }}</textarea>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-buildings me-1"></i>
                                                        Nama Gedung
                                                    </label>
                                                    <input type="text" class="form-control" name="building_name" id="building_name" value="{{ old('building_name', $executor->NAMA_GEDUNG) }}" placeholder="Nama gedung kantor (jika ada)">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-globe me-1"></i>
                                                        Website
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-globe"></i>
                                                        </span>
                                                        <input type="url" class="form-control" name="website" id="website" value="{{ old('website', $executor->WEBSITE) }}" placeholder="https://www.example.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-user-circle-gear me-1 text-primary"></i>
                                                <h6 class="mb-0 fw-semibold">Kontak Person</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-user-circle-plus me-1"></i>
                                                        Nama Admin Utama
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="contact1" id="contact1" value="{{ old('contact1', $executor->KONTAK1) }}" placeholder="Nama kontak person utama" required>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-user-circle me-1"></i>
                                                        Nama Admin Alternatif
                                                    </label>
                                                    <input type="text" class="form-control" name="contact2" id="contact2" value="{{ old('contact2', $executor->KONTAK2) }}" placeholder="Nama kontak person alternatif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-phone me-1 text-primary"></i>
                                                <h6 class="mb-0 fw-semibold">Informasi Kontak</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-envelope me-1"></i>
                                                        Email Utama
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-envelope"></i>
                                                        </span>
                                                        <input type="email" class="form-control bg-light" name="email1" id="email1" value="{{ old('email1', $executor->EMAIL1) }}" placeholder="email@example.com" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-envelope-simple me-1"></i>
                                                        Email Alternatif
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-envelope-simple"></i>
                                                        </span>
                                                        <input type="email" class="form-control" name="email2" id="email2" value="{{ old('email2', $executor->EMAIL2) }}" placeholder="email.alternatif@example.com">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-device-mobile me-1"></i>
                                                        No. Telepon Utama
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-phone"></i>
                                                        </span>
                                                        <input type="text" class="form-control bg-light" name="phone1" id="phone1" value="{{ old('phone1', $executor->TELP1) }}" placeholder="08xxxxxxxxxx" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-device-mobile-speaker me-1"></i>
                                                        No. Telepon Alternatif
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-phone"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="phone2" id="phone2" value="{{ old('phone2', $executor->TELP2) }}" placeholder="08xxxxxxxxxx">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-phone-call me-1"></i>
                                                        Fax Utama
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-phone-disconnect"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="fax1" id="fax1" value="{{ old('fax1', $executor->FAX1) }}" placeholder="031-xxxxxxx">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-phone-call me-1"></i>
                                                        Fax Alternatif
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-phone-disconnect"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="fax2" id="fax2" value="{{ old('fax2', $executor->FAX2) }}" placeholder="031-xxxxxxx">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-info me-1 text-primary"></i>
                                                <h6 class="mb-0 fw-semibold">Informasi Tambahan</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-chart-line me-1"></i>
                                                        Rata-rata Terbitan per Tahun
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-book-open"></i>
                                                        </span>
                                                        <input type="number" class="form-control" name="avg_publication" id="avg_publication" value="{{ old('avg_publication', $executor->RATA_TERBITAN) }}" placeholder="0" min="0">
                                                        <span class="input-group-text">Koleksi</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="ph-files me-1"></i>
                                                Dokumen Pendukung
                                            </h6>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="alert alert-info border-0 mb-4">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-file-text ph-2x text-info me-3"></i>
                                                    <div>
                                                        <h6 class="alert-heading fw-semibold mb-2">Ketentuan Dokumen:</h6>
                                                        <ul class="mb-0">
                                                            <li>File dalam format <strong>PDF</strong></li>
                                                            <li>Ukuran maksimal <strong>5 MB</strong> per file</li>
                                                            <li>Dokumen harus jelas dan dapat dibaca</li>
                                                            <li>Nama file tidak mengandung karakter khusus</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-file-text me-1"></i>
                                                        Surat Pernyataan
                                                        <span class="badge bg-danger ms-1">Wajib</span>
                                                    </label>
                                                    @if($executor->FILE_SP)
                                                        <div class="border rounded p-3 mb-3 file-preview">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                                                        <i class="ph-file-pdf text-danger fs-2"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-semibold">Surat_Pernyataan.pdf</div>
                                                                        <small class="text-muted">
                                                                            <i class="ph-check-circle text-success me-1"></i>
                                                                            Dokumen tersimpan
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ url('stream-file?filename=' . $executor->FILE_SP . '&type=penerbit_surat_pernyataan&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                    <i class="ph-eye me-1"></i> Lihat
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="border rounded p-3 mb-3 text-center text-muted bg-light">
                                                            <i class="ph-file-x fs-2 mb-2 opacity-50"></i>
                                                            <div><small>Dokumen belum tersedia</small></div>
                                                        </div>
                                                    @endif
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-upload-simple"></i>
                                                        </span>
                                                        <input type="file" name="file_statement" id="file_statement" class="form-control" accept=".pdf">
                                                    </div>
                                                    <small class="form-text text-muted d-block mt-1">
                                                        <i class="ph-info me-1"></i>
                                                        Upload ulang jika ingin mengganti dokumen
                                                    </small>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ph-file-text me-1"></i>
                                                        Akta Notaris
                                                        <span class="badge bg-secondary ms-1">Opsional</span>
                                                    </label>
                                                    @if($executor->FILE_AKTE_NOTARIS)
                                                        <div class="border rounded p-3 mb-3 file-preview">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                                                        <i class="ph-file-pdf text-danger fs-2"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-semibold">Akta_Notaris.pdf</div>
                                                                        <small class="text-muted">
                                                                            <i class="ph-check-circle text-success me-1"></i>
                                                                            Dokumen tersimpan
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ url('stream-file?filename=' . $executor->FILE_AKTE_NOTARIS . '&type=penerbit_akte_notaris&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                    <i class="ph-eye me-1"></i> Lihat
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="border rounded p-3 mb-3 text-center text-muted bg-light">
                                                            <i class="ph-file-x fs-2 mb-2 opacity-50"></i>
                                                            <div><small>Dokumen belum tersedia</small></div>
                                                        </div>
                                                    @endif
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="ph-upload-simple"></i>
                                                        </span>
                                                        <input type="file" name="file_deed" id="file_deed" class="form-control" accept=".pdf">
                                                    </div>
                                                    <small class="form-text text-muted d-block mt-1">
                                                        <i class="ph-info me-1"></i>
                                                        Upload ulang jika ingin mengganti dokumen
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="ph-shield-check ph-2x me-3"></i>
                                                    <small>
                                                        Dengan mengirimkan data ini, saya menyatakan bahwa semua informasi
                                                        yang diberikan adalah benar dan dapat dipertanggungjawabkan.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ph-paper-plane-tilt me-1"></i>
                                                    Simpan & Ajukan Ulang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                            @if($isPending)
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ph-eye me-1"></i>
                                            Data yang Sedang Diverifikasi
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-start border-warning border-3 mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-lock ph-2x text-warning me-3"></i>
                                                <div>
                                                    <strong>Informasi:</strong> Data di bawah ini sedang dalam proses verifikasi.
                                                    Anda tidak dapat melakukan perubahan hingga proses verifikasi selesai.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-buildings text-primary me-1 mt-1"></i>
                                                    <div>
                                                        <label class="text-muted small mb-1">Nama Pelaksana Serah</label>
                                                        <div class="fw-semibold">{{ $executor->NAME }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-tag text-primary me-1 mt-1"></i>
                                                    <div>
                                                        <label class="text-muted small mb-1">Alias</label>
                                                        <div class="fw-semibold">{{ $executor->ALIAS ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-envelope text-primary me-1 mt-1"></i>
                                                    <div>
                                                        <label class="text-muted small mb-1">Email</label>
                                                        <div class="fw-semibold">{{ $executor->EMAIL1 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-phone text-primary me-1 mt-1"></i>
                                                    <div>
                                                        <label class="text-muted small mb-1">Telepon</label>
                                                        <div class="fw-semibold">{{ $executor->TELP1 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph-map-pin text-primary me-1 mt-1"></i>
                                                    <div>
                                                        <label class="text-muted small mb-1">Alamat</label>
                                                        <div class="fw-semibold">{{ $executor->ALAMAT }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-sm navbar-footer border-top">
        <div class="container-fluid">
            <span class="text-muted">
                &copy; {{ date('Y') }}
                <a href="https://edeposit.perpusnas.go.id" target="_blank" class="text-primary">
                    SAKEDAP | Sistem Akses Elektronik Deposit Aman dan Praktis
                </a>
            </span>
            <ul class="nav">
                <li class="nav-item">
                    <a href="https://perpusnas.go.id" target="_blank" class="navbar-nav-link rounded">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-globe me-1"></i>
                            <span class="d-none d-md-inline-block">Website Resmi</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        $(function() {
            select2Serverside('#location_id', 'location');

            $('input[type="file"]').on('change', function() {
                const file = this.files[0];
                const $input = $(this);

                if (file) {
                    if (file.size > 5242880) {
                        swalInit.fire({
                            title: 'Ukuran File Terlalu Besar',
                            text: 'Ukuran file maksimal 5 MB. Silakan pilih file yang lebih kecil.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                        $input.val('');

                        return false;
                    }

                    if (file.type !== 'application/pdf') {
                        swalInit.fire({
                            title: 'Format File Salah',
                            text: 'Hanya file PDF yang diperbolehkan. Silakan pilih file PDF.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                        $input.val('');

                        return false;
                    }
                }
            });

            $('#postal_code').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
            });

            $('input[name="phone2"]').on('input', function() {
                this.value = this.value.replace(/[^0-9-]/g, '');
            });
        });
    </script>
</body>
</html>
