{include file='../header.tpl'}
	<div class="page-group">
		<div class="page" id="ordersList-page">
			<div class="content posfixed pull-to-refresh-content infinite-scroll" data-ptr-distance="55">
		        <div class="list-block orders-list-main media-list mar0">
		        	<!-- 加载提示符 -->
		        	<div class="pull-to-refresh-layer">
		        	        <div class="preloader"></div>
		        	        <div class="pull-to-refresh-arrow"></div>
		        	</div>

		          	<ul class="contest-list" id="ordersList-tem"></ul>

          			<!-- 加载提示符 -->
          	        <div class="infinite-scroll-preloader">
          	            <div class="preloader"></div>
          	        </div>
		        </div>
		    </div>
		    {include file='../footerNav.tpl'}
		</div>
	</div>
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}
