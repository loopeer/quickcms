@extends('backend::layouts.master')

@section('content')
    <!-- MAIN CONTENT -->
<div id="content">
        <section id="widget-grid" class="">
        <div class="row tips">
            @include('backend::layouts.message')
        </div>
        <div class="row">
            <p>
                <a href="javascript:void(0);" class="btn btn-primary" id="push_batch" data-target="#similar_product" data-toggle="modal" data-action="/admin/pushes/batch">推送消息</a>
                <a href="/admin/pushes/all/" class="btn btn-primary">全局推送</a>
            </p>
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>推送设备列表</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户id</th>
                                <th>设备id</th>
                                <th>平台</th>
                                <th>更新时间</th>
                                <th>选择</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="delete_token" value="{{csrf_token()}}"/>
            </div>
        </article>
        </div>
    </section>
</div>
    <!-- Modal -->
    <div class="modal fade" id="similar_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title">消息内容</h4>
                </div>
                <div class="modal-body custom-scroll terms-body">
                    <div id="left">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="submitSimilar"><i class="fa fa-check"></i>提交</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#dt_basic').DataTable({
            "processing": false,
            "serverSide": true,
            "bStateSave": true,
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": '<label class="checkbox"> <input type="checkbox" class="activity_box"><i></i></label>'
            }],
            "ajax": {
                "url": "/admin/pushes/search"
            }
        });
        $('#similar_product').on('show.bs.modal', function(e) {
            var action = $(e.relatedTarget).data('action');
            //populate the div
            loadURL(action, $('#similar_product .modal-content .modal-body #left'));
        });

        $( "#push_batch" ).on("click", function() {
            var chk_value =[];
            $('input[type="checkbox"]:checked').each(function(){
                var data = table.row($(this).parents('tr')).data();
                chk_value.push(data[0]);
            });
            if(chk_value.length==0){
                alert('请选择推送的用户');
                return false;
            }
        });

        $("#submitSimilar").click(function(){
            $("#submitSimilar").text("正在保存...");
            var $form = $('#add_similar_form');
            var page_info = table.page.info();
            var page = page_info.page;
            var datatable = $('#dt_basic').dataTable();
            var chk_value =[];
            $('input[type="checkbox"]:checked').each(function(){
                var data = table.row($(this).parents('tr')).data();
                chk_value.push(data[0]);
            });
            var content = $("#content").val();
            alert(content);
            alert($form.serialize());

            var data = $form.serializeArray();
            var uniquekey = {
                name: "account_ids",
                value: chk_value
            };
            data.push(uniquekey);
            $($form).submit(function(event) {
                var form = $(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: data
                }).done(function(result) {
                    if(result.result) {
                        datatable.fnPageChange(page);
                        $(".tips").html('<div class="alert alert-success fade in">'
                        +'<button class="close" data-dismiss="alert">×</button>'
                        +'<i class="fa-fw fa fa-check"></i>'
                        +'<strong>成功</strong>'+' '+result.content+'。'
                        +'</div>');
                    } else {
                        $(".tips").html('<div class="alert alert-danger fade in">'
                        +'<button class="close" data-dismiss="alert">×</button>'
                        +'<i class="fa-fw fa fa-warning"></i>'
                        +'<strong>失败</strong>' + ' ' + result.content + '。'
                        +'</div>');
                    }
                    $("#submitSimilar").text("提交");
                    $('#similar_product').modal('hide');
                    $form[0].reset();
                });
                event.preventDefault(); // Prevent the form from submitting via the browser.
            });
            $form.trigger('submit'); // trigger form submit
        });
    });
</script>
@endsection