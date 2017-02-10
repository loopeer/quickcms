@if(count($query) > 0)
<div class="jarviswidget jarviswidget-sortable jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false"
     data-widget-colorbutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-search"></i> </span>
        <h2>{{ $model_name }}查询</h2>
    </header>
    <div role="content">
        <div class="jarviswidget-editbox">
        </div>
        <div class="widget-body">
            <form class="form-horizontal">
                <fieldset>
                    @for($i = 0; $i < count($query) / 3; $i++)
                        <div class="form-group" style="margin-bottom: 0;margin-top: 10px;">
                            @foreach($query as $query_key => $query_value)
                                @if($query_key >= $i * 3 && $query_key < ($i + 1) * 3)
                                    <label class="col-md-1 control-label">{{ $query_value['name'] }}</label>
                                    <div class="col-md-2">
                                        @if(!isset($query_value['type']) || $query_value['type'] == 'input')
                                            <input class="form-control" type="text" id="@if(strstr($query_value['column'], '.') !== FALSE){{ str_replace('.', '-', $query_value['column']) }}@else{{ $query_value['column'] }}@endif">
                                        @elseif($query_value['type'] == 'selector')
                                            <select class="form-control" id="{{ $query_value['column'] }}">
                                                <option value="">全部</option>
                                                @foreach(json_decode($query_selector_data[$query_key], true) as $selector_key => $selector_value)
                                                    <option value="{{ $selector_key }}">{{ $selector_value }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($query_value['type'] == 'checkbox')
                                            @foreach(json_decode($query_selector_data[$query_key], true) as $selector_key => $selector_value)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="checkbox style-0" name="{{ $query_value['column'] }}" value="{{ $selector_key }}">
                                                    <span>{{ $selector_value }}</span>
                                                </label>
                                            @endforeach
                                        @elseif($query_value['type'] == 'date')
                                            <div class="input-group">
                                                @if(isset($query_value['operator']) && $query_value['operator'] == 'between')
                                                    <input type="text" style="width:50%;" class="form-control date-format between" id="{{ $query_value['column'] . '_from' }}">
                                                    <input type="text" style="width:50%;" class="form-control date-format between" id="{{ $query_value['column'] . '_to' }}">
                                                @else
                                                    <input type="text" class="form-control date-format single" id="{{ $query_value['column'] }}">
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                </fieldset>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="reset">
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