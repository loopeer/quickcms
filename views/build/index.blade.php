@extends('backend::layouts.master')
@section('content')
    <div id="content">
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-6">
                    @include('backend::layouts.message')
                    <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>新增模块</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="{{ $action }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    <style>
                                        .bootstrap-tagsinput {
                                            color: #555;
                                            font-size: 13px;
                                            line-height: 1.42857;
                                        }
                                        .bootstrap-tagsinput .tag {
                                            color: #fff;
                                            display: inline-block;
                                            margin: 3px 0 3px 2px;
                                            position: relative;
                                        }
                                        .bootstrap-tagsinput > span {
                                            background: none repeat scroll 0 0 #3276b1;
                                            border: 1px solid #285e8e;
                                            border-radius: 0 !important;
                                            font-size: 13px;
                                            font-weight: 400;
                                            padding: 3px 28px 4px 8px;
                                        }
                                    </style>
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <section>
                                            <label class="label">所属数据表</label>
                                            <select name="table" id="table" class="select2">
                                                @foreach($tables as $key => $table)
                                                    <option value="{{ $table['Tables_in_'.config('database.connections.mysql.database')] }}">
                                                        {{ $table['Tables_in_'.config('database.connections.mysql.database')] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </section>

                                        <section>
                                            <label class="label">列表展示字段</label>
                                            <input class="form-control" name="columns" id="columns" type="text">
                                        </section>
                                        <section>
                                            <label class="label">控制器名称</label>
                                            <label class="input">
                                                <input type="text" name="controller_name" placeholder="Controller" id="controller_name">
                                                <b class="tooltip tooltip-bottom-right">控制器名称</b> </label>
                                            <div class="note">如：UserController</div>
                                        </section>
                                        <section>
                                            <label class="label">控制器路径</label>
                                            <label class="input">
                                                <input type="text" name="controller_path" placeholder="Controller Path" id="controller_path">
                                                <b class="tooltip tooltip-bottom-right">控制器路径</b> </label>
                                            <div class="note">默认路径为：app/Http/Controllers/，如需要把路径改变为app/Http/Controllers/Backend/，请直接填写Backend</div>
                                        </section>
                                        <section>
                                            <label class="label">控制器namespace</label>
                                            <label class="input">
                                                <input type="text" name="controller_namespace" placeholder="Controller Namespace" id="controller_namespace">
                                                <b class="tooltip tooltip-bottom-right">控制器namespace</b> </label>
                                            <div class="note">默认为：App\Http\Controllers</div>
                                        </section>
                                        <section>
                                            <label class="label">模型名称</label>
                                            <label class="input">
                                                <input type="text" name="model_name" placeholder="Model" id="model_name">
                                                <b class="tooltip tooltip-bottom-right">模型名称</b> </label>
                                            <div class="note">如：User</div>
                                        </section>
                                        <section>
                                            <label class="label">模型路径</label>
                                            <label class="input">
                                                <input type="text" name="model_path" placeholder="Model Path" id="model_path">
                                                <b class="tooltip tooltip-bottom-right">模型路径</b> </label>
                                            <div class="note">默认路径为：app/Model/，如需要把路径改变为app/Model/Backend/，请直接填写Backend</div>
                                        </section>
                                        <section>
                                            <label class="label">模型namespace</label>
                                            <label class="input">
                                                <input type="text" name="model_namespace" placeholder="Controller Namespace" id="model_namespace">
                                                <b class="tooltip tooltip-bottom-right">模型namespace</b> </label>
                                            <div class="note">默认为：App\Models</div>
                                        </section>
                                        <section>
                                            <label class="label">模板路径</label>
                                            <label class="input">
                                                <input type="text" name="view_path" placeholder="View Path" id="view_path">
                                                <b class="tooltip tooltip-bottom-right">模板路径</b> </label>
                                            <div class="note">默认路径为：resources/views/，如需要把路径改变为resources/views/backend 或
                                                resources/views/backend/user，请直接填写backdend或backend/user</div>
                                        </section>
                                        <section>
                                            <label class="label">index模板名称</label>
                                            <label class="input">
                                                <input type="text" name="index_blade" placeholder="Index Blade" id="index_blade">
                                                <b class="tooltip tooltip-bottom-right">index模板名称</b> </label>
                                            <div class="note">默认名称为：index</div>
                                        </section>
                                        {{--<section>--}}
                                            {{--<label class="label">create模板名称</label>--}}
                                            {{--<label class="input">--}}
                                                {{--<input type="text" name="create_view_name" placeholder="Model Path" id="model_name">--}}
                                                {{--<b class="tooltip tooltip-bottom-right">模型路径</b> </label>--}}
                                            {{--<div class="note">默认名称为：create</div>--}}
                                        {{--</section>--}}
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="{{route('admin.permissions.index')}}" class="btn btn-primary">
                                            返回
                                        </a>
                                    </footer>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{{ asset('loopeer/quickcms/js/plugin/bootstrap-tags/bootstrap-tagsinput-angular.min.js') }}}"></script>
    <script>
        $(document).ready(function() {

            $('#columns').val('{{$default_column_fields}}');
            $('#columns').tagsinput();

            $('#table').on('change', function() {
                table = $('#table').val();
                $.ajax({
                    url: "/admin/getColumns",
                    type: "get",
                    data: {'table' : table}
                }).done(function(result) {
                    $('#columns').tagsinput('removeAll');
                    $('#columns').val(result);
                    $('#columns').tagsinput('add',result);
                });
            });

            $("#smart-form-register").validate({
                // Rules for form validation
                rules : {
                    controller_name : {
                        required : true
                    },model_name : {
                        required : true
                    },route : {
                        required : true
                    }
                },

                // Messages for form validation
                messages : {
                    controller_name : {
                        required : '必须填写控制器名称'
                    },model_name : {
                        required : '必须填写模型名称'
                    },route : {
                        required : '必须填写路由'
                    }
                },

                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    </script>
@endsection