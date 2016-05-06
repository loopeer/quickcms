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
                var delete_token = $('#delete_token').val();
                if(confirm('确定要删除此权限吗?')) {
                    $.ajax({
                        type: "post",
                        data: { '_token' : delete_token },
                        url: '/admin/permissions/' + {{ $id }} + '/deletePermission/' + data[0], //resource
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
