<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Profil Pelaksana Serah</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary p-2 bg-opacity-10 text-primary">
                    Kelola informasi profil
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="row">
        <div class="col-xl-10 offset-xl-1">
            <form method="POST" id="profileForm">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <div class="d-flex align-items-start">
                            <i class="ph-warning-circle me-1 mt-1"></i>
                            <div class="flex-fill">
                                <h6 class="alert-heading fw-semibold mb-2">Terjadi Kesalahan</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="ph-check-circle me-1"></i>
                            <div class="flex-fill">
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="ph-x-circle me-1"></i>
                            <div class="flex-fill">
                                <strong>Gagal!</strong> {{ session('error') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
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
                                <input type="text" class="form-control bg-light" value="{{ $executor->NAME_PENERBIT_KATEGORI ?? '-' }}" disabled readonly>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ph-list-bullets me-1"></i>
                                    Jenis
                                </label>
                                <input type="text" class="form-control bg-light" value="{{ $executor->NAME_PENERBIT_JENIS ?? '-' }}" disabled readonly>
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
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ph-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" name="email1" id="email1" value="{{ old('email1', $executor->EMAIL1) }}" placeholder="email@example.com" required>
                                </div>
                                <div class="form-text">
                                    <i class="ph-warning me-1 text-warning"></i>
                                    <span class="text-warning">Perubahan email memerlukan verifikasi OTP</span>
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
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ph-phone"></i>
                                    </span>
                                    <input type="text" class="form-control" name="phone1" id="phone1" value="{{ old('phone1', $executor->TELP1) }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="form-text">
                                    <i class="ph-warning me-1 text-warning"></i>
                                    <span class="text-warning">Perubahan telepon memerlukan verifikasi OTP</span>
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
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ph-files me-1 text-primary"></i>
                            <h6 class="mb-0 fw-semibold">Dokumen Pendukung</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ph-file-text me-1"></i>
                                    Surat Pernyataan
                                </label>
                                <div class="border rounded p-3 bg-light">
                                    @if($executor->FILE_SP)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-file-pdf text-danger fs-2 me-1"></i>
                                                <div>
                                                    <div class="fw-semibold">Surat_Pernyataan.pdf</div>
                                                    <small class="text-muted">Dokumen tersedia</small>
                                                </div>
                                            </div>
                                            <a href="{{ url('stream-file?filename=' . $executor->FILE_SP . '&type=penerbit_surat_pernyataan&id=' . $executor->ID) }}" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="ph-eye me-1"></i>
                                                Lihat
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="ph-file-x fs-1 mb-2 d-block"></i>
                                            <small>Dokumen belum tersedia</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">
                                    <i class="ph-file-text me-1"></i>
                                    Akta Notaris
                                </label>
                                <div class="border rounded p-3 bg-light">
                                    @if($executor->FILE_AKTE_NOTARIS)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-file-pdf text-danger fs-2 me-1"></i>
                                                <div>
                                                    <div class="fw-semibold">Akta_Notaris.pdf</div>
                                                    <small class="text-muted">Dokumen tersedia</small>
                                                </div>
                                            </div>
                                            <a href="{{ url('stream-file?filename=' . $executor->FILE_AKTE_NOTARIS . '&type=penerbit_akte_notaris&id=' . $executor->ID) }}" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="ph-eye me-1"></i>
                                                Lihat
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="ph-file-x fs-1 mb-2 d-block"></i>
                                            <small>Dokumen belum tersedia</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                            <div class="text-muted">
                                <i class="ph-info me-1"></i>
                                <small>Pastikan semua data yang diisi sudah benar sebelum menyimpan</small>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                <i class="ph-floppy-disk me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_otp" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title">
                    <i class="ph-shield-check me-1 text-primary"></i>
                    Verifikasi OTP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="ph-envelope-open fs-1 text-primary"></i>
                    </div>
                    <p class="mb-1">Kode OTP telah dikirim ke:</p>
                    <strong class="text-primary" id="otpTarget"></strong>
                </div>
                <form id="otpForm">
                    <input type="hidden" id="otpType" name="type">
                    <input type="hidden" id="otpValue" name="value">
                    <div class="form-group">
                        <label class="form-label fw-semibold">Masukkan Kode OTP</label>
                        <input type="text" class="form-control form-control-lg text-center fw-bold" id="otpCode" name="otp_code" placeholder="000000" maxlength="6" required style="letter-spacing: 0.5em; font-size: 1.5rem;">
                        <div class="form-text text-center">
                            <i class="ph-clock me-1"></i>
                            Kode OTP berlaku selama 5 menit
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <span id="otpTimer" class="badge bg-warning bg-opacity-10 text-warning"></span>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btnVerifyOtp">
                            <i class="ph-check-circle me-1"></i>
                            Verifikasi Kode
                        </button>
                        <button type="button" class="btn btn-light" id="btnResendOtp">
                            <i class="ph-arrow-clockwise me-1"></i>
                            Kirim Ulang Kode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        select2Serverside('#location_id', 'location');

        const originalEmail = $('#email1').val();
        const originalPhone = $('#phone1').val();

        let otpTimer = null;
        let otpModal = null;
        let otpCountdown = 0;

        let verificationStatus = {
            email: { required: false, verified: false },
            phone: { required: false, verified: false }
        };

        $('#profileForm').on('submit', function(e) {
            e.preventDefault();

            const currentEmail = $('#email1').val();
            const currentPhone = $('#phone1').val();

            verificationStatus.email.required = currentEmail !== originalEmail;
            verificationStatus.phone.required = currentPhone !== originalPhone;

            const needsVerification = verificationStatus.email.required || verificationStatus.phone.required;

            if (!needsVerification) {
                submitFormDirectly();
                return;
            }

            const allVerified = (!verificationStatus.email.required || verificationStatus.email.verified) && (!verificationStatus.phone.required || verificationStatus.phone.verified);

            if (allVerified) {
                submitFormDirectly();
                return;
            }

            startVerificationProcess(currentEmail, currentPhone);
        });

        function startVerificationProcess(email, phone) {
            if (verificationStatus.email.required && !verificationStatus.email.verified) {
                requestOTP('email', email);
            } else if (verificationStatus.phone.required && !verificationStatus.phone.verified) {
                requestOTP('phone', phone);
            }
        }

        function requestOTP(type, value) {
            $.ajax({
                url: '{{ url("auth/send-otp") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    value: value
                },
                beforeSend: function() {
                    $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Mengirim OTP...');
                },
                success: function(response) {
                    if (response.code == 200 || response.code == 201) {
                        showOTPModal(type, value);
                        showNotification('success', response.message);
                    } else {
                        showNotification('error', response.message);
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Gagal mengirim OTP';
                    showNotification('error', message);
                },
                complete: function() {
                    $('#btnSubmit').prop('disabled', false).html('<i class="ph-floppy-disk me-1"></i>Simpan Perubahan');
                }
            });
        }

        function showOTPModal(type, value) {
            $('#otpType').val(type);
            $('#otpValue').val(value);
            $('#otpCode').val('');

            const iconClass = type === 'email' ? 'ph-envelope-open' : 'ph-device-mobile';

            $('#modal_otp .bg-primary i').removeClass('ph-envelope-open ph-device-mobile').addClass(iconClass);

            const targetText = type === 'email' ? value : value.replace(/(\d{4})(\d+)(\d{4})/, '$1****$3');

            $('#otpTarget').text(targetText);

            const stepInfo = getVerificationStepInfo(type);

            $('#modal_otp .modal-title').html(`<i class="ph-shield-check me-1 text-primary"></i> Verifikasi OTP ${stepInfo}`);

            if (!otpModal) {
                otpModal = new bootstrap.Modal(document.getElementById('modal_otp'), {
                    backdrop: 'static',
                    keyboard: false
                });
            }

            otpModal.show();
            startOTPTimer(300);
        }

        function getVerificationStepInfo(currentType) {
            const needsEmail = verificationStatus.email.required;
            const needsPhone = verificationStatus.phone.required;

            if (needsEmail && needsPhone) {
                if (currentType === 'email') {
                    return '<span class="badge bg-primary ms-2">1/2</span>';
                } else {
                    return '<span class="badge bg-primary ms-2">2/2</span>';
                }
            }
            return '';
        }

        function startOTPTimer(seconds) {
            if (otpTimer) {
                clearInterval(otpTimer);
            }

            otpCountdown = seconds;

            $('#btnResendOtp').prop('disabled', true);

            otpTimer = setInterval(function() {
                if (otpCountdown <= 0) {
                    clearInterval(otpTimer);

                    otpTimer = null;

                    $('#otpTimer').html('');
                    $('#btnResendOtp').prop('disabled', false);

                    return;
                }

                const minutes = Math.floor(otpCountdown / 60);
                const secs = otpCountdown % 60;

                $('#otpTimer').html(`<i class="ph-clock me-1"></i> Minta ulang dalam ${minutes}:${secs.toString().padStart(2, '0')}`);

                otpCountdown--;
            }, 1000);
        }

        $('#btnResendOtp').on('click', function() {
            const type = $('#otpType').val();
            const value = $('#otpValue').val();

            if (otpModal) {
                otpModal.hide();
            }

            requestOTP(type, value);
        });

        $('#otpForm').on('submit', function(e) {
            e.preventDefault();

            const otpCode = $('#otpCode').val();
            const type = $('#otpType').val();
            const value = $('#otpValue').val();

            if (!otpCode || otpCode.length < 4) {
                showNotification('error', 'Kode OTP tidak valid');

                return;
            }

            $.ajax({
                url: '{{ url("auth/verify-otp") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp_code: otpCode,
                    type: type,
                    value: value
                },
                beforeSend: function() {
                    $('#btnVerifyOtp').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Memverifikasi...');
                },
                success: function(response) {
                    if (response.code == 200 || response.code == 201) {
                        if (otpTimer) {
                            clearInterval(otpTimer);

                            otpTimer = null;
                        }

                        verificationStatus[type].verified = true;

                        const verifiedLabel = type === 'email' ? 'email' : 'nomor telepon';

                        showNotification('success', `Verifikasi ${verifiedLabel} berhasil!`);

                        if (otpModal) {
                            otpModal.hide();
                        }

                        setTimeout(function() {
                            const currentEmail = $('#email1').val();
                            const currentPhone = $('#phone1').val();

                            if (verificationStatus.phone.required && !verificationStatus.phone.verified) {
                                requestOTP('phone', currentPhone);
                            } else if (verificationStatus.email.required && !verificationStatus.email.verified) {
                                requestOTP('email', currentEmail);
                            } else {
                                submitFormDirectly();
                            }
                        }, 500);
                    } else {
                        showNotification('error', response.message);

                        $('#otpCode').val('').focus();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Verifikasi gagal';

                    showNotification('error', message);

                    $('#otpCode').val('').focus();
                },
                complete: function() {
                    $('#btnVerifyOtp').prop('disabled', false).html('<i class="ph-check-circle me-1"></i>Verifikasi Kode');
                }
            });
        });

        function submitFormDirectly() {
            const form = $('#profileForm')[0];

            $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
            $('#profileForm').off('submit');

            form.submit();
        }

        function showNotification(type, message) {
            const icon = type === 'success' ? 'ph-check-circle' : 'ph-warning-circle';
            const iconColor = type === 'success' ? 'text-success' : 'text-danger';

            new Noty({
                text: `<i class="${icon} ${iconColor} me-1"></i>${message}`,
                type: type,
                timeout: 3000,
                theme: 'limitless',
                layout: 'topRight'
            }).show();
        }

        $('#phone1, #phone2, #postal_code, #otpCode').on('input', function() {
            const value = $(this).val().replace(/\D/g, '');

            $(this).val(value);
        });

        $(window).on('beforeunload', function() {
            if (otpTimer) {
                clearInterval(otpTimer);
            }
        });

        $('#email1').on('change', function() {
            const currentEmail = $(this).val();

            if (currentEmail !== originalEmail) {
                verificationStatus.email.verified = false;
            }
        });

        $('#phone1').on('change', function() {
            const currentPhone = $(this).val();

            if (currentPhone !== originalPhone) {
                verificationStatus.phone.verified = false;
            }
        });
    });
</script>
