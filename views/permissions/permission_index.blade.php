@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p>
                        <a href="/admin/permissions" class="btn btn-primary">返回</a>  
                        <a href="/admin/permissions/{{ $id }}/createPermission" class="btn btn-primary">新增功能权限</a>
                        <a href="/admin/permissions/{{ $id }}/initPermission" class="btn btn-primary">初始化CURD功能权限</a>
                    </p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>功能权限列表</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">

                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>名称</th>
                                        <th>显示名称</th>
                                        <th>路由</th>
                                        <th>描述</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <input type="hidden" id="delete_token" value="{{ csrf_token() }}"/>
        </section>
    </div>
    <div id="confirm-dialog"></div>
@endsection
@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "bStateSave": true,
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<button name="edit_permission" class="btn btn-primary">编辑</button>' +
                    '&nbsp;' +
                    '<button name="delete_permission" class="btn btn-primary">删除</button>' 
                } ],
                "ajax": {
                    "url": "/admin/permissions/" + {{ $id }} + "/searchPermission"
                }
            });

            $('#dt_basic tbody').on('click', 'button[name=edit_permission]', function () {
                var data = table.row($(this).parents('tr')).data();
                window.location.href = '/admin/permissions/' + {{ $id }} + '/editPermission/' + data[0];
            });
            $('#dt_basic tbody').on('click', 'button[name=delete_permission]', function () {
                var data = table.row($(this).parents('tr')).data();
                var page_info = table.page.info();
                var page = page_info.page;
                if ((page_info.end - page_info.start) == 1 && page != 0) {
                    page = page - 1;
                }

                var delete_token = $('#delete_token').val();

                $('#confirm-dialog').html('确定要删除此权限吗?');
                $('#confirm-dialog').dialog({
                    autoOpen: false,
                    width: 400,
                    resizable: false,
                    modal: true,
                    title: "提示",
                    draggable: false,
                    buttons: [
                        {
                            html: "<i class='fa fa-times'></i>  取消",
                            "class": "btn btn-danger",
                            click: function () {
                                $(this).dialog("close");
                            }
                        },
                        {
                            html: "<i class='fa fa-check'></i>  确定",
                            "class": "btn btn-success",
                            click: function () {
                                $.ajax({
                                    type: "POST",
                                    data: { '_token' : delete_token },
                                    url: '/admin/permissions/' + {{ $id }} + '/deletePermission/' + data[0],
                                    success: function(result) {
                                        if (result.result == 1) {
                                            $('#dt_basic').dataTable().fnPageChange(page);
                                            $(".tips").html('<div class="alert alert-success fade in">'
                                                + '<button class="close" data-dismiss="alert">×</button>'
                                                + '<i class="fa-fw fa fa-check"></i>'
                                                + '<strong>成功</strong>' +' ' + result.content + '。'
                                                + '</div>');
                                        } else {
                                            $(".tips").html('<div class="alert alert-danger fade in">'
                                                + '<button class="close" data-dismiss="alert">×</button>'
                                                + '<i class="fa-fw fa fa-warning"></i>'
                                                + '<strong>失败</strong>' +' ' + result.content + '。'
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
                                $(this).dialog("close");
                            }
                        }
                    ]
                });
                $('#confirm-dialog').dialog('open');
            });
        });
    </script>
@endsection
