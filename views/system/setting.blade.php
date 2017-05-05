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
                            <h2>系统基本设置 </h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/systems/setting" method="post" id="smart-form-register" class="smart-form">
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
                                        #logo {
                                            width: 100% !important;
                                        }
                                    </style>
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="label">网站标题</label>
                                            <label class="input">
                                                <input type="text" value="{{ $title->value }}" name="title">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">build号</label>
                                            <label class="input">
                                                <input type="text" value="{{ $build->value }}" name="build">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">android下载地址</label>
                                            <label class="input">
                                                <input type="text" value="{{ $android_download->value }}" name="android_download">
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">是否在审核期间</label>
                                            <label class="select">
                                                <select name="app_review">
                                                    <option value="0" @if($app_review->value == 0) selected @endif>否</option>
                                                    <option value="1" @if($app_review->value == 1) selected @endif>是</option>
                                                </select>
                                                <i> </i>
                                            </label>
                                        </section>
                                        <section id="{{ $logo_upload['name'] }}_section">
                                            <label class="label">网站logo</label>
                                            @include('backend::localImage.upload', ['image' => $logo_upload])
                                        </section>
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="{{URL::previous()}}" class="btn btn-primary">
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
    @include('backend::localImage.script', ['image' => $logo_upload])
    <script>
        $(document).ready(function() {

            $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    title : {
                        required : true
                    }
                },
                // Messages for form validation
                messages : {
                    title : {
                        required : '请输入网站标题'
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
