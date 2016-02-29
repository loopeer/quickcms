@extends('backend::layouts.master')

@section('content')
        <!-- MAIN CONTENT -->
<div id="content">
    <section id="widget-grid" class="">
        <div class="row tips">
            @include('backend::layouts.message')
        </div>
        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @if($create_able)
                    <p><a href="/admin/{{ $route_name }}/create/" class="btn btn-primary">新增{{ $model_name }}</a></p>
                @endif
                <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                        <h2>{{ $model_name }}列表</h2>
                    </header>
                    <div>
                        <div class="jarviswidget-editbox">
                        </div>
                        <div class="widget-body no-padding">
                            <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    @foreach($index_column_name as $name)
                                        <th>{{ $name }}</th>
                                    @endforeach
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
<!-- END MAIN CONTENT -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var route_name = '{{ $route_name }}';
            var table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "bStateSave": true,
                @if(!empty($actions))
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent":
                    '<div class="btn-group">'+
                    '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作 <span class="caret"></span></button>'+
                    '<ul class="dropdown-menu">'+
                    @foreach($actions as $action)
                            @if(!$action['default_show'])
                            '<style>'+
                    '.{{$action['name']}} {display:none;}'+
                    '</style>'+
                    @endif
                    '<li class="' + '{{$action['name']}}' + '">'+
                    '<a href="javascript:void(0);" name="' + '{{$action['name']}}' + '">' + '{{$action['display_name']}}' + '</a>'+
                    '</li>'+
                    @if($action['has_divider'])
                    '<li class="divider ' + '{{$action['name']}}' + '"></li>'+
                    @endif
                    @endforeach
                    '</ul>'+
                    '</div>'
                } ],
                @endif
                "ajax": {
                    "url": "/admin/" + route_name + "/search"
                }
            });

            table.on( 'draw.dt', function () {
                var $data = table.data();
                for (var i=0; i < $data.length; i++) {
                    @if(is_array($index_column_rename))
                        @foreach($index_column_rename as $column => $rename)
                            {{$column_no = array_flip($index_column)[$column]}}
                            @if($rename['type'] == 'normal')
                                @foreach($rename['param'] as $key => $value)
                                    if($data[i][parseInt('{{$column_no}}')] == parseInt('{{$key}}')) {
                                        $('tr').eq(i+1).children('td').eq(parseInt('{{$column_no}}')).html('{!!$value["value"]!!}');
                                        @if(!empty($value['action_name']))
                                        {{--$('tr').eq(i+1).children('td').('.{{$value['action_name']}}').html('');--}}
                                        $('tr:eq('+(i+1)+') '+'.'+'{{$value['action_name']}}').show();
                                        @endif
                                    }
                                @endforeach
                            @elseif($rename['type'] == 'dialog')
                                var html = '<a href="javascript:void(0);" name="{{$rename['param']['name']}}">' + $data[i][parseInt('{{$column_no}}')] + '</a>';
                                $('tr').eq(i+1).children('td').eq(parseInt('{{$column_no}}')).html(html);
                            @elseif($rename['type'] == 'html')
                                var html = sprintf('{!! $rename["template"] !!}', 1, $data[i][parseInt('{{$column_no}}')]);
                                $('tr').eq(i+1).children('td').eq(parseInt('{{$column_no}}')).html(html);
                            @elseif($rename['type'] == 'href')
                                var html = '<a href="' + $data[i][parseInt('{{$column_no}}')] + '" target="_blank" title="' + $data[i][parseInt('{{$column_no}}')] + '">点击查看</a>';
                                $('tr').eq(i+1).children('td').eq(parseInt('{{$column_no}}')).html(html);
                            @endif
                        @endforeach
                    @endif
                }
            });
            //
            @if(!empty($actions))
            @foreach($actions as $action)
                //
            @if($action['type'] == 'edit')
                $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
                        var data = table.row($(this).parents('tr')).data();
                        window.location = '/admin/' + route_name + '/' + data[0] + '/edit/';
                    });
            @endif

            @if ($action['type'] == 'redirect_with_id')
                 $('#dt_basic tbody').on('click', 'a[name=' + '{{$action['name']}}' + ']', function () {
                        var data = table.row($(this).parents('tr')).data();
                        window.location = '{{$action['url']}}' + '/' + data[0];
                });
            @endif
            @if($action['type'] == 'confirm')
                $('#dt_basic tbody').on('click', 'a[name=' + '{{$action['name']}}' + ']', function () {
                        var data = table.row($(this).parents('tr')).data();
                        var delete_token = $('#delete_token').val();
                        var page_info = table.page.info();
                        var page = page_info.page;
                        var datatable = $('#dt_basic').dataTable();
                        if (page_info.end - page_info.start == 1) {
                            page -= 1;
                        }
                        if (confirm('{{$action['confirm_msg']}}')) {
                            $.ajax({
                                type: '{{$action['method']}}',
                                @if($action['method'] == 'delete')
                                    data: { '_token' : delete_token },
                                @elseif(isset($action['data']))
                                    data: { 'column' : '{{ $action['data']['column'] }}', 'value' : '{{ $action['data']['value'] }}'},
                                @endif
                                url: '{{$action['url']}}' + '/' + data[0], //resource
                                success: function(result) {
                                    if (result == 1){
                                        datatable.fnPageChange(page);
                                        $(".tips").html('<div class="alert alert-success fade in">'
                                            +'<button class="close" data-dismiss="alert">×</button>'
                                            +'<i class="fa-fw fa fa-check"></i>'
                                            +'<strong>成功</strong>'+' '+'{{$action['success_msg']}}'+'。'
                                            +'</div>');
                                    }else{
                                        $(".tips").html('<div class="alert alert-danger fade in">'
                                            +'<button class="close" data-dismiss="alert">×</button>'
                                            +'<i class="fa-fw fa fa-warning"></i>'
                                            +'<strong>失败</strong>'+' '+'{{$action['failure_msg']}}'+'。'
                                            +'</div>');
                                    }
                                }
                            });
                        }
                    });
            @endif
            //
            @if($action['type'] == 'dialog')
            $('#content').after(
                '<div class="modal fade" id="' + '{{$action['target']}}' + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
                '<div class="modal-dialog style="{{isset($action['width']) ? "width: ".$action['width'] : ""}};"">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">' +
                '&times;' +
                '</button>' +
                '<h4 class="modal-title"></h4>' +
                '</div>' +
                '<div class="modal-body custom-scroll terms-body">' +
                '<div id="left">' +
                '</div>' +
                '</div>' +

                '</div>' +
                '</div>' +
                '</div>'
            );

            $('#dt_basic tbody').on('click', 'a[name=' + '{{$action['name']}}' + ']', function () {
                var data = table.row($(this).parents('tr')).data();
                $("#" + '{{$action['target']}}' + ' .modal-title').html('{{$action['dialog_title']}}');
                $(this).attr("data-toggle", "modal");
                $(this).attr("data-target", "#{{$action['target']}}");
                $(this).attr("data-action","{{$action['url']}}"+data[0]);
                $(this).attr("data-id",data[0]);
            });

            $('#' + '{{$action['target']}}').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                //populate the div
                loadURL(action, $('#' + '{{$action['target']}}' + ' .modal-content .modal-body #left'));
            });

            @if(!empty($action['form']))
            $('#' + '{{$action['target']}}' + " .modal-body").after(
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>' +
                '<button type="button" class="btn btn-primary" id="' + '{{$action['form']['submit_id']}}' +'"><i class="fa fa-check"></i>提交</button>' +
                '</div>'
            );

            $('#' + '{{$action['form']['submit_id']}}').click(function(){
                $('#' + '{{$action['form']['submit_id']}}').text("正在保存...");
                var $form = $("#" + '{{$action['form']['form_id']}}');
                var page_info = table.page.info();
                var page = page_info.page;
                var datatable = $('#dt_basic').dataTable();
                $($form).submit(function(event) {
                    var form = $(this);
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize()
                    }).done(function(result) {
                        if(result == 1) {
                            datatable.fnPageChange(page);
                            $(".tips").html('<div class="alert alert-success fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-check"></i>'
                                +'<strong>成功</strong>'+' '+'{{$action['form']['success_msg']}}'+'。'
                                +'</div>');
                        } else {
                            $(".tips").html('<div class="alert alert-danger fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-warning"></i>'
                                +'<strong>失败</strong>' + ' ' + '{{$action['form']['failure_msg']}}' + '。'
                                +'</div>');
                        }
                        $('#' + '{{$action['form']['submit_id']}}').text("提交");
                        $('#' + '{{$action['target']}}').modal('hide');
                        $form[0].reset();
                    });
                    event.preventDefault(); // Prevent the form from submitting via the browser.
                });
                $form.trigger('submit'); // trigger form submit
            });
            @endif

        @endif
        @endforeach
        @endif

        @foreach($index_column_rename as $column => $rename)
            @if($rename['type'] == 'dialog')
                $('#content').after(
                    '<div class="modal fade" id="' + '{{$rename['param']['target']}}' + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
                    '<div class="modal-dialog" style="{{isset($rename['param']['width']) ? "width: ".$rename['param']['width'] : ""}}">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">' +
                    '&times;' +
                    '</button>' +
                    '<h4 class="modal-title"></h4>' +
                    '</div>' +
                    '<div class="modal-body custom-scroll terms-body" style="min-height: 280px;">' +
                    '<div id="left">' +
                    '</div>' +
                    '</div>' +

                    '</div>' +
                    '</div>' +
                    '</div>'
            );

            $('#dt_basic tbody').on('click', 'a[name=' + '{{$rename['param']['name']}}' + ']', function () {
                var data = table.row($(this).parents('tr')).data();
                $("#" + '{{$rename['param']['target']}}' + " .modal-title").html('{{$rename['param']['dialog_title']}}');
                $(this).attr("data-toggle", "modal");
                $(this).attr("data-target", "#{{$rename['param']['target']}}");
                $(this).attr("data-action","{{$rename['param']['url']}}"+data[0]);
                $(this).attr("data-id",data[0]);
            });

            $('#' + '{{$rename['param']['target']}}').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                //populate the div
                loadURL(action, $('#' + '{{$rename['param']['target']}}' + ' .modal-content .modal-body #left'));
            });
            @endif
        @endforeach

    });
        function sprintf()
        {
            var arg = arguments,
                    str = arg[0] || '',
                    i, n;
            for (i = 1, n = arg.length; i < n; i++) {
                str = str.replace(/%s/, arg[2]);
            }
            return str;
        }
    </script>
@endsection