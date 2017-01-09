{$h}
<input type="button" value="order_get" onclick="hehe();"/>
<script type="text/javascript" src="http://static.devel.wechatsport.cn/wisdomfront/lib/jquery.min.js"></script>
<script>
function hehe(){
        $.ajax({
            url: 'order/get?order_pk=5',
            method: 'GET'
        })
        .done(function (msg) {
            console.log(msg);
            msg = eval('(' + msg + ')');
        });
}
</script>
