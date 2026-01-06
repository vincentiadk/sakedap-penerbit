<div class="page-header page-header-light shadow-sm">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Dashboard Statistik Koleksi</span>
            </h4>
        </div>
        <div class="d-lg-flex ms-lg-auto">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-light" onclick="loadAllStatistic()">
                    <i class="ph-arrows-clockwise me-1"></i>
                    Refresh Data
                </button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="row g-3 card-summary">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-body border-start border-primary border-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <div class="text-muted text-uppercase fs-sm fw-semibold mb-1">Total Digital</div>
                        <h3 class="mb-0 fw-bold" id="summary-digital">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        </h3>
                    </div>
                    <div class="ms-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="ph-laptop ph-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-body border-start border-success border-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <div class="text-muted text-uppercase fs-sm fw-semibold mb-1">Total Cetak</div>
                        <h3 class="mb-0 fw-bold" id="summary-printed">
                            <span class="spinner-border spinner-border-sm text-success" role="status"></span>
                        </h3>
                    </div>
                    <div class="ms-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="ph-book-open ph-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-body border-start border-warning border-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <div class="text-muted text-uppercase fs-sm fw-semibold mb-1">Total Analog</div>
                        <h3 class="mb-0 fw-bold" id="summary-analog">
                            <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                        </h3>
                    </div>
                    <div class="ms-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="ph-film-strip ph-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-body border-start border-info border-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <div class="text-muted text-uppercase fs-sm fw-semibold mb-1">Total Semua</div>
                        <h3 class="mb-0 fw-bold" id="summary-total">
                            <span class="spinner-border spinner-border-sm text-info" role="status"></span>
                        </h3>
                    </div>
                    <div class="ms-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="ph-database ph-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card shadow-sm" id="card-top-media">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-trophy me-1 text-warning"></i>
                                Top 5 Jenis Koleksi
                            </h6>
                        </div>
                        <span class="badge bg-primary" id="badge-top-media">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="data-top-media" class="position-relative" style="min-height: 250px;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm" id="card-top-worksheet">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-trophy me-1 text-success"></i>
                                Top 5 Jenis Bahan
                            </h6>
                        </div>
                        <span class="badge bg-success" id="badge-top-worksheet">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="data-top-worksheet" class="position-relative" style="min-height: 250px;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card shadow-sm" id="card-media-type">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-chart-pie me-1 text-primary"></i>
                                Distribusi Jenis Koleksi
                            </h6>
                        </div>
                        <span class="badge bg-primary" id="badge-media-type">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-media-type" class="position-relative" style="width:100%; height:350px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm" id="card-worksheet">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-chart-pie me-1 text-success"></i>
                                Distribusi Jenis Bahan
                            </h6>
                        </div>
                        <span class="badge bg-success" id="badge-worksheet">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-worksheet" class="position-relative" style="width:100%; height:350px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow-sm" id="card-total-collection">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-chart-pie-slice me-1 text-info"></i>
                                Distribusi Koleksi
                            </h6>
                        </div>
                        <span class="badge bg-info" id="badge-total-collection">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-total-collection" class="position-relative" style="width:100%; height:350px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card shadow-sm" id="card-collection-status">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-chart-bar me-1 text-secondary"></i>
                                Status Koleksi
                            </h6>
                        </div>
                        <span class="badge bg-secondary" id="badge-status-total">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-collection-status" class="position-relative" style="width:100%; height:400px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card shadow-sm" id="card-activity">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ph-clock-counter-clockwise me-1"></i>
                                Aktivitas Terbaru
                            </h6>
                        </div>
                        <span class="badge bg-dark">10 Data Terakhir</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:442px; height:442px; overflow-y:auto;">
                        <table class="table table-hover table-xs mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center" style="width: 40px;">No</th>
                                    <th style="width: 100px;">Aksi</th>
                                    <th>User</th>
                                    <th style="width: 120px;">Tanggal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="data-activity">
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="spinner-border text-muted" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat aktivitas...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        loadAllStatistic();
    });

    function loadAllStatistic() {
        chartMediaType();
        chartWorksheet();
        chartTotalCollection();
        chartCollectionStatus();
        dataActivity();
        topMediaData();
        topWorksheetData();
    }

    function topMediaData() {
        $.ajax({
            url: '{{ url("dashboard/data-media-type") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#data-top-media').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat data...</p>
                    </div>
                `);
            },
            success: function(response) {
                var total = 0;
                var validData = [];

                if(response && Array.isArray(response)) {
                    response.forEach(function(item) {
                        if(item && item.value && item.value > 0) {
                            total += parseInt(item.value);
                            validData.push(item);
                        }
                    });
                }

                $('#badge-top-media').text(total + ' Item');

                if(validData.length === 0) {
                    $('#data-top-media').html(`
                        <div class="text-center text-muted py-5">
                            <i class="ph-info ph-3x opacity-25 mb-3"></i>
                            <p class="mb-0 fw-semibold">Tidak ada data</p>
                            <p class="fs-sm mb-0">Data jenis koleksi belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                validData.sort(function(a, b) {
                    return b.value - a.value;
                });

                var top5 = validData.slice(0, 5);
                var html = '';
                var colors = ['primary', 'success', 'info', 'warning', 'danger'];

                top5.forEach(function(item, index) {
                    var percent = ((item.value / total) * 100).toFixed(1);
                    var color = colors[index];

                    html += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-${color} badge-sm me-1">${index + 1}</span>
                                    <span class="fw-semibold">${item.name}</span>
                                </div>
                                <span class="text-muted fs-sm">${item.value} <span class="text-${color}">(${percent}%)</span></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-${color}" role="progressbar" style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    `;
                });

                $('#data-top-media').html(html);
            },
            error: function(response) {
                $('#data-top-media').html(`
                    <div class="alert alert-danger border-0 mb-0">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat data. Silakan coba lagi.
                    </div>
                `);

                responseError(response);
            }
        });
    }

    function topWorksheetData() {
        $.ajax({
            url: '{{ url("dashboard/data-worksheet") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#data-top-worksheet').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat data...</p>
                    </div>
                `);
            },
            success: function(response) {
                var total = 0;
                var validData = [];

                if(response && Array.isArray(response)) {
                    response.forEach(function(item) {
                        if(item && item.value && item.value > 0) {
                            total += parseInt(item.value);
                            validData.push(item);
                        }
                    });
                }

                $('#badge-top-worksheet').text(total + ' Item');

                if(validData.length === 0) {
                    $('#data-top-worksheet').html(`
                        <div class="text-center text-muted py-5">
                            <i class="ph-info ph-3x opacity-25 mb-3"></i>
                            <p class="mb-0 fw-semibold">Tidak ada data</p>
                            <p class="fs-sm mb-0">Data jenis bahan belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                validData.sort(function(a, b) {
                    return b.value - a.value;
                });

                var top5 = validData.slice(0, 5);
                var html = '';
                var colors = ['success', 'primary', 'warning', 'info', 'danger'];

                top5.forEach(function(item, index) {
                    var percent = ((item.value / total) * 100).toFixed(1);
                    var color = colors[index];

                    html += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-${color} badge-sm me-1">${index + 1}</span>
                                    <span class="fw-semibold">${item.name}</span>
                                </div>
                                <span class="text-muted fs-sm">${item.value} <span class="text-${color}">(${percent}%)</span></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-${color}" role="progressbar" style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    `;
                });

                $('#data-top-worksheet').html(html);
            },
            error: function(response) {
                $('#data-top-worksheet').html(`
                    <div class="alert alert-danger border-0 mb-0">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat data. Silakan coba lagi.
                    </div>
                `);

                responseError(response);
            }
        });
    }

    function dataActivity() {
        $.ajax({
            url: '{{ url("dashboard/data-activity") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#data-activity').html(`
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="spinner-border text-muted" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 fs-sm">Memuat aktivitas...</p>
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                if(response.length > 0) {
                    var html = '';

                    $.each(response, function(i, val) {
                        var nomor = i + 1;
                        var action = val.ACTION || '-';
                        var user = val.ACTIONBY || '-';
                        var date = val.ACTIONDATE ? moment(val.ACTIONDATE).format('DD/MM/YYYY HH:mm') : '-';
                        var description = val.NOTE || '-';
                        var actionBadge = '';
                        var actionLower = action.toLowerCase();

                        if(actionLower.includes('create') || actionLower.includes('tambah')) {
                            actionBadge = '<span class="badge bg-success">' + action + '</span>';
                        } else if(actionLower.includes('update') || actionLower.includes('edit')) {
                            actionBadge = '<span class="badge bg-primary">' + action + '</span>';
                        } else if(actionLower.includes('delete') || actionLower.includes('hapus')) {
                            actionBadge = '<span class="badge bg-danger">' + action + '</span>';
                        } else {
                            actionBadge = '<span class="badge bg-secondary">' + action + '</span>';
                        }

                        html += `
                            <tr>
                                <td class="text-center fw-semibold text-muted">${nomor}</td>
                                <td>${actionBadge}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-1" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph-user"></i>
                                        </div>
                                        <span class="fw-semibold">${user}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted fs-sm">
                                        <i class="ph-clock me-1"></i>${date}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">${description}</span>
                                </td>
                            </tr>
                        `;
                    });

                    $('#data-activity').html(html);
                } else {
                    $('#data-activity').html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="ph-clipboard-text ph-3x opacity-25 mb-3"></i>
                                <p class="mb-0 fw-semibold">Tidak ada aktivitas</p>
                                <p class="fs-sm mb-0">Belum ada aktivitas yang tercatat</p>
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(response) {
                $('#data-activity').html(`
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="alert alert-danger border-0 mb-0">
                                <i class="ph-warning-circle me-1"></i>
                                Gagal memuat data aktivitas
                            </div>
                        </td>
                    </tr>
                `);

                responseError(response);
            }
        });
    }

    function chartMediaType() {
        $.ajax({
            url: '{{ url("dashboard/data-media-type") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#chart-media-type').html(`
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                    </div>
                `);
            },
            success: function(response) {
                var total = 0;
                var validData = [];

                if(response && Array.isArray(response)) {
                    response.forEach(function(item) {
                        if(item && item.value && item.value > 0) {
                            total += parseInt(item.value);
                            validData.push(item);
                        }
                    });
                }

                $('#badge-media-type').text(total + ' Item');
                $('#summary-digital').text(total.toLocaleString('id-ID'));

                if(validData.length === 0 || total === 0) {
                    $('#chart-media-type').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px;">
                            <i class="ph-chart-pie-slice ph-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-1 fw-semibold">Tidak ada data</p>
                            <p class="text-muted fs-sm mb-0">Data jenis koleksi belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                validData.sort(function(a, b) {
                    return b.value - a.value;
                });

                var chartSelector = document.getElementById('chart-media-type');
                var existingChart = echarts.getInstanceByDom(chartSelector);

                if(existingChart) {
                    existingChart.dispose();
                }

                var chart = echarts.init(chartSelector);

                var option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: function (params) {
                            var value = params.value;
                            var percentage = ((value / total) * 100).toFixed(1);
                            return params.name + ': ' + value + ' (' + percentage + '%)';
                        },
                        confine: true
                    },
                    series: [{
                        type: 'treemap',
                        width: '100%',
                        height: '100%',
                        roam: false,
                        nodeClick: false,
                        breadcrumb: {
                            show: false
                        },
                        label: {
                            show: true,
                            formatter: function(params) {
                                return params.name + '\n' + params.value;
                            },
                            fontSize: 11,
                            color: '#fff'
                        },
                        itemStyle: {
                            borderColor: '#fff',
                            borderWidth: 2,
                            gapWidth: 2
                        },
                        levels: [{
                            itemStyle: {
                                borderWidth: 0,
                                gapWidth: 5
                            }
                        }, {
                            itemStyle: {
                                gapWidth: 1
                            }
                        }],
                        data: validData,
                        visualDimension: 0,
                        colorMappingBy: 'index',
                        color: ['#0d6efd', '#198754', '#fd7e14', '#6f42c1', '#dc3545', '#0dcaf0', '#20c997', '#ffc107', '#d63384', '#6610f2']
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });
            },
            error: function(response) {
                $('#chart-media-type').html(`
                    <div class="alert alert-danger border-0 mb-0 mx-3">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat grafik
                    </div>
                `);

                responseError(response);
            }
        });
    }

    function chartWorksheet() {
        $.ajax({
            url: '{{ url("dashboard/data-worksheet") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#chart-worksheet').html(`
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                    </div>
                `);
            },
            success: function(response) {
                var total = 0;
                var validData = [];

                if(response && Array.isArray(response)) {
                    response.forEach(function(item) {
                        if(item && item.value && item.value > 0) {
                            total += parseInt(item.value);
                            validData.push(item);
                        }
                    });
                }

                $('#badge-worksheet').text(total + ' Item');

                if(validData.length === 0 || total === 0) {
                    $('#chart-worksheet').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px;">
                            <i class="ph-files ph-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-1 fw-semibold">Tidak ada data</p>
                            <p class="text-muted fs-sm mb-0">Data jenis bahan belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                validData.sort(function(a, b) {
                    return b.value - a.value;
                });

                var chartSelector = document.getElementById('chart-worksheet');
                var existingChart = echarts.getInstanceByDom(chartSelector);

                if(existingChart) {
                    existingChart.dispose();
                }

                var chart = echarts.init(chartSelector);

                var option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: function (params) {
                            var value = params.value;
                            var percentage = ((value / total) * 100).toFixed(1);
                            return params.name + ': ' + value + ' (' + percentage + '%)';
                        },
                        confine: true
                    },
                    series: [{
                        type: 'treemap',
                        width: '100%',
                        height: '100%',
                        roam: false,
                        nodeClick: false,
                        breadcrumb: {
                            show: false
                        },
                        label: {
                            show: true,
                            formatter: function(params) {
                                return params.name + '\n' + params.value;
                            },
                            fontSize: 11,
                            color: '#fff'
                        },
                        itemStyle: {
                            borderColor: '#fff',
                            borderWidth: 2,
                            gapWidth: 2
                        },
                        levels: [{
                            itemStyle: {
                                borderWidth: 0,
                                gapWidth: 5
                            }
                        }, {
                            itemStyle: {
                                gapWidth: 1
                            }
                        }],
                        data: validData,
                        visualDimension: 0,
                        colorMappingBy: 'index',
                        color: ['#198754', '#0d6efd', '#fd7e14', '#6f42c1', '#dc3545', '#0dcaf0', '#20c997', '#ffc107', '#d63384', '#6610f2']
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });
            },
            error: function(response) {
                $('#chart-worksheet').html(`
                    <div class="alert alert-danger border-0 mb-0 mx-3">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat grafik
                    </div>
                `);

                responseError(response);
            }
        });
    }

    function chartTotalCollection() {
        $.ajax({
            url: '{{ url("dashboard/data-total-works") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#chart-total-collection').html(`
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                    </div>
                `);
            },
            success: function(response) {
                var digital = parseInt(response.TOTAL_DIGITAL) || 0;
                var printed = parseInt(response.TOTAL_PRINTED) || 0;
                var analog = parseInt(response.TOTAL_ANALOG) || 0;
                var total = digital + printed + analog;

                $('#summary-digital').text(digital.toLocaleString('id-ID'));
                $('#summary-printed').text(printed.toLocaleString('id-ID'));
                $('#summary-analog').text(analog.toLocaleString('id-ID'));
                $('#summary-total').text(total.toLocaleString('id-ID'));
                $('#badge-total-collection').text(total + ' Item');

                if(total === 0) {
                    $('#chart-total-collection').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px;">
                            <i class="ph-stack ph-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-1 fw-semibold">Tidak ada data</p>
                            <p class="text-muted fs-sm mb-0">Data koleksi belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                var chartSelector = document.getElementById('chart-total-collection');
                var existingChart = echarts.getInstanceByDom(chartSelector);

                if(existingChart) {
                    existingChart.dispose();
                }

                var chart = echarts.init(chartSelector);

                var option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}: {c} ({d}%)',
                        confine: true
                    },
                    legend: {
                        orient: 'horizontal',
                        bottom: '5',
                        left: 'center',
                        textStyle: {
                            fontSize: 11
                        }
                    },
                    color: ['#0d6efd', '#198754', '#fd7e14'],
                    series: [{
                        type: 'pie',
                        radius: ['45%', '75%'],
                        center: ['50%', '45%'],
                        avoidLabelOverlap: true,
                        itemStyle: {
                            borderRadius: 8,
                            borderColor: '#fff',
                            borderWidth: 3
                        },
                        label: {
                            show: true,
                            position: 'outside',
                            formatter: '{c}',
                            fontSize: 14,
                            fontWeight: 'bold',
                            distanceToLabelLine: 5
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: 18,
                                fontWeight: 'bold'
                            },
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            },
                            scale: true,
                            scaleSize: 10
                        },
                        labelLine: {
                            show: true,
                            length: 15,
                            length2: 10,
                            smooth: false
                        },
                        data: [
                            { name: 'Digital', value: digital },
                            { name: 'Cetak', value: printed },
                            { name: 'Analog', value: analog }
                        ]
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });
            },
            error: function(response) {
                $('#chart-total-collection').html(`
                    <div class="alert alert-danger border-0 mb-0 mx-3">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat grafik
                    </div>
                `);

                responseError(response);
            }
        });
    }

    function chartCollectionStatus() {
        $.ajax({
            url: '{{ url("dashboard/data-collection-status") }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function() {
                $('#chart-collection-status').html(`
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 fs-sm">Memuat grafik...</p>
                    </div>
                `);
            },
            success: function(response) {
                var total = 0;

                if(response && response.data && Array.isArray(response.data)) {
                    response.data.forEach(function(val) {
                        total += parseInt(val) || 0;
                    });
                }

                $('#badge-status-total').text(total + ' Item');

                if(!response || !response.data || total === 0) {
                    $('#chart-collection-status').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 400px;">
                            <i class="ph-clipboard-text ph-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-1 fw-semibold">Tidak ada data</p>
                            <p class="text-muted fs-sm mb-0">Data status koleksi belum tersedia</p>
                        </div>
                    `);

                    return;
                }

                var chartSelector = document.getElementById('chart-collection-status');
                var existingChart = echarts.getInstanceByDom(chartSelector);

                if(existingChart) {
                    existingChart.dispose();
                }

                var chart = echarts.init(chartSelector);

                var option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: function(params) {
                            var item = params[0];
                            var percent = total > 0 ? ((item.value / total) * 100).toFixed(1) : 0;
                            return item.name + '<br/>' + item.marker + ' ' + item.value + ' (' + percent + '%)';
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        top: '10%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: [{
                        type: 'category',
                        data: response.label || [],
                        axisTick: {
                            alignWithLabel: true
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        axisLabel: {
                            fontSize: 12,
                            fontWeight: 500
                        }
                    }],
                    yAxis: [{
                        type: 'value',
                        axisLine: {
                            show: true,
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        axisLabel: {
                            fontSize: 12
                        },
                        splitLine: {
                            lineStyle: {
                                color: '#E5E7EB',
                                type: 'dashed'
                            }
                        }
                    }],
                    series: [{
                        name: 'Total',
                        type: 'bar',
                        barWidth: '50%',
                        itemStyle: {
                            borderRadius: [6, 6, 0, 0],
                            color: function(params) {
                                var colorList = ['#fd7e14', '#198754', '#dc3545', '#d63384'];
                                return colorList[params.dataIndex] || '#6c757d';
                            }
                        },
                        label: {
                            show: true,
                            position: 'top',
                            fontSize: 13,
                            fontWeight: 'bold',
                            color: '#333'
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.3)'
                            }
                        },
                        data: response.data || []
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });
            },
            error: function(response) {
                $('#chart-collection-status').html(`
                    <div class="alert alert-danger border-0 mb-0 mx-3">
                        <i class="ph-warning-circle me-1"></i>
                        Gagal memuat grafik
                    </div>
                `);

                responseError(response);
            }
        });
    }
</script>
