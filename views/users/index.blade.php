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
                    <p><a href="users/create" class="btn btn-primary">新增用户</a></p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>用户列表</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>姓名</th>
                                        <th>邮箱</th>
                                        <th>所属角色</th>
                                        <th>创建时间</th>
                                        <th>最后登录时间</th>
                                        <th>状态</th>
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
    </div>
    <div class="modal fade" id="role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">分配角色</h4>
                </div>
                <div class="modal-body custom-scroll terms-body">
                    <div id="left">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="submitRole"><i class="fa fa-check"></i>提交</button>
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
                "bStateSave": true,
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<div class="btn-group">'+
                    '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作 <span class="caret"></span></button>'+
                    '<ul class="dropdown-menu">'+
                    '<li>'+
                    '<a href="javascript:void(0);" name="role_btn" data-target="#role" data-toggle="modal" data-action="">分配角色</a>'+
                    '</li>'+
                    '<li class="divider btn_content"></li>'+
                    '<li>'+
                    '<a href="javascript:void(0);" name="edit_btn">编辑</a>'+
                    '</li>'+
                    '</ul>'+
                    '</div>'
                } ],
                "ajax": {
                    "url": "/admin/users/search",
                    "dataSrc": function (json) {
                        for (var i=0; i<json.data.length; i++) {
                            if (json.data[i][3] != null) {
                                json.data[i][3] = '<span class="label label-primary">'+json.data[i][3]+'</span>';
                            }
                        }
                        return json.data;
                    }
                }
            });

            table.on( 'draw.dt', function () {
                var $data = table.data();
                for (var i=0; i<$data.length; i++) {
                    if ($data[i][6] == 1) {
                        $('tr').eq(i+1).children('td').eq(6).html('<span class="label label-success">已启用</span>');
                        $('.btn-group').eq(i).children('.dropdown-menu').append( '<li class="divider btn_content"></li>'+
                        '<li class="btn_content">'+
                        '<a href="javascript:void(0);" name="unable_btn">禁用</a>'+
                        '</li>');

                    }else {
                        $('tr').eq(i+1).children('td').eq(6).html('<span class="label label-default">未启用</span>');
                        $('.btn-group').eq(i).children('.dropdown-menu').append( '<li class="divider btn_content"></li>'+
                        '<li class="btn_content">'+
                        '<a href="javascript:void(0);" name="useable_btn">启用</a>'+
                        '</li>');
                    }
                }
            } );

            $('#dt_basic tbody').on('click', 'a[name=role_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                $(this).attr("data-action","/admin/users/role/"+data[0]);
                $(this).attr("data-id",data[0]);
            });
            $('#role').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                //populate the div
                loadURL(action, $('#role .modal-content .modal-body #left'));
            });

            $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
                var id = table.row($(this).parents('tr')).data()[0];
                window.location = '/admin/users/edit/' + id;
            });

            $('#dt_basic tbody').on('click', 'a[name=unable_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                var $status = $(this).parents('tr').children().eq(6);
                var button = $(this).parents('.dropdown-menu').children('.btn_content');
                var dropdown = $(this).parents('.dropdown-menu');
                if(confirm('确定禁用此用户吗?')) {
                    $.ajax({
                        type: "GET",
                        url: '/admin/users/changeStatus/' + data[0], //resource
                        success: function(result) {
                            if (result.result){
                                $status.html('<span class="label label-default">未启用</span>');
                                button.remove();
                                dropdown.append( '<li class="divider btn_content"></li>'+
                                '<li class="btn_content">'+
                                '<a href="javascript:void(0);" name="useable_btn">启用</a>'+
                                '</li>');
                                $(".tips").html('<div class="alert alert-success fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-check"></i>'
                                +'<strong>成功</strong>'+' '+result.content+'。'
                                +'</div>');
                            }
                        }
                    });
                }
            });

            $('#dt_basic tbody').on('click', 'a[name=useable_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                var $status = $(this).parents('tr').children().eq(6);
                var button = $(this).parents('.dropdown-menu').children('.btn_content');
                var dropdown = $(this).parents('.dropdown-menu');
                if(confirm('确定启用此用户吗?')) {
                    $.ajax({
                        type: "GET",
                        url: '/admin/users/changeStatus/' + data[0], //resource
                        success: function(result) {
                            if (result.result){
                                $status.html('<span class="label label-success">已启用</span>');
                                button.remove();
                                dropdown.append( '<li class="divider btn_content"></li>'+
                                '<li class="btn_content">'+
                                '<a href="javascript:void(0);" name="unable_btn">禁用</a>'+
                                '</li>');
                                $(".tips").html('<div class="alert alert-success fade in">'
                                +'<button class="close" data-dismiss="alert">×</button>'
                                +'<i class="fa-fw fa fa-check"></i>'
                                +'<strong>成功</strong>'+' '+result.content+'。'
                                +'</div>');
                            }
                        }
                    });
                }
            });

            $("#submitRole").click(function(){
                $("#submitRole").text("正在保存...");
                var $form = $('#smart-form-register');
                $($form).submit(function(event) {
                    var form = $(this);
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize()
                    }).done(function(result) {
                        if (result.result) {
                            var nRow = $($(this).data('id')).closest("tr").get(0);
                            var table = $('#dt_basic').dataTable();
                            table.fnDeleteRow(nRow, null, true);
                            $(".tips").html('<div class="alert alert-success fade in">'
                            + '<button class="close" data-dismiss="alert">×</button>'
                            + '<i class="fa-fw fa fa-check"></i>'
                            + '<strong>成功</strong>' + ' ' + result.content + '。'
                            + '</div>');
                            $("#submitRole").text("提交");
                            $('#role').modal('hide');
                            $form[0].reset();
                        }
                    });
                    event.preventDefault(); // Prevent the form from submitting via the browser.
                });
                $form.trigger('submit'); // trigger form submit
            });
        });
    </script>
@endsection