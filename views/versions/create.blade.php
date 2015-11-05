@extends('backend::layouts.master')

@section('content')
    <!-- MAIN CONTENT -->
<div id="content">
    <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                    <header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div><div class="widget-toolbar" role="menu"><a data-toggle="dropdown" class="dropdown-toggle color-box selector" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
                        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                        <h2>版本管理 </h2>
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                    <div role="content">
                        <div class="jarviswidget-editbox">
                        </div>
                        <div class="widget-body no-padding">
                            <form action="{{route('admin.versions.store')}}" method="post" id="smart-form-register" class="smart-form" novalidate="novalidate">
                                {!! csrf_field() !!}
                                <header>
                                    添加新版本
                                </header>
                                <fieldset>
                                    <section>
                                        <label class="input">
                                        @if ($version->id)
                                            <input type="hidden" name="id" value="{{$version->id}}" placeholder="">
                                        @endif
                                            <input type="text" name="platform" value="{{$version->platform}}" placeholder="发表平台">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <input type="text" name="version_code" value="{{$version->version_code}}" placeholder="版本号" id="password">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <input type="text" name="version" value="{{$version->version}}" placeholder="版本名称">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <input type="text" name="url" value="{{$version->url}}" placeholder="下载地址">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <input type="text" name="message" value="{{$version->message}}" placeholder="提示信息">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <input type="text" name="description" value="{{$version->description}}" placeholder="版本描述">
                                        </label>
                                    </section>

                                </fieldset>
                                <footer>
                                    <a href="/admin/versions/" class="btn btn-primary">
                                        返回
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        修改
                                    </button>
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
<script>
    $(document).ready(function() {
        $('#dt_basic').dataTable({
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": "/admin/users/search",
                "data": function ( d ) {
                    //d.myKey = "myValue";
                    // d.custom = $('#myInput').val();
                    // etc
                }
            }
        });
    });
</script>
@endsection
