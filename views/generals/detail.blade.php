<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <!-- widget div-->
            <!-- widget content -->
            <div class="widget-body smart-form  client-form">
                <fieldset>
                    <style>
                        .status {
                            display: inline;
                            padding: .2em .6em .3em;
                            font-size: 75%;
                            font-weight: 700;
                            line-height: 1;
                            color: #fff;
                            text-align: center;
                            white-space: nowrap;
                            vertical-align: baseline;
                            border-radius: .25em;
                        }
                    </style>
                    @foreach($columns as $key => $column)
                            <section>
                                <label class="control-label"><strong>{{ $detail_column_name ? $detail_column_name[$key] : $column_names[$column] }}：</strong></label>
                                @if(in_array($column, $rename_keys))
                                    @if($renames[$column]['type'] == 'amount')
                                        {{ $data->$column / 100 }}
                                    @elseif($renames[$column]['type'] == 'normal')
                                        {!! $renames[$column]['param'][$data->$column] !!}
                                    @elseif($renames[$column]['type'] == 'image')
                                        @if(is_array($data->$column))
                                            <p>
                                                @foreach($data->$column as $image)
                                                    <a href="{{ $image }}" target="_blank" title="查看原图">
                                                        <img src="{{ $image }}" alt="{{ $image }}" width="100">
                                                    </a>
                                                @endforeach
                                            </p>
                                        @else
                                            <p>
                                                <a href="{{ $data->$column }}" target="_blank" title="查看原图">
                                                    <img src="{{ $data->$column }}" alt="{{ $data->$column }}" width="100">
                                                </a>
                                            </p>
                                        @endif
                                    @elseif($renames[$column]['type'] == 'selector')
                                        {{ $selector_data[$column][$data->$column] }}
                                    @elseif($renames[$column]['type'] == 'date')
                                        {{ date($renames[$column]['format'], strtotime($data->$column)) }}
                                    @elseif($renames[$column]['type'] == 'html')
                                        {!! $data->$column !!}
                                    @elseif($renames[$column]['type'] == 'language')
                                        <label style="vertical-align: text-top;">
                                        @foreach($language_resource_data as $lang_res_data_key => $lang_res_data_value)
                                        {{ $lang_res_data_value->value }}<br>
                                        @endforeach
                                        </label>
                                    @endif
                                @else
                                    {{ $data->$column }}
                                @endif

                            </section>
                    @endforeach
                </fieldset>
            </div>
        </article>
    </div>
</section>