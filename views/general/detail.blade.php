<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="widget-body smart-form  client-form">
                <fieldset>
                    @foreach($model->detail as $detail)
                    <section>
                        <label class="control-label">
                            <strong>{{ trans('fasts.' . $model->route . '.' . $detail['column']) }}</strong>
                            @if(strpos($detail['column'], '.') !== FALSE && is_array($value = explode('.', $detail['column'])))
                                @if(!isset($detail['type']))
                                    @if(isset($detail['param']))
                                        {{ is_array($data->$value[0]->$value[1]) ? implode(',', ${$detail['param']}[$data->$value[0]->$value[1]]) : ${$detail['param']}[$data->$value[0]->$value[1]] }}
                                    @else
                                        {{ is_array($data->$value[0]->$value[1]) ? implode(',', $data->$value[0]->$value[1]) : $data->$value[0]->$value[1]}}
                                    @endif
                                @elseif($detail['type'] == 'html')
                                    {!! $data->$value[0]->$value[1] !!}
                                @elseif($detail['type'] == 'image')
                                    @foreach(is_array($data->$value[0]->$value[1]) ? $data->$value[0]->$value[1] : [$data->$value[0]->$value[1]] as $image)
                                    <img width="200" height="200" src="{{ $image }}">
                                    @endforeach
                                @endif
                            @else
                                @if(!isset($detail['type']))
                                    @if(isset($detail['param']))
                                        {{ is_array($data->$detail['column']) ? implode(',', ${$detail['param']}[$data->$detail['column']]) : ${$detail['param']}[$data->$detail['column']] }}
                                    @else
                                        {{ is_array($data->$detail['column']) ? implode(',', $data->$detail['column']) : $data->$detail['column']}}
                                    @endif
                                @elseif($detail['type'] == 'html')
                                    {!! $data->$detail['column'] !!}
                                @elseif($detail['type'] == 'image')
                                    @foreach(is_array($data->$detail['column']) ? $data->$detail['column'] : [$data->$detail['column']] as $image)
                                        <img width="200" height="200" src="{{ $image }}">
                                    @endforeach
                                @endif
                            @endif
                        </label>
                    </section>
                    @endforeach
                </fieldset>
            </div>
        </article>
    </div>
</section>