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
                            <h2>新增角色</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ $action }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                                <input type="text" name="name" placeholder="名称" value="{{$role->name}}">
                                                <b class="tooltip tooltip-bottom-right">角色标示</b> </label>
                                        </section>
                                        <section>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="display_name" placeholder="显示名称" id="display_name" value="{{$role->display_name}}">
                                                <b class="tooltip tooltip-bottom-right">角色显示名称</b> </label>
                                        </section>
                                        <section>
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="description" placeholder="描述" value="{{$role->description}}">
                                                <b class="tooltip tooltip-bottom-right">角色描述</b> </label>
                                        </section>
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="{{route('admin.roles.index')}}" class="btn btn-primary">
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
            var $registerForm = $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    name : {
                        required : true
                    },display_name : {
                        required : true
                    },route : {
                        required : true
                    }
                },

                // Messages for form validation
                messages : {
                    name : {
                        required : '必须填写角色名称'
                    },display_name : {
                        required : '必须填写显示名称'
                    },route : {
                        required : '必须填写路由'
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