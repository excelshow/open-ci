window.alert = function(msg){
    layer.msg(msg);
}
/**
 *检查url
 */
function IsURL(str_url) {
    var RegUrl = new RegExp();
    RegUrl.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=#]+$");
    if (!RegUrl.test(str_url)) {
        return false;
    }
    return true;
}
/**
 *参数说明：
 *截取字符串
 */
function cutString(str, len) {
    var str_length = 0;
    var str_len = 0;
    str_cut = new String();
    str_len = str.replace(/[^\x00-\xff]/g, '**').length;
    for (var i = 0; i < str_len; i++) {
        a = str.charAt(i);
        str_length++;
        if (escape(a).length > 4) {
            //中文字符的长度经编码之后大于4  
            str_length++;
        }
        if (str_length <= len) {
            str_cut = str_cut.concat(a);
        }
    }
    //如果给定字符串小于指定长度，则返回源字符串；  
    if (str_length < len) {
        return str;
    }
    return str_cut;
}
$(function(){
 setTimeout(function(){
    $(".loading-toast").remove();
 },"200")
})
