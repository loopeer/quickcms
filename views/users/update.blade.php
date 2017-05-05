@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6">
                    @include('backend::layouts.message')
                    <div class="jarviswidget" id="wid-id-4" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
                         data-widget-fullscreenbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>个人资料 </h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/users/profile" method="post" id="smart-form-register" class="smart-form">
                                    <style>
                                        .btn {
                                            display: inline-block;
                                            margin-bottom: 0;
                                            font-weight: 400;
                                            text-align: center;
                                            vertical-align: middle;
                                            touch-action: manipulation;
                                            cursor: pointer;
                                            background-logo: none;
                                            border: 1px solid transparent;
                                            white-space: nowrap;
                                            padding: 6px 12px;
                                            font-size: 13px;
                                            line-height: 1.42857143;
                                            border-radius: 2px;
                                            -webkit-user-select: none;
                                            -moz-user-select: none;
                                            -ms-user-select: none;
                                            user-select: none;
                                        }
                                    </style>
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section id="{{ $image['name'] }}_section">
                                            <label class="label">头像</label>
                                            @include('backend::localImage.upload', ['image' => $image])
                                        </section>
                                        <section>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="password" placeholder="密码" id="password">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
                                        </section>
                                        <section>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="passwordConfirm" placeholder="确认密码">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
                                        </section>
                                        <section>
                                            <label class="input">
                                                <input type="text" value="{{ $user->name }}" name="name" placeholder="名称">
                                            </label>
                                        </section>
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                    </footer>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection

@section('script')
    @include('backend::localImage.script', ['image' => $image])
<script>
    $(document).ready(function() {
        $("#smart-form-register").validate({
            // Rules for form validation
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                passwordConfirm: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                    equalTo: '#password'
                }
            },

            // Messages for form validation
            messages: {
                email: {
                    required: '请输入您的邮箱',
                    email: '请输入有效的邮箱'
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
                },
                name: {
                    required: '请输入的名称'
                }
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>
@endsection
