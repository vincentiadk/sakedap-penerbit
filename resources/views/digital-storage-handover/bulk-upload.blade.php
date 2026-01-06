<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - <span class="fw-normal">Unggah Banyak</span>
            </h4>
            <a href="#page-header" class="btn btn-light btn-sm rounded-pill ms-2 d-lg-none" data-bs-toggle="collapse">
                <i class="ph-caret-down"></i>
            </a>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page-header">
            <div class="d-sm-flex align-items-center mb-lg-0 ms-lg-3">
                <div class="d-inline-flex mt-3 mt-sm-0 gap-2">
                    <a href="{{ url('download/from-public') }}?path=assets/bulk-example.zip" target="_blank" class="btn btn-success btn-sm">
                        <i class="ph-file-zip me-1"></i>
                        Contoh Upload
                    </a>
                    <a href="{{ url('download/from-public') }}?path=assets/PANDUAN BULK UPLOAD SAKEDAP.pdf" target="_blank" class="btn btn-info btn-sm">
                        <i class="ph-file-pdf me-1"></i>
                        Panduan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert alert-danger border-0 d-none" id="validation-element">
        <div class="d-flex align-items-start">
            <i class="ph-warning-circle me-1 mt-1"></i>
            <div class="flex-fill">
                <h6 class="alert-heading fw-semibold mb-2">Validasi Error</h6>
                <ul class="mb-0" id="validation-data"></ul>
            </div>
        </div>
        <button type="button" class="btn-close" onclick="clearValidation()"></button>
    </div>
    <form id="form-data">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-0">
                    <li class="nav-item">
                        <a href="#nav-tabs-upload" class="nav-link active" data-bs-toggle="tab">
                            <i class="ph-upload me-1"></i>
                            Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#nav-tabs-progress" class="nav-link" data-bs-toggle="tab" onclick="loadData()">
                            <i class="ph-clock-counter-clockwise me-1"></i>
                            Progress
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active" id="nav-tabs-upload">
                        <div class="alert alert-info alert-dismissible fade show shadow-sm">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="ph-info ph-2x"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="alert-heading fw-semibold mb-1">Petunjuk Upload Banyak</h6>
                                    <ul class="mb-0 small">
                                        <li>Upload file dalam format <strong>ZIP</strong></li>
                                        <li>Pilih jenis koleksi: <strong>Non Serial</strong> atau <strong>Serial</strong></li>
                                        <li>Download contoh file dan panduan untuk referensi</li>
                                        <li>File ZIP maksimal sesuai konfigurasi server</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="ph-list-bullets me-1"></i>
                                    Jenis Koleksi
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="type" id="type" onchange="changeType()">
                                    <option value="">Pilih jenis koleksi...</option>
                                    <option value="bulk_non_serial">Non Serial</option>
                                    <option value="bulk_serial">Serial</option>
                                </select>
                            </div>
                            <div class="col-12" id="param-id"></div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="ph-file-zip me-1"></i>
                                    File ZIP
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="file" id="file">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url('digital-storage-handover/bulk-upload') }}" class="btn btn-light" onclick="onLoading('show', 'body')">
                                <i class="ph-arrow-counter-clockwise me-1"></i>
                                Reset
                            </a>
                            <button type="button" class="btn btn-primary" onclick="submitted()">
                                <i class="ph-paper-plane-tilt me-1"></i>
                                Submit Data
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-tabs-progress">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered display nowrap w-100" id="datatable-serverside">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center text-nowrap" style="width: 60px">
                                            <i class="ph-hash"></i>
                                        </th>
                                        <th class="text-center text-nowrap" style="width: 100px">
                                            <i class="ph-eye"></i>
                                            Detail
                                        </th>
                                        <th class="text-nowrap" style="min-width: 200px">
                                            <i class="ph-file-zip me-1"></i>
                                            File
                                        </th>
                                        <th class="text-center text-nowrap" style="min-width: 150px">
                                            <i class="ph-play-circle me-1"></i>
                                            Mulai Proses
                                        </th>
                                        <th class="text-center text-nowrap" style="min-width: 150px">
                                            <i class="ph-check-circle me-1"></i>
                                            Selesai Proses
                                        </th>
                                        <th class="text-center text-nowrap" style="min-width: 120px">
                                            <i class="ph-info me-1"></i>
                                            Status
                                        </th>
                                        <th class="text-center text-nowrap" style="min-width: 120px">
                                            <i class="ph-calendar-check me-1"></i>
                                            Tanggal
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="modal-bulk" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="ph-list-bullets me-1"></i>
                    Detail Bulk Upload
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px">
                                    <i class="ph-hash"></i>
                                </th>
                                <th class="text-nowrap" style="min-width: 250px">
                                    <i class="ph-book-open me-1"></i>
                                    Judul
                                </th>
                                <th class="text-nowrap" style="min-width: 200px">
                                    <i class="ph-note me-1"></i>
                                    Keterangan
                                </th>
                                <th class="text-center text-nowrap" style="min-width: 120px">
                                    <i class="ph-info me-1"></i>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody id="data-detail-bulk"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ph-x me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        dragAndDropFile('#file', {
            maxFileCount: 1,
            autoReplace: true,
            allowedFileExtensions: ['zip'],
            dropZoneTitle: '<i class="ph-file-zip ph-2x mb-2"></i><br>Drag & drop file ZIP di sini atau <span class="text-primary fw-semibold">klik untuk browse</span><br><small class="text-muted">Hanya file ZIP yang diperbolehkan</small>',
            msgPlaceholder: 'Pilih file ZIP untuk diupload',
            fileActionSettings: {
                showUpload: false,
                showRemove: true,
                showZoom: false,
                showDrag: false,
            }
        });
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
                url: '{{ url("digital-storage-handover/bulk-upload/datatable-bulk") }}',
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
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-center text-nowrap' },
                { orderable: true, className: 'align-middle text-center text-nowrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center text-nowrap' },
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
            url: '{{ url("digital-storage-handover/bulk-upload/detail-bulk") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id: id
            },
            beforeSend: function() {
                onLoading('show', '.modal-content');

                $('#modal-bulk').modal('show');
                $('#data-detail-bulk').html('');
            },
            success: function(response) {
                onLoading('close', '.modal-content');

                if(response.length > 0 && response) {
                    $.each(response, function(i, val) {
                        $('#data-detail-bulk').append(`
                            <tr>
                                <td class="text-center fw-semibold">${ i + 1 }</td>
                                <td class="text-wrap">${ val.TITLE }</td>
                                <td class="text-wrap">${ val.DESCRIPTION }</td>
                                <td class="text-center text-nowrap">${ val.STATUS }</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#data-detail-bulk').html(`
                        <tr>
                            <td class="text-center text-muted" colspan="4">
                                <i class="ph-database me-1"></i>
                                Tidak ada data
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(response) {
                onLoading('close', '.modal-content');
                responseError(response);
            }
        });
    }

    function changeType() {
        var type = $('#type').val();

        $('#btn-template').html('');
        $('#param-id').html('');

        if(type == 'bulk_serial') {
            $('#param-id').html(`
                <label class="form-label fw-semibold">
                    <i class="ph-book me-1"></i>
                    Katalog Parent
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ph-magnifying-glass"></i>
                    </span>
                    <input type="hidden" name="id" id="id">
                    <input type="text" class="form-control" name="text" id="text" placeholder="Cari dan pilih katalog parent..." readonly>
                </div>
            `);

            lookupCatalogParent('#text', '#id');
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
            $('#validation-data').append('<li>' + value + '</li>');
        });

        $('html, body').animate({
            scrollTop: $('#validation-element').offset().top - 100
        }, 500);
    }

    function submitted() {
        $.ajax({
            url: '{{ url("digital-storage-handover/bulk-upload/submitted") }}',
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
                        title: '<i class="ph-check-circle text-success"></i> Berhasil',
                        html: `
                            <div class="alert alert-success border-0 mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="ph-check me-1 mt-1"></i>
                                    <div class="text-start">${response.message}</div>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: '<i class="ph-check me-1"></i> Oke',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onLoading('show', 'body');
                            location.href = '{{ url("digital-storage-handover/bulk-upload") }}';
                        }
                    });
                } else if(response.code == 400) {
                    onLoading('close', 'body');
                    showValidation(response.error);
                } else {
                    swalInit.fire({
                        title: '<i class="ph-warning text-warning"></i> Perhatian',
                        html: `
                            <div class="alert alert-warning border-0 mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="ph-warning me-1 mt-1"></i>
                                    <div class="text-start">${response.message}</div>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        showCloseButton: true,
                        confirmButtonText: '<i class="ph-check me-1"></i> Mengerti',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
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
