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
                            <h2>发送邮件</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ $action }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="label">选择模板</label>
                                            <div class="row">
                                                <div class="col col-8">
                                                    <select name="templateInvokeName" class="select2">
                                                        <optgroup label="触发邮件">
                                                            @foreach($trigger_templates as $trigger_template)
                                                                <option value="{{{$trigger_template->invokeName}}}">{{{$trigger_template->invokeName}}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        <optgroup label="批量邮件">
                                                            @foreach($group_templates as $group_template)
                                                                <option value="{{{$group_template->invokeName}}}">{{{$group_template->invokeName}}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </section>
                                        <section>
                                            <button type="button" id="preview" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> 添加自定义字段</button>
                                        </section>
                                        <div id="fields_content">
                                            <div class="row">
                                                <section class="col col-6">
                                                    <label class="input">
                                                        <input type="text" name="field_name[]" placeholder="字段名">
                                                    </label>
                                                </section>
                                                <section class="col col-6">
                                                    <label class="input">
                                                        <input type="text" name="field_value[]" placeholder="字段值">
                                                    </label>
                                                </section>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            提交
                                        </button>
                                        <a href="{{route('admin.sendcloud.index')}}" class="btn btn-primary">
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
            pageSetUp();

//            var $registerForm = $("#smart-form-register").validate({
//                // Rules for form validation
//                rules : {
//                    name : {
//                        required : true
//                    },display_name : {
//                        required : true
//                    },route : {
//                        required : true
//                    }
//                },
//
//                // Messages for form validation
//                messages : {
//                    name : {
//                        required : '必须填写权限名称'
//                    },display_name : {
//                        required : '必须填写显示名称'
//                    },route : {
//                        required : '必须填写路由'
//                    }
//                },
//
//                // Do not change code below
//                errorPlacement : function(error, element) {
//                    error.insertAfter(element.parent());
//                }
//            });
        });
    </script>
@endsection