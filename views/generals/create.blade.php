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
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{ $model_data['id'] }}">
                                    <fieldset>
                                        @foreach($edit_column_name as $key => $column_name)
                                        <section>
                                            <label class="label">{{ $column_name }}</label>
                                            <label class="input">
                                                @if (array_key_exists($edit_column[$key], $edit_column_detail))
                                                    @if ($edit_column_detail[$edit_column[$key]]['type'] == 'date')
                                                    <input type="text" class="date-format" name="{{ $edit_column[$key] }}" value="{{ $model_data[$edit_column[$key]] }}">
                                                    @elseif($edit_column_detail[$edit_column[$key]]['type'] == 'time')
                                                    <input type="text" class="time" name="{{ $edit_column[$key] }}"  value="{{ $model_data[$edit_column[$key]] }}">
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

    <script>
        $(document).ready(function() {
            var $registerForm = $("#smart-form-register").validate({
                // Rules for form validation

                rules : {
                    @foreach($edit_column as $key=>$column)
                        @if (isset($edit_column_detail[$column]))
                        '{{$column}}' : {
                            @foreach($edit_column_detail[$column]['validator'] as $k=>$v)
                            '{{$k}}' : function () {
                                return '{{$v}}' ? true : false;
                            }
                            @endforeach
                        },
                        @endif
                    @endforeach
                },

                // Messages for form validation
//                messages : {
//                    name : {
//                        required : '必须填写角色名称'
//                    },display_name : {
//                        required : '必须填写显示名称'
//                    },route : {
//                        required : '必须填写路由'
//                    }
//                },

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
@endsection