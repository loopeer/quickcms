@extends('backend::layouts.master')

@section('content')
    <!-- MAIN CONTENT -->
    <div id="content">
        @include('backend::layouts.message')
        <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>反馈列表</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>反馈内容</th>
                                <th>版本名称</th>
                                <th>版本号</th>
                                <th>设备唯一ID</th>
                                <th>渠道编号</th>
                                <th>联系方式</th>
                                <th>选项</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" id="delete_token" value="{{ csrf_token() }}"/>
        </article>
    </div>
    <!-- END MAIN CONTENT -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#dt_basic').DataTable({
            "processing": false,
            "serverSide": true,
            "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent": '<button name="delete_feedback" class="btn btn-primary">删除</button>'
            } ],
            "ajax": {
                "url": "/admin/feedbacks/search"
            }
        });

        $('#dt_basic tbody').on('click', 'button[name=delete_feedback]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            if(confirm('删除这条日志记录?')) {
                $.ajax({
                    type: "DELETE",
                    data: { '_token' : delete_token },
                    url: '/admin/feedbacks/' + data[0], //resource
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
    });
</script>
@endsection