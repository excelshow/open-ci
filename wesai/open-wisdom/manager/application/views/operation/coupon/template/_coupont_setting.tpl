<div class="panel-body">
    <form id="venue_form">
        <div class="add-contest">
            <div class="form-group">
                <p>
                    <label>
                        1. 优惠券名称 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="此名称会显示在用户账户中，请尽量描述清晰" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        2. 优惠券开始日期 <b>*</b>
                    </label>
                </p>
                <div class="date-box clearfix mr20">
                    <div class="choose-date">
                        <input type="text" id="inpstart" class="pull-left" value="">
                        <span class="horizontal-line"></span>
                        <input type="text" id="inpend" value="" class="pull-right">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        3. 优惠券截止日期 <b>*</b>
                    </label>
                </p>
                <div class="date-box clearfix mr20">
                    <div class="choose-date">
                        <input type="text" id="inpstart" class="pull-left" value="">
                        <span class="horizontal-line"></span>
                        <input type="text" id="inpend" value="" class="pull-right">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        4. 优惠券数量 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="最多100000张" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>
           <div class="form-group">
                <p>
                    <label>
                        5. 优惠券面值金额 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="请填写金额（元）" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>
           <div class="form-group">
                <p>
                    <label>
                        6. 优惠券最低使用金额 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="请填写金额（元）" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>

            <div class="pd10">
                {if !empty($result)}
                <button class="btn btn-default btn-blue mgr20" id="save_venue" data-save-type="edit" data-venue-id="{$result->venue_id}">保存</button>
                {else}
                <button class="btn btn-default btn-blue mgr20" id="save_venue">添加</button>
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
<script src="{'manager/lib/vendor/jquery.ui.widget.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.iframe-transport.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-process.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery.fileupload-validate.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<script src="{'manager/venue/js/config.js'|cdnurl}"></script>
<script src="{'manager/venue/js/operation_venue.js'|cdnurl}"></script>
{literal}
<script id="poster-images-template" type="text/x-handlebars-template">
    <div class="col-sm-3 venue-photos mr20">
        <figure class="image-group">
            <a href="javascript:;">
                <img class="img-responsive" src="{{serverPath}}{{fileid}}" alt="">
                <div class="close-button" title="删除图片"></div>
            </a>
            <figcaption style="display:none;">
                <input type="text" placeholder="请添加标签" class="form-control">
            </figcaption>
        </figure>
        <input type="hidden" name="images[]" value="{{fileid}}">
    </div>
</script>
{/literal}
