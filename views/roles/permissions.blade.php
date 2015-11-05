<form action="{{{ route('admin.roles.permissions', $role->id) }}}" method="post" id="smart-form-permissions" class="smart-form client-form">
    {!! csrf_field() !!}
    @foreach ($perents as $perent)
        <fieldset>
            <section>
                <div class="row">
                <label class="checkbox">
                    <input type="checkbox" name="{{{ $perent->name }}}" value="{{$perent->id}}" @if(in_array($perent->id,$permission_ids)) checked @endif>
                    <i></i>{{{ $perent->display_name }}}</label>
                    </div>
                @if (isset($perent->menus))
                        @foreach($perent->menus as $menu)
                        <div class="col col-4">
                            <label class="checkbox">
                            <input type="checkbox" name="{{{ $menu->name }}}" value="{{$menu->id}}" @if(in_array($menu->id,$permission_ids)) checked @endif>
                            <i></i>{{{ $menu->display_name }}}</label>
                        </div>
                        @endforeach
                @endif
            </section>
        </fieldset>
    @endforeach

</form>