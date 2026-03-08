<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Digital - Koleksi Ditolak - <span class="fw-normal">Detail</span>
            </h4>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page-header">
            <div class="d-sm-flex align-items-center mb-lg-0 ms-lg-3">
                <div class="d-inline-flex mt-3 mt-sm-0 gap-2">
                    <button type="button" class="btn btn-secondary" onclick="lookupCatalogHistory('E_COLLECTIONS', {{ $collection->ID }})">
                        <i class="ph-books me-1"></i>
                        Histori E-Collection
                    </button>
                    <a href="{{ url('digital-storage-handover/reject') }}" class="btn btn-primary">
                        <i class="ph-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert alert-danger d-none" id="validation-element">
        <ul class="mb-0" id="validation-data"></ul>
    </div>
    <form id="form-data">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold text-danger">
                    <i class="ph-warning-circle me-1"></i>
                    Status Penolakan
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-danger border-0 mb-0">
                    <div class="d-flex align-items-start">
                        <i class="ph-x-circle ph-2x me-3"></i>
                        <div class="flex-fill">
                            <h6 class="alert-heading fw-semibold mb-2">Alasan Ditolak:</h6>
                            <p class="mb-0">{{ $collection->REJECT ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-warning-circle me-1 text-warning"></i>
                        Histori Masalah
                    </h5>
                    <div>
                        @if($collection->REVISION_COUNT)
                            <span class="badge bg-danger bg-opacity-10 text-danger p-2">
                                <i class="ph-arrow-clockwise me-1"></i>
                                {{ $collection->REVISION_COUNT }} Kali Revisi
                            </span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info p-2">
                                <i class="ph-check-circle me-1"></i>
                                Belum Ada Revisi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Masalah</th>
                                <th style="width: 200px;">Tanggal</th>
                                <th style="width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($collectionProblemHistory || $collection->PROBLEM)
                                @if($collectionProblemHistory)
                                    @foreach($collectionProblemHistory as $cph)
                                        <tr>
                                            <td>{{ $cph->NAME_PROBLEM }}</td>
                                            <td>{{ $cph->CREATED_AT ? Carbon::parse($cph->CREATED_AT)->isoFormat('dddd, D MMMM Y') : '-' }}</td>
                                            <td>
                                                @if($cph->SOLVED == 1)
                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                        <i class="ph-check me-1"></i>
                                                        Telah Diperbaiki
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                                        <i class="ph-clock me-1"></i>
                                                        Belum Diperbaiki
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($collection->PROBLEM)
                                    <tr class="table-active">
                                        <td colspan="3">
                                            <strong>Catatan:</strong> {{ $collection->PROBLEM }}
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="ph-info me-1"></i>
                                        Tidak ada data histori masalah
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-user-circle me-1 text-primary"></i>
                    Pelaksana Serah
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        {{ $collection->NAME_PENERBIT }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-folders me-1 text-success"></i>
                    Jenis Bahan
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 mb-0">
                    <i class="ph-info me-1"></i>
                    {{ $collection->ALIAS_WORKSHEET }} ({{ $collection->CATEGORY_WORKSHEET }})
                </div>
            </div>
        </div>
        @if($collection->TITLE_PARENT)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-tree-structure me-1 text-info"></i>
                        Parent (Induk Koleksi)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ph-link"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ $collection->TITLE_PARENT }}" readonly>
                    </div>
                </div>
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-note-pencil me-1 text-primary"></i>
                    Meta Data Koleksi
                </h5>
            </div>
            <div class="card-body">
                @if($collection->TITLE_PARENT)
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 fw-semibold">
                            <i class="ph-newspaper me-1"></i>
                            Edisi
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="{{ $collection->EDITION }}" readonly>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 fw-semibold">
                            <i class="ph-calendar-check me-1"></i>
                            Tanggal Terbit Edisi
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-calendar-blank"></i>
                                </span>
                                <input type="text" class="form-control" value="{{ $collection->EDITION_DATE ? date('d/m/Y', strtotime($collection->EDITION_DATE)) : '' }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-folders me-1"></i>
                        Jenis Koleksi
                    </label>
                    <div class="col-md-9">
                        <select class="form-select" readonly>
                            @foreach($media as $m)
                                <option value="{{ $m->ID }}" {{ $collection->COLLECTION_MEDIA_ID == $m->ID ? 'selected' : '' }}>{{ $m->NAME }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-text-aa me-1"></i>
                        Judul
                    </label>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="3" readonly>{{ $collection->TITLE }}</textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-barcode me-1"></i>
                        Identifier
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select class="form-select w-auto flex-grow-0" readonly style="max-width: 150px;">
                                <option value="">Tidak Ada</option>
                                <option value="1" {{ $collection->CODE_TYPE == 1 ? 'selected' : '' }}>ISBN</option>
                                <option value="2" {{ $collection->CODE_TYPE == 2 ? 'selected' : '' }}>ISMN</option>
                                <option value="3" {{ $collection->CODE_TYPE == 3 ? 'selected' : '' }}>ISRC</option>
                                <option value="4" {{ $collection->CODE_TYPE == 4 ? 'selected' : '' }}>ISSN</option>
                                <option value="5" {{ $collection->CODE_TYPE == 5 ? 'selected' : '' }}>ISAN</option>
                            </select>
                            <input type="text" class="form-control" value="{{ $collection->CODE }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-qr-code me-1"></i>
                        QRCBN
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <div class="form-check form-check-inline mb-0">
                                    <input type="checkbox" class="form-check-input" {{ $collection->QRCBN ? '' : 'checked' }} readonly>
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                            </span>
                            <input type="text" class="form-control" value="{{ $collection->QRCBN }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-books me-1"></i>
                        Seri
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <div class="form-check form-check-inline mb-0">
                                    <input type="checkbox" class="form-check-input" {{ $collection->SERIES ? '' : 'checked' }} readonly>
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                            </span>
                            <input type="text" class="form-control" value="{{ $collection->SERIES }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-clock-clockwise me-1"></i>
                        Kala Terbit
                    </label>
                    <div class="col-md-9">
                        <select class="form-select" readonly>
                            <option value="">Tidak Ada</option>
                            <option value="1" {{ $collection->SERIAL == 1 ? 'selected' : '' }}>Harian</option>
                            <option value="2" {{ $collection->SERIAL == 2 ? 'selected' : '' }}>Mingguan</option>
                            <option value="3" {{ $collection->SERIAL == 3 ? 'selected' : '' }}>Bulanan</option>
                            <option value="4" {{ $collection->SERIAL == 4 ? 'selected' : '' }}>3 Bulan Sekali</option>
                            <option value="5" {{ $collection->SERIAL == 5 ? 'selected' : '' }}>4 Bulan Sekali</option>
                            <option value="6" {{ $collection->SERIAL == 6 ? 'selected' : '' }}>6 Bulan Sekali</option>
                            <option value="7" {{ $collection->SERIAL == 7 ? 'selected' : '' }}>Tahunan</option>
                            <option value="8" {{ $collection->SERIAL == 8 ? 'selected' : '' }}>2 Tahun Sekali</option>
                            <option value="9" {{ $collection->SERIAL == 9 ? 'selected' : '' }}>3 Tahun Sekali</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-calendar me-1"></i>
                        Waktu Publikasi
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-calendar-blank"></i>
                            </span>
                            <input type="text" class="form-control" value="{{ ($collection->PUBLICATION_DAY && $collection->PUBLICATION_MONTH && $collection->PUBLICATION_YEAR) ? $collection->PUBLICATION_YEAR . '/' . $collection->PUBLICATION_MONTH . '/' . $collection->PUBLICATION_DAY : '' }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-eye me-1"></i>
                        Preview
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" value="{{ $collection->PREVIEW }}" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-currency-circle-dollar me-1"></i>
                        Harga Jual
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <select class="form-select w-auto flex-grow-0" readonly style="max-width: 120px;">
                                <option value="{{ $collection->CURRENCY }}" selected>{{ $collection->CURRENCY }}</option>
                            </select>
                            <input type="number" class="form-control" value="{{ $collection->PRICE }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-book me-1"></i>
                        Jilid
                    </label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" value="{{ $collection->JILID }}" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-book-open me-1"></i>
                        Keterangan Fisik
                    </label>
                    <div class="col-md-9">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Total Halaman / Durasi</span>
                                    <input type="number" class="form-control" value="{{ isset($physicalDescription->paging) ? $physicalDescription->paging : '' }}" readonly>
                                    <select class="form-select flex-grow-0 w-auto" readonly style="max-width: 120px;">
                                        <option value="Halaman" {{ isset($physicalDescription->paging_flag) ? ($physicalDescription->paging_flag == 'Halaman' ? 'selected' : '') : '' }}>Halaman</option>
                                        <option value="Menit" {{ isset($physicalDescription->paging_flag) ? ($physicalDescription->paging_flag == 'Menit' ? 'selected' : '') : '' }}>Menit</option>
                                        <option value="Jam" {{ isset($physicalDescription->paging_flag) ? ($physicalDescription->paging_flag == 'Jam' ? 'selected' : '') : '' }}>Jam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Ilustrasi</span>
                                    <input type="text" class="form-control" value="{{ isset($physicalDescription->ill) ? $physicalDescription->ill : '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Ukuran / Dimensi</span>
                                    <input type="text" class="form-control" value="{{ isset($physicalDescription->sizes) ? $physicalDescription->sizes : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group mb-0">
                    <label class="col-form-label col-md-3 fw-semibold">
                        <i class="ph-article me-1"></i>
                        Sinopsis
                    </label>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="5" readonly>{{ $collection->DESCRIPTION }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-lock-key me-1 text-warning"></i>
                    Pengaturan Akses
                </h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input type="radio" class="form-check-input" name="access" id="access-1" value="1" {{ $collection->AKSES == 1 ? 'checked' : '' }} readonly>
                    <label class="form-check-label" for="access-1">
                        <strong>Akses Full</strong>
                        <div class="small text-muted">Akses full file berwatermak secara online</div>
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="radio" class="form-check-input" name="access" id="access-2" value="2" {{ $collection->AKSES == 2 ? 'checked' : '' }} readonly>
                    <label class="form-check-label" for="access-2">
                        <strong>Akses Preview + LAN</strong>
                        <div class="small text-muted">Akses hanya preview file secara online, namun tetap dapat di dayagunakan di lingkungan perpustakaan nasional RI dengan jaringan internet LAN</div>
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="radio" class="form-check-input" name="access" id="access-3" value="3" {{ $collection->AKSES == 3 ? 'checked' : '' }} readonly>
                    <label class="form-check-label" for="access-3">
                        <strong>Akses Preview + Embargo 5 Tahun</strong>
                        <div class="small text-muted">Akses hanya file preview secara online, dan tidak didayagunakan di lingkungan Perpustakaan Nasional RI selama 5 tahun sejak diserahkan. Setelah 5 tahun, akan didayagunakan oleh Perpustakaan Nasional RI di jaringan internet LAN</div>
                    </label>
                </div>
                <div class="form-check mb-0">
                    <input type="radio" class="form-check-input" name="access" id="access-4" value="4" {{ $collection->AKSES == 4 ? 'checked' : '' }} readonly>
                    <label class="form-check-label" for="access-4">
                        <strong>Akses Preview Saja</strong>
                        <div class="small text-muted">Akses hanya file preview secara online selamanya dan tidak didayagunakan dimana pun</div>
                    </label>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-tag me-1 text-danger"></i>
                    Kategori
                </h5>
            </div>
            <div class="card-body">
                <div id="category-content"></div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-users me-1 text-primary"></i>
                    Kontributor
                </h5>
            </div>
            <div class="card-body">
                <select class="form-select" name="author[]" id="author" multiple readonly>
                    @if($collectionContributor)
                        @foreach($collectionContributor as $cc)
                            <option value="{{ $cc }}" selected>{{ $cc }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-file me-1 text-success"></i>
                    File Koleksi
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="border rounded p-3">
                            <h6 class="fw-semibold">
                                <i class="ph-image me-1 text-primary"></i>
                                Cover
                            </h6>
                            <div class="alert alert-info border-0">
                                <div class="small">
                                    <div class="mb-1"><strong>Hash:</strong> {{ $collection->HASH_CATALOGCOVERS ?? '-' }}</div>
                                    <div class="mb-1"><strong>Mime Type:</strong> {{ $collection->MIME_CATALOGCOVERS ?? '-' }}</div>
                                    <div class="mb-1"><strong>Ukuran:</strong> {{ Main::formatFileSize($collection->FILE_SIZE_CATALOGCOVERS ?? 0) }}</div>
                                    <div><strong>Metode:</strong> {{ Main::method($collection->METHOD_CATALOGCOVERS ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <img src="" class="img-fluid rounded shadow-sm" id="file-cover" style="object-fit: contain; max-width: 100%; max-height: 600px;" alt="Cover Catalog">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border rounded p-3">
                            <h6 class="fw-semibold">
                                <i class="ph-file-pdf me-1 text-danger"></i>
                                Konten
                            </h6>
                            <div class="alert alert-info border-0">
                                <div class="small">
                                    <div class="mb-1"><strong>Hash:</strong> {{ $collection->HASH_CATALOGFILES ?? '-' }}</div>
                                    <div class="mb-1"><strong>Mime Type:</strong> {{ $collection->MIME_CATALOGFILES ?? '-' }}</div>
                                    <div class="mb-1"><strong>Ukuran:</strong> {{ Main::formatFileSize($collection->FILE_SIZE_CATALOGFILES ?? 0) }}</div>
                                    <div><strong>Metode:</strong> {{ Main::method($collection->METHOD_CATALOGFILES ?? 0) }}</div>
                                </div>
                            </div>
                            <div id="viewer-wrapper" class="rounded shadow-sm" style="position: relative; width: 100%; min-height: 400px; background: #f5f5f5; border: 1px solid #ddd;">
                                <div id="viewer-content" style="width: 100%; height: 100%;">
                                    <div id="pdf-viewer-container" style="overflow: auto; max-height: 600px; background: #525659; padding: 20px 0; display: none;"></div>
                                    <video id="video-player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" style="display: none; width: 100%; height: 450px;"></video>
                                    <div id="epub-container" style="display: none; height: 600px; width: 100%; background: white;"></div>
                                    <div id="epub-controls" style="display: none; position: absolute; top: 50%; left: 0; width: 100%; justify-content: space-between; padding: 0 20px; pointer-events: none; z-index: 10000; transform: translateY(-50%);">
                                        <button type="button" id="prev-btn" class="btn btn-dark btn-sm rounded-circle shadow" style="width: 40px; height: 40px; pointer-events: auto; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph-caret-left" style="font-size: 20px;"></i>
                                        </button>
                                        <button type="button" id="next-btn" class="btn btn-dark btn-sm rounded-circle shadow" style="width: 40px; height: 40px; pointer-events: auto; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph-caret-right" style="font-size: 20px;"></i>
                                        </button>
                                    </div>
                                    <div id="audio-wrapper" style="display: none; height: 600px; width: 100%; position: relative; overflow: hidden; border-radius: 8px; background: #000;">
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #023BAD 0%, #06732A 100%); z-index: 1;"></div>
                                        <canvas id="wave-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; opacity: 0.6;"></canvas>
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; display: flex; flex-direction: column; padding: 30px;">
                                            <div class="d-flex justify-content-between text-white align-items-center" style="font-family: sans-serif;">
                                                <span id="timer-current" style="font-variant-numeric: tabular-nums;">0:00</span>
                                                <span class="fw-bold text-uppercase" style="opacity: 0.8; font-size: 14px;">Now Playing</span>
                                                <span id="timer-duration" style="font-variant-numeric: tabular-nums;">--:--</span>
                                            </div>
                                            <div class="flex-grow-1 d-flex align-items-center justify-content-center text-center">
                                                <h3 id="audio-title-display" class="text-white fw-light" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                                    Menyiapkan Audio...
                                                </h3>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center gap-4 pb-4" style="position: relative; z-index: 20;">
                                                <button type="button" class="btn btn-link text-white p-0" id="btn-rewind" title="-10 Detik">
                                                    <i class="ph-rewind" style="font-size: 32px;"></i>
                                                </button>
                                                <div id="play-pause-wrapper" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(5px); border: 2px solid rgba(255,255,255,0.5); cursor: pointer;transition: transform 0.2s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                    <i id="icon-play" class="ph-play text-white" style="font-size: 40px; margin-left: 4px;"></i>
                                                    <i id="icon-pause" class="ph-pause text-white" style="font-size: 40px; display: none;"></i>
                                                </div>
                                                <button type="button" class="btn btn-link text-white p-0" id="btn-forward" title="+10 Detik">
                                                    <i class="ph-fast-forward" style="font-size: 32px;"></i>
                                                </button>
                                            </div>
                                            <div style="position: absolute; bottom: 30px; right: 30px;">
                                                <button type="button" id="btn-mute" class="btn btn-link text-white p-0" style="opacity: 0.6;">
                                                    <i class="ph-speaker-high" id="icon-vol" style="font-size: 24px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="default-message" style="display: flex; height: 600px; align-items: center; justify-content: center;">
                                        <div class="text-center text-muted">
                                            <i class="ph-file ph-3x mb-2 d-block"></i>
                                            <span>Memuat file...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let currentSound = null;
    let watermarkSound = null;
    let animationFrameId = null;
    let audioAnimFrame = null;

    $(function() {
        $('#author').select2({
            multiple: true,
            tags: true,
            tokenSeparators: [';']
        });

        getMedia();
        imageWatermark('#file-cover', '{{ url("stream-file") }}?type=cover&id={{ $collection->ID_CATALOGCOVERS ?? "" }}&filename={{ $collection->FILEURL_CATALOGCOVERS ?? "" }}');
    });

    function getMedia() {
        const media = @json($media ?? []);
        const worksheetId = '{{ $collection->WORKSHEET_ID ?? 0 }}';
        const selectedId = '{{ $collection->COLLECTION_MEDIA_ID ?? 0 }}';

        $('#collection_media_id').html('<option value=""></option>');

        const mediaContent = media.filter(val => val.WORKSHEET_ID == worksheetId).map(val => {
            const selected = (selectedId == val.ID ? 'selected' : '');

            return `<option value="${val.ID}" ${selected}>${val.NAME}</option>`;
        }).join('');

        $('#collection_media_id').append(mediaContent);

        getCategory();
    }

    function getCategory() {
        const category = @json($category ?? []);
        const categoryValue = @json($collectionCategory ?? []);
        const mediaId = $('#collection_media_id').val();
        const selectedIds = new Set(categoryValue.map(v => v.CATEGORY_ID));

        if (!mediaId) {
            $('#category-content').html(`
                <div class="alert alert-info border-0 mb-0">
                    <i class="ph-info me-1"></i>
                    Tidak ada kategori tersedia
                </div>
            `);

            return;
        }

        const filteredCategories = category.filter(val => val.TYPE == mediaId);

        if (filteredCategories.length === 0) {
            $('#category-content').html(`
                <div class="alert alert-warning border-0 mb-0">
                    <i class="ph-warning me-1"></i>
                    Tidak ada kategori untuk jenis koleksi ini
                </div>
            `);

            return;
        }

        const categoryContent = filteredCategories.map((val, index) => {
            const checked = selectedIds.has(val.ID) ? 'checked' : '';

            return `
                <div class="form-check ${index !== filteredCategories.length - 1 ? 'mb-2' : ''}">
                    <input type="checkbox" class="form-check-input" name="category[]" id="category-${val.ID}" value="${val.ID}" ${checked} readonly>
                    <label class="form-check-label" for="category-${val.ID}">${val.NAME}</label>
                </div>
            `;
        }).join('');

        $('#category-content').html(categoryContent);
    }

    $(document).ready(function() {
        const fileUrl = "{{ url('stream-file') }}?type=konten_digital&id={{ $collection->ID_CATALOGFILES ?? '' }}&filename={{ $collection->FILEURL_CATALOGFILES ?? '' }}";
        const rawFilename = "{{ $collection->FILEURL_CATALOGFILES ?? '' }}";
        const fileExtension = rawFilename.split('.').pop().toLowerCase();

        if(rawFilename) {
            loadUniversalViewer(fileUrl, fileExtension);
        } else {
            $('#default-message').html(`
                <div class="text-center">
                    <i class="ph-file-x ph-3x text-muted"></i>
                    <p class="text-muted mb-0">File konten tidak tersedia</p>
                </div>
            `);
        }
    });

    function loadUniversalViewer(url, ext) {
        if (typeof audioAnimFrame !== 'undefined' && audioAnimFrame) {
            cancelAnimationFrame(audioAnimFrame);
        }

        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }

        if (currentSound) {
            currentSound.stop();
            currentSound.unload();
            currentSound = null;
        }

        if (watermarkSound) {
            watermarkSound.unload();
            watermarkSound = null;
        }

        $('#pdf-canvas, #epub-container, #audio-wrapper, #default-message').hide();
        $('#video-player').hide();
        $('#watermark-overlay').css('display', 'flex');

        switch (ext) {
            case 'pdf':
                renderPdf(url);
                break;
            case 'mp4':
            case 'webm':
                renderVideo(url, ext);
                break;
            case 'ogg':
            case 'mp3':
            case 'wav':
                renderAudio(url, ext);
                break;
            case 'epub':
                renderEpub(url);
                break;
            default:
                $('#default-message').show().html(`
                    <div class="text-center">
                        <i class="ph-warning ph-3x text-warning"></i>
                        <p class="text-muted mb-0">Preview tidak didukung untuk format: .${ext}</p>
                    </div>
                `);
                $('#watermark-overlay').hide();
                break;
        }
    }

    function renderPdf(url) {
        const $container = $('#pdf-viewer-container');

        $container.css({
            'display': 'block',
            'width': '100%',
            'height': '600px',
            'max-height': '600px',
            'overflow-y': 'auto',
            'overflow-x': 'hidden',
            'padding': '20px 0',
            'background': '#525659',
            'box-sizing': 'border-box'
        }).empty();

        $('#video-player, #epub-container, #audio-wrapper, #default-message').hide();
        $('#watermark-overlay').hide();

        const checkPdfLib = setInterval(function() {
            if (window.pdfjsLib) {
                clearInterval(checkPdfLib);

                window.pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        renderPage(pdf, pageNum, $container);
                    }
                }).catch(function(error) {
                    $('#default-message').show().html(`
                        <div class="text-center">
                            <i class="ph-x-circle ph-3x text-danger"></i>
                            <p class="text-muted mb-0">Gagal memuat PDF</p>
                        </div>
                    `);
                    $container.hide();
                });
            }
        }, 100);
    }

    function renderPage(pdf, pageNumber, $container) {
        pdf.getPage(pageNumber).then(function(page) {
            let availableWidth = ($container[0].clientWidth || 800) - 40;

            if (availableWidth < 300) availableWidth = 300;

            var unscaledViewport = page.getViewport({ scale: 1 });
            var scale = availableWidth / unscaledViewport.width;
            const viewport = page.getViewport({ scale: scale });

            const $pageWrapper = $('<div/>', {
                class: 'pdf-page-wrapper',
                style: `
                    position: relative;
                    margin: 0 auto 20px auto;
                    display: block;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
                    width: ${viewport.width}px;
                    height: ${viewport.height}px;
                    background-color: white;
                `
            });

            const canvasId = 'pdf-page-' + pageNumber;
            const $canvas = $('<canvas/>', { id: canvasId });
            const canvas = $canvas[0];
            const context = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const watermarkHtml = `
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10;">
                    <div style="position: absolute; bottom: 10px; left: 63px;  transform: rotate(-90deg); transform-origin: bottom left; width: ${viewport.height - 40}px; text-align: left; white-space: nowrap; opacity: 0.4; color: #333; font-family: 'Arial', sans-serif; font-size: 13px;  line-height: 1.5;">
                        <div style="font-weight: bold; text-transform: uppercase;">
                            Pelaksanaan Undang - Undang Nomor 13 Tahun 2018.
                        </div>
                        <div style="font-weight: bold; text-transform: uppercase;">
                            Tentang Serah Simpan Karya Cetak dan Karya Rekam
                        </div>
                        <div style="color: #d9534f; font-weight: bold; display: inline-block;">
                            Peringatan : Dilarang menggandakan, mencetak, mengunduh, atau mendistribusikan kembali tanpa izin.
                        </div>
                    </div>
                </div>
            `;

            $pageWrapper.append($canvas);
            $pageWrapper.append(watermarkHtml);
            $container.append($pageWrapper);

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    }

    function renderVideo(url, ext) {
        Howler.unload();

        if (typeof audioAnimFrame !== 'undefined' && audioAnimFrame)  {
            cancelAnimationFrame(audioAnimFrame);
        }

        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }

        const watermarkSrc = "{{ asset('assets/audio-wm.mp3') }}";

        $('#pdf-canvas, #epub-container, #audio-wrapper, #default-message').hide();

        const $videoEl = $('#video-player');

        $videoEl.show().css({ 'width': '100%', 'height': '600px' });

        $('#video-watermark-layer').remove();

        watermarkSound = new Howl({
            src: [watermarkSrc],
            html5: true,
            loop: true,
            volume: 0.2,
            preload: true
        });

        const syncVideoWatermark = () => {
            if (!watermarkSound || !window.player) return;

            const wmDur = watermarkSound.duration();

            if (wmDur > 0) {
                const currentTime = window.player.currentTime();
                const targetPos = currentTime % wmDur;

                if (Math.abs(watermarkSound.seek() - targetPos) > 0.5) {
                    watermarkSound.seek(targetPos);
                }

                if (!window.player.paused() && !watermarkSound.playing()) {
                    watermarkSound.play();
                }
            }
        };

        if (window.player) {
            window.player.dispose();
        }

        window.player = videojs('video-player', {
            controls: true,
            preload: 'auto',
            fluid: false,
            height: 600,
            width: '100%',
            sources: [
                {
                    src: url,
                    type: 'video/' + (ext === 'mov' ? 'mp4' : ext)
                }
            ]
        });

        window.player.ready(function() {
            window.player.addClass('vjs-matrix');

            const watermarkHtml = `
                <div id="video-watermark-layer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 20; overflow: hidden;">
                    <div style="position: absolute; bottom: 30px; left: 65px; width: 600px; transform: rotate(-90deg); transform-origin: bottom left; text-align: left;">
                        <div style="font-family: 'Arial', sans-serif; font-size: 11px; line-height: 1.5; color: #fff; opacity: 0.7; text-shadow: 2px 2px 4px rgba(0,0,0,0.9); white-space: nowrap;">
                            <span style="font-weight: bold; text-transform: uppercase;">
                                Pelaksanaan Undang - Undang Nomor 13 Tahun 2018
                            </span><br>
                            <span style="font-weight: bold; text-transform: uppercase;">
                                Tentang Serah Simpan Karya Cetak dan Karya Rekam
                            </span><br>
                            <span style="color: #ff6b6b; font-weight: bold; text-shadow: 1px 1px 2px #000;">
                                Peringatan : Dilarang menggandakan, mencetak, mengunduh, atau mendistribusikan kembali tanpa izin.
                            </span>
                        </div>
                    </div>
                </div>
            `;

            $(window.player.el()).append(watermarkHtml);
        });

        window.player.on('play', function() {
            watermarkSound.play();
            syncVideoWatermark();
        });

        window.player.on('pause', function() { watermarkSound.pause(); });
        window.player.on('ended', function() { watermarkSound.stop(); });
        window.player.on('seeking', function() { syncVideoWatermark(); });

        $('#video-player').on('contextmenu', () => false);
    }

    function renderEpub(url) {
        const $container = $('#epub-container');

        $container
            .show()
            .css({
                'display': 'block',
                'overflow-y': 'hidden',
                'position': 'relative'
            })
            .empty();

        $('#watermark-overlay').css('display', 'flex');
        $('#epub-controls').css('display', 'none');
        $('#epub-watermark-layer').remove();

        if (window.ePub) {
            const book = window.ePub(url);

            const rendition = book.renderTo("epub-container", {
                width: '100%',
                height: '100%',
                flow: 'scrolled-doc',
                allowScriptedContent: true
            });

            rendition.display().then(() => {
                $('#epub-controls').css('display', 'flex');
                $('#watermark-overlay').hide();

                const containerHeight = $container.height();

                const watermarkHtml = `
                    <div id="epub-watermark-layer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 999;">
                        <div style="position: absolute; bottom: 10px; left: 60px; transform: rotate(-90deg); transform-origin: bottom left; width: ${containerHeight - 40}px; text-align: left; white-space: nowrap; opacity: 0.4; color: #333; font-family: 'Arial', sans-serif; font-size: 13px; line-height: 1.5;">
                            <div style="font-weight: bold; text-transform: uppercase;">
                                Pelaksanaan Undang - Undang Nomor 13 Tahun 2018
                            </div>
                            <div style="font-weight: bold; text-transform: uppercase;">
                                Tentang Serah Simpan Karya Cetak dan Karya Rekam
                            </div>
                            <div style="color: #d9534f; font-weight: bold; display: inline-block;">
                                Peringatan : Dilarang menggandakan, mencetak, mengunduh, atau mendistribusikan kembali tanpa izin.
                            </div>
                        </div>
                    </div>
                `;

                $container.append(watermarkHtml);
            });

            rendition.themes.default({
                'img': { 'max-width': '100% !important', 'height': 'auto !important' },
                'table': { 'max-width': '100% !important' },
                'p': {
                    'font-family': 'Helvetica, sans-serif',
                    'font-size': '1.1em',
                    'line-height': '1.6',
                    'text-align': 'justify'
                },
                'body': { 'padding': '0 15px !important', 'box-sizing': 'border-box' }
            });

            $('#next-btn').off('click').on('click', function(e) {
                e.preventDefault();
                rendition.next();
            });

            $('#prev-btn').off('click').on('click', function(e) {
                e.preventDefault();
                rendition.prev();
            });

            $(document).off('keyup').on('keyup', function(e) {
                if (e.keyCode == 37) rendition.prev();
                if (e.keyCode == 39) rendition.next();
            });
        }
    }

    function renderAudio(url, formatExt) {
        const watermarkSrc = "{{ asset('assets/audio-wm.mp3') }}";

        Howler.unload();

        if (typeof audioAnimFrame !== 'undefined' && audioAnimFrame) {
            cancelAnimationFrame(audioAnimFrame);
        }

        let cleanExt = (formatExt || 'mp3').toString().replace('.', '').toLowerCase().trim();
        const $wrapper = $('#audio-wrapper');
        const $playWrapper = $('#play-pause-wrapper');
        const $iconPlay = $('#icon-play');
        const $iconPause = $('#icon-pause');
        const $title = $('#audio-title-display');
        const canvas = document.getElementById('wave-canvas');
        const ctx = canvas.getContext('2d');

        $wrapper.fadeIn();

        $('#watermark-overlay').hide();

        $title.text('Menyiapkan...');
        $iconPlay.show(); $iconPause.hide();

        const syncWatermark = () => {
            if (!watermarkSound || !currentSound) return;
            if (!currentSound.playing()) return;

            const wmDur = watermarkSound.duration();

            if (wmDur > 0) {
                const targetPos = currentSound.seek() % wmDur;
                const currentWmPos = watermarkSound.seek();

                if (Math.abs(currentWmPos - targetPos) > 0.3) {
                    watermarkSound.seek(targetPos);
                }

                if (!watermarkSound.playing()) {
                    watermarkSound.play();
                }
            }
        };

        watermarkSound = new Howl({
            src: [watermarkSrc],
            html5: true,
            loop: true,
            preload: true,
            volume: 0.3,
        });

        currentSound = new Howl({
            src: [url],
            html5: true,
            format: [cleanExt, 'mp3'],
            xhr: { method: 'GET', withCredentials: true },

            onload: function() {
                $title.text('Audio Siap');

                $('#timer-duration').text(formatTime(currentSound.duration()));

                if(!audioAnimFrame) loopAnimation();
            },
            onloaderror: function(id, err) {
                $title.text('Gagal Memuat');
            },
            onplay: function() {
                watermarkSound.play();

                syncWatermark();

                $iconPlay.hide(); $iconPause.show(); $title.text('Sedang Memutar');
            },
            onpause: function() {
                watermarkSound.pause();
                $iconPlay.show(); $iconPause.hide(); $title.text('Dijeda');
            },
            onseek: function() {
                syncWatermark();
            },
            onend: function() {
                watermarkSound.stop();
                $iconPlay.show(); $iconPause.hide(); $title.text('Selesai');
            }
        });

        let wavePhase = 0;

        const loopAnimation = () => {
            if (currentSound && currentSound.playing()) {
                $('#timer-current').text(formatTime(currentSound.seek() || 0));
            }

            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            const w = canvas.width, h = canvas.height, mid = h / 2;

            ctx.clearRect(0, 0, w, h);

            const speed = (currentSound && currentSound.playing()) ? 0.1 : 0.02;

            wavePhase += speed;

            const drawSine = (color, offset, amp) => {
                ctx.beginPath(); ctx.strokeStyle = color; ctx.lineWidth = 2;
                for (let x = 0; x < w; x++) {
                    const y = mid + Math.sin(x * 0.01 + wavePhase + offset) * (50 * amp) * Math.sin(wavePhase * 0.5);
                    ctx.lineTo(x, y);
                }
                ctx.stroke();
            };

            drawSine('rgba(255,255,255,0.3)', 0, 1);
            drawSine('rgba(255,255,255,0.8)', 2, 0.8);

            audioAnimFrame = requestAnimationFrame(loopAnimation);
        };

        const formatTime = (s) => {
            if(isNaN(s)) return "0:00";

            let m = Math.floor(s/60), sec = Math.floor(s%60);

            return m + ':' + (sec<10?'0':'')+sec;
        }

        $playWrapper.off().on('click', function() {
            if (!currentSound) return;

            if (currentSound.playing()) {
                currentSound.pause();
            } else {
                if (Howler.ctx && Howler.ctx.state !== 'running') Howler.ctx.resume();

                currentSound.play();
            }
        });

        $('#btn-mute').off().on('click', function() {
            const muted = !currentSound.mute();

            currentSound.mute(muted);
            watermarkSound.mute(muted);

            $('#icon-vol').attr('class', muted ? 'ph-speaker-slash' : 'ph-speaker-high');
        });

        $('#btn-rewind').off().on('click', function() {
            if (!currentSound) return;
            const current = currentSound.seek();
            currentSound.seek(Math.max(0, current - 10));
        });

        $('#btn-forward').off().on('click', function() {
            if (!currentSound) return;
            const current = currentSound.seek();
            currentSound.seek(Math.min(currentSound.duration(), current + 10));
        });

        loopAnimation();
    }
</script>
