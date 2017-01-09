            
            <div data-toggle="distpicker" id="distpicker" class="pdtbt10">
            <input type="hidden" name="tmp-province" level="2" id="tmp-province" value="{if !empty($location)}{$location[2]}{/if}"/>
            <input type="hidden" name="tmp-city" leval="3" id="tmp-city" value="{if !empty($location)}{$location[3]}{/if}">
            <input type="hidden" name="tmp-district" leval="4" id="tmp-district" value="{if !empty($location[4])}{$location[4]}{/if}">
            <label>中国</label>
            {if !empty($location)}
                <select data-province="{$location[2]}" id="province" class="form-control"></select>
                <select data-city="{$location[3]}" id="city" class="form-control"></select>
                <select data-district="{if !empty($location[4])}{$location[4]}{/if}" id="district" class="form-control"></select>
             {else}
                <select data-province="---- 选择省 ----" id="province" class="form-control"></select>
                <select data-city="---- 选择市 ----" id="city" class="form-control"></select>
                <select data-district="---- 选择区 ----" id="district" class="form-control"></select>
             {/if}

            <script type="text/javascript">
            $(function() {
                $("input[type='radio'][name='country_scope']").click(function(){
                    if($(this).val()=="2"){
                        $(".location-box").hide();
                    }else{
                        $(".location-box").show();
                    } 
                })
                if($("input[type='radio']:checked").val()=="2"){
                    $(".location-box").hide();
                }else{
                    $(".location-box").show();
                } 
                // $("input[type='radio'][name='country_scope']").trigger('click');
                
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
