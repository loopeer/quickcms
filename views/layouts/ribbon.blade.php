<!-- RIBBON -->
<div id="ribbon">
    <span class="ribbon-button-alignment">
        <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
            <i class="fa fa-refresh"></i>
        </span>
    </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>管理面板</li>
        @if(isset(session('/' . Route::getCurrentRoute()->getPath())->parent))
            <li><a href="{{ session('/' . Route::getCurrentRoute()->getPath())->parent->route }}">{{ session('/' . Route::getCurrentRoute()->getPath())->parent->display_name }}</a></li>
        @endif
        @if(isset(session('/' . Route::getCurrentRoute()->getPath())->id))
            <li><a href="{{ session('/' . Route::getCurrentRoute()->getPath())->route }}">{{ session('/' . Route::getCurrentRoute()->getPath())->display_name }}</a></li>
        @endif
    </ol>
    <!-- end breadcrumb -->
</div>
<!-- END RIBBON -->