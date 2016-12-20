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
                            <h2>用户管理 </h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{$action}}" method="post" id="smart-form-register" class="smart-form">
                                    {!! csrf_field() !!}
                                    <header>
                                        @if (isset($user->id))
                                            编辑用户
                                        @else
                                            新建用户
                                        @endif
                                    </header>
                                    <fieldset>
                                        @if (is_null($user->id))
                                            <section>
                                                <label class="label">账号</label>
                                                <label class="input"> <i class="icon-append fa fa-envelope-o"></i>
                                                    <input type="email" name="email" id="email" placeholder="邮箱">
                                                    <b class="tooltip tooltip-bottom-right">登录时输入的账号</b> </label>
                                            </section>
                                        @endif
                                        <section>
                                            <label class="label">密码</label>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="password" placeholder="输入密码" id="password">
                                                <b class="tooltip tooltip-bottom-right">不要忘记你的密码</b> </label>
                                            @if (isset($user->id))
                                                <div class="note">
                                                    不填写则不修改
                                                </div>
                                            @endif
                                        </section>
                                        <section>
                                            <label class="label">确认密码</label>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="passwordConfirm" placeholder="确认密码">
                                                <b class="tooltip tooltip-bottom-right">不要忘记你的密码</b> </label>
                                        </section>
                                        <section>
                                            <label class="label">姓名</label>
                                            <label class="input">
                                                <input type="text" name="name" value="{{$user->name}}" placeholder="姓名">
                                            </label>
                                        </section>
                                        @if (is_null($user->id))
                                            <section>
                                                <label class="label">选择角色</label>
                                                <div class="row">
                                                    <div class="col col-6">
                                                        <select name="role_id" class="select2">
                                                            @foreach($roles as $role)
                                                                <option value="{{{$role->id}}}">{{{$role->display_name}}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>
                                        @endif
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="/admin/users" class="btn btn-primary">
                                            返回
                                        </a>
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
    <script>
        $(document).ready(function() {
            $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    name : {
                        required : true
                    },
                    email : {
                        @if (is_null($user->id))
                        required : true,
                        remote:{
                            url: "/admin/users/checkEmail",     //后台处理程序
                            type: "get",               //数据发送方式
                            dataType: "json",           //接受数据格式
                            data: {                     //要传递的数据
                                email: function() {
                                    return $("#email").val();
                                }
                            }
                        }
                        @endif
                    },
                    password : {
                        @if (is_null($user->id))
                        required : true,
                        minlength : 6,
                        maxlength : 20
                        @endif
                    },
                    passwordConfirm : {
                        @if (is_null($user->id))
                        required : true,
                        minlength : 6,
                        maxlength : 20,
                        @endif
                        equalTo : '#password'
                    }
                },
                // Messages for form validation
                messages : {
                    name : {
                        required : '请输入姓名'
                    },
                    email : {
                        required : '请输入登陆邮箱',
                        remote: '此邮箱已被注册'
                    },
                    password : {
                        required : '请输入密码'
                    },
                    passwordConfirm : {
                        required : '请确认密码',
                        equalTo : '两次输入的密码不一致'
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