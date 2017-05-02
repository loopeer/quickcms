@extends('backend::layouts.master')
@section('content')
    <div id="content">
        @include('backend::fasts.query')
        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>{{ $model->module . '列表' }}</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        @foreach($model->index as $item)
                                        <th>{{ trans('fasts.' . $model->route . '.' . $item['column']) }}</th>
                                        @endforeach
                                        @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['detail'] || count($model->buttons['actions']) > 0)
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

    <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body custom-scroll terms-body">
                    <div id="left"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // table start
            var table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "bStateSave": {{ $model->state_save ? 1 : 0 }},
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
                    @foreach($model->index as $item)
                        { "orderable" : Boolean('{{ isset($item['order']) }}'), "name": '{{ $item['column'] }}' },
                    @endforeach
                    @if(count($model->buttons['actions']) > 0 || $model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['detail'])
                        { "orderable" : false },
                    @endif
                ],
                "order": [
                    @foreach($model->index as $orderKey => $orderItem)
                        @if(isset($orderItem['order']))
                        [{{ $orderKey }}, '{{ $orderItem['order'] }}'],
                        @endif
                    @endforeach
                ],
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 25,
                "columnDefs": [
                    @foreach($model->index as $widthKey => $widthItem)
                        @if(isset($widthItem['width']))
                        { "targets": parseInt('{{ $widthKey }}'), "width": "{{ $widthItem['width'] }}" },
                        @endif
                    @endforeach
                    @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['detail'] || count($model->buttons['actions']) > 0)
                        {
                        "targets": -1,
                        "data": null,
                        "defaultContent": ''
                            @if($model->buttons['style'])
                                @if($model->buttons['edit'])
                                + '<a name="edit_btn" class="btn btn-primary" permission="admin.{{ $model->route }}.edit">编辑</a>'
                                @endif
                                @if($model->buttons['delete'])
                                + '<a name="delete_btn" class="btn btn-primary" permission="admin.{{ $model->route }}.delete">删除</a>'
                                @endif
                                @if($model->buttons['detail'])
                                + '<a name="detail_btn" class="btn btn-primary" permission="admin.{{ $model->route }}.show">详情</a>'
                                @endif
                                @foreach($model->buttons['actions'] as $action)
                                + '<a name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}" class="btn btn-primary">{{ $action['text'] }}</a>'
                                @endforeach
                            @else
                                @if($model->buttons['edit'] || $model->buttons['delete'] || $model->buttons['detail'] || count($model->buttons['actions']) > 0)
                                + '<div class="btn-group"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作<span class="caret"></span></button><ul class="dropdown-menu">'
                                    @if($model->buttons['edit'])
                                    + '<li class="edit_btn"><a href="javascript:void(0);" name="edit_btn" permission="admin.{{ $model->route }}.edit">编辑</a></li><li class="divider"></li>'
                                    @endif
                                    @if($model->buttons['delete'])
                                    + '<li class="delete_btn"><a href="javascript:void(0);" name="delete_btn" permission="admin.{{ $model->route }}.delete">删除</a></li><li class="divider"></li>'
                                    @endif
                                    @if($model->buttons['detail'])
                                    + '<li class="detail_btn"><a href="javascript:void(0);" name="detail_btn" permission="admin.{{ $model->route }}.show">详情</a></li><li class="divider"></li>'
                                    @endif
                                    @foreach($model->buttons['actions'] as $action)
                                    + '<li class="{{ $action['name'] }}"><a href="javascript:void(0);" name="{{ $action['name'] }}" permission="{{ $action['permission'] or '' }}">{{ $action['text'] }}</a></li><li class="divider"></li>'
                                    @endforeach
                                + '</ul></div>'
                                @endif
                            @endif
                        }
                    @endif
                ],
                "ajax": {
                    @if($model->redirect_column !== null)
                    "url": "{{ $model->route }}/{{ $model->redirect_value }}/" + "search"
                    @else
                    "url": "{{ $model->route }}/search"
                    @endif
                }
            });
            // table end

            var str = window.location.href;
            var arr = str.split("/");
            var num = arr[arr.length - 1];
            var route ="{{ $model->route }}";
            var route_arr = route.split("/");
            var ro = route_arr[route_arr.length - 1];
            if(num == ro)
            {
                var href = "{{ $model->route }}" + "/create/";
            }else{
                var href = "{{ $model->route }}" + "/create/" + num;
            }

            var buttons = '';
            @if($model->redirect_back_route !== null)
                buttons += '<a href="{{ $model->redirect_back_route }}" style="margin-left: 10px;" class="btn btn-primary">返回</a>';
            @endif
            @if($model->buttons['create'])
                    buttons += '<a href="'+ href +'" id="create_btn"  style="margin-left: 10px;" class="btn btn-primary" permission="admin.{{ $model->route }}.create">新增</a>';
            @endif
            @if($model->buttons['queryExport'])
            @if($model->redirect_column !== null)
                buttons += '<a href="{{ $model->route }}/{{ $model->redirect_value }}/queryExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">列表导出</a>';
            @else
                buttons += '<a href="{{ $model->route }}/queryExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">列表导出</a>';
            @endif
            @endif
            @if($model->buttons['dbExport'])
                @if($model->redirect_column !== null)
                buttons += '<a href="{{ $model->route }}/{{ $model->redirect_value }}/dbExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">全表导出</a>';
                @else
                buttons += '<a href="{{ $model->route }}/dbExport" style="margin-left: 10px;" class="btn btn-primary" target="_blank">全表导出</a>';
                @endif
            @endif
            $("div.dt-toolbar div:first").html(buttons);

            $("#reset").on('click', function (e) {
                $('#query-form').find('input:text').val('');
                $('input:checkbox').removeAttr('checked');
                $('#query-form option:selected').removeAttr('selected');
                $("#query").trigger("click");
            });

            @if(count($query = array_column($model->index, 'query')) > 0)
                $('#query').on('click', function () {
                    @foreach($model->index as $qk => $qv)
                        @if(isset($qv['query']))
                            @if(isset($qv['type']) && $qv['type'] == 'checkbox')
                                table.columns({{ $qk }}).search($('input[name="{{ $qv['column']}}"]:checked').map(function () {
                                    return this.value;
                                }).get());
                            @elseif($qv['query'] == 'between')
                                table.columns({{ $qk }}).search([$('#' + '{{ $qv['column'] }}' + '_from').val(), $('#' + '{{ $qv['column'] }}' + '_to').val()]);
                            @elseif(strstr($qv['column'], '.') !== FALSE)
                                table.columns({{ $qk }}).search($('#' + '{{ str_replace('.', '-', $qv['column']) }}').val());
                            @else
                                table.columns({{ $qk }}).search($('#' + '{{ $qv['column'] }}').val());
                            @endif
                        @endif
                    @endforeach
                    table.draw();
                });
            @endif

            $('.form_datetime').datetimepicker({
                language:  'zh-CN',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });

            $('.form_date').datetimepicker({
                language:  'zh-CN',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });

            table.on( 'draw.dt', function () {
                var data = table.data();
                for (var i = 0; i < data.length; i++) {
                    @foreach($model->buttons['actions'] as $action)
                        @if(($action['type'] == 'confirm' || $action['type'] == 'dialog' || $action['type'] == 'redirect') && isset($action['where']))
                            @foreach($action['where'] as $wk => $wv)
                                {{ $list_td = array_flip(array_column($model->index, 'column'))[$wk] }}
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
                    @endforeach

                    @foreach($model->index as $renameKey => $renameItem)
                        @if(isset($renameItem['type']))
                            var tr = $('tr').eq(i+1).children('td').eq(parseInt('{{$renameKey}}'));
                            var value = data[i][parseInt('{{$renameKey}}')];
                            @if($renameItem['type'] == 'normal')
                                @foreach($renameItem['param'] as $normalKey => $normalItem)
                                    if(value == parseInt('{{ $normalKey }}')) {
                                        tr.html('{!! $normalItem !!}');
                                    }
                                @endforeach
                            @elseif($renameItem['type'] == 'dialog')
                                tr.html('<a href="javascript:void(0);" name="{{$renameItem['param']['name']}}">' + value + '</a>');
                                $('#dt_basic tbody').on('click', 'a[name=' + '{{isset($renameItem['param']['name']) ? $renameItem['param']['name'] : $action['name']}}' + ']', function () {
                                    if(isDisabled($(this))) {
                                        var data = table.row($(this).parents('tr')).data();
                                        $("#dialog .modal-title").html('{{ $renameItem['param']['dialog_title'] }}');
                                        $(this).attr("data-toggle", "modal");
                                        $(this).attr("data-target", "#dialog");
                                        $(this).attr("data-action", "{{$renameItem['param']['url']}}" + data[0]);
                                        $(this).attr("data-id", data[0]);
                                    }
                                });
                            @elseif($renameItem['type'] == 'html')
                                tr.html(sprintf('{!! $renameItem["param"] !!}', 1, value));
                            @elseif($renameItem['type'] == 'image')
                                if(value != null) {
                                    tr.html('<a href="' + value + '" target="_blank"><img class="index-image" src="' + value + '"></a>');
                                }
                            @elseif($renameItem['type'] == 'images')
                                if(value != null) {
                                    var images = '';
                                    value.forEach(function (item) {
                                        images += '<a href="' + item + '" target="_blank"><img class="index-image" src="' + item + '"></a>&nbsp;';
                                    });
                                    tr.html(images);
                                }
                            @elseif($renameItem['type'] == 'select')
                                tr.html(JSON.parse('{!! json_encode(is_array($renameItem["param"]) ? $renameItem["param"] : ${$renameItem["param"]}) !!}')[tr.html()]);
                            @elseif($renameItem['type'] == 'limit')
                                tr.html(tr.html().slice(0, parseInt('{{ $renameItem['param'] }}')));
                            @endif
                        @endif
                    @endforeach
                }
                permission();
            });

            @if($model->buttons['edit'])
                $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
                    if(isDisabled($(this))) {
                        var data = table.row($(this).parents('tr')).data();
                        window.location = '{{ $model->route }}/' + data[0] + '/edit/';
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
                        var str = window.location.href;
                        var arr = str.split("/");
                        var num = arr[arr.length - 1];
                        var route ="{{ $model->route }}";
                        var route_arr = route.split("/");
                        var ro = route_arr[route_arr.length - 1];
                        if(num == ro)
                        {
                            var url = '{{ $model->route }}/' + table.row($(this).parents('tr')).data()[0];
                        }else{
                            var url = '{{ $model->route }}/' + table.row($(this).parents('tr')).data()[0] + '/delete';
                        }
                        //var url = '{{ $model->route }}/' + table.row($(this).parents('tr')).data()[0];
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

            @if($model->buttons['detail']) {
                $('#dt_basic tbody').on('click', 'a[name=detail_btn]', function () {
                    if(isDisabled($(this))) {
                        var data = table.row($(this).parents('tr')).data();
                        $("#dialog .modal-title").html('查看详情');
                        $(this).attr("data-toggle", "modal");
                        $(this).attr("data-target", "#dialog");
                        $(this).attr("data-action", "/admin/{{ $model->route }}/" + data[0]);
                        $(this).attr("data-id", data[0]);
                    }
                });
            }
            @endif

            @foreach($model->buttons['actions'] as $action)
                @if($action['type'] == 'confirm')
                $('#dt_basic tbody').on('click', 'a[name=' + '{{ $action['name'] }}' + ']', function () {
                    if(isDisabled($(this))) {
                        var data = table.row($(this).parents('tr')).data();
                        var page_info = table.page.info();
                        var page = page_info.page;
                        var data_table = $('#dt_basic').dataTable();
                        if (page_info.end - page_info.start == 1) {
                            page -= 1;
                        }
                        if (confirm('{{{ $action['confirm_msg'] or '是否继续操作?' }}}')) {
                            $.ajax({
                                type: 'put',
                                data: {
                                    @foreach($action['data'] as $dataKey => $dataValue)
                                    '{{ $dataKey }}' : '{{ $dataValue }}',
                                    @endforeach
                                },
                                url: '{{ $action['url'] }}' + '/' + data[0],
                                success: function(result) {
                                    if (result) {
                                        data_table.fnPageChange(page);
                                        $(".tips").html('<div class="alert alert-success fade in">'
                                        + '<button class="close" data-dismiss="alert">×</button>'
                                        + '<i class="fa-fw fa fa-check"></i>'
                                        + '<strong>操作成功</strong></div>');

                                    } else {
                                        $(".tips").html('<div class="alert alert-danger fade in">'
                                        + '<button class="close" data-dismiss="alert">×</button>'
                                        + '<i class="fa-fw fa fa-warning"></i>'
                                        + '<strong>操作失败</strong></div>');
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

                @if($action['type'] == 'redirect')
                $('#dt_basic tbody').on('click', 'a[name=' + '{{isset($action['btn_name']) ? $action['btn_name'] : $action['name']}}' + ']', function () {
                     @if(isset($action['redirect_column']))
                    var redirect_key = '{{ array_search($action['redirect_column'],  array_column($model->index, 'column')) }}';
                    var data = table.row($(this).parents('tr')).data();
                    var redirect_value = data[parseInt(redirect_key)];
                    @else
                    var redirect_value = '';
                    @endif
                    window.location.href = '{{$action['url']}}' + '/' + redirect_value;
                });
                @endif

                @if($action['type'] == 'dialog')
                    $('#dt_basic tbody').on('click', 'a[name=' + '{{isset($action['btn_name']) ? $action['btn_name'] : $action['name']}}' + ']', function () {
                        if(isDisabled($(this))) {
                            var data = table.row($(this).parents('tr')).data();
                            $("#dialog .modal-title").html('{{$action['dialog_title']}}');
                            $(this).attr("data-toggle", "modal");
                            $(this).attr("data-target", "#dialog");
                            $(this).attr("data-action", "{{$action['url']}}" + data[0]);
                            $(this).attr("data-id", data[0]);
                        }
                    });

                    @if(!empty($action['form']))
                    $("#dialog .modal-body").after(
                            '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>' +
                            '<button type="button" class="btn btn-primary" id="' + '{{$action['form']['submit_id']}}' +'"><i class="fa fa-check"></i>提交</button>' +
                            '</div>'
                    );
                    $('#' + '{{$action['form']['submit_id']}}').click(function(){
                        var $form = $("#" + '{{$action['form']['form_id']}}');
                        var page_info = table.page.info();
                        var page = page_info.page;
                        var data_table = $('#dt_basic').dataTable();
                        $('#' + '{{$action['form']['submit_id']}}').text("提交");
                        $('#dialog').modal('hide');
                        $($form).submit(function(event) {
                            var form = $(this);
                            $.ajax({
                                type: form.attr('method'),
                                url: form.attr('action'),
                                data: form.serialize()
                            }).done(function(result) {
                                var html = '';
                                if (result == 1 || result.result == true) {
                                    data_table.fnPageChange(page);
                                    html = '<div class="alert alert-success fade in">'
                                    +'<button class="close" data-dismiss="alert">×</button>'
                                    +'<i class="fa-fw fa fa-check"></i>';
                                    if (isObject(result)) {
                                        html += '<strong>成功</strong>'+' '+ result.content +'。'
                                    } else {
                                        html += '<strong>成功</strong>'+' '+ '{{isset($action['form']['success_msg']) ? $action['form']['success_msg'] : '操作成功'}}'+'。'
                                    }
                                    html += '</div>';
                                    $(".tips").html(html);
                                } else {
                                    html = '<div class="alert alert-danger fade in">'
                                    +'<button class="close" data-dismiss="alert">×</button>'
                                    +'<i class="fa-fw fa fa-warning"></i>';
                                    if (isObject(result)) {
                                        html += '<strong>失败</strong>'+' '+ result.content +'。'
                                    } else {
                                        html += '<strong>失败</strong>'+' '+ '{{isset($action['form']['failure_msg']) ? $action['form']['failure_msg'] : '操作失败'}}'+'。'
                                    }
                                    $(".tips").html(html);
                                    $(".tips").html(html);
                                }
                                $form[0].reset();
                                hideTips();
                            });
                            event.preventDefault(); // Prevent the form from submitting via the browser.
                        });
                        function isObject(obj){
                            return (typeof obj=='object')&&obj.constructor==Object;
                        }
                        $form.trigger('submit'); // trigger form submit
                    });
                    @endif
                @endif
            @endforeach

            $('#dialog').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                loadURL(action, $('#dialog .modal-content .modal-body #left'));
            });

            function sprintf() {
                var arg = arguments,
                        str = arg[0] || '',
                        i, n;
                for (i = 1, n = arg.length; i < n; i++) {
                    str = str.replace(/%s/, arg[2]);
                }
                return str;
            }
        });
    </script>
@endsection
