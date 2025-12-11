<div class="page-header page-header-light shadow mb-4">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                Serah Simpan Fisik - Monitoring Pengiriman - <span class="fw-normal">Detail</span>
            </h4>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page-header">
            <div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">
                <div class="d-inline-flex mt-3 mt-sm-0">
                    <a href="{{ url('physical-handover/delivery-monitoring') }}" class="btn btn-primary">
                        <i class="ph-arrow-left me-1"></i>
                        Kembali ke Tabel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="alert alert-danger alert-dismissible fade d-none" id="validation-element">
        <ul class="mb-0" id="validation-data"></ul>
    </div>
    <form id="form-data">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pengiriman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nomor Resi : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="receipt_no" id="receipt_no" value="{{ $letter->RECEIPT_NO ?? '' }}" placeholder="...........................">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Jasa Kirim : <span class="text-danger">*</span></label>
                            <select class="form-select select2-basic" name="delivery_service_id" id="delivery_service_id">
                                <option value=""></option>
                                @foreach($deliveryService as $ds)
                                    <option value="{{ $ds->ID }}" {{ ($letter->JASA_PENGIRIMAN_ID ?? '') == $ds->ID ? 'selected' : '' }}>{{ $ds->NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Biaya Kirim : <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="delivery_fee" id="delivery_fee" value="{{ $letter->BIAYA_KIRIM ?? '' }}" placeholder="...........................">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0"><hr></div>
                <div class="text-end">
                    <button type="button" class="btn btn-success" onclick="submitted()">
                        <i class="ph-floppy-disk me-1"></i>
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lacak Paket</h5>
            </div>
            <div class="card-body">
                @if($receipt)
                    <div class="border p-3 rounded">
                        <div class="list-feed list-feed-solid">
                            @foreach($receipt->manifest as $key => $m)
                                <div class="list-feed-item {{ $key == 0 ? 'border-success' : 'border-primary' }}">
                                    <div class="fw-semibold">{{ $m->manifest_code }}</div>
                                    <div class="text-muted"><small>{{ $m->manifest_date }} {{ $m->manifest_time }}</small></div>
                                    <div>{{ $m->manifest_description }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Koleksi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered w-100 display" id="datatable-client">
                    <thead class="text-bg-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Cover</th>
                            <th>Judul</th>
                            <th>Identifier</th>
                            <th>Jilid</th>
                            <th>Edisi</th>
                            <th>Jumlah</th>
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
                                        <img src="{{ $fileCover }}" class="img img-fluid img-thumbnail" style="max-width:70px;">
                                    </a>
                                </td>
                                <td class="text-wrap">{{ $ld->TITLE }}</td>
                                <td class="text-wrap">{{ $ld->ISBN }}</td>
                                <td class="text-wrap">{{ $ld->NOMORPANGGILJILID }}</td>
                                <td class="text-wrap">{{ $ld->EDISI_SERIAL }}</td>
                                <td class="text-wrap">{{ $ld->COPY }}</td>
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

    function clearValidation() {
        $('#validation-element').removeClass('show').addClass('d-none');
        $('#validation-data').html('');
    }

    function showValidation(data) {
        $('#validation-element').removeClass('d-none').addClass('show');
        $('#validation-data').html('');

        $.each(data, function(index, value) {
            $('#validation-data').append('<li>' + value + '</li>');
        });

        $('.btn-to-top button').click();
    }

    function submitted() {
        $.ajax({
            url: '{{ url("physical-handover/delivery-monitoring/detail/" . $letter->LETTER_ID) }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-data').serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                onLoading('show', 'body');
            },
            success: function(response) {
                onLoading('close', 'body');

                if(response.code == 200) {
                    swalInit.fire({
                        title: 'Berhasil',
                        text: response.message,
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Oke',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onLoading('show', 'body');

                            location.href = '{{ url("physical-handover/delivery-monitoring") }}';
                        }
                    });
                } else if (response.code == 400) {
                    showValidation(response.error);
                    showToast('error', 'Terdapat kesalahan pada form. Mohon periksa kembali.');
                } else {
                    swalInit.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
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
</script>
