<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>获取地图当前所在行政区</title>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script src="http://webapi.amap.com/maps?v=1.3&key={$key}"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
</head>
<body>
<div id="container"></div>
<div id="tip">可以移动地图，得到城市的信息哦！<br><span id="info"></span></div>
<script>
    var map = new AMap.Map('container', {
        resizeEnable: true
    });

    map.on('moveend', getCity);
    function getCity() {
        map.getCity(function(data) {
            if (data['province'] && typeof data['province'] === 'string') {
                document.getElementById('info').innerHTML = '城市：' + (data['city'] || data['province']);
                window.parent.document.getElementById('province').value = data['province'];
                var new_city = (data['city'] || data['district']);
                window.parent.document.getElementById('city').value = new_city;
            }
        });
    }
</script>
<script type="text/javascript">
    var mock = {
        log: function(result) {
            window.parent.setIFrameResult('log', result);
        }
    }
    console = mock;
    window.Konsole = {
        exec: function(code) {
            code = code || '';
            try {
                var result = window.eval(code);
                window.parent.setIFrameResult('result', result);
            } catch (e) {
                window.parent.setIFrameResult('error', e);
            }
        }
    }
</script></body>
</html>