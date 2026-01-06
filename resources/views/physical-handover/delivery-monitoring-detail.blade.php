<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - Monitoring Pengiriman - <span class="fw-normal">Detail</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('physical-handover/delivery-monitoring') }}" class="btn btn-primary">
                    <i class="ph-arrow-left me-1"></i>
                    Kembali ke Tabel
                </a>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <form id="form-data">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-envelope me-1 text-primary"></i>
                    Informasi Surat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-calendar-check ph-2x text-success"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Tanggal Surat</div>
                                    <div class="fw-semibold">{{ Carbon::parse($letter->LETTER_DATE)->isoFormat('dddd, D MMMM Y') }}</div>
                                    <div class="text-muted small">{{ Carbon::parse($letter->LETTER_DATE)->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-file-text ph-2x text-info"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Nomor Surat</div>
                                    <div class="fw-semibold">{{ $letter->LETTER_NUMBER ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-user-circle me-1 text-success"></i>
                    Informasi Pengirim
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-user ph-2x text-primary"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Nama Pengirim</div>
                                    <div class="fw-semibold">{{ $letter->SENDER ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-phone ph-2x text-success"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Nomor Telepon</div>
                                    <div class="fw-semibold">{{ $letter->PHONE ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-package me-1 text-warning"></i>
                    Informasi Pengiriman
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-truck ph-2x text-primary"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Jasa Pengiriman</div>
                                    <div class="fw-semibold">{{ $letter->NAME_JASA_PENGIRIMAN ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-map-pin ph-2x text-danger"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Tujuan Pengiriman</div>
                                    <div class="fw-semibold">{{ $letter->NAME_BRANCH ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-barcode ph-2x text-info"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Nomor Resi</div>
                                    <div class="fw-semibold">{{ $letter->RECEIPT_NO ?: 'Tidak Ada' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-currency-circle-dollar ph-2x text-success"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Biaya Pengiriman</div>
                                    <div class="fw-semibold">Rp {{ number_format($letter->BIAYA_KIRIM ?: 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-cube ph-2x text-warning"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Berat Paket</div>
                                    <div class="fw-semibold">{{ number_format(($letter->BERAT ?: 0) / 1000, 2, ',', '.') }} Kg</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-info ph-2x text-primary"></i>
                                </div>
                                <div class="flex-fill ms-3">
                                    <div class="text-muted small mb-1">Status Pengiriman</div>
                                    <div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                            <i class="ph-circle me-1"></i>
                                            {{ $letter->STATUS ?: 'Tidak Ada' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-map-trifold me-1 text-danger"></i>
                    Lacak Paket
                </h5>
            </div>
            <div class="card-body">
                @if($receipt)
                    @if(isset($receipt->manifest) && count($receipt->manifest) > 0)
                        <div class="border rounded p-3">
                            <div class="list-feed list-feed-solid">
                                @foreach($receipt->manifest as $key => $m)
                                    <div class="list-feed-item {{ $key == 0 ? 'border-success' : 'border-primary' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-{{ $key == 0 ? 'success' : 'primary' }} bg-opacity-10 rounded-circle p-1">
                                                    <i class="ph-{{ $key == 0 ? 'check-circle' : 'map-pin' }} text-{{ $key == 0 ? 'success' : 'primary' }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="fw-semibold mb-1">{{ $m->manifest_code }}</div>
                                                <div class="text-muted small mb-2">
                                                    <i class="ph-calendar me-1"></i>
                                                    {{ $m->manifest_date }}
                                                    <i class="ph-clock ms-2 me-1"></i>
                                                    {{ $m->manifest_time }}
                                                </div>
                                                <div class="text-muted">{{ $m->manifest_description }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info border-0 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="ph-info ph-2x me-3"></i>
                                <div>
                                    <div class="fw-semibold">Belum Ada Data Pelacakan</div>
                                    <div class="small">Data pelacakan paket belum tersedia saat ini</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning border-0 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="ph-warning ph-2x me-3"></i>
                            <div>
                                <div class="fw-semibold">Tidak Ada Data Resi</div>
                                <div class="small">Informasi resi pengiriman tidak ditemukan</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="ph-books me-1 text-success"></i>
                    Daftar Koleksi
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover w-100 display" id="datatable-client">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-nowrap" rowspan="2" style="width: 50px">
                                    <i class="ph-hash"></i>
                                </th>
                                <th class="text-center text-nowrap" rowspan="2" style="width: 100px">
                                    <i class="ph-image"></i>
                                    Cover
                                </th>
                                <th class="text-nowrap" rowspan="2" style="min-width: 250px">
                                    <i class="ph-book me-1"></i>
                                    Judul
                                </th>
                                <th class="text-center text-nowrap" rowspan="2" style="min-width: 100px">
                                    <i class="ph-book-open me-1"></i>
                                    Jilid
                                </th>
                                <th class="text-center text-nowrap" rowspan="2" style="min-width: 100px">
                                    <i class="ph-files me-1"></i>
                                    Edisi
                                </th>
                                <th class="text-center">
                                    <i class="ph-package me-1"></i>
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($letterDetail ?? [] as $key => $ld)
                                @php
                                    $code = str_replace('-', '', $ld->ISBN);
                                    $fileCover = asset('assets/no-file.jpg');

                                    if ($code) {
                                        $getDataISBN = ISBN::get('search', [
                                            'code' => $code
                                        ], true);

                                        if($getDataISBN) {
                                            if(isset($getDataISBN->cover_file_name)) {
                                                if($getDataISBN->cover_file_name) {
                                                    $fileCover = $getDataISBN->cover_file_name;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">
                                        <a href="{{ $fileCover }}" data-lightbox="cover-{{ $code }}" data-title="{{ $ld->TITLE }}">
                                            <img src="{{ $fileCover }}" class="img-fluid img-thumbnail rounded shadow-sm" style="max-width: 80px; max-height: 100px; object-fit: cover;" alt="Cover">
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <div class="fw-semibold text-wrap mb-2">{{ $ld->TITLE }}</div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="ph-barcode me-1"></i>
                                                {{ $ld->ISBN ?: 'Tidak ada ISBN' }}
                                            </span>
                                        </div>
                                    </td>
                                   <td class="text-center align-middle">
                                        @if($ld->NOMORPANGGILJILID)
                                            <span class="badge bg-light text-dark border">{{ $ld->NOMORPANGGILJILID }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($ld->EDISI_SERIAL)
                                            <span class="badge bg-light text-dark border">{{ $ld->EDISI_SERIAL }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                            {{ $ld->COPY }} Eksemplar
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('#datatable-client').DataTable({
            paging: true,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: [0, 1, 5],
                    orderable: false
                }
            ]
        });
    });
</script>
