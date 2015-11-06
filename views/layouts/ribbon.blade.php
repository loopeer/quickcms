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
        <li><a href="{{ session('ribbon')->parent->route }}">{{ session('ribbon')->parent->display_name }}</a></li>
        <li><a href="{{ session('ribbon')->route }}">{{ session('ribbon')->display_name }}</a></li>
    </ol>
    <!-- end breadcrumb -->
</div>
<!-- END RIBBON -->