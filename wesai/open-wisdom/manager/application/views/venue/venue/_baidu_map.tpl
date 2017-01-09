<!-- baidu map -->
<div class="show-baidu-map">
    <div class="wrapper-baidu-map">
        <div class="btn-baidu-map sure-baidu-map">确定</div>
        <div class="btn-baidu-map close-baidu-map">关闭</div>
        <div class="search-baidu-map" id="r-result">
            <input type="text" id="suggestId" size="20" placeholder="搜索地址,确认后请在地图中点击该位置" />
        </div>
        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto;display: none;"></div>
        <div id="allmap"></div>
    </div>
</div>
<script src="http://api.map.baidu.com/api?v=2.0&ak=VgzC5FGgd6h6QsU5GbPHG6aGpAFY2kVO"></script>
<script type="text/javascript">
// 百度地图API功能
var mapPoint, addComp, myValue;
var map = new BMap.Map("allmap"); // 创建Map实例
var myGeo = new BMap.Geocoder(); // 获取位置街道方法
map.addControl(new BMap.CityListControl()); //添加地图城市控件
map.setCurrentCity("北京"); // 设置地图显示的城市 此项是必须设置的
map.enableScrollWheelZoom(true); //开启鼠标滚轮缩放
$(".get-baidu-map").on('click', function() {
    $(".show-baidu-map").css('display', 'block');
    $("html,body").css('overflow', 'hidden');
    var adDefault = $("#tmp-province").val()+$("#tmp-city").val()+$("#tmp-district").val()+$("#streetNumber").val();
    theLocation(adDefault, 15);
})

// 将地址解析结果显示在地图上,并调整地图视野
function theLocation(location, zoom) {
    location = location || "北京";
    console.log(location);
    myGeo.getPoint(location, function(point) {
        if (point) {
            mapPoint = point;
            showMap(point);
            map.centerAndZoom(point, zoom);
        } else {
            alert("请选择正确的地址");
        }
    }, "北京市");
}

function showMap(point) {
    map.clearOverlays(); //清除地图上所有覆盖物
    var marker = new BMap.Marker(point); // 创建标注
    map.addOverlay(marker); // 将标注添加到地图中
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}

function addAddress(point) {
    myGeo.getLocation(point, function(rs) {
        addComp = rs.addressComponents;
        alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
    });
}

map.addEventListener("click", function(e) {
    var point = e.point;
    showMap(point);
    addAddress(point);
});

$(".sure-baidu-map").on('click', function() {
    $(".show-baidu-map").css('display', 'none');
    $("html,body").css('overflow', 'auto');
    $("#map_lat").val(mapPoint.lat);
    $("#map_lng").val(mapPoint.lng);
    $("#tmp-province").val(addComp.province);
    $("#tmp-city").val(addComp.city);
    $("#tmp-district").val(addComp.district);
    $("#streetNumber").val(addComp.street + ", " + addComp.streetNumber);
})

$(".close-baidu-map").on('click', function() {
    $(".show-baidu-map").css('display', 'none');
    $("html,body").css('overflow', 'auto');
})

//  地址搜索
function G(id) {
    return document.getElementById(id);
}
var ac = new BMap.Autocomplete({ //建立一个自动完成的对象
    "input": "suggestId",
    "location": map
});

ac.addEventListener("onhighlight", function(e) { //鼠标放在下拉列表上的事件
    var str = "";
    var _value = e.fromitem.value;
    var value = "";
    if (e.fromitem.index > -1) {
        value = _value.province + _value.city + _value.district + _value.street + _value.business;
    }
    str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
    value = "";
    if (e.toitem.index > -1) {
        _value = e.toitem.value;
        value = _value.province + _value.city + _value.district + _value.street + _value.business;
    }
    str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
    G("searchResultPanel").innerHTML = str;
});

ac.addEventListener("onconfirm", function(e) { //鼠标点击下拉列表后的事件
    var _value = e.item.value;
    myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
    G("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
    setPlace();
});

function setPlace() {
    function myFun() {
        mapPoint = local.getResults().getPoi(0).point; //获取第一个智能搜索的结果
        addAddress(mapPoint);
        showMap(mapPoint);
        map.centerAndZoom(mapPoint, 18);
    }
    var local = new BMap.LocalSearch(map, { //智能搜索
        onSearchComplete: myFun
    });
    local.search(myValue);
}
</script>