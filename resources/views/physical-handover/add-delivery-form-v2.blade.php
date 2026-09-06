@php
    use App\Helpers\Main;
    use App\Http\Controllers\PhysicalHandover\AddDeliveryFormController;
@endphp
<style>
.letter-preview-paper {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #d9d9d9;
    padding: 35px 45px;
    min-height: 700px;
    font-family: "Times New Roman", Times, serif;
    color: #000;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}

.letter-preview-title {
    text-align: center;
    margin-bottom: 25px;
}

.letter-preview-title h4 {
    margin: 0;
    font-weight: bold;
    text-decoration: underline;
    font-size: 18px;
}

.letter-preview-number {
    margin-top: 4px;
    font-size: 14px;
}

.letter-preview-meta {
    width: 100%;
    margin-bottom: 20px;
    font-size: 14px;
}

.letter-preview-meta td {
    padding: 2px 0;
    vertical-align: top;
}

.letter-preview-text {
    font-size: 14px;
    line-height: 1.6;
    text-align: justify;
    margin-bottom: 18px;
}

.letter-preview-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    font-size: 12px;
}

.letter-preview-table th,
.letter-preview-table td {
    border: 1px solid #000;
    padding: 6px;
}

.letter-preview-table th {
    text-align: center;
}

.letter-preview-signature {
    width: 42%;
    margin-left: auto;
    margin-top: 35px;
    text-align: center;
    font-size: 14px;
}

.letter-preview-signature-space {
    height: 70px;
}
</style>
<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex border-top">
        <div class="d-flex">
            <div class="breadcrumb py-2">
                <a href="{{ url('physical-handover/delivery-monitoring') }}" class="breadcrumb-item">Serah Simpan Fisik</a>
                <span class="breadcrumb-item active">Form Pengiriman</span>
            </div>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
            <div class="d-flex align-items-center gap-2 mb-2 mb-lg-0">
                <span class="text-muted small" id="autosave-indicator"></span>
                <button type="button" class="btn btn-light btn-sm text-danger d-none" id="btn-clear-autosave" onclick="confirmClearAutoSave()">
                    <i class="ph-trash me-1"></i>
                    Hapus Data Tersimpan
                </button>
                <a href="{{ url('physical-handover/add-delivery-form') }}" class="btn btn-light btn-sm">
                    <i class="ph-arrow-u-up-left me-1"></i>
                    Versi Lama
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    {{-- Langkah pengisian --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap" id="stepper">
                <div class="step-item flex-fill" data-step="1" onclick="goToStep(1)">
                    <div class="d-flex align-items-center gap-2">
                        <span class="step-badge">1</span>
                        <div>
                            <div class="fw-semibold step-title">Informasi Pengiriman</div>
                            <div class="text-muted small">Pengirim dan tujuan</div>
                        </div>
                    </div>
                </div>
                <i class="ph-caret-right text-muted d-none d-md-block"></i>
                <div class="step-item flex-fill" data-step="2" onclick="goToStep(2)">
                    <div class="d-flex align-items-center gap-2">
                        <span class="step-badge">2</span>
                        <div>
                            <div class="fw-semibold step-title">Koleksi</div>
                            <div class="text-muted small">Daftar buku yang dikirim</div>
                        </div>
                    </div>
                </div>
                <i class="ph-caret-right text-muted d-none d-md-block"></i>
                <div class="step-item flex-fill" data-step="3" onclick="goToStep(3)">
                    <div class="d-flex align-items-center gap-2">
                        <span class="step-badge">3</span>
                        <div>
                            <div class="fw-semibold step-title">Metode &amp; Kirim</div>
                            <div class="text-muted small">Cara pengiriman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger alert-dismissible fade d-none" id="validation-element">
        <button type="button" class="btn-close" onclick="clearValidation()"></button>
        <h6 class="alert-heading mb-2">
            <i class="ph-warning-circle me-1"></i>
            Ada isian yang perlu diperbaiki
        </h6>
        <ul class="mb-0 ps-3" id="validation-data"></ul>
    </div>

    <form id="form-data">
        {{-- ============ LANGKAH 1 ============ --}}
        <div class="step-pane" data-pane="1">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ph-address-book me-1"></i> Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pengirim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sender_name" id="sender_name" value="{{ session('name') }}" placeholder="Masukkan nama pengirim">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{ Main::phoneFormat(session('phone')) }}" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pelaksana Serah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ session('name') }}" readonly>
                            <input type="hidden" name="executor_id" id="executor_id" value="{{ session('id') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Surat Pengantar</label>
                            <input type="text" class="form-control" name="cover_letter_number" id="cover_letter_number" placeholder="Contoh: 001/SP/2025">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tujuan Penyerahan <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="card border h-100 mb-0 choice-card" data-destination="3" onclick="selectDestination(3)">
                                        <div class="card-body text-center py-3">
                                            <i class="ph-buildings ph-2x d-block mb-2 text-primary"></i>
                                            <strong>Perpusnas &amp; Provinsi</strong>
                                            <div class="text-muted small mt-1">2 eks + 1 eks</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border h-100 mb-0 choice-card" data-destination="1" onclick="selectDestination(1)">
                                        <div class="card-body text-center py-3">
                                            <i class="ph-bank ph-2x d-block mb-2 text-primary"></i>
                                            <strong>Perpusnas Saja</strong>
                                            <div class="text-muted small mt-1">2 eks</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border h-100 mb-0 choice-card" data-destination="2" onclick="selectDestination(2)">
                                        <div class="card-body text-center py-3">
                                            <i class="ph-map-pin ph-2x d-block mb-2 text-primary"></i>
                                            <strong>Provinsi Saja</strong>
                                            <div class="text-muted small mt-1">1 eks</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="destination" id="destination" value="3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Berat Paket</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ph-package"></i></span>
                                <input type="number" class="form-control" name="weight" id="weight" placeholder="Minimal 1" min="1" step="0.1">
                                <span class="input-group-text">Kg</span>
                            </div>
                            <small class="text-muted">Wajib diisi bila memakai ekspedisi.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ LANGKAH 2 ============ --}}
        <div class="step-pane d-none" data-pane="2">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ph-barcode me-1"></i> Koleksi ISBN</h5>
                    <span class="badge bg-light text-dark" id="collection-counter">0 koleksi</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" for="search_isbn">Nomor ISBN</label>
                        <p class="text-muted small mb-2">
                            Masukkan satu atau beberapa nomor ISBN sekaligus. Pisahkan tiap nomor dengan
                            baris baru atau koma. Tanda hubung boleh ditulis maupun dilewati.
                        </p>

                        <div class="border rounded bg-light px-3 py-2 mb-2">
                            <div class="text-muted small mb-1">
                                <i class="ph-lightbulb me-1"></i>
                                Contoh pengisian
                            </div>
                            <div class="font-monospace small lh-lg">
                                978-602-401-717-0<br>
                                9786230205484<br>
                                978-602-401-138-3, 978-623-02-0548-4
                            </div>
                        </div>

                        <textarea class="form-control font-monospace" id="search_isbn" rows="3"></textarea>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                            <span class="text-muted small">
                                <i class="ph-keyboard me-1"></i>
                                Tekan <kbd>Ctrl</kbd> + <kbd>Enter</kbd> untuk langsung mencari
                            </span>
                            <button type="button" class="btn btn-primary" onclick="searchISBN()">
                                <i class="ph-magnifying-glass me-1"></i>
                                Cari &amp; Tambahkan
                            </button>
                        </div>
                    </div>

                    <div id="isbn-empty-state" class="text-center text-muted py-5 border rounded bg-light">
                        <i class="ph-books ph-3x d-block mb-2 opacity-50"></i>
                        <div class="fw-semibold">Belum ada koleksi</div>
                        <div class="small">Masukkan nomor ISBN di atas untuk mulai menambahkan.</div>
                    </div>

                    <div id="isbn-collection-list" class="d-flex flex-column gap-3"></div>
                </div>
            </div>
        </div>

        {{-- ============ LANGKAH 3 ============ --}}
        <div class="step-pane d-none" data-pane="3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ph-truck me-1"></i> Metode Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border h-100 mb-0 choice-card" data-delivery="1" onclick="selectDeliveryType(1)">
                                <div class="card-body text-center py-4">
                                    <i class="ph-hand-waving ph-2x d-block mb-2 text-primary"></i>
                                    <strong>Kirim Langsung</strong>
                                    <div class="text-muted small mt-1">Serahkan koleksi secara langsung</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border h-100 mb-0 choice-card" data-delivery="2" onclick="selectDeliveryType(2)">
                                <div class="card-body text-center py-4">
                                    <i class="ph-package ph-2x d-block mb-2 text-success"></i>
                                    <strong>Ekspedisi</strong>
                                    <div class="text-muted small mt-1">Kirim melalui jasa pengiriman</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="type_delivery" id="type_delivery">
                </div>
            </div>

            <div id="expedition-card" class="d-none">
                <div class="row g-3">
                    <div class="col-xl-6 d-none" id="expedition-card-perpusnas">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="ph-airplane-takeoff me-1"></i> Ekspedisi Perpusnas</h6>
                            </div>
                            <div class="card-body" id="expedition-card-body-perpusnas"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 d-none" id="expedition-card-province">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="ph-airplane-takeoff me-1"></i> Ekspedisi Provinsi</h6>
                            </div>
                            <div class="card-body" id="expedition-card-body-province"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ph-clipboard-text me-1"></i> Ringkasan</h5>
                </div>
                <div class="card-body" id="summary-body"></div>
            </div>
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="ph-file-text me-1"></i>
                            Preview Surat Pengantar
                        </h5>

                        <div class="text-muted small mt-1">
                            Periksa kembali isi surat sebelum pengiriman disimpan.
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn btn-light btn-sm"
                        onclick="renderLetter()"
                    >
                        <i class="ph-arrows-clockwise me-1"></i>
                        Perbarui Preview
                    </button>
                </div>

                <div class="card-body bg-light">
                    <div
                        id="cover-letter-preview-loading"
                        class="text-center py-5 d-none"
                    >
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <div class="text-muted small mt-2">
                            Memuat preview surat pengantar...
                        </div>
                    </div>
                    <div class="row g-3">
                    {{-- Preview Perpusnas --}}
                    <div
                        id="preview-perpusnas-wrapper"
                        class="col-lg-6 d-none"
                    >
                        <div class="fw-semibold mb-2">
                            <i class="ph-bank me-1"></i>
                            Surat Pengantar Perpusnas
                        </div>

                        <iframe
                            id="preview-perpusnas"
                            style="
                                width: 100%;
                                height: 800px;
                                border: 1px solid #ddd;
                                background: #fff;
                                border-radius: 6px;
                            "
                        ></iframe>
                    </div>


                    {{-- Preview Provinsi --}}
                    <div
                        id="preview-province-wrapper"
                        class="col-lg-6 d-none"
                    >
                        <div class="fw-semibold mb-2">
                            <i class="ph-map-pin me-1"></i>
                            Surat Pengantar Perpustakaan Provinsi
                        </div>

                        <iframe
                            id="preview-province"
                            style="
                                width: 100%;
                                height: 800px;
                                border: 1px solid #ddd;
                                background: #fff;
                                border-radius: 6px;
                            "
                        ></iframe>
                    </div>

                </div>

                </div>
            </div>
        </div>
    </form>
</div>

{{-- Bilah ringkasan yang selalu terlihat --}}
<div class="border-top bg-white shadow-lg sticky-bottom">
    <div class="content py-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3 flex-wrap small">
                <span><i class="ph-books me-1 text-muted"></i> <strong id="bar-titles">0</strong> judul</span>
                <span><i class="ph-bank me-1 text-muted"></i> Perpusnas <strong id="bar-perpusnas">0</strong> eks</span>
                <span><i class="ph-map-pin me-1 text-muted"></i> Provinsi <strong id="bar-province">0</strong> eks</span>
                <span id="bar-incomplete" class="text-danger d-none"><i class="ph-warning-circle me-1"></i> <strong></strong></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light" id="btn-prev" onclick="prevStep()">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </button>
                <button type="button" class="btn btn-primary" id="btn-next" onclick="nextStep()">
                    Lanjut <i class="ph-arrow-right ms-1"></i>
                </button>
                <button type="button" class="btn btn-success d-none" id="btn-submit" onclick="submitted()">
                    <i class="ph-paper-plane-right me-1"></i> Kirim
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .step-item { cursor: pointer; opacity: .5; transition: opacity .2s; }
    .step-item.active, .step-item.done { opacity: 1; }
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        background: #e9ecef; color: #6c757d; font-weight: 600;
    }
    .step-item.active .step-badge { background: var(--bs-primary); color: #fff; }
    .step-item.done .step-badge { background: var(--bs-success); color: #fff; }
    .choice-card { cursor: pointer; transition: border-color .15s, box-shadow .15s; }
    .choice-card:hover { border-color: var(--bs-primary) !important; }
    .choice-card.selected { border-color: var(--bs-primary) !important; border-width: 2px !important; box-shadow: 0 0 0 .2rem rgba(13,110,253,.15); }
    .collection-card.is-incomplete { border-left: 4px solid var(--bs-danger) !important; }
    .collection-card.is-complete { border-left: 4px solid var(--bs-success) !important; }
    .field-error { display: none; font-size: .8125rem; color: var(--bs-danger); margin-top: .25rem; }
    .is-invalid ~ .field-error, .input-group .is-invalid ~ .field-error { display: block; }
    .sticky-bottom { position: sticky; bottom: 0; z-index: 100; }
</style>

<script>
    const STORAGE_KEY = 'physical_handover_form_v2_autosave_' + '{{ session("id") }}';
    const QUOTA = { perpusnas: 2, province: 1 };
    // Diambil dari konstanta controller supaya validasi sisi klien dan server tidak pernah berbeda
    const MIN_PRICE = {{ AddDeliveryFormController::MIN_PRICE }};

    var currentStep = 1;
    var rowSeq = 0;

    // ---------------------------------------------------------------- langkah

    function goToStep(step) {
        // Maju hanya boleh kalau semua langkah sebelumnya sudah valid
        for (var s = currentStep; s < step; s++) {
            if (!validateStep(s, true)) {
                return;
            }
        }

        currentStep = step;

        $('.step-pane').addClass('d-none');
        $(`.step-pane[data-pane="${step}"]`).removeClass('d-none');

        $('.step-item').each(function () {
            var s = parseInt($(this).data('step'), 10);

            $(this).toggleClass('active', s === step).toggleClass('done', s < step);
        });

        $('#btn-prev').toggleClass('d-none', step === 1);
        $('#btn-next').toggleClass('d-none', step === 3);
        $('#btn-submit').toggleClass('d-none', step !== 3);

        if (step === 3) {
            renderSummary();
            renderLetter();
        }

        autoSaveForm();
        $('html, body').animate({ scrollTop: 0 }, 200);
    }

    function nextStep() {
        if (currentStep < 3) {
            goToStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    }

    // ------------------------------------------------------------- pilihan

    function selectDestination(value) {
        $('#destination').val(value);
        $('.choice-card[data-destination]').removeClass('selected');
        $(`.choice-card[data-destination="${value}"]`).addClass('selected');

        // Kuota per koleksi berubah mengikuti tujuan, jadi baris perlu dihitung ulang
        $('#isbn-collection-list .collection-card').each(function () {
            applyDestinationToRow($(this));
        });

        refreshBar();
        autoSaveForm();
    }

    function selectDeliveryType(value) {
        $('#type_delivery').val(value);
        $('.choice-card[data-delivery]').removeClass('selected');
        $(`.choice-card[data-delivery="${value}"]`).addClass('selected');

        if (value == 2) {
            $('#expedition-card').removeClass('d-none');
            loadExpeditionForm();
        } else {
            $('#expedition-card').addClass('d-none');
        }

        autoSaveForm();
    }

    // ------------------------------------------------------------- koleksi

    function searchISBN() {
        var raw = $('#search_isbn').val() || '';
        var codes = raw.split(/[\n,;]+/).map(c => c.trim()).filter(Boolean);

        if (codes.length < 1) {
            notification('error', 'Mohon masukkan nomor ISBN terlebih dahulu');

            return;
        }

        var queue = codes.slice();
        var added = 0;
        var problems = [];
        var deferred = [];

        function next() {
            if (queue.length < 1) {
                onLoading('close', 'body');
                $('#search_isbn').val('').focus();

                if (added > 0) {
                    notification('success', added + ' koleksi berhasil ditambahkan');
                }

                if (problems.length > 0) {
                    swalInit.fire({
                        title: 'Sebagian ISBN Tidak Ditambahkan',
                        html: '<ul class="text-start ps-3 mb-0">' + problems.map(p => `<li>${p}</li>`).join('') + '</ul>',
                        icon: 'info'
                    }).then(function () {
                        askDeferred(deferred);
                    });

                    return;
                }

                askDeferred(deferred);

                return;
            }

            var code = queue.shift();
            var clean = code.replace(/[^0-9X]/gi, '');

            if (clean.length !== 10 && clean.length !== 13) {
                problems.push(`<strong>${escapeHtml(code)}</strong>: format ISBN tidak valid`);

                return next();
            }

            if ($(`#isbn-collection-list input[name="ci_code[]"][value="${clean}"]`).length > 0) {
                problems.push(`<strong>${escapeHtml(code)}</strong>: sudah ada dalam daftar`);

                return next();
            }

            $.ajax({
                url: '{{ url("physical-handover/add-delivery-form-v2/search-isbn") }}',
                type: 'GET',
                dataType: 'JSON',
                data: { code: code, executor_id: $('#executor_id').val(), destination: $('#destination').val() },
                success: function (response) {
                    var result = addCollection(response, code, clean, false);

                    if (result.status === 'problem') {
                        problems.push(result.message);
                    } else if (result.status === 'deferred') {
                        // Kuotanya habis hanya karena kiriman yang belum sampai,
                        // ditawarkan sekaligus setelah antrean selesai
                        deferred.push({ response: response, code: code, clean: clean, message: result.message });
                    } else {
                        added++;
                    }

                    next();
                },
                error: function () {
                    problems.push(`<strong>${escapeHtml(code)}</strong>: gagal menghubungi server`);
                    next();
                }
            });
        }

        onLoading('show', 'body');
        next();
    }

    /**
     * Menambahkan satu koleksi ke daftar.
     *
     * `force` dipakai saat penerbit memastikan kiriman sebelumnya tidak sampai,
     * sehingga kuota dihitung ulang seolah kiriman yang masih di jalan hilang.
     */
    function addCollection(response, originalCode, cleanCode, force) {
        var data = response ? response.data : null;

        function problem(message) {
            return { status: 'problem', message: `<strong>${escapeHtml(originalCode)}</strong>: ${message}` };
        }

        if (!data || typeof data !== 'object') {
            return problem('tidak ditemukan di database ISBN');
        }

        if ((data.jenis_media ?? '').toLowerCase() !== 'cetak') {
            return problem('bukan ISBN cetak, unggah sebagai karya digital');
        }

        if (data.penerbit_id != '{{ session("id") }}') {
            return problem(`terdaftar atas penerbit ${escapeHtml(data.nama_penerbit ?? 'lain')}`);
        }

        var perpusnas = response.perpusnas || {};
        var province = response.province || {};
        var quotaPerpusnas = parseInt((force ? perpusnas.quotaIfLost : perpusnas.quota) ?? 0, 10) || 0;
        var quotaProvince = parseInt((force ? province.quotaIfLost : province.quota) ?? 0, 10) || 0;

        // Tidak ada eksemplar yang perlu dikirim, jadi tidak ada gunanya ditambahkan
        if (quotaPerpusnas < 1 && quotaProvince < 1) {
            if (!force && (perpusnas.soft || province.soft)) {
                return {
                    status: 'deferred',
                    message: `<strong>${escapeHtml(data.title ?? originalCode)}</strong> (${escapeHtml(originalCode)})`
                };
            }

            return problem('sudah diserahkan, tidak perlu dikirim lagi');
        }

        rowSeq++;

        var id = 'row-' + rowSeq;

        $('#isbn-collection-list').append(`
            <div class="card border collection-card mb-0" id="${id}"
                 data-quota-perpusnas="${quotaPerpusnas}"
                 data-quota-province="${quotaProvince}"
                 data-forced="${force ? 1 : 0}"
                 data-note-perpusnas="${escapeHtml(evidenceText(perpusnas, 'Perpusnas'))}"
                 data-note-province="${escapeHtml(evidenceText(province, 'Provinsi'))}">
                <input type="hidden" name="ci[]" value="1">
                <input type="hidden" name="ci_code[]" value="${escapeHtml(cleanCode)}">
                <input type="hidden" name="ci_qty_perpusnas[]" value="0">
                <input type="hidden" name="ci_qty_province[]" value="0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">${response.fileCover || ''}</div>
                            <div>
                                <h6 class="mb-1">${escapeHtml(data.title ?? '-')}</h6>
                                <div class="text-muted small">${escapeHtml(data.kepeng ?? '-')}</div>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark">${escapeHtml(data.isbn ?? cleanCode)}</span>
                                    <span class="badge bg-light text-dark">${escapeHtml(data.tahun_terbit ?? '-')}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger completeness-badge">Belum lengkap</span>
                            <button type="button" class="btn btn-light btn-sm ms-1" onclick="removeCollection('${id}')">
                                <i class="ph-trash text-danger"></i>
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 py-2 small quota-note d-none"></div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">Tanggal Terbit <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" name="ci_publish_date[]" value="${response.publishDate || ''}" onchange="onFieldChange(this)">
                            <div class="field-error">Tanggal terbit wajib diisi</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" name="ci_price[]" placeholder="0" onchange="onFieldChange(this)" onkeyup="onFieldChange(this)">
                            </div>
                            <div class="field-error">Harga jual minimal Rp {{ number_format(AddDeliveryFormController::MIN_PRICE, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Jumlah Eksemplar</label>
                            <div class="qty-display small pt-1"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Sinopsis <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="ci_sinopsis[]" rows="3" placeholder="Lengkapi sinopsis koleksi" onchange="onFieldChange(this)" onkeyup="onFieldChange(this)">${escapeHtml(data.sinopsis ?? '')}</textarea>
                            <div class="field-error">Sinopsis wajib diisi</div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        var card = $('#' + id);

        applyDestinationToRow(card);
        $('#' + id + ' input[name="ci_price[]"]').number(true);
        validateCard(card, false);

        $('#isbn-empty-state').addClass('d-none');
        refreshBar();
        autoSaveForm();

        return { status: 'added' };
    }

    /**
     * Koleksi yang kuotanya habis semata-mata karena kiriman sebelumnya belum
     * diterima. Ditawarkan sekali di akhir antrean, bukan satu per satu, supaya
     * penambahan banyak ISBN tidak terpotong dialog berkali-kali.
     */
    function askDeferred(deferred) {
        if (!Array.isArray(deferred) || deferred.length < 1) {
            return;
        }

        swalInit.fire({
            title: 'Masih Dalam Pengiriman',
            html: `
                <p class="mb-2">Koleksi berikut sudah pernah dikirim dan <strong>belum diterima</strong>, jadi tidak ditambahkan:</p>
                <ul class="text-start ps-3 mb-2">${deferred.map(d => `<li>${d.message}</li>`).join('')}</ul>
                <p class="mb-0 small text-muted">Tambahkan hanya jika kiriman sebelumnya dipastikan tidak sampai.</p>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Tetap Tambahkan',
            cancelButtonText: 'Tidak Perlu'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            var forced = 0;

            deferred.forEach(function (item) {
                if (addCollection(item.response, item.code, item.clean, true).status === 'added') {
                    forced++;
                }
            });

            if (forced > 0) {
                notification('success', forced + ' koleksi ditambahkan sebagai kiriman ulang');
            }
        });
    }

    function removeCollection(id) {
        $('#' + id).fadeOut(200, function () {
            $(this).remove();

            if ($('#isbn-collection-list .collection-card').length < 1) {
                $('#isbn-empty-state').removeClass('d-none');
            }

            refreshBar();
            autoSaveForm();
        });
    }

    /**
     * Kuota yang tersimpan adalah kuota untuk kedua tujuan. Tujuan yang tidak
     * dipilih dinolkan supaya jumlah yang dikirim selalu mencerminkan pilihan
     * penerbit, bukan hasil perhitungan mentah dari server.
     */
    function applyDestinationToRow(card) {
        var destination = $('#destination').val();
        var perpusnas = ['1', '3'].includes(destination) ? (parseInt(card.data('quota-perpusnas'), 10) || 0) : 0;
        var province = ['2', '3'].includes(destination) ? (parseInt(card.data('quota-province'), 10) || 0) : 0;

        card.find('input[name="ci_qty_perpusnas[]"]').val(perpusnas);
        card.find('input[name="ci_qty_province[]"]').val(province);

        var parts = [];

        if (['1', '3'].includes(destination)) {
            parts.push(qtyChip('Perpusnas', perpusnas, QUOTA.perpusnas));
        }

        if (['2', '3'].includes(destination)) {
            parts.push(qtyChip('Provinsi', province, QUOTA.province));
        }

        card.find('.qty-display').html(parts.join(' '));

        var notes = [];

        if (['1', '3'].includes(destination) && perpusnas < QUOTA.perpusnas) {
            notes.push(card.attr('data-note-perpusnas'));
        }

        if (['2', '3'].includes(destination) && province < QUOTA.province) {
            notes.push(card.attr('data-note-province'));
        }

        notes = notes.filter(Boolean);

        card.find('.quota-note').toggleClass('d-none', notes.length < 1)
            .html(notes.map(n => escapeHtml(n)).join('<br>'));
    }

    /**
     * Menjelaskan kenapa kuota sebuah tujuan berkurang. Bukti berjumlah memakai
     * angka eksemplar, bukti dari API ISBN memakai tanggal karena jumlahnya
     * memang tidak diketahui.
     */
    function evidenceText(info, label) {
        if (!info || typeof info !== 'object') {
            return '';
        }

        if (info.basis === 'date' && info.date) {
            return `Tercatat sudah diserahkan ke ${label} pada ${info.date}.`;
        }

        var accepted = parseInt(info.accepted ?? 0, 10) || 0;
        var intransit = parseInt(info.intransit ?? 0, 10) || 0;
        var catalog = parseInt(info.catalog ?? 0, 10) || 0;
        var parts = [];

        if (accepted > 0) {
            parts.push(`${accepted} eks sudah diterima`);
        }

        if (accepted < 1 && intransit < 1 && catalog > 0) {
            parts.push(`${catalog} eks sudah tercatat di katalog`);
        }

        if (intransit > 0) {
            parts.push(`${intransit} eks masih dalam pengiriman`);
        }

        return parts.length > 0 ? `Ke ${label}: ${parts.join(', ')}.` : '';
    }

    function qtyChip(label, qty, quota) {
        if (qty < 1) {
            return `<span class="badge bg-secondary">${label}: sudah diserahkan</span>`;
        }

        var cls = qty < quota ? 'bg-warning text-dark' : 'bg-primary';

        return `<span class="badge ${cls}">${label}: ${qty} eks</span>`;
    }

    // ---------------------------------------------------------- validasi

    function onFieldChange(el) {
        validateCard($(el).closest('.collection-card'), true);
        refreshBar();
        autoSaveForm();
    }

    function parsePrice(value) {
        var clean = String(value ?? '').replace(/[.,]/g, '');

        return clean === '' || isNaN(clean) ? null : parseInt(clean, 10);
    }

    function validateCard(card, showError) {
        var problems = 0;

        function mark(field, ok) {
            if (showError) {
                field.toggleClass('is-invalid', !ok);
            }

            if (!ok) {
                problems++;
            }
        }

        mark(card.find('input[name="ci_publish_date[]"]'), !!card.find('input[name="ci_publish_date[]"]').val());
        mark(card.find('textarea[name="ci_sinopsis[]"]'), (card.find('textarea[name="ci_sinopsis[]"]').val() || '').trim() !== '');

        var price = parsePrice(card.find('input[name="ci_price[]"]').val());

        mark(card.find('input[name="ci_price[]"]'), price !== null && price >= MIN_PRICE);

        card.toggleClass('is-incomplete', problems > 0).toggleClass('is-complete', problems < 1);
        card.find('.completeness-badge')
            .toggleClass('bg-danger', problems > 0)
            .toggleClass('bg-success', problems < 1)
            .text(problems > 0 ? (problems + ' isian kurang') : 'Lengkap');

        return problems < 1;
    }

    function validateStep(step, showError) {
        var errors = [];

        if (step === 1) {
            if (!($('#sender_name').val() || '').trim()) errors.push('Nama pengirim tidak boleh kosong');
            if (!($('#phone').val() || '').trim()) errors.push('Nomor telepon tidak boleh kosong');
            if (!$('#destination').val()) errors.push('Tujuan penyerahan belum dipilih');
        }

        if (step === 2) {
            var cards = $('#isbn-collection-list .collection-card');

            if (cards.length < 1) {
                errors.push('Belum ada koleksi yang ditambahkan');
            }

            var incomplete = 0;

            cards.each(function () {
                if (!validateCard($(this), showError)) {
                    incomplete++;
                }
            });

            if (incomplete > 0) {
                errors.push(incomplete + ' koleksi masih memiliki isian yang belum lengkap');
            }

            if (cards.length > 0 && totalQty('perpusnas') + totalQty('province') < 1) {
                errors.push('Tidak ada eksemplar yang perlu dikirim untuk tujuan yang dipilih');
            }
        }

        if (errors.length > 0 && showError) {
            showValidation(errors);
        } else {
            clearValidation();
        }

        return errors.length < 1;
    }

    function clearValidation() {
        $('#validation-element').removeClass('show').addClass('d-none');
        $('#validation-data').html('');
    }

    function showValidation(data) {
        $('#validation-element').removeClass('d-none').addClass('show');
        $('#validation-data').html(data.map(v => `<li>${v}</li>`).join(''));
        $('html, body').animate({ scrollTop: 0 }, 200);
    }

    // ------------------------------------------------------------ ringkasan

    function totalQty(type) {
        var total = 0;

        $(`#isbn-collection-list input[name="ci_qty_${type}[]"]`).each(function () {
            total += parseInt($(this).val(), 10) || 0;
        });

        return total;
    }

    function refreshBar() {
        var cards = $('#isbn-collection-list .collection-card');
        var incomplete = cards.filter('.is-incomplete').length;

        $('#bar-titles').text(cards.length);
        $('#bar-perpusnas').text(totalQty('perpusnas'));
        $('#bar-province').text(totalQty('province'));
        $('#collection-counter').text(cards.length + ' koleksi');

        $('#bar-incomplete').toggleClass('d-none', incomplete < 1).find('strong')
            .text(incomplete + ' koleksi belum lengkap');
    }

    function renderSummary() {
        var destination = $('#destination').val();
        var destinationLabel = destination == 1 ? 'Perpusnas' : (destination == 2 ? 'Provinsi' : 'Perpusnas & Provinsi');
        var rows = [];

        $('#isbn-collection-list .collection-card').each(function () {
            var card = $(this);

            rows.push(`
                <tr>
                    <td>${card.find('h6').text()}</td>
                    <td class="text-nowrap">${card.find('input[name="ci_qty_perpusnas[]"]').val()} eks</td>
                    <td class="text-nowrap">${card.find('input[name="ci_qty_province[]"]').val()} eks</td>
                </tr>
            `);
        });

        $('#summary-body').html(`
            <div class="row g-3 mb-3">
                <div class="col-md-4"><div class="text-muted small">Pengirim</div><div class="fw-semibold">${escapeHtml($('#sender_name').val() || '-')}</div></div>
                <div class="col-md-4"><div class="text-muted small">Tujuan</div><div class="fw-semibold">${destinationLabel}</div></div>
                <div class="col-md-4"><div class="text-muted small">Total Judul</div><div class="fw-semibold">${$('#isbn-collection-list .collection-card').length}</div></div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light"><tr><th>Judul</th><th>Perpusnas</th><th>Provinsi</th></tr></thead>
                    <tbody>${rows.join('') || '<tr><td colspan="3" class="text-center text-muted">Belum ada koleksi</td></tr>'}</tbody>
                </table>
            </div>
        `);
    }

    // ----------------------------------------------------------- ekspedisi

    var expeditionLoadTimeout;
    var pendingExpedition = { perpusnas: null, province: null };

    /**
     * Mengembalikan pilihan ekspedisi hasil pemulihan. Hanya dipakai sekali,
     * supaya pemuatan ulang berikutnya tidak menimpa pilihan baru penerbit.
     */
    function applyPendingExpedition(type) {
        var value = pendingExpedition[type];

        if (!value) {
            return;
        }

        var option = $(`input[name="${type}_delivery"][value="${value.replace(/"/g, '\\"')}"]`);

        if (option.length > 0) {
            option.prop('checked', true);
        }

        pendingExpedition[type] = null;
    }

    function loadExpeditionForm2() {
        clearTimeout(expeditionLoadTimeout);

        expeditionLoadTimeout = setTimeout(function () {
            var weight = $('#weight').val();
            var destination = $('#destination').val();
            var hint = '<div class="alert alert-info border-0 mb-0"><i class="ph-info me-1"></i> Mohon isi berat paket terlebih dahulu (minimal 1 Kg).</div>';

            $('#expedition-card-perpusnas').toggleClass('d-none', !['1', '3'].includes(destination));
            $('#expedition-card-province').toggleClass('d-none', !['2', '3'].includes(destination));

            if (!weight || isNaN(weight) || parseFloat(weight) <= 0) {
                $('#expedition-card-body-perpusnas, #expedition-card-body-province').html(hint);

                return;
            }

            $.ajax({
                url: '{{ url("physical-handover/add-delivery-form-v2/calculate-cost") }}',
                type: 'GET',
                dataType: 'JSON',
                data: { weight: weight, destination: destination },
                beforeSend: function () {
                    $('#expedition-card-body-perpusnas, #expedition-card-body-province')
                        .html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div><div class="mt-2 text-muted small">Memuat pilihan ekspedisi...</div></div>');
                },
                success: function (response) {
                    $('#expedition-card-body-perpusnas').html(buildExpeditionOptions(response ? response.perpusnas : null, 'perpusnas'));
                    $('#expedition-card-body-province').html(buildExpeditionOptions(response ? response.province : null, 'province'));

                    applyPendingExpedition('perpusnas');
                    applyPendingExpedition('province');
                },
                error: function () {
                    var err = '<div class="alert alert-danger border-0 mb-0">Gagal memuat pilihan ekspedisi.</div>';

                    $('#expedition-card-body-perpusnas, #expedition-card-body-province').html(err);
                }
            });
        }, 500);
    }
    function loadExpeditionForm() {
        clearTimeout(expeditionLoadTimeout);

        expeditionLoadTimeout = setTimeout(function () {
            var destination = $('#destination').val();

            $('#expedition-card-perpusnas')
                .toggleClass('d-none', !['1', '3'].includes(destination));

            $('#expedition-card-province')
                .toggleClass('d-none', !['2', '3'].includes(destination));

            $.ajax({
                url: '{{ url("physical-handover/add-delivery-form-v2/calculate-cost") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    destination: destination
                },

                beforeSend: function () {
                    $('#expedition-card-body-perpusnas, #expedition-card-body-province')
                        .html(`
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                                <div class="mt-2 text-muted small">
                                    Memuat jasa pengiriman...
                                </div>
                            </div>
                        `);
                },

                success: function (response) {
                    $('#expedition-card-body-perpusnas').html(
                        buildExpeditionOptions(
                            response ? response.perpusnas : null,
                            'perpusnas'
                        )
                    );

                    $('#expedition-card-body-province').html(
                        buildExpeditionOptions(
                            response ? response.province : null,
                            'province'
                        )
                    );

                    applyPendingExpedition('perpusnas');
                    applyPendingExpedition('province');
                },

                error: function () {
                    var err = `
                        <div class="alert alert-danger border-0 mb-0">
                            Gagal memuat jasa pengiriman.
                        </div>
                    `;

                    $('#expedition-card-body-perpusnas, #expedition-card-body-province')
                        .html(err);
                }
            });

        }, 300);
    }
    function buildExpeditionOptions2(data, type) {
        if (!data || typeof data !== 'object') {
            return '<div class="alert alert-warning border-0 mb-0">Tidak ada pengiriman yang tersedia.</div>';
        }

        var options = [].concat(data.calculate_reguler || [], data.calculate_cargo || []);

        if (options.length < 1) {
            return '<div class="alert alert-warning border-0 mb-0">Tidak ada pengiriman yang tersedia.</div>';
        }

        return options.map(function (val, i) {
            var cost = parseFloat(val.shipping_cost) || 0;
            var total = parseFloat(val.grandtotal) || 0;
            var name = escapeHtml(val.shipping_name || '');
            var service = escapeHtml(val.service_name || '');

            return `
                <div class="form-check border rounded p-3 mb-2">
                    <input type="radio" class="form-check-input" name="${type}_delivery" id="${type}_opt_${i}" ${i === 0 ? 'checked' : ''}
                           value="${name};${service};${total};${cost}" onchange="autoSaveForm()">
                    <label class="form-check-label w-100" for="${type}_opt_${i}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">${name}</div>
                                <div class="text-muted small">${service}</div>
                            </div>
                            <span class="badge bg-primary">Rp ${$.number(cost)}</span>
                        </div>
                        <div class="mt-1 small text-muted"><i class="ph-clock me-1"></i> Estimasi: ${escapeHtml(val.etd || '-')}</div>
                    </label>
                </div>
            `;
        }).join('');
    }
    function buildExpeditionOptions(data, type) {
        if (!Array.isArray(data) || data.length < 1) {
            return `
                <div class="alert alert-warning border-0 mb-0">
                    Tidak ada jasa pengiriman yang tersedia.
                </div>
            `;
        }

        return data.map(function (val, i) {
            var id = val.ID ?? val.id ?? '';
            var name = escapeHtml(val.NAME ?? val.name ?? '');
            var code = escapeHtml(val.CODE ?? val.code ?? '');

            return `
                <div class="form-check border rounded p-3 mb-2">
                    <input
                        type="radio"
                        class="form-check-input"
                        name="${type}_delivery"
                        id="${type}_opt_${i}"
                        value="${id};${code};${name}"
                        ${i === 0 ? 'checked' : ''}
                        onchange="autoSaveForm()"
                    >

                    <label
                        class="form-check-label w-100"
                        for="${type}_opt_${i}"
                    >
                        <div class="fw-semibold">
                            ${name}
                        </div>
                    </label>
                </div>
            `;
        }).join('');
    }

    // ------------------------------------------------------------ autosave

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    var saveTimeout;
    // Penyimpanan ditahan sampai proses pemulihan selesai, supaya inisialisasi
    // halaman tidak menimpa isian yang sudah tersimpan
    var suppressSave = true;

    function debounceSave() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(autoSaveForm, 400);
    }

    function autoSaveForm() {
        if (suppressSave) {
            return;
        }

        var payload = {
            step: currentStep,
            sender_name: $('#sender_name').val(),
            phone: $('#phone').val(),
            cover_letter_number: $('#cover_letter_number').val(),
            destination: $('#destination').val(),
            weight: $('#weight').val(),
            type_delivery: $('#type_delivery').val(),
            perpusnas_delivery: $('input[name="perpusnas_delivery"]:checked').val() || '',
            province_delivery: $('input[name="province_delivery"]:checked').val() || '',
            collections: [],
            saved_at: new Date().toISOString()
        };

        $('#isbn-collection-list .collection-card').each(function () {
            var card = $(this);

            payload.collections.push({
                code: card.find('input[name="ci_code[]"]').val(),
                publish_date: card.find('input[name="ci_publish_date[]"]').val(),
                sinopsis: card.find('textarea[name="ci_sinopsis[]"]').val(),
                price: card.find('input[name="ci_price[]"]').val(),
                forced: card.attr('data-forced') === '1'
            });
        });

        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
            $('#autosave-indicator').text('Tersimpan otomatis ' + new Date().toLocaleTimeString('id-ID'));
            $('#btn-clear-autosave').removeClass('d-none');
        } catch (e) {
            $('#autosave-indicator').text('');
        }
    }

    function clearAutoSave() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            // abaikan, penyimpanan lokal tidak tersedia
        }

        $('#autosave-indicator').text('');
        $('#btn-clear-autosave').addClass('d-none');
    }

    function confirmClearAutoSave() {
        swalInit.fire({
            title: 'Hapus Data Tersimpan?',
            text: 'Seluruh isian yang tersimpan otomatis akan dihapus dan halaman dimuat ulang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                clearAutoSave();
                location.reload();
            }
        });
    }

    function restoreForm(done) {
        var saved = null;

        try {
            saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
        } catch (e) {
            return done();
        }

        if (!saved) {
            return done();
        }

        var collections = Array.isArray(saved.collections) ? saved.collections : [];
        var hasHeader = !!(saved.cover_letter_number || saved.weight || saved.type_delivery);

        // Nama pengirim dan telepon terisi otomatis dari sesi, jadi tidak dihitung
        if (collections.length < 1 && !hasHeader) {
            return done();
        }

        $('#btn-clear-autosave').removeClass('d-none');

        var detail = collections.length > 0
            ? `berisi <strong>${collections.length} koleksi</strong>`
            : 'berisi informasi pengiriman';

        swalInit.fire({
            title: 'Lanjutkan Pengisian?',
            html: `Ada isian tersimpan ${detail}. Lanjutkan dari sana?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Mulai Baru'
        }).then(function (result) {
            if (!result.isConfirmed) {
                clearAutoSave();

                return done();
            }

            $('#sender_name').val(saved.sender_name || '');
            $('#phone').val(saved.phone || '');
            $('#cover_letter_number').val(saved.cover_letter_number || '');
            $('#weight').val(saved.weight || '');
            selectDestination(saved.destination || 3);

            // Pilihan ekspedisi baru bisa dicentang setelah daftarnya termuat
            pendingExpedition = {
                perpusnas: saved.perpusnas_delivery || null,
                province: saved.province_delivery || null
            };

            if (saved.type_delivery) {
                selectDeliveryType(saved.type_delivery);
            }

            if (collections.length < 1) {
                goToStep(parseInt(saved.step, 10) || 1);

                return done();
            }

            restoreCollections(collections, function (dropped) {
                refreshBar();
                goToStep(parseInt(saved.step, 10) || 1);
                done();

                if (dropped.length > 0) {
                    swalInit.fire({
                        title: 'Sebagian Koleksi Tidak Dipulihkan',
                        html: `
                            <p class="mb-2">Koleksi berikut sudah tidak perlu dikirim lagi, jadi tidak ikut dipulihkan:</p>
                            <ul class="text-start ps-3 mb-0">${dropped.map(d => `<li>${escapeHtml(d)}</li>`).join('')}</ul>
                        `,
                        icon: 'info'
                    });
                }
            });
        });
    }

    /**
     * Kuota diambil ulang dari server, bukan dari penyimpanan lokal, supaya
     * pengiriman yang terjadi sejak isian terakhir disimpan tetap diperhitungkan.
     * Isian yang sudah diketik penerbit dikembalikan setelah kartunya terbentuk.
     */
    function restoreCollections(items, done) {
        var queue = items.slice();
        var dropped = [];

        function step() {
            if (queue.length < 1) {
                onLoading('close', 'body');
                done(dropped);

                return;
            }

            var item = queue.shift();

            $.ajax({
                url: '{{ url("physical-handover/add-delivery-form-v2/search-isbn") }}',
                type: 'GET',
                dataType: 'JSON',
                data: { code: item.code, executor_id: $('#executor_id').val(), destination: $('#destination').val() },
                success: function (response) {
                    if (addCollection(response, item.code, item.code, !!item.forced).status !== 'added') {
                        dropped.push(item.code);

                        return step();
                    }

                    var card = $(`#isbn-collection-list input[name="ci_code[]"][value="${item.code}"]`).closest('.collection-card');

                    if (item.publish_date) card.find('input[name="ci_publish_date[]"]').val(item.publish_date);
                    if (item.sinopsis) card.find('textarea[name="ci_sinopsis[]"]').val(item.sinopsis);
                    if (item.price) card.find('input[name="ci_price[]"]').val(item.price);

                    validateCard(card, false);
                    step();
                },
                error: function () {
                    dropped.push(item.code);
                    step();
                }
            });
        }

        onLoading('show', 'body');
        step();
    }

    // -------------------------------------------------------------- submit

    function submitted() {
        if (!validateStep(1, true)) {
            goToStep(1);

            return;
        }

        if (!validateStep(2, true)) {
            goToStep(2);

            return;
        }

        if (!$('#type_delivery').val()) {
            showValidation(['Metode pengiriman belum dipilih']);

            return;
        }

        swalInit.fire({
            title: 'Konfirmasi Pengiriman',
            html: `Anda akan mengirim <strong>${$('#isbn-collection-list .collection-card').length} judul</strong>. Lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Periksa Kembali'
        }).then(function (result) {
            if (result.isConfirmed) {
                processSubmit();
            }
        });
    }
    function loadCoverLetterPreview(
        form,
        target,
        iframeSelector,
        wrapperSelector
    ) {

        var formData = new FormData(form);

        formData.append('target', target);

        return fetch(
            '{{ url("physical-handover/add-delivery-form-v2/preview-cover-letter") }}',
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}'
                },
                body: formData
            }
        )
        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Gagal membuat preview ' + target
                );
            }
            return response.blob();

        })
        .then(function (blob) {
            var pdfUrl = URL.createObjectURL(blob);
            $(iframeSelector).attr('src', pdfUrl);
            $(wrapperSelector).removeClass('d-none');

        })
        .catch(function (error) {
            console.error(error);
        });
    }
    function processSubmit() {
        $.ajax({
            url: '{{ url("physical-handover/add-delivery-form-v2/submitted") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-data').serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            beforeSend: function () {
                onLoading('show', 'body');
                clearValidation();
            },
            success: function (response) {
                onLoading('close', 'body');

                if (response.code == 200) {
                    clearAutoSave();

                    swalInit.fire({
                        title: 'Pengiriman Berhasil!',
                        html: response.message,
                        icon: 'success',
                        confirmButtonText: 'Selesai',
                        allowOutsideClick: false
                    }).then(function () {
                        onLoading('show', 'body');
                        location.href = '{{ url("physical-handover/delivery-monitoring") }}';
                    });

                    return;
                }

                if (response.code == 400) {
                    showValidation(response.error || ['Terdapat kesalahan pada form.']);

                    return;
                }

                swalInit.fire({ title: 'Perhatian', text: response.message, icon: 'info' });
            },
            error: function (response) {
                onLoading('close', 'body');
                responseError(response);
            }
        });
    }
    function renderLetter() {

        var form = document.getElementById('form-data');

        if (!form) {
            console.error('Form #form-data tidak ditemukan');
            return;
        }

        var destination = $('#destination').val();

        // Reset
        $('#preview-perpusnas-wrapper').addClass('d-none');
        $('#preview-province-wrapper').addClass('d-none');

        $('#cover-letter-preview-loading').removeClass('d-none');


        var requests = [];


        // ==========================================
        // PERPUSNAS
        // ==========================================

        if (destination == '1' || destination == '3') {

            requests.push(
                loadCoverLetterPreview(
                    form,
                    'perpusnas',
                    '#preview-perpusnas',
                    '#preview-perpusnas-wrapper'
                )
            );

        }


        // ==========================================
        // PROVINSI
        // ==========================================

        if (destination == '2' || destination == '3') {

            requests.push(
                loadCoverLetterPreview(
                    form,
                    'province',
                    '#preview-province',
                    '#preview-province-wrapper'
                )
            );

        }


        Promise.all(requests)
            .finally(function () {

                $('#cover-letter-preview-loading')
                    .addClass('d-none');

            });
    }

    // ---------------------------------------------------------------- init

    $(function () {
        selectDestination(3);
        goToStep(1);
        refreshBar();

        $('#destination').on('change', function () {
            if ($('#type_delivery').val() == 2) {
                loadExpeditionForm();
            }
        });

        // Semua isian di dalam form ikut tersimpan, termasuk yang ditambahkan belakangan
        $('#form-data').on('change input', 'input, textarea, select', debounceSave);

        // Enter dibiarkan membuat baris baru karena satu kotak bisa memuat banyak ISBN
        $('#search_isbn').on('keydown', function (e) {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                searchISBN();
            }
        });

        restoreForm(function () {
            suppressSave = false;
        });
    });
</script>
