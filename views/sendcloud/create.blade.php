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
                            <h2>创建模板</h2>
                        </header>
                        @include('UEditor::head')
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ $action }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="label">模板名称</label>
                                            <label class="input">
                                                <input id="name" name="name" value="{{ isset($template) ? $template->name : '' }}">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">调用名称</label>
                                            <label class="input">
                                                <input id="invokeName" name="invokeName" value="{{ isset($template) ? $template->invokeName : '' }}">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">邮件标题</label>
                                            <label class="input">
                                                <input id="subject" name="subject" value="{{ isset($template) ? $template->subject : '' }}">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">邮件类型</label>
                                            <div class="row">
                                                <div class="col col-4">
                                                    <label class="radio state-success"><input type="radio" name="templateType" value="0" @if(isset($template) && $template->templateType == 0) checked @endif><i></i>触发邮件</label>
                                                    <label class="radio state-success"><input type="radio" name="templateType" value="1" @if(isset($template) && $template->templateType == 1) checked @endif><i></i>批量邮件</label>
                                                </div>
                                            </div>
                                        </section>
                                        <section>
                                            <label class="label">模板内容</label>
                                            <script id="container" name="html" type="text/plain">{!! isset($template) ? $template->html : '' !!}</script>
                                            <!-- 实例化编辑器 -->
                                            <script type="text/javascript">
                                                var ue = UE.getEditor('container',{
                                                    autoHeightEnabled: true,
                                                    lang:"zh-cn",
                                                    autoFloatEnabled: true,
                                                    initialFrameHeight: 500,
                                                    retainOnlyLabelPasted: false,
                                                    pasteplain: false
                                                });
                                                ue.ready(function() {
                                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                                                    //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                                });
                                            </script>
                                        </section>
                                        <section>
                                            <label class="label">是否提交审核</label>
                                            <div class="row">
                                                <div class="col col-4">
                                                    <label class="radio state-success"><input type="radio" name="isSubmitAudit" value="1" checked><i></i>是</label>
                                                    <label class="radio state-success"><input type="radio" name="isSubmitAudit" value="0"><i></i>否</label>
                                                </div>
                                            </div>
                                        </section>
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