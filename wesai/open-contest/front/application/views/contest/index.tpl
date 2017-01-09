{include file='../header.tpl'}
{include file='./_topnav.tpl'}

<div class="page-group change-page-style">
	<div class="page" id="contest-index">
		<div  class="content posfixed pull-to-refresh-content infinite-scroll" data-ptr-distance="55">
				<!-- 加载提示符 -->
				<div class="pull-to-refresh-layer">
			        <div class="preloader"></div>
			        <div class="pull-to-refresh-arrow"></div>
				</div>
				<div class="swiper-container">
				    <div class="swiper-wrapper" id="swiperCon" data-corp_id="{$corp_id}" data-appId="{$appId}" data-operation={$smarty.const.OPERATION_DOMAIN_SUF}>
				    </div>
				    <div class="swiper-pagination"></div>
				</div>
				<div id="contest-content"></div>
				<!-- 加载提示符 -->
		        <div class="infinite-scroll-preloader">
		            <div class="preloader"></div>
		        </div>
		</div>
		{include file='../footerNav.tpl'}
	</div>
</div>
{include file='../wxsdk.tpl'}

{if !empty($contestData)}
<script>
	//微信分享内容
		var shareData = {
		    title : '{$contestData->name}',
		    desc  : '{$contestData->name}',
		    link  : document.URL,
		    imgUrl: '{$contestData->logo|cdnurl:"upload"}'
		};
		wxshareContent(shareData);
		wxshareContentLine(shareData);
</script>
{/if}
{include file='../footer.tpl'}

