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
                            <h2>
                                {{ $model->module . (isset($data->id) ? '编辑' : '新增') }}
                            </h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/{{ $model->route }}" method="post" id="create-form" class="smart-form client-form">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    @foreach($model->createHidden as $hidden)
                                        @if(!isset($hidden['action']) || ($hidden['action'] == 'create' && !isset($data->id)) || ($hidden['action'] == 'edit' && isset($data->id)))
                                            <input type="hidden" name="{{ $hidden['column'] }}" value="{{ $hidden['value'] == 'admin' ? Auth::admin()->get()->email : $hidden['value'] }}">
                                        @endif
                                    @endforeach
                                    <fieldset>
                                    @foreach($model->create as $item)
                                        <section>
                                            <label class="label">{{ trans('fasts.' . $model->route . '.' . $item['column']) }}</label>
                                            @if(!isset($item['type']) || $item['type'] == 'text')
                                                <label class="input">
                                                    <input type="text" name="{{ $item['column'] }}" value="{{ old($item['column']) ?: $data->{$item['column']} }}">
                                                </label>
                                            @elseif($item['type'] == 'textarea')
                                                <label class="textarea textarea-resizable">
                                                    <textarea rows="3" class="custom-scroll" name="{{ $item['column'] }}">{{ old($item['column']) ?: $data->{$item['column']} }}</textarea>
                                                </label>
                                            @elseif($item['type'] == 'password')
                                                <label class="input">
                                                    <input type="password" name="{{ $item['column'] }}" value="{{ old($item['column']) ?: $data->{$item['column']} }}" @if(isset($item['disabled']) && isset($data->id))) disabled @endif>
                                                </label>
                                            @elseif($item['type'] == 'select')
                                                <label class="select">
                                                    <select name="{{ $item['column'] }}">
                                                    @foreach(${$item['param']} as $sk => $sv)
                                                        @if($sk == old($item['column']))
                                                            <option value="{{ $sk }}" selected>{{ $sv }}</option>
                                                        @else
                                                            @if(strstr($item['column'], '.') !== FALSE)
                                                            <option value="{{ $sk }}" {{ $sk == ($data->{explode('.', $item['column'])[0]} instanceof \Illuminate\Database\Eloquent\Collection ?
                                                            $data->{explode('.', $item['column'])[0]}->first()[explode('.', $item['column'])[1]] :
                                                            $data->{explode('.', $item['column'])[0]}->{explode('.', $item['column'])[1]}) ? 'selected' : '' }}>{{ $sv }}</option>
                                                            @else
                                                            <option value="{{ $sk }}" {{ $sk == $data->$item['column'] ? 'selected' : '' }}>{{ $sv }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    </select><i></i>
                                                </label>
                                            @elseif($item['type'] == 'checkbox')
                                                <div class="inline-group">
                                                    @foreach(is_array($item['param']) ? $item['param'] : ${$item['param']} as $cbk => $cbv)
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="{{ $item['column'] }}[]" value="{{ $cbk }}" {{ in_array($cbk, $data->{$item['relation']['foreign_key']}) ? 'checked' : '' }}>
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
                                            @elseif($item['type'] == 'date' || $item['type'] == 'datetime' || $item['type'] == 'time')
                                                @if($item['type'] == 'date')
                                                    <div class="input-group date form_date" data-date-format="yyyy-mm-dd">
                                                @elseif($item['type'] == 'datetime')
                                                    <div class="input-group date form_datetime" data-date-format="yyyy-mm-dd hh:ii">
                                                @else
                                                    <div class="input-group date form_time" data-date-format="hh:ii">
                                                @endif
                                                    <input class="form-control" size="16" type="text" name="{{ $item['column'] }}" value="{{ old($item['column']) ?: $data->$item['column'] }}" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            @elseif($item['type'] == 'editor')
                                                <script id="{{ $item['column'] }}-container" name="{{ $item['column'] }}" type="text/plain">{!! old($item['column']) ?: $data->$item['column'] !!}</script>
                                                <script type="text/javascript">
                                                    var ue = UE.getEditor("{{ $item['column'] }}-container", {
                                                        initialFrameHeight: '{{ isset($item['param']['initialFrameHeight']) ? $item['param']['initialFrameHeight'] : 320 }}'
                                                    });
                                                </script>
                                            @elseif($item['type'] == 'image')
                                                @include('backend::image.upload', ['image' => $item, 'image_name' => $item['column']])
                                            @elseif($item['type'] == 'map')
                                                   <label class="input">
                                                       <input type="text" id="{{ $item['column'] }}" name="{{ $item['column'] }}" value="{{ old($item['column']) ?: $data->{$item['column']} }}" readonly>
                                                   </label>
                                            @endif

                                            @if(isset($item['note']))
                                                <div class="note">{{ $item['note'] }}</div>
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

                @if(in_array('map', $types))
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="jarviswidget" id="wid-id-4" data-widget-hidden="false" data-widget-togglebutton="false"
                         data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
                            <h2>百度地图</h2>
                        </header>
                        <div>
                            <style>
                                .BMapLabel{
                                    max-width: none;
                                }
                            </style>
                            <div class="smart-form">
                                <fieldset style="padding: 25px 14px 25px;">
                                    <div class="row">
                                        <section class="col col-5">
                                            <label class="input"> <i class="icon-append fa fa-home"></i>
                                                <input type="text" name="map-address" id="map-address" placeholder="请输入要查询的地址">
                                            </label>
                                        </section>
                                        <section class="col col-5">
                                            <label class="input"> <i class="icon-append fa fa-map-marker"></i>
                                                <input type="text" name="map-result" id="map-result" placeholder="查询结果(经纬度)" readonly>
                                            </label>
                                        </section>
                                        <div class="col col-2">
                                            <button id="map-search" class="btn btn-primary" style="margin: inherit;padding: 6px 12px;">
                                                查询
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div>
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->

                                </div>
                                <div class="widget-body no-padding">

                                    <div id="container" class="google_maps" style="height: 600px"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </article>
                @endif
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

    @if(in_array('map', $types))
        <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=pwlLr6dpGrWK9PNjzFHzur3V"></script>

        <script>
            var map = new BMap.Map("container");

            @if(isset($data->id))
            var longitude = '116.403963';
            var latitude = '39.915119';

             @foreach($model->create as $itemColumn)
                @if(isset($itemColumn['type']) && $itemColumn['type'] == 'map')
                 @if($itemColumn['param'] == 'all' && !empty($data->{$itemColumn['column']}))

                 longitude = '{{ explode(',', $data->{$itemColumn['column']})[0] }}';
                 latitude = '{{ explode(',', $data->{$itemColumn['column']})[1] }}';

                 @elseif($itemColumn['param'] == 'longitude' && !empty($data->{$itemColumn['column']}))
                     longitude = "{{ $data->{$itemColumn['column']} }}";
                 @elseif($itemColumn['param'] == 'latitude' && !empty($data->{$itemColumn['column']}))
                     latitude = "{{ $data->{$itemColumn['column']} }}";
                 @endif
                @endif
             @endforeach

            var default_point = new BMap.Point(longitude,latitude);
            var default_label = new BMap.Label(longitude + "," + latitude,{offset:new BMap.Size(20,-10)});
             map.centerAndZoom(default_point, 15);
            addMarker(default_point,default_label);
            $("#map-result").val(longitude + "," +  latitude);
            @else
            map.centerAndZoom("北京", 12);
            @endif


            map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
            map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
            map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
            map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
            map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开
            var localSearch = new BMap.LocalSearch(map);
            localSearch.enableAutoViewport(); //允许自动调节窗体大小

            map.addEventListener("click",function(e){
                // alert(e.point.lng + "," + e.point.lat);
                var allOverlay = map.getOverlays();
                if (allOverlay.length > 0) {
                    map.clearOverlays();
                }
                var marker = new BMap.Marker(new BMap.Point(e.point.lng, e.point.lat));
                map.addOverlay(marker);//增加点
                var point = new BMap.Point(e.point.lng, e.point.lat);
                var label = new BMap.Label(e.point.lng+","+ e.point.lat,{offset:new BMap.Size(20,-10)});
                addMarker(point,label);
                $("#map-result").val(e.point.lng + "," +  e.point.lat);
                @foreach($model->create as $itemColumn)
                @if(isset($itemColumn['type']) && $itemColumn['type'] == 'map')
                    @if($itemColumn['param'] == 'all')
                    $("#{{ $itemColumn['column'] }}").val(e.point.lng + "," +  e.point.lat);
                @elseif($itemColumn['param'] == 'longitude')
                    $("#{{ $itemColumn['column'] }}").val(e.point.lng);
                @elseif($itemColumn['param'] == 'latitude')
                    $("#{{ $itemColumn['column'] }}").val(e.point.lat);
                @endif
                @endif
                @endforeach
            });

            function addMarker(point,label){
                var marker = new BMap.Marker(point);
                map.addOverlay(marker);
                marker.setLabel(label);
            }

            $('#map-search').on('click', function() {
                map.clearOverlays();//清空原来的标注
                var keyword = $('#map-address').val();
                localSearch.setSearchCompleteCallback(function (searchResult) {
                    var poi = searchResult.getPoi(0);
                    $('#map-result').val(poi.point.lng + "," + poi.point.lat);
                    map.centerAndZoom(poi.point, 13);
                    var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
                    map.addOverlay(marker);
                    var content = $('#map-address').val() + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
                    var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
                    marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
                    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
                    $("#map-result").val(poi.point.lng + "," +  poi.point.lat);
                    @foreach($model->create as $itemColumn)
                   @if(isset($itemColumn['type']) && $itemColumn['type'] == 'map')
                       @if($itemColumn['param'] == 'all')
                       $("#{{ $itemColumn['column'] }}").val(poi.point.lng + "," +  poi.point.lat);
                    @elseif($itemColumn['param'] == 'longitude')
                        $("#{{ $itemColumn['column'] }}").val(poi.point.lng);
                    @elseif($itemColumn['param'] == 'latitude')
                        $("#{{ $itemColumn['column'] }}").val(poi.point.lat);
                    @endif
                    @endif
                    @endforeach
                });
                localSearch.search(keyword);
            });

        </script>
    @endif

    <script>
        $(document).ready(function() {

            pageSetUp();

            $('#create-form').validate({
                // Rules for form validation
                rules: {
                    @foreach($model->create as $ruleItem)
                        @if(isset($ruleItem['rules']))
                        {{ $ruleItem['column'] }}: {
                            @foreach($ruleItem['rules'] as $ruleKey => $ruleValue)
                            {{ $ruleKey . ':' . $ruleValue }},
                            @endforeach
                        },
                        @endif
                    @endforeach
                },

                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                },
                submitHandler: function(form) {
                    $('#submit_btn').attr('disabled', true);
                    form.submit();
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