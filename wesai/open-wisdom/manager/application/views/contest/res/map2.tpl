<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>全国城市下拉列表</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main.css?v=1.0"/>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=a185dea1aefb09b1a22dea88870a15e8&plugin=AMap.DistrictSearch"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.2/jquery.js"></script>
    <style type="text/css">
        #tip {
            background-color: #fff;
            padding:0 10px;
            border: 1px solid silver;
            box-shadow: 3px 4px 3px 0px silver;
            position: absolute;
            font-size: 12px;
            right: 10px;
            top: 5px;
            border-radius: 3px;
            line-height: 36px;
        }
    </style>
</head>
<body>
<div id="mapContainer"></div>
<div id="tip">
    省：<select id='province' style="width:100px" onchange='search(this)'></select>
    市：<select id='city' style="width:100px" onchange='search(this)'></select>
    区：<select id='district' style="width:100px" onchange='search(this)'></select>
   <div style="display: none;"> 商圈：<select id='biz_area' style="width:100px" onchange= 'setCenter(this)'></select></div>
</div>
<script type="text/javascript">
    var mapObj, district, polygons = [], citycode;
    var citySelect = document.getElementById('city');
    var districtSelect = document.getElementById('district');
    var areaSelect = document.getElementById('biz_area');

    mapObj = new AMap.Map('mapContainer', {
        resizeEnable: true,
        center: [116.30946, 39.937629],
        zoom: 3
    });
    //行政区划查询
    var opts = {
        subdistrict: 1,   //返回下一级行政区
        level: 'city'  //查询行政级别为 市
    };
    district = new AMap.DistrictSearch(opts);//注意：需要使用插件同步下发功能才能这样直接使用
    district.search('中国', function(status, result) {
            getData(result);
    });
    function getData(e) {
        var dList = e.districtList;
        for (var m = 0, ml = dList.length; m < ml; m++) {
            var data = e.districtList[m].level;
            var bounds = e.districtList[m].boundaries;
            if (bounds) {
                for (var i = 0, l = bounds.length; i < l; i++) {
                    var polygon = new AMap.Polygon({
                        map: mapObj,
                        strokeWeight: 1,
                        strokeColor: '#CC66CC',
                        fillColor: '#CCF3FF',
                        fillOpacity: 0.5,
                        path: bounds[i]
                    });
                    polygons.push(polygon);
                }
                mapObj.setFitView();//地图自适应
            }
            var list = e.districtList || [],
                    subList = [], level, nextLevel;
            if (list.length >= 1) {
                subList = list[0].districtList;
                level = list[0].level;
            }
            //清空下一级别的下拉列表
            if (level === 'province') {
                nextLevel = 'city';
                citySelect.innerHTML = '';
                districtSelect.innerHTML = '';
                areaSelect.innerHTML = '';
            } else if (level === 'city') {
                nextLevel = 'district';
                districtSelect.innerHTML = '';
                areaSelect.innerHTML = '';
            } else if (level === 'district') {
                nextLevel = 'biz_area';
                areaSelect.innerHTML = '';
            }
            if (subList) {
                var contentSub =new Option('--请选择--');
                for (var i = 0, l = subList.length; i < l; i++) {
                    var name = subList[i].name;
                    var levelSub = subList[i].level;
                    var cityCode = subList[i].citycode;
                    if(i==0){
                        document.querySelector('#' + levelSub).add(contentSub);
                    }
                    contentSub=new Option(name);
                    contentSub.setAttribute("value", levelSub);
                    contentSub.center = subList[i].center;
                    document.querySelector('#' + levelSub).add(contentSub);
                }
            }
        }
    }
    function search(obj) {
        //清除地图上所有覆盖物
        for (var i = 0, l = polygons.length; i < l; i++) {
            polygons[i].setMap(null);
        }

        var option = obj[obj.options.selectedIndex];
        var keyword = option.text; //关键字
        district.setLevel(option.value); //行政区级别
        district.setExtensions('all');
        //行政区查询
        district.search(keyword, function(status, result) {
            getData(result);
        });
    }
    function setCenter(obj){
        mapObj.setCenter(obj[obj.options.selectedIndex].center)
    }
    $(function(){
        $("select").change(function(){
            var lelval = $(this).val();
            var leltext = $(this).find("option:selected").text();

            if (leltext && typeof lelval === 'string') {
                //省
                if(lelval ==='province'){
                 window.parent.document.getElementById('province').value = leltext;
                }
                //地级市／区
                if(lelval ==='city' || lelval ==='district')
                {
                    var lcity = $("#city").find("option:selected").text();
                    var lproc = $("#province").find("option:selected").text();
                    var ldist = $("#district").find("option:selected").text();
                    var procarr = ["北京市","天津市","上海市","重庆市"];
                    if(jQuery.inArray(lproc,procarr) !=-1){
                        leltext = ldist;
                    }else{
                        leltext = lcity;
                    }
                    if(lcity == ""){
                        leltext = ldist;
                    }
                    window.parent.document.getElementById('city').value = leltext;
                }
            }
        })
    })
</script>
</body>
</html>