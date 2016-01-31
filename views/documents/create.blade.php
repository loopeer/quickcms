@extends('backend::layouts.master')

@section('content')
        <!-- MAIN CONTENT -->
<div id="content">
    <section id="widget-grid" class="">
        <div class="row">
            <article class="col-sm-12 col-md-12 col-lg-10 sortable-grid ui-sortable">
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                    <header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div><div class="widget-toolbar" role="menu"><a data-toggle="dropdown" class="dropdown-toggle color-box article" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
                        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                        <h2>枚举管理 </h2>
                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                    <div role="content">
                        <div class="jarviswidget-editbox">
                        </div>
                        <div class="widget-body no-padding">
                            <form action="/admin/document" method="post" id="smart-form-register" class="smart-form" novalidate="novalidate">
                                {!! csrf_field() !!}
                                <header>
                                    添加新文章
                                </header>
                                @include('UEditor::head')
                                <style>
                                    .btn {
                                        display: inline-block;
                                        margin-bottom: 0;
                                        font-weight: 400;
                                        text-align: center;
                                        vertical-align: middle;
                                        touch-action: manipulation;
                                        cursor: pointer;
                                        background-logo: none;
                                        border: 1px solid transparent;
                                        white-space: nowrap;
                                        padding: 6px 12px;
                                        font-size: 13px;
                                        line-height: 1.42857143;
                                        border-radius: 2px;
                                        -webkit-user-select: none;
                                        -moz-user-select: none;
                                        -ms-user-select: none;
                                        user-select: none;
                                    }
                                </style>
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <label class="label">文档key</label>
                                            @if ($document->id)
                                                <input type="hidden" name="id" value="{{$document->id}}">
                                            @endif
                                            <input type="text" name="document_key" value="{{$document->document_key}}">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <label class="label">文档标题</label>
                                            <input type="text" name="title" value="{{$document->title}}">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <label class="label">文档内容</label>
                                            <script id="container" name="document_content" type="text/plain">{!! $document->document_content !!}</script>
                                            <!-- 实例化编辑器 -->
                                            <script type="text/javascript">
                                                var ue = UE.getEditor('container',{
                                                    autoHeightEnabled: true,
                                                    lang:"zh-cn",
                                                    autoFloatEnabled: true,
                                                    initialFrameHeight: 500
                                                });
                                                ue.ready(function() {
                                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                                                    //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                                });
                                            </script>
                                        </label>
                                    </section>
                                </fieldset>
                                <footer>
                                    <button type="submit" class="btn btn-primary">
                                        保存
                                    </button>
                                    <a href="/admin/document" class="btn btn-primary">
                                        返回
                                    </a>
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
            pageSetUp();
            $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    title : {
                        required : true
                    }, document_key : {
                        required : true
                    },document_content:{
                        required: true
                    }
                },
                // Messages for form validation
                messages : {
                    title : {
                        required : '请输入标题'
                    },document_key : {
                        required : '请输入 key'
                    },document_content:{
                        required: '请输入文章内容'
                    }
                },
                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    </script>
@endsection
