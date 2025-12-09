<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Permintaan File</span>
            </h4>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Koleksi</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover w-100 display" id="datatable-serverside-collection">
                <thead class="text-bg-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Aksi</th>
                        <th class="text-nowrap">Judul</th>
                        <th class="text-nowrap">Kode</th>
                        <th class="text-nowrap">Tgl Terima</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Pengajuan</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover w-100 display" id="datatable-serverside">
                <thead class="text-bg-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Judul</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Total Download</th>
                        <th class="text-nowrap">Surat Pernyataan</th>
                        <th class="text-nowrap">Tgl Pengajuan</th>
                        <th class="text-nowrap">Download</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="modal-form" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ph-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="validation-element">
                    <ul class="mb-0" id="validation-data"></ul>
                </div>
                <form id="form-data" class="form-ajax">
                    <input type="hidden" name="catalog_id" id="catalog_id">
                    <div class="form-group">
                        <label class="form-label">Surat Pernyataan : <span class="text-danger fw-bold">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="request_letter" id="request_letter">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <a href="{{ url('download/from-public?path=assets/surat-permohonan-file.doc') }}" class="btn btn-success" target="_blank">
                    <i class="ph-download me-1"></i>
                    Unduh Contoh Surat
                </a>
                <button class="btn btn-primary d-none" id="btn-create" onclick="createData()">
                    <i class="ph-plus-circle me-1"></i>
                    Ajukan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        loadData();
        loadDataCollection();
    });

    function onReloadTable() {
        loadData();
    }

    function onReset() {
        clearValidation();

        $('#modal-form').modal('hide');
        $('#form-data').trigger('reset');
        $('#btn-create').removeClass('d-none');
    }

    function onCreate() {
        onReset();

        $('#modal-form .modal-title').text('Tambah Pengajuan');
        $('#modal-form').modal('show');
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
                url: '{{ url("request-file/datatable") }}',
                dataType: 'JSON',
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
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle' },
                { orderable: false, className: 'align-middle text-center' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('#datatable-serverside_filter input');

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

    function loadDataCollection() {
        window.gDataTable = $('#datatable-serverside-collection').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            scrollX: true,
            destroy: true,
            order: [[0, 'desc']],
            ajax: {
                url: '{{ url("request-file/datatable-collection") }}',
                dataType: 'JSON',
                beforeSend: function() {
                    onLoading('show', '#datatable-serverside-collection_wrapper');
                },
                error: function(response) {
                    onLoading('close', '#datatable-serverside-collection_wrapper');
                    responseError(response);
                }
            },
            columns: [
                { orderable: true, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('#datatable-serverside-collection_filter input');

                searchInput.off().unbind();

                searchInput.on('keyup', debounce(function () {
                    table.search(this.value).draw();
                }, 500));
            },
        }).on('draw.dt', function() {
            onLoading('close', '#datatable-serverside-collection_wrapper');
        });

        window.gDataTable.columns.adjust().draw();
    }

    function praCreate(id) {
        onCreate();

        $('#catalog_id').val(id);
    }

    function createData() {
        $.ajax({
            url: '{{ url("request-file/create-data") }}',
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
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
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
