<form action="/admin/notifyTemplates/customPush/{{$id}}" method="post" id="custom-push-form" class="smart-form client-form">
    {!! csrf_field() !!}
    <fieldset>
        <section>
            <label class="label">用户ID(1,2,5):</label>
            <label class="input">
                <input type="text" name="accountIds">
            </label>
        </section>
    </fieldset>
</form>