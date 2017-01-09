<div class="goTop">
  <div class="glyphicon glyphicon-circle-arrow-up" title="返回顶部"></div>
</div>
<script type="text/javascript">
    var menu_action = '{$smarty.server.REQUEST_URI}';
</script>
{literal}
    <!-- 基本操作 -->
    <!--confirm-->
    <script type="text/javascript">
    	$(function (){
    	  $(window).scroll(function(){
    		  if ($(window).scrollTop()>300){
    		  $(".goTop").fadeIn(300);
    		  }else{
    		  $(".goTop").fadeOut(300);}});
    		  $(".goTop").click(function(){
    		  	$('body,html').animate({scrollTop:0},400);
    		  return false;
    	  });
    	});
    </script>
{/literal}
</body>
</html>
