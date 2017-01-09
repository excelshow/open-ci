{include file='_header.tpl'}
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
					<li class="active">订单管理</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue">
					<div class="panel-heading">
						<h3 class="panel-title">订单筛选</h3>
					</div>
					<div class="panel-body padd15">
						<div class="filter-condition">
							<div class="filter-status clearfix">
								<div class="dt">订单状态:</div>
								<div class="dd">
									<a class="link_gtype {if empty($state)}link_state_color{/if}" data-parmeter="" href="javascript:;">全部</a>
									{foreach from=$ORDER_PAY_SATATELIST key=key item=item}
										<a class="link_gtype {if $key == $state }link_state_color{/if}" data-parmeter="state={$key}" href="javascript:;">{$item}</a>
									{/foreach}
								</div>
							</div>
							<div class="filter-date clearfix">
								<div class="dt">日期:</div>
								<div class="dd">
									<a class="link_gtype {if empty($dateType)}link_state_color{/if}" data-parmeter="" href="javascript:;">全部</a>
									<a class="link_gtype {if $datePeriod == 1 }link_state_color{/if}
	                                      " data-parmeter="date_type=1&date_period=1" href="javascript:;">当日</a>
									<a class="link_gtype {if $datePeriod == 2 }link_state_color{/if}" data-parmeter="date_type=1&date_period=2" href="javascript:;">近一周</a>
									<a class="link_gtype {if $datePeriod == 3 }link_state_color{/if}" data-parmeter="date_type=1&date_period=3" href="javascript:;">近一月</a>
									<div class="choose-date">
										<input type="text" id="inpstart" class="pull-left"
										       value="{if !empty($dateStart)}{$dateStart}{/if}">
										<!-- <span class="horizontal-line"></span> -->
										<input type="text" id="inpend"
										       value="{if !empty($dateEnd)}{$dateEnd}{/if}" class="pull-right">
									</div>
									<a class="search-confirm date-btn {if $dateType == 2}link_state_color link_gtype date-btn-bg{/if}" id="dateSearch"
									   href="javascript:;"
									   data-parmeter="date_type=2&dateStart={$dateStart}&dateEnd={$dateEnd}">确认</a>
								</div>
							</div>
							<div class="filter-search clearfix">
								<div class="dt">搜索:</div>
								<div class="dd">
									<input type="text" class="search_text" value="{$keyword}" placeholder="订单号/流水号">
									<a class="search-confirm {if !empty($keyword)}link_state_color link_gtype date-btn-bg{/if}" id="searchText" {if !empty($keyword)}data-parmeter="keyword={$keyword}"{/if} href="javascript:;" data-parmeter="keyword=">确认</a>
									<a class="link_state_color" href="/contest/order/list_order" data-parmeter="">条件清空</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="day-statistics">
					<table class="table txt-cen">
						<thead>
						<tr>
							<th>订单ID</th>
							<th>活动名称</th>
							<th>订单金额(元)</th>
							<th>抵扣金额(元)</th>
							<th>实付金额(元)</th>
							<th>流水号</th>
							<th>创建时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						{if !empty($orderList)}
							{foreach from=$orderList item=item}
								<tr>
									<td>
										<a href="/contest/order/detail_order?oid={$item->pk_order}&keyword={$item->contest_info->name}">{$item->pk_order}</a>
									</td>
									<td>{$item->contest_info->name}</td>
									<td>{($item->amount/100)|string_format:"%.2f"}</td>
									{if $item->state != $smarty.const.ORDER_STATE_CLOSED}
										{assign var='amount_pay' value=$item->amount}
									{else}
										{assign var='amount_pay' value=$item->amount_pay}
									{/if}
									<td>{(($item->amount-$amount_pay)/100)|string_format:"%.2f"}</td>
									<td>{($amount_pay/100)|string_format:"%.2f"}</td>
									<td>{$item->channel_transaction_id}</td>
									<td>{$item->ctime}</td>

									<td class="order{$item->state}">{$ORDER_PAY_SATATELIST[$item->state]}</td>

									<td>
										<a href="/contest/order/detail_order?oid={$item->pk_order}&keyword={$item->contest_info->name}" class="seedetails-btn">查看</a>
									</td>
								</tr>
							{/foreach}
						{else}
							<tr style="height:50px;">
								<th></th>
								{*<th></th>*}
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						{/if}
						</tbody>
					</table>
					<!-- 翻页 -->
					<nav>
						{$page_ctrl}
					</nav>
				</div>
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>
{include file="_foot.tpl"}

<script>
	$(function () {
		function parameter() {
			var parameter = "";
			$('.filter-condition a.link_state_color').each(function () {
				var link = $(this).attr('data-parmeter') + "&";
				if (link !== "&") {
					parameter += link;
				}
			});
			return parameter.substring(0, parameter.length - 1);
		}

		var Url = '/contest/order/list_order?';
		$('.filter-condition').on('click', 'a.link_gtype',function () {
			var that = $(this);
			that.addClass("link_state_color").siblings().removeClass('link_state_color');
			var parameters=parameter();
			window.location.href=Url+parameters;
		});
		$(".search_text").on('input', function () {
			var _val = $(this).val();
			$('#searchText').attr('data-parmeter', "keyword=" + _val + "").addClass('date-btn-bg link_gtype');
		});
	});

</script>
<script>
	var start = {
		dateCell : '#inpstart',
		format   : 'YYYY-MM-DD',
		minDate  : '2011-06-30 23:59:59', //设定最小日期为当前日期
		// isinitVal:true,
		festival : true,
		ishmsVal : false,
		maxDate  : '2099-06-30 23:59:59', //最大日期
		choosefun: function (elem, datas) {
			end.minDate = datas; //开始日选好后，重置结束日的最小日期
			// $('#dateSearch').addClass('date-btn-bg').siblings().removeClass('link_state_color');
			// $('#inpend').val(datas);
			var inpend     = $("#inpend").val();
			var inpendTime = (new Date(inpend)).getTime(); //得到毫秒数
			var startTime = (new Date(datas)).getTime(); //得到毫秒数
			if(startTime>inpendTime){
				alert("开始时间不能大于结束时间！！")
				$('#inpstart').val("");
				return false;
			}
			var dataParmeter = 'date_type=2&dateStart=' + datas + '&dateEnd=' + inpend;
			$('#dateSearch').attr('data-parmeter', dataParmeter);
		},
		clearfun:function(elem, val) {
			var inpend     = $("#inpend").val();
			var dataParmeter = 'date_type=2&dateStart=&dateEnd=' + inpend;
			$('#dateSearch').attr('data-parmeter', dataParmeter);
		}
	};
	var end   = {
		dateCell : '#inpend',
		format   : 'YYYY-MM-DD',
		minDate  : jeDate.now(0), //设定最小日期为当前日期
		festival : true,
		maxDate  : '2099-06-16 23:59:59', //最大日期
		choosefun: function (elem, datas) {
			$('#dateSearch').addClass('date-btn-bg link_gtype').siblings().removeClass('link_state_color');
			var inpstart     = $("#inpstart").val();
			var dataParmeter = 'date_type=2&dateStart=' + inpstart + '&dateEnd=' + datas;
			$('#dateSearch').attr('data-parmeter', dataParmeter);

		},
		clearfun:function(elem, val) {
			$('#dateSearch').addClass('link_state_color').removeClass(' date-btn-bg link_gtype');
		}
	};
	jeDate(start);
	jeDate(end);
</script>
