<div class="page-header page-header-light shadow mb-4">
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
                        <span class="input-group-text">Tanggal</span>
                        <input type="text" class="form-control wmin-200" name="date" id="date" value="{{ date('Y/m/01') }} - {{ date('Y/m/d') }}" placeholder="Pilih Tanggal" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content pt-0">
    <div class="row">
        <div class="col-md-4">
            <div class="card" id="card-media-type">
                <div class="card-header">
                    <h5 class="hstack gap-2 mb-0">Jenis Media</h5>
                </div>
                <div class="card-body">
                    <div id="chart-media-type" style="width:100%; height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" id="card-worksheet">
                <div class="card-header">
                    <h5 class="hstack gap-2 mb-0">Jenis Bahan</h5>
                </div>
                <div class="card-body">
                    <div id="chart-worksheet" style="width:100%; height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" id="card-total-collection">
                <div class="card-header">
                    <h5 class="hstack gap-2 mb-0">Total Koleksi</h5>
                </div>
                <div class="card-body">
                    <div id="chart-total-collection" style="width:100%; height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" id="card-collection-status">
                <div class="card-header">
                    <h5 class="hstack gap-2 mb-0">Status Koleksi</h5>
                </div>
                <div class="card-body">
                    <div id="chart-collection-status" style="width:100%; height:500px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" id="card-activity">
                <div class="card-header">
                    <h5 class="hstack gap-2 mb-0">10 Data Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-fix-header" style="min-height:500px;">
                        <table class="table">
                            <thead class="text-bg-light">
                                <tr>
                                    <th class="text-nowrap">No</th>
                                    <th class="text-nowrap">Aksi</th>
                                    <th class="text-nowrap">User</th>
                                    <th class="text-nowrap">Tanggal</th>
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
                        var action = val.ACTION;
                        var user = val.ACTIONBY;
                        var date = moment(val.ACTIONDATE).format('DD/MM/YYYY');
                        var description = val.NOTE;


                        $('#data-activity').append(`
                            <tr>
                                <td class="align-top text-nowrap">${ nomor }</td>
                                <td class="align-top text-nowrap">${ action }</td>
                                <td class="align-top text-wrap">${ user }</td>
                                <td class="align-top">${ date }</td>
                                <td class="align-top text-wrap">${ description }</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#data-activity').html('<tr><td class="text-center" colspan="5">Tidak ada data</td></tr>');
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
            },
            success: function(response) {
                var chartSelector = document.getElementById('chart-media-type');
                var chart = echarts.init(chartSelector, null, {
                    renderer: 'canvas'
                });

                var option = {
                    tooltip: {
                        trigger: 'item',
                    },
                    legend: {
                        orient: 'horizontal',
                        left: 'center'
                    },
                    series: [
                        {
                            type: 'pie',
                            smooth: true,
                            radius: '95%',
                            top: '30%',
                            data: response,
                            label: {
                                show: true,
                                position: 'inside',
                                formatter: '{c}',
                                textStyle: {
                                    color: '#fff',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: true
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };

                option && chart.setOption(option);

                onLoading('close', '#card-media-type');
            },
            error: function(response) {
                onLoading('close', '#card-media-type');
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
                var chartSelector = document.getElementById('chart-worksheet');
                var chart = echarts.init(chartSelector, null, {
                    renderer: 'canvas'
                });

                var option = {
                    tooltip: {
                        trigger: 'item',
                    },
                    legend: {
                        orient: 'horizontal',
                        left: 'center'
                    },
                    series: [
                        {
                            type: 'pie',
                            smooth: true,
                            radius: '95%',
                            top: '10%',
                            data: response,
                            label: {
                                show: true,
                                position: 'inside',
                                formatter: '{c}',
                                textStyle: {
                                    color: '#fff',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: true
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };

                option && chart.setOption(option);

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
                var chartSelector = document.getElementById('chart-total-collection');
                var chart = echarts.init(chartSelector, null, {
                    renderer: 'canvas'
                });

                var option = {
                    tooltip: {
                        trigger: 'item',
                    },
                    legend: {
                        orient: 'horizontal',
                        left: 'center'
                    },
                    series: [
                        {
                            type: 'pie',
                            smooth: true,
                            radius: '95%',
                            top: '10%',
                            data: [
                                { name: 'Digital', value: (response.TOTAL_DIGITAL ?? 0) },
                                { name: 'Cetak', value: (response.TOTAL_PRINTED ?? 0) },
                                { name: 'Analog', value: (response.TOTAL_ANALOG ?? 0) },
                            ],
                            label: {
                                show: true,
                                position: 'inside',
                                formatter: '{c}',
                                textStyle: {
                                    color: '#fff',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: true
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };

                option && chart.setOption(option);

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
                var chartSelector = document.getElementById('chart-collection-status');
                var chart = echarts.init(chartSelector, null, {
                    renderer: 'canvas'
                });

                var option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    grid: {
                        left: 2,
                        right: 2,
                        top: 10,
                        bottom: 0,
                        containLabel: true
                    },
                    xAxis: [
                        {
                            type: 'category',
                            data: response.label,
                            axisTick: {
                                alignWithLabel: true
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#9CA3AF'
                                }
                            },
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: '#E5E7EB'
                                }
                            }
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value',
                            axisLine: {
                                show: true,
                                lineStyle: {
                                    color: '#9CA3AF'
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: '#E5E7EB'
                                }
                            },
                            splitArea: {
                                show: true,
                                areaStyle: {
                                    color: ['rgba(255, 255, 255, .01)', 'rgba(0, 0, 0, .01)']
                                }
                            }
                        }
                    ],
                    axisPointer: [
                        {
                            lineStyle: {
                                color: '#6B7280'
                            }
                        }
                    ],
                    series: [
                        {
                            name: 'Total',
                            type: 'bar',
                            smooth: true,
                            barWidth: '60%',
                            data: response.data
                        }
                    ]
                };

                option && chart.setOption(option);

                onLoading('close', '#card-collection-status');
            },
            error: function(response) {
                onLoading('close', '#card-collection-status');
                responseError(response);
            }
        });
    }
</script>
