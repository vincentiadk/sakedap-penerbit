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
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">API Key Anda:</label>
                        <div class="input-group">
                            <input type="text" id="api-key" class="form-control form-control-lg font-monospace" value="{{ session('api_key') }}" readonly>
                            <button class="btn btn-light" type="button" onclick="copyAPIKey()" id="copy-btn">
                                <i class="ph-copy"></i>
                            </button>
                        </div>
                        <div class="mt-1">
                            @if(session('api_status') == 1)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                    @if(session('api_status') == 1)
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" onclick="generateAPIKey()">
                                <i class="ph-key me-2"></i>
                                Generate API Key Baru
                            </button>
                        </div>
                    @endif
                </div>
            </div>
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
                <h6 class="fw-semibold mb-3">Endpoint yang Tersedia</h6>
                <div class="accordion" id="apiEndpoints">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint1">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>1. Login Pelaksana Serah</strong>
                            </button>
                        </h2>
                        <div id="endpoint1" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/sso/login</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Melakukan autentikasi untuk mendapatkan akses ke sistem SAKEDAP.</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>username</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Username penerbit</td>
                                            </tr>
                                            <tr>
                                                <td><code>password</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Password akun penerbit</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint2">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>2. Pencarian Data Tagihan ISBN</strong>
                            </button>
                        </h2>
                        <div id="endpoint2" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/isbn/tagihan</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Melakukan pencarian data tagihan ISBN dengan berbagai filter yang tersedia.</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>title</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Judul koleksi yang dicari</td>
                                            </tr>
                                            <tr>
                                                <td><code>kepeng</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Nama kepengarangan/penulis</td>
                                            </tr>
                                            <tr>
                                                <td><code>tahun_terbit</code></td>
                                                <td>number(4)</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Tahun terbit (4 digit)</td>
                                            </tr>
                                            <tr>
                                                <td><code>bulan_terbit</code></td>
                                                <td>number(2)</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Bulan terbit (01-12)</td>
                                            </tr>
                                            <tr>
                                                <td><code>isbn</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Nomor ISBN tanpa spasi</td>
                                            </tr>
                                            <tr>
                                                <td><code>jenis_media</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>1=cetak, 2=pdf, 3=epub, 4=audio book, 5=audio visual</td>
                                            </tr>
                                            <tr>
                                                <td><code>length</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Jumlah items (default: 10)</td>
                                            </tr>
                                            <tr>
                                                <td><code>page</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Halaman (default: 1)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint3">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>3. Detail ISBN</strong>
                            </button>
                        </h2>
                        <div id="endpoint3" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/isbn/detail</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Mendapatkan informasi detail dari nomor ISBN tertentu.</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>isbn</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Nomor ISBN (dengan/tanpa tanda -)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint4">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>4. Data Penerbit</strong>
                            </button>
                        </h2>
                        <div id="endpoint4" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/penerbit/data</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Mendapatkan data informasi penerbit dengan berbagai filter.</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>name</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Nama penerbit</td>
                                            </tr>
                                            <tr>
                                                <td><code>kabkot</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Kabupaten/Kota (contoh: "kota bogor")</td>
                                            </tr>
                                            <tr>
                                                <td><code>provinsi</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-secondary">No</span></td>
                                                <td>Provinsi (contoh: "dki jakarta")</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint5">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>5. Unggah Buku ISBN</strong>
                            </button>
                        </h2>
                        <div id="endpoint5" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/isbn/upload</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Mengunggah file koleksi yang telah memiliki ISBN beserta cover-nya.</p>
                                </div>
                                <div class="alert alert-warning mb-3">
                                    <i class="ph-warning-circle me-2"></i>
                                    <strong>Penting:</strong> File harus diunggah dalam bentuk multipart/form-data
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>content</code></td>
                                                <td>file</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>File koleksi (posting file)</td>
                                            </tr>
                                            <tr>
                                                <td><code>mime_content</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>MIME type file content</td>
                                            </tr>
                                            <tr>
                                                <td><code>hash_content</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Hash file untuk validasi</td>
                                            </tr>
                                            <tr>
                                                <td><code>tanggal_terbit</code></td>
                                                <td>dd-mm-yyyy</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Tanggal publikasi</td>
                                            </tr>
                                            <tr>
                                                <td><code>access</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Hak akses (1-4)<br>
                                                    <small class="text-muted">
                                                        1: Akses penuh internet<br>
                                                        2: Preview internet, full LAN<br>
                                                        3: Preview internet, full LAN setelah 5 tahun<br>
                                                        4: Hanya preview, tidak didayagunakan
                                                    </small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#endpoint6">
                                <span class="badge bg-success me-2">POST</span>
                                <strong>6. Unggah Karya Digital Lainnya (Non-ISBN)</strong>
                            </button>
                        </h2>
                        <div id="endpoint6" class="accordion-collapse collapse" data-bs-parent="#apiEndpoints">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong>Endpoint:</strong>
                                    <code class="d-block bg-light p-2 rounded mt-1">/upload</code>
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong>
                                    <p>Mengunggah karya digital selain koleksi ber-ISBN seperti musik, film, majalah elektronik, dll.</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Jenis Media yang Didukung:</strong>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <span class="badge bg-info">1 - Buku Elektronik</span>
                                        <span class="badge bg-info">2 - Musik Digital</span>
                                        <span class="badge bg-info">3 - Film</span>
                                        <span class="badge bg-info">4 - Majalah/Buletin/Jurnal/Surat Kabar Elektronik</span>
                                        <span class="badge bg-info">5 - Peta Elektronik</span>
                                        <span class="badge bg-info">6 - Partitur Musik/Sheet Music</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Mandatory</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>token</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Token API yang diberikan</td>
                                            </tr>
                                            <tr>
                                                <td><code>title</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Judul karya</td>
                                            </tr>
                                            <tr>
                                                <td><code>authors</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Nama penulis/creator</td>
                                            </tr>
                                            <tr>
                                                <td><code>sinopsis</code></td>
                                                <td>text</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Sinopsis (minimal 200 karakter)</td>
                                            </tr>
                                            <tr>
                                                <td><code>jenis_media</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Tipe media (1-6)</td>
                                            </tr>
                                            <tr>
                                                <td><code>tanggal_terbit</code></td>
                                                <td>dd-mm-yyyy</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Tanggal publikasi</td>
                                            </tr>
                                            <tr>
                                                <td><code>preview</code></td>
                                                <td>text</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Halaman preview (PDF: "1-10,67") atau waktu (Video: "00:01:40-00:02:30")</td>
                                            </tr>
                                            <tr>
                                                <td><code>mata_uang</code></td>
                                                <td>string</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Kode mata uang 3 digit (IDR, USD)</td>
                                            </tr>
                                            <tr>
                                                <td><code>harga</code></td>
                                                <td>integer</td>
                                                <td><span class="badge bg-danger">Yes</span></td>
                                                <td>Harga tanpa koma/titik</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
