{$h}
<input type="button" value="order_get" onclick="hehe();"/>
<input type="button" value="order_get_tpl" onclick="tpl();"/>
<script type="text/javascript" src="http://static.devel.wechatsport.cn/wisdomfront/lib/jquery.min.js"></script>
<script>
function hehe(){
        $.ajax({
            url: '/test/get?order_pk=5',
            method: 'GET'
        })
        .done(function (msg) {
            console.log(msg);
            msg = eval('(' + msg + ')');
        });
}
function tpl(){
        $.ajax({
            url: '/test/test',
            method: 'GET'
        })
        .done(function (msg) {
            console.log(msg);
            msg = eval('(' + msg + ')');
        });
}
</script>
