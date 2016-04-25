<script src="{{ asset('loopeer/quickcms/js/plugin/dropzone/dropzone.min.js') }}"></script>
<script>
    Dropzone.autoDiscover = false;
    var {{ $dropzone_name }} = new Dropzone("#{{ $dropzone_id }}", {
        paramName: "file",
        url: "/admin/dropzone/upload",
        addRemoveLinks : true,
        maxFilesize: {{ $max_size }},
        dictResponseError: 'Error uploading file!',
        maxFiles : parseInt('{{ $max_count }}'),
        success : function(file, response) {
            var count = $('.dz-preview').length;
            file.previewElement.id = response.key;

            if (count > parseInt('{{ $max_count }}')) {
                $("#" + response.key).find('.dz-progress').remove();
                $("#" + response.key).addClass('dz-error');
                $("#" + response.key).find('.dz-error-message').text('You can not upload any more files.');
                return false;
            }
            if(response.result) {
                $(".dz-progress").remove();
                $('.dz-preview').addClass('dz-success');
                $("#" + response.key).append('<input type="hidden" name="{{ $file_name }}[]" value="' + response.key + '">');
            } else {
                alert(response.msg);
            }

        }
    });

    @if(isset($model->id) && !empty($model->$column))
    //编辑时回显已存在的文件
    $.getJSON('/admin/dropzone/fileList/' + '{{ $model->id }}', {table: "{{ $model->getTable() }}", column: "{{ $column }}"}, function(data) {
        console.log(data);
        $.each(data, function(index, val) {
            var mockFile = { name: val.name, size: val.size, key: val.key, url: val.url, mimeType: val.mime_type };
            //编辑时回显已存在的文件
            {{ $dropzone_name }}.emit("addedfile", mockFile);
            if(/.(gif|jpg|jpeg|bmp|png)$/.test(mockFile.mimeType)) {
                // And optionally show the thumbnail of the file:
                {{ $dropzone_name }}.emit("thumbnail", mockFile, mockFile.url);
            }
            {{ $dropzone_name }}.files.push(mockFile);
            $(mockFile.previewElement).prop('id', mockFile.key);
            $("#" + mockFile.key).append('<input type="hidden" name="test[]" value="' + mockFile.key + '">');
        });
    });
    @endif
</script>