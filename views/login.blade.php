<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title>全麦运动后台管理系统</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/font-awesome.min.css') }}">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/smartadmin-production-plugins.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/smartadmin-production.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/smartadmin-skins.min.css') }}">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/smartadmin-rtl.min.css') }}">

    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('loopeer/quickcms/css/demo.min.css') }}">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="{{ asset('loopeer/quickcms/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('loopeer/quickcms/img/favicon/favicon.ico') }}" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- #APP SCREEN / ICONS -->
    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="{{ asset('loopeer/quickcms/img/splash/sptouch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('loopeer/quickcms/img/splash/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('loopeer/quickcms/img/splash/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('loopeer/quickcms/img/splash/touch-icon-ipad-retina.png') }}">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="{{ asset('loopeer/quickcms/img/splash/ipad-landscape.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="{{ asset('loopeer/quickcms/img/splash/ipad-portrait.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="{{ asset('loopeer/quickcms/img/splash/iphone.png') }}" media="screen and (max-device-width: 320px)">
</head>
<body class="animated fadeInDown">

<header id="header">

    <div id="logo-group">
        <span id="logo"> <img src="{{ asset('loopeer/quickcms/img/logo.png') }}" alt="SmartAdmin"> </span>
    </div>

    {{--<span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Need an account?</span> <a href="register.html" class="btn btn-danger">Create account</a> </span>--}}

</header>

<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                @include('backend::layouts.message')
                <div class="well no-padding">
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
                                {{--<div class="note">--}}
                                    {{--<a href="forgotpassword.html">Forgot password?</a>--}}
                                {{--</div>--}}
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

<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="{{ asset('loopeer/quickcms/js/plugin/pace/pace.min.js') }}"></script>

<script> if (!window.jQuery) { document.write('<script src="{{ asset('loopeer/quickcms/js/libs/jquery-2.1.1.min.js') }}"><\/script>');} </script>

<script> if (!window.jQuery.ui) { document.write('<script src="{{ asset('loopeer/quickcms/js/libs/jquery-ui-1.10.3.min.js') }}"><\/script>');} </script>

<!-- IMPORTANT: APP CONFIG -->
<script src="{{ asset('loopeer/quickcms/js/app.config.js') }}"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="{{ asset('loopeer/quickcms/js/bootstrap/bootstrap.min.js') }}"></script>

<!-- JQUERY VALIDATE -->
<script src="{{ asset('loopeer/quickcms/js/plugin/jquery-validate/jquery.validate.min.js') }}"></script>

<!-- JQUERY MASKED INPUT -->
<script src="{{ asset('loopeer/quickcms/js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="{{ asset('loopeer/quickcms/js/app.min.js') }}"></script>

<script type="text/javascript">
    runAllForms();

    $(function() {
        // Validation
        $("#login-form").validate({
            // Rules for form validation
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

            // Messages for form validation
            messages : {
                email : {
                    required : 'Please enter your email address',
                    email : 'Please enter a VALID email address'
                },
                password : {
                    required : 'Please enter your password'
                }
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>

</body>
</html>
