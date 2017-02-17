@extends('backend::layouts.master')

@section('style')
<style>
    div.dataTables_info {
        font-style: normal;
    }
    .index-image {
        width: 50px;
        height: 50px;
    }
</style>
@endsection

@section('content')
    <div id="content">

        @include('backend::general.query')

        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if(isset($custom_id))
                        <p><a href="{{ $custom_id_back_url }}" class="btn btn-primary">返回</a></p>
                    @endif

                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>列表</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        @foreach($model->indexColumnNames as $columnName)
                                        <th>{{ $columnName }}</th>
                                        @endforeach
                                        @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['show'] || $model->actions)
                                        <th>操作</th>
                                        @endif
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="delete_token" value="{{ csrf_token() }}"/>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // table start
            var table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "bStateSave": false,
                "searching": true,
                "language": {
                    "sProcessing": "处理中...",
                    "sLengthMenu": "显示 _MENU_ 项数据",
                    "sZeroRecords": "没有匹配结果",
                    "sInfo": "显示第 _START_ 至 _END_ 项数据，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项数据，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix": "",
                    "sSearch": "搜索: ",
                    "sUrl": "",
                    "sEmptyTable": "表中数据为空",
                    "sLoadingRecords": "载入中...",
                    "sInfoThousands": ",",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上一页",
                        "sNext": "下一页",
                        "sLast": "末页"
                    },
                    "oAria": {
                        "sSortAscending": ": 以升序排列此列",
                        "sSortDescending": ": 以降序排列此列"
                    }
                },
                "columns" : [
                    @foreach($model->indexColumns as $index => $column)
                        @if ($index < count($model->indexColumnNames))
                            @if(isset($model->orderAbles[$column]) && $model->orderAbles[$column])
                                { "orderable" : true, "name": '{{ $column }}' },
                            @else
                                { "orderable" : false, "name": '{{ $column }}' },
                            @endif
                        @endif
                    @endforeach
                    @if($model->actions || $model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['show'])
                        { "orderable" : false },
                    @endif
                ],
                @if(isset($model->orderSorts))
                "order": [
                    @foreach($model->orderSorts as $sortKey => $sortValue)
                        [{{ $sortKey }}, '{{ $sortValue }}'],
                    @endforeach
                ],
                @endif
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 25,
                "columnDefs": [
                    @foreach($model->widths as $widthKey => $widthValue)
                        {
                            "targets": "{{ $widthKey }}",
                            "width": "{{ $widthValue }}"
                        },
                    @endforeach
                    @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['show'] || $model->actions)
                        {
                        "targets": -1,
                        "data": null,
                        "defaultContent": ''
                            @if($model->operateStyle)
                                @if($model->buttons['edit'])
                                + '<a name="edit_btn" class="btn btn-primary" permission="admin.{{ $model->routeName }}.edit">编辑</a>&nbsp;'
                                @endif
                                @if($model->buttons['delete'])
                                + '<a name="delete_btn" class="btn btn-primary" permission="admin.{{ $model->routeName }}.delete">删除</a>&nbsp;'
                                @endif
                                @if($model->buttons['show'])
                                + '<a name="show_btn" class="btn btn-primary" permission="admin.{{ $model->routeName }}.show">详情</a>&nbsp;'
                                @endif
                                @foreach($model->actions as $action)
                                + '<a name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}" class="btn btn-primary">{{ $action['text'] }}</a>&nbsp;'
                                @endforeach
                            @else
                                @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['show'] || $model->actions)
                                + '<div class="btn-group"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作<span class="caret"></span></button><ul class="dropdown-menu">'
                                    @if($model->buttons['edit'])
                                    + '<li class="edit_btn"><a href="javascript:void(0);" name="edit_btn" permission="admin.{{ $model->routeName }}.edit">编辑</a></li><li class="divider"></li>'
                                    @endif
                                    @if($model->buttons['delete'])
                                    + '<li class="delete_btn"><a href="javascript:void(0);" name="delete_btn" permission="admin.{{ $model->routeName }}.delete">删除</a></li><li class="divider"></li>'
                                    @endif
                                    @if($model->buttons['show'])
                                    + '<li class="show_btn"><a href="javascript:void(0);" name="show_btn" permission="admin.{{ $model->routeName }}.show">详情</a></li><li class="divider"></li>'
                                    @endif
                                    @foreach($model->actions as $action)
                                    + '<li class="{{ $action['name'] }}"><a href="javascript:void(0);" name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}">{{ $action['text'] }}</a></li><li class="divider"></li>'
                                    @endforeach
                                + '</ul></div>'
                                @endif
                            @endif
                        }
                    @endif
                ],
                "ajax": {
                    "url": "{{ $model->routeName }}/search"
                }
            });
            // table end

            var buttons = '';
            @if($model->buttons['create'])
                buttons += '<a href="{{ $model->routeName }}/create" id="create_btn" class="btn btn-primary" permission="admin.{{ $model->routeName }}.create">新增</a>';
            @endif
            @if($model->buttons['queryExport'])
                buttons += '<a href="{{ $model->routeName }}/queryExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">列表导出</a>';
            @endif
            @if($model->buttons['dbExport'])
                buttons += '<a href="{{ $model->routeName }}/dbExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">全表导出</a>';
            @endif
            $("div.dt-toolbar div:first").html(buttons);

            @if(count($model->query) > 0)
                $('#query').on('click', function () {
                    @foreach($model->query as $qv)
                        var index = '{{ array_search($qv['column'], $model->indexColumns) }}';
                        @if(isset($qv['type']) && $qv['type'] == 'checkbox')
                            table.columns(index).search($('input[name="{{ $qv['column']}}"]:checked').map(function () {
                                return this.value;
                            }).get());
                        @elseif(isset($qv['operator']) && $qv['operator'] == 'between')
                            table.columns(index).search([$('#' + '{{ $qv['column'] }}' + '_from').val(), $('#' + '{{ $qv['column'] }}' + '_to').val()]);
                        @elseif(strstr($qv['column'], '.') !== FALSE)
                            table.columns(index).search($('#' + '{{ str_replace('.', '-', $qv['column']) }}').val());
                        @else
                            table.columns(index).search($('#' + '{{ $qv['column'] }}').val());
                        @endif
                    @endforeach
                    table.draw();
                });

                @foreach($model->query as $qv)
                    @if(isset($qv['type']) && $qv['type'] == 'date')
                        @if(isset($qv['operator']) && $qv['operator'] == 'between')
                        $('{{ isset($qv['operator']) && $qv['operator'] == 'between' ? '.between' : '.single' }}').datepicker({
                            dateFormat: 'yy-mm-dd',
                            changeMonth: true,
                            changeYear: true,
                            numberOfMonths: 1,
                            prevText: '<i class="fa fa-chevron-left"></i>',
                            nextText: '<i class="fa fa-chevron-right"></i>',
                            yearRange: '{{ isset($qv['format']['yearRange']) ? $qv['format']['yearRange'] : 'c-10:c+10' }}',
                            minDate: '',
                            maxDate: '',
                            monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
                            dayNamesMin: ['日', '一', '二', '三', '四', '五', '六']
                        });
                        @endif
                    @endif
                @endforeach
            @endif

            table.on( 'draw.dt', function () {
                var data = table.data();
                for (var i = 0; i < data.length; i++) {
                    @foreach($model->actions as $action)
                        @if($action['type'] == 'confirm' || $action['type'] == 'dialog')
                            @if(isset($action['where']))
                                @foreach($action['where'] as $wk => $wv)
                                    {{ $list_td = array_flip($model->indexColumns)[$wk]}}
                                    var flag = false;
                                    @foreach($wv as $val)
                                    if(data[i][parseInt('{{ $list_td }}')] == parseInt('{{ $val }}')) {
                                        flag = true;
                                    }
                                    @endforeach
                                    if(!flag) {
                                        $('tr:eq('+(i+1)+') '+'a[name={{ $action['name'] }}]').hide();
                                        $('tr:eq('+(i+1)+') '+'.divider:last').hide();
                                    }
                                @endforeach
                            @endif
                        @endif
                    @endforeach

                    @foreach($model->indexColumnRenames as $rek => $rev)
                        {{$column_no = array_flip($model->indexColumns)[$rek]}}
                        var tr = $('tr').eq(i+1).children('td').eq(parseInt('{{$column_no}}'));
                        var value = data[i][parseInt('{{$column_no}}')];
                        @if($rev['type'] == 'normal')
                            @foreach($rev['param'] as $key => $value)
                            tr.html('{!! $value !!}');
                            @endforeach
                        @elseif($rev['type'] == 'dialog')
                            tr.html('<a href="javascript:void(0);" name="{{$rev['param']['name']}}">' + value + '</a>');
                        @elseif($rev['type'] == 'html')
                            tr.html(sprintf('{!! $rev["param"] !!}', 1, value));
                        @elseif($rev['type'] == 'image')
                            tr.html('<a href="' + value + '" target="_blank"><img class="index-image" src="' + value + '"></a>');
                        @elseif($rev['type'] == 'images')
                            var images = '';
                            value.forEach(function (item) {
                                images += '<a href="' + item + '" target="_blank"><img class="index-image" src="' + item + '"></a>&nbsp;';
                            });
                            tr.html(images);
                        @elseif($rev['type'] == 'selector')
                            tr.html(JSON.parse('{!! json_encode(${$rev["param"]}) !!}')[tr.html()]);
                        @elseif($rev['type'] == 'limit')
                            tr.html(tr.html().slice(0, parseInt('{{ $rev['param'] }}')));
                        @endif
                    @endforeach
                }
                permission();
            });

            @if($model->buttons['edit'])
                $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
                    if(isDisabled($(this))) {
                        var data = table.row($(this).parents('tr')).data();
                        window.location = '{{ $model->routeName }}/' + data[0] + '/edit/';
                    }
                });
            @endif

            @if($model->buttons['delete'])
                $('#dt_basic tbody').on('click', 'a[name=delete_btn]', function () {
                    if(isDisabled($(this))) {
                        var page_info = table.page.info();
                        var page = page_info.page;
                        if (page_info.length == 1 && page != 0) {
                            page = page - 1;
                        }
                        var url = '{{ $model->routeName }}/' + table.row($(this).parents('tr')).data()[0];
                        if(confirm('删除这条记录?')) {
                            $.ajax({
                                type: "DELETE",
                                data: { '_token' : $('#delete_token').val() },
                                url: url,
                                success: function(result) {
                                    if (result == 1) {
                                        $('#dt_basic').dataTable().fnPageChange(page);
                                        $(".tips").html('<div class="alert alert-success fade in">'
                                        + '<button class="close" data-dismiss="alert">×</button>'
                                        + '<i class="fa-fw fa fa-check"></i>'
                                        + '<strong>成功</strong>' + ' ' + '删除成功。'
                                        + '</div>');
                                    } else {
                                        $(".tips").html('<div class="alert alert-danger fade in">'
                                        + '<button class="close" data-dismiss="alert">×</button>'
                                        + '<i class="fa-fw fa fa-warning"></i>'
                                        + '<strong>失败</strong>' + ' ' + '删除失败。'
                                        + '</div>');
                                    }
                                },
                                error: function() {
                                    $(".tips").html('<div class="alert alert-danger fade in">'
                                    + '<button class="close" data-dismiss="alert">×</button>'
                                    + '<i class="fa-fw fa fa-warning"></i>'
                                    + '<strong>失败</strong>' + ' ' + '请求失败，请稍后再试。'
                                    + '</div>');
                                }
                            });
                            hideTips();
                        }
                    }
                });
            @endif
        });
    </script>
@endsection