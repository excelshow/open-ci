<div class="panel-body">
    <form id="market_form">
        <div class="add-contest">
            <div class="form-group">
                <p>
                    <label>
                        1. 活动标题 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="请填写活动标题" value="{if !empty($result)}{$result->name}{/if}" maxlength="50">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        2. 活动开始时间 <b>*</b>
                    </label>
                </p>
                <input type="text" id="inpstart" class="form-control operation-time" name="time_start" placeholder="请点击选择开始时间" value="{if !empty($result)}{$result->time_start}{/if}" oninput="this.value =''">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        3. 活动结束时间 <b>*</b>
                    </label>
                </p>
                <input type="text" id="inpend" class="form-control operation-time" name="time_end" placeholder="请点击选择结束时间" value="{if !empty($result)}{$result->time_end}{/if}" oninput="this.value =''">
            </div>
            <div class="form-group">
                <p>
                    <label>4. 活动规则说明 <b>*</b></label>
                </p>
                <textarea class="form-control textarea-none" name="desc_rule" placeholder="活动规则说明">{if !empty($result)}{$result->desc_rule}{/if}</textarea>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        5. 是否需要关注公众号 <b>*</b>
                    </label>
                </p>
                <div class="label-listimg">
                    {if !empty($result)} {if $result->need_follow == 1}
                    <input type="radio" name="need_follow" value="1" checked>是
                    <input type="radio" name="need_follow" value="2">否 {else}
                    <input type="radio" name="need_follow" value="1">是
                    <input type="radio" name="need_follow" value="2" checked>否 {/if} {else}
                    <input type="radio" name="need_follow" value="1">是
                    <input type="radio" name="need_follow" value="2" checked>否 {/if}
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        6. 用户默认可领取 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="number" placeholder="请填写张数" value="{if !empty($result)}{$result->number}{/if}" oninput="this.value=this.value.replace(/[^\d]/g,'')" maxlength="6">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        7. 分享大于等于1次可再领取 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="number_invite_one" placeholder="请填写张数" value="{if !empty($result)}{$result->number_invite_one}{/if}" oninput="this.value=this.value.replace(/[^\d]/g,'')" maxlength="6">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        8. 最多可领取张数 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="number_max" placeholder="请填写张数" value="{if !empty($result)}{$result->number_max}{/if}" oninput="this.value=this.value.replace(/[^\d]/g,'')" maxlength="6">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        9. 上传图片
                        <b>*</b>
                        <span class="text-muted">*(支持jpg、png，尺寸长宽750*272)</span>
                    </label>
                </p>
                <input type="file" name="files" class="imagesss uploadImage"/>
                <input type="button" class="uploadImage btn btn-sm btn-blue" value="{if !empty($result)}重新上传{else}上传图片{/if}" targetId="poster" />
                <input class="hiddenImg" type="hidden" name="banner" value="{if !empty($result)}{$result->banner}{/if}">
                <div class="img-thump poster" id="empty_poster">
                    <img src="{if !empty($result)}http://img.wesai.com/{$result->banner}{/if}" alt="">
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        10. 排序 <span class="text-muted">(按从小到大排序)</span>
                    </label>
                </p>
                <input type="text" class="form-control operation-time" name="orderby" placeholder="请填写序号" maxlength="6" value={if !empty($result)}{if !empty($result->orderby)}{$result->orderby}{else}0{/if}{/if}>
            </div>
            {if !empty($result)}
            <div class="pd10" data-corp-id="{$result->corp_id}" data-activity-id="{$result->activity_id}">
                <a href="javascript:;" class="oper-btn btn btn-default btn-blue mgr20" id="edit_operation">保存</a>
            </div>
            {else}
            <div class="pd10">
                <a href="javascript:;" class="oper-btn btn btn-default btn-blue mgr20" id="save_operation">添加</a>
            </div>
            {/if}
        </div>
    </form>
</div>
<script src="{'manager_operation/lib/js/layer.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/jquery.ui.widget.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/jquery.iframe-transport.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/jquery.fileupload.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/jquery.fileupload-process.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/jquery.fileupload-validate.js'|cdnurl}"></script>
<script src="{'manager_operation/lib/img_js/main_img.js'|cdnurl}"></script>
{literal}
<script type="text/javascript">
var start = {
    dateCell: '#inpstart',
    format: 'YYYY-MM-DD',
    minDate: jeDate.now(0), //设定最小日期为当前日期
    festival: true,
    ishmsVal: false,
    maxPage: 0,
    maxDate: '2099-06-30 23:59:59', //最大日期
    choosefun: function(elem, datas) {
        $("#inpstart").val($("#inpstart").val()+" 00:00:00");
        end.minDate = datas; //开始日选好后，重置结束日的最小日期
    },
    clearfun: function(elem, val) {}
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
    clearfun: function(elem, val) {}
};
jeDate(start);
jeDate(end);

//  表单验证
function verifyData(data) {
    if (!data.name) {
        layer.msg("请输入活动标题")
        return false;
    }
    if (!data.time_start) {
        layer.msg("请输入活动开始时间")
        return false;
    }
    if (!data.time_end) {
        layer.msg("请输入活动结束时间")
        return false;
    }
    if (!data.desc_rule) {
        layer.msg("请输入活动规则说明")
        return false;
    }
    if (!data.number) {
        layer.msg("请输入用户默认可领取")
        return false;
    }
    if (!data.number_invite_one) {
        layer.msg("请输入分享大于等于1次可再领取张数")
        return false;
    }
    if (!data.number_max) {
        layer.msg("请输入最多可领取张数")
        return false;
    }
    if (parseInt(data.number)>parseInt(data.number_max)) {
        layer.msg("最多可领取张数需大于等于用户默认可领取张数")
        return false;
    }
    if (!data.banner) {
        layer.msg("请上传图片")
        return false;
    }
    return true;
}
</script>
{/literal}
