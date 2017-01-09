<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<title>微赛报名手机预览</title>
	<meta name="keywords" content="微赛">
	<meta name="description" content="活动报名">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="icon" href="favicon.ico">
	<link rel="stylesheet" href="http://static.wesai.com/front_contest_static/css/main.css">
</head>
<body>
<!-- 赛事详情 -->
<div class="wrap-content gray_bg">
	<div class="head-top white_bg">
		<div class="pannel-img">
			<img src="http://img.wesai.com/{$data->banner}">
		</div>
		<div class="pannerl-info">
			<h3 class="m-title"><s>{$data->name}</s>
				<span class="state-span  nomal-font s3">报名中</span>
			</h3>
			<div class="icon-list">
				{foreach from=$NEWCONTEST_LEVEL_LIST key=Lkey item=Litem}
					{if ($data->level & $Lkey) == true}
						<p><span class="icon-span icon{$Lkey}">{$Litem[1]}</span>{$Litem[0]}</p>
					{/if}
				{/foreach}
			</div>
			<div class="local-time">
				<p class="local"><img src="{'img/s.png|cdnurl'}" class="sprite"><span>{$data->location}</span></p>
				<p class="time"><img src="{'img/s.png|cdnurl'}" class="sprite"><span>{$data->sdate|date_format:"%Y年%m月%d日"}</span></p>
			</div>
		</div>
		<div class="pannel white_bg">
			<h4 class="h-title">报名类型</h4>
			<ul class="reg-list">
				{if !empty($itemdata)}
					{foreach from=$itemdata item=iitem}
						<li>
							<div class="col title">{$iitem->name}</div>
							<div class="col price">RMB {($iitem->fee/100)|string_format:"%.2f"}</div>
							<div class="col bm-act">
								<a href="#" hrefdata="#" class="act-btn act-btn-ing" itemid="70">报名</a>
							</div>
						</li>
					{/foreach}
				{else}
					<li>暂无项目</li>
				{/if}
			</ul>
		</div>
	</div>
	<div class="pannel white_bg">
		<h4 class="h-title">基本信息</h4>
		<div class="con-detail">
			{$data->intro}
		</div>
	</div>
</div>
</body>
</html>
