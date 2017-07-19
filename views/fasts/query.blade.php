@if(count($queries) > 0)
<div class="jarviswidget jarviswidget-sortable jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false"
     data-widget-colorbutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-search"></i> </span>
        <h2>{{ $model->module . '查询' }}</h2>
    </header>
    <div role="content">
        <div class="jarviswidget-editbox">
        </div>
        <div class="widget-body">
            <form id="query-form" class="form-horizontal">
                <fieldset>
                    @foreach(collect($queries)->chunk(3) as $chunk)
                        <div class="form-group" style="margin-bottom: 0;margin-top: 10px;">
                            @foreach($chunk as $query)
                            <label class="col-md-1 control-label">{{ trans('fasts.' . $model->route . '.' . $query['column']) }}</label>
                            <div class="col-md-2">
                                @if(!isset($query['type']) || $query['type'] == 'input')
                                    @if($query['query'] == 'between')
                                        <input type="text" class="form-control" style="width:50%;float:left" id="{{ $query['column']."_from" }}" value="{{ isset($query['default']) ? $query['default'] : '' }}">
                                        <input type="text" class="form-control" style="width:50%;float:left" id="{{ $query['column']."_to" }}" value="{{ isset($query['default']) ? $query['default'] : '' }}">
                                    @else
                                        <input class="form-control" type="text" id="@if(strstr($query['column'], '.') !== FALSE){{ str_replace('.', '-', $query['column']) }}@else{{ $query['column'] }}@endif" value="{{ isset($query['default']) ? $query['default'] : '' }}">
                                    @endif
                                @elseif($query['type'] == 'select')
                                    <select class="form-control" id="@if(strstr($query['column'], '.') !== FALSE){{ str_replace('.', '-', $query['column']) }}@else{{ $query['column'] }}@endif">
                                        <option>全部</option>
                                        @foreach(is_array($query['param']) ? $query['param'] : ${$query['param']} as $sk => $sv)
                                            <option value="{{ $sk }}" {{ isset($query['default']) && $query['default'] == $sk ? 'selected' : '' }}>{{ $sv }}</option>
                                        @endforeach
                                    </select>
                                @elseif($query['type'] == 'checkbox')
                                    @foreach(${$query['param']} as $sk => $sv)
                                        <label class="checkbox-inline">
                                            <input {{ isset($query['default']) && in_array($sk, (array)$query['default']) ? 'checked' : '' }} type="checkbox" class="checkbox style-0" name="{{ $query['column'] }}" value="{{ $sk }}">
                                            <span>{{ $sv }}</span>
                                        </label>
                                    @endforeach
                                @elseif($query['type'] == 'date' || $query['type'] == 'datetime')
                                    <div class="input-group date">
                                        @if($query['query'] == 'between')
                                            <input class="form-control {{ $query['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                   data-date-format="{{ $query['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                   style="width: 50%;" type="text" id="{{ $query['column'] . '_from' }}" value="{{ isset($query['default'][0]) ? $query['default'][0] : '' }}">
                                            <input class="form-control {{ $query['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                   data-date-format="{{ $query['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                   style="width: 50%;" type="text" id="{{ $query['column'] . '_to' }}" value="{{ isset($query['default'][1]) ? $query['default'][1] : '' }}">
                                        @else
                                            <input class="form-control {{ $query['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                   data-date-format="{{ $query['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                   style="width: 50%;" type="text" id="{{ $query['column'] }}" value="{{ isset($query['default']) ? $query['default'] : '' }}">
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </fieldset>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-2">
                            <button id="reset" class="btn btn-primary" type="button">
                                <i class="fa fa-undo"></i>
                                重置
                            </button>
                            &nbsp;
                            <button id="query" class="btn btn-primary" type="button">
                                <i class="fa fa-search"></i>
                                查询
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif