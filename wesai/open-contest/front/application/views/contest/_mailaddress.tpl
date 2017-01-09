<div class="pannel white_bg">
     <h4 class="h-title"><i style="color: red;">* </i>收货地址<span class="night_color">（比赛相关装备将寄于此地址）</span></h4>
     <div class="address-box">
         <div class="avatar">
            <img src="{'/img/shouhuo@2x.png'|cdnurl}">
         </div>
	     <div class="addres-info">
		     {if count($myAddressList) > 0}
			     <p class="three_color"><strong class="s-showname">{$myAddressList[0]->name}</strong> <span
						     class="fR s-showmobile">{$myAddressList[0]->mobile}</span></p>
			     <p class="six_color s-showaddr">{$myAddressList[0]->addr} </p>
			     <p class="s-zipcode">{$myAddressList[0]->zipcode}</p>
		     {else}
			     <p class="three_color tip-txt"><strong>请添加收货信息</strong></p>
		     {/if}
	     </div>
     </div>
</div>
<!-- 修改收货地址 -->
<!-- 管理地址 -->
<div class="addr-manage hide" id="addr-manage">
    <div class="t-box">
      <ul class="addmr-list">
        <li>
          <label>姓名</label>
        <input type="text" placeholder="收货人姓名" id="addr_name" name="addr_name"></li>
        <li> <label>手机号 </label>
        <input type="text" placeholder="收货人手机号" id="addr_mobile" name="addr_mobile"></li>
        <li> <label>详细地址 </label>
        <input type="text" placeholder="详细地址" id="addr_addr" name="addr_addr"></li>
          <li> <label>邮编 </label>
        <input type="text" placeholder="邮政编码" id="addr_zipcode" name="addr_zipcode"></li>
      </ul>
    </div>
    <div class="t-foot">
      <em class="x-close">X</em>
        <a href="javascript:void(0)" class="ok" id="do_addr" onclick="return addaddress()">确定</a>
    </div>
</div>
{literal}
<script>
shipping_addr = {
      "name":$(".s-showname").text(),
      "mobile":$(".s-showmobile").text(),
      "addr":$(".s-showaddr").text(),
      "zipcode":$(".s-zipcode").text()
    }
 $(".address-box").click(function(){
    // debugger;
    $("#addr-manage").toggle();
    if(shipping_addr.name&&shipping_addr.name!='点击填写收货地址'){

      $("#addr_name").val(shipping_addr.name);
    }
    if(shipping_addr.mobile){
      $("#addr_mobile").val(shipping_addr.mobile);
    }
    if(shipping_addr.addr){
      $("#addr_addr").val(shipping_addr.addr);
    }
    if(shipping_addr.zipcode){
       $("#addr_zipcode").val(shipping_addr.zipcode);
    }

    return false;
 })
 $("#addr-manage .x-close").click(function(){
     // debugger;
    $("#addr-manage").hide();
    $("#addr-manage").find("input").val("");
 })
//新增收货地址
function addaddress()
{    //debugger;
    var name = $("#addr_name").val();
    if(name == "")
    {
      $("#addr_name").focus();
      alert("姓名不能为空");
      return false;
    }
    var mobile = $("#addr_mobile").val();
    var mobilereg = /^1\d{10}$/;
    if(!mobilereg.test(mobile))
    {
      $("#addr_mobile").focus();
      alert("手机号格式不正确");
      return false;
    }
    var addr = $("#addr_addr").val();
    if(addr == "")
    {
      $("#addr_addr").focus();
      alert("地址不能为空");
      return false;
    }
    var zipcode = $("#addr_zipcode").val();
    var zrcodereg = /^[0-9]{6,10}$/;
    if(!zrcodereg.test(zipcode))
    {
      $("#addr_zipcode").focus();
      alert("邮编格式不正确");
      return false;
    }
    var postaddrr = {
      "name":name,
      "mobile":mobile,
      "addr":addr,
      "zipcode":zipcode
    }
    $.post("/user/ajax_addaddress",postaddrr,function(data){
      if(data.error == 0)
      {
         shipping_addr = postaddrr;
         $("#addr-manage").hide();
         $("#addr-manage").find("input").val("");
        var tmphtml ='';
            tmphtml+='<p class="three_color">';
            tmphtml+='<strong class="s-showname">'+name+'</strong>';
            tmphtml+='<span class="fR s-showmobile">'+mobile+'</span></p>';
            tmphtml+='<p class="six_color s-showaddr">'+addr+'</p>';
            tmphtml+='<p class="s-zipcode">'+zipcode+'</p>';
        $(".addres-info").html(tmphtml);
      }else{
        alert(data.info);
      }
    },"json");
  return false;
}
</script>
 {/literal}
