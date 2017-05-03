<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title>{{ config('quickCms.site_title') }}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @include('backend::layouts.link')
    <style>
        html {
            background: #f4f4f4!important;
        }
        #wrap{min-height:100%}
        #background-full-screen {
            /*position: absolute;*/
            /*transition: bottom .3s ease-out 1.7s;*/
            /*top: 0;*/
            /*right: 0;*/
            /*bottom: 0;*/
            /*left: 0;*/
            /*background-size: cover;*/
            /*background-position: 50% 50%;*/
            /*height:100%;*/
        }
        #main {
            color: rgba(255,255,255,.5);
            height: 100%;!important;
            padding-top: 0;
        }
        #content{
            margin-top: 50px;
        }
        .smart-form footer,.smart-form fieldset,.client-form header {
            /*background: rgba(248,248,248,.5);*/
        }
        #logo-group {
            margin-top: 18px;
        }
        #logo-title {
            display: inline-block;
            margin-left: 10px;
            margin-top: 5px;
        }
        #form-content {
            float: none;
            margin: 0 auto;
        }
    </style>
</head>
<body class="animated fadeInDown">
<header id="header">
    <div id="logo-group">
        <span><img src="{{ isset($system_logo) ? $system_logo : asset('loopeer/quickcms/img/cms_logo.png') }}" alt="{{ isset($system_title) ? $system_title : config('quickCms.site_title') }}" onerror="this.src='{{ asset('loopeer/quickcms/img/cms_logo.png') }}'"></span>
        <div id="logo-title">{{ isset($system_title) ? $system_title : config('quickCms.site_title') }}</div>
    </div>
    <span id="extr-page-header-space">
<span class="hidden-mobile hiddex-xs">没有账号?</span>
<a class="btn btn-primary" id="register-btn">申请账号</a>
</span>
</header>
<div id="wrap">
    <div id="main" role="main">
        <div id="background-full-screen"></div>
        <div id="content" class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="form-content">
                    <div class="tips">
                        @include('backend::layouts.message')
                    </div>
                    <div class="well no-padding">
                        <form action="{{route('admin.login')}}" method="post" id="login-form" class="smart-form client-form">
                            <header>
                                登录
                            </header>
                            {!! csrf_field() !!}
                            <fieldset>
                                <section>
                                    <label class="label">邮箱</label>
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="email" name="email">
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> 请输入帐号</b></label>
                                </section>
                                <section>
                                    <label class="label">密码</label>
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                        <input type="password" name="password">
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> 请输入密码</b> </label>
                                </section>
                                <section>
                                    <label class="checkbox">
                                        <input type="checkbox" name="remember" value="1" checked>
                                        <i></i>记住登陆状态</label>
                                </section>
                            </fieldset>
                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    登&nbsp;&nbsp;录
                                </button>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="register-dialog" title="<div class='widget-header'><h4><i class='fa fa-plus'></i> 申请账号</h4></div>" style="display: none">
        <form action="/admin/register" method="post" id="register-form" class="smart-form client-form">
            <fieldset>
                <section>
                    <label class="label">邮箱</label>
                    <label class="input"> <i class="icon-append fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email address">
                        <b class="tooltip tooltip-bottom-right">用来登录后台管理系统</b> </label>
                </section>
                <section>
                    <label class="label">姓名</label>
                    <label class="input"> <i class="icon-append fa fa-user"></i>
                        <input type="text" name="name">
                    </label>
                </section>
                <section>
                    <label class="label">密码</label>
                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" id="password" maxlength="20">
                    </label>
                </section>
                <section>
                    <label class="label">确认密码</label>
                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="passwordConfirm" placeholder="Confirm password" maxlength="20">
                    </label>
                </section>
            </fieldset>
        </form>
    </div>

</div>
<script src="{{ asset('loopeer/quickcms/js/libs/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/libs/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.config.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.min.js') }}"></script>
<script type="text/javascript">
    runAllForms();

    $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
        _title : function(title) {
            if (!this.options.title) {
                title.html("&#160;");
            } else {
                title.html(this.options.title);
            }
        }
    }));

    // Register
    var register_dialog = $("#register-dialog").dialog({
        autoOpen : false,
        width : 500,
        height: 550,
        resizable : false,
        diaggable:false,
        draggable: false,
        modal : true,
        beforeClose: function() {
            $("#register-form").trigger("reset");
        },
        buttons : [{
            html : "<i class='fa fa-times'></i>&nbsp; 取消",
            "class" : "btn btn-default",
            click : function() {
                $(this).dialog("close");
            }
        }, {
            html : "<i class='fa fa-check'></i>&nbsp; 提交",
            "class" : "btn btn-primary",
            click : function() {
                register_dialog.find("form").submit();
            }
        }]
    });

    // addTab button: just opens the dialog
    $("#register-btn").button().click(function() {
        register_dialog.dialog("open");
    });


    $(function() {
        $("#login-form").validate({
            rules : {
                email : {
                    required : true,
                    email : true
                },
                password : {
                    required : true,
                    minlength : 3,
                    maxlength : 20
                }
            },
            messages : {
                email : {
                    required : '请输入您的邮箱',
                    email : '请输入有效的邮箱'
                },
                password : {
                    required : '请输入您的密码'
                }
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
//    var height = window.screen.availHeight;
//    $('#main').css('height', height);
    var register_form = register_dialog.find("form").submit(function(event) {
        event.preventDefault();
    }).validate({
        // Rules for form validation
        rules : {
            email : {
                required : true,
                email : true
            },
            name : {
                required : true
            },
            password : {
                required : true,
                minlength : 6,
                maxlength : 20
            },
            passwordConfirm : {
                required : true,
                minlength : 6,
                maxlength : 20,
                equalTo : '#password'
            }
        },
        // Messages for form validation
        messages : {
            email : {
                required : '请输入邮箱地址',
                email : '请输入正确的邮箱格式'
            },
            name : {
                required : '请输入姓名'
            },
            password: {
                required : '请输入密码',
                minlength: '密码至少输入6位',
                maxlength: '密码不能超过20位'
            },
            passwordConfirm : {
                required : '请确认密码',
                equalTo : '两次输入的密码不一致',
                minlength: '密码至少输入6位',
                maxlength: '密码不能超过20位'
            }
        },
        // Ajax form submition
        submitHandler : function(form) {
            var $form = $('#register-form');
//            $("#add_user_label").text("正在保存...");
            $.post($form.attr('action'), $form.serialize(), function(result) {
                register_dialog.dialog("close");
                if (result.result == 1) {
                    $(".tips").html('<div class="alert alert-success fade in">'
                        + '<button class="close" data-dismiss="alert">×</button>'
                        + '<i class="fa-fw fa fa-check"></i>'
                        + '<strong>成功</strong>' + ' ' + result.content
                        + '</div>');
                    $form[0].reset();
                } else {
                    $(".tips").html('<div class="alert alert-danger fade in">'
                        + '<button class="close" data-dismiss="alert">×</button>'
                        + '<i class="fa-fw fa fa-warning"></i>'
                        + '<strong>失败</strong>' + ' ' + result.content
                        + '</div>');
                }
            }, 'json');
        },
        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
</script>
</body>
</html>
