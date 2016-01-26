<form action="/admin/pushes/save" method="post" id="push_form" class="smart-form client-form">
    <fieldset>
        <section>
            <label class="input">
                <input type="text" name="content" id="content" placeholder="输入推送内容">
            </label>
            <label class="input">
                <input type="text" name="notice_type" id="notice_type" placeholder="通知业务类型">
            </label>
            <label class="input">
                <input type="text" name="notice_id" id="notice_id" placeholder="通知业务主键id">
            </label>
            <label class="input">
                <input type="text" name="notice_url" id="notice_url" placeholder="通知业务跳转链接">
            </label>
        </section>
    </fieldset>
</form>
<script>
    pageSetUp();
</script>