<!-- HEADER -->
<header id="header">
    <div id="logo-group" style="padding: 10px;">
        <img id="logo-img" style="width: 32px;height: 32px;" src="{{ asset('loopeer/quickcms/img/cms_logo.png') }}">
        <div style="display: inline-block;margin-left: 4px;">{{ config('quickCms.site_title')  }}</div>
    </div>

    <div class="pull-right">
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
            <li class="">
                <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                    <img src="{{ isset(Auth::admin()->get()->avatar) && Auth::admin()->get()->avatar != '' ? Auth::admin()->get()->avatar : asset('loopeer/quickcms/img/avatars/sunny.png') }}" alt="John Doe" class="online" style="height: 30px" />
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i>&nbsp;全屏</a>
                    </li>
                    <li class="divider"></li>
		    <li>
			<a href="/admin/users/profile" class="padding-10 padding-top-0 padding-bottom-0" data-action="userProfile"><i class="fa fa-child"></i>&nbsp;个人</a>
		    </li>
                    <li class="divider"></li>
                    <li>
			<a href="{{route('admin.logout')}}" class="padding-10 padding-top-0 padding-bottom-0" data-action="userLogout"><i class="fa fa-sign-out"></i>&nbsp;登出</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="{{route('admin.logout')}}" title="Sign Out" data-action="userLogout" data-logout-msg="您可以在登出后通过关闭此打开的浏览器进一步提高安全性"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
    </div>
<div class="pull-right" style="padding-top:15px">
	<span>欢迎你，{{ Auth::admin()->get()->name }}</span>
</div>
</header>
