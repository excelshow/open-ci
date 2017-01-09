	<div class="tab-tit">
		<a href="javascript:;" class="pull-left item activity single_registration" rols="single_registration">单人报名</a>
		<a href="javascript:;" class="pull-left item team_registration" rols="team_registration">团队报名</a>
	</div>
	<table class="table txt-cen  personal" >
		<thead>
		<tr>
			<th>项目名称</th>
			<th>费用(元)</th>
			<th>上限人数</th>
			<th>当前库存</th>
			<th>是否需要邀请</th>
			<th>是否可一次买多张</th>
			<th>检票次数</th>
			<th>开始时间</th>
			<th>结束时间</th>
			<th>报名表</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody class="individual">
		{if !empty($itemdata)}
		{foreach from=$itemdata item=iitem}
			{if $iitem->type==1}
				<tr item_id="{$iitem->
				pk_contest_items}">
					<td>{$iitem->name}</td>
					<td>{$iitem->fee/100|string_format:"%.2f"}</td>
					<td>{$iitem->max_stock}</td>
					<td>{$iitem->cur_stock}</td>
					<td val="{$iitem->
					invite_required}">{$CONTEST_ITEM_INVITE_REQUIRED_OPTIONS[$iitem->invite_required]}
						{if $iitem->invite_required =="1"}
							<a href="/contest/contest/export_invite_code?item_id={$iitem->pk_contest_items}">下载</a>
						{/if}
					</td>
					<td>
						{if $iitem->multi_buy == 1}
							可以
						{elseif $iitem->multi_buy == 2}
							不可以
						{/if}
					</td>
					<td>{$iitem->max_verify}</td>
					<td>{$iitem->start_time}</td>
					<td data-a="{$itemEditEnable}">{$iitem->end_time}</td>
					<td>
						{if $itemEditEnable}
							{if empty($iitem->forminfo)}
								<a href="/contest/form/index?item_id={$iitem->pk_contest_items}" class="huobtn lightgreen-btn">添加
								</a>
							{else}
								<a href="/contest/form/detail_form?form_id={$iitem->
								forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightblue-btn">查看
								</a>
								<a href="/contest/form/index?form_id={$iitem->
								forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightyellow-btn">编辑
								</a>
							{/if}
						{else}
						<a href="/contest/form/detail_form?form_id={$iitem->forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightblue-btn">查看
						</a>
						{/if}
					</td>
					<td>
						{if $itemEditEnable}
							<a href="/contest/contest/edit_item?item_id={$iitem->pk_contest_items}" class="seedetails-btn" item_id="{$iitem->pk_contest_items}">编辑</a>
							
							<p></p>
							<a href="javascript:void(0);" class="seedetails-btn delete-items" item_id="{$iitem->pk_contest_items}">删除</a>
						{/if}
					</td>
				</tr>
			{/if}
		{/foreach}
		{else}
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		{/if}
		</tbody>
	</table>
	<table class="table txt-cen  mission-on" >
		<thead>
		<tr>
			<th>项目名称</th>
			<th>费用(元)</th>
			<th>团队人数</th>
			<th>团队个数</th>
			<th>当前库存</th>
			<th>是否需要邀请</th>
			<th>检票次数</th>
			<th>开始时间</th>
			<th>结束时间</th>
			<th>报名表</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody class="individual">
		{if !empty($itemdata)}
		{foreach from=$itemdata item=iitem}
			{if  $iitem->type==2}
				<tr  item_id="{$iitem->
				pk_contest_items}">
					<td>{$iitem->name}</td>
					<td>{$iitem->fee/100|string_format:"%.2f"}</td>
					<td>{$iitem->team_max_stock}</td>
					<td>{$iitem->team_size}</td>
					<td>{$iitem->team_cur_stock}</td>
					<td val="{$iitem->
					invite_required}">{$CONTEST_ITEM_INVITE_REQUIRED_OPTIONS[$iitem->invite_required]}
						{if $iitem->invite_required =="1"}
							<a href="/contest/contest/export_invite_code?item_id={$iitem->pk_contest_items}">下载</a>
						{/if}
					</td>
					<td>{$iitem->max_verify}</td>
					<td>{$iitem->start_time}</td>
					<td data-a="{$itemEditEnable}">{$iitem->end_time}</td>
					<td>
						{if $itemEditEnable}
							{if empty($iitem->forminfo)}
								<a href="/contest/form/index?item_id={$iitem->pk_contest_items}"  class="huobtn lightgreen-btn">添加
								</a>
							{else}
								<a href="/contest/form/detail_form?form_id={$iitem->
								forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightblue-btn">查看
								</a>
								<a href="/contest/form/index?form_id={$iitem->
								forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightyellow-btn">编辑
								</a>
							{/if}
						{else}
						<a href="/contest/form/detail_form?form_id={$iitem->forminfo->pk_enrol_form}&item_id={$iitem->forminfo->fk_contest_items}" class="huobtn lightblue-btn">查看
						</a>
						{/if}
					</td>
					<td>
						{if $itemEditEnable}
						<a href="/contest/contest/edit_item?item_id={$iitem->pk_contest_items}" class="seedetails-btn" item_id="{$iitem->pk_contest_items}">编辑</a>
						</p>
						<p>
							<a href="javascript:void(0);" class="seedetails-btn delete-items" item_id="{$iitem->pk_contest_items}">删除</a>
							{/if}
					</td>
				</tr>
			{/if}
			
		{/foreach}
		{else}
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		{/if}
		</tbody>
	</table>


<script type="text/javascript">
	$('.delete-items').click(function () {
		if (!confirm('确定删除吗?')) {
			return false;
		}
		var postData = {
			"item_id": $(this).attr('item_id')
		};
		$.post("/contest/contest/ajax_delete_items", postData, function (data) {
			if (data['error'] == '0') {
				window.location.reload();
			} else {
				alert(data['info']);
			}
		}, 'json');
	});
	$('.edit-items').click(function () {
		var nds = $(this).parent().parent().parent().find('td');
		$('#item_name').val($(nds[0]).text());
		$('#item_fee').val($(nds[1]).text());
		$('#item_max_stock').val($(nds[2]).text());
		$('select[name=invite_required]').val($(nds[4]).attr('val'));
		$('#item_start').val($(nds[5]).text());
		$('#item_end').val($(nds[6]).text());
		$('#pk_contest_items').val($(this).attr('item_id'));
		$('#add-item').text('保存修改');
	});
</script>
