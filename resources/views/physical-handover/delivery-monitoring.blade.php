<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - <span class="fw-normal">Monitoring Pengiriman</span>
            </h4>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="card">
        <div class="card-header">
            <h5 class="hstack gap-2 mb-0">Filter Data</h5>
        </div>
        <div class="card-body">
            <form id="form-filter">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tujuan :</label>
                            <select class="form-select" name="branch_id" id="branch_id">
                                <option value="">Semua</option>
                                <option value="37">Perpustakaan Nasional Republik Indonesia</option>
                                @if(Main::getBranch())
                                    <option value="{{ Main::getBranch()->ID }}">{{ Main::getBranch()->NAME }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Jenis Tanggal :</label>
                            <select class="form-select" name="date_type" id="date_type">
                                <option value="letter_date" selected>Pengiriman</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tanggal :</label>
                            <input type="text" class="form-control" name="date" id="date" placeholder="Semua Tanggal" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer bg-white">
            <div class="text-end">
                <a href="{{ url('physical-handover/delivery-monitoring') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
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
                        <th class="text-nowrap" rowspan="2">No</th>
                        <th class="text-nowrap" rowspan="2">Aksi</th>
                        <th class="text-nowrap" rowspan="2">Pelaksana Serah</th>
                        <th class="text-nowrap" rowspan="2">Status</th>
                        <th class="text-nowrap" rowspan="2">No Surat</th>
                        <th class="text-nowrap" rowspan="2">Tanggal</th>
                        <th class="text-nowrap" rowspan="2">Tujuan</th>
                        <th class="text-nowrap" rowspan="2">Resi</th>
                        <th class="text-nowrap" rowspan="2">Jasa Kirim</th>
                        <th class="text-nowrap" rowspan="2">Pengirim</th>
                        <th class="text-nowrap" rowspan="2">Telp</th>
                        <th class="text-nowrap text-center" colspan="2">Pengiriman</th>
                    </tr>
                    <tr>
                        <th class="text-nowrap text-center">Judul</th>
                        <th class="text-nowrap text-center">Eksemplar</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="modal-form" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Resi</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ph-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="validation-element">
                    <ul class="mb-0" id="validation-data"></ul>
                </div>
                <form id="form-data">
                    <input type="hidden" name="table_id" id="table_id">
                    <div class="form-group">
                        <label class="form-label">Nama Pengirim : <span class="text-danger fw-bold">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="Contoh : John Doe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Resi : <span class="text-danger fw-bold">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="receipt_no" id="receipt_no" placeholder="Contoh : JNE1234567890">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Biaya Kirim : <span class="text-danger fw-bold">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="delivery_fee" id="delivery_fee" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jasa Kirim : <span class="text-danger fw-bold">*</span></label>
                        <div class="input-group">
                            <select class="form-select select2-basic" name="delivery_service_id" id="delivery_service_id" data-dropdown-parent="#modal-form">
                                <option value=""></option>
                                @foreach($deliveryService as $ds)
                                    <option value="{{ $ds->ID }}">{{ $ds->NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-warning" id="btn-create" onclick="updateData()">
                    <i class="ph-floppy-disk me-1"></i>
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        datePickerBasic('#date');
        loadData();
    });

    function onReloadTable() {
        loadData();
    }

    function onReset() {
        clearValidation();

        $('#modal-form').modal('hide');
        $('#form-data').trigger('reset');
        $('#delivery_service_id').val('').change();
    }

    function clearValidation() {
        $('#validation-element').addClass('d-none');
        $('#validation-data').html('');
    }

    function showValidation(data) {
        $('#validation-element').removeClass('d-none');
        $('#validation-data').html('');

        $.each(data, function(index, value) {
            $('#validation-data').append('<li>' + value + '</li>');
        });
    }

    function formSuccess() {
        onReset();
        onReloadTable();
    }

    function loadData() {
        window.gDataTable = $('#datatable-serverside').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollX: true,
            destroy: true,
            order: [[0, 'desc']],
            ajax: {
                url: '{{ url("physical-handover/delivery-monitoring/datatable") }}',
                dataType: 'JSON',
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
                { orderable: true, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle' },
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

    function showData(id) {
        $.ajax({
            url: '{{ url("physical-handover/delivery-monitoring/show-data") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id: id
            },
            beforeSend: function() {
                onLoading('show', '.modal-content');
                onReset();

                $('#modal-form').modal('show');
            },
            success: function(response) {
                onLoading('close', '.modal-content');

                $('#table_id').val(response.LETTER_ID);
                $('#sender_name').val(response.SENDER);
                $('#receipt_no').val(response.RECEIPT_NO);
                $('#delivery_fee').val(response.BIAYA_KIRIM);
                $('#delivery_service_id').val(response.JASA_PENGIRIMAN_ID).change();
            },
            error: function(response) {
                onLoading('close', '.modal-content');
                responseError(response);
            }
        });
    }

    function updateData() {
        $.ajax({
            url: '{{ url("physical-handover/delivery-monitoring/update-data") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-data').serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                onLoading('show', '.modal-content');
                clearValidation();
            },
            success: function(response) {
                onLoading('close', '.modal-content');

                if(response.code == 200) {
                    formSuccess();
                    notification('success', response.message);
                } else if(response.code == 400) {
                    $('#modal-form .modal-body').scrollTop(0);
                    showValidation(response.error);
                } else {
                    swalInit.fire({
                        title: response.code == 404 ? 'Oops ...' : 'Error',
                        text: response.message,
                        icon: response.code == 404 ? 'warning' : 'error',
                        showCloseButton: false
                    });
                }
            },
            error: function(response) {
                onLoading('close', '.modal-content');
                responseError(response);
            }
        });
    }
</script>
