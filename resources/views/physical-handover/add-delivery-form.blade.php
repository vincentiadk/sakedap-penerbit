<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - <span class="fw-normal">Tambah Form Pengiriman</span>
            </h4>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
            <div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">
                <div id="remove-btn-autosave"></div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert alert-danger alert-dismissible fade d-none" id="validation-element">
        <ul class="mb-0" id="validation-data"></ul>
    </div>
    <form id="form-data">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph-address-book me-1"></i>
                    Informasi Pengiriman
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Nama Pengirim
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-user"></i></span>
                            <input type="text" class="form-control" name="sender_name" id="sender_name" value="{{ session('name') }}" placeholder="Masukkan nama pengirim">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Pelaksana Serah
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-users-three"></i></span>
                            <select class="form-select select2-basic" name="executor_id" id="executor_id" data-placeholder="Semua" data-width="1%">
                                <option value=""></option>
                                @if(Main::getExecutorGroup())
                                    @foreach(Main::getExecutorGroup() as $geg)
                                        <option value="{{ $geg->ID }}" {{ session('id') == $geg->ID ? 'selected' : '' }}>{{ $geg->NAME }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ session('id') }}" selected>{{ session('name') }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Tujuan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-map-pin"></i></span>
                            <select class="form-select" name="destination" id="destination">
                                <option value="1">Perpusnas</option>
                                <option value="2">Provinsi</option>
                                <option value="3" selected>Perpusnas & Provinsi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Nomor Telepon
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-phone"></i></span>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{ Main::phoneFormat(session('phone')) }}" placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Nomor Surat Pengantar
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-envelope"></i></span>
                            <input type="text" class="form-control" name="cover_letter_number" id="cover_letter_number" placeholder="Contoh: 001/SP/2025">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Berat Paket
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-package"></i></span>
                            <input type="number" class="form-control" name="weight" id="weight" placeholder="Minimal 1" min="1" step="0.1">
                            <span class="input-group-text">Kg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white d-sm-flex align-items-sm-center py-3">
                <h5 class="mb-sm-0">
                    <i class="ph-barcode me-1"></i>
                    Koleksi ISBN
                </h5>
                <div class="ms-sm-auto my-sm-auto">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ph-magnifying-glass"></i></span>
                        <input type="text" class="form-control" name="search_isbn" id="search_isbn" placeholder="Masukkan Nomor ISBN" onkeypress="if(event.keyCode == 13) { searchISBN(); return false; }">
                        <button type="button" class="btn btn-primary" onclick="searchISBN()">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0" role="alert">
                    <i class="ph-info me-1"></i>
                    Masukkan nomor ISBN dan klik tombol <strong>Cari</strong> untuk menambahkan koleksi
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-nowrap" width="80">Cover</th>
                                <th class="text-nowrap">Tgl Terbit</th>
                                <th class="text-nowrap">Judul</th>
                                <th class="text-nowrap">Kepengarangan</th>
                                <th class="text-nowrap">Pelaksana Serah</th>
                                <th class="text-nowrap">Tahun Terbit</th>
                                <th class="text-nowrap">Identifier</th>
                                <th class="text-nowrap">Sinopsis</th>
                                <th class="text-nowrap">Jumlah Eks Perpusnas</th>
                                <th class="text-nowrap">Jumlah Eks Provinsi</th>
                                <th class="text-center text-nowrap" width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-collection-isbn">
                            <tr id="empty-isbn-row">
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="ph-books ph-3x d-block mb-2 opacity-50"></i>
                                    Belum ada data ISBN
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph-book-open me-1"></i>
                    Koleksi Non ISBN
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0" role="alert">
                    <i class="ph-info me-1"></i>
                    Hanya untuk koleksi tidak berISBN, contoh: Buku tidak berISBN, CD, DVD, Bluray, Kaset Pita, dll
                </div>
                <div class="table-responsive" id="non-isbn-table-container">
                    <table class="table table-bordered">
                        <tbody id="data-collection-non-isbn">
                            <tr id="empty-non-isbn-row">
                                <td class="text-center text-muted py-4">
                                    <i class="ph-article ph-3x d-block mb-2 opacity-50"></i>
                                    Belum ada data koleksi non ISBN
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="button" class="btn btn-success" onclick="addCollectionNonISBN()">
                                <i class="ph-plus-circle me-1"></i>
                                Tambah
                            </button>
                            <input type="number" class="form-control text-center" id="add-number-collection-non-isbn"
                                min="1" max="10" value="1" placeholder="Jumlah">
                            <span class="input-group-text">Baris</span>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <small class="text-muted">
                            <i class="ph-info me-1"></i>
                            Maksimal menambahkan 10 baris sekaligus
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph-newspaper me-1"></i>
                    Koleksi Terbitan Berkala
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0" role="alert">
                    <i class="ph-info me-1"></i>
                    Tambahkan koleksi terbitan berkala seperti majalah, jurnal, atau buletin
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody id="data-collection-periodicals">
                            <tr id="empty-periodicals-row">
                                <td class="text-center text-muted py-4">
                                    <i class="ph-newspaper-clipping ph-3x d-block mb-2 opacity-50"></i>
                                    Belum ada data terbitan berkala
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="button" class="btn btn-success" onclick="addPeriodicals()">
                                <i class="ph-plus-circle me-1"></i>
                                Tambah
                            </button>
                            <input type="number" class="form-control text-center" id="add-number-collection-periodicals" min="1" max="10" value="1" placeholder="Jumlah">
                            <span class="input-group-text">Baris</span>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <small class="text-muted">
                            <i class="ph-info me-1"></i>
                            Maksimal menambahkan 10 baris sekaligus
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph-truck me-1"></i>
                    Metode Pengiriman
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border mb-0 h-100 cursor-pointer delivery-type-card" onclick="selectDeliveryType(1)">
                            <div class="card-body text-center">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type_delivery" value="1" id="type-delivery-1" onchange="typeDelivery()"><br>
                                    <label class="form-check-label" for="type-delivery-1">
                                        <i class="ph-hand-waving ph-2x d-block mb-2 text-primary"></i>
                                        <strong>Kirim Langsung</strong>
                                        <div class="text-muted small mt-1">Serahkan koleksi secara langsung</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-0 h-100 cursor-pointer delivery-type-card" onclick="selectDeliveryType(2)">
                            <div class="card-body text-center">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type_delivery" value="2" id="type-delivery-2" onchange="typeDelivery()">
                                    <label class="form-check-label" for="type-delivery-2">
                                        <i class="ph-package ph-2x d-block mb-2 text-success"></i>
                                        <strong>Kirim Menggunakan Ekspedisi</strong>
                                        <div class="text-muted small mt-1">Kirim melalui jasa pengiriman</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="expedition-card" style="display: none;">
            <div class="row g-3">
                <div class="col-md-6" id="expedition-card-perpusnas" style="display: none;">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="ph-airplane-takeoff me-1"></i>
                                Ekspedisi Perpusnas
                            </h5>
                        </div>
                        <div class="card-body" id="expedition-card-body-perpusnas">
                            <div class="alert alert-info border-0">
                                <i class="ph-info me-1"></i>
                                Mohon isi berat paket dan tujuan pengiriman terlebih dahulu (minimal 1 Kg).
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="expedition-card-province" style="display: none;">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="ph-airplane-takeoff me-1"></i>
                                Ekspedisi Provinsi
                            </h5>
                        </div>
                        <div class="card-body" id="expedition-card-body-province">
                            <div class="alert alert-info border-0">
                                <i class="ph-info me-1"></i>
                                Mohon isi berat paket dan tujuan pengiriman terlebih dahulu (minimal 1 Kg).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        <i class="ph-info me-1"></i>
                        Pastikan semua data telah diisi dengan benar
                    </small>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitted()">
                    <i class="ph-paper-plane-right me-1"></i>
                    Kirim Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .delivery-type-card:hover {
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .delivery-type-card input[type="radio"]:checked~label {
        color: var(--bs-primary);
    }

    .expedition-option {
        transition: all 0.2s ease;
    }

    .expedition-option:hover {
        background-color: rgba(0, 0, 0, 0.025);
    }

    .expedition-option input[type="radio"]:checked~label {
        font-weight: 600;
    }

    #data-collection-isbn tr:not(#empty-isbn-row):hover, #data-collection-non-isbn tr:not(#empty-non-isbn-row):hover, #data-collection-periodicals tr:not(#empty-periodicals-row):hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .input-mode-switch {
        transition: all 0.3s ease;
    }

    .periodical-catalog-section, .periodical-manual-section {
        display: none;
    }

    .periodical-catalog-section.active, .periodical-manual-section.active {
        display: block;
    }
</style>

<script>
    $(function() {
        datePickerSingle('.date-single');
        typeDelivery();
        setupAutoSave();

        setTimeout(() => {
            restoreFormData();
        }, 500);
    });

    const STORAGE_KEY = 'physical_handover_form_autosave_' + '{{ session("id") }}';
    const SAVE_DELAY = 1000;
    let saveTimeout;

    function typeNonISBN(param) {
        var valueOption = $('.cni-type-' + param).val();
        var dataAttrOption = $('.cni-type-' + param + ' option[value="' + valueOption + '"]').data('category');

        if(dataAttrOption == 'KRA') {
            $('.cni-is-analog-' + param).val(1);
            $('.cni-pn-' + param).val(1);
            $('.cni-prov-' + param).val(1);
        } else {
            $('.cni-is-analog-' + param).val(0);
            $('.cni-pn-' + param).val(2);
            $('.cni-prov-' + param).val(1);
        }
    }

    function autoSaveForm() {
        clearTimeout(saveTimeout);

        saveTimeout = setTimeout(() => {
            const formData = {
                sender_name: $('#sender_name').val(),
                executor_id: $('#executor_id').val(),
                destination: $('#destination').val(),
                phone: $('#phone').val(),
                cover_letter_number: $('#cover_letter_number').val(),
                weight: $('#weight').val(),
                type_delivery: $('input[name="type_delivery"]:checked').val(),
                perpusnas_delivery: $('input[name="perpusnas_delivery"]:checked').val(),
                province_delivery: $('input[name="province_delivery"]:checked').val(),
                isbn_collections: [],
                non_isbn_collections: [],
                periodicals_collections: [],
                timestamp: new Date().toISOString()
            };

            $('#data-collection-isbn tr').not('#empty-isbn-row').each(function() {
                const row = $(this);

                formData.isbn_collections.push({
                    code: row.find('input[name="ci_code[]"]').val(),
                    publish_date: row.find('input[name="ci_publish_date[]"]').val(),
                    qty_perpusnas: row.find('input[name="ci_qty_perpusnas[]"]').val(),
                    qty_province: row.find('input[name="ci_qty_province[]"]').val(),
                });
            });

            $('#data-collection-non-isbn tr').not('#empty-non-isbn-row').each(function() {
                const row = $(this);

                formData.non_isbn_collections.push({
                    catalog_id: row.find('input[name="cni_catalog_id[]"]').val(),
                    executor: row.find('input[name="cni_executor[]"]').val(),
                    title: row.find('input[name="cni_title[]"]').val(),
                    author: row.find('input[name="cni_author[]"]').val(),
                    physical_description: row.find('input[name="cni_physical_description[]"]').val(),
                    year: row.find('input[name="cni_year[]"]').val(),
                    binding: row.find('input[name="cni_binding[]"]').val(),
                    type: row.find('select[name="cni_type[]"]').val(),
                    qrcbn: row.find('input[name="cni_qrcbn[]"]').val(),
                    isbd: row.find('input[name="cni_isbd[]"]').val(),
                    price: row.find('input[name="cni_price[]"]').val()
                });
            });

            const processedPeriodicals = new Set();

            $('#data-collection-periodicals tr').each(function() {
                const row = $(this);
                const cpIndex = row.data('cp-index');

                if (cpIndex && !processedPeriodicals.has(cpIndex)) {
                    processedPeriodicals.add(cpIndex);

                    const classes = row.attr('class');
                    const match = classes ? classes.match(/periodical-row-(\w+)/) : null;
                    const randStr = match ? match[1] : null;

                    if (!randStr) return;

                    const catalogActive = $(`.periodical-catalog-section-${randStr}`).hasClass('active');

                    const periodicalData = {
                        randStr: randStr,
                        cpIndex: cpIndex,
                        mode: catalogActive ? 'catalog' : 'manual',
                        catalog_id: catalogActive ? $(`.cp_catalog_id_${randStr}`).val() : '',
                        catalog_text: catalogActive ? $(`.cp_catalog_text_${randStr}`).val() : '',
                        manual_title: !catalogActive ? row.find(`input[name="cp_manual_title[${cpIndex}]"]`).val() : '',
                        editions: []
                    };

                    $(`#data-collection-periodicals-edition-${cpIndex} .card`).each(function() {
                        const editionCard = $(this);
                        const editionClasses = editionCard.attr('class');

                        if (editionClasses && editionClasses.includes('edition-card-')) {
                            periodicalData.editions.push({
                                edition: editionCard.find(`input[name="cpe_edition[${cpIndex}][]"]`).val(),
                                first_ttes: editionCard.find(`input[name="cpe_first_ttes[${cpIndex}][]"]`).val(),
                                end_ttes: editionCard.find(`input[name="cpe_end_ttes[${cpIndex}][]"]`).val()
                            });
                        }
                    });

                    formData.periodicals_collections.push(periodicalData);
                }
            });

            localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));

            $('#clear-autosave-btn').fadeIn();
        }, SAVE_DELAY);
    }

    function restoreFormData() {
        try {
            const savedData = localStorage.getItem(STORAGE_KEY);

            if (!savedData) {
                return false;
            }

            const formData = JSON.parse(savedData);

            swalInit.fire({
                title: 'Data Tersimpan Ditemukan',
                html: `
                    Ditemukan data yang tersimpan pada:<br><strong>${new Date(formData.timestamp).toLocaleString('id-ID')}</strong><br><br>Apakah Anda ingin memulihkan data tersebut?
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="ph-arrow-counter-clockwise me-1"></i> Pulihkan Data',
                cancelButtonText: '<i class="ph-x me-1"></i> Mulai Baru',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    performRestore(formData);
                } else {
                    clearAutoSave();
                }
            });

            return true;
        } catch (error) {
            return false;
        }
    }

    function performRestore(formData) {
        try {
            onLoading('show', 'body');

            $('#form-data').off('input change');

            if (!formData || typeof formData !== 'object') {
                throw new Error('Invalid form data structure');
            }

            $('#sender_name').val(formData.sender_name || '');
            $('#executor_id').val(formData.executor_id || '').trigger('change');
            $('#destination').val(formData.destination || '3').trigger('change');
            $('#phone').val(formData.phone || '');
            $('#cover_letter_number').val(formData.cover_letter_number || '');
            $('#weight').val(formData.weight || '');

            if (formData.type_delivery) {
                $(`#type-delivery-${formData.type_delivery}`).prop('checked', true).trigger('change');
            }

            if (Array.isArray(formData.isbn_collections) && formData.isbn_collections.length > 0) {
                $('#empty-isbn-row').remove();

                const fetchPromises = formData.isbn_collections.map(item => {
                    return $.ajax({
                        url: '{{ url("physical-handover/add-delivery-form/search-isbn") }}',
                        type: 'GET',
                        dataType: 'JSON',
                        data: {
                            code: item.code,
                            executor_id: $('#executor_id').val(),
                            destination: $('#destination').val(),
                        }
                    }).then(response => {
                        if (!response.data) return;

                        const data = response.data;
                        var safeTitle = $('<div>').text(data.title ?? '-').html();
                        var safeKepeng = $('<div>').text(data.kepeng ?? '-').html();
                        var safePenerbit = $('<div>').text(data.nama_penerbit ?? '-').html();
                        var safeSinopsis = $('<div>').text(data.sinopsis ?? '-').html();
                        var safeISBN = $('<div>').text(data.isbn ?? '-').html();

                        $('#data-collection-isbn').append(`
                            <tr class="animate__animated animate__fadeIn">
                                <input type="hidden" name="ci[]" value="1">
                                <input type="hidden" name="ci_code[]" value="${safeISBN}">
                                <td class="text-center align-middle">${response.fileCover ?? '<span class="text-muted">-</span>'}</td>
                                <td class="align-middle" nowrap>
                                    <input type="date" class="form-control form-control-sm" name="ci_publish_date[]" value="${item.publish_date || response.publishDate}">
                                </td>
                                <td class="align-middle text-wrap">${safeTitle}</td>
                                <td class="align-middle text-wrap">${safeKepeng}</td>
                                <td class="align-middle text-wrap">${safePenerbit}</td>
                                <td class="align-middle">${data.tahun_terbit ?? '-'}</td>
                                <td class="align-middle text-nowrap">${safeISBN}</td>
                                <td class="align-middle text-wrap">
                                    <div class="readmore-block">${safeSinopsis}</div>
                                </td>
                                <td class="align-middle">
                                    <input type="hidden" name="ci_qty_perpusnas[]" value="${item.qty_perpusnas ?? response.qtyPerpusnas}">
                                    ${item.qty_perpusnas ?? response.qtyPerpusnas}
                                </td>
                                <td class="align-middle">
                                    <input type="hidden" name="ci_qty_province[]" value="${item.qty_province ?? response.qtyProvince}">
                                    ${item.qty_province ?? response.qtyProvince}
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                        <i class="ph-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                });

                Promise.allSettled(fetchPromises).then(() => {
                    readmoreJS();
                });
            }

            if (Array.isArray(formData.non_isbn_collections) && formData.non_isbn_collections.length > 0) {
                $('#empty-non-isbn-row').remove();

                formData.non_isbn_collections.forEach(item => {
                    if (!item || typeof item !== 'object') {
                        return;
                    }

                    const randStr = randomString(10);
                    var safeTitle = $('<div>').text(item.title || '').html();
                    var safeAuthor = $('<div>').text(item.author || '').html();
                    var safeExecutor = $('<div>').text(item.executor || '').html();

                    $('#data-collection-non-isbn').append(`
                        <tr class="animate__animated animate__fadeIn">
                            <input type="hidden" name="cni[]" value="1">
                            <input type="hidden" name="cni_is_analog[]" class="cni-is-analog-${randStr}" value="0">
                            <td width="5%" class="align-top">
                                <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                                    <i class="ph-trash"></i>
                                </button>
                            </td>
                            <td width="95%">
                                <div class="card border-0 bg-light mb-0">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">ID Catalog</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ph-database"></i></span>
                                                    <input type="text" class="form-control cni_catalog_id_${randStr}" name="cni_catalog_id[]" placeholder="Pilih Katalog" onchange="selectCollectionNonISBN(this)" value="${item.catalog_id || ''}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Pelaksana Serah</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ph-user"></i></span>
                                                    <input type="text" class="form-control" name="cni_executor[]" value="${safeExecutor}" placeholder="Nama pelaksana serah">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Judul</label>
                                                <input type="text" class="form-control" name="cni_title[]" placeholder="Masukkan judul" value="${safeTitle}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Kepengarangan</label>
                                                <input type="text" class="form-control" name="cni_author[]" placeholder="Masukkan nama pengarang" value="${safeAuthor}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Deskripsi Fisik</label>
                                                <input type="text" class="form-control" name="cni_physical_description[]" placeholder="Contoh: viii, 200 hlm. ; 21 cm" value="${item.physical_description || ''}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tahun Terbit</label>
                                                <input type="text" class="form-control" name="cni_year[]" placeholder="Contoh: 2025" value="${item.year || ''}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No Jilid</label>
                                                <input type="text" class="form-control" name="cni_binding[]" placeholder="Nomor jilid" value="${item.binding || ''}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jenis</label>
                                                <select class="form-select select2-basic cni-type-${randStr}" name="cni_type[]" onchange="typeNonISBN('${randStr}')">
                                                    <option value="">Pilih Jenis</option>
                                                    @foreach ($media as $m)
                                                        <option value="{{ $m->NAME }}" ${item.type == '{{ $m->NAME }}' ? 'selected' : ''} data-category="{{ $m->WORKSHEET_CATEGORY }}">{{ $m->NAME }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">QRCBN</label>
                                                <input type="text" class="form-control" name="cni_qrcbn[]" placeholder="Masukkan QRCBN" value="${item.qrcbn || ''}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ISBD</label>
                                                <input type="text" class="form-control" name="cni_isbd[]" placeholder="Masukkan ISBD" value="${item.isbd || ''}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Harga Jual</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control" name="cni_price[]" placeholder="0" value="${item.price || ''}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jumlah</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">PN</span>
                                                    <input type="text" class="form-control cni-pn-${randStr}" placeholder="0" value="2">
                                                    <span class="input-group-text">Prov</span>
                                                    <input type="text" class="form-control cni-prov-${randStr}" placeholder="0" value="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);

                    lookupCatalog(`.cni_catalog_id_${randStr}`, `.cni_catalog_id_${randStr}`, true);
                });

                $('input[name="cni_price[]"]').number(true);

                select2Basic();
            }

            if (Array.isArray(formData.periodicals_collections) && formData.periodicals_collections.length > 0) {
                $('#empty-periodicals-row').remove();

                formData.periodicals_collections.forEach(item => {
                    if (!item || typeof item !== 'object') {
                        return;
                    }

                    const randStr = item.randStr || randomString(10);
                    const cpIndex = item.cpIndex || (Date.now() + '_' + Math.random());
                    var safeTitle = $('<div>').text(item.manual_title || '').html();

                    $('#data-collection-periodicals').append(`
                        <tr class="periodical-row-${randStr} animate__animated animate__fadeIn" data-cp-index="${cpIndex}">
                            <input type="hidden" name="cp[${cpIndex}]" value="1">
                            <td width="5%" rowspan="2" class="align-top">
                                <button type="button" class="btn btn-danger" onclick="removeItemPeriodicals('${randStr}')">
                                    <i class="ph-trash"></i>
                                </button>
                            </td>
                            <td width="95%">
                                <div class="card border-0 bg-light mb-2">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0"><i class="ph-toggle-left me-1"></i> Katalog</h6>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary input-mode-switch ${item.mode === 'catalog' ? 'active' : ''}" onclick="switchInputMode('${randStr}', 'catalog', '${cpIndex}')">
                                                    <i class="ph-database me-1"></i>
                                                    Pilihan
                                                </button>
                                                <button type="button" class="btn btn-outline-primary input-mode-switch ${item.mode === 'manual' ? 'active' : ''}" onclick="switchInputMode('${randStr}', 'manual', '${cpIndex}')">
                                                    <i class="ph-keyboard me-1"></i>
                                                    Manual
                                                </button>
                                            </div>
                                        </div>
                                        <div class="periodical-catalog-section-${randStr} periodical-catalog-section ${item.mode === 'catalog' ? 'active' : ''}" style="${item.mode === 'catalog' ? '' : 'display:none;'}">
                                            <input type="hidden" class="cp_catalog_id_${randStr}" name="cp_catalog_id[${cpIndex}]" value="${item.catalog_id || ''}">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ph-database"></i></span>
                                                <input type="text" class="form-control cp_catalog_text_${randStr}" placeholder="Pilih Katalog Terbitan Berkala" value="${item.catalog_text || ''}" readonly>
                                            </div>
                                        </div>
                                        <div class="periodical-manual-section-${randStr} periodical-manual-section ${item.mode === 'manual' ? 'active' : ''}" style="${item.mode === 'manual' ? '' : 'display:none;'}">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Judul Terbitan</span>
                                                        <input type="text" class="form-control" name="cp_manual_title[${cpIndex}]" placeholder="Contoh: Majalah Perpustakaan Indonesia" value="${safeTitle}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="periodical-row-${randStr}" data-cp-index="${cpIndex}">
                            <td>
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0"><i class="ph-list-numbers me-1"></i> Daftar Edisi</h6>
                                            <button type="button" class="btn btn-success btn-sm" onclick="addCollectionPeriodicalsEdition('${cpIndex}')">
                                                <i class="ph-plus-circle me-1"></i>
                                                Tambah Edisi
                                            </button>
                                        </div>
                                        <div id="data-collection-periodicals-edition-${cpIndex}">
                                            ${Array.isArray(item.editions) && item.editions.length > 0 ? '' : '<div class="alert alert-info border-0 mb-0"><i class="ph-info me-1"></i>Belum ada edisi yang ditambahkan</div>'}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);

                    lookupCatalog(`.cp_catalog_text_${randStr}`, `.cp_catalog_id_${randStr}`, false, {
                        worksheet_id: [13, 142]
                    });

                    if (Array.isArray(item.editions) && item.editions.length > 0) {
                        item.editions.forEach(edition => {
                            if (!edition || typeof edition !== 'object') {
                                return;
                            }

                            const editionRandStr = randomString(10);
                            var safeEdition = $('<div>').text(edition.edition || '').html();

                            $(`#data-collection-periodicals-edition-${cpIndex}`).append(`
                                <div class="card border mb-2 edition-card-${editionRandStr}">
                                    <div class="card-body p-3">
                                        <input type="hidden" name="cpe[${cpIndex}][]" value="1">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label small">Edisi Serial</label>
                                                <input type="text" class="form-control form-control-sm" name="cpe_edition[${cpIndex}][]" placeholder="Contoh: Vol. 1 No. 1" value="${safeEdition}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small">TTES Awal</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="ph-calendar"></i></span>
                                                    <input type="text" class="form-control date-single" name="cpe_first_ttes[${cpIndex}][]" value="${edition.first_ttes || ''}" placeholder="Pilih Tanggal">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small">TTES Akhir</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="ph-calendar"></i></span>
                                                    <input type="text" class="form-control date-single" name="cpe_end_ttes[${cpIndex}][]" value="${edition.end_ttes || ''}" placeholder="Pilih Tanggal">
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItemEdition('${editionRandStr}')">
                                                    <i class="ph-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }
                });

                datePickerSingle('.date-single');
            }

            if (formData.type_delivery == 2) {
                setTimeout(() => {
                    if (formData.perpusnas_delivery) {
                        $(`input[name="perpusnas_delivery"][value="${formData.perpusnas_delivery}"]`).prop('checked', true);
                    }

                    if (formData.province_delivery) {
                        $(`input[name="province_delivery"][value="${formData.province_delivery}"]`).prop('checked', true);
                    }
                }, 2000);
            }

            setupAutoSave();
            onLoading('close', 'body');
            notification('success', 'Data berhasil dipulihkan');
        } catch (error) {
            onLoading('close', 'body');

            swalInit.fire({
                title: 'Gagal Memulihkan Data',
                text: 'Terjadi kesalahan saat memulihkan data. Silakan mulai dari awal.',
                icon: 'error'
            });

            clearAutoSave();
        }
    }

    function clearAutoSave() {
        localStorage.removeItem(STORAGE_KEY);

        $('#clear-autosave-btn').fadeOut();
    }

    function setupAutoSave() {
        $('#form-data').on('input change', 'input, textarea, select', function() {
            autoSaveForm();
        });

        $('#form-data').on('change', 'input[type="radio"]', function() {
            autoSaveForm();
        });

        if ($('#clear-autosave-btn').length === 0) {
            $('#remove-btn-autosave').append(`
                <button type="button" id="clear-autosave-btn" class="btn btn-danger btn-sm" onclick="confirmClearAutoSave()" style="display:none;">
                    <i class="ph-trash me-1"></i>
                    Hapus Data Tersimpan
                </button>
            `);
        }

        if (localStorage.getItem(STORAGE_KEY)) {
            $('#clear-autosave-btn').show();
        }
    }

    function confirmClearAutoSave() {
        swalInit.fire({
            title: 'Hapus Data Tersimpan?',
            text: 'Data yang tersimpan secara otomatis akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                clearAutoSave();

                notification('success', 'Data tersimpan berhasil dihapus');
            }
        });
    }

    function selectDeliveryType(type) {
        $('#type-delivery-' + type).prop('checked', true).trigger('change');
        $('.delivery-type-card').removeClass('border-primary');
        $('#type-delivery-' + type).closest('.delivery-type-card').addClass('border-primary');
    }

    function typeDelivery() {
        var type = $('input[name="type_delivery"]:checked').val();
        var deliveryMethod = '{{ config("system.delivery_method") }}';

        $('.delivery-type-card').removeClass('border-primary border-2');

        if (type) {
            $('#type-delivery-' + type).closest('.delivery-type-card').addClass('border-primary border-2');
        }

        if (deliveryMethod == 'expedition') {
            if (type == 1) {
                $('#expedition-card').slideUp();
                $('#expedition-card-perpusnas').hide();
                $('#expedition-card-province').hide();
                $('#expedition-card-body-perpusnas').html(getInfoAlert());
                $('#expedition-card-body-province').html(getInfoAlert());
                $('#weight').removeAttr('oninput');
                $('#destination').removeAttr('onchange');
            } else if (type == 2) {
                $('#expedition-card').slideDown();
                $('#expedition-card-body-perpusnas').html(getInfoAlert());
                $('#expedition-card-body-province').html(getInfoAlert());
                $('#weight').attr('oninput', 'loadExpeditionForm()');
                $('#destination').attr('onchange', 'loadExpeditionForm()');

                loadExpeditionForm();
            } else {
                $('#expedition-card').slideUp();
                $('#expedition-card-body-perpusnas').html(getInfoAlert());
                $('#expedition-card-body-province').html(getInfoAlert());
                $('#weight').removeAttr('oninput');
            }
        }
    }

    function getInfoAlert() {
        return `
            <div class="alert alert-info border-0">
                <i class="ph-info me-1"></i>
                Mohon isi berat paket dan tujuan pengiriman terlebih dahulu (minimal 1 Kg).
            </div>
        `;
    }

    function getWarningAlert(message = 'Tidak ada pengiriman yang tersedia.') {
        return `
            <div class="alert alert-warning border-0">
                <i class="ph-warning me-1"></i>
                ${message}
            </div>
        `;
    }

    function getLoadingSpinner() {
        return `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 text-muted">Memuat data pengiriman...</div>
            </div>
        `;
    }

    var expeditionLoadTimeout;

    function loadExpeditionForm() {
        clearTimeout(expeditionLoadTimeout);

        expeditionLoadTimeout = setTimeout(function() {
            var weight = $('#weight').val();
            var destination = $('#destination').val();

            if (!weight || isNaN(weight) || parseFloat(weight) <= 0) {
                $('#expedition-card-body-perpusnas').html(getInfoAlert());
                $('#expedition-card-body-province').html(getInfoAlert());

                return;
            }

            if (!destination || !['1', '2', '3'].includes(destination)) {
                $('#expedition-card-body-perpusnas').html(getInfoAlert());
                $('#expedition-card-body-province').html(getInfoAlert());

                return;
            }

            if (destination == 3) {
                $('#expedition-card-perpusnas').slideDown();
                $('#expedition-card-province').slideDown();
            } else if (destination == 1) {
                $('#expedition-card-perpusnas').slideDown();
                $('#expedition-card-province').slideUp();
            } else if (destination == 2) {
                $('#expedition-card-perpusnas').slideUp();
                $('#expedition-card-province').slideDown();
            }

            $.ajax({
                url: '{{ url("physical-handover/add-delivery-form/calculate-cost") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    weight: weight,
                    destination: destination,
                },
                beforeSend: function() {
                    $('#expedition-card-body-perpusnas').html(getLoadingSpinner());
                    $('#expedition-card-body-province').html(getLoadingSpinner());
                },
                success: function(response) {
                    if (!response || typeof response !== 'object') {
                        $('#expedition-card-body-perpusnas').html(getWarningAlert('Response tidak valid'));
                        $('#expedition-card-body-province').html(getWarningAlert('Response tidak valid'));

                        return;
                    }

                    if (response.province || response.perpusnas) {
                        if (response.perpusnas) {
                            $('#expedition-card-body-perpusnas').html(buildExpeditionOptions(response.perpusnas, 'perpusnas'));
                        } else {
                            $('#expedition-card-body-perpusnas').html(getWarningAlert());
                        }

                        if (response.province) {
                            $('#expedition-card-body-province').html(buildExpeditionOptions(response.province, 'province'));
                        } else {
                            $('#expedition-card-body-province').html(getWarningAlert());
                        }
                    } else {
                        $('#expedition-card-body-perpusnas').html(getWarningAlert());
                        $('#expedition-card-body-province').html(getWarningAlert());
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'Terjadi kesalahan saat memuat data.';

                    if (status === 'timeout') {
                        errorMessage = 'Request timeout. Mohon coba lagi.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Endpoint tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan di server.';
                    }

                    var errorHtml = `
                        <div class="alert alert-danger border-0">
                            <i class="ph-x-circle me-1"></i>
                            ${errorMessage}
                            <a href="javascript:void(0);" class="alert-link" onclick="loadExpeditionForm()">Coba lagi</a>
                        </div>
                    `;

                    $('#expedition-card-body-perpusnas').html(errorHtml);
                    $('#expedition-card-body-province').html(errorHtml);
                }
            });
        }, 500);
    }

    function buildExpeditionOptions(data, type) {
        if (!data || typeof data !== 'object') {
            return getWarningAlert('Data tidak valid');
        }

        let html = '<div class="row g-3">';

        html += '<div class="col-md-6">';
        html += '<div class="fw-bold border-bottom pb-2 mb-3"><i class="ph-truck me-1"></i> Reguler</div>';

        if (Array.isArray(data.calculate_reguler) && data.calculate_reguler.length > 0) {
            $.each(data.calculate_reguler, function(i, val) {
                var safeShippingName = $('<div>').text(val.shipping_name || '').html();
                var safeServiceName = $('<div>').text(val.service_name || '').html();
                var safeEtd = $('<div>').text(val.etd || '').html();
                var shippingCost = parseFloat(val.shipping_cost) || 0;
                var grandTotal = parseFloat(val.grandtotal) || 0;

                html += `
                    <div class="card border expedition-option mb-2">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="${type}_delivery" id="${type}_delivery_reguler_${i}" ${i == 0 ? 'checked' : ''} value="${safeShippingName};${safeServiceName};${grandTotal};${shippingCost}" onchange="autoSaveForm()">
                                <label class="form-check-label w-100" for="${type}_delivery_reguler_${i}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${safeShippingName}</h6>
                                            <div class="text-muted small">${safeServiceName}</div>
                                        </div>
                                        <span class="badge bg-primary">Rp ${$.number(shippingCost)}</span>
                                    </div>
                                    <div class="mt-2 small text-muted">
                                        <i class="ph-clock me-1"></i>
                                        Estimasi: ${safeEtd}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += getWarningAlert();
        }

        html += '</div>';
        html += '<div class="col-md-6">';
        html += '<div class="fw-bold border-bottom pb-2 mb-3"><i class="ph-package me-1"></i> Kargo</div>';

        if (Array.isArray(data.calculate_cargo) && data.calculate_cargo.length > 0) {
            $.each(data.calculate_cargo, function(i, val) {
                var safeShippingName = $('<div>').text(val.shipping_name || '').html();
                var safeServiceName = $('<div>').text(val.service_name || '').html();
                var safeEtd = $('<div>').text(val.etd || '').html();
                var shippingCost = parseFloat(val.shipping_cost) || 0;
                var grandTotal = parseFloat(val.grandtotal) || 0;

                html += `
                    <div class="card border expedition-option mb-2">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="${type}_delivery" id="${type}_delivery_cargo_${i}" ${i == 0 && (!Array.isArray(data.calculate_reguler) || data.calculate_reguler.length == 0) ? 'checked' : ''} value="${safeShippingName};${safeServiceName};${grandTotal};${shippingCost}" onchange="autoSaveForm()">
                                <label class="form-check-label w-100" for="${type}_delivery_cargo_${i}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${safeShippingName}</h6>
                                            <div class="text-muted small">${safeServiceName}</div>
                                        </div>
                                        <span class="badge bg-primary">Rp ${$.number(shippingCost)}</span>
                                    </div>
                                    <div class="mt-2 small text-muted">
                                        <i class="ph-clock me-1"></i>
                                        Estimasi: ${safeEtd}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += getWarningAlert();
        }

        html += '</div>';
        html += '</div>';

        return html;
    }

    function searchISBN() {
        var isbnValue = $('#search_isbn').val().trim();
        isbnValue = isbnValue.replace(/[^0-9X\-]/gi, '');

        if (!isbnValue) {
            swalInit.fire({
                title: 'Perhatian',
                text: 'Mohon masukkan nomor ISBN terlebih dahulu',
                icon: 'warning'
            });

            return;
        }

        var cleanISBN = isbnValue.replace(/-/g, '');

        if (cleanISBN.length !== 10 && cleanISBN.length !== 13) {
            swalInit.fire({
                title: 'Format ISBN Tidak Valid',
                text: 'ISBN harus terdiri dari 10 atau 13 digit',
                icon: 'warning'
            });

            return;
        }

        $.ajax({
            url: '{{ url("physical-handover/add-delivery-form/search-isbn") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                code: isbnValue,
                executor_id: $('#executor_id').val(),
                destination: $('#destination').val(),
            },
            beforeSend: function() {
                onLoading('show', 'body');
            },
            success: function(response) {
                onLoading('close', 'body');

                if (!response.data || typeof response.data !== 'object' || Object.keys(response.data).length === 0) {
                    swalInit.fire({
                        title: 'Data Tidak Ditemukan',
                        text: 'ISBN yang Anda cari tidak ditemukan dalam database',
                        icon: 'info'
                    });

                    return;
                }

                const executorId = "{{ session('id') }}";
                const data = response.data;

                if ((data.jenis_media ?? '').toLowerCase() !== 'cetak') {
                    swalInit.fire({
                        title: 'ISBN Digital',
                        text: 'ISBN bukan merupakan ISBN cetak, silahkan unggah karya digital pada web SAKEDAP',
                        icon: 'warning'
                    });

                    return;
                }

                if (data.penerbit_id != executorId) {
                    swalInit.fire({
                        title: 'Penerbit Tidak Sesuai',
                        html: `Mohon pilih pelaksana serah atas nama <strong>${data.nama_penerbit ?? 'pelaksana serah terkait'}</strong>`,
                        icon: 'warning'
                    });

                    return;
                }

                var isDuplicate = false;

                $('#data-collection-isbn input[name="ci_code[]"]').each(function() {
                    if ($(this).val() === (data.isbn ?? '')) {
                        isDuplicate = true;

                        return false;
                    }
                });

                readmoreJS();

                if (isDuplicate) {
                    swalInit.fire({
                        title: 'ISBN Sudah Ditambahkan',
                        text: 'ISBN ini sudah ada dalam daftar tabel',
                        icon: 'warning'
                    });

                    return;
                }

                /*if (response.existsData == 1) {
                    swalInit.fire({
                        title: 'Duplikasi Data',
                        text: 'ISBN ini sudah ada dalam sistem',
                        icon: 'warning'
                    });

                    return;
                }*/

                $('#empty-isbn-row').remove();

                var safeTitle = $('<div>').text(data.title ?? '-').html();
                var safeKepeng = $('<div>').text(data.kepeng ?? '-').html();
                var safePenerbit = $('<div>').text(data.nama_penerbit ?? '-').html();
                var safeSinopsis = $('<div>').text(data.sinopsis ?? '-').html();
                var safeISBN = $('<div>').text(data.isbn ?? '-').html();

                $('#data-collection-isbn').append(`
                    <tr class="animate__animated animate__fadeIn">
                        <input type="hidden" name="ci[]" value="1">
                        <input type="hidden" name="ci_code[]" value="${safeISBN}">
                        <td class="text-center align-middle">${response.fileCover ?? '<span class="text-muted">-</span>'}</td>
                        <td class="align-middle" nowrap>
                            <input type="date" class="form-control form-control-sm" name="ci_publish_date[]" value="${response.publishDate}">
                        </td>
                        <td class="align-middle text-wrap">${safeTitle}</td>
                        <td class="align-middle text-wrap">${safeKepeng}</td>
                        <td class="align-middle text-wrap">${safePenerbit}</td>
                        <td class="align-middle">${data.tahun_terbit ?? '-'}</td>
                        <td class="align-middle text-nowrap">${safeISBN}</td>
                        <td class="align-middle text-wrap">
                            <div class="readmore-block">
                                ${safeSinopsis}
                            </div>
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="ci_qty_perpusnas[]" value="${response.qtyPerpusnas}">
                            ${response.qtyPerpusnas}
                        </td>
                        <td class="align-middle">
                            <input type="hidden" name="ci_qty_province[]" value="${response.qtyProvince}">
                            ${response.qtyProvince}
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                <i class="ph-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);

                $('#search_isbn').val('').focus();

                readmoreJS();
                autoSaveForm();

                if (data.is_kdt_valid == 1) {
                    swalInit.fire({
                        title: 'Berhasil Ditambahkan',
                        html: `ISBN telah tervalidasi dengan KDT.<br>Koleksi otomatis dikaitkan dengan Katalog ID: <strong>${data.catalog_id}</strong>`,
                        icon: 'success',
                    });
                } else {
                    notification('success', 'ISBN berhasil ditambahkan');
                }
            },
            error: function(response) {
                onLoading('close', 'body');
                responseError(response);
            }
        });
    }

    function removeItem(param) {
        swalInit.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $(param).closest('tr').fadeOut(300, function() {
                    $(this).remove();

                    var tableId = $(param).closest('tbody').attr('id');

                    if ($('#' + tableId + ' tr').length === 0) {
                        var emptyMessage = '';

                        if (tableId === 'data-collection-isbn') {
                            emptyMessage = `
                                <tr id="empty-isbn-row">
                                    <td colspan="11" class="text-center text-muted py-4">
                                        <i class="ph-books ph-3x d-block mb-2 opacity-50"></i>
                                        Belum ada data ISBN
                                    </td>
                                </tr>
                            `;
                        } else if (tableId === 'data-collection-non-isbn') {
                            emptyMessage = `
                                <tr id="empty-non-isbn-row">
                                    <td class="text-center text-muted py-4">
                                        <i class="ph-article ph-3x d-block mb-2 opacity-50"></i>
                                        Belum ada data koleksi non ISBN
                                    </td>
                                </tr>
                            `;
                        } else if (tableId === 'data-collection-periodicals') {
                            emptyMessage = `
                                <tr id="empty-periodicals-row">
                                    <td class="text-center text-muted py-4">
                                        <i class="ph-newspaper-clipping ph-3x d-block mb-2 opacity-50"></i>
                                        Belum ada data terbitan berkala
                                    </td>
                                </tr>
                            `;
                        }

                        $('#' + tableId).html(emptyMessage);
                    }

                    autoSaveForm();
                });

                notification('success', 'Data berhasil dihapus');
            }
        });
    }

    function addCollectionNonISBN() {
        var total = parseInt($('#add-number-collection-non-isbn').val());

        if (isNaN(total) || total < 1) {
            swalInit.fire({
                title: 'Perhatian',
                text: 'Minimal menambahkan 1 baris',
                icon: 'warning'
            });

            $('#add-number-collection-non-isbn').val(1);

            return;
        }

        if (total > 10) {
            swalInit.fire({
                title: 'Perhatian',
                text: 'Maksimal menambahkan 10 baris sekaligus',
                icon: 'warning'
            });

            $('#add-number-collection-non-isbn').val(10);

            return;
        }

        $('#empty-non-isbn-row').remove();

        for (var i = 1; i <= total; i++) {
            var randStr = randomString(10);

            $('#data-collection-non-isbn').append(`
                <tr class="animate__animated animate__fadeIn">
                    <input type="hidden" name="cni[]" value="1">
                    <input type="hidden" name="cni_is_analog[]" class="cni-is-analog-${randStr}" value="0">
                    <td width="5%" class="align-top">
                        <button type="button" class="btn btn-danger" onclick="removeItem(this)">
                            <i class="ph-trash"></i>
                        </button>
                    </td>
                    <td width="95%">
                        <div class="card border-0 bg-light mb-0">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">ID Catalog</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ph-database"></i></span>
                                            <input type="text" class="form-control cni_catalog_id_${randStr}" name="cni_catalog_id[]" placeholder="Pilih Katalog" onchange="selectCollectionNonISBN(this)" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pelaksana Serah</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ph-user"></i></span>
                                            <input type="text" class="form-control" name="cni_executor[]" value="{{ session('name') }}" placeholder="Nama pelaksana serah">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="cni_title[]" placeholder="Masukkan judul">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kepengarangan</label>
                                        <input type="text" class="form-control" name="cni_author[]" placeholder="Masukkan nama pengarang">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Deskripsi Fisik</label>
                                        <input type="text" class="form-control" name="cni_physical_description[]" placeholder="Contoh: viii, 200 hlm. ; 21 cm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tahun Terbit</label>
                                        <input type="text" class="form-control" name="cni_year[]" placeholder="Contoh: 2025">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No Jilid</label>
                                        <input type="text" class="form-control" name="cni_binding[]" placeholder="Nomor jilid">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis</label>
                                        <select class="form-select select2-basic cni-type-${randStr}" name="cni_type[]" onchange="typeNonISBN('${randStr}')">
                                            <option value="">Pilih Jenis</option>
                                            @foreach ($media as $m)
                                                <option value="{{ $m->NAME }}" data-category="{{ $m->WORKSHEET_CATEGORY }}">{{ $m->NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">QRCBN</label>
                                        <input type="text" class="form-control" name="cni_qrcbn[]" placeholder="Masukkan QRCBN">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">ISBD</label>
                                        <input type="text" class="form-control" name="cni_isbd[]" placeholder="Masukkan ISBD">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Harga Jual</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" name="cni_price[]" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jumlah</label>
                                        <div class="input-group">
                                            <span class="input-group-text">PN</span>
                                            <input type="text" class="form-control cni-pn-${randStr}" placeholder="0" value="2">
                                            <span class="input-group-text">Prov</span>
                                            <input type="text" class="form-control cni-prov-${randStr}" placeholder="0" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `);

            lookupCatalog(`.cni_catalog_id_${randStr}`, `.cni_catalog_id_${randStr}`, true);
        }

        $('input[name="cni_price[]"]').number(true);

        select2Basic();
        autoSaveFom();
    }

    function selectCollectionNonISBN(param) {
        var catalogId = $(param).val();

        if (!catalogId || catalogId === '') {
            return;
        }

        $.ajax({
            url: '{{ url("physical-handover/add-delivery-form/select-catalog") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id: catalogId
            },
            beforeSend: function() {
                onLoading('show', '#data-collection-non-isbn');
            },
            success: function(response) {
                onLoading('close', '#data-collection-non-isbn');

                if (!response || typeof response !== 'object') {
                    swalInit.fire({
                        title: 'Data Tidak Valid',
                        text: 'Response dari server tidak valid',
                        icon: 'error'
                    });

                    return;
                }

                let selector = $(param).closest('tr');

                selector.find('input[name="cni_title[]"]').val(response?.TITLE || '');
                selector.find('input[name="cni_author[]"]').val(response?.AUTHOR || '');
                selector.find('input[name="cni_physical_description[]"]').val(response?.DESCRIPTION || '');
                selector.find('input[name="cni_year[]"]').val(response?.PUBLISHYEAR || '');
                selector.find('select[name="cni_type[]"]').val(response?.ALIAS_WORKSHEET || '').trigger('change');
                selector.find('input[name="cni_price[]"]').val(response?.PRICE || '');

                notification('success', 'Data katalog berhasil dimuat');
                autoSaveForm();
            },
            error: function(xhr, status, error) {
                onLoading('close', '#data-collection-non-isbn');

                var errorMessage = 'Terjadi kesalahan saat memuat data katalog';

                if (status === 'timeout') {
                    errorMessage = 'Request timeout. Mohon coba lagi.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Katalog tidak ditemukan.';
                }

                swalInit.fire({
                    title: 'Gagal Memuat Data',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    }

    function addPeriodicals() {
        var total = parseInt($('#add-number-collection-periodicals').val());

        if (total > 10) {
            swalInit.fire({
                title: 'Perhatian',
                text: 'Maksimal menambahkan 10 baris sekaligus',
                icon: 'warning'
            });

            return;
        }

        if (total < 1) {
            swalInit.fire({
                title: 'Perhatian',
                text: 'Minimal menambahkan 1 baris',
                icon: 'warning'
            });

            return;
        }

        $('#empty-periodicals-row').remove();

        for (var i = 1; i <= total; i++) {
            var randStr = randomString(10);
            var cpIndex = Date.now() + '_' + i;

            $('#data-collection-periodicals').append(`
                <tr class="periodical-row-${randStr} animate__animated animate__fadeIn" data-cp-index="${cpIndex}">
                    <input type="hidden" name="cp[${cpIndex}]" value="1">
                    <td width="5%" rowspan="2" class="align-top">
                        <button type="button" class="btn btn-danger" onclick="removeItemPeriodicals('${randStr}')">
                            <i class="ph-trash"></i>
                        </button>
                    </td>
                    <td width="95%">
                        <div class="card border-0 bg-light mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0"><i class="ph-toggle-left me-1"></i> Katalog</h6>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary input-mode-switch active" onclick="switchInputMode('${randStr}', 'catalog', '${cpIndex}')">
                                            <i class="ph-database me-1"></i>
                                            Pilihan
                                        </button>
                                        <button type="button" class="btn btn-outline-primary input-mode-switch" onclick="switchInputMode('${randStr}', 'manual', '${cpIndex}')">
                                            <i class="ph-keyboard me-1"></i>
                                            Manual
                                        </button>
                                    </div>
                                </div>
                                <div class="periodical-catalog-section-${randStr} periodical-catalog-section active">
                                    <input type="hidden" class="cp_catalog_id_${randStr}" name="cp_catalog_id[${cpIndex}]">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph-database"></i></span>
                                        <input type="text" class="form-control cp_catalog_text_${randStr}" placeholder="Pilih Katalog Terbitan Berkala" readonly>
                                    </div>
                                </div>
                                <div class="periodical-manual-section-${randStr} periodical-manual-section">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-text">Judul Terbitan</span>
                                                <input type="text" class="form-control" name="cp_manual_title[${cpIndex}]" placeholder="Contoh: Majalah Perpustakaan Indonesia">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="periodical-row-${randStr}" data-cp-index="${cpIndex}">
                    <td>
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0"><i class="ph-list-numbers me-1"></i> Daftar Edisi</h6>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addCollectionPeriodicalsEdition('${cpIndex}')">
                                        <i class="ph-plus-circle me-1"></i>
                                        Tambah Edisi
                                    </button>
                                </div>
                                <div id="data-collection-periodicals-edition-${cpIndex}">
                                    <div class="alert alert-info border-0 mb-0">
                                        <i class="ph-info me-1"></i>
                                        Belum ada edisi yang ditambahkan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `);

            lookupCatalog(`.cp_catalog_text_${randStr}`, `.cp_catalog_id_${randStr}`, false, {
                worksheet_id: [13, 142]
            });
        }

        autoSaveForm();
    }

    function switchInputMode(randStr, mode, cpIndex) {
        $(`.periodical-row-${randStr} .input-mode-switch`).removeClass('active');
        $(`.periodical-row-${randStr} .input-mode-switch`).each(function() {
            if ((mode === 'catalog' && $(this).text().trim().includes('Pilihan')) || (mode === 'manual' && $(this).text().trim().includes('Manual'))) {
                $(this).addClass('active');
            }
        });

        if (mode === 'catalog') {
            $(`.periodical-catalog-section-${randStr}`).addClass('active').slideDown();
            $(`.periodical-manual-section-${randStr}`).removeClass('active').slideUp();
            $(`.periodical-manual-section-${randStr} input, .periodical-manual-section-${randStr} textarea`).val('');
        } else {
            $(`.periodical-catalog-section-${randStr}`).removeClass('active').slideUp();
            $(`.periodical-manual-section-${randStr}`).addClass('active').slideDown();
            $(`.cp_catalog_id_${randStr}`).val('');
            $(`.cp_catalog_text_${randStr}`).val('');
        }

        autoSaveForm();
    }

    function removeItemPeriodicals(param) {
        swalInit.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data terbitan berkala ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('.periodical-row-' + param).fadeOut(300, function() {
                    $(this).remove();

                    if ($('#data-collection-periodicals tr').length === 0) {
                        $('#data-collection-periodicals').html(`
                            <tr id="empty-periodicals-row">
                                <td class="text-center text-muted py-4">
                                    <i class="ph-newspaper-clipping ph-3x d-block mb-2 opacity-50"></i>
                                    Belum ada data terbitan berkala
                                </td>
                            </tr>
                        `);
                    }

                    autoSaveForm();
                });

                notification('success', 'Data terbitan berkala berhasil dihapus');
            }
        });
    }

    function addCollectionPeriodicalsEdition(cpIndex) {
        var randStr = randomString(10);

        $('#data-collection-periodicals-edition-' + cpIndex + ' .alert-info').remove();

        $('#data-collection-periodicals-edition-' + cpIndex).append(`
            <div class="card border mb-2 animate__animated animate__fadeIn edition-card-${randStr}">
                <div class="card-body p-3">
                    <input type="hidden" name="cpe[${cpIndex}][]" value="1">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small">Edisi Serial</label>
                            <input type="text" class="form-control form-control-sm" name="cpe_edition[${cpIndex}][]" placeholder="Contoh: Vol. 1 No. 1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">TTES Awal</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ph-calendar"></i></span>
                                <input type="text" class="form-control date-single" name="cpe_first_ttes[${cpIndex}][]" placeholder="Pilih Tanggal">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">TTES Akhir</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ph-calendar"></i></span>
                                <input type="text" class="form-control date-single" name="cpe_end_ttes[${cpIndex}][]" placeholder="Pilih Tanggal">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItemEdition('${randStr}')">
                                <i class="ph-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        autoSaveForm();
        datePickerSingle('.date-single');
    }

    function removeItemEdition(param) {
        swalInit.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus edisi ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('.edition-card-' + param).fadeOut(300, function() {
                    $(this).remove();
                    autoSaveForm();
                });

                notification('success', 'Edisi berhasil dihapus');
            }
        });
    }

    function clearValidation() {
        $('#validation-element').removeClass('show').addClass('d-none');
        $('#validation-data').html('');
    }

    function showValidation(data) {
        $('#validation-element').removeClass('d-none').addClass('show');
        $('#validation-data').html('');

        $.each(data, function(index, value) {
            $('#validation-data').append('<li>' + value + '</li>');
        });

        $('.btn-to-top button').click();
    }

    function submitted() {
        swalInit.fire({
            title: 'Konfirmasi Pengiriman',
            text: 'Pastikan semua data telah diisi dengan benar. Lanjutkan pengiriman?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ph-paper-plane-right me-1"></i> Ya, Kirim',
            cancelButtonText: 'Periksa Kembali',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                processSubmit();
            }
        });
    }

    function processSubmit() {
        $.ajax({
            url: '{{ url("physical-handover/add-delivery-form/submitted") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-data').serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                onLoading('show', 'body');
                clearValidation();
            },
            success: function(response) {
                onLoading('close', 'body');

                if (response.code == 200) {
                    clearAutoSave();

                    swalInit.fire({
                        title: 'Pengiriman Berhasil!',
                        html: response.message,
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: '<i class="ph-check me-1"></i> Selesai',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onLoading('show', 'body');
                            location.href = '{{ url("physical-handover/delivery-monitoring") }}';
                        }
                    });
                } else if (response.code == 400) {
                    showValidation(response.error);
                    notification('error', 'Terdapat kesalahan pada form. Mohon periksa kembali.');
                } else {
                    swalInit.fire({
                        title: 'Perhatian',
                        text: response.message,
                        icon: 'info',
                        confirmButtonText: 'Mengerti',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                }
            },
            error: function(response) {
                onLoading('close', 'body');
                responseError(response);
            }
        });
    }
</script>
