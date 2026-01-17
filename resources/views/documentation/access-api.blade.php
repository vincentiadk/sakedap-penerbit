<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Dokumentasi API - <span class="fw-normal">Akses API</span>
            </h4>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Manajemen API Key</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <span class="fw-semibold">Tentang API Key!</span>
                <div>API Key adalah kunci autentikasi yang diperlukan untuk mengakses layanan API SAKEDAP. Pastikan untuk menjaga kerahasiaan API Key Anda.</div>
            </div>
            @php
                $apiStatus = session('api_status') ?? null;
                $apiKey = session('api_key') ?? null;
            @endphp
            @if($apiStatus == 'APPROVED')
                <div class="alert alert-success border-0">
                    <div class="d-flex align-items-center">
                        <i class="ph-check-circle fs-3 me-2"></i>
                        <div>
                            <strong>Akses API Anda Aktif</strong>
                            <p class="mb-0">API Key Anda telah disetujui dan dapat digunakan.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">API Key Anda:</label>
                            <div class="input-group">
                                <input type="text" id="api-key" class="form-control form-control-lg font-monospace" value="{{ $apiKey }}" readonly>
                                <button class="btn btn-light" type="button" onclick="copyAPIKey()">
                                    <i class="ph-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" onclick="generateAPIKey()">
                                <i class="ph-key me-2"></i>
                                Generate API Key Baru
                            </button>
                        </div>
                    </div>
                </div>
            @elseif($apiStatus == 'PENDING')
                <div class="alert alert-warning border-0">
                    <div class="d-flex align-items-center">
                        <i class="ph-clock fs-3 me-2"></i>
                        <div>
                            <strong>Permintaan Sedang Diproses</strong>
                            <p class="mb-0">Permintaan akses API Anda sedang ditinjau oleh admin. Mohon tunggu konfirmasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            @elseif($apiStatus == 'REJECTED')
                <div class="alert alert-danger border-0">
                    <div class="d-flex align-items-center">
                        <i class="ph-x-circle fs-3 me-2"></i>
                        <div>
                            <strong>Permintaan Ditolak</strong>
                            <p class="mb-0">Permintaan akses API Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="requestAPIAccess()">
                    <i class="ph-paper-plane-tilt me-2"></i>
                    Ajukan Ulang Permintaan Akses API
                </button>
            @elseif($apiStatus == 'REVOKED')
                <div class="alert alert-dark border-0">
                    <div class="d-flex align-items-center">
                        <i class="ph-prohibit fs-3 me-2"></i>
                        <div>
                            <strong>Akses API Dicabut</strong>
                            <p class="mb-0">Akses API Anda telah dicabut. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-light border">
                    <div class="d-flex align-items-center">
                        <i class="ph-info fs-3 me-2"></i>
                        <div>
                            <strong>Belum Memiliki Akses API</strong>
                            <p class="mb-0">Anda belum mengajukan permintaan akses API. Klik tombol di bawah untuk mengajukan permintaan.</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="requestAPIAccess()">
                    <i class="ph-paper-plane-tilt me-2"></i>
                    Ajukan Permintaan Akses API
                </button>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Dokumentasi API</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6 class="fw-semibold mb-3">Informasi Umum</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" width="200">Base URL</td>
                                <td><code>https://api-sakedap.pusnasdev.online/api</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Versi Dokumen</td>
                                <td>2.0</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Rilis</td>
                                <td>18 November 2025</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Format Response</td>
                                <td><span class="badge bg-primary">JSON</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="my-4">
            <div class="mb-3">
                <h6 class="fw-semibold mb-3">Unduh Dokumentasi Lengkap</h6>
                <p class="text-muted">Untuk informasi lebih detail dan contoh implementasi, silakan unduh dokumen PDF lengkap.</p>
                <a href="{{ url('download/from-public?path=assets/Dokumentasi API Pelaksana Serah Sakedap.pdf') }}" class="btn btn-teal" target="_blank">
                    <i class="ph-file-pdf me-2"></i>
                    Download Dokumentasi PDF
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-start border-primary border-width-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-fill">
                            <h6 class="mb-0">Response Format</h6>
                        </div>
                        <div class="ms-3">
                            <i class="ph-code fs-2 text-primary"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Semua response API menggunakan format JSON dengan struktur standar yang konsisten.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-start border-success border-width-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-fill">
                            <h6 class="mb-0">Status Code</h6>
                        </div>
                        <div class="ms-3">
                            <i class="ph-check-circle fs-2 text-success"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        <span class="badge bg-success me-1">200</span> Success<br>
                        <span class="badge bg-danger me-1">500</span> Failed/Error
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-start border-info border-width-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-fill">
                            <h6 class="mb-0">Keamanan</h6>
                        </div>
                        <div class="ms-3">
                            <i class="ph-shield-check fs-2 text-info"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Pastikan API Key Anda tetap rahasia dan gunakan HTTPS untuk semua request.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function requestAPIAccess() {
        swalInit.fire({
            title: 'Ajukan Permintaan Akses API?',
            text: 'Permintaan Anda akan ditinjau oleh admin.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("documentation/access-api/request-access") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        onLoading('show', 'body');
                    },
                    success: function(response) {
                        onLoading('close', 'body');

                        if(response.code == 200) {
                            swalInit.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            swalInit.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(response) {
                        onLoading('close', 'body');
                        responseError(response);
                    }
                });
            }
        });
    }

    function generateAPIKey() {
        swalInit.fire({
            title: 'Generate API Key Baru?',
            text: 'API Key lama akan digantikan dengan yang baru.',
            icon: 'warning',
            buttons: {
                cancel: {
                    text: 'Batal',
                    visible: true,
                    closeModal: true,
                },
                confirm: {
                    text: 'Ya, Generate!',
                    value: true,
                    visible: true,
                }
            },
            dangerMode: false,
        }).then((willGenerate) => {
            if (willGenerate) {
                $.ajax({
                    url: '{{ url("documentation/access-api/generate-new-token") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        onLoading('show', 'body');
                    },
                    success: function(response) {
                        onLoading('close', 'body');

                        if(response.code == 200) {
                            $('#api-key').val(response.data.token);

                            notification('success', response.message);
                        } else {
                            swalInit.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                showCloseButton: false
                            });
                        }
                    },
                    error: function(response) {
                        onLoading('close', 'body');
                        responseError(response);
                    }
                });
            }
        });
    }

    function copyAPIKey() {
        const apiKeyValue = $('#api-key').val();

        if(apiKeyValue) {
            const apiKeyInput = $('#api-key')[0];

            apiKeyInput.select();
            apiKeyInput.setSelectionRange(0, 99999);
            document.execCommand('copy');

            swalInit.fire('Tersalin!', 'API Key berhasil disalin ke clipboard.', 'success');
        }
    }
</script>
