<div class="panel-body">
	<div class="add-contest">
		<div class="form-group">
			<p>
				<label>
					1. 活动中文名 <b>*</b>
				</label>
			</p>
			<input type="text" class="form-control" name="name" placeholder="请填写活动名称" id="name" value="{if !empty($data)}{$data->name}{/if}">
		</div>
		<div class="form-group">
			<p>
				<label>
					2. 活动LOGO
					<b>*</b>
					<span class="text-muted">（尺寸 长*宽 120*120）</span>
				</label>
			</p>
			<input type="hidden" name="logo" id="logo" class="hiddenImg" value="{if !empty($data)}{$data->logo}{/if}">
			<input type="file" name="files" class="imagesss uploadImage" sizeImg="?imageMogr2/thumbnail/84x84"/>
			<input type='button' class="btn btn-sm btn-blue uploadImage" value="上传图片" targetId="logo"/>
			<div class="img-thump {if !empty($data)}img-thump-bg{/if} logo">
				{if !empty($data)}<img class="img-responsive" src='{RES_FILEVIEW_URL}/{$data->logo}?imageMogr2/thumbnail/84x84'>{/if}
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>
					3. 活动海报图片
					<b>*</b>
					<span class="text-muted">（尺寸 长*宽 164*240）</span>
				</label>
			</p>
			<input type="hidden" name="poster" id="poster" class="hiddenImg" value="{if !empty($data)}{$data->poster}{/if}">
			<input type="file" name="files" class="imagesss uploadImage" sizeImg="?imageMogr2/thumbnail/164x240"/>
			<input type="button" class="uploadImage btn btn-sm btn-blue" value="上传图片" targetId="poster"/>
			<div class="img-thump {if !empty($data)}img-thump-bg{/if} poster">
				{if !empty($data)}<img src='{RES_FILEVIEW_URL}/{$data->poster}?imageMogr2/thumbnail/164x240'>{/if}
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>
					4. 活动横幅图片
					<b>*</b>
					<span class="text-muted">（尺寸 长*宽 750*236）</span>
				</label>
			</p>
			<input type="hidden" name="banner" id="banner" class="hiddenImg" value="{if !empty($data)}{$data->banner}{/if}">
			<input type="button" class="uploadImage btn btn-sm btn-blue" value="上传图片" targetId="banner"/>
			<input type="file" name="files" class="imagesss uploadImage" sizeImg="?imageMogr2/thumbnail/750x236"/>
			<div class="img-thump {if !empty($data)}img-thump-bg{/if} banner">
				{if !empty($data)}<img src='{RES_FILEVIEW_URL}/{$data->banner}?imageMogr2/thumbnail/750x236'>{/if}
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>
					5. 活动日期
					<b>*</b>
				</label>
			</p>
			<input type="text" class="form-control width200 datetimepicker pull-left" name="start" id="sdate_start" placeholder="请选择日期" readonly value="{if !empty($data)}{$data->sdate_start}{/if}"> 
			<span class="horizontal-line"></span>
			<input type="text" class="form-control width200 datetimepicker pull-left" name="end" id="sdate_end" placeholder="请选择日期" readonly value="{if !empty($data)}{$data->sdate_end}{/if}">
		</div>
		<div class="form-group">
			<p>
				<label>
					6. 比赛地点
					<b>*</b>
				</label>
			</p>
			<label>
				<input type="radio" name="country_scope" value="1"  {if !empty($data)}{if $data->
				country_scope == 1} checked="checked"  {/if}    {else} checked="checked" {/if}>
							          国内
			</label>
			<label>
				<input type="radio" name="country_scope"  value="2" {if !empty($data)}{if $data->
				country_scope == 2} checked="checked"  {/if} {/if} >
							          国外
			</label>
			<div class="globel-select" id="locationBox">
				<div class="location-box">{include file="../contest/edititem/_locationcity.tpl"}</div>
			</div>
			<input type="text" class="form-control" name="location" id="location" placeholder="详细活动地址" maxlength="50" value="{if !empty($data)}{$data->location}{/if}">
			<p class="text-muted" id="preview-location"></p>
		</div>
		<div class="form-group">
			<p>
				<label>
					7. 活动类型
					{if empty($data)}
						<b>*</b>
						<span class="text-muted">（慎重选择,不可变更）</span>
					{/if}
				</label>
			</p>
			<div class="tableline">
				{if !empty($data)}
					{foreach from=$CONTEST_GTYPE_LIST key=key item=item}
						{if $data->gtype == $key}{$item}{/if}
					{/foreach}
					<input type="hidden" name="gtype" id="gtype" value="{$data->gtype}">
				{else}
					<select name="gtype" class="form-control" id="gtype">
						<option value="{$smarty.const.CONTEST_GTYPE_DEFAULT}">其他</option>
						<option value="{$smarty.const.CONTEST_GTYPE_MALATHION}">马拉松</option>
					</select>
				{/if}
			</div>
		</div>
		{*<div class="form-group">*}
			{*<p>*}
				{*<label>*}
					{*8. 是否邮寄装备*}
					{*<b>*</b>*}
				{*</label>*}
			{*</p>*}
			{*<label>*}
				{*<input type="radio" name="deliver_gear" value="1" {if !empty($data)}{if $data->*}
				{*deliver_gear == 1} checked="checked" {/if}{/if}>*}
				{*需要邮寄装备*}
			{*</label>*}
			{*<label>*}
				{*<input type="radio" name="deliver_gear" {if !empty($data)}{if $data->*}
				{*deliver_gear == 2} checked="checked" {/if} {/if} value="2">*}
				{*无需邮寄装备*}
			{*</label>*}
		{*</div>*}
		<div class="form-group">
			<p>
				<label>
					8. 客服电话
					<b>*</b>
				</label>
			</p>
			<div class="label-listimg">
				<input type="text" class="form-control width200 pull-left" name="service_tel" id="service_tel" maxlength="20" value="{if !empty($data)}{$data->service_tel}{/if}" placeholder="请填写客服电话">
				<span class="pull-left service_tel">或</span>
				<input type="text" class="form-control width200 pull-left" name="service_mail" id="service_mail" maxlength="20" value="{if !empty($data)}{$data->service_mail}{/if}" placeholder="请填写邮箱">
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>9. 报名须知 <b>*</b>
					<span class="text-muted">（活动基本信息，报名信息等内容）</span>
				</label>
			</p>
			<div style="max-width:800px;">
				<div style="width:100%;height:360px;" id="editor">
					{if !empty($data)}{$data->intro}{/if}
				</div>
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>10. 请选择页面模板 <b>*</b>
					<span class="text-muted"></span>
				</label>
			</p>
			<div class="main-img-rabio">
				<label for="template_1">
					<input type="radio" name="template" id="template_1" value="1" {if !empty($data) && $data->template == 1}checked="checked"{/if} /> 标准报名模板
					<div class="img-main"><img src="{'manager_contest/img/sign_up.png'|cdnurl}" alt=""></div>
				</label>
				<label for="template_2">
					<input type="radio" name="template" id="template_2" value="2" {if !empty($data) && $data->template == 2}checked="checked"{/if} /> 购票模板
					<div class="img-main"><img src="{'manager_contest/img/tickets.png'|cdnurl}" alt=""></div>
				</label>
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>10. 是否显示已售数目 <b>*</b>
					<span class="text-muted"></span>
				</label>
			</p>
			<div class="main-img-rabio">
				<label for="show_enrol_data_count_1">
					<input type="radio" name="show_enrol_data_count" id="show_enrol_data_count_1" value="1" {if !empty($data) && $data->show_enrol_data_count == 1}checked="checked"{/if} /> 是
				</label>
				<label for="show_enrol_data_count_2">
					<input type="radio" name="show_enrol_data_count" id="show_enrol_data_count_2" value="2" {if !empty($data) && $data->show_enrol_data_count == 2}checked="checked"{/if} /> 否
				</label>
			</div>
		</div>
		<div class="pd10">
			{if !empty($data)}
				<button class="btn btn-default btn-blue mgr20" id="savecontest">编辑活动</button>
			{else}
				<button class="btn btn-default btn-blue mgr20" id="savecontestAdd">创建活动</button>
			{/if}
			<button class="btn btn-default btn-cancel mgr20 hide" id="savecontestloging">提交中</button>
			<button type="button" class="btn btn-default btn-cancel" onclick="window.history.go(-1)">取消</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	serverPath = '{RES_FILEVIEW_URL}/';
</script>
<script src="{'manager/lib/vendor/jquery.ui.widget.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.iframe-transport.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-process.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-validate.js'|cdnurl}"></script>
<script src="{'manager/js/main_img.js'|cdnurl}"></script>
