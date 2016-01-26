@extends('backend::layouts.master')

@section('content')
        <!-- MAIN CONTENT -->
<div id="content">
    <div class="row tips">
    @include('backend::layouts.message')
    </div>
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                <p><a href="/admin/document/create" class="btn btn-primary">添加新文档</a></p>
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>文档列表</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>文档 key</th>
                                <th>文档标题</th>
                                <th>添加时间</th>
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
                    "bStateSave": true,
                    "columnDefs": [ {
                        "targets": -1,
                        "data": null,
                        "defaultContent": '<button name="delete_document" class="btn btn-primary">删除</button>&nbsp;&nbsp;' +
                                    '<button name="edit_document" class="btn btn-primary">编辑</button>'
                    } ],
                    "ajax": {
                        "url": "/admin/document/search"
                    }
                });

                $('#dt_basic tbody').on('click', 'button[name=delete_document]', function () {
                    var data = table.row($(this).parents('tr')).data();
                    var delete_token = $('#delete_token').val();
                    var page_info = table.page.info();
                    var page = page_info.page;
                    var datatable = $('#dt_basic').dataTable();
                    if(confirm('删除这篇文档?')) {
                        $.ajax({
                            type: "DELETE",
                            data: { '_token' : delete_token },
                            url: '/admin/document/' + data[0], //resource
                            success: function(result) {
                                if (result.result) {
                                    datatable.fnPageChange(page);
                                    $(".tips").html('<div class="alert alert-success fade in">'
                                            +'<button class="close" data-dismiss="alert">×</button>'
                                            +'<i class="fa-fw fa fa-check"></i>'
                                            +'<strong>成功</strong>'+ result.content +'。'
                                            +'</div>');
                                }
                            }
                        });
                    }
                });

                $('#dt_basic tbody').on('click', 'button[name=edit_document]', function () {
                    var data = table.row($(this).parents('tr')).data();
                    window.location = "/admin/document/" + data[0] + "/edit"
                });
            });
        </script>
@endsection