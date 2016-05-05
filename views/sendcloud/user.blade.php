<form action="/admin/sendcloud/apiuser" method="post" id="api_user_form" class="smart-form client-form">
    <fieldset>
        <section>
            <label class="label">选择API USER</label>
            <select name="api_user" class="select2">
                @foreach($users as $user)
                    <option value="{{ $user }}" @if($user == Cache::get('sendcloud_api_user')) selected @endif>{{ $user }}</option>
                @endforeach
            </select>
        </section>
    </fieldset>
</form>

<script>
    $(document).ready(function () {
        pageSetUp();
    });
</script>