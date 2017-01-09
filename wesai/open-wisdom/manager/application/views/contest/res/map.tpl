<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>全国城市下拉列表</title>
    <script src="{'manager_contest/js/jquery.min.js'|cdnurl}"></script>
    <script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
     <script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>
</head>
<body>
<div data-toggle="distpicker">
  <select data-province="---- 选择省 ----" id="province"></select>
  <select data-city="---- 选择市 ----" id="city"></select>
  <select data-district="---- 选择区 ----" id="district"></select>
  <script type="text/javascript">
      $(function(){
        $("select").change(function(){
            var lelval = $(this).attr("id");
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
</div>
</body>
</html>
