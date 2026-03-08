<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - <span class="fw-normal">Koleksi Ditinjau</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-warning p-2 bg-opacity-10 text-warning">
                    Kelola koleksi dalam peninjauan
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
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
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="ph-text-aa me-1"></i>
                            Judul Koleksi
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-magnifying-glass"></i>
                            </span>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Cari berdasarkan judul...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="ph-calendar me-1"></i>
                            Tanggal
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-calendar-blank"></i>
                            </span>
                            <input type="text" class="form-control" name="date" id="date" placeholder="Pilih tanggal">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="ph-folders me-1"></i>
                            Jenis Koleksi
                        </label>
                        <select class="form-select select2-basic" name="media_id" id="media_id" data-placeholder="Semua Jenis" data-width="100%">
                            <option value=""></option>
                            @foreach($media as $m)
                                <option value="{{ $m->ID }}">{{ $m->NAME }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="ph-barcode me-1"></i>
                            Identifier
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-hash"></i>
                            </span>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Kode identifier...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="ph-calendar-blank me-1"></i>
                            Tahun
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-clock-counter-clockwise"></i>
                            </span>
                            <input type="number" class="form-control" name="year" id="year" placeholder="YYYY" min="1900" max="2100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url('digital-storage-handover/review') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
                        <i class="ph-arrow-counter-clockwise me-1"></i>
                        Reset
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
                    <i class="ph-eye me-1 text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Koleksi Ditinjau</h6>
                </div>
                <span class="badge bg-warning bg-opacity-10 text-warning" id="total-records">
                    <i class="ph-clock me-1"></i>
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
                            <th class="text-center text-nowrap" style="width: 100px">
                                <i class="ph-gear"></i>
                                Aksi
                            </th>
                            <th class="text-nowrap" style="min-width: 180px">
                                <i class="ph-user-circle me-1"></i>
                                Pelaksana Serah
                            </th>
                            <th class="text-nowrap" style="min-width: 250px">
                                <i class="ph-text-aa me-1"></i>
                                Judul
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-folders me-1"></i>
                                Jenis Koleksi
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                Identifier
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-calendar-check me-1"></i>
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
        datePickerBasic('#date');
        loadData();
    });

    function loadData() {
        window.gDataTable = $('#datatable-serverside').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollX: true,
            destroy: true,
            order: [[0, 'desc']],
            ajax: {
                url: '{{ url("digital-storage-handover/review/datatable") }}',
                dataType: 'JSON',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    title: $('#title').val(),
                    code: $('#code').val(),
                    qrcbn: $('#qrcbn').val(),
                    year: $('#year').val(),
                    media_id: $('#media_id').val(),
                    date: $('#date').val(),
                    executor_id: $('#executor_id').val(),
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
                { orderable: true, className: 'align-middle text-center fw-semibold' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-nowrap' },
                { orderable: true, className: 'align-middle text-center text-nowrap' },
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
