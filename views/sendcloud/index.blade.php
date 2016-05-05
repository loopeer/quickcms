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
                        <a href="#" data-toggle="modal" data-action="/admin/sendcloud/apiuser" data-target="#user_dialog" class="btn btn-primary">设置API USER</a>
                        <a href="{{route('admin.sendcloud.create')}}" class="btn btn-primary">新建模板</a>
                        {{--<a href="/admin/sendcloud/normal" class="btn btn-primary">发送普通邮件</a>--}}
                        <a href="/admin/sendcloud/template" class="btn btn-primary">发送模板邮件</a>
                    </p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>模板列表</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">

                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th>模板名称</th>
                                        <th>调用名称</th>
                                        <th>邮件标题</th>
                                        <th>类型</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    @foreach($templates as $template)
                                        <tr>
                                            <td>{{ $template->name }}</td>
                                            <td>{{ $template->invokeName }}</td>
                                            <td>{{ $template->subject }}</td>
                                            <td>{{ $template->templateType == 0 ? '触发邮件' : '批量邮件' }}</td>
                                            <td>
                                                @if($template->templateStat == -2)
                                                    <span class="label label-default">未提审</span>
                                                @elseif($template->templateStat == -1)
                                                    <span class="label label-danger">审核未通过</span>
                                                @elseif($template->templateStat == 0)
                                                    <span class="label label-default">待审核</span>
                                                @elseif($template->templateStat == 1)
                                                    <span class="label label-success">审核通过</span>
                                                @endif
                                            </td>
                                            <td>{{ $template->gmtCreated }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作 <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        {{--<li class="divider btn_content"></li>--}}
                                                        <li>
                                                            <a href="javascript:void(0);" name="edit_btn">编辑</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" name="delete_btn">删除</a>
                                                        </li>
                                                        @if($template->templateStat == 0)
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" name="review_btn" data-status="0">撤销审核</a>
                                                            </li>
                                                        @elseif($template->templateStat == -2 || $template->templateStat == -1)
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" name="review_btn" data-status="{{ $template->templateStat }}">提交审核</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
    <div class="modal fade" id="user_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">设置API USER</h4>
                </div>
                <div class="modal-body custom-scroll terms-body" style="padding: 10px;">
                    <div id="left"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="saveApiUser"><i class="fa fa-check"></i>提交</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#dt_basic').DataTable();

            $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                window.location.href = '/admin/sendcloud/' + data[1] + '/edit';
            });

            $('#user_dialog').on('show.bs.modal', function(e) {
                var action = $(e.relatedTarget).data('action');
                //populate the div
                loadURL(action, $('#user_dialog .modal-content .modal-body #left'));
            });

            $("#saveApiUser").click(function(){
                $("#saveApiUser").text("正在保存...");
                var $form = $('#api_user_form');
                $($form).submit(function(event) {
                    var form = $(this);
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize()
                    }).done(function(result) {
                        // Optionally alert the user of success here...
                        $("#saveApiUser").text("提交");
                        $('#user_dialog').modal('hide');
                        $("#tips").html(result.tip);
                        if (result.result){
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
                    });
                    event.preventDefault(); // Prevent the form from submitting via the browser.
                });
                $form.trigger('submit'); // trigger form submit
            });

            $('#dt_basic tbody').on('click', 'a[name=delete_btn]', function () {
                var data = table.row($(this).parents('tr')).data();
                var delete_token = $('#delete_token').val();
                var page_info = table.page.info();
                var page = page_info.page;
                var datatable = $('#dt_basic').dataTable();
                if (page_info.end - page_info.start == 1) {
                    page -= 1;
                }
                if(confirm('确定要删除吗?')) {
                    $.ajax({
                        type: "DELETE",
                        data: { '_token' : delete_token },
                        url: '/admin/sendcloud/' + data[1], //resource
                        success: function(result) {
                            if (result.result){
                                datatable.fnPageChange(page);
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

            $('#dt_basic tbody').on('click', 'a[name=review_btn]', function() {
                var data = table.row($(this).parents('tr')).data();
                var page_info = table.page.info();
                var page = page_info.page;
                var datatable = $('#dt_basic').dataTable();
                var status = $(this).attr('data-status');
                var confirm_msg = status == 0 ? '确定取消审核吗?' : '确定提交审核吗?';
                if(confirm(confirm_msg)) {
                    $.ajax({
                        type: "GET",
                        url: '/admin/sendcloud/' + data[1] + '/review?status=' + status,
                        success: function(result) {
                            if (result.result) {
                                datatable.fnPageChange(page);
                                $(".tips").html('<div class="alert alert-success fade in">'
                                + '<button class="close" data-dismiss="alert">×</button>'
                                + '<i class="fa-fw fa fa-check"></i>'
                                + '<strong>成功</strong>' + ' ' + result.content + '。'
                                + '</div>');
                            } else {
                                $(".tips").html('<div class="alert alert-danger fade in">'
                                + '<button class="close" data-dismiss="alert">×</button>'
                                + '<i class="fa-fw fa fa-warning"></i>'
                                + '<strong>失败</strong>' + ' ' + result.content + '。'
                                + '</div>');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
