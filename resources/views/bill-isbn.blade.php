<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Tagihan ISBN</span>
            </h4>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card card-body text-center bg-primary text-white card-summary">
                <div class="text-center position-relative mb-2">
                    <i class="ph-barcode ph-2x"></i>
                </div>
                <h6 class="mb-2">Nomor ISBN Diberikan</h6>
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="fs-sm">Total</div>
                        <h5 class="mb-0" id="total-isbn">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-body text-center bg-secondary text-white card-summary">
                <div class="text-center position-relative mb-2">
                    <i class="ph-book-bookmark ph-2x"></i>
                </div>
                <h6 class="mb-2">Status Penerbitan</h6>
                <div class="row justify-content-center">
                    <div class="col-6 text-center">
                        <div class="fs-sm">Sudah Terbit</div>
                        <h5 class="mb-0" id="done-publish">0</h5>
                    </div>
                    <div class="col-6 text-center">
                        <div class="fs-sm">Belum Terkonfirmasi</div>
                        <h5 class="mb-0" id="not-publish">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-body text-center bg-danger text-white card-summary">
                <div class="text-center position-relative mb-2">
                    <i class="ph-x ph-2x"></i>
                </div>
                <h6 class="mb-2">Belum Diserahkan</h6>
                <div class="row justify-content-center">
                    <div class="col-6 text-center">
                        <div class="fs-sm">Perpusnas</div>
                        <h5 class="mb-0" id="not-handover-perpusnas">0</h5>
                    </div>
                    <div class="col-6 text-center">
                        <div class="fs-sm">Provinsi</div>
                        <h5 class="mb-0" id="not-handover-province">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-body text-center bg-success text-white card-summary">
                <div class="text-center position-relative mb-2">
                    <i class="ph-checks ph-2x"></i>
                </div>
                <h6 class="mb-2">Sudah Diserahkan</h6>
                <div class="row justify-content-center">
                    <div class="col-6 text-center">
                        <div class="fs-sm">Perpusnas</div>
                        <h5 class="mb-0" id="done-handover-perpusnas">0</h5>
                    </div>
                    <div class="col-6 text-center">
                        <div class="fs-sm">Provinsi</div>
                        <h5 class="mb-0" id="done-handover-province">0</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-body text-center bg-warning text-white card-summary">
                <div class="text-center position-relative mb-2">
                    <i class="ph-truck ph-2x"></i>
                </div>
                <h6 class="mb-2">Dalam Pengiriman</h6>
                <div class="row justify-content-center">
                    <div class="col-6 text-center">
                        <div class="fs-sm">Perpusnas</div>
                        <h5 class="mb-0" id="in-delivery-perpusnas">0</h5>
                    </div>
                    <div class="col-6 text-center">
                        <div class="fs-sm">Provinsi</div>
                        <h5 class="mb-0" id="in-delivery-province">0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="hstack gap-2 mb-0">Filter Data</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text">Pelaksana Serah</span>
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
            <hr class="py-1 mb-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Judul :</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Kepeng :</label>
                        <input type="text" class="form-control" name="author" id="author" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tahun Terbit :</label>
                        <select class="form-select" name="year" id="year">
                            <option value="">Semua</option>
                            @for($i = 2019; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Nomor ISBN :</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Subjek :</label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Sinopsis Class :</label>
                        <input type="text" class="form-control" name="sinopsis_class" id="sinopsis_class" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Nomor Panggil :</label>
                        <input type="text" class="form-control" name="call_number" id="call_number" placeholder="....................">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Media :</label>
                        <select class="form-select" name="media" id="media">
                            <option value="">Semua</option>
                            <option value="cetak">Cetak</option>
                            <option value="digital pdf">Digital PDF</option>
                            <option value="digital epub">Digital EPUB</option>
                            <option value="audio book">Audio Book</option>
                            <option value="audio visual book">Audio Visual Book</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tgl Terima KCKR :</label>
                        <input type="text" class="form-control date-range-picker" name="received_date_kckr" id="received_date_kckr" placeholder="Semua Tanggal" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tgl Terima Provinsi :</label>
                        <input type="text" class="form-control date-range-picker" name="received_date_province" id="received_date_province" placeholder="Semua Tanggal" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="text-end">
                <a href="{{ url('bill-isbn') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
                    <i class="ph-arrows-clockwise me-1"></i>
                    Reset Filter
                </a>
                <a href="javascript:void(0);" class="btn btn-success" onclick="loadData()">
                    <i class="ph-magnifying-glass me-1"></i>
                    Cari Data
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover w-100 display" id="datatable-serverside">
                <thead class="text-bg-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Pelaksana Serah</th>
                        <th class="text-nowrap">Judul</th>
                        <th class="text-nowrap">Kepeng</th>
                        <th class="text-nowrap">Tahun</th>
                        <th class="text-nowrap">ISBN</th>
                        <th class="text-nowrap">Media</th>
                        <th class="text-nowrap">Pustaka</th>
                        <th class="text-nowrap">Tgl Terima KCKR</th>
                        <th class="text-nowrap">Tgl Terima Provinsi</th>
                        <th class="text-nowrap">Sinopsis</th>
                        <th class="text-nowrap">Tgl Terima</th>
                        <th class="text-nowrap">Tgl Dibuat</th>
                        <th class="text-nowrap">Tgl Update</th>
                    </tr>
                </thead>
            </table>
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
                $('#done-handover-perpusnas').text($.number(response.total_sudah_serah_prov));
                $('#in-delivery-province').text($.number(response.total_dalam_pengiriman));
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
                data: {
                    title: $('#title').val(),
                    author: $('#author').val(),
                    year: $('#year').val(),
                    code: $('#code').val(),
                    subject: $('#subject').val(),
                    media: $('#media').val(),
                    received_date_kckr: $('#received_date_kckr').val(),
                    received_date_province: $('#received_date_province').val(),
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
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle text-wrap' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle' },
                { orderable: false, className: 'align-middle' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('div.dataTables_filter input');

                searchInput.off().unbind();

                searchInput.on('keyup', debounce(function () {
                    table.search(this.value).draw();
                }, 500));
            },
        }).on('draw.dt', function() {
            onLoading('close', '#datatable-serverside_wrapper');
        });

        window.gDataTable.columns.adjust().draw();
    }
</script>
