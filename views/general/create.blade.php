@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6">
                    @include('backend::layouts.message')
                    <div class="jarviswidget" id="wid-id-4" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"><i class="fa fa-edit"></i></span>
                            <h2>新增</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/{{ $model->routeName }}" method="post" id="create-form" class="smart-form client-form">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    @foreach($model->createHidden as $hidden)
                                        @if(!isset($hidden['action']) || ($hidden['action'] == 'create' && !isset($data->id)) || ($hidden['action'] == 'edit' && isset($data->id)))
                                            <input type="hidden" name="{{ $hidden['column'] }}" value="{{ $hidden['value'] == 'admin' ? Auth::admin()->get()->email : $hidden['value'] }}">
                                        @endif
                                    @endforeach
                                    <fieldset>
                                    @foreach($model->create as $item)
                                        <section>
                                            <label class="label">{{ $item['name'] }}</label>
                                            @if(!isset($item['type']) || $item['type'] == 'text')
                                                <label class="input">
                                                    <input type="text" name="{{ $item['column'] }}" value="{{ $data->$item['column'] }}">
                                                </label>
                                            @elseif($item['type'] == 'password')
                                                <label class="input">
                                                    <input type="password" name="{{ $item['column'] }}" value="{{ $data->$item['column'] }}">
                                                </label>
                                            @elseif($item['type'] == 'select')
                                                <label class="select">
                                                    <select name="{{ $item['column'] }}">
                                                    @foreach(${$item['param']} as $sk => $sv)
                                                        <option value="{{ $sk }}">{{ $sv }}</option>
                                                    @endforeach
                                                    </select><i></i>
                                                </label>
                                            @elseif($item['type'] == 'checkbox')
                                                <div class="inline-group">
                                                    @foreach(is_array($item['param']) ? $item['param'] : ${$item['param']} as $cbk => $cbv)
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="{{ $item['column'] }}[]" value="{{ $cbk }}" {{ isset($data->$item['column']) && in_array($cbk, $data->$item['column']) ? 'checked' : '' }}>
                                                        <i></i>{{ $cbv }}</label>
                                                    @endforeach
                                                </div>
                                            @elseif($item['type'] == 'radio')
                                                <div class="inline-group">
                                                    @foreach(is_array($item['param']) ? $item['param'] : ${$item['param']} as $rk => $rv)
                                                        <label class="radio">
                                                            <input type="radio" name="{{ $item['column'] }}" value="{{ $rk }}" {{ $rk == $data->$item['column'] ? 'checked' : '' }}>
                                                            <i></i>{{ $rv }}</label>
                                                    @endforeach
                                                </div>
                                            @elseif($item['type'] == 'tags')
                                                <input class="form-control tagsinput" name="{{ $item['column'] }}" value="{{ $data->$item['column'] }}" data-role="tagsinput">
                                                <div class="note">每输入一个标签后请按回车键确认</div>
                                            @elseif($item['type'] == 'date')
                                                <div class="input-group date form_date" data-date-format="yyyy-mm-dd">
                                                    <input class="form-control" size="16" type="text" name="{{ $item['column'] }}" value="" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            @elseif($item['type'] == 'datetime')
                                                <div class="input-group date form_datetime" data-date-format="yyyy-mm-dd hh:ii">
                                                    <input class="form-control" size="16" type="text" name="{{ $item['column'] }}" value="" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            @elseif($item['type'] == 'time')
                                                <div class="input-group date form_time" data-date-format="hh:ii">
                                                    <input class="form-control" size="16" type="text" name="{{ $item['column'] }}" value="" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            @elseif($item['type'] == 'editor')
                                                <script id="{{ $item['column'] }}-container" name="{{ $item['column'] }}" type="text/plain">{!! $data->$item['column'] !!}</script>
                                                <script type="text/javascript">var ue = UE.getEditor("{{ $item['column'] }}-container");</script>
                                            @elseif($item['type'] == 'image')
                                                @include('backend::image.upload', ['image' => $item, 'image_name' => $item['column']])
                                            @endif
                                        </section>
                                    @endforeach
                                    </fieldset>
                                    <footer>
                                        <button type="submit" id="submit_btn" class="btn btn-primary">
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

    @include('backend::image.script')

    @foreach($model->create as $itemImage)
        @if(isset($itemImage['type']) && $itemImage['type'] == 'image')
            @include('backend::image.action', ['image' => $itemImage, 'image_data' => $data->$itemImage['column']])
        @endif
    @endforeach

    <script>
        $(document).ready(function() {

            pageSetUp();

            $('#create-form').validate({
                // Rules for form validation
                rules: {
                    @foreach($model->create as $ruleItem)
                        @if(isset($ruleItem['rules']))
                        '{{ $ruleItem['column'] }}': {
                            @foreach($ruleItem['rules'] as $ruleKey => $ruleValue)
                            '{!! $ruleKey !!}' : '{!! $ruleValue !!}',
                            @endforeach
                        },
                        @endif
                    @endforeach
                },

                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });

            $('.form_datetime').datetimepicker({
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });

            $('.form_date').datetimepicker({
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });

            $('.form_time').datetimepicker({
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0
            });

        });
    </script>
@endsection