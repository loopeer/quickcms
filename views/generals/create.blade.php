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
                                                            <select class="select2" name="{{$edit_column[$key]}}" id="select2">
                                                                @foreach(json_decode(Cache::get('selector_'.explode(':',$edit_column_detail[$edit_column[$key]]['type'])[1])) as $k=>$v)
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'image')
                                                            @include('backend::image.upload', ['image_name' => $edit_column[$key]])
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
                                        <button type="submit" id="submit_btn" class="btn btn-primary">
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
    @if ($image_config)
        @include('backend::image.script')
        @foreach($images as $image)
            @include('backend::image.action', ['image' => $image, 'image_data' => $model_data[$image['name']]])
        @endforeach
    @endif
    <script>
        $(document).ready(function() {
            $("#smart-form-register").validate({
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