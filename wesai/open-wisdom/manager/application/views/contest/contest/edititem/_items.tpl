
<div class="panel-body">
	<div class="add-contest">
		<div id="js_indexNum">
			<div class="form-group">
				<p>
					<label> <span class="index-num"></span>
						活动项目名称 <b>*</b>
					</label>
				</p>

				<input type="text" class="form-control" name="name" id="name" placeholder="项目名称" maxlength="20" value=""></div>
			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						报名方式 <b>*</b>
					</label>
				</p>
				<label class="mgr20">
					<input type="radio" class="sign-up"  checked="checked" name="type" value="1" > 单人报名</label>
				<label >
					<input type="radio" class="sign-up" name="type" value="2" > 团队报名</label>
			</div>
			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						报名费用(元)
						<b>*</b>
					</label>
				</p>
				<input type="text" class="form-control" name="fee" id="fee" placeholder="请填写报名费用" maxlength="6" value=""></div>
			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						是否需要邀请码
						<b>*</b>
					</label>
				</p>
				<label class="mgr20">
					<input type="radio" name="invite_required" value="1" > 需要</label>
				<label>
					<input type="radio" name="invite_required" value="2" > 不需要</label>
			</div>
			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						检票次数
						<b>*</b>
					</label>
				</p>
				<input type="number" class="form-control" name="max_verify" id="max_verify" min="0" max="100000" placeholder="每张票检票次数限制 默认为不限制" maxlength="6" value=""></div>

			<div class="create-form" id="team"></div>

			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						项目开始时间
						<b>*</b>
					</label>
				</p>
				<input type="text" class="form-control datetimepicker" readonly name="start_time" id="start_time" placeholder="请选择项目开始时间" value=""></div>
			<div class="form-group">
				<p>
					<label><span class="index-num"></span>
						项目关门时间
						<b>*</b>
					</label>
				</p>

				<input type="text" class="form-control datetimepicker" readonly name="end_time" id="end_time" placeholder="请选择项目结束时间" value=""></div>
			<div class="pd10">
				<button class="btn btn-default btn-blue mgr20" id="savecontest">创建项目</button>

				<button class="btn btn-default btn-cancel mgr20 hide" id="savecontestloging">提交中</button>

				<button type="button" class="btn btn-default btn-cancel" onclick="window.history.go(-1)">取消</button>
			</div>
		</div>

	</div>
</div>

<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
{literal}
<script id="team-template" type="text/x-handlebars-template">
    <div class="form-group">
    	<p>
    		<label><span class="index-num"></span> 团队人数 <b>*</b></label>
    	</p>
    	<input type="text" class="form-control" name="team_size" id="team_size" placeholder="请填写团队人数" maxlength="6" value="{{team_size}}"></div>
    <div class="form-group">
    	<p>
    		<label><span class="index-num"></span>
    			 团队个数
    			<b>*</b>
    		</label>
    	</p>
    	<input type="number" class="form-control" name="team_max_stock" id="team_max_stock" min="0" max="100000" placeholder="<100000" maxlength="6" value="{{team_max_stock}}"></div>
</script>

<script id="personal-template" type="text/x-handlebars-template">
    <div class="form-group">
    	<p>
    		<label><span class="index-num"></span>
    			参与人数上限
    			<b>*</b>
    		</label>
    	</p>
    	<input type="number" class="form-control" name="max_stock" id="max_stock" min="0" max="100000" placeholder="<100000" maxlength="6" value="{{max_stock}}">
	</div>
	<div class="form-group">
		<p>
			<label><span class="index-num"></span>
				是否可一次买多张
				<b>*</b>
			</label>
		</p>
		<label class="mgr20">
			<input type="radio" name="multi_buy" value="1" > 可以</label>
		<label>
			<input type="radio" name="multi_buy" value="2" > 不可以</label>
	</div>
</script>

<script>
	$(function(){
		$(document).on('click','.sign-up',function(){
			personal($(this).val());
		})
		function personal(val){
			if(val==1){
				var personal=$('#personal-template').html();
		        var personal = Handlebars.compile(personal);
				$('#team').html(personal());//场馆列表
				indexNum();
			}else if(val==2){
				var team=$('#team-template').html();
		        var team = Handlebars.compile(team);
				$('#team').html(team());//场馆列表
				indexNum();
			}
		}
		personal(1);
		var itemid=window.location.search;
		itemid=itemid.split('=');
		var params={
			item_id:itemid[1]
		}
		if(itemid==''){
			return;
		}
	    $.get("/contest/contest/ajax_edit_item", params, function (data) {
            if (data.error == '0') {
            	$('#name').val(data.result.name);
            	$('#fee').val(data.result.fee/100);
            	$("input[type=radio][name='invite_required'][value="+data.result.invite_required+"]").attr("checked",data.result.invite_required);
            	$("input[type=radio][name='type'][value="+data.result.type+"]").attr("checked",'checked');
            	$('#max_verify').val(data.result.max_verify);
            	$('#end_time').val(data.result.end_time);
            	$('#start_time').val(data.result.start_time);
            	$('#savecontest').text('编辑项目');
    			var team=$('#team-template').html();
    	        var team = Handlebars.compile(team);
    			$('#team').html(team());//场馆列表
    			personal(data.result.type);
            	$('#max_stock').val(data.result.max_stock);
            	$('#team_size').val(data.result.team_size);
            	$('#team_max_stock').val(data.result.team_max_stock);
            	$("input[type=radio][name='multi_buy'][value="+data.result.multi_buy+"]").attr("checked",data.result.multi_buy);


    			indexNum();
            } else {
                	alert(data['info']);
					$("#savecontest").show()
					$("#savecontestloging").hide();
					alert(data['info']);
            }
	        }, 'json'
      	);
		function indexNum(){
			$('#js_indexNum .index-num').each(function(index){
				$(this).text(index+1+'.');
			})
		}
	})

</script>
{/literal}
<script>
	//新增活动项目
	$(function () {
		var start = {
		    dateCell: '#start_time',
		    format: 'YYYY-MM-DD',
		    // minDate: jeDate.now(0), //设定最小日期为当前日期
		    isinitVal:false,
		    festival:true,
		    ishmsVal:false,
		    maxDate: '2099-06-30', //最大日期
		    choosefun: function(elem,datas){
		        end.minDate = datas; //开始日选好后，重置结束日的最小日期
		    }
		};
		var end = {
		    dateCell: '#end_time',
		    format: 'YYYY-MM-DD',
		    minDate: jeDate.now(0), //设定最小日期为当前日期
		    festival:true,
		    maxDate: '2099-06-16', //最大日期
		    choosefun: function(elem,datas){
		        start.maxDate = datas; //将结束日的初始值设定为开始日的最大日期
		    }
		};
		jeDate(start);
		jeDate(end);
		$('#name').on('input',function(){
			var len=$(this).val().length;
			
			if(len>19){
				alert('项目名称不能大于20个字符！！')
			}
		});
		$("#savecontest").on('click',
          function () {
          var cid  = $('#fk_contest').val() || location.href.split('#')[1];
          var item_id  =  location.href.split('=')[1];
          var name = $('#name').val();
          var fee  = $('#fee').val();
          var start_time      = $("#start_time").val();
          var end_time        = $("#end_time").val();
          var max_verify   = $("#max_verify").val();
          var invite_required = $("input[name='invite_required']:checked").val();
          var type = $("input[name='type']:checked").val();
          var max_stock       = $("#max_stock").val();
          var team_max_stock  = $("#team_max_stock").val();
          var team_size = $("#team_size").val();
          var multi_buy= $("input[name='multi_buy']:checked").val();
          team_max_stock = team_max_stock == "" ? 0 : team_max_stock;
          max_verify  = max_verify == "" ? 0 : max_verify;
              if (name == "") {
                  $('#item_name').focus();
                  alert('请输项目名称');
                  return false;
              }
              if (isNaN(fee)) {
                  $('#fee').focus();
                  alert('请输入报名费用');
                  return false;
              }
              if (fee == "") {
                  $('#fee').focus();
                  alert('请输入报名费用');
                  return false;
              }

              if (invite_required == "1") {
                  if (team_max_stock < 1 || team_max_stock == "") {
	                  alert("参赛人数不能为空");
	                  $("#team_max_stock").focus();
	                  return false;
                  }
              }

              if (start_time == "") {
                  $('#start_time').focus();
                  return false;
              }
              if (end_time == "") {
                  $('#end_time').focus();
                  return false;
              }
              var postData = {
                  "cid"            : cid,
                  "item_id"        : item_id,
                  "name"           : name,
                  "fee"            : fee * 100,
                  "start_time"     : start_time,
                  "end_time"       : end_time,
                  "invite_required": invite_required,
                  "max_verify"     : max_verify,
                  'type'           :type,
                  "max_stock"      : max_stock,
                  "team_max_stock" : team_max_stock,
                  'team_size'      :team_size,
                  "multi_buy": multi_buy,
              };
              $("#savecontest").hide();
              $("#savecontestloging").show().removeClass("hide");
              $.post("/contest/contest/ajax_add_items", postData, function (data) {
                     if (data['error'] == '0') {
                        window.history.go(-1)
                     } else {
                        alert(data['info']);
     					$("#savecontest").show()
     					$("#savecontestloging").hide();
     					alert(data['info']);
                     }
                   }, 'json'
              );
		      }
		)
	})
</script>
