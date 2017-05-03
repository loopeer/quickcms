@extends('backend::layouts.master')
@section('content')
    <div class="jarviswidget well jarviswidget-color-darken" id="wid-id-0" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
        <header role="heading">
            <div class="jarviswidget-ctrls" role="menu">
                <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a>
                <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a>
            </div>
            <span class="widget-icon"> <i class="fa fa-barcode"></i> </span>
            <h2>Item #44761 </h2>
            <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
        </header>
        <div role="content">
            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body no-padding">
                <div class="padding-10">
                    <br>
                    <div class="pull-left" style="width: 50%">
                        <h4 class="semi-bold">系统信息</h4>
                        <address>
                            <br>
                            <strong>服务器信息：</strong><span>{{ php_uname('s').' '.php_uname('r').' '.php_uname('v') }}</span>
                            <br>
                            <br>
                            <strong>主机名：</strong>{{ php_uname('n') }}
                            <br>
                            <br>
                            <strong>PHP版本：</strong>{{ PHP_VERSION }}
                            <br>
                            <br>
                            <strong>服务器IP：</strong>{{ GetHostByName($_SERVER['SERVER_NAME'])}}
                            <br>
                            <br>
                            <strong>服务器域名：</strong>{{ $_SERVER["HTTP_HOST"]}}
                            <br>
                            <br>
                            <strong>系统用户数：</strong>{{ $count_user }}
                            <br>
                        </address>
                    </div>
                    <div class="pull-left" style="margin-left: 15%;">
                        <h4 class="semi-bold">当前登陆用户信息</h4>
                        <address>
                            <br>
                            <strong>登陆邮箱：</strong>{{ $user->email }}
                            <br>
                            <br>
                            <strong>姓名：</strong>{{ $user->name }}
                            <br>
                            <br>
                            <strong>登陆IP：</strong>{{ Request::getClientIp() }}
                            <br>
                            <br>
                            <strong>最近登陆时间：</strong>{{ $user->last_login }}
                            <br>
                            <br>
                            <strong>最后更新时间：</strong>{{ $user->updated_at }}
                            <br>
                        </address>
                    </div>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>后台用户登陆日志</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th>登陆时间</th>
                                        <th>登陆邮箱</th>
                                        <th>登陆IP</th>
                                        <th>系统</th>
                                        <th>浏览器</th>
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
@endsection
@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#dt_basic').DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": "/admin/index/getLoginLog"
                }
            });
        });
    </script>
@endsection
