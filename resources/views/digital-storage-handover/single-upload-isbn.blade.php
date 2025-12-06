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
        <div class="card-header">
            <h5 class="hstack gap-2 mb-0">Upload File Koleksi</h5>
        </div>
        <div class="card-body">
            <form id="form-upload">
                <input type="file" name="files[]" id="files" multiple data-show-upload="false" data-show-caption="true">
            </form>
            <hr class="py-0">
            <div class="text-end">
                <button type="button" class="btn btn-primary" onclick="submitted()">
                    <i class="ph-upload me-1"></i>
                    Submit
                </button>
            </div>
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

        dragAndDropFile('#files', {
            showUpload: false,
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'epub'],
            maxFileSize: 208400,
            browseLabel: "Pilih Semua File (Cover & Konten)",
            dropZoneTitle: 'Drag & Drop semua file di sini (semua Cover dan semua Konten)',
            showCaption: true,
        });
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

    function submitted() {
        $.ajax({
            url: '{{ url("digital-storage-handover/single-upload-isbn/uploaded") }}',
            type: 'POST',
            dataType: 'JSON',
            data: new FormData($('#form-upload')[0]),
            contentType: false,
            processData: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                onLoading('show', 'body');
            },
            success: function(response) {
                onLoading('close', 'body');

                let errMessage = '';

                if(response.error) {
                    $.each(response.error, function(i, val) {
                        errMessage += '<li>' + val + '</li>';
                    });
                }

                var swalHtml = `
                    ${response.message}<br>
                    <ul class="mb-0 text-start justify-content-start mt-2">${errMessage}</ul>
                `;

                if(response.code == 200) {
                    swalInit.fire({
                        title: 'Berhasil',
                        html: swalHtml,
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Oke',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onLoading('show', 'body');

                            location.href = '{{ url("digital-storage-handover/single-upload-isbn") }}';
                        }
                    });
                } else {
                    onLoading('close', 'body');

                    swalInit.fire({
                        title: 'Oops ...',
                        html: swalHtml,
                        icon: 'info',
                        showCloseButton: true
                    });
                }
            },
            error: function(response) {
                onLoading('close', 'body');
                responseError(response);
            }
        });
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
