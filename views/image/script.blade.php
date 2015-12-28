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