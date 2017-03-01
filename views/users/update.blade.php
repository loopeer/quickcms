@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6">
                    @include('backend::layouts.message')
                    <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>个人资料 </h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/users/profile" method="post" id="smart-form-register" class="smart-form">
                                    {!! csrf_field() !!}
                                    <fieldset>
                                    <section>
                                        <label class="label">图片</label>
                                        @include('backend::image.upload', ['image_name' => 'image'])
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
@include('backend::image.script')
@include('backend::image.action', ['image' => $image, 'image_data' => $user->avatar])
<script>
    $(document).ready(function() {
        var $registerForm = $("#smart-form-register").validate({
            // Rules for form validation
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 3,
                    maxlength: 20
                },

                // Messages for form validation
                messages: {
                    email: {
                        required: '请输入您的邮箱',
                        email: '请输入有效的邮箱'
                    },
                    password: {
                        required: '请输入您的密码'
                    },
                    passwordConfirm: {
                        required: '请输入您的密码',
                        equalTo: '请输入相同的密码'
                    },
                    name: {
                        required: '请输入的名称'
                    }
                },

                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            }
        });
    });
</script>
@endsection
