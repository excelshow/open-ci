<div class="choose-date pull-left  selct">
    {if empty($rules)}
    <select class="form-control pull-left wah200" name="rule_detail['{$date_type}'][dob5a46ti70fulkgldi]['time_start']">
    {else}
    <select class="form-control pull-left wah200" name="rule_detail['{$date_type}'][{$key}]['time_start']">
    {/if}
        <option value="">请选择时间</option>
        {foreach from=$select_start_date item=date key=date_key}
            <option value="{$date['value']}" {if !empty($rules) && $date_key == $value->time_start_format}selected{/if}>
                {$date['name']}
            </option>
        {/foreach}
    </select>
    {if empty($rules)}
    <select class="form-control pull-right wah200" name="rule_detail['{$date_type}'][dob5a46ti70fulkgldi]['time_end']">
    {else}
    <select class="form-control pull-right wah200" name="rule_detail['{$date_type}'][{$key}]['time_end']">
    {/if}
        <option value="">请选择时间</option>
        {foreach from=$select_end_date item=date key=date_key}
            <option value="{$date['value']}" {if !empty($rules) && ($date_key+1) == $value->time_end_format}selected{/if}>
                {$date['name']}
            </option>
        {/foreach}
    </select>
</div>