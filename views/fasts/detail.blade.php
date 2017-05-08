<div id="detail" class="form-horizontal">
    @foreach($model->detail as $detail)
        @if(isset($detail['column']))
            <div class="form-group">
                <label class="control-label col-sm-2"><strong>{{ trans('fasts.' . $model->route . '.' . $detail['column']) }}:</strong></label>
                <div class="col-sm-10">
                    @if(strpos($detail['column'], '.') !== FALSE && is_array($value = explode('.', $detail['column'])))
                        @if(!isset($detail['type']))
                            @if(isset($detail['param']))
                                {{ is_array($data->{$value[0]}->{$value[1]}) ? implode(',', ${$detail['param']}[$data->{$value[0]}->{$value[1]}]) : ${$detail['param']}[$data->{$value[0]}->{$value[1]}] }}
                            @else
                                {{ is_array($data->{$value[0]}->{$value[1]}) ? implode(',', $data->{$value[0]}->{$value[1]}) : $data->{$value[0]}->{$value[1]} }}
                            @endif
                        @elseif($detail['type'] == 'html')
                            {!! $data->{$value[0]}->{$value[1]} !!}
                        @elseif($detail['type'] == 'image')
                            @foreach(is_array($data->{$value[0]}->{$value[1]}) ? $data->{$value[0]}->{$value[1]} : [$data->{$value[0]}->{$value[1]}] as $image)
                                <img width="200" height="200" src="{{ $image }}">
                            @endforeach
                        @endif
                    @else
                        @if(!isset($detail['type']))
                            <span class="form-control">
                    @if(isset($detail['param']))
                                    {{ is_array($data->{$detail['column']}) ? implode(',', ${$detail['param']}[$data->{$detail['column']}]) : ${$detail['param']}[$data->{$detail['column']}] }}
                                @else
                                    {{ is_array($data->{$detail['column']}) ? implode(',', $data->{$detail['column']}) : $data->{$detail['column']} }}
                                @endif
                        </span>
                        @elseif($detail['type'] == 'html')
                            <div class="detail-html">{!! $data->{$detail['column']} !!}</div>
                        @elseif($detail['type'] == 'image')
                            @foreach(is_array($data->{$detail['column']}) ? $data->{$detail['column']} : [$data->{$detail['column']}] as $image)
                                <img width="200" height="200" src="{{ $image }}">
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @endforeach
</div>
