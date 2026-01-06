<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Permintaan File Koleksi</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-light" onclick="onReloadTable()">
                    <i class="ph-arrows-clockwise me-1"></i>
                    Refresh Data
                </button>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert bg-info text-white alert-dismissible fade show shadow-sm">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="ph-info ph-2x"></i>
            </div>
            <div class="flex-fill">
                <h6 class="alert-heading fw-semibold mb-1">Informasi Permintaan File</h6>
                <p class="mb-2">Pilih koleksi yang ingin Anda ajukan untuk mendapatkan file digitalnya. Pastikan Anda mengisi surat pernyataan dengan benar.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-info">
                        <i class="ph-check-circle me-1"></i>
                        Pilih koleksi dari daftar
                    </span>
                    <span class="badge bg-white text-info">
                        <i class="ph-upload-simple me-1"></i>
                        Upload surat pernyataan
                    </span>
                    <span class="badge bg-white text-info">
                        <i class="ph-clock me-1"></i>
                        Tunggu verifikasi admin
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-fill">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-stack me-1 text-primary"></i>
                        Daftar Koleksi Tersedia
                    </h5>
                    <p class="text-muted fs-sm mb-0 mt-1">Pilih koleksi yang ingin Anda ajukan untuk mendapatkan file digitalnya</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100 display" id="datatable-serverside-collection">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-nowrap" style="width: 60px;">No</th>
                            <th class="text-center text-nowrap" style="width: 100px;">Aksi</th>
                            <th class="text-nowrap">Judul Koleksi</th>
                            <th class="text-center text-nowrap" style="width: 150px;">Identifier</th>
                            <th class="text-center text-nowrap" style="width: 130px;">Tgl Terima</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-fill">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-clipboard-text me-1 text-success"></i>
                        Daftar Pengajuan Anda
                    </h5>
                    <p class="text-muted fs-sm mb-0 mt-1">Pantau status pengajuan permintaan file yang telah Anda kirimkan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100 display" id="datatable-serverside">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-nowrap" style="width: 60px;">No</th>
                            <th class="text-nowrap">Judul Koleksi</th>
                            <th class="text-center text-nowrap" style="width: 130px;">Status</th>
                            <th class="text-center text-nowrap" style="width: 120px;">Total Download</th>
                            <th class="text-center text-nowrap" style="width: 150px;">Surat Pernyataan</th>
                            <th class="text-center text-nowrap" style="width: 140px;">Tgl Pengajuan</th>
                            <th class="text-center text-nowrap" style="width: 120px;">Download</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="modal-form" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <span></span>
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <div class="alert alert-info border-0 mb-3">
                    <div class="d-flex align-items-start">
                        <i class="ph-info ph-2x me-3"></i>
                        <div class="flex-fill">
                            <h6 class="alert-heading fw-semibold mb-1">Petunjuk Pengajuan:</h6>
                            <ol class="mb-0 ps-3">
                                <li class="mb-1">Download template surat pernyataan dengan klik tombol "Unduh Contoh Surat"</li>
                                <li class="mb-1">Isi surat pernyataan sesuai dengan data Anda</li>
                                <li class="mb-1">Upload surat yang sudah diisi pada form di bawah</li>
                                <li>Klik tombol "Ajukan" untuk mengirim pengajuan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <form id="form-data" class="form-ajax">
                    <input type="hidden" name="catalog_id" id="catalog_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Surat Pernyataan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-file-arrow-up"></i>
                            </span>
                            <input type="file" class="form-control" name="request_letter" id="request_letter" accept=".pdf,.doc,.docx">
                            <button class="btn btn-light" type="button" onclick="$('#request_letter').val('')" data-bs-toggle="tooltip" title="Clear file">
                                <i class="ph-x"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="ph-info me-1"></i>
                            Format file yang diterima: PDF, DOC, DOCX (Maksimal 2MB)
                        </div>
                    </div>
                    <div class="card border-dashed border-2 d-none" id="file-preview">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                        <i class="ph-file-text ph-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-1 fw-semibold" id="file-name">-</h6>
                                    <p class="text-muted fs-sm mb-0" id="file-size">-</p>
                                </div>
                                <button type="button" class="btn btn-light btn-icon" onclick="clearFilePreview()">
                                    <i class="ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="{{ url('download/from-public?path=assets/surat-permohonan-file.doc') }}"
                   class="btn btn-success" target="_blank">
                    <i class="ph-download me-1"></i>
                    Unduh Contoh Surat
                </a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="ph-x me-1"></i>
                    Batal
                </button>
                <button class="btn btn-primary d-none" id="btn-create" onclick="createData()">
                    <i class="ph-paper-plane-tilt me-1"></i>
                    Ajukan Permintaan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        loadData();
        loadDataCollection();
        initFileUpload();
    });

    function initFileUpload() {
        $('#request_letter').on('change', function() {
            var file = this.files[0];

            if (file) {
                var fileSize = (file.size / 1024 / 1024).toFixed(2);

                $('#file-name').text(file.name);
                $('#file-size').text(fileSize + ' MB');
                $('#file-preview').removeClass('d-none');
            } else {
                clearFilePreview();
            }
        });
    }

    function clearFilePreview() {
        $('#request_letter').val('');
        $('#file-preview').addClass('d-none');
        $('#file-name').text('-');
        $('#file-size').text('-');
    }

    function onReloadTable() {
        loadData();
        loadDataCollection();
    }

    function onReset() {
        clearValidation();
        clearFilePreview();

        $('#modal-form').modal('hide');
        $('#form-data').trigger('reset');
        $('#btn-create').removeClass('d-none');
    }

    function onCreate() {
        onReset();

        $('#modal-form .modal-title span').text('Tambah Pengajuan Permintaan File');
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
                { orderable: true, className: 'align-middle text-center fw-semibold' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('#datatable-serverside_filter input');

                searchInput.off().unbind();
                searchInput.addClass('form-control-sm');

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
        window.gDataTableCollection = $('#datatable-serverside-collection').DataTable({
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
                { orderable: true, className: 'align-middle text-center fw-semibold' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
            ],
            initComplete: function (settings, json) {
                var table = this.api();
                const searchInput = $('#datatable-serverside-collection_filter input');

                searchInput.off().unbind();
                searchInput.addClass('form-control-sm');

                searchInput.on('keyup', debounce(function () {
                    table.search(this.value).draw();
                }, 500));
            },
        }).on('draw.dt', function() {
            onLoading('close', '#datatable-serverside-collection_wrapper');
        });

        window.gDataTableCollection.columns.adjust().draw();
    }

    function praCreate(id) {
        onCreate();

        $('#catalog_id').val(id);
    }

    function createData() {
        var file = $('#request_letter')[0].files[0];

        if (!file) {
            swalInit.fire({
                title: 'Peringatan',
                text: 'Silakan upload surat pernyataan terlebih dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });

            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            swalInit.fire({
                title: 'Peringatan',
                text: 'Ukuran file maksimal 2MB',
                icon: 'warning',
                confirmButtonText: 'OK'
            });

            return;
        }

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
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
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
</script>
