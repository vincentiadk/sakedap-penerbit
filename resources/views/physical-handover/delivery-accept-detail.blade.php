<div class="page-header page-header-light shadow-sm mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - <span class="fw-normal">Detail Pengiriman Diterima</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('physical-handover/delivery-accept') }}" class="btn btn-primary">
                    <i class="ph-arrow-left me-1"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    @php
        $totalAccept = 0;
        $totalReject = 0;
        $totalTitle = count($letterDetail ?? []);
        $totalAll = 0;

        foreach($letterDetail ?? [] as $item) {
            $totalAccept += $item->QTY_ACCEPT ?: 0;
            $totalReject += $item->QTY_REJECT ?: 0;
            $totalAll += $item->COPY ?: 0;
        }

        $percentAccept = $totalAll > 0 ? round(($totalAccept / $totalAll) * 100, 1) : 0;
        $percentReject = $totalAll > 0 ? round(($totalReject / $totalAll) * 100, 1) : 0;
    @endphp
    <form id="form-data">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="ph-books text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small mb-1">Total Judul</div>
                                <div class="fs-4 fw-bold text-primary">{{ $totalTitle }}</div>
                                <div class="text-muted small">Judul Buku</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded p-3">
                                    <i class="ph-package text-info" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small mb-1">Total Dikirim</div>
                                <div class="fs-4 fw-bold text-info">{{ number_format($totalAll) }}</div>
                                <div class="text-muted small">Eksemplar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded p-3">
                                    <i class="ph-check-circle text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small mb-1">Diterima</div>
                                <div class="fs-4 fw-bold text-success">{{ number_format($totalAccept) }}</div>
                                <div class="text-muted small">{{ $percentAccept }}% dari total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger bg-opacity-10 rounded p-3">
                                    <i class="ph-x-circle text-danger" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small mb-1">Ditolak</div>
                                <div class="fs-4 fw-bold text-danger">{{ number_format($totalReject) }}</div>
                                <div class="text-muted small">{{ $percentReject }}% dari total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-2">
                @if($letter->STATUS == 'DITERIMA PENUH')
                    <span class="badge bg-success" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">
                        <i class="ph-check-circle me-1"></i>
                        {{ $letter->STATUS }}
                    </span>
                @elseif($letter->STATUS == 'DITERIMA PARSIAL')
                    <span class="badge bg-warning" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">
                        <i class="ph-warning-circle me-1"></i>
                        {{ $letter->STATUS }}
                    </span>
                @elseif($letter->STATUS == 'DITERIMA')
                    <span class="badge bg-info" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">
                        <i class="ph-warning-circle me-1"></i>
                        {{ $letter->STATUS }}
                    </span>
                @else
                    <span class="badge bg-secondary" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">
                        {{ $letter->STATUS }}
                    </span>
                @endif
                @if($totalReject > 0)
                    <div class="text-muted small mt-2">
                        <i class="ph-info me-1"></i>
                        Terdapat {{ $totalReject }} eksemplar yang ditolak dan akan dikembalikan sebagai hibah
                    </div>
                @endif
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">
                <div class="d-flex align-items-center">
                    <i class="ph-info me-1 text-primary fs-5"></i>
                    <h6 class="mb-0 fw-semibold">Informasi Pengiriman</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border-start border-primary border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-calendar-check text-primary me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Tanggal Pengiriman</label>
                                    <div class="fw-bold">{{ Carbon::parse($letter->LETTER_DATE)->isoFormat('dddd, D MMMM Y') }}</div>
                                    <div class="text-primary small"><i class="ph-clock me-1"></i>{{ Carbon::parse($letter->LETTER_DATE)->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-success border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-file-text text-success me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Nomor Surat</label>
                                    <div class="fw-bold">{{ $letter->LETTER_NUMBER ?: 'Tidak ada nomor surat' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-info border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-user-circle text-info me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Pengirim</label>
                                    <div class="fw-bold">{{ $letter->SENDER ?: 'Tidak ada nama pengirim' }}</div>
                                    <div class="text-info small">
                                        <i class="ph-phone me-1"></i>
                                        {{ $letter->PHONE ?: 'Tidak ada telepon' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-warning border-4 ps-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-map-pin text-warning me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Tujuan Pengiriman</label>
                                    <div class="fw-bold">{{ $letter->NAME_BRANCH ?: 'Tidak ada tujuan' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border-start border-primary border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-truck text-primary me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Jasa Pengiriman</label>
                                    <div class="fw-bold">{{ $letter->NAME_JASA_PENGIRIMAN ?: 'Tidak ada jasa kirim' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-success border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-barcode text-success me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Nomor Resi</label>
                                    <div class="fw-bold">
                                        <span class="badge bg-light text-dark border" style="padding: 0.5rem 1rem;">
                                            {{ $letter->RECEIPT_NO ?: 'Tidak ada resi' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-info border-4 ps-3 mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="ph-currency-circle-dollar text-info me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Biaya Pengiriman</label>
                                    <div class="fw-bold text-info">Rp {{ number_format($letter->BIAYA_KIRIM ?: 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="border-start border-warning border-4 ps-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ph-scales text-warning me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="text-muted small mb-1 text-uppercase fw-semibold">Berat Total</label>
                                    <div class="fw-bold text-warning">{{ number_format(($letter->BERAT ?: 0) / 1000, 2, ',', '.') }} Kg</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ph-books me-1 text-success fs-5"></i>
                        <h6 class="mb-0 fw-semibold">Detail Koleksi yang Diterima</h6>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            <i class="ph-book me-1"></i>
                            {{ $totalTitle }} Judul
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="ph-check-circle me-1"></i>
                            {{ number_format($totalAccept) }} Diterima
                        </span>
                        @if($totalReject > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger">
                            <i class="ph-x-circle me-1"></i>
                            {{ number_format($totalReject) }} Ditolak
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="ph-info me-1"></i>
                        <div class="flex-grow-1">
                            <strong>Informasi:</strong> Berikut adalah daftar lengkap koleksi yang diterima beserta status penerimaan masing-masing eksemplar.
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered display nowrap w-100" id="datatable-client">
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
                                <th class="text-center" colspan="3">
                                    <i class="ph-package me-1"></i>
                                    Status Eksemplar
                                </th>
                                <th class="text-nowrap" rowspan="2" style="min-width: 200px">
                                    <i class="ph-warning me-1"></i>
                                    Alasan Penolakan
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center text-nowrap bg-success bg-opacity-10" style="min-width: 100px">
                                    <i class="ph-check-circle me-1"></i>
                                    Diterima
                                </th>
                                <th class="text-center text-nowrap bg-danger bg-opacity-10" style="min-width: 100px">
                                    <i class="ph-x-circle me-1"></i>
                                    Ditolak
                                </th>
                                <th class="text-center text-nowrap bg-info bg-opacity-10" style="min-width: 100px">
                                    <i class="ph-percent me-1"></i>
                                    Terima
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($letterDetail ?? [] as $key => $ld)
                                @php
                                    $strRand = Str::random(5);
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

                                    $totalItem = ($ld->QTY_ACCEPT ?: 0) + ($ld->QTY_REJECT ?: 0);
                                    $percentItem = $totalItem > 0 ? round((($ld->QTY_ACCEPT ?: 0) / $totalItem) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark border">{{ $key + 1 }}</span>
                                    </td>
                                    <td class="text-center align-middle">
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
                                    <td class="text-center align-middle text-bg-success bg-opacity-5">
                                        {{ $ld->QTY_ACCEPT ?: 0 }}
                                    </td>
                                    <td class="text-center align-middle text-bg-danger bg-opacity-5">
                                        {{ $ld->QTY_REJECT ?: 0 }}
                                    </td>
                                    <td class="text-center align-middle text-bg-info bg-opacity-5">
                                        <strong>{{ $percentItem }}%</strong>
                                    </td>
                                    <td class="align-middle">
                                        @if($ld->REMARK)
                                            @php $remark = explode(';', $ld->REMARK); @endphp
                                            <ul class="mb-0 ps-3">
                                                @foreach($remark as $r)
                                                    @if(trim($r))
                                                        <li class="text-wrap small mb-1">
                                                            <i class="ph-warning-circle text-warning me-1"></i>
                                                            {{ trim($r) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="ph-check-circle text-success"></i>
                                                <small>Tidak ada penolakan</small>
                                            </div>
                                        @endif
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
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            scrollX: true,
            order: [[0, 'asc']]
        });
    });
</script>
