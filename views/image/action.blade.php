<script>
    $(document).ready(function() {
        //上传图片
        $("#" + '{{ $image['column']}}').fileupload({
            url: "{{{ route('admin.blueimp.upload', array('file_name' => $image['column'])) }}}",
            add: function (e, data) {
                {{ $max = isset($image['max']) ? $image['max'] : 1 }}
                if ($('#' + '{{$image['column']}}' + ' .table tr').length >= parseInt('{{ $max }}')) {
                    $('#' + '{{$image['column']}}' + '_error').text('{{ sprintf('最多只允许上传%s张图片', $max) }}');
                } else {
                    $.blueimp.fileupload.prototype.options.add.call(this, e, data);
                }
            }
        });
        $('#' + '{{$image['column']}}' + ' .files').on('click', '.gallery', function (event) {
            if (blueimp.Gallery($('#' + '{{$image['column']}}' + ' .gallery'), {index: this})) {
                event.preventDefault();
            }
        });

        //加载图片
        @foreach(is_array($image_data) ? $image_data : [$image_data] as $data)
            $('#' + '{{$image['column']}}').addClass('fileupload-processing');
            $.ajax({
                url: "{{{ route('admin.blueimp.index', array('url' => $image_data)) }}}",
                dataType: 'json',
                context: $('#' + '{{$image['column']}}')
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done').call(this, null, {result: result});
            });
        @endforeach

        $('#submit_btn').on('click', function () {
            {{ $min = isset($image['min']) ? $image['min'] : 1 }}
            if ($('#' + '{{$image['column']}}' + ' tr.success').length < parseInt('{{ $min }}')) {
                alert('{{ sprintf('至少上传%s张图片', $min) }}');
                return false;
            }
        });
    });
</script>
<script id="template-download-{{$image['column']}}" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade success">
      <td width="80px">
        <span class="preview">
        {% if (file.thumbnailUrl) { %}<a href="{%=file.url%}" title="{%=file.name%}" class="gallery" download="{%=file.name%}"><img width="200" height="100" src="{%=file.thumbnailUrl%}"></a>{% } %}
        </span>
        <input type="hidden" value="{%=file.key%}" name="{{ isset($image['max']) && $image['max'] > 1 ? $image['column'] . '[]' : $image['column'] }}"/>
      </td>
      <td width="220px">
        <span class="size">{%=o.formatFileSize(file.size)%}</span>
        {% if (file.error) { %}<div><span class="label label-important">Error</span> {%=file.error%}</div>{% } %}
      </td>
      <td style="vertical-align:middle;text-align:left;">
        <button class="btn btn-danger delete">
          <i class="icon-trash icon-white"></i>
          <span>删除</span>
        </button>
      </td>
    </tr>
    {% } %}
        });
</script>