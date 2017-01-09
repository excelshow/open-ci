<div class="create-form units-mod hide">
    <div class="mark-item">
        <h5>活动组织单位</h5>
        <div class="form-group">
            <label style="width:200px">
                <select class="form-control" name="role" id="role">
                    <option value="">请选择组织角色</option>
                    {foreach from=$CONTEST_UNITS_LIST key=ukey item=uitem}
                    <option value="{$ukey}">{$uitem}</option>
                    {/foreach}
                </select>
            </label>
            <label>
                <input type="text" class="form-control btn-sm" name="tag" id="tag" placeholder="单位名称" style="width:200px">
            </label>
            <label><a class="btn btn-success btn-sm save-unit">新增</a></label>
            </p>
        </div>
        <!-- 组织单位列表 -->
        {if !empty($udata)}
        <ul class="list-group">
            {foreach from=$udata item=unitem}
            <li class="list-group-item" unid="{$unitem->pk_tag_units}">
                <strong>{$CONTEST_UNITS_LIST[$unitem->role]}</strong> : {$unitem->name}</li>
            {/foreach}
        </ul>
        {/if}
    </div>
</div>
<script type="text/javascript">
//保存组织单位
$(".save-unit").click(
    function() {
        var role = $("#role").val();
        var cid = _contestConfig.fk_contest;
        var tag = $("#tag").val();
        if (role == "") {
            $("#role").focus();
            return false;
        }
        if (tag == "") {
            $("#tag").focus();
            return false;
        }
        var postData = {
            "cid": cid,
            "tag": tag,
            "role": role
        }
        $.post(
            "/contest/contest/ajax_add_units", postData,
            function(data) {
                if (data['error'] == '0') {
                    window.location.reload();
                } else {
                    alert(data['info']);
                }
            }, 'json'
        );
        return false;
    }
)
</script>
