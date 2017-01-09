<div class="panel-body event-details operation-coupon">
    <div class="filter-condition">
       <div class="filter-title clearfix">
            <p>活动筛选</p>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">查询优惠券规则:</div>
            <div class="dd">
                <input type="text">
            </div>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">请输入卡密查询:</div>
            <div class="dd">
                <input type="text">
            </div>
        </div>
        <div class="filter-type clearfix">
            <div class="dt">卡密状态:</div>
            <div class="dd">
                <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">全部</a>
                <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">未发放</a>
                <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">未使用</a>
                <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">已使用</a>
                <a class="link_gtype types" data-params="types" data-type="" href="javascript:;">已废弃</a>
            </div>
        </div>
        <div></div>
    </div>
</div>
<div id="orderList" class="wesai-dist">
    <table class="table txt-cen">
        <thead class="bg-f6f3f3">
            <tr>
                <th>卡密</th>
                <th>截止日期</th>
                <th>面值金额(元)</th>
                <th>最低使用金额(元)</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        {if !empty($data)} {foreach from=$data item=item}
        <tbody>
            <tr>
                <td>{$item->dist_contest_id}</td>
                <td>北京微赛科技时代有限公司</td>
                <td>1000000</td>
                <td>{$item->rebate_seller}</td>
                <td>20161010101010</td>
                <td class="refund-success">人工发放</td>
            </tr>
        </tbody>
        {/foreach} {/if}
    </table>
</div>
