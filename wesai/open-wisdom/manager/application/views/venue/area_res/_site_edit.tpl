<div class="panel-body">
    <form id="area_res_form">
        <div class="add-contest">
            <div class="form-group">
                <p>
                    <label>
                        1. 场地名称 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="请填写场地名称" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        2. 场馆项目 <b>*</b>(单选)
                    </label>
                </p>
                <div>
                    {foreach from=$allow_venue_types item=venue_type}
                        {if !empty($result) && $venue_type['tag_id'] == $result->type}
                            <label class="checkbox-inline">
                            <input type="radio" value="{$venue_type['tag_id']}" name="type" checked>{$venue_type['name']}
                            </label>
                        {/if}
                        {if empty($result)}
                            <label class="checkbox-inline">
                            <input type="radio" value="{$venue_type['tag_id']}" name="type"> {$venue_type['name']}
                            </label>
                        {/if}
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        3. 最小售卖时间
                        <b>*</b>
                    </label>
                </p>
                <div class="globel-select">
                        <select class="form-control">
                            <option value="1">一小时</option>
                        </select>
                </div>
            </div>
            <div class="form-group" id="price_list">
                <p>
                    <label>
                        4. 价格 <b>*</b>
                    </label>
                </p>
                {if empty($rules)}
                    <div class="date-box clearfix mr20">
                        <div class="date-main" id="working_days_list">
                            <div class="dt title">
                                工作日:
                            </div>
                            <div class="clearfix dd">
                                {include './_select_date.tpl' date_type='working_days'}
                                <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['working_days'][dob5a46ti70fulkgldi]['price']" oninput="this.value=priceReg(this)">
                                <span class="pull-left date-txt mrl20">
                                    元／小时
                                </span>
                                <button type="button" class="pull-left btn btn-link" operation-add data-date-type="working_days">添加</button>
                            </div>
                        </div>
                    </div>
                    <div class="date-box clearfix mr20">
                        <div class="date-main" id="weekend_list">
                            <div class="dt title">
                                周末:
                            </div>
                            <div class="clearfix dd">
                                {include './_select_date.tpl' date_type='weekend'}
                                <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['weekend'][dob5a46ti70fulkgldi]['price']" oninput="this.value=priceReg(this)">
                                <span class="pull-left date-txt mrl20">
                                    元／小时
                                </span>
                                <button type="button" class="pull-left btn btn-link" operation-add data-date-type="weekend">添加</button>
                            </div>
                        </div>
                    </div>
                    <div class="date-box clearfix mr20">
                        <div class="date-main" id="holidays_list">
                            <div class="dt title"aa>
                                节假日:
                            </div>
                            <div class="clearfix dd">
                                {include './_select_date.tpl' date_type='holidays'}
                                <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['holidays'][dob5a46ti70fulkgldi]['price']">
                                <span class="pull-left date-txt mrl20" oninput="this.value=priceReg(this)">
                                    元／小时
                                </span>
                                <button type="button" class="pull-left btn btn-link" operation-add data-date-type="holidays">添加</button>
                            </div>
                        </div>
                    </div>
                {else}
                    {foreach from=$rules item=rule key=index}
                        {if $index == 1}
                            <div class="date-box clearfix mr20">
                                <div class="date-main" id="working_days_list">
                                    <div class="dt title">
                                        工作日:
                                    </div>
                                    {foreach from=$rule item=value key=key}
                                    <div class="clearfix dd mr20">
                                        {include './_select_date.tpl' date_type='working_days'}
                                        <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['working_days'][{$key}]['price']" value="{$value->price}" oninput="this.value=priceReg(this)">
                                        <span class="pull-left date-txt mrl20">
                                            元／小时
                                        </span>
                                        {if $value@first}
                                            <button type="button" class="pull-left btn btn-link" operation-add data-date-type="working_days">添加</button>
                                        {else}
                                            <button type="button" class="pull-left btn btn-link" operation-delete>删除</button>
                                        {/if}
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        {else if $index == 2}
                            <div class="date-box clearfix mr20">
                                <div class="date-main" id="weekend_list">
                                    <div class="dt title">
                                        周末:
                                    </div>
                                    {foreach from=$rule item=value key=key}
                                    <div class="clearfix dd mr20">
                                        {include './_select_date.tpl' date_type='weekend'}
                                        <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['weekend'][{$key}]['price']" value="{$value->price}" oninput="this.value=priceReg(this)">
                                        <span class="pull-left date-txt mrl20">
                                            元／小时
                                        </span>
                                        {if $value@first}
                                            <button type="button" class="pull-left btn btn-link" operation-add data-date-type="weekend">添加</button>
                                        {else}
                                            <button type="button" class="pull-left btn btn-link" operation-delete>删除</button>
                                        {/if}
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        {else}
                            <div class="date-box clearfix mr20">
                                <div class="date-main" id="holidays_list">
                                    <div class="dt title"aa>
                                        节假日:
                                    </div>
                                    {foreach from=$rule item=value key=key}
                                    <div class="clearfix dd mr20">
                                        {include './_select_date.tpl' date_type='holidays'}
                                        <input type="text" class="pull-left form-control wah90 mrl20" name="rule_detail['holidays'][{$key}]['price']" value="{$value->price}" oninput="this.value=priceReg(this)">
                                        <span class="pull-left date-txt mrl20">
                                            元／小时
                                        </span>
                                        {if $value@first}
                                            <button type="button" class="pull-left btn btn-link" operation-add data-date-type="holidays">添加</button>
                                        {else}
                                            <button type="button" class="pull-left btn btn-link" operation-delete>删除</button>
                                        {/if}
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                {/if}
            </div>
            <div class="pd10">
                {if empty($result)}
                    <input type="hidden" name="venue_id" value="{$venue_id}">
                {else}
                    <input type="hidden" name="venue_id" value="{$result->venue_id}">
                {/if}

                {if !empty($result)}
                    <input type="hidden" name="venue_area_res_id" value="{$result->venue_area_res_id}">
                {/if}

                {if empty($result)}
                    <button class="btn btn-default btn-blue mgr20 save_venue_res" id="save_venue_res">添加场地</button>
                {else}
                    <button class="btn btn-default btn-blue mgr20 save_venue_res" id="save_venue_res" data-save-type="edit">编辑场地</button>
                {/if}
                <button type="button" class="btn btn-default btn-cancel" onclick="window.history.go(-1)">取消</button>
            <div>
                <div>

                </div>
            </div>
            </div>
        </div>
    </form>
</div>
{literal}
<script id="price-template" type="text/x-handlebars-template">
    <div class="clearfix dd mr20">
        <div class="choose-date pull-left selct">
            {{#select_start}}{{/select_start}}
            {{#select_end}}{{/select_end}}
        </div>
        <input type="text" class="pull-left form-control wah90 mrl20 area_res_time" name="rule_detail['{{type}}'][{{randomStr}}]['price']" oninput="this.value=priceReg(this)">
        <span class="pull-left date-txt mrl20">
            元／小时
        </span>
        <button type="button" class="pull-left btn btn-link" operation-delete>删除</button>
    </div>
</script>
{/literal}


