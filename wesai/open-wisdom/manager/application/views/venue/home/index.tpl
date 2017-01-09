{include file='venue/_header.tpl'}

<div class="loading">
	<div class="loading-icon"></div>
</div>
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
					<li class="active">统计</li>
				</ol>
			</div>
			<div class="right-con">
			<div class="panel panel-blue">
				<div class="panel-heading">
					<h3 class="panel-title">应用总概览</h3>
				</div>
				
					<div class="panel-body">
						<div class="row data-tit" id="dataSummary"></div>

						<div class="row">
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						</div>

						<div class="sub-data row" id="monthlyDataSummary">
							
						</div>
					</div>
			</div>
			<div class="panel panel-blue">
					<div class="panel-heading">
						<h3 class="panel-title">帮助中心</h3>
					</div>
					<div class="">
						<div class="help-module">
							<h4>绑定公众号步骤</h4>
							<div class="help-process">
								<div class="help-tit"><a href="/wxapps/auth"><span>1</span>公众号管理</a></div>
								<div class="help-tit"><span>2</span>点击绑定公众号</div>
								<div class="help-tit"><span>3</span>扫码授权</div>
							</div>

						</div>
						<div class="help-module">
							<h4>场馆预订步骤</h4>
							<div class="help-process">
								<div class="help-tit"><a href="/contest/contest/add_contest"><span>1</span>添加场馆</a></div>
								<div class="help-tit"><span>2</span>场馆设置场地或票</div>
								<div class="help-tit"><span>3</span>绑定公众号</div>
								<div class="help-tit"><span>4</span>上架场馆</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>
<script src="{'manager/lib/highcharts.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
{literal}
<script id="dataSummary-template" type="text/x-handlebars-template">
	<div class="col-md-4">
		<div class="data-head money-num">
			<h2 class="num">{{price}}</h2>
			<span cladd="des">总金额(元)</span>
		</div>
	</div>
	<div class="col-md-4">
		<div class="data-head people-num">
			<h2 class="num">
				{{num_order}}
			</h2>
			<span cladd="des">总预订数</span>
		</div>
	</div>
	<!-- <div class="col-md-4">
		<div class="data-head events-num">
			<h2 class="num">45</h2>
			<span cladd="des">总活动数</span>
		</div>
	</div>
	<div class="col-md-3">
		<div class="data-head items-num">
			<h2 class="num">5123</h2>
			<span cladd="des">总项目数</span>
		</div>
	</div> -->
</script>
<script id="monthlyDataSummary-template" type="text/x-handlebars-template">
	<div class="col-md-6">
		<h2 class="num">{{price}}</h2>
		<span cladd="des">月收入金额</span>
	</div>
	<div class="col-md-6">
		<h2 class="num">{{num_order}}</h2>
		<span cladd="des">月预订数</span>
	</div>
	<!-- <div class="col-md-3">
		<h2 class="num">2112</h2>
		<span cladd="des">月活动数</span>
	</div>
	<div class="col-md-3">
		<h2 class="num">5313</h2>
		<span cladd="des">月项目数</span>
	</div> -->
</script>
<script>
$(function() {
	var now = new Date(); //当前日期
	var nowDay = now.getDate(); //当前日
	var nowMonth = now.getMonth(); //当前月
	var nowYear = now.getYear(); //当前年
	nowYear += (nowYear < 2000) ? 1900 : 0; //
	//格式化日期：yyyy-MM-dd
	function formatDate(date) {
		var myyear = date.getFullYear();
		var mymonth = date.getMonth() + 1;
		var myweekday = date.getDate();

		if (mymonth < 10) {
			mymonth = "0" + mymonth;
		}
		if (myweekday < 10) {
			myweekday = "0" + myweekday;
		}
		return (myyear + "-" + mymonth + "-" + myweekday);
	}
	//获得某月的天数
	function getMonthDays(myMonth) {
		var monthStartDate = new Date(nowYear, myMonth, 1);
		var monthEndDate = new Date(nowYear, myMonth + 1, 1);
		var days = (monthEndDate - monthStartDate) / (1000 * 60 * 60 * 24);
		return days;
	}
	//今天
	var getCurrentDate = new Date(nowYear, nowMonth);
	var getCurrentDate = formatDate(getCurrentDate)
		//获得进一个月开始时间
	var getPreviousMonthDate = new Date(nowYear, nowMonth, nowDay);
	var getPreviousMonthDate = formatDate(getPreviousMonthDate)
		//获得本月的开始日期
	var getMonthStartDate = new Date(nowYear, nowMonth, 1);
	var getMonthStartDate = formatDate(getMonthStartDate);
	//获得本月的结束日期
	var getMonthEndDate = new Date(nowYear, nowMonth, getMonthDays(nowMonth));
	var getMonthEndDate = formatDate(getMonthEndDate);
	//总详情
	ajaxStatistics({
		dim: 'book'
	}).done(function(rs) {
		var dataSummaryTemplate = $("#dataSummary-template").html();
		var dataSummary = Handlebars.compile(dataSummaryTemplate);
		$('#dataSummary').html(dataSummary(rs.data[0])); //场馆列表
	});
	//月详情

	var currentDate = nowYear + '-' + (nowMonth + 1);
	ajaxStatistics({
		dim: 'book_month',
		start: currentDate,
		end: currentDate,
		type: 'month'
	}).done(function(rs) {
		var monthlyDataSummaryTemplate = $("#monthlyDataSummary-template").html();
		var monthlyDataSummary = Handlebars.compile(monthlyDataSummaryTemplate);
		$('#monthlyDataSummary').html(monthlyDataSummary(rs.data[0])); //场馆列表
	});
	//月列表
	ajaxStatistics({
		dim: 'book_venue_day',
		start: getMonthStartDate,
		end: getMonthEndDate,
		type: 'day'
	}).done(function(rs) {
		monthlyVenueDataChart(rs);
	});

	
	function monthlyVenueDataChart(rs) {
		var dataTitle = [];
		var dataInfo = [];
		var data = rs.data;
		for (var i in data) {
			dataTitle.push(data[i].day);
			dataInfo.push(Number(data[i].price));
		}
		var credits = {
			enabled: false
		}
		var title = {
			text: '本月收入'
		};
		var subtitle = {
			text: ''
		};
		var xAxis = {
			categories: dataTitle
		};
		var yAxis = {
			title: {
				text: '单位 (元)'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		};
		var tooltip = {
			valueSuffix: '元'
		}
		var legend = {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		};
		var series = [{
				name: '收入金额',
				data: dataInfo
			}
		];
		var json = {};
		json.credits = credits;
		json.title = title;
		json.subtitle = subtitle;
		json.xAxis = xAxis;
		json.yAxis = yAxis;
		json.tooltip = tooltip;
		json.legend = legend;
		json.series = series;
		$('#container').highcharts(json);
	}
	$('.loading').hide();

})
</script>
{/literal}

{include file='venue/_foot.tpl'}

