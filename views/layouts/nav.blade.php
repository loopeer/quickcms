<aside id="left-panel">
    <!-- User info -->
    <div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it -->

					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <img src="{{ asset('loopeer/quickcms/img/avatars/sunny.png') }}" alt="me" class="online" />
						<span>
                            @if(isset(Auth::admin()->get()->id))
							{{Auth::admin()->get()->name}}
                            @endif
						</span>
                        <i class="fa fa-angle-down"></i>
                    </a>

				</span>
    </div>
    <!-- end user info -->
    <input id="current_route" type="hidden" value="{{ '/' . Request::url() }}"/>
    <nav>
        <ul>
            @foreach(Session::get('menu') as $rootMenu)
            <li>
                <a href="{{$rootMenu['route']}}" title="{{ $rootMenu['name'] }}"><i class="fa fa-lg fa-fw {{$rootMenu['icon']}}"></i> <span class="menu-item-parent">{{ $rootMenu['display_name'] }}</span></a>
                @if(count($rootMenu['menus']) > 0)
                <ul>
                    @foreach($rootMenu['menus'] as $menu)
                    <li>
                        <a href="{{ $menu['route'] }}">{{ $menu['display_name'] }}</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>
</aside>