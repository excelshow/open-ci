<div data-toggle="distpicker" id="distpicker" class="pdtbt10">
    <input type="hidden" name="locations['province']" level="2" id="tmp-province" value="{if !empty($result->locations)}{$result->locations['province']}{/if}" />
    <input type="hidden" name="locations['city']" leval="3" id="tmp-city" value="{if !empty($result->locations)}{$result->locations['city']}{/if}">
    <input type="hidden" name="locations['district']" leval="4" id="tmp-district" value="{if !empty($result->locations)}{$result->locations['district']}{/if}">
    <label>中国</label>
    {if !empty($result->locations)}
    <select data-province="{$result->locations['province']}" id="province" class="form-control"></select>
    <select data-city="{$result->locations['city']}" id="city" class="form-control"></select>
    <select data-district="{$result->locations['district']}" id="district" class="form-control"></select>
    {else}
    <select data-province="---- 选择省 ----" id="province" class="form-control"></select>
    <select data-city="---- 选择市 ----" id="city" class="form-control"></select>
    <select data-district="---- 选择区 ----" id="district" class="form-control"></select>
    {/if}
    <script type="text/javascript">
    $(function() {
        jQuery("input[type='radio'][name='country_scope']").click(function() {
            if ($(this).val() == "2") {
                $(".location-box").hide();
            } else {
                $(".location-box").show();
            }
        })
        $("#distpicker select").change(function() {
            var lelval = $(this).attr("id");
            var leltext = $(this).find("option:selected").text();

            if (leltext && typeof lelval === 'string') {
                //省
                if (lelval === 'province') {
                    $("#tmp-province").val(leltext);
                }
                //地级市／区
                if (lelval === 'city') {
                    $("#tmp-city").val(leltext);
                }
                if (lelval === 'district') {
                    $("#tmp-district").val(leltext);
                }
            }
        })
    })
    </script>
</div>
