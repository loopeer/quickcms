@if(count($query = $queries) > 0)
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
            <form class="form-horizontal">
                <fieldset>
                    @for($qk = 0, $count = 0; $qk < count($queries); $qk++)
                        @if(isset($queries[$qk]['query']) && $count++ >= 0)
                            @if($count == 1 || $count == 4 || $count == 7)
                            <div class="form-group" style="margin-bottom: 0;margin-top: 10px;">
                            @endif
                                <label class="col-md-1 control-label">{{ trans('fasts.' . $model->route . '.' . $queries[$qk]['column']) }}</label>
                                <div class="col-md-2">
                                    @if(!isset($queries[$qk]['type']) || $queries[$qk]['type'] == 'input')
                                        <input class="form-control" type="text" id="@if(strstr($queries[$qk]['column'], '.') !== FALSE){{ str_replace('.', '-', $queries[$qk]['column']) }}@else{{ $queries[$qk]['column'] }}@endif">
                                    @elseif($queries[$qk]['type'] == 'select')
                                        <select class="form-control" id="{{ $queries[$qk]['column'] }}">
                                            <option value="">全部</option>
                                            @foreach(${$queries[$qk]['param']} as $sk => $sv)
                                                <option value="{{ $sk }}">{{ $sv }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($queries[$qk]['type'] == 'checkbox')
                                        @foreach(${$queries[$qk]['param']} as $sk => $sv)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="checkbox style-0" name="{{ $queries[$qk]['column'] }}" value="{{ $sk }}">
                                                <span>{{ $sv }}</span>
                                            </label>
                                        @endforeach
                                    @elseif($queries[$qk]['type'] == 'date' || $queries[$qk]['type'] == 'datetime')
                                        <div class="input-group date">
                                            @if($queries[$qk]['query'] == 'between')
                                                <input class="form-control {{ $queries[$qk]['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                       data-date-format="{{ $queries[$qk]['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                       style="width: 50%;" type="text" id="{{ $queries[$qk]['column'] . '_from' }}">
                                                <input class="form-control {{ $queries[$qk]['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                       data-date-format="{{ $queries[$qk]['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                       style="width: 50%;" type="text" id="{{ $queries[$qk]['column'] . '_to' }}">
                                            @else
                                                <input class="form-control {{ $queries[$qk]['type'] == 'date' ? 'form_date' : 'form_datetime' }}"
                                                       data-date-format="{{ $queries[$qk]['type'] == 'date' ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii' }}"
                                                       style="width: 50%;" type="text" id="{{ $queries[$qk]['column'] }}">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @if($count == 3 || $count == 6 || $count == 9 || $count == $query)
                            </div>
                            @endif
                        @endif
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