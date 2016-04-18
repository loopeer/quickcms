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
            <p>
                <a href="/admin/selector/create" class="btn btn-primary">添加新枚举</a>&nbsp;&nbsp;
            </p>
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>枚举列表</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>键名</th>
                                <th>键值</th>
                                <th>操作</th>
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
                "url": "/admin/selector/search"
            }
        });

        $('#dt_basic tbody').on('click', 'button[name=delete_version]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            var datatable = $('#dt_basic').dataTable();
            var page_info = table.page.info();
            var page = page_info.page;
            if (page_info.length == 1 && page_info.page != 0) {
                page = page - 1;
            }
            if(confirm('删除这条记录?')) {
                $.ajax({
                    type: "DELETE",
                    data: { '_token' : delete_token },
                    url: '/admin/selector/' + data[0], //resource
                    success: function(affectedRows) {
                        if (affectedRows > 0)
                            var table = $('#dt_basic').dataTable();
                            var nRow = $($(this).data('id')).closest("tr").get(0);
                            datatable.fnPageChange(page);
                            $(".tips").html('<div class="alert alert-success fade in">'
                                    +'<button class="close" data-dismiss="alert">×</button>'
                                    +'<i class="fa-fw fa fa-check"></i>'
                                    +'<strong>成功</strong>'+'删除成功。'
                                    +'</div>');
                    }
                });
            }
        });

        $('#dt_basic tbody').on('click', 'button[name=edit_version]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            window.location = '/admin/selector/' + data[0] + '/edit';
        });

        $('#update_cache').click(function () {
            $.ajax({
                type: 'get',
                url: '/admin/selector/updateCache',
                success:function(result) {
                    if (result.result) {
                        $(".tips").html('<div class="alert alert-success fade in">'
                                + '<button class="close" data-dismiss="alert">×</button>'
                                + '<i class="fa-fw fa fa-check"></i>'
                                + '<strong>成功</strong>' + ' ' + result.content + '。'
                                + '</div>');
                    }
                }
            });
        });
    });
</script>
@endsection