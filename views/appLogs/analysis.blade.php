@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <div class="jarviswidget jarviswidget-sortable" id="charts-content" data-widget-colorbutton="false"
             data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
             data-widget-fullscreenbutton="false" data-widget-custombutton="false" role="widget" style="position: relative; opacity: 1; left: 0px; top: 0px;">
            <header role="heading">
                <h2><strong>统计图</strong></h2>
                <ul class="nav nav-tabs pull-right in" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" href="#day-charts"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">日</span></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#month-charts"><i class="fa fa-calendar"></i> <span class="hidden-mobile hidden-tablet">月</span></a>
                    </li>
                </ul>
            </header>
            <div role="content">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body">
                    <div id="myTabContent" class="tab-content">
                        <div class="col col-3">
                            选择年份:
                            <label class="select">
                                <select name="year" id="year">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" @if($year == \Carbon\Carbon::now()->year) selected @endif>{{ $year }}</option>
                                    @endforeach
                                </select> <i></i> </label>
                        </div>
                        <div class="col col-3">
                            选择月份:
                            <label class="select">
                                <select name="month" id="month">
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{$i}}" @if(\Carbon\Carbon::now()->month == $i) selected @endif>{{ $i }}月</option>
                                    @endfor
                                </select> <i></i> </label>
                        </div>
                        <div class="tab-pane fade active in padding-10 no-padding-bottom" id="day-charts"></div>
                        <div class="tab-pane fade" id="month-charts">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('loopeer/quickcms/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('loopeer/quickcms/js/highcharts/exporting.js') }}"></script>
    <script>
        var year = $('#year').val();
        var month = $('#month').val();

        requestData(year, month);

        function requestData(year, month) {
            $.get('/admin/appLogStatistics/monthCharts', {
                year: year
            }, function(result) {
                if (result.code == 0) {
                    month_charts.series[0].setData(result.data.total_data);
                    month_charts.series[1].setData(result.data.android_data);
                    month_charts.series[2].setData(result.data.ios_data);
                }
            });

            $.get('/admin/appLogStatistics/dayCharts', {
                year: year,
                month: month
            }, function(result) {
                if (result.code == 0) {
                    day_charts.series[0].setData(result.data.total_data);
                    day_charts.series[1].setData(result.data.android_data);
                    day_charts.series[2].setData(result.data.ios_data);
                }
            });
        }

        $('#year').change(function () {
            year = $('#year').val();
            month = $('#month').val();
            month_charts.setTitle( {text: year + '年年度统计图' });// 更新标题的文字
            day_charts.setTitle( {text: year + '年' + month + '月统计图' });// 更新标题的文字
            requestData(year, month);
        });

        $('#month').change(function () {
            year = $('#year').val();
            month = $('#month').val();
            month_charts.setTitle( {text: year + '年年度统计图' });// 更新标题的文字
            day_charts.setTitle( {text: year + '年' + month + '月统计图' });// 更新标题的文字
            requestData(year, month);
        });

        $('#month-charts').highcharts({
            title: {
                text: year + '年年度统计图',
                x: -20
            },
            xAxis: {
                title: {
                    text: '月份'
                },
                categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
            },
            yAxis: {
                title: {
                    text: '数量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '总请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'android请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'iOS请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }]
        });
        $('#day-charts').highcharts({
            title: {
                text: year + '年' + month + '月统计图',
                x: -20
            },
            xAxis: {
                title: {
                    text: '日期'
                },
                categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
            },
            yAxis: {
                title: {
                    text: '数量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '总请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'android请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'iOS请求次数',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }]
        });
        var month_charts = $('#month-charts').highcharts();
        var day_charts = $('#day-charts').highcharts();
    </script>
@endsection