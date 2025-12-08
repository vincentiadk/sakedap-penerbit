<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <span class="fw-normal">Dashboard</span>
            </h4>
        </div>
        <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page-header">
            <div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">
                <div class="d-inline-flex mt-3 mt-sm-0">
                    <div class="input-group">
                        <span class="input-group-text">Filter Tanggal</span>
                        <input type="text" class="form-control wmin-200" name="date" id="date" value="{{ date('Y/01/01') }} - {{ date('Y/m/t') }}" placeholder="Pilih Tanggal" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="row card-summary">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-body bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0" id="summary-digital">0</h4>
                        Total Digital
                    </div>
                    <i class="ph-laptop ph-2x ms-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-body bg-success text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0" id="summary-printed">0</h4>
                        Total Cetak
                    </div>
                    <i class="ph-book-open ph-2x ms-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-body bg-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0" id="summary-analog">0</h4>
                        Total Analog
                    </div>
                    <i class="ph-film-strip ph-2x ms-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-body bg-info text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0" id="summary-total">0</h4>
                        Total Semua
                    </div>
                    <i class="ph-database ph-2x ms-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card" id="card-top-media">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-trophy me-1 text-warning"></i>
                        Top 5 Jenis Media
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-primary bg-opacity-10 text-primary" id="badge-top-media">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="data-top-media"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card" id="card-top-worksheet">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-trophy me-1 text-success"></i>
                        Top 5 Jenis Bahan
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-success bg-opacity-10 text-success" id="badge-top-worksheet">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="data-top-worksheet"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card" id="card-media-type">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-chart-pie me-1"></i>
                        Distribusi Jenis Media
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-primary bg-opacity-10 text-primary" id="badge-media-type">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-media-type" style="width:100%; height:350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card" id="card-worksheet">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-chart-pie me-1"></i>
                        Distribusi Jenis Bahan
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-success bg-opacity-10 text-success" id="badge-worksheet">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-worksheet" style="width:100%; height:350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card" id="card-total-collection">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-chart-pie-slice me-1"></i>
                        Distribusi Koleksi
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-info bg-opacity-10 text-info" id="badge-total-collection">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-total-collection" style="width:100%; height:350px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-7">
            <div class="card" id="card-collection-status">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-chart-bar me-1"></i>
                        Status Koleksi
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary" id="badge-status-total">0 Item</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-collection-status" style="width:100%; height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card" id="card-activity">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0">
                        <i class="ph-clock-counter-clockwise me-1"></i>
                        Aktivitas Terbaru
                    </h6>
                    <div class="ms-auto">
                        <span class="badge bg-indigo bg-opacity-10 text-indigo">10 Data</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:442px; height:442px; overflow-y:auto;">
                        <table class="table table-hover table-striped table-xs">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-nowrap" style="width: 35px;">No</th>
                                    <th class="text-nowrap" style="width: 90px;">Aksi</th>
                                    <th class="text-nowrap">User</th>
                                    <th class="text-nowrap" style="width: 110px;">Tanggal</th>
                                    <th class="text-nowrap">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="data-activity"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        datePickerBasic('#date');

        $('#date').on('apply.daterangepicker', function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + " - " + picker.endDate.format(picker.locale.format));

            loadAllStatistic();
        });

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
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-top-media');
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
                        <div class="text-center text-muted py-4">
                            <i class="ph-info ph-2x d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Tidak ada data</p>
                        </div>
                    `);

                    onLoading('close', '#card-top-media');

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
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">${item.name}</span>
                                <span class="text-muted">${item.value} (${percent}%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-${color}" role="progressbar" style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    `;
                });

                $('#data-top-media').html(html);

                onLoading('close', '#card-top-media');
            },
            error: function(response) {
                onLoading('close', '#card-top-media');
                responseError(response);
            }
        });
    }

    function topWorksheetData() {
        $.ajax({
            url: '{{ url("dashboard/data-worksheet") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-top-worksheet');
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
                        <div class="text-center text-muted py-4">
                            <i class="ph-info ph-2x d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Tidak ada data</p>
                        </div>
                    `);

                    onLoading('close', '#card-top-worksheet');

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
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">${item.name}</span>
                                <span class="text-muted">${item.value} (${percent}%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-${color}" role="progressbar" style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    `;
                });

                $('#data-top-worksheet').html(html);

                onLoading('close', '#card-top-worksheet');
            },
            error: function(response) {
                onLoading('close', '#card-top-worksheet');
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
                $('#data-activity').html('');

                onLoading('show', '#card-activity');
            },
            success: function(response) {
                if(response.length > 0) {
                    $.each(response, function(i, val) {
                        var nomor = i + 1;
                        var action = val.ACTION || '-';
                        var user = val.ACTIONBY || '-';
                        var date = val.ACTIONDATE ? moment(val.ACTIONDATE).format('DD/MM/YYYY HH:mm') : '-';
                        var description = val.NOTE || '-';
                        var actionBadge = '';
                        var actionLower = action.toLowerCase();

                        if(actionLower.includes('create') || actionLower.includes('tambah')) {
                            actionBadge = '<span class="badge bg-success bg-opacity-10 text-success fs-xs">' + action + '</span>';
                        } else if(actionLower.includes('update') || actionLower.includes('edit')) {
                            actionBadge = '<span class="badge bg-primary bg-opacity-10 text-primary fs-xs">' + action + '</span>';
                        } else if(actionLower.includes('delete') || actionLower.includes('hapus')) {
                            actionBadge = '<span class="badge bg-danger bg-opacity-10 text-danger fs-xs">' + action + '</span>';
                        } else {
                            actionBadge = '<span class="badge bg-secondary bg-opacity-10 text-secondary fs-xs">' + action + '</span>';
                        }

                        $('#data-activity').append(`
                            <tr>
                                <td class="text-center">${ nomor }</td>
                                <td class="text-nowrap">${ actionBadge }</td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-1 me-2" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph-user fs-sm"></i>
                                        </div>
                                        <span class="fs-sm">${ user }</span>
                                    </div>
                                </td>
                                <td class="text-nowrap"><small class="text-muted fs-xs"><i class="ph-clock me-1"></i>${ date }</small></td>
                                <td><small class="fs-xs">${ description }</small></td>
                            </tr>
                        `);
                    });
                } else {
                    $('#data-activity').html(`
                        <tr>
                            <td class="text-center text-muted" colspan="5">
                                <div class="py-4">
                                    <i class="ph-info ph-2x d-block mb-2 opacity-50"></i>
                                    Tidak ada data aktivitas
                                </div>
                            </td>
                        </tr>
                    `);
                }

                onLoading('close', '#card-activity');
            },
            error: function(response) {
                onLoading('close', '#card-activity');
                responseError(response);
            }
        });
    }

    function chartMediaType() {
        $.ajax({
            url: '{{ url("dashboard/data-media-type") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-media-type');
                onLoading('show', '.card-summary');
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

                if(validData.length === 0 || total === 0) {
                    $('#chart-media-type').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px;">
                            <i class="ph-chart-pie-slice ph-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data untuk ditampilkan</p>
                            <small class="text-muted">Silakan pilih rentang tanggal lain</small>
                        </div>
                    `);

                    onLoading('close', '#card-media-type');
                    onLoading('close', '.card-summary');

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
                        color: ['#2196F3', '#4CAF50', '#FF9800', '#9C27B0', '#F44336', '#00BCD4', '#8BC34A', '#FFC107', '#E91E63', '#3F51B5']
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });

                onLoading('close', '#card-media-type');
                onLoading('close', '.card-summary');
            },
            error: function(response) {
                onLoading('close', '#card-media-type');
                onLoading('close', '.card-summary');
                responseError(response);
            }
        });
    }

    function chartWorksheet() {
        $.ajax({
            url: '{{ url("dashboard/data-worksheet") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-worksheet');
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
                            <i class="ph-files ph-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data untuk ditampilkan</p>
                            <small class="text-muted">Silakan pilih rentang tanggal lain</small>
                        </div>
                    `);

                    onLoading('close', '#card-worksheet');

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
                        color: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#F44336', '#00BCD4', '#8BC34A', '#FFC107', '#E91E63', '#3F51B5']
                    }]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });

                onLoading('close', '#card-worksheet');
            },
            error: function(response) {
                onLoading('close', '#card-worksheet');
                responseError(response);
            }
        });
    }

    function chartTotalCollection() {
        $.ajax({
            url: '{{ url("dashboard/data-total-works") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-total-collection');
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
                            <i class="ph-stack ph-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data untuk ditampilkan</p>
                            <small class="text-muted">Silakan pilih rentang tanggal lain</small>
                        </div>
                    `);

                    onLoading('close', '#card-total-collection');

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
                    color: ['#2196F3', '#4CAF50', '#FF9800'],
                    series: [
                        {
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
                        }
                    ]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });

                onLoading('close', '#card-total-collection');
            },
            error: function(response) {
                onLoading('close', '#card-total-collection');
                responseError(response);
            }
        });
    }

    function chartCollectionStatus() {
        $.ajax({
            url: '{{ url("dashboard/data-collection-status") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                date: $('#date').val()
            },
            beforeSend: function() {
                onLoading('show', '#card-collection-status');
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
                            <i class="ph-clipboard-text ph-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data untuk ditampilkan</p>
                            <small class="text-muted">Silakan pilih rentang tanggal lain</small>
                        </div>
                    `);

                    onLoading('close', '#card-collection-status');

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
                    xAxis: [
                        {
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
                        }
                    ],
                    yAxis: [
                        {
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
                        }
                    ],
                    series: [
                        {
                            name: 'Total',
                            type: 'bar',
                            barWidth: '50%',
                            itemStyle: {
                                borderRadius: [6, 6, 0, 0],
                                color: function(params) {
                                    var colorList = ['#FFA726', '#66BB6A', '#EF5350', '#EC407A'];
                                    return colorList[params.dataIndex] || '#9E9E9E';
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
                        }
                    ]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    chart.resize();
                });

                onLoading('close', '#card-collection-status');
            },
            error: function(response) {
                onLoading('close', '#card-collection-status');
                responseError(response);
            }
        });
    }
</script>
