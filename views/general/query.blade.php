@if(count($model->query) > 0)
<div class="jarviswidget jarviswidget-sortable jarviswidget-color-darken" id="wid-id-0" data-widget-hidden="false" data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-editbutton="false"
     data-widget-colorbutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-search"></i> </span>
        <h2>查询</h2>
    </header>
    <div role="content">
        <div class="jarviswidget-editbox">
        </div>
        <div class="widget-body">
            <form class="form-horizontal">
                <fieldset>
                    @for($i = 0; $i < count($model->query) / 3; $i++)
                        <div class="form-group" style="margin-bottom: 0;margin-top: 10px;">
                            @foreach($model->query as $qk => $qv)
                                @if($qk >= $i * 3 && $qk < ($i + 1) * 3)
                                    <label class="col-md-1 control-label">{{ $qv['name'] }}</label>
                                    <div class="col-md-2">
                                        @if(!isset($qv['type']) || $qv['type'] == 'input')
                                            <input class="form-control" type="text" id="@if(strstr($qv['column'], '.') !== FALSE){{ str_replace('.', '-', $qv['column']) }}@else{{ $qv['column'] }}@endif">
                                        @elseif($qv['type'] == 'selector')
                                            <select class="form-control" id="{{ $qv['column'] }}">
                                                <option value="">全部</option>
                                                @foreach(${$qv['param']} as $sk => $sv)
                                                    <option value="{{ $sk }}">{{ $sv }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($qv['type'] == 'checkbox')
                                            @foreach(${$qv['param']} as $sk => $sv)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="checkbox style-0" name="{{ $qv['column'] }}" value="{{ $sk }}">
                                                    <span>{{ $sv }}</span>
                                                </label>
                                            @endforeach
                                        @elseif($qv['type'] == 'date')
                                            <div class="input-group">
                                                @if(isset($qv['operator']) && $qv['operator'] == 'between')
                                                    <input type="text" style="width:50%;" class="form-control date-format between" id="{{ $qv['column'] . '_from' }}">
                                                    <input type="text" style="width:50%;" class="form-control date-format between" id="{{ $qv['column'] . '_to' }}">
                                                @else
                                                    <input type="text" class="form-control date-format single" id="{{ $qv['column'] }}">
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