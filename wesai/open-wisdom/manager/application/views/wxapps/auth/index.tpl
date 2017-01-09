{include file='_header.tpl'}
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
            <!-- rightStart-->
            <div class="">
                <div class="breadcrumbs-box">
                    <ol class="breadcrumb">
                      <li class="active">公众号</li>
                    </ol>
                </div>
                <div class="right-con">
                    <div class="panel panel-blue">
                       <div class="panel-heading">
                          <h3 class="panel-title">帮助中心</h3>
                       </div>
                       <div class="">
                            <div class="help-module" >
                                <h4>绑定公众号</h4>
                                <div class="help-process" >
                                    <div class="help-tit"><span>1</span>点击绑定公众号</div>
                                    <div class="help-tit"><span>2</span>使用微信扫描二维码</div>
                                    <div class="help-tit"><span>3</span>微信授权</div>
                                    <div class="help-tit"><span>4</span>页面成功跳转</div>
                                </div>
                            </div>

                       </div>
                       <div class="day-statistics">
                            <div class="clearfix addbutton-box public-box">
                              <a class="pull-right add-button" href="http://{BASE_DOMAIN}/{_MENU_WXAPPS_DIR_}/auth/toAuth">绑定公众号</a>
                            </div>
                            <table class="table txt-cen">
                               <thead>
                                    <tr>
                                        <th class="text-center">公众号名称 </th>
                                       <th class="text-center">appid</th>
                                       <th class="text-center">授权状态</th>
                                       <th class="text-center">创建时间</th>
                                       <th class="text-center">操作</th>
                                    </tr>
                               </thead>
                               <tbody>
                                    {if !empty($apps)}
                                    {foreach from=$apps item=app}
                                        <tr class="gradeX">
                                            <td>{$app.nick_name}</td>
                                            <td>{$app.authorizer_appid}</td>
                                            <td>{if empty($app.state_auth)}未授权{else}已授权{/if}</td>
                                            <td>{$app.ctime}</td>
                                            <td>
                                                <a href="http://{BASE_DOMAIN}/{_MENU_WXAPPS_DIR_}/manage/menu?appid={$app.authorizer_appid}#/wxapps/auth" class="seedetails-btn">菜单管理</a>
                                                <a href="http://{BASE_DOMAIN}/{_MENU_WXAPPS_DIR_}/manage/pay_mch?appid={$app.authorizer_appid}#/wxapps/auth" class="seedetails-btn">支付管理</a>
                                                <!-- <a href="javascript:;" onclick="" class="seedetails-btn">删除</a> -->
                                            </td>
                                        </tr>
                                    {/foreach}
                                    {/if}
                               </tbody>

                            </table>
                       </div>
                    </div>
                </div>
            </div>
            <!-- rightEnd -->
    </div>
</div>
{include file="_foot.tpl"}
