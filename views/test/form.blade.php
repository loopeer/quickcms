<form action="/admin/test/add" method="post" id="version_form" class="smart-form client-form">
    {!! csrf_field() !!}
    <fieldset>
        <section>
            <label class="label">测试</label>
            <label class="input">
                <input class="form-control" name="name" type="text">
            </label>
        </section>
        <section>
            <label class="label">测试</label>
            <label class="input">
                <input class="form-control" name="sex" type="text">
            </label>
        </section>
    </fieldset>
</form>