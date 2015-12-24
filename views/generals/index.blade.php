@extends('backend::layouts.master')

@section('content')
<!-- MAIN CONTENT -->
<div id="content">
        <section id="widget-grid" class="">
            <div class="row tips">
                @include('backend::layouts.message')
            </div>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p><a href="/admin/{{ $route_name }}/create/" class="btn btn-primary">新增{{ $model_name }}</a></p>
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2>{{ $model_name }}列表</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        @foreach($column_name as $name)
                                        <th>{{ $name }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="delete_token" value="{{ csrf_token() }}"/>
                    </div>
                </article>
            </div>
    </section>
</div>
<!-- END MAIN CONTENT -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var route_name = '{{ $route_name }}';
        var model_class = '{{{ $model_class }}}';
        var table = $('#dt_basic').DataTable({
            "processing": false,
            "serverSide": true,
            "bStateSave": true,
            @if(!empty($actions))
            "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent":
                '<div class="btn-group">'+
                '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">操作 <span class="caret"></span></button>'+
                '<ul class="dropdown-menu">'+
                @foreach($actions as $action)
                '<li>'+
                '<a href="javascript:void(0);" name="' + '{{$action['name']}}' + '">' + '{{$action['display_name']}}' + '</a>'+
                '</li>'+
                @if($action['has_divider'])
                '<li class="divider btn_content"></li>'+
                @endif
                @endforeach
//                '<li>'+
//                '<a href="javascript:void(0);" name="delete_btn">删除</a>'+
//                '</li>'+
                '</ul>'+
                '</div>'
            } ],
            @endif
            "ajax": {
                "url": "/admin/" + route_name + "/search"
            }
        });

        $('#dt_basic tbody').on('click', 'a[name=edit_btn]', function () {
            var data = table.row($(this).parents('tr')).data();
            //var delete_token = $('#delete_token').val();
            window.location = '/admin/' + route_name + '/' + data[0] + '/edit/';
        });

        $('#dt_basic tbody').on('click', 'a[name=delete_btn]', function () {
            var data = table.row($(this).parents('tr')).data();
            var delete_token = $('#delete_token').val();
            if(confirm('删除这条记录?')) {
                $.ajax({
                    type: "DELETE",
                    data: { '_token' : delete_token, "model_class" : model_class },
                    url: '/admin/' + route_name + '/' + data[0], //resource
                    success: function(result) {
                        if (result > 0) {
                            var table = $('#dt_basic').dataTable();
                            var nRow = $($(this).data('id')).closest("tr").get(0);
                            table.fnDeleteRow( nRow, null, true );
                            $(".tips").html('<div class="alert alert-success fade in">'
                            +'<button class="close" data-dismiss="alert">×</button>'
                            +'<i class="fa-fw fa fa-check"></i>'
                            +'<strong>成功</strong>' + ' 删除记录成功。'
                            +'</div>');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection