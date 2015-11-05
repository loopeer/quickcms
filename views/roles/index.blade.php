@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p><a href="{{route('admin.roles.create')}}" class="btn btn-primary">新增角色</a></p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>角色列表</h2>
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
        </section>
        <input type="hidden" id="delete_token" value="{{ csrf_token() }}"/>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="permission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">分配权限</h4>
                </div>
                <div class="modal-body custom-scroll terms-body">
                    <div id="left">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="confirmPermission"><i class="fa fa-check"></i>保存</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<a href="#" class="btn btn-primary" data-toggle="modal" data-action="" data-id="" data-target="#permission" name="set_permission">分配权限</a>'
                    +'&nbsp;'
                    +'<button name="edit_role" class="btn btn-primary">编辑</button>' +
                    '&nbsp;' +
                    '<button name="delete_role" class="btn btn-primary">删除</button>'
                } ],
                "ajax": {
                    "url": "/admin/roles/search"
                }
            });

            $('#dt_basic tbody').on('click', 'a[name=set_permission]', function () {
                var data = table.row($(this).parents('tr')).data();
                $(this).attr("data-action","/admin/roles/permissions/"+data[0]);
                $(this).attr("data-id",data[0]);
            });
            $('#dt_basic tbody').on('click', 'button[name=edit_role]', function () {
                var data = table.row($(this).parents('tr')).data();
                window.location.href = '/admin/roles/' + data[0] + '/edit';
            });
            $('#dt_basic tbody').on('click', 'button[name=delete_role]', function () {
                var data = table.row($(this).parents('tr')).data();
                var delete_token = $('#delete_token').val();
                if(confirm('确定要删除此角色吗?')) {
                    $.ajax({
                        type: "DELETE",
                        data: { '_token' : delete_token },
                        url: '/admin/roles/' + data[0], //resource
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

            /* END BASIC */
            $('#permission').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                //populate the div
                loadURL(action, $('#permission .modal-content .modal-body #left'));
            });

            $("#confirmPermission").click(function(){
                $("#confirmPermission").text("正在保存...");
                var $form = $('#smart-form-permissions');
                $($form).submit(function(event) {
                    var form = $(this);
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize()
                    }).done(function(result) {
                        // Optionally alert the user of success here...
                        $(".tips").html('<div class="alert alert-success fade in">'
                        +'<button class="close" data-dismiss="alert">×</button>'
                        +'<i class="fa-fw fa fa-check"></i>'
                        +'<strong>成功</strong>'+' '+result.content+'。'
                        +'</div>');
                        $("#confirmPermission").text("保存");
                        //  if (result.result == 'true') {
                        $('#permission').modal('toggle');
                        $form[0].reset();
                        //  }
                    }).fail(function(result) {
                        $(".tips").html('<div class="alert alert-danger fade in">'
                        +'<button class="close" data-dismiss="alert">×</button>'
                        +'<i class="fa-fw fa fa-warning"></i>'
                        +'<strong>失败</strong>'+' '+result.content+'。'
                        +'</div>');
                    });
                    event.preventDefault(); // Prevent the form from submitting via the browser.
                });
                $form.trigger('submit'); // trigger form submit
            });
        });
    </script>
@endsection
