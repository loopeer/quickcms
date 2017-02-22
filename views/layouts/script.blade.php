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
</script>
