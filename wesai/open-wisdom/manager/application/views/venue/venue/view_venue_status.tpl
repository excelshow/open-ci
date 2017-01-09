{include file='venue/_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
	<div class="row">
		<!-- leftStart -->
		{include file='venue/_leftside.tpl'}
		<!-- leftEnt -->
		<!-- rightStart-->
		<div class="right-main">
			<div class="breadcrumbs-box">
				<ol class="breadcrumb">
					{include file="venue/_top_sub_navi.tpl"}
					<li class="active">查看预订状态</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="panel panel-blue day-statistics ">
					<div class="panel-heading">
						<h3 class="panel-title">查看预订状态</h3>
					</div>
					<div class="panel-body  event-details">
						<div class="ub venue-status-head margin30 padd110">
							<div class="f1 text-center venue-status-tit activity">场馆</div>
							<div class="f1 text-center venue-status-tit ">场地</div>
						</div>
						<div id="venue-status-box">
							<div class="venue-status-con" >
								<div class="status-filter">
									<div class="padd110">
										<div class="disInline wah200 listVenues" id="listVenues"></div>
										<div class="disInline mrl20 pull-right">
											<a href="javascript:;" id="nowadays-bay">今天</a>
											<input type="text" placeholder="请选择时间" id="inpstart" class="disInline mrl20 form-control watuo" value=""></div>
									</div>

								</div>
							</div>

							<div class="venue-status-con" style="display:none">
								<div class="status-filter">
									<div class="padd110">
										<div class="disInline wah200 listVenues" id="listSite"></div>
										<div class="disInline wah200 mrl20" id="listSiteList"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="por-r event-main" id="tableList"></div>

						<div class="event-footer" id="displayState"></div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- rightEnd -->
</div>
<!-- upload -->
<div class="dialog-bg" id="dialog" style="display:none"></div>


{literal}
<script id="listVenues-template" type="text/x-handlebars-template">
	{{#if this}}
	<select class="form-control">
		{{#each this}}
		<option value="{{venue_id}}" data-venue-area-res-id={{venue_area_res_id}}>{{name}}</option>
		{{/each}}
	</select>
	{{/if}}
</script>
<script id="table-template" type="text/x-handlebars-template">
	
	
	<div class="event-container scroll-box clearfix" >
		<div class="event-container-portrait pull-left" id="event-portrait">
		  	<ul>
		  		{{#each dateIndex}}
				  	<li class="event-tit-head">{{addKey @index}}:00</li>
				{{/each}}
		  	</ul>
		</div>
	  	<div class="scroll-con">
  		  	<ul class="event-container bg-fff">
  		  		{{#each data}}
  			  		<li class="event-item-tit">
  			  			<p>{{name}} {{#if state}}{{/if}}</p>
  			  		</li>
  		  		{{/each}}
  		  	</ul>
	  		<div class="content-inner">
	  			<ul>
	  				{{#with data}}
				  		{{#each this}}
				  			<li class="event-container-date clearfix">
						  		{{#each times}}
						  			{{#if time_start}} 
	  								<span class="event-item {{#if state}}{{#transformat state ../state}} {{/transformat}} {{/if}}" 
	  								datavenue-times-id="{{venue_area_res_times_id}}">{{price}}元</span>
	  								{{else}}
	  								<span class="event-item light-gray"></span>
						  			{{/if}}
						  		{{/each}}
				  			</li>
				  		{{/each}}
				  	{{/with}}
	  			</ul>
	  			
	  		</div>
	  	</div>
	</div>
</script>
<script id="displayState-template" type="text/x-handlebars-template">
	<div class="ub color-coded">
		{{#each this}}
			{{#verificationState @index}}
			<div class="f1 event-states">
				<div class="color-block ">
					<span class="{{className}}"></span>
				</div>
				<div class="font-tit">{{name}}</div>
			</div>
			{{/verificationState}}
		{{/each}}
	</div>
</script>
<script id="dialog-template" type="text/x-handlebars-template">
	<div class="dialog-box">
		<div class="dialog-tit">
			<h3>{{tittle}}</h3>
		</div>
		<div class="dialog-con">
			<div class="dialog-info">{{content}}</div>
			<div class="dialog-btn ub ub-ac ub-pc">
					<a href="javascript:;" class="btn confirm-bg" data-venue-times-id="{{timesId}}" data-changeType="{{changeType}}">{{confirm}}</a>
					<a href="javascript:;" class="btn cancel-bg">{{cancel}}</a>
			</div>
		</div>
	</div>
</script>

<script id="noDataShow-template" type="text/x-handlebars-template">
	<div class="no-data-show" id="noDataShow">
		暂无数据
	</div>
</script>
{/literal}
<div class="loading">
	<div class="loading-icon"></div>
</div>
<script src="{'manager/lib/plupload/plupload.full.min.js'|cdnurl}"></script>
<script src="{'manager/lib/wangEditor.min.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/iscroll-probe.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/lodash.min.js'|cdnurl}"></script>
<script>var userInfo = {$smarty.session.userInfo};</script>
{literal}
<script type="text/javascript">

$(function(){
	$(document).on('click',".venue-status-tit",function(){
		var index=$(this).index();
		$(this).addClass('activity').siblings().removeClass('activity');
		$('#venue-status-box .venue-status-con').eq(index).show().siblings().hide();
		if(index==1){
			getSiteList();
		}else if(index==0){
			updateList();
		}
		
	});
	var displayState = {
		'1':{
			name:'暂停服务',
			className:'sate-red'
		},
		'2':{
			name:'可预订',
			className:"green"
		},
		'3':{
			name:'可预订',
			className:"green"
		},
		'4':{
			name:'未使用',
			className:"gray"
		},
		'5':{
			name:'已使用',
			className:"blue"
		},
		'6':{
			name:'无人预订',
			className:"sate-yellow"
		},
		'7':{
			name:'不可预定',
			className:"sate-lightblue"
		}
	}


	var statusSale={
		'{/literal}{$smarty.const.VENUE_AREA_RES_STATE_UNSHELVE}{literal}':{
			name:"下架",
			className:"sate-lightblue"
		},
		'{/literal}{$smarty.const.VENUE_AREA_RES_STATE_SHELEVING}{literal}':{
			name:"上架中"
		},
		'{/literal}{$smarty.const.VENUE_AREA_RES_STATE_SHELVE}{literal}':{
			name:"上架",
			className:"sate-lightblue"
		},
		'{/literal}{$smarty.const.VENUE_AREA_RES_STATE_CAN_SELL}{literal}':{
			name:"可售卖"
		}
	}

	var start = {
		dateCell : '#inpstart',
		format   : 'YYYY-MM-DD',
		minDate  : '2011-06-30 23:59:59', //设定最小日期为当前日期
		// isinitVal:true,
		festival : true,
		ishmsVal : false,
		clearRestore : true,
		maxDate  : '2099-06-30 23:59:59', //最大日期
		choosefun: function (elem, datas) {
			updateList();
		},
		clearfun:function(elem, val) {
			console.log(val);
		}
	};
	jeDate(start);


	function noDataShowtemplate(){
		var noDataShow   = $("#noDataShow-template").html();
		var noDataShow = Handlebars.compile(noDataShow);
		$('#tableList').html(noDataShow); //面板清空；

	}
	function getSearchManage(){
		var params={
			corp_id:userInfo.pk_corp
		}
		ajaxgetSearchManage(params).done(function(rs) {
			var listVenues   = $("#listVenues-template").html();
			var listVenuesTemplate = Handlebars.compile(listVenues);

			$('.listVenues').html(listVenuesTemplate(rs.data));//场馆列表

			updateList(); 
		})
	}
	getSearchManage()//默认首屏加载；

	function getSiteList(){ //  根据场馆ID获取场地列表；
		var venue_id=$('#listSite select').val();
		var params={
			venue_id:venue_id
		}
		ajxa_list_by_venue(params).done(function(rs){
			var listVenues   = $("#listVenues-template").html();
			var listVenuesTemplate = Handlebars.compile(listVenues);
			$('#listSiteList').html(listVenuesTemplate(rs.data));
			getSiteStatesList();
		})
	}

	function getSiteStatesList(){ //获取场地信息
		$('.loading').show();
		var temporaryId=$('#listSiteList select').find("option:selected").attr('data-venue-area-res-id');
		if(!temporaryId){
			$('.loading').hide();
			noDataShowtemplate();
			return;
		}		
		var params={
			'venue_area_res_id':temporaryId
		}
		siteStatusDisplay(params).done(function(rs){
			renderListInformation(rs);
		});
	};

	//场地改变更新场地信息 
	$(document).on('change','#listSiteList select',function(){
		getSiteStatesList();
	})

	//场馆改变更新场地列表 
	$(document).on('change','#listSite select',function(){
		getSiteList();
	})

	//场馆改变更新场馆信息 
	$(document).on('change','#listVenues select',function(){
		updateList(); 
	});
	//获取今天的场馆信息
	$(document).on('click','#nowadays-bay',function(){
		$('#inpstart').val('');
		updateList();
	})

	// 获取所有场馆的场地信息
	function updateList(){ 
		$('.loading').show();
		var venue_id = $('#listVenues select').val();
		var day =  $('#inpstart').val() || ""; 
		var params = {
			venue_id : venue_id,
			day : day

		}
		venueStatusDisplay(params).done(function(rs) {
		    renderListInformation(rs)
		})
	}
	// 渲染场馆场地列表信息
	function renderListInformation(data){ 
			if(data.data.length < 1){
				noDataShowtemplate();
				$('.loading').hide();
				return;
			}
			var dateIndex=[];
			var source   = $("#table-template").html();
			var template = Handlebars.compile(source);

			data.data.forEach(function (item) {
			    item.times.forEach(function(item){
					dateIndex.push(parseInt(item.time_start));
				})
			});
			
			dateIndex=_.union(dateIndex,_.range(_.min(dateIndex),_.max(dateIndex)));
			dateIndex=systemSort(dateIndex);
			data.data.forEach(function (item) {
				if(item.times.length==0){
					item.times=(_.range(dateIndex.length));
					return;
				}
				var list = [];
				for(var i=0;i<dateIndex.length;i++){
					var _times = {};
					var isHave = 1;
					(function(_i){
						for(var j=0;j<item.times.length;j++){
							if ( parseInt(item.times[j].time_start) == parseInt(dateIndex[_i])) {
								_times = item.times[j];
								return isHave = 0;
							}
						}
					})(i);
					if(isHave){
						_times = '';
					}
					list.push(_times);
				}
				item.times = list;
			});

			data['dateIndex']=dateIndex;

	  		Handlebars.registerHelper("addKey",function(index){
	  			if(dateIndex[index]<10){
	  				return '0'+dateIndex[index];
	  			}else{
	  				return dateIndex[index];
	  			}
	  			
	  	  	});
  			
			Handlebars.registerHelper("transformat",function(v1,v2,option){
				if(v2 != {/literal}{$smarty.const.VENUE_AREA_RES_STATE_CAN_SELL}{literal} && (v1==1 || v1==2 || v1 ==3)){
					return statusSale[v2].className;
				}else{
					return displayState[v1].className;
				};
				
		  	});
		  	$('#tableList').html(template(data));
		    $('.loading').hide();
	}

	function displayStateTemplate(obj){
		var disStaTep  = $("#displayState-template").html();
		var disStaTep = Handlebars.compile(disStaTep);
		Handlebars.registerHelper("verificationState",function(index,option){
			if(index!=2){
				 return option.fn(this);
			}
	  	});

		$('#displayState').html(disStaTep(obj));
	}
	displayStateTemplate(displayState)

	$(document).on('click','.confirm-bg',function(){
		var timesIdnum=$(this).attr('data-venue-times-id');
		var changeType=$(this).attr('data-changeType');
		var params={
			'venue_area_res_times_ids':timesIdnum
		}
		if(changeType==3){
			ajaxPostStopTimesTimes(params).done(function(rs){
				if(rs.error=="0"){
					$(".event-item").each(function(){
						var timesId=$(this).attr('datavenue-times-id');
					   if(timesIdnum==timesId){
							$(this).addClass('sate-red').removeClass('green');
							$('#dialog').hide();
							alert('修改成功！');
					   }
					 });
					
				}else{
					alert('修改失败！');
				}
			})
		}else if(changeType==1){
			ajaxPostStartTimesTimes(params).done(function(rs){
				if(rs.error=="0"){
					$(".event-item").each(function(){
						var timesId=$(this).attr('datavenue-times-id');
					   if(timesIdnum==timesId){
							$(this).removeClass('sate-red').addClass('green');
							$('#dialog').hide();
							alert('修改成功！');
					   }
					 });
					
				}else{
					alert('修改失败！');
				}
			})
		}
			
	});

	//修改场地状态
	$(document).on('click','span.event-item',function(){
		var timesId=$(this).attr('datavenue-times-id');
		var obj={
			'confirm':'确认',
			'cancel' :'取消',
			'timesId':timesId,
		}
		$("li .event-item").each(function(){
			newtimesId=$(this).attr('datavenue-times-id');
		   if(timesId==newtimesId){
		   		if($(this).hasClass("sate-red")){
		   			obj['tittle']='设置启用服务';
		   			obj['content']='将选中场次设置为启用服务？';
		   			obj['changeType']=1;
		   		}else if($(this).hasClass("green")){
		   			obj['changeType']=3;
		   			obj['tittle']='设置暂停服务';
		   			obj['content']='将选中场次设置为暂停服务？';
		   		}else{
		   			return;
		   		}
		   		changeTimesTimes(obj);
		   		$('#dialog').show();
		   }
		 });
	});
	function changeTimesTimes(obj){
		var dialogTep  = $("#dialog-template").html();
		var dialogTep = Handlebars.compile(dialogTep);
		$('#dialog').html(dialogTep(obj));
	};
	$(document).on('click','.cancel-bg',function(){
		$('#dialog').hide();
	});

	function systemSort(array) {
	    return array.sort(function(a, b) {
	        return a - b;
	    });
	};
})
</script>
{/literal}
{include file='venue/_foot.tpl'}