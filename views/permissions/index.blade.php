@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p><a href="{{route('admin.permissions.create')}}" class="btn btn-primary">新增权限</a></p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>权限列表</h2>
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
                                        <th>上级权限</th>
                                        <th>排序</th>
                                        <th>图标</th>
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
@endsection
@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "bStateSave": true,
                "language": {
                    "sProcessing": "处理中...",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sZeroRecords": "没有匹配结果",
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix": "",
                    "sSearch": "搜索:",
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
                    null,
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false },
                    { "orderable" : false }
                ],
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent":
                    '<button name="edit_permission" class="btn btn-primary">编辑</button>' +
                    '&nbsp;' +
                    '<button name="delete_permission" class="btn btn-primary">删除</button>' +
                    '&nbsp;' +
                    '<button name="permission_btn" class="btn btn-primary">功能权限</button>'
                } ],
                "ajax": {
                    "url": "/admin/permissions/search"
                }
            });

            $('#dt_basic tbody').on('click', 'button[name=edit_permission]', function () {
                var data = table.row($(this).parents('tr')).data();
                window.location.href = '/admin/permissions/' + data[0] + '/edit';
            });
            $('#dt_basic tbody').on('click', 'button[name=permission_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                window.location.href = '/admin/permissions/' + data[0] + '/indexPermission';
            });
            $('#dt_basic tbody').on('click', 'button[name=delete_permission]', function () {
                var data = table.row($(this).parents('tr')).data();
                var delete_token = $('#delete_token').val();
                if(confirm('确定要删除此权限吗?')) {
                    $.ajax({
                        type: "DELETE",
                        data: { '_token' : delete_token },
                        url: '/admin/permissions/' + data[0], //resource
                        success: function(result) {
                            if (result.result){
                                var nRow = $($(this).data('id')).closest("tr").get(0);
                                var table = $('#dt_basic').dataTable();
                                table.fnDeleteRow( nRow, null, true );
                                $(".tips").html('<div class="alert alert-success fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-check"></i>'
                                +'<strong>成功</strong>'+' '+result.content+'。'
                                +'</div>');
                            }else{
                                $(".tips").html('<div class="alert alert-danger fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-warning"></i>'
                                +'<strong>失败</strong>'+' '+result.content+'。'
                                +'</div>');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
