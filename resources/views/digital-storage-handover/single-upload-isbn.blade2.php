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
                        <p class="mb-2">
                            Anda dapat mengunggah <strong>beberapa file sekaligus</strong> pada area di bawah ini.
                            Sistem akan memproses setiap file secara otomatis dan berurutan.
                        </p>

                        <ul class="mb-0 small">
                            <li><strong>Nama file wajib mengandung ISBN</strong> (boleh memakai tanda <code>-</code>). Contoh: <code>9786236870600.pdf</code> atau <code>978-623-687-060-0.jpg</code></li>
                            <li><strong>Cover</strong>: JPG/PNG (Max 2MB)</li>
                            <li><strong>Konten</strong>: PDF/EPUB/MP3/MP4 (Max 200MB per file)</li>
                        </ul>

                        <hr class="my-2">

                        <div class="small text-muted">
                            <div class="fw-semibold mb-1">Aturan Pemrosesan</div>
                            <ol class="mb-0 ps-3">
                                <li>Apabila karya digital ber-ISBN yang diunggah <strong>telah diterima</strong>, maka file tidak dapat diperbarui.</li>
                                <li>Apabila karya digital ber-ISBN yang diunggah <strong>sedang dalam proses peninjauan</strong>, maka file tidak dapat diperbarui.</li>
                                <li>Apabila karya digital ber-ISBN yang diunggah berstatus <strong>bermasalah</strong>, silakan lakukan perbaikan melalui fitur <strong>Koleksi Bermasalah</strong> sebelum mengunggah kembali.</li>
                                <li>Apabila karya digital ber-ISBN masih berstatus <strong>draft</strong> atau berada pada tabel pemrosesan ISBN di bawah ini, maka file yang diunggah akan <strong>menggantikan</strong> file sebelumnya sesuai dengan jenis file (cover atau konten).</li>
                            </ol>
                        </div>
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
    /*
    $(function () {
        loadData();

        var $input = $("#files");
        if ($input.length === 0) return;

        // ===== state queue =====
        let uploading = false;  // sedang upload batch sekarang
        let queued = false;     // ada tambahan file saat upload jalan
        let allLogs = [];       // kumpulin logs dari server (per file)
        let lastMessage = null; // message terakhir dari server
        let lastCode = 200;     // track code terakhir

        // destroy kalau sudah pernah init
        if ($input.data('fileinput')) {
            $input.fileinput('destroy');
        }

        // ===== INIT fileinput (dragAndDropFile wrapper kamu) =====
        dragAndDropFile('#files', {
            uploadUrl: '{{ url("digital-storage-handover/single-upload-isbn/uploaded") }}',

            uploadAsync: false,

            showUpload: false,
            showCancel: false,
            autoReplace: false,

            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'epub', 'mp3', 'mp4'],
            maxFileSize: 204800, // 200MB per file
            showCaption: true,
            showPreview: true,
            dropZoneEnabled: true,
            dropZoneClickable: true,
            dropZoneTitle:
            '<i class="ph-cloud-arrow-up ph-2x mb-2"></i><br>' +
            'Drag & drop file di sini atau <span class="text-primary fw-semibold">klik untuk browse</span><br>' +
            '<small class="text-muted">Cover (JPG/PNG) & Konten (PDF/EPUB/MP3/MP4) - Max 200MB</small>',
            msgPlaceholder: 'Pilih satu atau beberapa file (otomatis upload)',
            uploadExtraData: function () {
            return { _token: '{{ csrf_token() }}' };
            },
            ajaxSettings: {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            },
            fileActionSettings: {
            showUpload: false,
            showRemove: true,
            showZoom: true,
            showDrag: false,
            }
        });

        // ===== helper upload =====
        function startUpload() {
            // jangan dipanggil kalau sedang upload
            if (uploading) return;

            const count = $input.fileinput('getFilesCount');
            if (!count || count <= 0) return;

            uploading = true;
            setTimeout(function () {
            $input.fileinput('upload');
            }, 150);
        }

        // ====== EVENT: user pilih/drop batch ======
        // Ini kepanggil setiap kali user tambah file lewat browse atau drop
        $input.on('filebatchselected', function () {
            //if (uploading) {
            // sedang upload, tapi user nambah file → tandai antrean
            //queued = true;
            // gak usah upload sekarang, nanti lanjut setelah batch selesai
            //return;
            //}
            startUpload();
        });

        // ====== EVENT: mulai upload ======
        $input.on('filebatchpreupload', function () {
            uploading = true;
        });

        // ====== EVENT: tiap file selesai diupload (async) ======
        $input.on('fileuploaded', function (event, data) {
            const res = (data && data.response) ? data.response : {};
            lastCode = res.code ?? lastCode;

            if (res.message) lastMessage = res.message;

            if (res.logs && Array.isArray(res.logs)) {
            allLogs = allLogs.concat(res.logs);
            }

            // refresh table kalau ada yang sukses
            if (res.code == 200) {
            onReloadTable();
            }
        });

        // ====== EVENT: selesai 1 batch upload (sukses / error) ======
        // ini dipanggil setelah upload (yang ada di queue fileinput) kelar
        $input.on('filebatchuploadcomplete', function () {
            uploading = false;

            // kalau ada file ditambahkan saat upload jalan, jalankan upload lagi
            if (queued) {
            queued = false;
            startUpload();
            return;
            }

            // kalau gak ada antrean, tampilkan rekap sekali
            showSummarySwal();
        });

        // ====== EVENT: batch error (misal server down) ======
        $input.on('filebatchuploaderror', function (event, data, msg) {
            uploading = false;

            // tampilkan error (tetap boleh lanjut antrean kalau ada)
            let errorMsg = 'Terjadi kesalahan saat upload';
            if (data && data.jqXHR && data.jqXHR.responseJSON) {
            errorMsg = data.jqXHR.responseJSON.message || errorMsg;
            } else if (msg) {
            errorMsg = msg;
            }

            swalInit.fire({
            title: '<i class="ph-warning-circle text-danger"></i> Upload Gagal',
            html: `<div class="alert alert-danger border-0 text-start mb-0">${errorMsg}</div>`,
            icon: 'error',
            showCloseButton: true,
            confirmButtonText: '<i class="ph-check me-1"></i> Mengerti',
            customClass: { confirmButton: 'btn btn-danger' }
            });

            // kalau ada antrean, lanjut upload lagi
            if (queued) {
            queued = false;
            startUpload();
            }
        });

        // ====== EVENT: user clear ======
        $input.on('fileclear', function () {
            uploading = false;
            queued = false;
            allLogs = [];
            lastMessage = null;
            lastCode = 200;
        });

        // ====== swal rekap ======
        function showSummarySwal() {
            if (!allLogs.length && !lastMessage) {
            // gak ada apa-apa yang perlu ditampilkan
            return;
            }

            let listHtml = '';
            allLogs.forEach(function (val) {
            let itemClass = 'text-muted';
            if (val.startsWith('[OK]')) itemClass = 'text-success';
            else if (val.startsWith('[REJECT]') || val.startsWith('[ERROR]')) itemClass = 'text-danger';
            else if (val.startsWith('[SKIP]')) itemClass = 'text-warning';

            listHtml += `<li class="text-start ${itemClass}">${val}</li>`;
            });

            const swalHtml = `
            <div class="form-group">
                <p class="mb-2">${lastMessage || 'Upload selesai.'}</p>
                ${listHtml ? `<ul class="list-unstyled text-start bg-light rounded p-3 mb-0">${listHtml}</ul>` : ''}
            </div>
            `;

            // success kalau ada minimal 1 OK
            const hasOk = allLogs.some(x => x.startsWith('[OK]'));
            const icon = hasOk ? 'success' : (lastCode == 200 ? 'info' : 'warning');
            const title = hasOk
            ? '<i class="ph-check-circle text-success"></i> Selesai'
            : '<i class="ph-info text-primary"></i> Info';

            swalInit.fire({
            title: title,
            html: swalHtml,
            icon: icon,
            showCloseButton: true,
            confirmButtonText: '<i class="ph-check me-1"></i> Oke',
            customClass: { confirmButton: 'btn btn-success' }
            }).then(() => {
            // bersihin preview setelah user klik OK
            $input.fileinput('clear');
            $input.fileinput('unlock');

            // reset accumulator
            allLogs = [];
            lastMessage = null;
            lastCode = 200;
            });
        }
        });

    
        function onReloadTable() {
        if (window.gDataTable) {
            window.gDataTable.ajax.reload(null, false);
        }
    }
    */
$(function () {
    loadData();

    var $input = $("#files");
    if ($input.length === 0) return;

    let uploading = false;

    if ($input.data('fileinput')) {
        $input.fileinput('destroy');
    }
    function onReloadTable() {
        if (window.gDataTable) {
            window.gDataTable.ajax.reload(null, false);
        }
    }
    dragAndDropFile('#files', {
        uploadUrl: '{{ url("digital-storage-handover/single-upload-isbn/uploaded") }}',
        uploadAsync: false,
        showUpload: false,
        showCancel: false,
        autoReplace: false,
        allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'epub', 'mp3', 'mp4'],
        maxFileSize: 204800,
        showCaption: true,
        showPreview: true,
        dropZoneEnabled: true,
        dropZoneClickable: true,
        dropZoneTitle:
            '<i class="ph-cloud-arrow-up ph-2x mb-2"></i><br>' +
            'Drag & drop file di sini atau <span class="text-primary fw-semibold">klik untuk browse</span><br>' +
            '<small class="text-muted">Cover (JPG/PNG) & Konten (PDF/EPUB/MP3/MP4) - Max 200MB</small>',
        msgPlaceholder: 'Pilih satu atau beberapa file (otomatis upload)',
        uploadExtraData: function () {
            return { _token: '{{ csrf_token() }}' };
        },
        ajaxSettings: {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        },
        fileActionSettings: {
            showUpload: false,
            showRemove: true,
            showZoom: true,
            showDrag: false,
        }
    });

    function startUpload() {
        if (uploading) return;

        const count = $input.fileinput('getFilesCount');
        if (!count || count <= 0) return;

        uploading = true;
        setTimeout(function () {
            $input.fileinput('upload');
        }, 150);
    }

    $input.on('filebatchselected', function () {
        startUpload();
    });

    $input.on('filebatchpreupload', function () {
        uploading = true;
    });

    $input.on('filebatchuploadsuccess', function (event, data) {
        uploading = false;

        const res = (data && data.response) ? data.response : {};
        const logs = Array.isArray(res.logs) ? res.logs : [];
        const message = res.message || 'Upload selesai.';
        const hasOk = logs.some(x => x.startsWith('[OK]'));

        if (res.code == 200) {
            onReloadTable();
        }

        let listHtml = '';
        logs.forEach(function (val) {
            let itemClass = 'text-muted';
            if (val.startsWith('[OK]')) itemClass = 'text-success';
            else if (val.startsWith('[REJECT]') || val.startsWith('[ERROR]')) itemClass = 'text-danger';
            else if (val.startsWith('[SKIP]')) itemClass = 'text-warning';

            listHtml += `<li class="text-start ${itemClass}">${val}</li>`;
        });

        swalInit.fire({
            title: hasOk
                ? '<i class="ph-check-circle text-success"></i> Selesai'
                : '<i class="ph-info text-primary"></i> Info',
            html: `
                <div class="form-group">
                    <p class="mb-2">${message}</p>
                    ${listHtml ? `<ul class="list-unstyled text-start bg-light rounded p-3 mb-0">${listHtml}</ul>` : ''}
                </div>
            `,
            icon: hasOk ? 'success' : 'info',
            showCloseButton: true,
            confirmButtonText: '<i class="ph-check me-1"></i> Oke',
            customClass: { confirmButton: 'btn btn-success' }
        }).then(() => {
            $input.fileinput('clear');
            $input.fileinput('unlock');
        });
    });

    $input.on('filebatchuploaderror', function (event, data, msg) {
        uploading = false;

        let errorMsg = 'Terjadi kesalahan saat upload';
        if (data && data.jqXHR && data.jqXHR.responseJSON) {
            errorMsg = data.jqXHR.responseJSON.message || errorMsg;
        } else if (msg) {
            errorMsg = msg;
        }

        swalInit.fire({
            title: '<i class="ph-warning-circle text-danger"></i> Upload Gagal',
            html: `<div class="alert alert-danger border-0 text-start mb-0">${errorMsg}</div>`,
            icon: 'error',
            showCloseButton: true,
            confirmButtonText: '<i class="ph-check me-1"></i> Mengerti',
            customClass: { confirmButton: 'btn btn-danger' }
        });
    });

    $input.on('fileclear', function () {
        uploading = false;
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
                url: '{{ url("digital-storage-handover/single-upload-isbn/datatable") }}',
                dataType: 'JSON',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
