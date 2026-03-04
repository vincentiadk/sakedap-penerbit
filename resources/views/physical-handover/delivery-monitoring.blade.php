<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - <span class="fw-normal">Monitoring Pengiriman</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-info p-2 bg-opacity-10 text-info">
                    Pantau Status Pengiriman
                </span>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
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
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-map-pin me-1"></i>
                                Tujuan Pengiriman
                            </label>
                            <select class="form-select" name="branch_id" id="branch_id">
                                <option value="">Semua Tujuan</option>
                                <option value="37">Perpustakaan Nasional Republik Indonesia</option>
                                @if(Main::getBranch())
                                    <option value="{{ Main::getBranch()->ID }}">{{ Main::getBranch()->NAME }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar-blank me-1"></i>
                                Jenis Tanggal
                            </label>
                            <select class="form-select" name="date_type" id="date_type">
                                <option value="letter_date" selected>Tanggal Pengiriman</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar me-1"></i>
                                Tanggal
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-calendar-blank"></i>
                                </span>
                                <input type="text" class="form-control" name="date" id="date" placeholder="Pilih tanggal" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url('physical-handover/delivery-monitoring') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
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
        <div class="card-header border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ph-package me-1 text-success"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Pengiriman</h6>
                </div>
                <span class="badge bg-info bg-opacity-10 text-info" id="total-records">
                    <i class="ph-truck me-1"></i>
                    <span id="record-count">0</span> Pengiriman
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered display nowrap w-100" id="datatable-serverside">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-nowrap" rowspan="2" style="width: 60px">
                                <i class="ph-hash"></i>
                            </th>
                            <th class="text-center text-nowrap" rowspan="2" style="width: 100px">
                                <i class="ph-gear"></i>
                                Aksi
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 180px">
                                <i class="ph-user-circle me-1"></i>
                                Pelaksana Serah
                            </th>
                            <th class="text-center text-nowrap" rowspan="2" style="min-width: 120px">
                                <i class="ph-flag me-1"></i>
                                Status
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 150px">
                                <i class="ph-file-text me-1"></i>
                                No Surat
                            </th>
                            <th class="text-center text-nowrap" rowspan="2" style="min-width: 130px">
                                <i class="ph-calendar me-1"></i>
                                Tanggal
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 200px">
                                <i class="ph-map-pin me-1"></i>
                                Tujuan
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                Resi
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 150px">
                                <i class="ph-truck me-1"></i>
                                Jasa Kirim
                            </th>
                            <th class="text-nowrap" rowspan="2" style="min-width: 150px">
                                <i class="ph-user me-1"></i>
                                Pengirim
                            </th>
                            <th class="text-center text-nowrap" rowspan="2" style="min-width: 130px">
                                <i class="ph-phone me-1"></i>
                                Telepon
                            </th>
                            <th class="text-center" colspan="2">
                                <i class="ph-package me-1"></i>
                                Detail Pengiriman
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-book me-1"></i>
                                Judul
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-stack me-1"></i>
                                Eksemplar
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="modal-form" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-bg-warning">
                <h5 class="modal-title">
                    <i class="ph-receipt me-1"></i>
                    Form Data Resi Pengiriman
                </h5>
                <button type="button" class="btn btn-light btn-sm btn-icon rounded-pill" data-bs-dismiss="modal">
                    <i class="ph-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show border-0 d-none" id="validation-element">
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
                    <input type="hidden" name="table_id" id="table_id">
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-user me-1"></i>
                            Nama Pengirim
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-identification-card"></i>
                            </span>
                            <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="Contoh: John Doe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-barcode me-1"></i>
                            Nomor Resi
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-hash"></i>
                            </span>
                            <input type="text" class="form-control" name="receipt_no" id="receipt_no" placeholder="Contoh: JNE1234567890">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-currency-circle-dollar me-1"></i>
                            Biaya Kirim
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">IDR</span>
                            <input type="number" class="form-control" name="delivery_fee" id="delivery_fee" placeholder="0" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-cube me-1"></i>
                            Berat Paket
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="weight" id="weight" placeholder="0" min="0">
                            <span class="input-group-text">gram</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-semibold">
                            <i class="ph-truck me-1"></i>
                            Jasa Pengiriman
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-basic" name="delivery_service_id" id="delivery_service_id" data-dropdown-parent="#modal-form" data-placeholder="Pilih Jasa Pengiriman">
                            <option value=""></option>
                            @foreach($deliveryService as $ds)
                                <option value="{{ $ds->ID }}">{{ $ds->NAME }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="ph-x me-1"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-warning" id="btn-create" onclick="updateData()">
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
            $('#validation-data').append('<li class="mb-1">' + value + '</li>');
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
                { orderable: true, className: 'align-middle text-center fw-semibold' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
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
                $('#weight').val(response.BERAT);
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

                    swalInit.fire({
                        title: 'Berhasil',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else if(response.code == 400) {
                    $('#modal-form .modal-body').scrollTop(0);

                    showValidation(response.error);
                } else {
                    swalInit.fire({
                        title: response.code == 404 ? 'Oops...' : 'Error',
                        text: response.message,
                        icon: response.code == 404 ? 'warning' : 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(response) {
                onLoading('close', '.modal-content');
                responseError(response);
            }
        });
    }

    function destroyData(id) {
        var notyConfirm = new Noty({
            text: '<div class="mb-3"><h5 class="text-dark">Hapus Data?</h5><span class="text-muted">Data yang telah dihapus tidak bisa dikembalikan lagi</span></div>',
            timeout: false,
            modal: true,
            layout: 'center',
            closeWith: 'button',
            type: 'confirm',
            buttons: [
                Noty.button('Tidak', 'btn btn-light', function () {
                    notyConfirm.close();
                }),
                Noty.button('Hapus', 'btn btn-danger ms-2', function () {
                    $.ajax({
                        url: '{{ url("physical-handover/delivery-monitoring/destroy-data") }}',
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            onLoading('show', '.noty_bar');
                        },
                        success: function(response) {
                            onLoading('close', '.noty_bar');

                            if(response.code == 200) {
                                notyConfirm.close();
                                onReloadTable();
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
                            onLoading('close', '.noty_bar');
                            responseError(response);
                        }
                    });
                })
            ]
        }).show();
    }
</script>
