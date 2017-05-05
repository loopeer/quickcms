<link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/jquery.fileupload-ui.css') }}}">
<link rel="stylesheet" type="text/css" href="{{{ asset('loopeer/quickcms/js/blueimp/css/blueimp-gallery.min.css') }}}">
<script>
    $(document).ready(function() {
        @if(isset($image['file']) && !empty($image['file']))
            $("#{{ $image['name'] }}_section .{{ $image['name'] }}_content").html('<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"><tr class="template-download fade success in">' +
            '<td width="100%">' +
            '<span class="preview">' +
            '<img id="{{ $image['name'] }}" src="{{ url() . $image['file'] }}" style="max-width:100%;">' +
            '<input type="hidden" value="{{  $image['file'] }}" name="{{ $image['name'] }}">' +
            '</span>' +
            '</td>' +
            '<td style="vertical-align:middle;text-align:left;">' +
            '<button class="btn btn-danger delete" type="button" onclick="$(this).parent().parent().remove();">' +
            '<i class="icon-trash icon-white"></i>' +
            '<span>删除</span>' +
            '</button>' +
            '</td>' +
            '</tr></tbody></table>');
        @endif

            $('#{{ $image['name'] }}_file').on('change', function() {
            var image = this.files[0];
            if($("#{{ $image['name'] }}_section .{{ $image['name'] }}_content .table tr").length > 0){
                alert('只允许上传1张图片');
                return false;
            }

            if(/.(gif|jpg|jpeg|png|bmp)$/.test(image.name)) {
                $('#{{ $image['name'] }}_section #mark').html('正在上传...');
                var formData = new FormData();
                formData.append('{{ $image['name'] }}', image);
                formData.append('file_name', '{{ $image['name'] }}');

                $.ajax({
                    url: "/admin/blueimp/upload4Local",
                    type: "POST",
                    data: formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false   // tell jQuery not to set contentType
                }).done(function(result) {
                    if(result.result) {
                        $("#{{ $image['name'] }}_section .{{ $image['name'] }}_content").html('<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"><tr class="template-download fade success in">' +
                            '<td width="100%">' +
                            '<span class="preview">' +
                            '<img id="{{ $image['name'] }}" src="' + result.url + '" style="max-width:100%;">' +
                            '<input type="hidden" value="' + result.path + '" name="{{ $image['name'] }}">' +
                            '</span>' +
                            '</td>' +
                            '<td style="vertical-align:middle;text-align:left;">' +
                            '<button class="btn btn-danger delete" type="button" onclick="$(this).parent().parent().remove();">' +
                            '<i class="icon-trash icon-white"></i>' +
                            '<span>删除</span>' +
                            '</button>' +
                            '</td>' +
                            '</tr></tbody></table>');
                    } else {
                        $("#{{ $image['name'] }}_section .error_content").html('<div class="alert alert-danger fade in">' +
                            '<button class="close" data-dismiss="alert">×</button><i class="fa-fw fa fa-times"></i>' +
                            '<strong>失败!</strong>' + result.msg + '</div>');
                    }
                    $('#{{ $image['name'] }}_section #mark').html('上传图片');
                });

            } else {
                alert('只允许上传图片');
                return false;
            }
        });
    });
</script>
