@extends('backend::layouts.master')

@section('style')
<style>
    div.dataTables_info {
        font-style: normal;
    }
</style>
@endsection

@section('content')
    <div id="content">

        {{--@include('backend::generals.query')--}}

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
                                        @foreach($indexColumnNames as $columnName)
                                        <th>{{ $columnName }}</th>
                                        @endforeach
                                        @if($buttons['edit'] || $buttons['delete'] || $buttons['show'] || $actions)
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
            var routeName = '{{ $routeName }}';
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
                    @foreach($indexColumns as $index => $column)
                        @if ($index < count($indexColumnNames))
                            @if(isset($orderAbles[$column]) && $orderAbles[$column])
                                { "orderable" : true, "name": '{{ $column }}' },
                            @else
                                { "orderable" : false, "name": '{{ $column }}' },
                            @endif
                        @endif
                    @endforeach
                    @if($actions || $buttons['edit'] || $buttons['delete'] || $buttons['show'])
                        { "orderable" : false },
                    @endif
                ],
                @if(isset($orderSorts))
                "order": [
                    @foreach($orderSorts as $sortKey => $sortValue)
                        [{{ $sortKey }}, '{{ $sortValue }}'],
                    @endforeach
                ],
                @endif
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 25,
                "columnDefs": [
                    @foreach($widths as $widthKey => $widthValue)
                        {
                            "targets": "{{ $widthKey }}",
                            "width": "{{ $widthValue }}"
                        },
                    @endforeach
                    @if($buttons['edit'] || $buttons['delete'] || $buttons['show'] || $actions)
                        {
                        "targets": -1,
                        "data": null,
                        "defaultContent": ''
                            @if($operateStyle)
                                @if($buttons['edit'])
                                + '<a name="edit_btn" class="btn btn-primary" permission="admin.{{ $routeName }}.edit">编辑</a>&nbsp;'
                                @endif
                                @if($buttons['delete'])
                                + '<a name="delete_btn" class="btn btn-primary" permission="admin.{{ $routeName }}.delete">删除</a>&nbsp;'
                                @endif
                                @if($buttons['show'])
                                + '<a name="show_btn" class="btn btn-primary" permission="admin.{{ $routeName }}.show">详情</a>&nbsp;'
                                @endif
                                @foreach($actions as $action)
                                + '<a name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}" class="btn btn-primary">{{ $action['text'] }}</a>&nbsp;'
                                @endforeach
                            @else
                                @if($buttons['edit'] || $buttons['delete'] || $buttons['show'] || $actions)
                                + '<div class="btn-group"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作<span class="caret"></span></button><ul class="dropdown-menu">'
                                    @if($buttons['edit'])
                                    + '<li class="edit_btn"><a href="javascript:void(0);" name="edit_btn" permission="admin.{{ $routeName }}.edit">编辑</a></li><li class="divider"></li>'
                                    @endif
                                    @if($buttons['delete'])
                                    + '<li class="delete_btn"><a href="javascript:void(0);" name="delete_btn" permission="admin.{{ $routeName }}.delete">删除</a></li><li class="divider"></li>'
                                    @endif
                                    @if($buttons['show'])
                                    + '<li class="show_btn"><a href="javascript:void(0);" name="show_btn" permission="admin.{{ $routeName }}.show">详情</a></li><li class="divider"></li>'
                                    @endif
                                    @foreach($actions as $action)
                                    + '<li class="{{ $action['name'] }}"><a href="javascript:void(0);" name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}">{{ $action['text'] }}</a></li><li class="divider"></li>'
                                    @endforeach
                                + '</ul></div>'
                                @endif
                            @endif
                        }
                    @endif
                ],
                "ajax": {
                    "url": "/admin/{{ $routeName }}/search"
                }
            });
            // table end

            var buttons = '';
            @if($buttons['create'])
                buttons += '<a href="admin/{{ $routeName }}/create" id="create_btn" class="btn btn-primary" permission="admin.{{ $routeName }}.create">新增</a>';
            @endif
            @if($buttons['queryExport'])
                buttons += '<a href="/admin/{{ $routeName }}/queryExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">列表导出</a>';
            @endif
            @if($buttons['dbExport'])
                buttons += '<a href="/admin/{{ $routeName }}/dbExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">全表导出</a>';
            @endif
            $("div.dt-toolbar div:first").html(buttons);
	    });
    </script>
@endsection