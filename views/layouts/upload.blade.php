<link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/jquery.fileupload-ui.css') }}}">
<link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/blueimp-gallery.min.css') }}}">
<script src="{{ asset('loopeer/quickcms/js/blueimp/tmpl.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/load-image.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/canvas-to-blob.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/bootstrap-image-gallery.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-process.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-image.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-validate.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/blueimp/jquery.fileupload-ui.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/select2/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        pageSetUp();
        // image
        $('#image').fileupload({
            url: "{{{ route('admin.blueimp.upload', array('file_name'=>'image')) }}}",
            add: function (e, data) {
                if($("#image .table tr").length >= max_image_count){
                    $("#image_error").text("只允许上传" + max_image_count + "张图片");
                }else{
                    $.blueimp.fileupload.prototype.options.add.call(this, e, data);
                }
            }
        });
        $('#image .files').on('click', '.gallery', function (event) { if (blueimp.Gallery($('#image .gallery'), { index: this })) { event.preventDefault(); } });
        var image = images.split(',');
        for(var i = 0; i < image.length; i ++) {
            $('#image').addClass('fileupload-processing');
            $.ajax({
                url: '/admin/blueimp',
                data: {"url" : image[i]},
                dataType: 'json',
                context: $('#image')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done').call(this, null, {result: result});
            });
        }
        $('#submit_btn').on('click',function(){
            if($("#image .table tr.success").length != min_image_count){
                alert("请上传" + min_image_count + "张图片");
                return false;
            }
        });
    });
</script>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<tr class="template-upload fade">
<td width="80px"><span class="preview"></span></td>
<td width="220px">
<p class="size">{%=o.formatFileSize(file.size)%}</p>
{% if (!o.files.error) { %}<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>{% } %}
{% if (file.error) { %} <div><span class="label label-important">Error</span> {%=file.error%}</div>{% } %}
</td>
<td style="vertical-align:middle;text-align:left;">
{% if (!o.files.error && !i && !o.options.autoUpload) { %}<button class="btn btn-primary start marginR10"><i class="icon-upload icon-white"></i><span>开始</span></button>{% } %}
{% if (!i) { %}<button class="btn btn-warning cancel"><i class="icon-ban-circle icon-white"></i><span>取消</span></button>{% } %}
</td>
</tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download-image" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<tr class="template-download fade success">
<td width="80px">
<span class="preview">
{% if (file.thumbnailUrl) { %}<a href="{%=file.url%}" title="{%=file.name%}" class="gallery" download="{%=file.name%}"><img src="{%=file.thumbnailUrl%}"></a>{% } %}
</span>
<input type="hidden" value="{%=file.key%}" name="image[]" id="image"/>
</td>
<td width="220px">
<span class="size">{%=o.formatFileSize(file.size)%}</span>
{% if (file.error) { %}<div><span class="label label-important">Error</span> {%=file.error%}</div>{% } %}
</td>
<td style="vertical-align:middle;text-align:left;">
<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
  <i class="icon-trash icon-white"></i>
  <span>删除</span>
</button>
</td>
</tr>
{% } %}
</script>
