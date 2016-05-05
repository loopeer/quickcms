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
                            <h2>发送普通邮件</h2>
                        </header>
                        @include('UEditor::head')
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ route('admin.sendcloud.send') }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="label">发件人地址</label>
                                            <label class="input">
                                                <input type="email" id="from" name="from">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">发件人名称</label>
                                            <label class="input">
                                                <input id="fromName" name="fromName">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">默认回复地址</label>
                                            <label class="input">
                                                <input type="email" id="replyTo" name="replyTo">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">邮件主题</label>
                                            <label class="input">
                                                <input id="subject" name="subject">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">邮件内容</label>
                                            <script id="container" name="html" type="text/plain"></script>
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
                                            <label class="label">发送方式</label>
                                            <div class="row">
                                                <div class="col col-4">
                                                    <label class="radio state-success"><input type="radio" class="isGroup" name="isGroup" value="1" checked><i></i>批量发送</label>
                                                    <label class="radio state-success"><input type="radio" class="isGroup" name="isGroup" value="0"><i></i>单独发送</label>
                                                </div>
                                            </div>
                                        </section>
                                        <section id="to_content" style="display: none;">
                                            <label class="label">收件人地址</label>
                                            <label class="input">
                                                <input type="email" id="to" name="to">
                                            </label>
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

            $(".isGroup").click( function () {
                var isGroup = $('input:radio[name="isGroup"]:checked').val();
                //  alert(is_secondary);
                if(isGroup == 1){
                    $('#to_content').hide();
                    $('#to_content').val("");
                } else {
                    $('#to_content').show();
                }
            });

            var $registerForm = $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    from : {
                        required : true
                    },subject : {
                        required : true
                    }
                },

                // Messages for form validation
                messages : {
                    from : {
                        required : '必须填写发件人地址'
                    },subject : {
                        required : '必须填写邮件主题'
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