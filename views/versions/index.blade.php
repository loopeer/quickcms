@extends('backend::layouts.master')

@section('content')
    <!-- MAIN CONTENT -->
<div id="content">
        <section id="widget-grid" class="">
        @include('backend::layouts.message')
        <div class="row">
            <p><a href="/admin/versions/create/" class="btn btn-primary">添加新版本</a></p>
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>版本列表</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>发布平台</th>
                                <th>版本号</th>
                                <th>版本名称</th>
                                <th>下载地址</th>
                                <th>消息提示</th>
                                <th>版本描述</th>
                                <th>版本状态</th>
                                <th>选项</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="delete_token" value="{{csrf_token()}}"/>
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
        var table = $('#dt_basic').DataTable({
            "processing": false,
            "serverSide": true,
            "bStateSave": true,
            "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent": '<button name="delete_version" class="btn btn-primary">删除</button>' +
                                  '&nbsp;&nbsp;<button name="edit_version" class="btn btn-primary">编辑</button>'
            } ],
            "ajax": {
                "url": "/admin/versions/search"
            }
        });

        $('#dt_basic tbody').on('click', 'button[name=delete_version]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            if(confirm('删除这条日志记录?')) {
                $.ajax({
                    type: "DELETE",
                    data: { '_token' : delete_token },
                    url: '/admin/versions/' + data[0], //resource
                    success: function(affectedRows) {
                        if (affectedRows > 0)
                            var table = $('#dt_basic').dataTable();
                            var nRow = $($(this).data('id')).closest("tr").get(0);
                            table.fnDeleteRow( nRow, null, true );
                            $(".tips").html('<div class="alert alert-success fade in">'
                                    +'<button class="close" data-dismiss="alert">×</button>'
                                    +'<i class="fa-fw fa fa-check"></i>'
                                    +'<strong>成功</strong>'+' '+result.content+'。'
                                    +'</div>');
                    }
                });
            }
        });

        $('#dt_basic tbody').on('click', 'button[name=edit_version]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            window.location = '/admin/versions/' + data[0] + '/edit/';
        });

    });
</script>
@endsection