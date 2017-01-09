
{include file="./_tpl_coupon.tpl"}
{literal}
<script type="text/javascript">
$(function(){
    //  添加
    $(document).on('click',"#save_coupon",function(e){
        var formData = getFormData('couopn_form');
        formData.scope_type = 1;
        formData.value = formData.value*100;
        formData.value_min = formData.value_min*100;
        if (!verifyData(formData)) {
            return;
        }
        add_coupon_rule(formData).done(function (data) {
            if (!data.errot) {
                layer.msg("添加成功");
                window.location.href = "/operation/coupon/rule_list";
            }
        })
    })
})
</script>
{/literal}
