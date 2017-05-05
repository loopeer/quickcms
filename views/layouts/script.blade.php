<script src="{{ asset('loopeer/quickcms/js/libs/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/libs/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.config.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/notification/SmartNotification.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/smartwidgets/jarvis.widget.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/jquery-validate/jquery.validate.messages_zh.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/select2/select2.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/msie-fix/jquery.mb.browser.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/datetimepicker/bootstrap-datetimepicker.min.js')}}" charset="UTF-8"></script>
<script src="{{ asset('loopeer/quickcms/js/datetimepicker/bootstrap-datetimepicker.zh-CN.js') }}" charset="UTF-8"></script>

<script>
    $(document).ready(function() {
        pageSetUp();

        $(function() {
            var route = $('#current_route').val()+'/';
            $("nav a").each(function () {
                if(route.indexOf($(this).attr('href')+'/') != -1) {
                    $(this).parent().attr('class', 'active');
                    $(this).parent().parent().attr('style', 'display: block;');
                    if($(this).parent().parent().parent()[0].tagName != 'NAV') {
                        $(this).parent().parent().parent().attr('class', 'open active');
                        $(this).parent().parent().parent().find('em').attr('class', 'fa fa-minus-square-o');
                    }
                }
            });
        });
    });

    function hideTips() {
        $(".tips").show();
        $(".tips").delay({{ config('quickCms.hide_tips_time') }}).hide(0);
    }

    var permission_switch = '{{ config("quickCms.permission_switch") }}';
    function permission() {
        if(permission_switch == '1') {
            @if(isset($is_permission) && $is_permission)
                var all_action_btn = $('a[permission]');
                $.each(all_action_btn, function (i) {
                    var all_action_flag = true;
                    @if(Session::get('permissions'))
                    @foreach(Session::get('permissions') as $key => $permission)
                    '{!! $permission_name = $permission->name !!}'
                    if ('{!! $permission_name !!}' == $(this).attr('permission')) {
                        all_action_flag = false;
                    }
                    @endforeach
                        @endif
                    if (all_action_flag) {
                        if ($(this).parent().is('li')) {
                            $(this).parent().addClass('disabled');
                        } else {
                            $(this).attr('href', 'javascript:void(0)');
                            $(this).addClass('disabled');
                        }
                    }
                });
            @endif
        }
    }

    function isDisabled(obj) {
	    if(permission_switch == '1') {
	        return !obj.parent().hasClass('disabled');
	    }
	    return true;
    }

    // 字符验证
    jQuery.validator.addMethod("specificString", function(value, element) {
        var zh = /[\u3002|\uff1f|\uff01|\uff0c|\u3001|\uff1b|\uff1a|\u201c|\u201d|\u2018|\u2019|\uff08|\uff09|\u300a|\u300b|\u3008|\u3009|\u3010|\u3011|\u300e|\u300f|\u300c|\u300d|\ufe43|\ufe44|\u3014|\u3015|\u2026|\u2014|\uff5e|\ufe4f|\uffe5]/;
        return this.optional(element) || (/^[\u0391-\uFFE5\w]+$/.test(value) && !zh.test(value));
    }, "不允许包含特殊符号!");

    jQuery.validator.addMethod("english", function(value, element) {
        var english = /^[a-zA-Z]+$/;
        return this.optional(element) || (english.test(value));
    }, "不允许输入非英文字符");

</script>
