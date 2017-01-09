<div class="create-form location-mod hide">
    <div class="mark-item">
        <h5>活动地理位置</h5>
        <div class="pdtbt10">
            <table class="table">
                <tr>
                    <td>
                        <label>国家</label>
                        <input type="text" name="tmp-country" id="tmp-country" value="中国" level＝ "1" class="form-control" readonly>
                    </td>
                    <td>
                        省/直辖：
                        <input type="text" name="tmp-province" level="2" id="tmp-province" class="form-control" {if !empty($localdata)} value="{$localdata[1]->name}" 
                        {/if} readonly>
                    </td>
                    <td>地级市／区：
                        <input type="text" name="tmp-city" leval="3" id="tmp-city" 
                        {if !empty($localdata)} value="{$localdata[2]->name}" {/if} class="form-control" readonly>
                    </td>
                </tr>
            </table>
        </div>
        <h6><b>请选择地理位置：</b></h6>
        <div data-toggle="distpicker" id="distpicker" class="pdtbt10">
            <label>中国</label>
            <select data-province="---- 选择省 ----" id="province" class="form-control"></select>
            <select data-city="---- 选择市 ----" id="city" class="form-control"></select>
            <select data-district="---- 选择区 ----" id="district" class="form-control"></select>
            <button class="btn btn-default btn-success mgr20" id="saveloation">保存位置</button>
            <script type="text/javascript">
            {$localdata|json_encode}
            $(function() {
                $("#distpicker select").change(function() {
                    var lelval = $(this).attr("id");
                    var leltext = $(this).find("option:selected").text();

                    if (leltext && typeof lelval === 'string') {
                        //省
                        if (lelval === 'province') {
                            window.document.getElementById('tmp-province').value = leltext;
                        }
                        //地级市／区
                        if (lelval === 'city' || lelval === 'district') {
                            var lcity = $("#city").find("option:selected").text();
                            var lproc = $("#province").find("option:selected").text();
                            var ldist = $("#district").find("option:selected").text();
                            var procarr = ["北京市", "天津市", "上海市", "重庆市"];
                            if (jQuery.inArray(lproc, procarr) != -1) {
                                leltext = ldist;
                            } else {
                                leltext = lcity;
                            }
                            if (lcity == "") {
                                leltext = ldist;
                            }
                            window.document.getElementById('tmp-city').value = leltext;
                        }
                    }
                    console.log(leltext);
                })
            })
            //保存地理位置
            $("#saveloation").click(
                function () {
                    var country = "中国";
                    var province = $("#tmp-province").val();
                    var city = $("#tmp-city").val();
                    if (province == "" || city == "") {
                        alert("请选择省市和城市");
                        return false;
                    }
                    var localData = {
                        "cid": _contestConfig.fk_contest,
                        "country": country,
                        "province": province,
                        "city": city
                    }
                    $.post("/contest/contest/ajax_add_location", localData, function (data) {
                                if (data['error'] == '0') {
                                    alert("添加成功");
                                } else {
                                    alert(data['info']);
                                }
                            }, 'json'
                    );
                    return false;
                }
            )
            </script>
        </div>
    </div>
</div>
