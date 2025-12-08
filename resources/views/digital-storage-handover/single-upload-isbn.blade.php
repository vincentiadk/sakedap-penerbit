<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - <span class="fw-normal">Unggah Tunggal ISBN</span>
            </h4>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="card">
        <div class="card-header d-flex align-items-center py-0">
            <h6 class="py-3 mb-0">Upload File Koleksi</h6>
            <div class="ms-auto my-auto">
                <a href="{{ asset('assets/Panduan Penggunaan Aplikasi Sakedap - Unggah Buku ISBN.pdf') }}" class="btn btn-teal" target="_blank">
                    <i class="ph-eye me-1"></i>
                    Lihat Panduan (PDF)
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="form-upload">
                <input type="file" name="files[]" id="files" multiple data-show-upload="false" data-show-caption="true" multiple>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center py-0">
            <h6 class="py-3 mb-0">Daftar Koleksi</h6>
            <div class="ms-auto my-auto">
                <button type="button" class="btn btn-success" onclick="submission()">
                    <i class="ph-checks me-1"></i>
                    Ajukan Verifikasi
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover w-100 display" id="datatable-serverside">
                <thead class="text-bg-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap"><i class="ph-gear"></i></th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Judul</th>
                        <th class="text-nowrap">Kode</th>
                        <th class="text-nowrap">Tgl Upload</th>
                        <th class="text-nowrap">File Cover</th>
                        <th class="text-nowrap">File Konten</th>
                        <th class="text-nowrap">Waktu Terbit</th>
                        <th class="text-nowrap">Sinopsis</th>
                        <th class="text-nowrap">Kota</th>
                        <th class="text-nowrap">Preview</th>
                        <th class="text-nowrap">Akses</th>
                    </tr>
                </thead>
            </table>
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
            dropZoneTitle: 'Drag & drop file di sini atau <span class="text-primary">klik untuk browse</span>',
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
                    errMessage += '<li>' + val + '</li>';
                });
            }

            var swalHtml = `
                ${response.message}<br>
                ${errMessage ? '<ul class="mb-0 text-start justify-content-start mt-2">' + errMessage + '</ul>' : ''}
            `;

            if(response.code == 200) {
                onReloadTable();

                swalInit.fire({
                    title: 'Berhasil',
                    html: swalHtml,
                    icon: 'success',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Oke',
                }).then((result) => {
                    $input.fileinput('clear');
                    $input.fileinput('unlock');
                });
            } else {
                swalInit.fire({
                    title: 'Oops ...',
                    html: swalHtml,
                    icon: 'warning',
                    showCloseButton: true
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

            var swalHtml = errorMsg;

            if (errorDetails.length > 0) {
                swalHtml += '<ul class="mb-0 text-start mt-3">';

                errorDetails.forEach(function(err) {
                    swalHtml += '<li class="text-muted small">' + err + '</li>';
                });

                swalHtml += '</ul>';
            }

            swalInit.fire({
                title: 'Upload Gagal',
                html: swalHtml,
                icon: 'warning',
                showCloseButton: true,
                footer: '<small class="text-muted">Pastikan setiap ISBN memiliki Cover (jpg/png) dan Konten (pdf/epub) dengan nama file yang sama</small>'
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
                { orderable: true, className: 'align-middle text-center' },
                { orderable: false, className: 'align-middle text-center' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle text-wrap' },
                { orderable: true, className: 'align-middle' },
                { orderable: true, className: 'align-middle' },
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
            },
        }).on('draw.dt', function() {
            onLoading('close', '#datatable-serverside_wrapper');
        });

        window.gDataTable.columns.adjust().draw();
    }

    function submission() {
        var notyConfirm = new Noty({
            text: '<div class="mb-3"><h5 class="text-dark">Ajukan Verifikasi?</h5><span class="text-muted">Data akan diproses verifikasi oleh admin perpusnas</span></div>',
            timeout: false,
            modal: true,
            layout: 'center',
            closeWith: 'button',
            type: 'confirm',
            buttons: [
                Noty.button('Tidak', 'btn btn-light', function () {
                    notyConfirm.close();
                }),
                Noty.button('Ya, Ajukan', 'btn btn-success ms-2', function () {
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
