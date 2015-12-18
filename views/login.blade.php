<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title>{{ Cache::get('websiteTitle') }}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @include('backend::layouts.link')
    <style>
        #wrap{min-height:100%}
        #background-full-screen {
            position: absolute;
            transition: bottom .3s ease-out 1.7s;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-size: cover;
            background-position: 50% 50%;
            height:100%;
        }
        #main {
            color: rgba(255,255,255,.5);
            height: 100%;!important;
            padding-top: 0;
        }
        #content{
            margin-top: 200px;
        }
        .smart-form footer,.smart-form fieldset,.client-form header {
            background: rgba(248,248,248,.5);
        }
    </style>
</head>
<body class="animated fadeInDown" style="width: 100%;height:100%;">
{{--<header id="header">--}}
{{--<div id="logo-group">--}}
{{--<span id="logo"> <img src="{{ asset('loopeer/quickcms/img/logo.png') }}" alt="SmartAdmin"> </span>--}}
{{--</div>--}}
{{--</header>--}}
<div id="wrap">
    <div id="main" role="main">
        <div id="background-full-screen" style="background-image: url('{{asset('loopeer/quickcms/img/background.jpg')}}');"></div>
        <div id="content" class="container">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"></div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                @include('backend::layouts.message')
                <form action="{{route('admin.login')}}" method="post" id="login-form" class="smart-form client-form">
                    <header>
                        登陆
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
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"></div>
        </div>
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
                    required : 'Please enter your email address',
                    email : 'Please enter a VALID email address'
                },
                password : {
                    required : 'Please enter your password'
                }
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
    var height = window.screen.availHeight;
    $('#main').css('height', height);
</script>
</body>
</html>
