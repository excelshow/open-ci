
{include file="./_tpl_coupon.tpl"}
{literal}
<script type="text/javascript">
$(function() {
    //  修改
    $(document).on('click', "#edit_coupon", function(e) {
        var formData = getFormData('couopn_form');
        formData.scope_type = $(this).parent().attr("data-scope-type");
        formData.corp_id = $(this).parent().attr("data-corp-id");
        formData.rule_id = $(this).parent().attr("data-rule-id");
        formData.value = formData.value * 100;
        formData.value_min = formData.value_min * 100;
        if (!verifyData(formData)) {
            return;
        }
        modify_coupon(formData).done(function(data) {
            if (!data.error) {
                layer.msg("修改成功");
                window.location.href = "/operation/coupon/rule_list";
            }
        })
    })
})
</script>
{/literal}
