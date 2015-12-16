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
                            <h2>新增角色</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body no-padding">
                                <form action="/admin/{{ $route_name }}" method="post" id="smart-form-register" class="smart-form client-form">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" value="{{ $model_data['id'] }}">
                                    <fieldset>
                                        @foreach(config('general.versions_edit_column_name') as $key => $column_name)
                                        <section>
                                            <label class="label">{{ $column_name }}</label>
                                            <label class="input">
                                                <input type="text" name="{{ config('general.versions_edit_column')[$key] }}" value="{{ $model_data[config('general.versions_edit_column')[$key]] }}">
                                            </label>
                                        </section>
                                        @endforeach
                                    </fieldset>
                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                        <a href="{{ route('admin.' . $route_name . '.index') }}" class="btn btn-primary">
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
    <script>
//        $(document).ready(function() {
//            var $registerForm = $("#smart-form-register").validate({
//                // Rules for form validation
//                rules : {
//                    name : {
//                        required : true
//                    },display_name : {
//                        required : true
//                    },route : {
//                        required : true
//                    }
//                },
//
//                // Messages for form validation
//                messages : {
//                    name : {
//                        required : '必须填写角色名称'
//                    },display_name : {
//                        required : '必须填写显示名称'
//                    },route : {
//                        required : '必须填写路由'
//                    }
//                },
//
//                // Do not change code below
//                errorPlacement : function(error, element) {
//                    error.insertAfter(element.parent());
//                }
//            });
//        });
    </script>
@endsection