<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - <span class="fw-normal">Koleksi Ditolak</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-danger p-2 bg-opacity-10 text-danger">
                    Koleksi Ditolak
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
                    <i class="ph-list-checks me-1 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Koleksi Yang Akan Dihibahkan / Diambil Kembali</h6>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ph-hand-pointing me-1"></i>
                        Aksi
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item" onclick="grant()">
                            <i class="ph-gift me-1"></i>
                            Hibahkan
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" onclick="retur()">
                            <i class="ph-cube me-1"></i>
                            Ambil Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered display nowrap w-100" id="datatable-action">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap" style="min-width: 250px">
                                <i class="ph-book me-1"></i>
                                Judul
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-stack me-1"></i>
                                Jumlah
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                Resi
                            </th>
                            <th class="text-center text-nowrap" style="width: 100px">
                                <i class="ph-trash me-1"></i>
                                Hapus
                            </th>
                        </tr>
                    </thead>
                </table>
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
                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-calendar me-1"></i>
                                Tanggal
                            </label>
                            <div class="input-group">
                                <select class="form-select w-auto flex-grow-0" name="date_type" id="date_type" style="max-width: 150px;">
                                    <option value="accept_date">Diterima</option>
                                    <option value="letter_date">Pengiriman</option>
                                </select>
                                <span class="input-group-text">
                                    <i class="ph-calendar-blank"></i>
                                </span>
                                <input type="text" class="form-control" name="date" id="date" placeholder="Pilih tanggal">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ph-truck me-1"></i>
                                Jasa Kirim
                            </label>
                            <select class="form-select select2-basic" name="delivery_service_id" id="delivery_service_id" data-placeholder="Semua Jasa Kirim">
                                <option value=""></option>
                                @foreach($deliveryService as $ds)
                                    <option value="{{ $ds->ID }}">{{ $ds->NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url('physical-handover/reject') }}" class="btn btn-danger" onclick="onLoading('show', 'body')">
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
                    <i class="ph-x-circle me-1 text-danger"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Koleksi Ditolak</h6>
                </div>
                <button type="button" class="btn btn-primary" onclick="addListAction()">
                    <i class="ph-list-plus me-1"></i>
                    Tambahkan ke Daftar Atas
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered display nowrap w-100" id="datatable-serverside">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-nowrap" style="width: 50px">
                                <i class="ph-check-square"></i>
                            </th>
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
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-gift me-1"></i>
                                Auto Hibah
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 130px">
                                <i class="ph-calendar me-1"></i>
                                Tgl Kirim
                            </th>
                            <th class="text-nowrap" style="min-width: 250px">
                                <i class="ph-book me-1"></i>
                                Judul
                            </th>
                            <th class="text-nowrap" style="min-width: 200px">
                                <i class="ph-map-pin me-1"></i>
                                Tujuan
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-truck me-1"></i>
                                Jasa Kirim
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                Resi
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 100px">
                                <i class="ph-stack me-1"></i>
                                Jumlah
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-books me-1"></i>
                                Jenis Koleksi
                            </th>
                            <th class="text-nowrap" style="min-width: 200px">
                                <i class="ph-warning-circle me-1"></i>
                                Alasan Ditolak
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
        loadDataAction();
        loadData();
    });

    function onReloadTable() {
        window.gDataTable.ajax.reload(null, false);
    }

    function loadData() {
        window.gDataTable = $('#datatable-serverside').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollX: true,
            destroy: true,
            order: [[1, 'desc']],
            columnDefs: [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
            ],
            select: {
                style: 'multi',
                selector: 'td.allow-select'
            },
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="ph-microsoft-excel-logo me-1"></i> Download Excel',
                    className: 'btn btn-success',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Semua Data Keseluruhan',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                    search: 'none',
                                }
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Semua Data Dengan Pencarian',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                    search: 'applied',
                                }
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Halaman Ini Saja',
                            exportOptions: {
                                modifier: {
                                    page: 'current',
                                }
                            }
                        },
                    ]
                },
                {
                    extend: 'selectAll',
                    className: 'btn btn-info',
                    text: '<i class="ph-checks me-1"></i> Centang Semua'
                },
                {
                    extend: 'selectNone',
                    className: 'btn btn-warning',
                    text: '<i class="ph-x me-1"></i> Hilangkan Semua Centang'
                },
            ],
            ajax: {
                url: '{{ url("physical-handover/reject/datatable") }}',
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
                { orderable: false, className: 'align-middle text-center allow-select' },
                { orderable: true, className: 'align-middle text-center fw-semibold allow-select' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap allow-select' },
                { orderable: true, className: 'align-middle text-center allow-select' },
                { orderable: true, className: 'align-middle text-center allow-select' },
                { orderable: true, className: 'align-middle text-wrap allow-select' },
                { orderable: true, className: 'align-middle text-wrap allow-select' },
                { orderable: true, className: 'align-middle text-wrap allow-select' },
                { orderable: true, className: 'align-middle allow-select' },
                { orderable: true, className: 'align-middle text-center allow-select' },
                { orderable: true, className: 'align-middle text-wrap allow-select' },
                { orderable: true, className: 'align-middle allow-select' },
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

    function loadDataAction() {
        $('#datatable-action').DataTable({
            deferRender: true,
            scrollX: true,
            destroy: true,
            columns: [
                { className: 'align-middle text-wrap' },
                { className: 'align-middle text-center' },
                { className: 'align-middle' },
                { className: 'align-middle text-center' },
            ]
        });

        $('#datatable-action').DataTable().clear().draw();

        var localStorageData = localStorage.getItem('datatable-action-reject');
        var data = localStorageData ? JSON.parse(localStorageData) : [];

        $.each(data, function(i, val) {
            var btnRemove = `
                <button type="button" class="btn btn-danger btn-sm" onclick="removeListAction(${ val[0] })">
                    <i class="ph-trash"></i>
                </button>
            `;

            $('#datatable-action').DataTable().row.add([
                val[1],
                val[2],
                val[3],
                btnRemove
            ]).draw().node();
        });
    }

    function addListAction() {
        window.gDataTable.rows({ selected: true }).every(function() {
            var row = this.node();
            var data = $(row).find('input[name="data"]');
            var id = data.data('id');
            var title = data.data('title');
            var qty = data.data('qty-reject');
            var receipt = data.data('receipt');

            var dataStorage = localStorage.getItem('datatable-action-reject');
            var currentDataStorage = dataStorage ? JSON.parse(dataStorage) : [];
            var isDuplicate = false;

            var payload = [
                id,
                title,
                qty,
                receipt,
            ];

            for (var i = 0; i < currentDataStorage.length; i++) {
                if (currentDataStorage[i][0] === id) {
                    isDuplicate = true;

                    break;
                }
            }

            if (!isDuplicate) {
                currentDataStorage.push(payload);
            }

            localStorage.setItem('datatable-action-reject', JSON.stringify(currentDataStorage));
        });

        $('.buttons-select-none').click();

        loadDataAction();

        swalInit.fire({
            title: 'Berhasil',
            text: 'Data telah ditambahkan dalam list',
            icon: 'success'
        });
    }

    function removeListAction(id) {
        var dataStorage = localStorage.getItem('datatable-action-reject');
        var currentDataStorage = dataStorage ? JSON.parse(dataStorage) : [];

        var updatedDataStorage = currentDataStorage.filter(function(item) {
            return item[0] !== id;
        });

        localStorage.setItem('datatable-action-reject', JSON.stringify(updatedDataStorage));

        loadDataAction();
    }

    function grant(param = null) {
        if(param) {
            var id = [param];
        } else {
            var id = [];
            var dataStorage = localStorage.getItem('datatable-action-reject');
            var responseDataStorage = dataStorage ? JSON.parse(dataStorage) : [];

            $.each(responseDataStorage, function(i, val) {
                id.push(val[0]);
            });
        }

        swalInit.fire({
            icon: 'question',
            title: 'Verifikasi Password Koleksi Dihibahkan',
            html: `
                <div class="sweetalert-input-field mt-3">
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password login anda untuk verifikasi">
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
            input: null,
            didOpen: () => {
                $('.swal2-input').remove();
            },
            preConfirm: () => {
                const password = $('#password').val();

                if (!password) {
                    Swal.showValidationMessage('Mohon mengisi password');

                    return false;
                }

                return $.ajax({
                    url: '{{ url("auth/check-ajax-password") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        password: password
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        onLoading('show', '.swal2-popup');
                    }
                }).then(function(response) {
                    onLoading('close', '.swal2-popup');

                    if(response.code == 200) {
                        return true;
                    } else {
                        throw new Error(response.message);
                    }
                }).catch(function(error) {
                    onLoading('close', '.swal2-popup');

                    if (error.message) {
                        Swal.showValidationMessage(error.message);
                    } else {
                        Swal.showValidationMessage('Terjadi kesalahan server');
                    }

                    return false;
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("physical-handover/reject/grant") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        onLoading('show', 'body');
                    },
                    success: function(response) {
                        onLoading('close', 'body');

                        if(response.code == 200) {
                            onReloadTable();
                            localStorage.removeItem('datatable-action-reject');
                            notification('success', response.message);

                            $('#datatable-action').DataTable().clear().draw();
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

    function retur(param = null) {
        if(param) {
            var id = [param];
        } else {
            var id = [];
            var dataStorage = localStorage.getItem('datatable-action-reject');
            var responseDataStorage = dataStorage ? JSON.parse(dataStorage) : [];

            $.each(responseDataStorage, function(i, val) {
                id.push(val[0]);
            });
        }

        swalInit.fire({
            icon: 'question',
            title: 'Verifikasi Password Koleksi Diambil Kembali',
            html: `
                <div class="sweetalert-input-field mt-3">
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password login anda untuk verifikasi">
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
            input: null,
            didOpen: () => {
                $('.swal2-input').remove();
            },
            preConfirm: () => {
                const password = $('#password').val();

                if (!password) {
                    Swal.showValidationMessage('Mohon mengisi password');

                    return false;
                }

                return $.ajax({
                    url: '{{ url("auth/check-ajax-password") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        password: password
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        onLoading('show', '.swal2-popup');
                    }
                }).then(function(response) {
                    onLoading('close', '.swal2-popup');

                    if(response.code == 200) {
                        return true;
                    } else {
                        throw new Error(response.message);
                    }
                }).catch(function(error) {
                    onLoading('close', '.swal2-popup');

                    if (error.message) {
                        Swal.showValidationMessage(error.message);
                    } else {
                        Swal.showValidationMessage('Terjadi kesalahan server');
                    }

                    return false;
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                swalInit.fire({
                    icon: 'question',
                    title: 'Perencanaan',
                    html: `
                        <div class="sweetalert-input-field mt-3">
                            <div class="form-group text-start">
                                <label class="form-label">Rencana Pengambilan : <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="retur_planning" id="retur_planning" class="form-control">
                            </div>
                            <div class="form-group text-start">
                                <label class="form-label">No Telp : <span class="text-danger">*</span></label>
                                <input type="text" name="contact" id="contact" class="form-control" placeholder="..........................">
                            </div>
                            <div class="form-group text-start">
                                <label class="form-label">Nama Pengambil : <span class="text-danger">*</span></label>
                                <input type="text" name="retur_name" id="retur_name" class="form-control" placeholder="..........................">
                            </div>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    input: null,
                    didOpen: () => {
                        $('.swal2-input').remove();
                    },
                    preConfirm: () => {
                        const returPlanning = $('#retur_planning').val();
                        const contact = $('#contact').val();
                        const returName = $('#retur_name').val();

                        if (!returPlanning || !contact || !returName) {
                            Swal.showValidationMessage('Mohon mengisi rencana pengambilan, no telp, dan nama pengambil');

                            return false;
                        }

                        return {
                            returPlanning: returPlanning,
                            contact: contact,
                            returName: returName,
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $.ajax({
                            url: '{{ url("physical-handover/reject/retur") }}',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                retur_planning: result.value.returPlanning,
                                contact: result.value.contact,
                                retur_name: result.value.returName,
                                id: id
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                onLoading('show', 'body');
                            },
                            success: function(response) {
                                onLoading('close', 'body');

                                if(response.code == 200) {
                                    onReloadTable();
                                    localStorage.removeItem('datatable-action-reject');
                                    notification('success', response.message);

                                    $('#datatable-action').DataTable().clear().draw();
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
        });
    }
</script>
