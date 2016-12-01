@extends('backend::layouts.master')
@section('style')
    <style>
        #ui-datepicker-div {
            z-index: 9999999!important;
        }
        .select2-hidden-accessible {
            display: none;
        }
         .bootstrap-tagsinput {
             color: #555;
             font-size: 13px;
             line-height: 1.42857;
         }
        .bootstrap-tagsinput .tag {
            color: #fff;
            display: inline-block;
            margin: 3px 0 3px 2px;
            position: relative;
        }
        .bootstrap-tagsinput > span {
            background: none repeat scroll 0 0 #3276b1;
            border: 1px solid #285e8e;
            border-radius: 0 !important;
            font-size: 13px;
            font-weight: 400;
            padding: 3px 28px 4px 8px;
        }
    </style>
@endsection
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6">
                    @include('backend::layouts.message')
                    <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>
                                {{ $model_data['id'] ? '编辑' : '新增'}}{{ $model_name }}
                            </h2>
                        </header>
                        @if(isset($edit_editor))
                            @include('UEditor::head')
                        @endif
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ isset($custom_id) ? $route_path : '/admin/' . $route_name }}" method="post" id="smart-form-register" class="smart-form client-form">
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
                                    @if ($model_data['id'])
                                        <input type="hidden" name="id" value="{{ $model_data['id'] }}">
                                    @endif
                                    @if(isset($edit_hidden))
                                        @foreach($edit_hidden as $hidden)
                                            <input type="hidden" name="{{ $hidden['name'] }}" value="{{ $hidden['value'] }}">
                                        @endforeach
                                    @endif
                                    @if(isset($edit_hidden_business_id))
                                        <input type="hidden" name="{{ $edit_hidden_business_id['name'] }}" value="{{ $edit_hidden_business_id['value'] }}">
                                    @endif
                                    @if(isset($custom_id))
                                        <input type="hidden" name="{{ $custom_id_relation_column }}" value="{{ $custom_id }}">
                                    @endif
                                    <fieldset>
                                        @foreach($edit_column as $key => $column_name)
                                            <section>
                                                <label class="label">
                                                    {{ $edit_column_name ? $edit_column_name[$key] : $column_names[$column_name] }}
                                                </label>
                                                @if (isset($edit_column_detail[$edit_column[$key]]['type']))
                                                    @if($edit_column_detail[$edit_column[$key]]['type'] == 'checkbox')
                                                        <div class="inline-group">
                                                        @foreach($edit_column_detail[$edit_column[$key]]['value'] as $checkBoxKey => $checkBoxValue)
                                                            <label class="checkbox">
                                                            <input type="checkbox" name="{{ $column_name }}[]" value="{{ $checkBoxKey }}" {{ in_array($checkBoxKey, explode(',', $model_data[$edit_column[$key]])) == true ? 'checked' : ''}}>
                                                            <i></i>{{ $checkBoxValue }}
                                                            </label>
                                                        @endforeach
                                                        </div>
                                                    @else
                                                    <label style="width: 100%;" class="{{ $edit_column_detail[$edit_column[$key]]['style'] or 'input' }}">
                                                        @if ($edit_column_detail[$edit_column[$key]]['type'] == 'date')
                                                            <div class="input-group">
                                                                <input type="text" class="date-format" id="{{ $edit_column[$key] }}" name="{{ $edit_column[$key] }}"
                                                                       value="{{ (!$model_data['id'] && isset($edit_column_detail[$edit_column[$key]]['default_value'])) ? date('Y-m-d', time()) : $model_data[$edit_column[$key]] }}">
                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'time')
                                                            <input type="text" class="time" name="{{ $edit_column[$key] }}"  value="{{ $model_data[$edit_column[$key]] }}">
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'selector')
                                                            <select class="{{ $edit_column_detail[$edit_column[$key]]['style'] or 'select' }}" name="{{$edit_column[$key]}}" id="select2">
                                                                @foreach($selector_data[$edit_column_detail[$edit_column[$key]]['selector_key']] as $k=>$v)
                                                                    @if($model_data[$edit_column[$key]] == $k)
                                                                        <option selected value="{{$k}}">{{$v}}</option>
                                                                    @else
                                                                        <option value="{{$k}}">{{$v}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @if(isset($edit_column_detail[$edit_column[$key]]['style']) && $edit_column_detail[$edit_column[$key]]['style'] == 'select')
                                                                <i></i>
                                                            @endif
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'tagsinput')
                                                            <input class="form-control tagsinput" name="{{ $edit_column[$key] }}" type="text" value="{{ $model_data[$edit_column[$key]] }}" data-role="tagsinput">
                                                            <div class="note">
                                                                每输入一个标签后请按回车键确认
                                                            </div>
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'editor')
                                                            @if(isset($edit_column_detail[$edit_column[$key]]['language']))
                                                                @if(!$model_data['id'])
                                                                    @foreach($language as $lang_key => $lang_value)
                                                                        <label>{{ $lang_value }}</label>
                                                                        <script id="{{ $edit_column[$key] . '_editor' . '_' . $lang_key }}" name="{{ $edit_column[$key] . '_' . $lang_key }}" type="text/plain">{!! $model_data[$edit_column[$key]] !!}</script>
                                                                        <!-- 实例化编辑器 -->
                                                                        <script type="text/javascript">
                                                                            var toolbar = '{{ isset($edit_column_detail[$edit_column[$key]]["toolbars"]) ? implode(',',  $edit_column_detail[$edit_column[$key]]["toolbars"]) : '' }}';
                                                                            var toolbars = new Array();
                                                                            if(toolbar != null) {
                                                                                toolbars = toolbar.split(',');
                                                                            }
                                                                            var ue = UE.getEditor('{{ $edit_column[$key] . '_editor' . '_' . $lang_key }}',{
                                                                                @if(isset($edit_column_detail[$edit_column[$key]]["toolbars"]))
                                                                                toolbars: [toolbars],
                                                                                @endif
                                                                                autoHeightEnabled: true,
                                                                                lang:"zh-cn",
                                                                                autoFloatEnabled: true,
                                                                                initialFrameHeight: 350
                                                                            });
                                                                            ue.ready(function() {
                                                                                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                                                                                //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                                                            });
                                                                        </script>
                                                                        <br>
                                                                    @endforeach
                                                                @else
                                                                    @foreach($language_resource_editor as $lang_res_key => $lang_res_value)
                                                                        <label>{{ $lang_res_value->language }}</label>
                                                                        <script id="{{ $edit_column[$key] . '_editor' . '_' . $lang_res_value->language }}" name="{{ $edit_column[$key] . '_' . $lang_res_value->language }}" type="text/plain">{!! $lang_res_value->value !!}</script>
                                                                        <!-- 实例化编辑器 -->
                                                                        <script type="text/javascript">
                                                                            var toolbar = '{{ isset($edit_column_detail[$edit_column[$key]]["toolbars"]) ? implode(',',  $edit_column_detail[$edit_column[$key]]["toolbars"]) : '' }}';
                                                                            var toolbars = new Array();
                                                                            if(toolbar != null) {
                                                                                toolbars = toolbar.split(',');
                                                                            }
                                                                            var ue = UE.getEditor('{{ $edit_column[$key] . '_editor' . '_' . $lang_res_value->language }}',{
                                                                                @if(isset($edit_column_detail[$edit_column[$key]]["toolbars"]))
                                                                                toolbars: [toolbars],
                                                                                @endif
                                                                                autoHeightEnabled: true,
                                                                                lang:"zh-cn",
                                                                                autoFloatEnabled: true,
                                                                                initialFrameHeight: 350
                                                                            });
                                                                            ue.ready(function() {
                                                                                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                                                                                //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                                                            });
                                                                        </script>
                                                                        <br>
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                <script id="{{ $edit_column[$key] . '_editor' }}" name="{{ $edit_column[$key] }}" type="text/plain">{!! $model_data[$edit_column[$key]] !!}</script>
                                                                <!-- 实例化编辑器 -->
                                                                <script type="text/javascript">
                                                                    var toolbar = '{{ isset($edit_column_detail[$edit_column[$key]]["toolbars"]) ? implode(',',  $edit_column_detail[$edit_column[$key]]["toolbars"]) : '' }}';
                                                                    var toolbars = new Array();
                                                                    if(toolbar != null) {
                                                                        toolbars = toolbar.split(',');
                                                                    }
                                                                    var ue = UE.getEditor('{{ $edit_column[$key] . '_editor' }}',{
                                                                        @if(isset($edit_column_detail[$edit_column[$key]]["toolbars"]))
                                                                        toolbars: [toolbars],
                                                                        @endif
                                                                        autoHeightEnabled: true,
                                                                        lang:"zh-cn",
                                                                        autoFloatEnabled: true,
                                                                        initialFrameHeight: 350
                                                                    });
                                                                    ue.ready(function() {
                                                                        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                                                                        //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                                                    });
                                                                </script>
                                                            @endif
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'image')
                                                            @include('backend::image.upload', ['image_name' => $edit_column[$key]])
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'file')
                                                            @include('backend::dropzone.layout', ['dropzone_id' => isset($edit_column_detail[$edit_column[$key]]['dropzone_id']) ? $edit_column_detail[$edit_column[$key]]['dropzone_id'] : null])
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'textarea')
                                                            <label class="textarea">
                                                                <textarea name="{{ $edit_column[$key] }}" rows="{{ $edit_column_detail[$edit_column[$key]]['row'] }}">{{ $model_data[$edit_column[$key]] }}</textarea>
                                                        </label>
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'language')
                                                            @if(!$model_data['id'])
                                                                @foreach($language as $lang_key => $lang_value)
                                                                    <input type="text" required="" placeholder="{{ $lang_value }}" name="{{ $edit_column[$key] . '_' . $lang_key }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                                    <br>
                                                                @endforeach
                                                            @else
                                                                @foreach($language_resource as $lang_res_key => $lang_res_value)
                                                                    <input type="text" placeholder="{{ $language[$lang_res_value->language] }}"
                                                                           name="{{ $edit_column[$key] . '_' . $lang_res_value->language }}"
                                                                           value="{{ $lang_res_value->value }}">
                                                                    <br>
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            <input type="text" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                        @endif
                                                        </label>
                                                    @endif
                                                @else
                                                    <label class="input">
                                                        <input type="text" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                    </label>
                                                @endif
                                            </section>
                                        @endforeach
                                    </fieldset>
                                    <footer>
                                        <button type="submit" id="submit_btn" class="btn btn-primary">
                                            保存
                                        </button>
                                        @if(!isset($business_user))
                                            @if(isset($custom_id))
                                                <a href="{{URL::previous()}}" class="btn btn-primary">
                                                    返回
                                                </a>
                                            @else
                                                <a href="{{ route('admin.' . $route_name . '.index') }}" class="btn btn-primary">
                                                    返回
                                                </a>
                                            @endif
                                        @endif
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
    {{--    <script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>--}}
    <script src="{{{ asset('loopeer/quickcms/js/plugin/clockpicker/clockpicker.min.js') }}}"></script>
    <script src="{{{ asset('loopeer/quickcms/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js') }}}"></script>
    <script src="{{{ asset('loopeer/quickcms/js/plugin/bootstrap-tags/bootstrap-tagsinput-angular.min.js') }}}"></script>
    @if ($image_config)
        @include('backend::image.script')
        @foreach($images as $image)
            @include('backend::image.action', ['image' => $image, 'image_data' => $model_data[$image['name']]])
        @endforeach
    @endif
    @if($file_config)
        @include('backend::dropzone.script')
        @foreach($files as $file)
            @include('backend::dropzone.action', $file)
        @endforeach
    @endif
    <script>
        $(document).ready(function() {
            $("#smart-form-register").validate({
                // Rules for form validation
                rules: {
                    @foreach($edit_column as $key=>$column)
                    @if (isset($edit_column_detail[$column]))
                    '{{$column}}': {
                        @if (isset($edit_column_detail[$column]['validator']))
                        @foreach($edit_column_detail[$column]['validator'] as $k=>$v)
                        '{{$k}}': function () {
                            return '{{$v}}' ? true : false;
                        },
                        @endforeach
                        @endif
                    },
                    @endif
                    @endforeach
                },
                // Messages for form validation
                messages: {
                    @foreach($edit_column as $key=>$column)
                    @if (isset($edit_column_detail[$column]))
                    '{{$column}}': {
                        @if (isset($edit_column_detail[$column]['message']))
                        @foreach($edit_column_detail[$column]['message'] as $k=>$v)
                        '{{$k}}': '{{$v}}',
                        @endforeach
                    @endif
                    },
                    @endif
                    @endforeach
                },
                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
                    $('#submit_btn').attr('disabled', true);
                    form.submit();
                }
            });

            @foreach($edit_column_detail as $edit_key => $edit_column)
            @if(isset($edit_column['type']) && $edit_column['type'] == 'date')
            $("#" + '{!! $edit_key !!}').datepicker({
                dateFormat: '{{ (isset($edit_column['date_picker']['dateFormat']) && $edit_column['date_picker']['dateFormat']) ? $edit_column['date_picker']['dateFormat'] : 'yy-mm-dd' }}',
                changeMonth: true,
                changeYear:true,
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                yearRange: '{{ (isset($edit_column['date_picker']['yearRange']) && $edit_column['date_picker']['yearRange']) ? $edit_column['date_picker']['yearRange'] : '-20:20' }}',
                minDate: '{{ (isset($edit_column['date_picker']['minDate']) && $edit_column['date_picker']['minDate']) ? $edit_column['date_picker']['minDate'] : '' }}',
                maxDate: '{{ (isset($edit_column['date_picker']['maxDate']) && $edit_column['date_picker']['maxDate']) ? $edit_column['date_picker']['maxDate'] : '' }}',
                monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
                dayNamesMin: ['日', '一', '二', '三', '四', '五', '六']
            });
            @endif
            @endforeach

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