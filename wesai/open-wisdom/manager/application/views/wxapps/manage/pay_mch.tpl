{include file='_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/base.css'|cdnurl}">
<link rel="stylesheet" type="text/css" href="{'manager/css/menu.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
            <!-- rightStart-->
            <div class="">
                <div class="breadcrumbs-box">
                    <ol class="breadcrumb">
                      <li>
                        <a href="/wxapps/auth">公众号</a>
                      </li>
                      <li class="active">支付管理</li>
                    </ol>
                </div>
                <div class="right-con">
                    <div class="row">
                                <div class="col-sm-10">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-content">
                                            <div class="mrg-b20">
                                                <h5>填写公众号支付信息</h5>
                                            </div>
                                            <form method="post" id="roleForm" class="form-horizontal nice-validator n-yellow">
                                                <div class="hr-line-dashed"></div>
                                                 <div class="form-group">
                                                    <label for="mch_id" class="col-sm-2 control-label">商户号id<span class="red-fonts">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input id="mch_id" name="mch_id" placeholder="商户号id" type="text" class="form-control" value="{$authorizer_app->result->mch_id}" aria-required="true" data-tip="商户号id">
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group">
                                                    <label for="mch_secret" class="col-sm-2 control-label">密钥secret<span class="red-fonts">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" style="display:none;" id="mch_secret" name="mch_secret" placeholder="请复制粘贴私钥" value="" type="text"/>
                                                        <label style="display:block;float:left;padding:5px 5px 0 0;" id="secret_lable">{$authorizer_app->result->mch_secret}</label>
                                                        <input type="button" value="修改" id="secret_edit" style="padding:5px;"/>

                                                        <!-- <span class="help-block m-b-none">不修改请留空</span> -->
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="form-group">
                                                {if empty($authorizer_app_verifyed)}
                                                <div style="text-align:center;">您的公众号未认证，不能使用本功能！</div>
                                                {else}
                                                    <div class="col-sm-4 col-sm-offset-2">
                                                        <button id="submit_button" class="btn btn-primary" type="button">确认保存</button>
                                                        <button class="btn btn-white" type="reset" id="reset">取消</button>
                                                    </div>
                                                {/if}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
            <!-- rightEnd -->
    </div>
</div>

<script type="text/javascript">
var appid = '{$appid}';
jQuery(document).on("click","#submit_button",function(){
    var mch_id = jQuery("#mch_id").val();
    var mch_secret = jQuery("#mch_secret").val();
    if(mch_id==""){
        alert('商户号id不能为空');
        return false;
    }
    if(mch_secret==""){
        alert('密钥secret不能为空');
        return false;
    }
    var postdata = {
        'appid':appid,
        'mch_id':mch_id,
        'mch_secret':mch_secret
    }
    jQuery.post("/wxapps/manage/set_pay_mch",postdata,function(data){
        if(data.error==1){
            alert(data.info);
            history.go(-1);

        }else{
            alert(data.info);
        }
    },"json");
    return false;
})
jQuery("#reset").click(function(){
    history.go(-1);
    return false;
})
jQuery("#secret_edit").click(function(){
    $("#secret_lable").toggle("display");
    $("#mch_secret").toggle("display");
})
</script>
{include file="_foot.tpl"}
