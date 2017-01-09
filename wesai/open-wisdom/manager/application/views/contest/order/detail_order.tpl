
{include file='_header.tpl'}
<script src="{'manager_contest/js/template.js'|cdnurl}"></script>
<script src="{'manager_contest/js/formdata.list.js'|cdnurl}"></script>
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file="_leftside.tpl"}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="../../_top_sub_navi.tpl"}
                    {include file="../../_top_two_nav.tpl"}
                    <li>
                        <a href="/contest/order/list_order">订单管理</a>
                    </li>
                    <li class="active">查看活动</li>
                </ol>
            </div>
            <div class="right-con">

                <div class="panel panel-blue">
                    <div class="panel-heading">
                        <h3 class="panel-title">订单详情</h3>
                    </div>
                    <table class="table">
                        <tr>
                            <td> <strong>订单ID：</strong>
                                <span>{$orderInfo->result->pk_order}</span>
                            </td>
                            <td> <strong>活动名称：</strong>
                                <span>{$contest_name}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>支付状态：</strong>
                                <span>{$ORDER_PAY_SATATELIST[$orderInfo->result->state]}</span>
                            </td>
	                        <td>
		                        <strong>报名方式：</strong>
		                        <span>
                                    {if $orderInfo->result->type =='1'}个人报名 {elseif $orderInfo->result->type =='2'} 小组报名 {elseif  $orderInfo->result->type =='3'} 团队报名{/if}
                                </span>
	                        </td>
                        </tr>
	                    <tr>
		                    <td>
			                    <strong>支付方式：</strong>
			                    <span>{if $orderInfo->result->channel_id =='1'}微信{/if}</span>
		                    </td>
		                    <td>
			                    <strong>订单金额：</strong>
			                    <span>{($orderInfo->result->amount/100)|string_format:"%.2f"} 元</span>
		                    </td>
	                    </tr>
	                    {if $orderInfo->result->state != $smarty.const.ORDER_STATE_CLOSED}
		                    {assign var='amount_pay' value=$orderInfo->result->amount}
	                    {else}
		                    {assign var='amount_pay' value=$orderInfo->result->amount_pay}
	                    {/if}
                        <tr>
                            <td>
                                <strong>抵扣金额：</strong>
                                <span>{(($orderInfo->result->amount-$amount_pay)/100)|string_format:"%.2f"}元</span>
                            </td>
	                        <td>
		                        <strong>实付金额：</strong>
		                        <span>{($amount_pay/100)|string_format:"%.2f"} 元</span>
	                        </td>
                        </tr>
                        {if !empty($orderInfo->result->shipping_addr)}
                            {if $orderInfo->result->shipping_addr !== "null" }
                        <tr>
                            <td>
                                <strong>收货地址：</strong>
                                <span>{$orderInfo->result->shipping_addr}</span>
                            </td>
                            <td></td>
                        </tr>
                        {/if}
                        {/if}
                    </table>
                </div>
                <div class="panel panel-blue">
                    {if $orderInfo->result->type =='3'}
                        <div class="enrollistbox" id="teamlistbox"></div>
                    {elseif $orderInfo->result->type =='1'}
                        <div class="enrollistbox" id="enrollistbox"></div>
                    {elseif $orderInfo->result->type =='2'}
                        {if !empty(group_idInfo)}
                        <div class="panel-heading">
                            <h3 class="panel-title">小组信息</h3>
                        </div>
                        <table class="table panel" style="margin-bottom: 0;border-bottom: 1px solid #ccc;">
                            <tbody>
                                <tr>
                                    <td class="text-left"> <strong>小组名称:</strong>
                                        <span>{$group_idInfo->name}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <strong>组长姓名:</strong>
                                        <span>{$group_idInfo->leader_name}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <strong>组长电话:</strong>
                                        <span>{$group_idInfo->leader_contact}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <strong>报名人数:</strong>
                                        <span>{$group_idInfo->cur_member_count}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {/if}
                        <div class="enrollistbox" id="manylistbox"></div>
                    {/if}
                </div>
                {literal}
                <!-- 个人报名模板 -->
                <script id="enrollist" type="text/html">
                    <table class="table" style="margin-bottom:0;">
                        {{each list as itemobj i}}
                            <div class="panel-heading">
                                <h3 class="panel-title">项目名称：{{itemobj.name}}</h3>
                            </div>
                            {{each itemobj.enrol_data as itemI j}}
                                {{each itemI.enrol_data_detail as item g}}
                                    <tr>
                                        <td>
                                            <strong>{{item.title}} :</strong>
                                            {{if item.type =="uploadfile"}}
                                                <span>{{item.value | fileFormat}}</span>
                                            {{else}}
                                                <span>{{item.value}}</span>
                                            {{/if}}
                                        </td>
                                    </tr>
                                {{/each}}
                              {{/each}}
                        {{/each}}
                    </table>
                </script>
                <!-- 小组报名模板 -->
                <script id="manylistboxTem" type="text/html">
                    {{each list as itemobj i}}
                    <div class="panel-heading">
                        <h3 class="panel-title">项目名称：{{itemobj.name}}</h3>
                    </div>
                    <table class="table txt-cen">
                        <thead>
                            <tr>
                                    {{each itemobj.enrol_form_titles as itemI j}}
                                        <th>{{itemI}}</th>
                                    {{/each}}
                            </tr>
                        </thead>
                        <tbody>
                                {{each itemobj.enrol_data as itemI j}}
                                <tr>
                                    {{each itemI.enrol_data_detail as item g}}
                                        {{if item.type =="uploadfile"}}
                                        <td>{{item.value | fileFormat}}</td>
                                        {{else}}
                                        <td>{{item.value}}</td>
                                        {{/if}}
                                    {{/each}}
                                </tr>
                                {{/each}}
                        </tbody>
                    </table>
                    {{/each}}
                </script>
                <!-- 团队报名模板 -->
                <script id="teamlistboxTem" type="text/html">
                    <table class="table panel" style="margin-bottom: 0;border-bottom: 1px solid #ccc;">
                        <tbody>
                            <tr>
                                <td class="text-left"> <strong>团队名称:</strong>
                                    <span>{{teaminfo.name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td> <strong>团长姓名:</strong>
                                    <span>{{teaminfo.leader_name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td> <strong>团长电话:</strong>
                                    <span>{{teaminfo.leader_contact}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td> <strong>报名人数:</strong>
                                    <span>{{teaminfo.cur_member_count}}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table txt-cen day-statistics" style="margin-bottom:0;">
                        {{each list as itemobj i}}
                            <div class="panel-heading">
                                <h3 class="panel-title">项目名称：{{itemobj.name}}</h3>
                            </div>
                        {{/each}}
                        <thead>
                            <tr>
	                            <th width="5%">编号</th>
                                {{each list as itemobj i}}
                                    {{each itemobj.enrol_form_titles as itemI j}}
                                        <th>{{itemI}}</th>
                                    {{/each}}
                                {{/each}}
                            </tr>
                        </thead>
                        <tbody>
                            {{each list as itemobj i}}
                                {{each itemobj.enrol_data as itemI j}}
                                    <tr>
	                                    <td>{{itemI.pk_enrol_data}}</td>
                                        {{each itemI.enrol_data_detail as item g}}
                                           <td>{{item.value}}</td>
                                        {{/each}}
                                    </tr>
                                {{/each}}
                            {{/each}}
                        </tbody>
                    </table>
                </script>
                {/literal}
            </div>
        </div>
        <!-- rightEnd --> </div>
</div>
<script type="text/javascript">
  template.helper('fileFormat', function (filename) {
    var filename = filename;
    return "<img src='http://img.wesai.com/" + filename + "' width='50' height='50'/>";

  });
  //订单列表

  var enroldata = {
    "list": {$orderInfo->result->contest_item_list|json_encode}
  }
  var obj_html  = template('enrollist', enroldata);
  $("#enrollistbox").html(obj_html);



  var manylistbox  = template('manylistboxTem', enroldata);
  $("#manylistbox").html(manylistbox);
  var team_id=enroldata.list[0].enrol_data[0].fk_team;
  if(team_id){
    var postinfo={
        team_id:team_id
    }
    $.get("/contest/order/ajax_team",postinfo,function(data){
       if(data.error ==0){
        debugger;
            enroldata['teaminfo']=data.result;
            var teamlistboxTem  = template('teamlistboxTem', enroldata);
            $("#teamlistbox").html(teamlistboxTem);
            return false;
       }else{
         alert(data.info);
       }
    },"json")
  }


</script>
{include file='_foot.tpl'}
