<div class="content">
    <div class="row">
        <div class="col-xl-10 offset-xl-1">
            <form method="POST" id="profileForm">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <span class="fw-semibold">Terjadi Kesalahan :</span>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <span class="fw-semibold">
                            <i class="ph-check-circle me-1"></i> Berhasil!
                        </span>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <span class="fw-semibold">
                            <i class="ph-x-circle me-1"></i> Gagal!
                        </span>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="ph-user-circle me-1"></i>
                            Informasi Umum
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $executor->NAME) }}" placeholder="Masukkan nama lengkap pelaksana serah" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Alias</label>
                                    <input type="text" class="form-control" name="alias" id="alias" value="{{ old('alias', $executor->ALIAS) }}" placeholder="Nama singkat atau alias">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" class="form-control" value="{{ $executor->NAME_PENERBIT_KATEGORI ?? '-' }}" disabled readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis</label>
                                    <input type="text" class="form-control" value="{{ $executor->NAME_PENERBIT_JENIS ?? '-' }}" disabled readonly>
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
                            Alamat & Lokasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Wilayah <span class="text-danger">*</span></label>
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
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{ old('postal_code', $executor->KODEPOS) }}" placeholder="contoh: 60119" maxlength="5" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('address', $executor->ALAMAT) }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Gedung</label>
                                    <input type="text" class="form-control" name="building_name" id="building_name" value="{{ old('building_name', $executor->NAMA_GEDUNG) }}" placeholder="Nama gedung kantor (jika ada)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Website</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-globe"></i></span>
                                        <input type="url" class="form-control" name="website" id="website" value="{{ old('website', $executor->WEBSITE) }}" placeholder="https://www.example.com">
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
                            Kontak Person
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Admin Utama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="contact1" id="contact1" value="{{ old('contact1', $executor->KONTAK1) }}" placeholder="Nama kontak person utama" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Admin Alternatif</label>
                                    <input type="text" class="form-control" name="contact2" id="contact2" value="{{ old('contact2', $executor->KONTAK2) }}" placeholder="Nama kontak person alternatif">
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
                                    <label class="form-label">Email Utama <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-envelope"></i></span>
                                        <input type="email" class="form-control" name="email1" id="email1" value="{{ old('email1', $executor->EMAIL1) }}" placeholder="email@example.com" required>
                                    </div>
                                    <div class="form-text text-warning">
                                        <i class="ph-warning me-1"></i>Perubahan email memerlukan verifikasi OTP
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Alternatif</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-envelope"></i></span>
                                        <input type="email" class="form-control" name="email2" id="email2" value="{{ old('email2', $executor->EMAIL2) }}" placeholder="email.alternatif@example.com">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon Utama <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-phone"></i></span>
                                        <input type="text" class="form-control" name="phone1" id="phone1" value="{{ old('phone1', $executor->TELP1) }}" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                    <div class="form-text text-warning">
                                        <i class="ph-warning me-1"></i>Perubahan telepon memerlukan verifikasi OTP
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon Alternatif</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-phone"></i></span>
                                        <input type="text" class="form-control" name="phone2" id="phone2" value="{{ old('phone2', $executor->TELP2) }}" placeholder="08xxxxxxxxxx">
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
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Surat Pernyataan</label>
                                    <div class="border rounded p-3">
                                        @if($executor->FILE_SP)
                                            <div class="d-flex align-items-center justify-content-between py-2">
                                                <div>
                                                    <i class="ph-file-pdf text-danger fs-1"></i>
                                                    <span class="ms-2">Surat_Pernyataan.pdf</span>
                                                </div>
                                                <a href="{{ url('stream-file?filename=' . $executor->FILE_SP . '&type=penerbit_surat_pernyataan&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="ph-eye me-1"></i> Lihat
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="ph-file-x fs-1"></i>
                                                <p class="mb-0 mt-1">Dokumen belum tersedia</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Akta Notaris</label>
                                    <div class="border rounded p-3">
                                        @if($executor->FILE_AKTE_NOTARIS)
                                            <div class="d-flex align-items-center justify-content-between py-2">
                                                <div>
                                                    <i class="ph-file-pdf text-danger fs-1"></i>
                                                    <span class="ms-2">Akta_Notaris.pdf</span>
                                                </div>
                                                <a href="{{ url('stream-file?filename=' . $executor->FILE_AKTE_NOTARIS . '&type=penerbit_akte_notaris&id=' . $executor->ID) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="ph-eye me-1"></i> Lihat
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="ph-file-x fs-1"></i>
                                                <p class="mb-0 mt-1">Dokumen belum tersedia</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <i class="ph-info me-1"></i>
                                <small>Pastikan semua data yang diisi sudah benar</small>
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ph-shield-check me-1"></i>
                    Verifikasi OTP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ph-envelope-open fs-1 text-primary"></i>
                    <p class="mt-3 mb-0">Kode OTP telah dikirim ke:</p>
                    <strong id="otpTarget"></strong>
                </div>
                <form id="otpForm">
                    <input type="hidden" id="otpType" name="type">
                    <input type="hidden" id="otpValue" name="value">

                    <div class="mb-3">
                        <label class="form-label">Masukkan Kode OTP</label>
                        <input type="text" class="form-control form-control-lg text-center" id="otpCode" name="otp_code" placeholder="000000" maxlength="6" required style="letter-spacing: 0.5em; font-size: 1.5rem;">
                        <div class="form-text text-center">Kode OTP berlaku selama 5 menit</div>
                    </div>
                    <div class="mb-3 text-center">
                        <span id="otpTimer" class="text-muted"></span>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btnVerifyOtp">
                            <i class="ph-check me-1"></i>
                            Verifikasi
                        </button>
                        <button type="button" class="btn btn-link" id="btnResendOtp">
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
                    $('#btnSubmit').prop('disabled', true).html('<i class="ph-spinner spinner me-1"></i>Mengirim OTP...');
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

            $('#modal_otp .text-center i').removeClass('ph-envelope-open ph-device-mobile').addClass(iconClass);

            const targetText = type === 'email' ? value : value.replace(/(\d{4})(\d+)(\d{4})/, '$1****$3');

            $('#otpTarget').text(targetText);

            const stepInfo = getVerificationStepInfo(type);

            $('#modal_otp .modal-title').html(`<i class="ph-shield-check me-1"></i> Verifikasi OTP ${stepInfo}`);

            if (!otpModal) {
                otpModal = new bootstrap.Modal(document.getElementById('modal_otp'), {
                    backdrop: 'static',
                    keyboard: false
                });
            }

            otpModal.show();
            startOTPTimer(60);
        }

        function getVerificationStepInfo(currentType) {
            const needsEmail = verificationStatus.email.required;
            const needsPhone = verificationStatus.phone.required;

            if (needsEmail && needsPhone) {
                if (currentType === 'email') {
                    return '<span class="badge bg-white text-primary ms-2">1/2</span>';
                } else {
                    return '<span class="badge bg-white text-primary ms-2">2/2</span>';
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

                $('#otpTimer').text(`Minta ulang dalam ${minutes}:${secs.toString().padStart(2, '0')}`);

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
                    $('#btnVerifyOtp').prop('disabled', true).html('<i class="ph-spinner spinner me-1"></i>Memverifikasi...');
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
                    $('#btnVerifyOtp').prop('disabled', false).html('<i class="ph-check me-1"></i>Verifikasi');
                }
            });
        });

        function submitFormDirectly() {
            const form = $('#profileForm')[0];

            $('#btnSubmit').prop('disabled', true).html('<i class="ph-spinner spinner me-1"></i>Menyimpan...');
            $('#profileForm').off('submit');

            form.submit();
        }

        function showNotification(type, message) {
            const icon = type === 'success' ? 'ph-check' : 'ph-x';

            new Noty({
                text: `<i class="${icon} me-1"></i>${message}`,
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

<style>
    .spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        margin-bottom: 1.5rem;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .05);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
    }
</style>
