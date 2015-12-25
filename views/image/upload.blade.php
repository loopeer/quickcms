<div id="{{$image_name}}" class="span10" data-download-template-id="template-download-{{$image_name}}">
    <div class="fileupload-buttonbar">
        <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span>添加图片</span>
            <input type="file" name="{{$image_name}}">
        </span>
        <div class="fileupload-loading"></div>
        <label class="error" id="{{$image_name}}_error"></label>
    </div>
    <br>
    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
</div>