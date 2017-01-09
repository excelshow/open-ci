{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="groupList-page">
		<header class="user-orders-tab">
			<div class="row no-gutter">
				<div class="col-50 user-orders-item activity" data-type='1'>我创建的</div>
				<div class="col-50 user-orders-item" data-type='2'>我参加的</div>
			</div>
		</header>
		<div class="content pull-to-refresh-content infinite-scroll" data-ptr-distance="55">
			
			<div class="list-block media-list mar0">
				<!-- 加载提示符 -->
	        	<div class="pull-to-refresh-layer">
	    	        <div class="preloader"></div>
	    	        <div class="pull-to-refresh-arrow"></div>
	        	</div>
			    <ul id="groupList" class="groupList"></ul>
	    		<!-- 加载提示符 -->
	            <div class="infinite-scroll-preloader">
	                <div class="preloader"></div>
	            </div>
			</div>
		</div>
	</div>
</div>
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}