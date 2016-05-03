<!-- RIBBON -->
<div id="ribbon">
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
