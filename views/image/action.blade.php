<script>
    $(document).ready(function() {
        //上传图片
        $('#'+'{{$image['name']}}').fileupload({
            url: "{{{ route('admin.blueimp.upload', array('file_name' => $image['name'])) }}}",
            add: function (e, data) {
                if($('#'+'{{$image['name']}}'+' .table tr').length >= parseInt('{{$image['max_count']}}')) {
                    $('#'+'{{$image['name']}}'+'_error').text('{{sprintf($image['max_error_msg'], $image['max_count'])}}');
                } else {
                    $.blueimp.fileupload.prototype.options.add.call(this, e, data);
                }
            }
        });
        $('#'+'{{$image['name']}}'+' .files').on('click', '.gallery', function (event) { if (blueimp.Gallery($('#'+'{{$image['name']}}'+' .gallery'), { index: this })) { event.preventDefault(); } });
    });
    //加载图片
    @if($image['editable'] && !empty($image_data))
    @if($image['max_count'] <= 1)
    $('#'+'{{$image['name']}}').addClass('fileupload-processing');
    $.ajax({
        url: "{{{ route('admin.blueimp.index', array('url' =>$image_data)) }}}",
        dataType: 'json',
        context: $('#'+'{{$image['name']}}')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done').call(this, null, {result: result});
    });
    @else
    @foreach($image_data as $data)
    $('#'+'{{$image['name']}}').addClass('fileupload-processing');
    $.ajax({
        url: "{{{ route('admin.blueimp.index', array('url' => $data)) }}}",
        dataType: 'json',
        context: $('#'+'{{$image['name']}}')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done').call(this, null, {result: result});
    });
    @endforeach
@endif
@endif

$('#submit_btn').on('click',function(){
        if($('#'+'{{$image['name']}}'+' tr.success').length < parseInt('{{$image['min_count']}}')){
            alert('{{sprintf($image['min_error_msg'], $image['min_count'])}}');
            return false;
        }
    });
</script>
<script id="template-download-{{$image['name']}}" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<tr class="template-download fade success">
  <td width="80px">
    <span class="preview">
    {% if (file.thumbnailUrl) { %}<a href="{%=file.url%}" title="{%=file.name%}" class="gallery" download="{%=file.name%}"><img src="{%=file.thumbnailUrl%}"></a>{% } %}
    </span>
    <input type="hidden" value="{%=file.key%}" name="{{$image['name']}}[]"/>
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