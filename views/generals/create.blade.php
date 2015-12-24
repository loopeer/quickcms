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
                            <h2>新增{{$model_name}}</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/{{ $route_name }}" method="post" id="smart-form-register" class="smart-form client-form">
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
                                    <input type="hidden" name="id" value="{{ $model_data['id'] }}">
                                    <fieldset>
                                        @foreach($edit_column_name as $key => $column_name)
                                            <section>
                                                <label class="label">{{ $column_name }}</label>
                                                <label class="input">
                                                    @if (isset($edit_column_detail[$edit_column[$key]]['type']))
                                                        @if (explode(':',$edit_column_detail[$edit_column[$key]]['type'])[0] == 'date')
                                                            <input type="text" class="date-format" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                        @elseif(explode(':',$edit_column_detail[$edit_column[$key]]['type'])[0] == 'time')
                                                            <input type="text" class="time" name="{{ $edit_column[$key] }}"  value="{{ $model_data[$edit_column[$key]] }}">
                                                        @elseif(explode(':',$edit_column_detail[$edit_column[$key]]['type'])[0] == 'selector')
                                                            <select class="select2" name="" id="select2">
                                                                @foreach(json_decode(Cache::get('selector_'.explode(':',$edit_column_detail[$edit_column[$key]]['type'])[1])) as $k=>$v)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif(explode(':',$edit_column_detail[$edit_column[$key]]['type'])[0] == 'image')
                                                            <div id="image" class="span10" data-download-template-id="template-download-image">
                                                                <div class="fileupload-buttonbar">
                                                                <span class="btn btn-success fileinput-button">
                                                                    <i class="icon-plus icon-white"></i>
                                                                    <span>添加图片</span>
                                                                    {{--                                                                    @if (isset($image_config[$edit_column[$key]][1]) || $image_config[$edit_column[$key]][0] > 1)--}}
                                                                    {{--<input type="file" name="{{$edit_column[$key]}}[]">--}}
                                                                    {{--@else--}}
                                                                    <input type="file" name="{{$edit_column[$key]}}">
                                                                    {{--@endif--}}
                                                                </span>
                                                                    <div class="fileupload-loading"></div>
                                                                    <label class="error" id="image_error"></label>
                                                                </div>
                                                                <br>
                                                                <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                                                            </div>
                                                        @else
                                                            <input type="text" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                        @endif
                                                    @else
                                                        <input type="text" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                    @endif
                                                </label>
                                            </section>
                                        @endforeach
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="{{ route('admin.' . $route_name . '.index') }}" class="btn btn-primary">
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
    <script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{{ asset('loopeer/quickcms/js/plugin/clockpicker/clockpicker.min.js') }}}"></script>
    @if (count($image_config) > 0)
        <link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/jquery.fileupload-ui.css') }}}">
        <link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/blueimp-gallery.min.css') }}}">
        <script src="{{ asset('loopeer/quickcms/js/blueimp/tmpl.min.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/load-image.min.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/canvas-to-blob.min.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/bootstrap-image-gallery.min.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.iframe-transport.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-process.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-image.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-validate.js') }}"></script>
        <script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-ui.js') }}"></script>
    @endif
    <script>
        $(document).ready(function() {
            var $registerForm = $("#smart-form-register").validate({
                // Rules for form validation

                rules : {
                    @foreach($edit_column as $key=>$column)
                        @if (isset($edit_column_detail[$column]))
                        '{{$column}}' : {
                        @if (isset($edit_column_detail[$column]['validator']))
                        @foreach($edit_column_detail[$column]['validator'] as $k=>$v)
                        '{{$k}}' : function () {
                            return '{{$v}}' ? true : false;
                        },
                        @endforeach
                        @endif
                    },
                    @endif
                @endforeach
            },

                // Messages for form validation
                messages : {
                    @foreach($edit_column as $key=>$column)
                        @if (isset($edit_column_detail[$column]))
                          '{{$column}}' : {
                        @if (isset($edit_column_detail[$column]['message']))
                            @foreach($edit_column_detail[$column]['message'] as $k=>$v)
                            '{{$k}}' : '{{$v}}',
                        @endforeach
                    @endif
                    },
                    @endif
                @endforeach
            },

                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });

            $('.date-format').datepicker({
                dateFormat:'yy-mm-dd',
                changeMonth: true,
                changeYear:true,
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                yearRange: '-0:+40',
                monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
                dayNamesMin: ['日', '一', '二', '三', '四', '五', '六']
            });

            $('.time').clockpicker({
                placement: 'top',
                donetext: '确定'
            });
        });
    </script>
    <script>
        $('.delete').click(function () {
            $(this).parent('tr').remove();
        });
    </script>
@endsection