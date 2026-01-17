<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - <span class="fw-normal">Unggah Tunggal Non ISBN</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                    Form Unggah Koleksi
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-none" id="validation-element">
        <div class="d-flex align-items-start">
            <i class="ph-warning-circle ph-2x me-3"></i>
            <div class="flex-fill">
                <h6 class="alert-heading fw-semibold mb-2">Terdapat kesalahan pada form:</h6>
                <ul class="mb-0" id="validation-data"></ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="clearValidation()"></button>
        </div>
    </div>
    <form id="form-data">
        <input type="hidden" name="upload_id_cover" id="upload_id_cover" value="{{ $uploadIDCover }}">
        <input type="hidden" name="upload_id_content" id="upload_id_content" value="{{ $uploadIDCover }}">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-user-circle me-1 text-primary"></i>
                    Pelaksana Serah
                    <span class="text-danger fw-bold">*</span>
                </h5>
            </div>
            <div class="card-body">
                <select class="form-select select2-basic" name="executor_id" id="executor_id" data-placeholder="Pilih Pelaksana">
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
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-folders me-1 text-success"></i>
                    Jenis Bahan
                    <span class="text-danger fw-bold">*</span>
                </h5>
            </div>
            <div class="card-body">
                <select class="form-select select2-basic" name="worksheet_id" id="worksheet_id" onchange="chooseWorksheet()" data-placeholder="Pilih Jenis Bahan">
                    <option value=""></option>
                    @foreach($worksheet as $w)
                        <option value="{{ $w->ID }}">{{ $w->ALIAS }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card shadow-sm d-none" id="form-parent">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-tree-structure me-1 text-warning"></i>
                    Parent (Induk Koleksi)
                </h5>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ph-link"></i>
                    </span>
                    <input type="hidden" name="catalog_id" id="catalog_id">
                    <input type="text" class="form-control" name="catalog_title" id="catalog_title" placeholder="Tidak Ada" onchange="catalogParent()" readonly>
                    <button type="button" class="btn btn-danger d-none" onclick="onLoading('show', 'body'); location.reload(true);" id="btn-cancel-parent">
                        <i class="ph-x me-1"></i>
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-note-pencil me-1 text-info"></i>
                    Meta Data Koleksi
                </h5>
            </div>
            <div class="card-body">
                <div class="d-none" id="column-edition">
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 fw-semibold">
                            <i class="ph-newspaper me-1"></i>
                            Edisi
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="edition" id="edition" placeholder="Masukkan edisi koleksi">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 fw-semibold">
                            <i class="ph-calendar-check me-1"></i>
                            Tanggal Terbit Edisi
                            <span class="text-danger fw-bold">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-calendar-blank"></i>
                                </span>
                                <input type="text" class="form-control date-picker-single" name="edition_date" id="edition_date" placeholder="Pilih Tanggal" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-folders me-1"></i>
                        Jenis Koleksi
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <select class="form-select select2-basic" name="collection_media_id" id="collection_media_id" onchange="getCategory()" data-placeholder="Pilih Jenis Koleksi"></select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-text-aa me-1"></i>
                        Judul
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <textarea name="title" class="form-control" id="title" rows="5" placeholder="Masukkan judul koleksi"></textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-barcode me-1"></i>
                        Identifier
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select class="form-select w-auto flex-grow-0" name="code_type" id="code_type" onchange="codeType()" style="max-width: 150px;">
                                <option value="">Tidak Ada</option>
                                <option value="2">ISMN</option>
                                <option value="3">ISRC</option>
                                <option value="4">ISSN</option>
                                <option value="5">ISAN</option>
                            </select>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Masukkan kode identifier">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-qr-code me-1"></i>
                        QRCBN
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <div class="form-check form-check-inline mb-0">
                                    <input type="checkbox" class="form-check-input" onchange="$(this).is(':checked') ? $('#qrcbn').attr('disabled', true).val('') : $('#qrcbn').attr('disabled', false)" checked>
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                            </span>
                            <input type="text" class="form-control" name="qrcbn" id="qrcbn" placeholder="Masukkan kode QRCBN" disabled>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-books me-1"></i>
                        Seri
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <div class="form-check form-check-inline mb-0">
                                    <input type="checkbox" class="form-check-input" id="series_checkbox" onchange="$(this).is(':checked') ? $('#series').attr('disabled', true).val('') : $('#series').attr('disabled', false)" checked>
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                            </span>
                            <input type="text" class="form-control" name="series" id="series" placeholder="Masukkan seri koleksi" disabled>
                        </div>
                    </div>
                </div>
                <div class="row form-group" id="form-input-serial">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-clock-clockwise me-1"></i>
                        Kala Terbit
                    </label>
                    <div class="col-md-9">
                        <select class="form-select select2-basic" name="serial" id="serial" data-placeholder="Tidak Ada">
                            <option value=""></option>
                            <option value="1">Harian</option>
                            <option value="2">Mingguan</option>
                            <option value="3">Bulanan</option>
                            <option value="4">3 Bulan Sekali</option>
                            <option value="5">4 Bulan Sekali</option>
                            <option value="6">6 Bulan Sekali</option>
                            <option value="7">Tahunan</option>
                            <option value="8">2 Tahun Sekali</option>
                            <option value="9">3 Tahun Sekali</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-calendar me-1"></i>
                        Waktu Publikasi
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-calendar-blank"></i>
                            </span>
                            <input type="text" class="form-control date-picker-single" name="publish_time" id="publish_time" placeholder="Pilih Tanggal" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-eye me-1"></i>
                        Preview
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="preview" id="preview" placeholder="Contoh: 1-5 / 00:01-00:20">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-currency-circle-dollar me-1"></i>
                        Harga Jual
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select class="form-select w-auto flex-grow-0" name="currency" id="currency" data-width="30%">
                                <option value="IDR" selected>IDR</option>
                            </select>
                            <input type="number" class="form-control" name="price" id="price" placeholder="Masukkan harga jual" min="0">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-book me-1"></i>
                        Jilid
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="binding" id="binding" placeholder="Masukkan informasi jilid">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-book-open me-1"></i>
                        Keterangan Fisik
                    </label>
                    <div class="col-md-9">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Total Halaman / Durasi</span>
                                    <input type="number" class="form-control" name="physical_description[paging]" id="physical_description[paging]" placeholder="Jumlah">
                                    <select class="form-select flex-grow-0 w-auto" name="physical_description[paging_flag]" id="physical_description[paging_flag]" style="max-width: 120px;">
                                        <option value="Halaman" selected>Halaman</option>
                                        <option value="Menit">Menit</option>
                                        <option value="Jam">Jam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Ilustrasi</span>
                                    <input type="text" class="form-control" name="physical_description[ill]" list="suggestion-physical-description-ill" id="physical_description[ill]" placeholder="Pilih atau ketik" autocomplete="off">
                                    <datalist id="suggestion-physical-description-ill">
                                        <option value="Tidak Ada">Tidak Ada</option>
                                        <option value="Ada (Berwarna)">Ada (Berwarna)</option>
                                        <option value="Ada (Tidak Berwarna)">Ada (Tidak Berwarna)</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Ukuran / Dimensi</span>
                                    <input type="text" class="form-control" name="physical_description[sizes]" id="physical_description[sizes]" placeholder="Contoh: 21 x 14 cm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-article me-1"></i>
                        Sinopsis
                        <span class="text-danger fw-bold">*</span>
                    </label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" id="description" rows="5" placeholder="Masukkan sinopsis atau deskripsi koleksi"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-lock-key me-1 text-warning"></i>
                    Pengaturan Akses
                </h5>
            </div>
            <div class="card-body">
                <div class="form-check form-group">
                    <input type="radio" class="form-check-input" name="access" id="access-1" value="1">
                    <label class="form-check-label" for="access-1">
                        <strong>Akses Full</strong>
                        <div class="small">Akses full file berwatermak secara online</div>
                    </label>
                </div>
                <div class="form-check form-group">
                    <input type="radio" class="form-check-input" name="access" id="access-2" value="2" checked>
                    <label class="form-check-label" for="access-2">
                        <strong>Akses Preview + LAN</strong>
                        <div class="small">Akses hanya preview file secara online, namun tetap dapat di dayagunakan di lingkungan perpustakaan nasional RI dengan jaringan internet LAN</div>
                    </label>
                </div>
                <div class="form-check form-group">
                    <input type="radio" class="form-check-input" name="access" id="access-3" value="3">
                    <label class="form-check-label" for="access-3">
                        <strong>Akses Preview + Embargo 5 Tahun</strong>
                        <div class="small">Akses hanya file preview secara online, dan tidak didayagunakan di lingkungan Perpustakaan Nasional RI selama 5 tahun sejak diserahkan. Setelah 5 tahun, akan didayagunakan oleh Perpustakaan Nasional RI di jaringan internet LAN</div>
                    </label>
                </div>
                <div class="form-check form-group">
                    <input type="radio" class="form-check-input" name="access" id="access-4" value="4">
                    <label class="form-check-label" for="access-4">
                        <strong>Akses Preview Saja</strong>
                        <div class="small">Akses hanya file preview secara online selamanya dan tidak didayagunakan dimana pun</div>
                    </label>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-tag me-1 text-danger"></i>
                    Kategori
                </h5>
            </div>
            <div class="card-body">
                <div id="category-content">
                    <div class="alert alert-info border-0 mb-0">
                        <i class="ph-info me-1"></i>
                        Pilih jenis koleksi terlebih dahulu
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-users me-1 text-primary"></i>
                    Kontributor
                    <span class="text-danger fw-bold">*</span>
                </h5>
            </div>
            <div class="card-body">
                <select class="form-select" name="author[]" id="author" data-placeholder="Ketik nama kontributor (Penulis, Editor, dll)" multiple></select>
                <div class="form-text">
                    <i class="ph-info me-1"></i>
                    Contoh format: Penulis, Hermawan, S.Kom.
                </div>
            </div>
        </div>
        <div class="card shadow-sm" id="card-edition">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-newspaper me-1 text-success"></i>
                        Edisi Serial
                    </h5>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="has_edition" id="has_edition" onchange="$(this).is(':checked') ? $('#content-edition-copy').slideDown(300) : $('#content-edition-copy').slideUp(300)">
                        <label class="form-check-label" for="has_edition">Centang jika ada edisi</label>
                    </div>
                </div>
            </div>
            <div id="content-edition-copy" style="display:none;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">Edisi / Volume</th>
                                    <th style="width: 20%;">Tgl Terbit</th>
                                    <th style="width: 20%;">Cover</th>
                                    <th style="width: 20%;">Konten</th>
                                    <th style="width: 15%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="data-edition"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-success" onclick="addEdition()">
                            <i class="ph-plus-circle me-1"></i>
                            Tambah Edisi
                        </button>
                        <input type="number" class="form-control" id="add-number-edition" min="1" value="1" placeholder="Jumlah baris" style="max-width: 120px;">
                        <span class="text-muted">Baris</span>
                    </div>
                </div>
            </div>
        </div>
        @if(!$uploadIDCover && !$uploadIDContent)
            <div class="row g-3">
                <div class="col-md-6" id="section-file-cover">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0 fw-semibold">
                                <i class="ph-image me-1 text-primary"></i>
                                File Cover
                            </h5>
                        </div>
                        <div class="card-body">
                            <input type="file" name="file_cover" id="file_cover">
                            <div class="form-text mt-2">
                                <i class="ph-info me-1"></i>
                                Format: JPG, JPEG, PNG | Maksimal: 2MB
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="section-file-content">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0 fw-semibold">
                                <i class="ph-file-pdf me-1 text-danger"></i>
                                File Konten
                            </h5>
                        </div>
                        <div class="card-body">
                            <input type="file" name="file_content" id="file_content">
                            <div class="form-text mt-2">
                                <i class="ph-info me-1"></i>
                                Format: PDF, EPUB, MP3, MP4, WAV | Maksimal: 200MB
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </form>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-0">
                        <i class="ph-warning me-1"></i>
                        Pastikan semua data sudah benar sebelum menyimpan
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url('digital-storage-handover/single-upload-non-isbn') }}" class="btn btn-light" onclick="onLoading('show', 'body')">
                        <i class="ph-arrow-counter-clockwise me-1"></i>
                        Reset
                    </a>
                    <button type="button" class="btn btn-primary" onclick="submitted()">
                        <i class="ph-check-circle me-1"></i>
                        Submit Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        datePickerSingle('.date-picker-single');

        select2Serverside('#city_id', 'location', {
            for: 'city',
            province_id: '{{ session("province_id") }}',
        }, {
            minimumInputLength: 0
        });

        select2Serverside('#currency', 'currency');

        dragAndDropFile('#file_cover', {
            maxFileCount: 1,
            autoReplace: true,
            allowedFileExtensions: ['jpg', 'jpeg', 'png'],
            maxFileSize: 2048,
        });

        dragAndDropFile('#file_content', {
            maxFileCount: 1,
            autoReplace: true,
            allowedFileExtensions: ['pdf', 'epub', 'mp3', 'mp4', 'wav'],
            maxFileSize: 204800,
        });

        $('#author').select2({
            multiple: true,
            tags: true,
            tokenSeparators: [';']
        });

        lookupCatalogParent('#catalog_title', '#catalog_id');
        codeType();
        chooseWorksheet();
    });

    function getMedia() {
        const media = @json($media ?? []);
        const worksheetId = $('#worksheet_id').val();

        $('#collection_media_id').html('<option value=""></option>');

        const mediaContent = media.filter(val => val.WORKSHEET_ID == worksheetId).map(val => `
            <option value="${val.ID}">${val.NAME}</option>
        `).join('');

        $('#collection_media_id').append(mediaContent);

        getCategory();
    }

    function getCategory() {
        const category = @json($category ?? []);
        const mediaId = $('#collection_media_id').val();

        if (!mediaId) {
            $('#category-content').html(`
                <div class="alert alert-info border-0 mb-0">
                    <i class="ph-info me-1"></i>
                    Pilih jenis koleksi terlebih dahulu
                </div>
            `);

            return;
        }

        const filteredCategories = category.filter(val => val.TYPE == mediaId);

        if (filteredCategories.length === 0) {
            $('#category-content').html(`
                <div class="alert alert-warning border-0 mb-0">
                    <i class="ph-warning me-1"></i>
                    Tidak ada kategori tersedia untuk jenis koleksi ini
                </div>
            `);

            return;
        }

        const categoryContent = filteredCategories.map((val, index) => `
            <div class="form-check ${index !== filteredCategories.length - 1 ? 'mb-2' : ''}">
                <input type="checkbox" class="form-check-input" name="category[]" id="category-${val.ID}" value="${val.ID}">
                <label class="form-check-label" for="category-${val.ID}">${val.NAME}</label>
            </div>
        `).join('');

        $('#category-content').html(categoryContent);
    }

    function chooseWorksheet() {
        var worksheetId = $('#worksheet_id').val();

        if(worksheetId == 142) {
            $('#form-parent').removeClass('d-none');
            $('#column-edition').removeClass('d-none');
            $('#card-edition').removeClass('d-none');
            $('#section-file-cover').removeClass('d-none');
            $('#section-file-content').addClass('d-none');
            $('#form-input-serial').removeClass('d-none');
        } else {
            $('#form-parent').addClass('d-none');
            $('#btn-cancel-parent').addClass('d-none');
            $('#card-edition').addClass('d-none');
            $('#section-file-cover').removeClass('d-none');
            $('#section-file-content').removeClass('d-none');
            $('#column-edition').addClass('d-none');
            $('#form-input-serial').addClass('d-none');
        }

        $('#card-edition #data-edition').html('');

        getMedia();
    }

    function catalogParent() {
        $('#btn-cancel-parent').removeClass('d-none');
        $('#section-file-cover').addClass('d-none');

        if($('#catalog_id').val()) {
            $.ajax({
                url: '{{ url("digital-storage-handover/single-upload-non-isbn/catalog-parent") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    id: $('#catalog_id').val()
                },
                beforeSend: function() {
                    onLoading('show', 'body');
                },
                success: function(response) {
                    onLoading('close', 'body');

                    $('#executor_id').html(`
                        <option value="${ response.PENERBIT_ID }" selected>
                            ${ response.PENERBIT_ID } | ${ response.NAME_PENERBIT }
                        </option>
                    `);

                    $('#worksheet_id').val(response.WORKSHEET_ID).change();
                    $('#media_id').val(response.COLLECTIONMEDIA_ID).change();
                    $('#title').val(response.TITLE);
                    $('#code_type').val(response.CODE_TYPE_E_COLLECTION).change();
                    $('#code').val(response.ISBN);
                    $('#series_checkbox').prop('checked', response.SERIES ? false : true).change();
                    $('#series').val(response.SERIES);
                    $('#serial').val(response.SERIAL_E_COLLECTION).change();
                    $('#publish_time').val(response.PUBLISHYEAR + '-' + response.PUBLISH_MONTH);
                    $('#preview').val(response.PREVIEW);
                    $('#currency').html('<option value="' + response.CURRENCY_E_COLLECTION + '" selected>' + response.CURRENCY_E_COLLECTION + '</option>');
                    $('#price').val(response.PRICE_E_COLLECTION);
                    $('#binding').val(response.JILID_E_COLLECTION);
                    $('#content_type').val(response.JENIS_ISI).change();
                    $('#container_type').val(response.JENIS_WADAH).change();
                    $('#media_type').val(response.JENIS_MEDIA).change();
                    $('#big_class_id').val(response.KELAS_BESAR_ID).change();
                    $('input[name="physical_description[paging]"]').val(response.PAGING);
                    $('input[name="physical_description[ill]"]').val(response.ILL);
                    $('input[name="physical_description[sizes]"]').val(response.SIZES);
                    $('#description').val(response.DESCRIPTION_E_COLLECTION).change();

                    if(response.NAMAKAB && response.NAMAPROPINSI) {
                        $('#city_id').html(`
                            <option value="${ response.CITY_ID }" selected>
                                ${ response.NAMAPROPINSI } -> ${ response.NAMAKAB }
                            </option>
                        `);
                    }
                },
                error: function(response) {
                    onLoading('close', 'body');
                    responseError(response);
                }
            });
        }
    }

    function addEdition() {
        var total = $('#add-number-edition').val();

        if (!total || total < 1) {
            swalInit.fire({
                title: 'Peringatan',
                text: 'Masukkan jumlah baris yang valid',
                icon: 'warning',
                confirmButtonText: 'OK'
            });

            return;
        }

        for(var i = 1; i <= total; i++) {
            $('#data-edition').append(`
                <tr>
                    <input type="hidden" name="cc_edition[]" value="1">
                    <td>
                        <input type="text" class="form-control" name="cc_edition_title[]" placeholder="Masukkan edisi/volume">
                    </td>
                    <td>
                        <input type="text" class="form-control date-picker-edition" name="cc_edition_date[]" placeholder="Pilih Tanggal" readonly>
                    </td>
                    <td>
                        <input type="file" class="form-control" name="cc_edition_cover[]" accept=".jpg,.jpeg,.png">
                    </td>
                    <td>
                        <input type="file" class="form-control" name="cc_edition_content[]" accept=".pdf,.epub,.mp3,.mp4,.wav">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)" data-bs-toggle="tooltip" title="Hapus baris">
                            <i class="ph-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        }

        datePickerSingle('.date-picker-edition');
        initTooltip();
    }

    function removeRow(param) {
        swalInit.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus baris ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $(param).closest('tr').remove();
            }
        });
    }

    function codeType() {
        var codeType = $('#code_type').val();

        $('#code').val('');
        $('#code').attr('disabled', false);

        if(codeType == '') {
            $('#code').attr('disabled', true);
            $('#code').attr('placeholder', 'Pilih jenis identifier terlebih dahulu');
        } else {
            $('#code').attr('placeholder', 'Masukkan kode identifier');
        }
    }

    function clearValidation() {
        $('#validation-element').addClass('d-none');
        $('#validation-data').html('');
    }

    function showValidation(data) {
        $('#validation-element').removeClass('d-none');
        $('#validation-data').html('');

        $.each(data, function(index, value) {
            $('#validation-data').append('<li class="mb-1">' + value + '</li>');
        });
    }

    function submitted() {
        $.ajax({
            url: '{{ url("digital-storage-handover/single-upload-non-isbn/submitted") }}',
            type: 'POST',
            dataType: 'JSON',
            data: new FormData($('#form-data')[0]),
            contentType: false,
            processData: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                onLoading('show', 'body');
                clearValidation();
            },
            success: function(response) {
                onLoading('close', 'body');

                if(response.code == 200) {
                    swalInit.fire({
                        title: 'Berhasil',
                        text: response.message,
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onLoading('show', 'body');

                            location.href = '{{ url("digital-storage-handover/single-upload-non-isbn") }}';
                        }
                    });
                } else if(response.code == 400) {
                    onLoading('close', 'body');
                    $('.btn-to-top button').click();
                    showValidation(response.error);
                } else {
                    swalInit.fire({
                        title: 'Oops...',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
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
