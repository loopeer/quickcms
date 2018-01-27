<script>
    $(document).ready(function() {
        //上传音频
        $("#" + '{{ $voice['column']}}').fileupload({
            url: "{{{ route('admin.blueimp.uploadVoice', array('file_name' => $voice['column'])) }}}",
            add: function (e, data) {
                {{ $max = isset($voice['max']) ? $voice['max'] : 1 }}
                if ($('#' + '{{$voice['column']}}' + ' .table tr').length >= parseInt('{{ $max }}')) {
                    $('#' + '{{$voice['column']}}' + '_error').text('{{ sprintf('最多只允许上传%s个音频', $max) }}');
                } else {
                    $.blueimp.fileupload.prototype.options.add.call(this, e, data);
                }
            }
        });
        $('#' + '{{$voice['column']}}' + ' .files').on('click', '.gallery', function (event) {
            if (blueimp.Gallery($('#' + '{{$voice['column']}}' + ' .gallery'), {index: this})) {
                event.preventDefault();
            }
        });

        //加载音频
        @foreach(is_array($voice_data) ? $voice_data : [$voice_data] as $data)
            $('#' + '{{$voice['column']}}').addClass('fileupload-processing');
        $.ajax({
            url: "{{{ route('admin.blueimp.index', array('url' => $data)) }}}",
            dataType: 'json',
            context: $('#' + '{{$voice['column']}}')
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done').call(this, null, {result: result});
        });
        @endforeach

        $('#submit_btn').on('click', function () {
            {{ $min = isset($voice['min']) ? $voice['min'] : 1 }}
            if ($('#' + '{{$voice['column']}}' + ' tr.success').length < parseInt('{{ $min }}')) {
                alert('{{ sprintf('至少上传%s个音频', $min) }}');
                return false;
            }
        });
    });
</script>
<script id="template-download-{{$voice['column']}}" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade success">
      <td width="80px">
        <span class="preview" width ="100px" height='20px'>
        {% if (file.url) { %}<audio  src="{%=file.url%}" controls="controls" ></audio>{% if(file.duration) { %}<span>语音时长为{%=file.duration%}毫秒</span>{% } %}{% } %}
        </span>
        <input type="hidden" value="{%=file.key%}" name="{{ isset($voice['max']) && $voice['max'] > 1 ? $voice['column'] . '[]' : $voice['column'] }}"/>
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