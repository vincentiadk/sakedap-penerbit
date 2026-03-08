<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Tagihan ISBN</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary p-2 bg-opacity-10 text-primary">
                    Monitoring Tagihan ISBN
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm card-summary">
                <div class="card-body bg-primary bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                <i class="ph-barcode ph-2x"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="text-muted mb-1">Nomor ISBN Diberikan</div>
                            <h5 class="mb-0 fw-semibold" id="total-isbn">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm card-summary">
                <div class="card-body bg-secondary bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2">
                                <i class="ph-book-bookmark ph-2x"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="text-muted mb-1 fs-sm">Status Penerbitan</div>
                            <div class="d-flex gap-2">
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Terbit</div>
                                    <h6 class="mb-0 fw-semibold" id="done-publish">0</h6>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Belum</div>
                                    <h6 class="mb-0 fw-semibold" id="not-publish">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm card-summary">
                <div class="card-body bg-danger bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 text-danger rounded p-2">
                                <i class="ph-x ph-2x"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="text-muted mb-1 fs-sm">Belum Diserahkan</div>
                            <div class="d-flex gap-2">
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">PN</div>
                                    <h6 class="mb-0 fw-semibold" id="not-handover-perpusnas">0</h6>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Prov</div>
                                    <h6 class="mb-0 fw-semibold" id="not-handover-province">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm card-summary">
                <div class="card-body bg-success bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded p-2">
                                <i class="ph-checks ph-2x"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="text-muted mb-1 fs-sm">Sudah Diserahkan</div>
                            <div class="d-flex gap-2">
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">PN</div>
                                    <h6 class="mb-0 fw-semibold" id="done-handover-perpusnas">0</h6>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Prov</div>
                                    <h6 class="mb-0 fw-semibold" id="done-handover-province">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm card-summary">
                <div class="card-body bg-warning bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2">
                                <i class="ph-truck ph-2x"></i>
                            </div>
                        </div>
                        <div class="flex-fill ms-3">
                            <div class="text-muted mb-1 fs-sm">Dalam Pengiriman</div>
                            <div class="d-flex gap-2">
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">PN</div>
                                    <h6 class="mb-0 fw-semibold" id="in-delivery-perpusnas">0</h6>
                                </div>
                                <div class="vr"></div>
                                <div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Prov</div>
                                    <h6 class="mb-0 fw-semibold" id="in-delivery-province">0</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ph-funnel me-1 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Filter Pencarian</h6>
                </div>
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="ph-caret-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body">
                <form id="form-filter">
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-user-circle me-1"></i>
                            Pelaksana Serah
                        </label>
                        <select class="form-select select2-basic" name="executor_id" id="executor_id" data-placeholder="Pilih Pelaksana" data-width="100%">
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
                    <hr class="my-3">
                    <div class="row g-3 form-group">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-book me-1"></i>
                                Judul
                            </label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Cari berdasarkan judul">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-user me-1"></i>
                                Kepeng
                            </label>
                            <input type="text" class="form-control" name="author" id="author" placeholder="Cari berdasarkan kepeng">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-check-circle me-1"></i>
                                Penyerahan Perpusnas
                            </label>
                            <select class="form-select" name="is_perpusnas" id="is_perpusnas">
                                <option value="">Semua Status</option>
                                <option value="1">Sudah Diserahkan</option>
                                <option value="2">Belum Diserahkan</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-check-circle me-1"></i>
                                Penyerahan Provinsi
                            </label>
                            <select class="form-select" name="is_province" id="is_province">
                                <option value="">Semua Status</option>
                                <option value="1">Sudah Diserahkan</option>
                                <option value="2">Belum Diserahkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 form-group">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar me-1"></i>
                                Tahun Terbit
                            </label>
                            <select class="form-select select2-basic" name="year" id="year" data-placeholder="Semua Tahun">
                                @for($i = date('Y'); $i >= 1998; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-barcode me-1"></i>
                                Nomor ISBN
                            </label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Cari berdasarkan ISBN">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-tag me-1"></i>
                                Subjek
                            </label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Cari berdasarkan subjek">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-file-text me-1"></i>
                                Sinopsis Class
                            </label>
                            <input type="text" class="form-control" name="sinopsis_class" id="sinopsis_class" placeholder="Cari sinopsis class">
                        </div>
                    </div>
                    <div class="row g-3 form-group">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-hash me-1"></i>
                                Nomor Panggil
                            </label>
                            <input type="text" class="form-control" name="call_number" id="call_number" placeholder="Cari nomor panggil">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-books me-1"></i>
                                Jenis Koleksi
                            </label>
                            <select class="form-select" name="media" id="media">
                                <option value="">Semua Jenis</option>
                                <option value="cetak">Cetak</option>
                                <option value="digital pdf">Digital PDF</option>
                                <option value="digital epub">Digital EPUB</option>
                                <option value="audio book">Audio Book</option>
                                <option value="audio visual book">Audio Visual Book</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar-check me-1"></i>
                                Tgl Terima Perpusnas
                            </label>
                            <input type="text" class="form-control date-range-picker" name="received_date_kckr" id="received_date_kckr" placeholder="Semua Tanggal">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar-check me-1"></i>
                                Tgl Terima Provinsi
                            </label>
                            <input type="text" class="form-control date-range-picker" name="received_date_province" id="received_date_province" placeholder="Semua Tanggal">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url('bill-isbn') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
                        <i class="ph-arrow-counter-clockwise me-1"></i>
                        Reset Filter
                    </a>
                    <button type="button" class="btn btn-primary" onclick="loadData()">
                        <i class="ph-magnifying-glass me-1"></i>
                        Cari Data
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ph-barcode me-1 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Tagihan ISBN</h6>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary" id="total-records">
                    <i class="ph-list-bullets me-1"></i>
                    <span id="record-count">0</span> Data
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered display nowrap w-100" id="datatable-serverside">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-nowrap" style="width: 60px">
                                <i class="ph-hash"></i>
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-flag me-1"></i>
                                Status
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-image me-1"></i>
                                Cover
                            </th>
                            <th class="text-nowrap" style="min-width: 180px">
                                <i class="ph-user-circle me-1"></i>
                                Pelaksana Serah
                            </th>
                            <th class="text-nowrap" style="min-width: 250px">
                                <i class="ph-book me-1"></i>
                                Judul
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-user me-1"></i>
                                Kepeng
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-calendar me-1"></i>
                                Tahun
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                ISBN
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-books me-1"></i>
                                Jenis Koleksi
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-buildings me-1"></i>
                                Pustaka
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 150px">
                                <i class="ph-calendar-check me-1"></i>
                                Tgl Terima Perpusnas
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 150px">
                                <i class="ph-calendar-check me-1"></i>
                                Tgl Terima Provinsi
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-file-text me-1"></i>
                                Sinopsis
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 130px">
                                <i class="ph-calendar me-1"></i>
                                Tgl Terima
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 130px">
                                <i class="ph-calendar-plus me-1"></i>
                                Tgl Dibuat
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 130px">
                                <i class="ph-calendar-blank me-1"></i>
                                Tgl Update
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        datePickerBasic('.date-range-picker');
        loadData();
        loadSummary();
    });

    function loadSummary() {
        $.ajax({
            url: '{{ url("bill-isbn/load-summary") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                executor_id: $('#executor_id').val()
            },
            beforeSend: function() {
                onLoading('show', '.card-summary');
            },
            success: function(response) {
                onLoading('close', '.card-summary');

                $('#total-isbn').text($.number(response.total_isbn));
                $('#done-publish').text($.number(response.total_konfirmasi_terbit));
                $('#not-publish').text($.number(response.total_belum_konfirmasi_terbit));
                $('#not-handover-perpusnas').text($.number(response.total_belum_serah));
                $('#not-handover-province').text($.number(response.total_belum_serah_prov));
                $('#done-handover-perpusnas').text($.number(response.total_sudah_serah));
                $('#done-handover-province').text($.number(response.total_sudah_serah_prov));
                $('#in-delivery-perpusnas').text($.number(response.total_dalam_pengiriman));
                $('#in-delivery-province').text($.number(response.total_dalam_pengiriman_prov));
            },
            error: function(response) {
                onLoading('close', '.card-summary');
                responseError(response);
            }
        });
    }

    function loadData() {
        window.gDataTable = $('#datatable-serverside').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollX: true,
            destroy: true,
            ajax: {
                url: '{{ url("bill-isbn/datatable") }}',
                dataType: 'JSON',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    $('#form-filter').serializeArray().forEach(function(item) {
                        d[item.name] = item.value;
                    });

                    return d;
                },
                beforeSend: function() {
                    onLoading('show', '#datatable-serverside_wrapper');
                },
                error: function(response) {
                    onLoading('close', '#datatable-serverside_wrapper');
                    responseError(response);
                }
            },
            columns: [
                { orderable: false, className: 'align-middle text-center fw-semibold' },
                { orderable: false, className: 'align-middle text-center', export: false },
                { orderable: false, className: 'align-middle text-center', export: false },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-center', export: false },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center', export: false },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('div.dataTables_filter input');

                searchInput.off().unbind();
                searchInput.on('keyup', debounce(function () {
                    table.search(this.value).draw();
                }, 500));

                updateRecordCount(json.recordsFiltered);
            },
            drawCallback: function(settings) {
                var api = this.api();

                updateRecordCount(api.page.info().recordsFiltered);
            }
        }).on('draw.dt', function() {
            onLoading('close', '#datatable-serverside_wrapper');
        });

        window.gDataTable.columns.adjust().draw();
    }

    function updateRecordCount(count) {
        $('#record-count').text(count || 0);
    }
</script>
