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
                                <form action="/admin/{{ $model->routeName }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <fieldset>
                                    @foreach($model->create as $item)
                                        <section>
                                            <label class="label">{{ $item['name'] }}</label>
                                            @if(!isset($item['type']) || $item['type'] == 'text')
                                                <label class="input">
                                                    <input type="text" name="{{ $item['column'] }}" value="{{ $data->$item['column'] }}" maxlength="10">
                                                </label>
                                            @elseif($item['type'] == 'password')
                                                <label class="input">
                                                    <input type="password" name="{{ $item['column'] }}" value="{{ $data->$item['column'] }}" maxlength="10">
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
                                                        <input type="checkbox" name="{{ $item['column'] }}[]" value="{{ $cbk }}" {{ in_array($cbk, $data->$item['column']) ? 'checked' : '' }}>
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
                                                <div class="input-group">
                                                    <input type="text" name="{{ $item['column'] }}" class="form-control datepicker" data-dateformat="yy-mm-dd">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
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
    <script src="{{{ asset('loopeer/quickcms/js/plugin/clockpicker/clockpicker.min.js') }}}"></script>

    <script>
        $( ".datepicker" ).datepicker({
            changeYear: true,
            changeMonth: true
        });
    </script>
@endsection