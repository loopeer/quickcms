<form action="{{ $action }}" method="post" id="smart-form-register" class="smart-form">
    {!! csrf_field() !!}
    <fieldset>
        <section>
            <label class="label">选择角色</label>
            <div class="row">
                <select name="role_id" class="select2">
                    @foreach($roles as $role)
                        <option value="{{{$role->id}}}" @if($role_id == $role->id) selected @endif>{{{$role->display_name}}}</option>
                    @endforeach
                </select>
            </div>
        </section>
    </fieldset>
</form>
<script>
    $(document).ready(function() {
        pageSetUp();
    });
</script>