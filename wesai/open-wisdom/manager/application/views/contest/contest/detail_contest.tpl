	{include file='_header.tpl'}
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
					<li>
						<a href="/contest/contest/index">活动管理</a>
					</li>
					<li class="active">活动详情</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue day-statistics ">
					
					<ul class="clearfix tab-haed" >
						<li class="panel-title pull-left activity eventdetails" rols="eventdetails"><a href="javascript:;">活动详情</a></li>
						<li class="panel-title pull-left listitems" rols="listitems"><a href="javascript:;">项目列表</a></li>
					</ul>
					<div class="tab-con">
						<div class="panel-body  event-details">
						{if !empty($data)}
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">活动ID:</div>
								<div class="dd">
									<p>{$data->pk_contest}</p>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">客服电话:</div>
								<div class="dd">
									<p>{$data->service_tel}</p>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">活动类型:</div>
								<div class="dd">
									<p>
										{foreach from=$CONTEST_GTYPE_LIST key=key item=item}
											{if $data->gtype == $key}{$item}{/if}
										{/foreach}
									</p>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">活动名称:</div>
								<div class="dd">
									<p>{$data->name}</p>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">比赛时间:</div>
								<div class="dd">
									<p>{$data->sdate_start} — {$data->sdate_end}</p>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">比赛地址:</div>
								<div class="dd">
								{if !empty($data)}
									{if $data->country_scope=='2'}
										<p>{$data->location}</p>
									{else}
										<p>
											中国
											<span>{if !empty($location)} {$location[2]} {/if}</span>
											<span>{if !empty($location)} {$location[3]} {/if}</span>
											<span>{if !empty($location[4])} {$location[4]} {/if}</span>
											<span>{$data->location}</span>
										</p>
									{/if}
								{/if}
								</div>
							</div>
							{*<div class="filter-date clearfix col-sm-12">*}
								{*<div class="dt">是否需要邮寄装备:</div>*}
								{*<div class="dd">*}
									{*<p>*}
										{*{if $data->deliver_gear == 1} 需要邮寄装备{/if}*}
										{*{if $data->deliver_gear == 2} 无需邮寄装备{/if}*}
									{*</p>*}
								{*</div>*}
							{*</div>*}
							<div class="filter-date-img clearfix col-sm-12">
								<div class="dt">活动logo:</div>
								<div class="dd logo-img">
									<div class="img-thump">
										<img src='{RES_FILEVIEW_URL}/{$data->logo}'></div>
								</div>
							</div>
							<div class="filter-date-img clearfix col-sm-12">
								<div class="dt">活动海报图:</div>
								<div class="dd poster-img">
									<div class="img-thump">
										<img src='{RES_FILEVIEW_URL}/{$data->poster}'>
									</div>
								</div>
							</div>
							<div class="filter-date-img clearfix col-sm-12">
								<div class="dt">活动横幅图:</div>
								<div class="dd banner-img">
									<div class="img-thump">
										<img src='{RES_FILEVIEW_URL}/{$data->banner}'>
									</div>
								</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">活动详情:</div>
								<div class="dd">{$data->intro}</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">页面模板:</div>
								<div class="dd">{if $data->template == 1}标准报名模板{elseif $data->template == 2}购票模板{/if}</div>
							</div>
							<div class="filter-date clearfix col-sm-12">
								<div class="dt">是否显示已售数目:</div>
								<div class="dd">{if $data->show_enrol_data_count == 1}是{elseif $data->show_enrol_data_count == 2}否{/if}</div>
							</div>
							<!--列表 -->
							<div class="filter-date clearfix col-sm-12 edit-btn-box">
								<a href="/contest/contest/edit?cid={$data->pk_contest}" class="btn add-button">编辑活动</a>
							</div>
						{/if}
					</div>
					<div class="panel-body  event-details" style="display:none">
						{if $itemEditEnable}
						<div class="clearfix addbutton-box">
							<a class="pull-right add-button" href="/contest/contest/add_item#{$data->pk_contest}">创建项目</a>
						</div>
						{/if}
						{include file='../contest/_itemlist.tpl'}
					</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<!-- rightEnd -->
</div>
<script>
	$(function(){
		$('.tab-haed li').on('click',function(){
			tab($(this));
		})
		function tab(that){
			var index=that.index();
			var rols=that.attr('rols');
			window.location.hash=rols;
			that.addClass('activity').siblings().removeClass('activity');
			$('.right-con .event-details').eq(index).show().siblings().hide();
		}
		function Urlpar(){
			var _urlPar=(window.location.hash);
			if(_urlPar=="#eventdetails"){
				$(".eventdetails").trigger("click");
			}else if(_urlPar=="#listitems"){
				$(".listitems").trigger("click");
			}
		}
		Urlpar();
		window.onpopstate = function () {
	      	Urlpar();
	    };
	    $('.mission-on').hide();
	    $('.tab-tit .item').on('click',function(){
	    	tab_con($(this));
	    })
	    function tab_con(_that){
	    	var defaultUrl=window.location.hash;
	    	_that.addClass('activity').siblings().removeClass('activity');
	    	var index=_that.index();
    		var rols=_that.attr('rols');
    		localStorage.setItem("tab_name",rols);
	    	if(index==0){
	    		$('.personal').show();
	    		$('.mission-on').hide();
	    	}else{
	    		$('.personal').hide();
	    		$('.mission-on').show();
	    	};
	    }
    	var tab_name = localStorage.getItem("tab_name");
	    if(tab_name == 'team_registration'){
	    	$('.team_registration').trigger("click");
	    }else{
	    	$(".single_registration").trigger("click");
	    }
	})
</script>
{include file='_foot.tpl'}

