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
                        <a href="{{route('admin.sendcloud.create')}}" class="btn btn-primary">新建模板</a>
                        {{--<a href="/admin/sendcloud/normal" class="btn btn-primary">发送普通邮件</a>--}}
                        <a href="/admin/sendcloud/template" class="btn btn-primary">发送邮件</a>
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
