<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - Pengiriman Diterima - <span class="fw-normal">Detail</span>
            </h4>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page-header">
            <div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">
                <div class="d-inline-flex mt-3 mt-sm-0">
                    <a href="{{ url('physical-handover/delivery-accept') }}" class="btn btn-primary">
                        <i class="ph-arrow-left me-1"></i>
                        Kembali ke Tabel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <form id="form-data">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Data</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="table-success" width="20%">Tanggal</th>
                            <td width="30%">{{ Carbon::parse($letter->LETTER_DATE)->isoFormat('D MMM Y') }}, {{ Carbon::parse($letter->LETTER_DATE)->format('H:i') }}</td>
                            <th class="table-success" width="20%">No Surat</th>
                            <td width="30%">{{ $letter->LETTER_NUMBER }}</td>
                        </tr>
                        <tr>
                            <th class="table-success" width="20%">Pengirim</th>
                            <td width="30%">{{ $letter->SENDER }}</td>
                            <th class="table-success" width="20%">Telp</th>
                            <td width="30%">{{ $letter->PHONE }}</td>
                        </tr>
                        <tr>
                            <th class="table-success" width="20%">Jasa Kirim</th>
                            <td width="30%">{{ $letter->NAME_JASA_PENGIRIMAN }}</td>
                            <th class="table-success" width="20%">Tujuan</th>
                            <td width="30%">{{ $letter->NAME_BRANCH }}</td>
                        </tr>
                        <tr>
                            <th class="table-success" width="20%">Resi</th>
                            <td width="30%">{{ $letter->RECEIPT_NO }}</td>
                            <th class="table-success" width="20%">Biaya Kirim</th>
                            <td width="30%">Rp {{ number_format($letter->BIAYA_KIRIM ?: 0) }}</td>
                        </tr>
                        <tr>
                            <th class="table-success" width="20%">Berat</th>
                            <td width="30%">{{ number_format(($letter->BERAT ?: 0) / 1000, 2, ',', '.') }} Kg</td>
                            <th class="table-success" width="20%">Status</th>
                            <td width="30%">{{ $letter->STATUS }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered w-100 display" id="datatable-client">
                    <thead class="text-bg-light">
                        <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <th rowspan="2">Cover</th>
                            <th rowspan="2">Judul</th>
                            <th rowspan="2">Identifier</th>
                            <th rowspan="2">Jilid</th>
                            <th rowspan="2">Edisi</th>
                            <th colspan="2" class="text-center">Jumlah Eks</th>
                            <th rowspan="2">Alasan Ditolak</th>
                        </tr>
                        <tr>
                            <th class="text-center">Diterima</th>
                            <th class="text-center">Ditolak</th>
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
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">
                                    <a href="{{ $fileCover }}" data-lightbox="cover-{{ $code }}" data-title="{{ $ld->TITLE }}">
                                        <img src="{{ $fileCover }}" class="img img-fluid img-thumbnail" style="max-width:70px;">
                                    </a>
                                </td>
                                <td class="text-wrap">{{ $ld->TITLE }}</td>
                                <td class="text-wrap">{{ $ld->ISBN }}</td>
                                <td class="text-wrap">{{ $ld->NOMORPANGGILJILID }}</td>
                                <td class="text-wrap">{{ $ld->EDISI_SERIAL }}</td>
                                <td class="text-wrap">{{ $ld->QTY_ACCEPT }}</td>
                                <td class="text-wrap">{{ $ld->QTY_REJECT }}</td>
                                <td class="text-wrap">
                                    @php $remark = explode(';', $ld->REMARK ?? ''); @endphp
                                    <ul class="mb-0 m-0 ps-3">
                                        @foreach($remark as $r)
                                            <li>{{ $r }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('#datatable-client').DataTable({
            paging: false,
            lengthChange: false,
            info: false,
            scrollY: '400px',
            scrollX: false,
            scrollCollapse: true,
        });
    });
</script>
