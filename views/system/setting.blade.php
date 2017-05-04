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
                                        <section id="logo_section">
                                            <label class="label">网站Logo</label>
                                            {{--                                            @include('backend::image.upload', ['image_name' => 'image'])--}}
                                            <span class="btn btn-success fileinput-button">
                                                <i class="icon-plus icon-white"></i>
                                                <span id="mark">上传图片</span>
                                                <input type="file" id="image_file">
                                            </span>
                                            <div class="row image_content"></div>
                                            <div class="error_content"></div>
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
    <link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/jquery.fileupload-ui.css') }}}">
    <link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/blueimp-gallery.min.css') }}}">
    <script>
        $(document).ready(function() {
            @if(!empty($logo->value))
               $("#logo_section .image_content").html('<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"><tr class="template-download fade success in">' +
                '<td width="100%">' +
                '<span class="preview">' +
                '<img id="image" src="{{ url() . $logo->value }}" style="max-width:100%;">' +
                '<input type="hidden" value="{{  $logo->value }}" name="logo">' +
                '</span>' +
                '</td>' +
                '<td style="vertical-align:middle;text-align:left;">' +
                '<button class="btn btn-danger delete" type="button" onclick="$(this).parent().parent().remove();">' +
                '<i class="icon-trash icon-white"></i>' +
                '<span>删除</span>' +
                '</button>' +
                '</td>' +
                '</tr></tbody></table>');
            @endif

            $('#image_file').on('change', function() {
                var image = this.files[0];
                if($("#logo_section .image_content .table tr").length > 0){
                    alert('只允许上传1张图片');
                    return false;
                }

                if(/.(gif|jpg|jpeg|png|bmp)$/.test(image.name)) {
                    $('#logo_section #mark').html('正在上传...');
                    var formData = new FormData();
                    formData.append('logo', image);
                    formData.append('file_name', 'logo');

                    $.ajax({
                        url: "/admin/blueimp/upload4Local",
                        type: "POST",
                        data: formData,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false   // tell jQuery not to set contentType
                    }).done(function(result) {
                        if(result.result) {
                            $("#logo_section .image_content").html('<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"><tr class="template-download fade success in">' +
                                '<td width="100%">' +
                                '<span class="preview">' +
                                '<img id="image" src="' + result.url + '" style="max-width:100%;">' +
                                '<input type="hidden" value="' + result.path + '" name="logo">' +
                                '</span>' +
                                '</td>' +
                                '<td style="vertical-align:middle;text-align:left;">' +
                                '<button class="btn btn-danger delete" type="button" onclick="$(this).parent().parent().remove();">' +
                                '<i class="icon-trash icon-white"></i>' +
                                '<span>删除</span>' +
                                '</button>' +
                                '</td>' +
                                '</tr></tbody></table>');
                        } else {
                            $("#logo_section .error_content").html('<div class="alert alert-danger fade in">' +
                                '<button class="close" data-dismiss="alert">×</button><i class="fa-fw fa fa-times"></i>' +
                                '<strong>失败!</strong>' + result.msg + '</div>');
                        }
                        $('#logo_section #mark').html('上传图片');
                    });

                } else {
                    alert('只允许上传图片');
                    return false;
                }
            });



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
