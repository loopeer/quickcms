<script src="{{ asset('loopeer/quickcms/js/libs/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/libs/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.config.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/notification/SmartNotification.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/smartwidgets/jarvis.widget.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/select2/select2.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/msie-fix/jquery.mb.browser.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/demo.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/app.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('loopeer/quickcms/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
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
</script>