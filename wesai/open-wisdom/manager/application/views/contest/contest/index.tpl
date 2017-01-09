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
					<li class="active">活动管理</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue">
					<div class="panel-heading">
						<h3 class="panel-title">活动筛选</h3>
					</div>
					<div class="panel-body padd15">
						<div class="filter-condition">
							<div class="filter-category clearfix">
								<div class="dt">类型:</div>
								<div class="dd">
									<a class="link_gtype {if empty($gtype)}link_state_color{/if}" data-parmeter="" href="javascript:;">全部</a>
									<a class="link_gtype {if $gtype == 1 }link_state_color{/if}" data-parmeter="gtype=1" href="javascript:;">其他</a>
									<a class="link_gtype {if $gtype == 2 }link_state_color{/if}" data-parmeter="gtype=2" href="javascript:;">马拉松</a>

								</div>
							</div>
							<div class="filter-status clearfix">
								<div class="dt">上架状态:</div>
								<div class="dd">
									<a class="link_gtype {if empty($state)}link_state_color{/if}" data-parmeter="" href="javascript:;">全部</a>
									<a class="link_gtype {if $state == 1 }link_state_color{/if}" data-parmeter="state=1" href="javascript:;">暂存</a>
									<a class="link_gtype {if $state == 2 }link_state_color{/if}" data-parmeter="state=2" href="javascript:;">上架</a>
									<a class="link_gtype {if $state == 3 }link_state_color{/if}" data-parmeter="state=3" href="javascript:;">报名中</a>
									<a class="link_gtype {if $state == 4 }link_state_color{/if}" data-parmeter="state=4" href="javascript:;">下架</a>
								</div>
							</div>
							<div class="filter-date clearfix">
								<div class="dt">日期:</div>
								<div class="dd">
									<a class="link_gtype {if empty($dateType)}link_state_color{/if}" data-parmeter="" href="javascript:;">全部</a>
									<a class="link_gtype link_date_type {if $datePeriod == 1 }link_state_color{/if}
                                      " data-parmeter="date_type=1&date_period=1" href="javascript:;">当日</a>
									<a class="link_gtype link_date_type {if $datePeriod == 2 }link_state_color{/if}" data-parmeter="date_type=1&date_period=2" href="javascript:;">近一周</a>
									<a class="link_gtype link_date_type {if $datePeriod == 3 }link_state_color{/if}" data-parmeter="date_type=1&date_period=3" href="javascript:;">近一月</a>
									<div class="choose-date">
										<input type="text" id="inpstart" class="pull-left"
										       value="{if !empty($dateStart)}{$dateStart}{/if}">
										<!-- <span class="horizontal-line"></span> -->
										<input type="text" id="inpend"
										       value="{if !empty($dateEnd)}{$dateEnd}{/if}" class="pull-right">
									</div>
									<a class="search-confirm date-btn {if $dateType == 2}link_state_color link_gtype date-btn-bg{/if}" id="dateSearch"
									   href="javascript:;"
									   data-parmeter="date_type=2&date_start={$dateStart}&date_end={$dateEnd}">确认</a>
								</div>
							</div>
							<div class="filter-search clearfix">
								<div class="dt">搜索:</div>
								<div class="dd">
									<input type="text" class="search_text" value="{$name}">
									<a class="search-confirm {if !empty($name)}link_state_color link_gtype date-btn-bg{/if}" id="searchText" {if !empty($name)}data-parmeter="name={$name}"{/if} href="javascript:;" data-parmeter="name=""
									>确认</a>
									<a class="link_state_color" href="/contest/contest/index" data-parmeter="">条件清空</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix addbutton-box">
					<a class="pull-right add-button" href="/contest/contest/add_contest">创建新活动</a>
                    {if $is_softykt_corp}
					<a class="pull-right add-button" style="margin-right:10px;" href="javascript:void(0)" onclick="return import_softykt_product();">同步金飞鹰产品</a>
                    <span class="pull-right" style="margin:10px;" id="softykt_import_info"></span>
                    {/if}
				</div>
				<div class="table-result  day-statistics">
					<table class="table">
						<thead>
						<tr>
							<th>活动ID</th>
							<th>类别</th>
							<th style="width:260px;overflow: hidden;text-align:left;">活动资料</th>
							<th>上架状态</th>
							<th>操作</th>
						</tr>
						</thead>
						<tbody>
						{if !empty($data)}
							{foreach from=$data item=item}
								<tr>
									<td class="text-center">{$item->pk_contest}</td>
									<td>{$CONTEST_GTYPE_LIST[$item->gtype]}</td>
									<td class="event-info">
										<div class="pull-left thumpic">
											<img src='{RES_FILEVIEW_URL}/{$item->
											logo}?imageMogr2/thumbnail/82x82' data-src='{RES_FILEVIEW_URL}/{$item->logo}'>
										</div>
										<div class="contest-info">
											<h5>
												<a href="/contest/contest/detail_contest?cid={$item->pk_contest}">{$item->name}</a>
											</h5>
											<p class="line-text">地点：
											{if !empty($item->locationData[1])}{$item->locationData[1]}{/if}
											{if !empty($item->locationData[2])}{$item->locationData[2]}{/if}
											{if !empty($item->locationData[3])}{$item->locationData[3]}{/if}
											{if !empty($item->locationData[4])}{$item->locationData[4]}{/if}
											{$item->location}</p>
											<p>时间：{$item->sdate_start} - {$item->sdate_end}</p>
										</div>
									</td>
									<td class="txt-cen sale-states">
										{if $item->publish_state==1}
											<strong class="txt-scratch">{$CONTEST_STATE_LIST[$item->publish_state]}</strong>
										{elseif $item->publish_state==2}
											<strong class="txt-added">{$CONTEST_STATE_LIST[$item->publish_state]}</strong>
										{elseif $item->publish_state==3}
											<strong class="txt-sellPin">{$CONTEST_STATE_LIST[$item->publish_state]}</strong>
										{elseif $item->publish_state==4}
											<strong class="txt-shelves">{$CONTEST_STATE_LIST[$item->publish_state]}</strong>
										{/if}
									</td>
									<td class="edit txt-cen">
										{if $item->publish_state != 3}
										<a href="/contest/contest/detail_contest?cid={$item->pk_contest}&intro=1" class="seedetails-btn">编辑</a>
										{/if}
										{if $item->publish_state == CONTEST_PUBLISH_STATE_DRAFT}
											<a href="javascript:void(0)" onclick="return changeContestPublishState({$item->pk_contest},'ajax_online')" class="seedetails-btn">上架</a>
										{elseif $item->publish_state == CONTEST_PUBLISH_STATE_ON}
											<a href="javascript:void(0)" onclick="return changeContestPublishState({$item->pk_contest},'ajax_start_selling')" class="seedetails-btn">开始报名</a>
										{elseif $item->publish_state == CONTEST_PUBLISH_STATE_SELLING}
											<a href="javascript:void(0)" onclick="return changeContestPublishState({$item->pk_contest},'ajax_offline')" class="seedetails-btn">下架</a>
										{elseif $item->publish_state == CONTEST_PUBLISH_STATE_OFF}
											<a href="javascript:void(0)" onclick="return changeContestPublishState({$item->pk_contest},'ajax_re_online')" class="seedetails-btn">重新上架</a>
										{/if}
									</td>
								</tr>
							{/foreach}
						{else}
						<tr style="height:50px;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							{*<td>*}
							{*<a href="#" class="seedetails-btn">查看</a>*}
							{*</td>*}
						</tr>
						{/if}
						</tbody>
					</table>
					<!-- 翻页 -->
					<nav>{$page_ctrl}</nav>
				</div>
			</div>
		</div>
		<!-- rightEnd --> </div>
</div>
{include file="_foot.tpl"}

<script>
	$(function () {
		function parameter() {
			var parameters = "";
			$('.filter-condition a.link_state_color').each(function () {
				var link = $(this).attr('data-parmeter') + "&";
				if (link !== "&") {
					parameters += link;
				}
			});
			var parameters = parameters.substring(0, parameters.length - 1);
			return parameters;
		}

		var Url = '/contest/contest/index?';
		$('.filter-condition').on('click','a.link_gtype', function () {
			var that = $(this);
			that.addClass("link_state_color").siblings().removeClass('link_state_color');
			var parameters       = parameter();
			window.location.href = Url + parameters;
		});
		$(".search_text").on('input', function () {
			var _val = $(this).val();
			$('#searchText').attr('data-parmeter', "name=" + _val + "").addClass('date-btn-bg link_gtype link_state_color');
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
			var dataParmeter = 'date_type=2&date_start=' + datas + '&date_end=' + inpend;
			$('#dateSearch').attr('data-parmeter', dataParmeter);
		},
		clearfun:function(elem, val) {
			var inpend     = $("#inpend").val();
			var dataParmeter = 'date_type=2&date_start=&date_end=' + inpend;
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
			var dataParmeter = 'date_type=2&date_start=' + inpstart + '&date_end=' + datas;
			$('#dateSearch').attr('data-parmeter', dataParmeter);

		},
		clearfun:function(elem, val) {
			$('#dateSearch').addClass('link_state_color').removeClass(' date-btn-bg link_gtype');
			
		}
	};
	jeDate(start);
	jeDate(end);

	jQuery(function () {
		var POP = $('<div class="popShow2"><div class="popCon2"></div><div class="popBg2"></div><div class="popClose2" title="关闭">╳</div></div>')
		$('body').append(POP)

		var wH = $(document).height();
		var wW = $(document).width();
		$('.popBg2').height(wH).width(wW);

		var currdomain = '{$smarty.const.RES_FILEVIEW_URL}' || 'files.wesai.com';
		jQuery("img").on("click", function () {
			var imgsrc = jQuery(this).attr("data-src");
			if (imgsrc.indexOf(currdomain) > 0) {
				$('.popShow2').show();
				$('.popCon2').append('<img src="' + imgsrc + '" height="90%">');
				return false;
			}
		});
		$('.popClose2').click(function () {
			$('.popShow2').hide();
			$('.popCon2').find('img,video,audio').remove();
		});
		$('.popCon2').click(function () {
			$('.popShow2').hide();
			$('.popCon2').find('img,video,audio').remove();
		});
	});

    function import_softykt_product(){
        $("#softykt_import_info").html("金飞鹰产品同步中...");
        $.ajax({
            url:'/contest/product/index',
            type:'GET', //GET
            async:true,    //或false,是否异步
            data:{
            },
            timeout:30000,    //超时时间
            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
            success:function(data){
            console.log(data);
                if(data.error != 0){
                    $("#softykt_import_info").html("金飞鹰产品同步失败");
                }else{
                    var info = "产品总数:"+data.result.all_number;
                    info += " 新增:"+data.result.syn_number;
                    info += " 系统已存在:"+data.result.exits_number;
                    $("#softykt_import_info").html(info);
                }
            }
        });
    }
</script>
