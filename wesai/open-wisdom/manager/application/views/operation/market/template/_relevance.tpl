<div class="dialog-bg" id="dialog" style="display:none"></div>
{literal}
<script id="dialog-template" type="text/x-handlebars-template">
    <div class="dialog-box">
        <div class="dialog-tit">
            <h3>{{tittle}}</h3>
        </div>
        <div class="dialog-con">
            <div class="dialog-info">
                {{#each message}}
                <p>{{content}}</p>
                {{/each}}
            </div>
            <div class="dialog-btn ub ub-ac ub-pc">
                <a href="javascript:;" class="btn confirm-bg" data-venue-times-id="{{timesId}}" data-changeType="{{changeType}}">{{confirm}}</a>
                <a href="javascript:;" class="btn cancel-bg">{{cancel}}</a>
            </div>
        </div>
    </div>
</script>
{/literal}
