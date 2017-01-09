<div class="panel-body">
    <form id="venue_form">
        <div class="add-contest">
            <div class="form-group">
                <p>
                    <label>
                        1. 场馆名称 <b>*</b>
                    </label>
                </p>
                <input type="text" class="form-control" name="name" placeholder="请填写场馆名称" id="name" value="{if !empty($result)}{$result->name}{/if}">
            </div>
            <div class="form-group">
                <p>
                    <label>
                        2. 场馆项目 <b>*</b>(多选)
                    </label>
                </p>
                <div>
                    {foreach from=$allow_venue_types item=venue_type}
                    <label class="checkbox-inline">
                        <input type="checkbox" value="{$venue_type['tag_id']}" name="types[]" {if !empty($result) && in_array($venue_type[ 'tag_id'], $result->types)}checked{/if}> {$venue_type['name']}
                    </label>
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        3. 场馆地址
                        <b>*</b>
                    </label>
                </p>
                <div class="globel-select">
                    <div class="ipt-line location-box">
                        <input type="button" class="btn btn-sm btn-blue2 get-baidu-map" value="获取位置" targetid="poster">
                        <span>纬度</span>
                        <input type="text" class="form-control input-readonly watuo" id="map_lat" name="latitude" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->latitude}{/if}" readonly>
                        <span>经度</span>
                        <input type="text" class="form-control input-readonly watuo" id="map_lng" name="longitude" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->longitude}{/if}"  readonly>
                    </div>
                    <div class="location-box">
                        <span class="tmp-country">中国</span>
                        <input class="form-control input-readonly" type="hidden" id="tmp-country" name="locations['country']" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->locations['country']}{else}中国{/if}" readonly>
                        <span>省份</span>
                        <input class="form-control input-readonly" type="text" id="tmp-province" name="locations['province']" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->locations['province']}{/if}" readonly>
                        <span>城市</span>
                        <input class="form-control input-readonly" type="text" id="tmp-city" name="locations['city']" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->locations['city']}{/if}" readonly>
                        <span>地区</span>
                        <input class="form-control input-readonly" type="text" id="tmp-district" name="locations['district']" placeholder="点击获取位置获取" value="{if !empty($result)}{$result->locations['district']}{/if}" readonly>
                    </div>
                    <input type="text" class="form-control" id="streetNumber" name="address" placeholder="请填写详细信息" maxlength="50" value="{if !empty($result)}{$result->address}{/if}">
                    <p class="text-muted" id="preview-location"></p>
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        4. 开放时间 <b>*</b>
                    </label>
                </p>
                <div class="date-box clearfix mr20">
                    <div class="dt title">
                        工作日:
                    </div>
                    <div class="choose-date selct dd">
                        <select placeholder="请选择时间" class="pull-left form-control watuo " name="open_time['working_days']['start']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_start_date item=date key=date_key}
                            <option value="{$date['value']}" 
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['working_days']['start']}
                                    selected
                                {/if}
                            {/if}>
                            {$date['value']}</option>
                            {/foreach}
                        </select>
                        <select placeholder="请选择时间" class="pull-right form-control watuo " name="open_time['working_days']['end']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_end_date item=date key=date_key}
                            <option value="{$date['value']}" 
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['working_days']['end']}
                                selected
                                {/if}
                            {/if}>{$date['value']}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="date-box clearfix mr20">
                    <div class="dt title">
                        周末:
                    </div>
                    <div class="choose-date selct dd">
                        <select placeholder="请选择时间" class="pull-left form-control watuo" name="open_time['weekend']['start']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_start_date item=date key=date_key}
                            <option value="{$date['value']}" 
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['weekend']['start']}
                                selected
                                {/if}
                            {/if}>{$date['value']}</option>
                            {/foreach}
                        </select>
                        <select placeholder="请选择时间" class="pull-right form-control watuo" name="open_time['weekend']['end']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_end_date item=date key=date_key}
                            <option value="{$date['value']}" 
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['weekend']['end']}
                                    selected
                                {/if}
                            {/if}>{$date['value']}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="date-box clearfix mr20">
                    <div class="dt title">
                        节假日:
                    </div>
                    <div class="choose-date selct dd">
                        <select placeholder="请选择时间" class="pull-left form-control watuo" name="open_time['holidays']['start']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_start_date item=date key=date_key}
                            <option value="{$date['value']}"
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['holidays']['start']}
                                selected
                                {/if}
                            {/if}>{$date['value']}</option>
                            {/foreach}
                        </select>
                        <select placeholder="请选择时间" class="pull-right form-control watuo" name="open_time['holidays']['end']">
                            <option value="">请选择时间</option>
                            {foreach from=$select_end_date item=date key=date_key}
                            <option value="{$date['value']}" 
                            {if !empty($result)}
                                {if $date['value'] == $result->open_time['holidays']['end']}
                                selected
                                {/if}
                            {/if}>{$date['value']}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        5. 客服电话
                        <b>*</b>
                    </label>
                </p>
                <div class="label-listimg">
                    <input type="text" class="form-control" name="phone" maxlength="20" value="{if !empty($result)}{$result->phone}{/if}" placeholder="请填写客服电话">
                </div>
            </div>
            <div class="form-group">
                <p>
                    <label>
                        6. 场馆图片
                        <b>*</b>
                        <span class="text-muted">（尺寸 长*宽 960*640）</span>
                    </label>
                </p>
                <input type="file" name="files" class="imagesss uploadImage" />
                <input type="button" class="uploadImage btn btn-sm btn-blue" value="上传图片" targetId="poster" />
                <div class="img-thump poster" id="empty_poster" {if !empty($result)}style="display:none;" {/if}></div>
                <div class="row" id="poster_image_list"></div>
            </div>
            <div class="form-group">
                <p>
                    <label>7. 场馆介绍 <b>*</b></label>
                </p>
                <div style="max-width:800px;">
                    <div style="width:100%;height:360px;" id="editor">
                        {if !empty($result)}{$result->intro}{/if}
                    </div>
                </div>
            </div>
            <div class="pd10">
                {if !empty($result)}
                <button class="btn btn-default btn-blue mgr20" id="save_venue" data-save-type="edit" data-venue-id="{$result->venue_id}">编辑场馆</button>
                {else}
                <button class="btn btn-default btn-blue mgr20" id="save_venue">添加场馆</button>
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
