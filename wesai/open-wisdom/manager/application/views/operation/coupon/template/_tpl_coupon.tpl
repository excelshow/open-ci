<div class="panel-body">
    <form id="couopn_form">
        <div class="add-contest">
            <div class="form-group">
                <p>
                    <label>
                        1. 优惠券名称 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="此名称会显示在用户账户中，请尽量描述清晰" value="{if !empty($result)}{$result->name}{/if}" maxlength="10">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        2. 优惠券截止日期 <b>*</b>
                    </label>
                </p>
                <input type="text" id="inpend" class="form-control operation-time" name="stop_time" placeholder="请点击选择截止时间" value="{if !empty($result)}{$result->stop_time}{/if}" oninput="this.value =''">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        3. 优惠券数量 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="number" placeholder="最多100000张" value="{if !empty($result)}{$result->number}{/if}" oninput="this.value=this.value.replace(/[^\d]/g,'')" maxlength="6">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        4. 优惠券面值金额 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="value" placeholder="请填写金额（元）" value="{if !empty($result)}{$result->value}{/if}" oninput="this.value=_numberReg(this)" maxlength="6">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        5. 优惠券最低使用金额 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="value_min" placeholder="请填写金额（元）" value="{if !empty($result)}{$result->value_min}{/if}" oninput="this.value=_numberReg(this)" maxlength="6">
            </div>
            {if !empty($result)}
                <div class="pd10" data-corp-id="{$result->corp_id}" data-rule-id="{$result->voucher_rule_id}" data-scope-type="{$result->scope_type}">
                 <a href="javascript:;" class="oper-btn btn btn-blue mgr20" id="edit_coupon">保存</a>
                </div>
            {else}
                <div class="pd10">
                    <a href="javascript:;" class="oper-btn btn btn-blue mgr20" id="save_coupon">添加</a>
                </div>
            {/if}
        </div>
    </form>
</div>

{literal}
<script type="text/javascript">
var start = {
    dateCell: '#inpstart',
    format: 'YYYY-MM-DD',
    minDate: jeDate.now(0), //设定最小日期为当前日期
    festival: true,
    ishmsVal: false,
    maxPage:0,
    maxDate: '2099-06-30 23:59:59', //最大日期
    choosefun: function(elem, datas) {
        $("#inpstart").val($("#inpstart").val()+" 00:00:00");
        end.minDate = datas; //开始日选好后，重置结束日的最小日期
    },
    clearfun: function(elem, val) {
    }
};
var end = {
    dateCell: '#inpend',
    format: 'YYYY-MM-DD',
    minDate: jeDate.now(0), //设定最小日期为当前日期
    festival: true,
    maxDate: '2099-06-16 23:59:59', //最大日期
    choosefun: function(elem, datas) {
        $("#inpend").val($("#inpend").val()+" 23:59:59");
        start.maxDate = datas;
    },
    clearfun: function(elem, val) {
    }
};
jeDate(start);
jeDate(end);

function verifyData(data){
    if (!data.name) {
        layer.msg("请输入优惠券名称");
        return false;
    }
    if (!data.stop_time) {
        layer.msg("请选择截止时间");
        return false;
    }
    if (!data.number) {
        layer.msg("请按输入优惠券数量");
        return false;
    }
    if (data.number > 100000) {
        layer.msg("优惠券数量最大为100000");
        return false;
    }
    if (!data.value) {
        layer.msg("请按输入优惠券面值金额");
        return false;
    }
    if (!data.value_min) {
        layer.msg("请按输入优惠券最低使用金额");
        return false;
    }
    if (parseInt(data.value_min) == parseInt(data.value)) {
        layer.msg("最低使用金额需大于面值金额");
        return false;
    }
    return true;
}

</script>
{/literal}
