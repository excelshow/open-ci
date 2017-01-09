
{include file="./_tpl_market.tpl"}
{literal}
<script type="text/javascript">
$(function(){
    //  添加
    $(document).on('click','#save_operation',function(){
        var formData = getFormData("market_form");
        var params = formData;
        if (!verifyData(params)) {
            return;
        }
        add_activity(params).done(function(data){
            if (!data.error) {
                layer.msg("保存成功");
                window.location.href = "/operation/market/activity_list";
            }
        })
    })
})
</script>
{/literal}