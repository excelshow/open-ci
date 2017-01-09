<div class="dialog-bg bing-coupon" id="bing-coupon" style="display:none"></div>
{literal}
<script id="bing-coupon-template" type="text/x-handlebars-template">
    <div class="dialog-box">
        <div class="dialog-tit">
            <h3>{{title}}</h3>
        </div>
        <div class="dialog-con">
            <div class="banner-name">{{name}}</div>
            <div class="coupon-info">
                <div class="coupon-tit">选择关联优惠券：</div>
                <div class="coupon-select">
                    {{#each data}}
                    <div class="coupon-group">
                        <label for="{{voucher_rule_id}}">
                            <input id="{{voucher_rule_id}}" type="checkbox" name="{{voucher_rule_id}}" value="{{voucher_rule_id}}">
                            <span>{{name}}</span>
                        </label>
                    </div>
                    {{/each}}
                </div>
            </div>
            <div class="dialog-btn ub ub-ac ub-pc" data-corp-id="{{corp_id}}" data-plan-id="{{plan_id}}">
                <a href="javascript:;" class="btn sure-select confirm-bg">{{confirm}}</a>
                <a href="javascript:;" class="btn cancel-bg">{{cancel}}</a>
            </div>
        </div>
    </div>
</script>
{/literal}
{literal}
<script type="text/javascript">
$(function() {
    $(document).on('click','.sure-select',function(){
        var corpId = $(this).parent().attr('data-corp-id');
        var planId = $(this).parent().attr('data-plan-id');
        var formData = $(".coupon-group input.user_sel");
        var arr = [];
        if (formData.length == 0) {
            layer.msg("请选择想要关联的优惠券");
            return;
        }
        for(var i =0;i<formData.length;i++){
            arr.push($(formData).eq(i).val())
        }
        var rule = arr.toString()
        var params = {
            corp_id:corpId,
            activity_id:planId,
            type: 60,
            rule:rule
        }
        bind_operation(params).done(function(data){
            if (!data.error) {
                layer.msg("关联成功");
                window.location.href = "/operation/market/activity_list";
            }
        })
    })
    $(document).on('click','.coupon-group input',function(){
        if ($(this).hasClass('user_sel')) {
            $(this).removeClass('user_sel');
        }else{
            $(this).addClass('user_sel');
        }
    })  
})   
</script>
{/literal} 