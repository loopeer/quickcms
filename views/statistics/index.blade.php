@extends('backend::layouts.master')
@section('content')
    <style type="text/css">
        #content h2 {font-family:'黑体';}
        #content ul,#content h3{ margin:0; padding:0; font-family:'黑体'; font-size:14px;}
        #content ul h3{ color:black; height:12px; padding-top:17px;}
        #content ul{width:100%; padding:10px 0; background:#EDEDED;margin-bottom: 18px;border: 8px solid #D80C18}
        #content span{ color:#4C463A; font-size:24px; height:43px; line-height:63px; text-align:center; font-weight:bold; display:inline-block;}
        #content li{list-style:none; float:left; width:25%; height:62px; border-right:1px  dotted #CC9933; text-align:center;}
    </style>

    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12">
                    <ul style="height: {{ (count($statistics) / 4) * 78 }}px;min-height: 108px;">
                        @foreach($statistics as $statistic)
                        <li>
                            <h3>{{ $statistic->statistic_key }}</h3>
                            <span>{{ $statistic->statistic_value }}</span>
                        </li>
                        @endforeach
                    </ul>
                </article>
            </div>
        </section>
    </div>

    <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
        <header>
            <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
            <h2>趋势变化图</h2>
            <ul class="nav nav-tabs pull-right in" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">日</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#s2"><i class="fa fa-calendar"></i> <span class="hidden-mobile hidden-tablet">月</span></a>
                </li>
            </ul>
        </header>

        <div class="no-padding">
            <div class="jarviswidget-editbox">
                test
            </div>
            <div class="widget-body">
                <div id="myTabContent" class="tab-content">
                    <section class="col" style="margin-left:50px;margin-top:30px;">
                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                <input type="text" class="date" id="chart_date" placeholder="选择年月">
                        </label>
                    </section>
                    <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
                    </div>
                    <div class="tab-pane fade" id="s2">
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
        $(document).ready(function() {

            $('.date').datepicker({
                dateFormat:'yy-mm',
                changeMonth: true,
                changeYear:true,
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月']
            });
            var date = new Date();
            var chart_month = date.getMonth() + 1;
            var chart_year = date.getFullYear();
            if(chart_month < 10) {
                chart_month = '0' + chart_month;
            }
            var chart_date = chart_year + '-' + chart_month;
            getCharts(chart_date, chart_year);

            $('#chart_date').on('change', function() {
                chart_date = $('#chart_date').val();
                chart_year = chart_date.substring(0, 4);
                getCharts(chart_date, chart_year);
            });
        });

        function getCharts(chart_date, chart_year) {
            $('#myTab').on('click', 'li', function () {
                $('#count_tweet').attr('data-percent', 80);
            });
            var dataTmp = "";
            $.ajax({
                type: 'get',
                data: {'chart_date': chart_date},
                dataType: 'json',
                url: '/admin/statistics/chartDays',
                success: function (result) {
                    dataTmp = "";
                    $.each(result, function (index, value) {
                        var data = "";
                        for (var i = 1; i < 32; i++) {
                            data += value.data[i] + ",";
                        }
                        data = data.substring(0, data.length - 1);
                        dataTmp += "{name: '" + value.name + "',data: [" + data + "]}" + ",";
                    });
                    dataTmp = dataTmp.substring(0, dataTmp.length - 1);
                    $('#s1').highcharts({
                        title: {
                            text: '趋势变化图',
                            x: -20 //center
                        },
                        subtitle: {
                            text: 'Source: loopeer.com',
                            x: -20
                        },
                        xAxis: {
                            categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15',
                                '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31']
                        },
                        yAxis: {
                            title: {
                                text: chart_date
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                        },
                        tooltip: {
                            valueSuffix: ''
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: eval("[" + dataTmp + "]")
                    });
                }
            });

            $.ajax({
                type: 'get',
                dataType: 'json',
                data: {'chart_year': chart_year},
                url: '/admin/statistics/chartMonths',
                success: function (result) {
                    dataTmp = "";
                    $.each(result, function (index, value) {
                        var data = "";
                        for (var i = 1; i < 13; i++) {
                            data += value.data[i] + ",";
                        }
                        data = data.substring(0, data.length - 1);
                        dataTmp += "{name: '" + value.name + "',data: [" + data + "]}" + ",";
                    });
                    dataTmp = dataTmp.substring(0, dataTmp.length - 1);
                    $('#s2').highcharts({
                        title: {
                            text: '趋势变化图',
                            x: -20 //center
                        },
                        subtitle: {
                            text: 'Source: loopeer.com',
                            x: -20
                        },
                        xAxis: {
                            categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
                        },
                        yAxis: {
                            title: {
                                text: chart_year
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                        },
                        tooltip: {
                            valueSuffix: ''
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: eval("[" + dataTmp + "]")
                    });
                }
            });
        }
    </script>
@endsection