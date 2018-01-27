<div id="{{$voice_name}}" class="span10" data-download-template-id="template-download-{{$voice_name}}">
    <div class="fileupload-buttonbar">
        <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span>添加语音</span>
            <input type="file" name="{{$voice_name}}">
        </span>
        <div class="fileupload-loading"></div>
        <label class="error" id="{{$voice_name}}_error"></label>
    </div>
    <br>
    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
</div>