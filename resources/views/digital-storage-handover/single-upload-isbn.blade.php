<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - <span class="fw-normal">Unggah Tunggal ISBN</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <span class="badge bg-info p-2 bg-opacity-10 text-info">
                    Upload koleksi berbasis ISBN
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
                    <i class="ph-cloud-arrow-up me-1 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Upload File Koleksi</h6>
                </div>
                <a href="{{ asset('assets/Panduan Penggunaan Aplikasi Sakedap - Unggah Buku ISBN.pdf') }}" class="btn btn-teal btn-sm" target="_blank">
                    <i class="ph-file-pdf me-1"></i>
                    Lihat Panduan
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info alert-dismissible fade show shadow-sm">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="ph-info ph-2x"></i>
                    </div>
                    <div class="flex-fill">
                        <h6 class="alert-heading fw-semibold mb-1">Petunjuk Upload</h6>
                        <p class="mb-2">Upload file Cover (JPG/PNG) dan Konten (PDF/EPUB) dengan <strong>nama file yang sama</strong> sesuai ISBN.</p>
                        <ul class="mb-0 small">
                            <li>Format Cover: JPG, PNG (Max 200MB)</li>
                            <li>Format Konten: PDF, EPUB (Max 200MB)</li>
                            <li>Contoh: <code>9786023851218.jpg</code> & <code>9786023851218.pdf</code></li>
                        </ul>
                    </div>
                </div>
            </div>
            <form id="form-upload">
                <input type="file" name="files[]" id="files" multiple data-show-upload="false" data-show-caption="true" multiple>
            </form>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ph-list-bullets me-1 text-success"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Koleksi</h6>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-success bg-opacity-10 text-success" id="total-records">
                        <i class="ph-books me-1"></i>
                        <span id="record-count">0</span> Koleksi
                    </span>
                    <button type="button" class="btn btn-success btn-sm" onclick="submission()">
                        <i class="ph-paper-plane-tilt me-1"></i>
                        Ajukan Verifikasi
                    </button>
                </div>
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
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-info me-1"></i>
                                Status
                            </th>
                            <th class="text-nowrap" style="min-width: 250px">
                                <i class="ph-book-open me-1"></i>
                                Judul
                            </th>
                            <th class="text-nowrap" style="min-width: 150px">
                                <i class="ph-barcode me-1"></i>
                                Identifier
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 120px">
                                <i class="ph-calendar-check me-1"></i>
                                Tgl Upload
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-image me-1"></i>
                                Cover
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-file-pdf me-1"></i>
                                Konten
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-calendar-blank me-1"></i>
                                Waktu Publikasi
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-align-left me-1"></i>
                                Sinopsis
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-map-pin me-1"></i>
                                Kota
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-eye me-1"></i>
                                Preview
                            </th>
                            <th class="text-center text-nowrap" style="min-width: 110px">
                                <i class="ph-lock-key me-1"></i>
                                Akses
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
        loadData();

        var $input = $("#files");
        var isUploading = false;

        if ($input.length === 0) {
            return;
        }

        if ($input.data('fileinput')) {
            $input.fileinput('destroy');
        }

        dragAndDropFile('#files', {
            uploadUrl: '{{ url("digital-storage-handover/single-upload-isbn/uploaded") }}',
            uploadAsync: false,
            showUpload: false,
            showCancel: false,
            autoReplace: false,
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'epub'],
            maxFileSize: 204800,
            showCaption: true,
            showPreview: true,
            dropZoneEnabled: true,
            dropZoneClickable: true,
            dropZoneTitle: '<i class="ph-cloud-arrow-up ph-2x mb-2"></i><br>Drag & drop file di sini atau <span class="text-primary fw-semibold">klik untuk browse</span><br><small class="text-muted">Cover (JPG/PNG) & Konten (PDF/EPUB) - Max 200MB</small>',
            msgPlaceholder: 'Pilih dua atau beberapa file (otomatis upload)',
            uploadExtraData: function() {
                return {
                    _token: '{{ csrf_token() }}'
                };
            },
            ajaxSettings: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            fileActionSettings: {
                showUpload: false,
                showRemove: true,
                showZoom: true,
                showDrag: false,
            }
        });

        setTimeout(function() {
            $input = $("#files");

            $input.on('filebatchselected', function(event, files) {
                if (isUploading) {
                    return;
                }

                var fileCount = 0;

                if (files) {
                    if (typeof files === 'object' && !Array.isArray(files)) {
                        fileCount = Object.keys(files).length;
                    } else if (Array.isArray(files)) {
                        fileCount = files.length;
                    } else if (files.length !== undefined) {
                        fileCount = files.length;
                    }
                }

                if (fileCount > 1) {
                    isUploading = true;

                    setTimeout(function() {
                        $input.fileinput('upload');
                    }, 500);
                }
            });
        }, 300);

        $input.on('filebatchpreupload', function(event, data, previewId, index) {
            var fileCount = 0;

            if (data.files && data.files.length) {
                fileCount = data.files.length;
            } else if (data.filescount) {
                fileCount = data.filescount;
            } else if (data.filenames && data.filenames.length) {
                fileCount = data.filenames.length;
            }
        });

        $input.on('filebatchuploadsuccess', function(event, data, previewId, index) {
            isUploading = false;

            const response = data.response;
            let errMessage = '';

            if(response.error && response.error.length > 0) {
                $.each(response.error, function(i, val) {
                    errMessage += '<li class="text-start">' + val + '</li>';
                });
            }

            var swalHtml = `
                <div class="form-group">
                    <p class="mb-2">${response.message}</p>
                    ${errMessage ? '<ul class="list-unstyled text-start bg-light rounded p-3 mb-0">' + errMessage + '</ul>' : ''}
                </div>
            `;

            if(response.code == 200) {
                onReloadTable();

                swalInit.fire({
                    title: '<i class="ph-check-circle text-success"></i> Berhasil',
                    html: swalHtml,
                    icon: 'success',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: '<i class="ph-check me-1"></i> Oke',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then((result) => {
                    $input.fileinput('clear');
                    $input.fileinput('unlock');
                });
            } else {
                swalInit.fire({
                    title: '<i class="ph-warning text-warning"></i> Perhatian',
                    html: swalHtml,
                    icon: 'warning',
                    showCloseButton: true,
                    confirmButtonText: '<i class="ph-check me-1"></i> Mengerti',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });

                $input.fileinput('clear');
                $input.fileinput('unlock');
            }
        });

        $input.on('filebatchuploaderror', function(event, data, msg) {
            isUploading = false;

            var errorMsg = 'Terjadi kesalahan saat upload';
            var errorDetails = [];

            if (data.jqXHR && data.jqXHR.responseJSON) {
                var response = data.jqXHR.responseJSON;
                errorMsg = response.message || errorMsg;

                if (response.error && Array.isArray(response.error) && response.error.length > 0) {
                    errorDetails = response.error;
                }
            } else if (msg) {
                errorMsg = msg;
            }

            var swalHtml = `
                <div class="alert alert-danger border-0 text-start">
                    <div class="d-flex align-items-start">
                        <i class="ph-x-circle me-1 mt-1"></i>
                        <div>${errorMsg}</div>
                    </div>
                </div>
            `;

            if (errorDetails.length > 0) {
                swalHtml += '<div class="text-start"><h6 class="fw-semibold mb-2">Detail Error:</h6><ul class="mb-0">';

                errorDetails.forEach(function(err) {
                    swalHtml += '<li class="text-muted small mb-1">' + err + '</li>';
                });

                swalHtml += '</ul></div>';
            }

            swalInit.fire({
                title: '<i class="ph-warning-circle text-danger"></i> Upload Gagal',
                html: swalHtml,
                icon: 'error',
                showCloseButton: true,
                confirmButtonText: '<i class="ph-check me-1"></i> Mengerti',
                footer: '<div class="alert alert-info border-0 mb-0 small"><i class="ph-info me-1"></i> Pastikan setiap ISBN memiliki Cover (jpg/png) dan Konten (pdf/epub) dengan nama file yang sama</div>',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });

            $input.fileinput('clear');
            $input.fileinput('unlock');
        });

        $input.on('fileclear', function(event) {
            isUploading = false;
        });
    });

    function onReloadTable() {
        if (window.gDataTable) {
            window.gDataTable.ajax.reload(null, false);
        }
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
                url: '{{ url("digital-storage-handover/single-upload-isbn/datatable") }}',
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
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-nowrap' },
                { orderable: true, className: 'align-middle text-center text-nowrap' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-center' },
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

    function submission() {
        var notyConfirm = new Noty({
            text: `
                <div class="text-center py-2">
                    <div class="form-group">
                        <i class="ph-paper-plane-tilt ph-3x text-success"></i>
                    </div>
                    <h5 class="text-dark fw-semibold mb-2">Ajukan Verifikasi?</h5>
                    <p class="text-muted mb-0">Data akan diproses verifikasi oleh admin Perpusnas</p>
                </div>
            `,
            timeout: false,
            modal: true,
            layout: 'center',
            closeWith: 'button',
            type: 'confirm',
            buttons: [
                Noty.button('<i class="ph-x me-1"></i> Batal', 'btn btn-light', function () {
                    notyConfirm.close();
                }),
                Noty.button('<i class="ph-check me-1"></i> Ya, Ajukan', 'btn btn-success ms-2', function () {
                    $.ajax({
                        url: '{{ url("digital-storage-handover/single-upload-isbn/submission") }}',
                        type: 'POST',
                        dataType: 'JSON',
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
                                    title: '<i class="ph-warning-circle text-danger"></i> Error',
                                    text: response.message,
                                    icon: 'error',
                                    showCloseButton: false,
                                    confirmButtonText: '<i class="ph-check me-1"></i> Oke',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
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

    function destroyData(id) {
        var notyConfirm = new Noty({
            text: `
                <div class="text-center py-2">
                    <div class="form-group">
                        <i class="ph-trash ph-3x text-danger"></i>
                    </div>
                    <h5 class="text-dark fw-semibold mb-2">Hapus Data?</h5>
                    <p class="text-muted mb-0">Data yang telah dihapus tidak bisa dikembalikan lagi</p>
                </div>
            `,
            timeout: false,
            modal: true,
            layout: 'center',
            closeWith: 'button',
            type: 'confirm',
            buttons: [
                Noty.button('<i class="ph-x me-1"></i> Batal', 'btn btn-light', function () {
                    notyConfirm.close();
                }),
                Noty.button('<i class="ph-trash me-1"></i> Hapus', 'btn btn-danger ms-2', function () {
                    $.ajax({
                        url: '{{ url("digital-storage-handover/single-upload-isbn/destroy-data") }}',
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
                                    title: '<i class="ph-warning-circle text-danger"></i> Error',
                                    text: response.message,
                                    icon: 'error',
                                    showCloseButton: false,
                                    confirmButtonText: '<i class="ph-check me-1"></i> Oke',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
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
