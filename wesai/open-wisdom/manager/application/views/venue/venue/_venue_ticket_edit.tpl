<div class="panel-body">
	<div class="add-contest">
		<div class="form-group">
			<p>
				<label>
		
					1. 场馆票名称 <b>*</b>
				</label>
			</p>
			<input type="text" class="form-control" name="name" placeholder="请填写场地名称" id="name" value="{if !empty($data)}{$data->name}{/if}"></div>
		<div class="form-group">
			<p>
				<label>
					2. 场地项目 <b>*</b>
					(单选)
				</label>
			</p>
			<div>
				<label class="checkbox-inline">
					<input type="radio" name="optionsRadiosinline" id="optionsRadios3" value="option1" checked> 选项 1</label>
				<label class="checkbox-inline">
					<input type="radio" name="optionsRadiosinline" id="optionsRadios4"  value="option2"> 选项 2</label>
				<label class="checkbox-inline">
					<input type="radio" name="optionsRadiosinline" id="optionsRadios4"  value="option2"> 选项 2</label>
				<label class="checkbox-inline">
					<input type="radio" name="optionsRadiosinline" id="optionsRadios4"  value="option2"> 选项 2</label>
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>
					3. 场馆票图片
					<b>*</b>
					<span class="text-muted">（尺寸 长*宽 164*240）</span>
				</label>
			</p>
			<input type="hidden" name="poster" id="poster" class="hiddenImg" value="010200005799665f00002e0599f9e6e1.jpg">
			<input type="file" name="files" class="imagesss uploadImage" sizeimg="?imageMogr2/thumbnail/164x240">
			<input type="button" class="btn btn-sm btn-blue uploadImage" value="上传图片" targetid="poster">
			<div class="img-thump img-thump-bg poster">
				<img src="http://img.wesai.com/010200005799665f00002e0599f9e6e1.jpg?imageMogr2/thumbnail/164x240"></div>
		</div>
		<div class="form-group">
			<p>
				<label>
					4. 价格
					<b>*</b>
				</label>
			</p>
			<div class="ub ub-ac ub-pc">
				<div>
					<label>
						<input type="radio" name="pic" value="1" > 统一价
					</label>
					<input type="text" class="form-control wah200 disInline mrl20"  value="" disabled>
				</div>
				<div class="f1 ub ub-pc">
					<label class="mrl20">
						<input type="radio" name="pic" value="2" checked> 不同价
					</label>
					<div class="mrl20">
						<p>工作日票：<input type="text" class="form-control wah50 disInline mrl20"  value=""> 元</p>
						<p>周末票：<input type="text" class="form-control wah50 disInline mrl20"  value=""> 元</p>
						<p>节假日票：<input type="text" class="form-control wah50 disInline mrl20"  value=""> 元</p>
					</div>
				</div>
				
			</div>
			
		</div>
		<div class="form-group">
			<p>
				<label>
					5. 核销次数
					<b>*</b>
				</label>
			</p>
			<div class="label-listimg">
				<input type="text" class="form-control wah200" name="service_tel" id="service_tel" value="">
			</div>
		</div>
		<div class="form-group">
			<p>
				<label>
					6. 库存
					<b>*</b>
				</label>
			</p>
			<label>
				<input type="radio" name="deliver_gear" value="1" > 有 限
			</label>
			<input type="text" class="form-control wah200 disInline mrl20"  value="" disabled>
			<label class="mrl20">
				<input type="radio" name="deliver_gear" value="2" checked> 无限
			</label>
		</div>
		
		<div class="form-group">
			<p>
				<label>
					7. 报名须知
					<b>*</b>
					<span class="text-muted">（活动基本信息，报名信息等内容）</span>
				</label>

			</p>
			<div style="max-width:800px;">
				<div style="width:100%;height:360px;" id="editor">{if !empty($data)}{$data->intro}{/if}</div>
			</div>

		</div>
		<div class="pd10">
			<button class="btn btn-default btn-blue mgr20" id="savecontest">添加场地</button>
			<button type="button" class="btn btn-default btn-cancel" onclick="window.history.go(-1)">取消</button>
			<div></div>
		</div>
	</div>
</div>

<script src="{'manager/lib/vendor/jquery.ui.widget.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.iframe-transport.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-process.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-validate.js'|cdnurl}"></script>
<script src="{'manager/venue/js/main_img.js'|cdnurl}"></script>
