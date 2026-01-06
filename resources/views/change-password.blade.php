<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <i class="ph-lock-key me-2"></i>
                <span class="fw-normal">Ganti Password</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-warning p-2 bg-opacity-10 text-warning">
                    Keamanan Akun
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
            <form method="POST" id="form-password" autocomplete="off">
                @csrf
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ph-key me-2 text-primary"></i>
                            <h6 class="mb-0 fw-semibold">Ubah Password Anda</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center" role="alert">
                                <i class="ph-check-circle ph-2x me-3"></i>
                                <div class="flex-fill">
                                    {{ session('success') }}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center" role="alert">
                                <i class="ph-warning-circle ph-2x me-3"></i>
                                <div class="flex-fill">
                                    {{ session('error') }}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0" role="alert">
                                <div class="d-flex align-items-start">
                                    <i class="ph-warning-circle ph-2x me-3"></i>
                                    <div class="flex-fill">
                                        <h6 class="alert-heading fw-semibold mb-2">Terdapat kesalahan:</h6>
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
                        <div class="alert alert-info border-0 d-flex align-items-start mb-4">
                            <i class="ph-info ph-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading fw-semibold mb-1">Tips Keamanan</h6>
                                <p class="mb-0 fs-sm">Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk membuat password yang kuat dan aman.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label fw-semibold" for="new_password">
                                <i class="ph-lock me-1"></i>
                                Password Baru
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-key"></i>
                                </span>
                                <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Masukkan password baru" autocomplete="new-password">
                                <button type="button" class="btn btn-light" id="toggle-new-password">
                                    <i class="ph-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="password-rule mt-3 p-3 bg-light rounded">
                            <h6 class="fs-sm fw-semibold mb-3">
                                <i class="ph-check-square me-1"></i>
                                Password harus memenuhi:
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-text text-danger d-flex align-items-center">
                                        <span class="icon-char">
                                            <i class="ph-x text-danger me-2"></i>
                                        </span>
                                        <span>Minimal 8 karakter</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-text text-danger d-flex align-items-center">
                                        <span class="icon-case">
                                            <i class="ph-x text-danger me-2"></i>
                                        </span>
                                        <span>1 huruf besar dan 1 huruf kecil</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-text text-danger d-flex align-items-center">
                                        <span class="icon-number">
                                            <i class="ph-x text-danger me-2"></i>
                                        </span>
                                        <span>1 angka</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-text text-danger d-flex align-items-center">
                                        <span class="icon-symbol">
                                            <i class="ph-x text-danger me-2"></i>
                                        </span>
                                        <span>1 simbol (Misalnya: !@#$%^&*)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="form-group">
                            <label class="form-label fw-semibold" for="confirm_password">
                                <i class="ph-lock-key me-1"></i>
                                Konfirmasi Password
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-key"></i>
                                </span>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Ketik ulang password baru" autocomplete="new-password">
                                <button type="button" class="btn btn-light" id="toggle-confirm-password">
                                    <i class="ph-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="confirm-password-rule mt-3 p-3 bg-light rounded">
                            <div class="form-text text-danger d-flex align-items-center">
                                <span class="icon-match">
                                    <i class="ph-x text-danger me-2"></i>
                                </span>
                                <span>Password harus cocok</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light">
                                <i class="ph-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btn-submit" disabled>
                                <i class="ph-floppy-disk me-1"></i>
                                Simpan Password Baru
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <i class="ph-question text-primary ph-2x me-3"></i>
                            <div>
                                <h6 class="fw-semibold mb-2">Butuh Bantuan?</h6>
                                <p class="text-muted mb-0 fs-sm">Jika Anda mengalami kesulitan dalam mengganti password, silakan hubungi administrator sistem untuk bantuan lebih lanjut.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        const requirements = {
            length: { regex: /^.{8,}$/, selector: '.icon-char' },
            case: { regex: /(?=.*[a-z])(?=.*[A-Z])/, selector: '.icon-case' },
            digit: { regex: /\d/, selector: '.icon-number' },
            special: { regex: /[!@#$%^&*()_+={}[\]:;'"<,>.?/\\|~`]/, selector: '.icon-symbol' }
        };

        const $submitButton = $('#btn-submit');
        const $newPasswordInput = $("#new_password");
        const $confirmPasswordInput = $("#confirm_password");

        $('#toggle-new-password').on('click', function() {
            const type = $newPasswordInput.attr('type') === 'password' ? 'text' : 'password';

            $newPasswordInput.attr('type', type);

            $(this).find('i').toggleClass('ph-eye ph-eye-slash');
        });

        $('#toggle-confirm-password').on('click', function() {
            const type = $confirmPasswordInput.attr('type') === 'password' ? 'text' : 'password';

            $confirmPasswordInput.attr('type', type);

            $(this).find('i').toggleClass('ph-eye ph-eye-slash');
        });

        function updateNewPasswordRules(passwordValue) {
            let allValid = true;

            $.each(requirements, function(key, req) {
                const $iconSpan = $('.password-rule ' + req.selector);
                const $iconElement = $iconSpan.find('i');
                const $ruleText = $iconSpan.closest('.form-text');

                if (req.regex.test(passwordValue)) {
                    $iconElement.removeClass('ph-x text-danger').addClass('ph-check-circle text-success');
                    $ruleText.addClass('text-success').removeClass('text-danger');
                } else {
                    $iconElement.removeClass('ph-check-circle text-success').addClass('ph-x text-danger');
                    $ruleText.addClass('text-danger').removeClass('text-success');

                    allValid = false;
                }
            });

            return allValid;
        }

        function checkConfirmPassword(newPass, confirmPass) {
            const $matchIconSpan = $('.confirm-password-rule .icon-match');
            const $matchIconElement = $matchIconSpan.find('i');
            const $matchRuleText = $matchIconSpan.closest('.form-text');
            const isMatch = (newPass.length > 0 && newPass === confirmPass);

            if (isMatch) {
                $matchIconElement.removeClass('ph-x text-danger').addClass('ph-check-circle text-success');
                $matchRuleText.addClass('text-success').removeClass('text-danger');
            } else {
                $matchIconElement.removeClass('ph-check-circle text-success').addClass('ph-x text-danger');
                $matchRuleText.addClass('text-danger').removeClass('text-success');
            }

            return isMatch;
        }

        function checkAllValidation() {
            const newPassValue = $newPasswordInput.val();
            const confirmPassValue = $confirmPasswordInput.val();
            const isNewPassValid = updateNewPasswordRules(newPassValue);
            const isConfirmValid = checkConfirmPassword(newPassValue, confirmPassValue);
            const finalValidation = isNewPassValid && isConfirmValid;

            $submitButton.prop('disabled', !finalValidation);

            return finalValidation;
        }

        $newPasswordInput.on("keyup blur", function() {
            checkAllValidation();
        });

        $confirmPasswordInput.on("keyup blur", function() {
            checkAllValidation();
        });

        $('#form-password').on('submit', function(e) {
            const isValid = checkAllValidation();

            if (typeof onLoading === 'function') {
                onLoading('show', 'body');
            }

            if (!isValid) {
                e.preventDefault();

                if (typeof onLoading === 'function') {
                    onLoading('close', 'body');
                }

                if (typeof swalInit !== 'undefined') {
                    swalInit.fire('Oops ...', 'Harap perbaiki semua aturan password yang belum terpenuhi.', 'warning');
                } else {
                    alert('Harap perbaiki semua aturan password yang belum terpenuhi.');
                }
            }
        });

        checkAllValidation();
    });
</script>
