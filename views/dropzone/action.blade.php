<script>
    Dropzone.autoDiscover = false;
    var {{ isset($dropzone_name) ? $dropzone_name : 'myDropzone' }} = new Dropzone("#{{ isset($dropzone_id) ? $dropzone_id : 'mydropzone' }}", {
        paramName: "file",
        url: "/admin/dropzone/upload",
        addRemoveLinks : true,
        maxFilesize: {{ isset($max_size) ? $max_size : 2 }},
        dictResponseError: 'Error uploading file!',
        @if(isset($max_count))
        maxFiles : parseInt('{{ $max_count }}'),
        @endif
        success : function(file, response) {
            var count = $("#{{ isset($dropzone_id) ? $dropzone_id : 'mydropzone' }} .dz-preview").length;
            file.previewElement.id = response.key;

            @if(isset($max_count))
            if (count > parseInt('{{ $max_count }}')) {
                $("#" + response.key).find('.dz-progress').remove();
                $("#" + response.key).addClass('dz-error');
                $("#" + response.key).find('.dz-error-message').text('You can not upload any more files.');
                return false;
            }
            @endif
            if(response.result) {
                $(".dz-progress").remove();
                $('.dz-preview').addClass('dz-success');
                $("#" + response.key).append('<input type="hidden" name="{{ $file_name }}[]" value="' + response.real_key + '">');
            } else {
                alert(response.msg);
            }

        }
    });

    @if(isset($model->id) && !empty($model->$column))
    //编辑时回显已存在的文件
    $.getJSON('/admin/dropzone/fileList/' + '{{ $model->id }}', {table: "{{ $model->getTable() }}", column: "{{ $column }}"}, function(data) {
        $.each(data, function(index, val) {
            var mockFile = { name: val.name, size: val.size, key: val.key, url: val.url, mimeType: val.mime_type, realKey: val.real_key };
            //编辑时回显已存在的文件
            {{ isset($dropzone_name) ? $dropzone_name : 'myDropzone' }}.emit("addedfile", mockFile);
            if(/.(gif|jpg|jpeg|bmp|png)$/.test(mockFile.mimeType)) {
                // And optionally show the thumbnail of the file:
                {{ isset($dropzone_name) ? $dropzone_name : 'myDropzone' }}.emit("thumbnail", mockFile, mockFile.url);
            }
            {{ isset($dropzone_name) ? $dropzone_name : 'myDropzone' }}.files.push(mockFile);
            $(mockFile.previewElement).prop('id', mockFile.key);
            $("#" + mockFile.key).append('<input type="hidden" name="{{ $file_name }}[]" value="' + mockFile.realKey + '">');
        });
    });
    @endif
</script>