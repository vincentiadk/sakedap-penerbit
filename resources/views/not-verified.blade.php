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
            border-color: #FFD648;
            background-color: #fff8e1;
        }

        .verification-status.revision {
            border-color: #EF4444;
            background-color: #ffebee;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item:last-child:before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 5px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e0e0e0;
        }

        .timeline-dot.active {
            background: #EF4444;
            border-color: #EF4444;
        }
    </style>

</head>
<body>
    <div class="navbar navbar-expand-xl navbar-static shadow">
		<div class="container-fluid">
			<div class="navbar-brand flex-1">
				<a href="{{ url('home') }}" class="d-inline-flex align-items-center">
					<img src="{{ asset('assets/icon.png') }}" alt="Logo">
                    <span class="ms-2 fs-4 pt-1 text-dark fw-bold">SAKEDAP</span>
				</a>
			</div>
			<ul class="nav gap-1 flex-xl-1 justify-content-end order-0 order-xl-1">
				<li class="nav-item">
					<a href="javascript:void(0);" class="navbar-nav-link align-items-center rounded-pill p-1 bg-transparent no-click">
                        <img src="{{ asset('assets/user.png') }}" class="w-32px h-32px rounded-pill" alt="">
						<span class="d-none d-md-inline-block mx-md-2">{{ session('name') }}</span>
					</a>
				</li>
                <li class="nav-item">
					<a href="javascript:void(0);" class="navbar-nav-link text-danger rounded" onclick="logout()">
                        <i class="ph-sign-out me-2"></i>
                        Keluar
                    </a>
				</li>
			</ul>
		</div>
	</div>
    <div class="page-content">
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content pt-4">
                    <div class="row">
                        <div class="col-xl-10 offset-xl-1">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <span class="fw-semibold">
                                        <i class="ph-x-circle me-1"></i> Terjadi Kesalahan:
                                    </span>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <span class="fw-semibold">
                                        <i class="ph-check-circle me-1"></i> Berhasil!
                                    </span>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <span class="fw-semibold">
                                        <i class="ph-x-circle me-1"></i> Gagal!
                                    </span>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @php
                                $status = $executor->STATUS;
                                $isPending = $status == 1;
                                $isRevision = $status == 2;
                            @endphp
                            @if($isPending)
                                <div class="card verification-status pending">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="ph-clock-clockwise text-warning" style="font-size: 48px;"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <h5 class="mb-2 fw-bold text-warning">
                                                    <i class="ph-hourglass-medium me-1"></i>
                                                    Akun Sedang Dalam Proses Verifikasi
                                                </h5>
                                                <p class="mb-3">
                                                    <div>Terima kasih telah melakukan pendaftaran di sistem SAKEDAP (Serah Karya Demi Akses Perpustakaan).</div>
                                                    <div>Data yang Anda kirimkan saat ini sedang dalam proses verifikasi oleh Tim Perpustakaan Nasional Republik Indonesia.</div>
                                                </p>
                                                <div class="alert alert-warning alert-dismissible mb-0">
                                                    <div class="alert-heading fw-semibold">
                                                        <i class="ph-info me-1"></i>
                                                        Informasi Penting :
                                                    </div>
                                                    <ul class="mb-0">
                                                        <li>Proses verifikasi biasanya memakan waktu <strong>1-3 hari kerja</strong></li>
                                                        <li>Anda akan menerima notifikasi melalui email setelah verifikasi selesai</li>
                                                        <li>Pastikan email yang Anda daftarkan aktif dan dapat menerima pesan</li>
                                                        <li>Jika ada pertanyaan, hubungi kami di email: <strong>depbangkol@gmail.com</strong></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($isRevision)
                                <div class="card verification-status revision">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="ph-warning-circle text-danger" style="font-size: 48px;"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <h5 class="mb-2 fw-bold text-danger">
                                                    <i class="ph-pencil-line me-1"></i>
                                                    Akun Memerlukan Perbaikan Data
                                                </h5>
                                                <p class="mb-3">
                                                    <div>Berdasarkan hasil verifikasi oleh Tim Perpustakaan Nasional, terdapat beberapa data yang perlu diperbaiki atau dilengkapi.</div>
                                                    <div>Silakan periksa histori masalah di bawah ini dan lakukan perbaikan sesuai catatan yang diberikan.</div>
                                                </p>
                                                <div class="alert alert-danger alert-dismissible mb-0">
                                                    <div class="alert-heading fw-semibold">
                                                        <i class="ph-warning me-1"></i>
                                                        Tindakan yang Diperlukan :
                                                    </div>
                                                    <ul class="mb-0">
                                                        <li>Periksa <strong>Histori Masalah</strong> di bawah untuk mengetahui data yang perlu diperbaiki</li>
                                                        <li>Perbaiki data sesuai dengan catatan yang diberikan</li>
                                                        <li>Pastikan semua dokumen pendukung telah dilengkapi dengan benar</li>
                                                        <li>Klik tombol <strong>"Simpan & Ajukan Ulang"</strong> setelah selesai memperbaiki</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="mb-0">
                                            <i class="ph-warning-octagon me-1"></i>
                                            Histori Masalah & Catatan Verifikasi
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-light border-start border-danger border-3 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-lightbulb text-warning fs-3 me-3"></i>
                                                <div>
                                                    <strong>Petunjuk :</strong> Berikut adalah catatan dari tim verifikator mengenai data yang perlu diperbaiki.
                                                    Harap baca dengan teliti dan lakukan perbaikan sesuai instruksi.
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($problemHistory) > 0)
                                            <div class="timeline">
                                                @foreach($problemHistory as $key => $ph)
                                                    <div class="timeline-item">
                                                        <div class="timeline-dot active"></div>
                                                        <div class="card bg-light">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <span class="badge bg-danger">Revisi #{{ $key + 1 }}</span>
                                                                    <small class="text-muted">
                                                                        <i class="ph-calendar-blank me-1"></i>
                                                                        {{ \Carbon\Carbon::parse($ph->CREATEDATE)->format('d/m/Y H:i') }}
                                                                    </small>
                                                                </div>
                                                                <p class="mb-0 fw-semibold">{{ $ph->MASALAH }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <i class="ph-check-circle text-success" style="font-size: 48px;"></i>
                                                <p class="mb-0 mt-2">Tidak ada catatan masalah</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($isRevision)
                                <form method="POST" enctype="multipart/form-data" onsubmit="onLoading('show', 'body')">
                                    @csrf
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-user-circle me-1"></i>
                                                Informasi Umum Pelaksana Serah
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Nama Lengkap Pelaksana Serah
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $executor->NAME) }}" placeholder="Masukkan nama lengkap penerbit" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alias / Nama Singkat</label>
                                                        <input type="text" class="form-control" name="alias" id="alias" value="{{ old('alias', $executor->ALIAS) }}" placeholder="Nama singkat atau alias (opsional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kategori Pelaksana Serah</label>
                                                        <input type="text" class="form-control" value="{{ $executor->NAME_PENERBIT_KATEGORI ?? 'Belum ditentukan' }}" disabled readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Pelaksana Serah</label>
                                                        <input type="text" class="form-control" value="{{ $executor->NAME_PENERBIT_JENIS ?? 'Belum ditentukan' }}" disabled readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Lembaga Penaung</label>
                                                        <input type="text" class="form-control" name="shelter_institution" id="shelter_institution" value="{{ old('shelter_institution', $executor->LEMBAGA_PENAUNG) }}" placeholder="Nama lembaga penaung (jika ada)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-map-pin me-1"></i>
                                                Alamat & Lokasi Kantor
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Wilayah
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select class="form-select" name="location_id" id="location_id" data-placeholder="Pilih Kelurahan" required>
                                                            @if($executor->NAMAPROPINSI && $executor->NAMAKAB && $executor->NAMAKEC && $executor->NAMAKEL)
                                                                <option value="{{ $executor->VILLAGE_ID }}" selected>
                                                                    {{ $executor->NAMAPROPINSI }} →
                                                                    {{ $executor->NAMAKAB }} →
                                                                    {{ $executor->NAMAKEC }} →
                                                                    {{ $executor->NAMAKEL }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Kode Pos
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{ old('postal_code', $executor->KODEPOS) }}" placeholder="contoh: 60119" maxlength="5" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Alamat Lengkap
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap termasuk nama jalan, nomor, RT/RW" required>{{ old('address', $executor->ALAMAT) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Gedung / Building</label>
                                                        <input type="text" class="form-control" name="building_name" id="building_name" value="{{ old('building_name', $executor->NAMA_GEDUNG) }}" placeholder="Nama gedung kantor (jika ada)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Website Resmi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-globe"></i></span>
                                                            <input type="url" class="form-control" name="website" id="website" value="{{ old('website', $executor->WEBSITE) }}" placeholder="https://www.contohpenerbit.com">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-user-circle-gear me-1"></i>
                                                Kontak Person / Administrator
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Nama Admin Utama
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="contact1" id="contact1" value="{{ old('contact1', $executor->KONTAK1) }}" placeholder="Nama lengkap kontak person utama" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Admin Alternatif</label>
                                                        <input type="text" class="form-control" name="contact2" id="contact2" value="{{ old('contact2', $executor->KONTAK2) }}" placeholder="Nama kontak person cadangan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-phone me-1"></i>
                                                Informasi Kontak
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Email Utama
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-envelope"></i></span>
                                                            <input type="email" class="form-control" name="email1" id="email1" value="{{ old('email1', $executor->EMAIL1) }}" placeholder="email@contohpenerbit.com" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-envelope"></i></span>
                                                            <input type="email" class="form-control" name="email2" id="email2" value="{{ old('email2', $executor->EMAIL2) }}" placeholder="email.alternatif@contohpenerbit.com">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            No. Telepon Utama
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-phone"></i></span>
                                                            <input type="text" class="form-control" name="phone1" id="phone1" value="{{ old('phone1', $executor->TELP1) }}" placeholder="08xxxxxxxxxx" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">No. Telepon Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-phone"></i></span>
                                                            <input type="text" class="form-control" name="phone2" id="phone2" value="{{ old('phone2', $executor->TELP2) }}" placeholder="08xxxxxxxxxx atau 031-xxxxxxx">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Fax Utama</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-phone-disconnect"></i></span>
                                                            <input type="text" class="form-control" name="fax1" id="fax1" value="{{ old('fax1', $executor->FAX1) }}" placeholder="031-xxxxxxx">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Fax Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ph-phone-disconnect"></i></span>
                                                            <input type="text" class="form-control" name="fax2" id="fax2" value="{{ old('fax2', $executor->FAX2) }}" placeholder="031-xxxxxxx">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-info me-1"></i>
                                                Informasi Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Rata-rata Terbitan per Tahun</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="avg_publication" id="avg_publication" value="{{ old('avg_publication', $executor->RATA_TERBITAN) }}" placeholder="0" min="0">
                                                            <span class="input-group-text">Koleksi</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="ph-files me-1"></i>
                                                Dokumen Pendukung
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info border-start border-info border-3 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="ph-file-text text-info fs-3 me-3"></i>
                                                    <div>
                                                        <strong>Format Dokumen :</strong>
                                                        <ul class="mb-0 mt-1">
                                                            <li>File dalam format PDF</li>
                                                            <li>Ukuran maksimal 5 MB per file</li>
                                                            <li>Dokumen harus jelas dan dapat dibaca</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Surat Pernyataan
                                                            <span class="badge bg-info ms-1">Wajib</span>
                                                        </label>
                                                        @if($executor->FILE_SP)
                                                            <div class="border rounded p-2 mb-3 bg-light">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="ph-file-pdf text-danger fs-1 me-1"></i>
                                                                        <div>
                                                                            <div class="fw-semibold">Surat_Pernyataan.pdf</div>
                                                                            <small class="text-muted">Dokumen tersimpan</small>
                                                                        </div>
                                                                    </div>
                                                                    <a href="{{ url('stream-file?filename=' . $executor->FILE_SP . '&type=penerbit_surat_pernyataan&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                        <i class="ph-eye me-1"></i> Lihat
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="border rounded p-2 mb-3 text-center text-muted bg-light">
                                                                <i class="ph-file-x"></i>
                                                                <div><small class="mb-0 mt-1">Dokumen belum tersedia</small></div>
                                                            </div>
                                                        @endif
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="ph-upload-simple"></i>
                                                            </span>
                                                            <input type="file" name="file_statement" id="file_statement" class="form-control" accept=".pdf">
                                                        </div>
                                                        <small class="form-text text-muted">
                                                            <i class="ph-info me-1"></i>
                                                            Upload ulang jika ingin mengganti dokumen
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Akta Notaris
                                                            <span class="badge bg-warning ms-1">Opsional</span>
                                                        </label>
                                                        @if($executor->FILE_AKTE_NOTARIS)
                                                            <div class="border rounded p-2 mb-3 bg-light">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="ph-file-pdf text-danger fs-1 me-1"></i>
                                                                        <div>
                                                                            <div class="fw-semibold">Akta_Notaris.pdf</div>
                                                                            <small class="text-muted">Dokumen tersimpan</small>
                                                                        </div>
                                                                    </div>
                                                                    <a href="{{ url('stream-file?filename=' . $executor->FILE_AKTE_NOTARIS . '&type=penerbit_akte_notaris&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                        <i class="ph-eye me-1"></i> Lihat
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="border rounded p-2 mb-3 text-center text-muted bg-light">
                                                                <i class="ph-file-x"></i>
                                                                <div><small class="mb-0 mt-1">Dokumen belum tersedia</small></div>
                                                            </div>
                                                        @endif
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="ph-upload-simple"></i>
                                                            </span>
                                                            <input type="file" name="file_deed" id="file_deed" class="form-control" accept=".pdf">
                                                        </div>
                                                        <small class="form-text text-muted">
                                                            <i class="ph-info me-1"></i>
                                                            Upload ulang jika ingin mengganti dokumen
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-muted">
                                                    <i class="ph-shield-check me-1"></i>
                                                    <small>
                                                        Dengan mengirimkan data ini, saya menyatakan bahwa semua informasi
                                                        yang diberikan adalah benar dan dapat dipertanggungjawabkan.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ph-paper-plane-right me-1"></i>
                                                    Simpan & Ajukan Ulang Verifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                            @if($isPending)
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="ph-eye me-1"></i>
                                            Data yang Sedang Diverifikasi
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-light border-start border-warning border-3">
                                            <i class="ph-lock me-1 text-warning"></i>
                                            <strong>Informasi :</strong> Data di bawah ini sedang dalam proses verifikasi.
                                            Anda tidak dapat melakukan perubahan hingga proses verifikasi selesai.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="text-muted small">Nama Pelaksana Serah</label>
                                                <div class="fw-semibold">{{ $executor->NAME }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="text-muted small">Alias</label>
                                                <div class="fw-semibold">{{ $executor->ALIAS ?? '-' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="text-muted small">Email</label>
                                                <div class="fw-semibold">{{ $executor->EMAIL1 }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="text-muted small">Telepon</label>
                                                <div class="fw-semibold">{{ $executor->TELP1 }}</div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="text-muted small">Alamat</label>
                                                <div class="fw-semibold">{{ $executor->ALAMAT }}</div>
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
            <span>
                &copy; {{ date('Y') }}
                <a href="https://edeposit.perpusnas.go.id" target="_blank" class="text-primary">
                    SAKEDAP | Sistem Akses Elektronik Deposit Aman dan Praktis
                </a>
            </span>
            <ul class="nav">
                <li class="nav-item">
                    <a href="https://perpusnas.go.id" target="_blank" class="navbar-nav-link navbar-nav-link-icon rounded">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-globe"></i>
                            <span class="d-none d-md-inline-block ms-1">Official Website</span>
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

                if (file) {
                    if (file.size > 5242880) {
                        alert('Ukuran file terlalu besar. Maksimal 5 MB.');

                        $(this).val('');

                        return false;
                    }

                    if (file.type !== 'application/pdf') {
                        alert('Format file harus PDF.');

                        $(this).val('');

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
