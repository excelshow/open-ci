{include file='apps/_header.tpl'}
<div class="main-right">
    <div class="page-head border-bottom white-bg ">
        <h5>授权管理</h5>
        <ol class="breadcrumb">
            <li>
                <a href="/">主页</a>
            </li>
            <li>
                <a href="/">授权管理</a>
            </li>
            <li class="active">
                <strong>授权</strong>
            </li>
        </ol>
    </div>
    <div class="wrapper-content">
       <div class="row">
            <div class="col-sm-10">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>填写公众号信息</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" id="roleForm" class="form-horizontal nice-validator n-yellow" action="" novalidate="novalidate">
                            <div class="form-group">
                                <label for="role_name" class="col-sm-2 control-label">公账号名称<span class="red-fonts">*</span></label>
                                <div class="col-sm-8">
                                    <input id="role_name" name="role_name" placeholder="输入公账号名称" type="text" class="form-control" value="" aria-required="true" data-tip="输入公账号名称">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                             <div class="form-group">
                                <label for="role_name" class="col-sm-2 control-label">公账号appid<span class="red-fonts">*</span></label>
                                <div class="col-sm-8">
                                    <input id="role_name" name="role_name" placeholder="公账号appid" type="text" class="form-control" value="" aria-required="true" data-tip="公账号appid">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label for="role_key" class="col-sm-2 control-label">密钥</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="role_key" placeholder="请复制粘贴私钥" rows="10" style="font-size: 9px;"></textarea>
                                    <span class="help-block m-b-none">不修改请留空</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-white" type="reset">取消</button>
                                    <button id="submit_button" class="btn btn-primary" type="submit">确认保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="apps/_foot.tpl"}
