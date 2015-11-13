@extends('backend::layouts.master')

@section('content')
    <!-- MAIN CONTENT -->
    <div id="message"></div>

<style type="text/css">
.demo{width:620px; margin:30px auto}
.demo p{line-height:32px}
.btn{position: relative;overflow: hidden;margin-right: 4px;display:inline-block;*display:inline;padding:4px 10px 4px;font-size:14px;line-height:18px;*line-height:20px;color:#fff;text-align:center;vertical-align:middle;cursor:pointer;-moz-border-radius:4px;border-radius:4px;}
.btn input {position: absolute;top: 0; right: 0;margin: 0;border: solid transparent;opacity: 0;filter:alpha(opacity=0); cursor: pointer;}
.progress { position:relative; margin-left:100px; margin-top:-24px; width:200px;padding: 1px; border-radius:3px; display:none}
.bar {background-color: green; display:block; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; height:20px; display:inline-block; top:3px; left:2%; color:#fff }
.files{height:22px; line-height:22px; margin:10px 0}
</style>

<div id="content">
    <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
                    <header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div><div class="widget-toolbar" role="menu"><a data-toggle="dropdown" class="dropdown-toggle color-box selector" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
                        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                        <h2>系统设置 </h2>
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                    <div role="content">
                        <div class="jarviswidget-editbox">
                        </div>
                        <div class="widget-body no-padding">
                            <form action="" method="post" enctype="multipart/form-data" name="system-form" id="smart-form-register" class="smart-form" novalidate="novalidate">
                                <input type="hidden" name="_token" value="WItqrJREFNmMU1dwOPiDbvZUfCV6cRUmviOPpNAs">
                                <header>
                                    编辑系统配置
                                </header>
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <input type="text" id="title" value="{{Cache::get('websiteTitle')}}" placeholder="输入网站标题">
                                            <b class="tooltip tooltip-bottom-right">显示在浏览器标签</b>
                                        </label>
                                    </section>
                                    <section>
                                            <div class="logo">
                                                    <p>说明：只允许上传png格式的图片，图片大小不能超过500k。</p>
                                                    <div class="btn btn-primary">
                                                     <span>修改Logo</span>
                                                     <input type="file" id="file_upload" name="my_pic">
                                                 </div>
                                                 <div class="progress">
                                                    <span class="bar"></span><span class="percent">0%</span >
                                                </div>
                                                 <div class="files"></div>
                                                 <div id="show_img"></div>
                                            </div>
                                    </section>
                                    <input type="hidden" name="_token" id="sys-token" value="{{csrf_token()}}">
                                </fieldset>
                                <footer>
                                    <button type="button" id="submit-title" class="btn btn-primary">
                                        保存
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
<script type="text/javascript" src="{{asset('/loopeer/quickcms/js/plugin/jquery-form/jquery-form.min.js')}}"></script>
<script>
    $(document).ready(function() {

        $(function () {
            $("#file_upload").change(function(){
                var formData = new FormData();
                var files = document.getElementById('file_upload').files;
                formData.append('logo',files[0]);
                formData.append('_token','{{csrf_token()}}');
                $.ajax({
                    url:'/admin/systems/uploadLogo/',
                    data:formData,
                    type:'post',
                    contentType:false,
                    processData: false,
                    success: function(data) {
                        switch (data) {
                            case '0':
                                $('#message').html('<article class="col-sm-12" id="tips"><div class="alert alert-success fade in"><button class="close" data-dismiss="alert">×</button> <i class="fa-fw fa fa-times"></i> <strong>成功</strong> 文件不合法 </div> </article>');break;
                            case '1':
                                $('#message').html('<article class="col-sm-12" id="tips"><div class="alert alert-success fade in"><button class="close" data-dismiss="alert">×</button> <i class="fa-fw fa fa-times"></i> <strong>成功</strong> Logo修改成功 </div> </article>');
                                document.getElementById('logo-img').src = '{{asset('/loopeer/quickcms/img/')}}/logo.png?n='+ Math.random();
                            break;
                            case '2': $('#message').html('<article class="col-sm-12" id="tips"><div class="alert alert-danger fade in"><button class="close" data-dismiss="alert">×</button> <i class="fa-fw fa fa-times"></i> <strong>失败</strong> 图片格式不正确 </div> </article>');break;
                        }
                    }
                });
        	});
        });

        $('#submit-title').click(function() {
            var title = $('#title').val();
            $.ajax({
                url:'/admin/systems/title',
                data:{
                    'title':title,
                    '_token':'{{csrf_token()}}'
                },
                type:'post',
                success:function (data) {
                    if (data == 1) {
                        $('#message').html('<article class="col-sm-12" id="tips"><div class="alert alert-success fade in"><button class="close" data-dismiss="alert">×</button> <i class="fa-fw fa fa-check"></i> <strong>成功</strong> 修改成功，刷新页面后生效 </div> </article>');
                    }
                }
            })
        });
    });


</script>
@endsection
