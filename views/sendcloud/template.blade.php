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
                            <h2>发送模板邮件</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ route('admin.sendcloud.sendTemplate') }}" method="post" id="smart-form-register" class="smart-form client-form">
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
                                        {{--<section>--}}
                                            {{--<label class="label">发件人地址</label>--}}
                                            {{--<label class="input">--}}
                                                {{--<input type="email" id="from" name="from">--}}
                                            {{--</label>--}}
                                        {{--</section>--}}
                                        {{--<section>--}}
                                            {{--<label class="label">发件人名称</label>--}}
                                            {{--<label class="input">--}}
                                                {{--<input id="fromName" name="fromName">--}}
                                            {{--</label>--}}
                                        {{--</section>--}}
                                        {{--<section>--}}
                                            {{--<label class="label">默认回复地址</label>--}}
                                            {{--<label class="input">--}}
                                                {{--<input type="email" id="replyTo" name="replyTo">--}}
                                            {{--</label>--}}
                                        {{--</section>--}}
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
                                        <section>
                                            <label class="label">是否批量发送</label>
                                            <div class="row">
                                                <div class="col col-4">
                                                    <label class="radio state-success"><input type="radio" class="isGroup" name="isGroup" value="1" checked><i></i>是</label>
                                                    <label class="radio state-success"><input type="radio" class="isGroup" name="isGroup" value="0"><i></i>否</label>
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
                    }
                },

                // Messages for form validation
                messages : {
                    from : {
                        required : '必须填写发件人地址'
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