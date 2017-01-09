
{include file="./_tpl_market.tpl"}
{literal}
<script type="text/javascript">
$(function(){
    //  修改
    $(document).on('click','#edit_operation',function(){
        var formData = getFormData("market_form");
        var params = formData;
        params.corp_id = $(this).parent().attr("data-corp-id");
        params.activity_id = $(this).parent().attr("data-activity-id");
        if (!verifyData(params)) {
            return;
        }
        modify_activity(params).done(function(data){
            if (!data.error) {
                layer.msg("保存成功");
                window.location.href = "/operation/market/activity_list";
            }
        })
    })
})
</script>
{/literal}