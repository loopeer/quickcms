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
                                <label class="control-label"><strong>{{ $column_names[$key] }}：</strong></label>
                                @if(! isset($column['type']))
                                    {{ $data->$column['key'] }}
                                @elseif($column['type'] == 'normal')
                                    {{ $data->$column['key'] }}
                                @elseif($column['type'] == 'rename')
                                    {!! $column['value'][$data->$column['key']] !!}
                                @elseif($column['type'] == 'amount')
                                    {{ $data->$column['key'] / 100 }}
                                @elseif($column['type'] == 'image')
                                    <p>
                                        <a href="{{ $data->$column['key'] }}" target="_blank" title="查看原图">
                                            <img src="{{ $data->$column['key'] }}" alt="{{ $data->$column['key'] }}" width="100">
                                        </a>
                                    </p>
                                @endif
                            </section>
                    @endforeach
                </fieldset>
            </div>
        </article>
    </div>
</section>