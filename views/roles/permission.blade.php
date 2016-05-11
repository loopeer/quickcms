<form action="{{{ route('admin.roles.permissions', $role->id) }}}" method="post" id="smart-form-permissions" class="tree smart-form">
    <ul>
        <style>
            .fa-minus-circle {
                padding-top: 5%;
            }
            .fa-plus-circle {
                padding-top: 5%;
            }
            .fa-lg {
                font-size: 1.5em;
            }
        </style>
        @foreach($perents as $perent)
            <li>
                <span> @if(isset($perent->menus))<i class="fa fa-lg fa-minus-circle pull-left"></i>@endif
                <label class="checkbox inline-block pull-right">
                    <input type="checkbox"  name="{{{ $perent->name }}}" value="{{$perent->id}}" @if(in_array($perent->id,$permission_ids)) checked @endif>
                    <i></i>{{ $perent->display_name }}
                </label>
            </span>
                <ul>
                    @if(isset($perent->menus))
                        @foreach($perent->menus as $menu)
                            <li>
                                <span> @if(isset($menu->actions))<i class="fa fa-lg fa-minus-circle pull-left"></i>@endif
                                    <label class="checkbox inline-block pull-right">
                                        <input type="checkbox" name="{{{ $menu->name }}}" value="{{$menu->id}}" @if(in_array($menu->id,$permission_ids)) checked @endif>
                                        <i></i>{{ $menu->display_name }}</label>
                                </span>
                                <ul>
                                    @if(isset($menu->actions))
                                        @foreach($menu->actions as $action)
                                            <li>
                                                <span> <label class="checkbox inline-block">
                                                        <input type="checkbox" name="{{{ $action->name }}}" value="{{$menu->id}}" @if(in_array($action->id,$permission_ids)) checked @endif>
                                                        <i></i>{{ $action->display_name }}</label>
                                                </span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>
        @endforeach
    </ul>
</form>

<script>
    $(document).ready(function() {

        pageSetUp();

        // PAGE RELATED SCRIPTS

        $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
        $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch');
        $('.fa').on('click', function(e) {
            var children = $(this).parent().parent('li.parent_li').find(' > ul > li');
            if (children.is(':visible')) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').removeClass().addClass('fa fa-lg fa-plus-circle pull-left');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').removeClass().addClass('fa fa-lg fa-minus-circle pull-left');
            }
            e.stopPropagation();
        });

    })

</script>