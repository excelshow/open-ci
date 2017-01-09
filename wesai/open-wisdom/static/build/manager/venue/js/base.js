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
});

// 价格判断
var pc_venue = {
    price:''
};
function priceReg(val){
    var _val = val.value;
    var priceReg=/^\d{0,8}\.{0,1}(\d{1,2})?$/;
    if(priceReg.test(_val)){
        if (_val > 999999.99) {
            alert("最大不能超过六位");
            return pc_venue.price;
        }else{
            pc_venue.price = _val;
            return _val;
        }
    }else{
        return pc_venue.price;
    }
    function decimalPoint(str){
        var index = str.indexOf('.');
        var _index = str.indexOf('.',index+1);
        if (_index > -1) {
            return true;
        }
        return false;
    }
}

/**
 * getFormData
 * 获取form表单中的数据
 *
 * @param string formId 表单ID
 * return object
 */
 function getFormData(formId) {
    // 根据form获取表单数据
    var data = $('#' + formId).serialize();

    // 解析有jq获取的form数据
    function __parseStr(str) {
        var array = {};
        var strArr = String(str).replace(/^&/, '').replace(/&$/, '').split('&'),
            sal = strArr.length,
            i, j, ct, p, lastObj, obj, lastIter, undef, chr, tmp, key, value,
            postLeftBracketPos, keys, keysLen,
            fixStr = function (str) {
                return decodeURIComponent(str.replace(/\+/g, '%20'));
            };

        for (i = 0; i < sal; i++) {
            tmp = strArr[i].split('=');
            key = fixStr(tmp[0]);
            value = (tmp.length < 2) ? '' : fixStr(tmp[1]);

            while (key.charAt(0) === ' ') {
                key = key.slice(1);
            }
            if (key.indexOf('\x00') > -1) {
                key = key.slice(0, key.indexOf('\x00'));
            }
            if (key && key.charAt(0) !== '[') {
                keys = [];
                postLeftBracketPos = 0;
                for (j = 0; j < key.length; j++) {
                    if (key.charAt(j) === '[' && !postLeftBracketPos) {
                        postLeftBracketPos = j + 1;
                    } else if (key.charAt(j) === ']') {
                        if (postLeftBracketPos) {
                            if (!keys.length) {
                                keys.push(key.slice(0, postLeftBracketPos - 1));
                            }
                            keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos));
                            postLeftBracketPos = 0;
                            if (key.charAt(j + 1) !== '[') {
                                break;
                            }
                        }
                    }
                }
                if (!keys.length) {
                    keys = [key];
                }
                for (j = 0; j < keys[0].length; j++) {
                    chr = keys[0].charAt(j);
                    if (chr === ' ' || chr === '.' || chr === '[') {
                        keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
                    }
                    if (chr === '[') {
                        break;
                    }
                }

                obj = array;
                for (j = 0, keysLen = keys.length; j < keysLen; j++) {
                    key = keys[j].replace(/^['"]/, '').replace(/['"]$/, '');
                    lastIter = j !== keys.length - 1;
                    lastObj = obj;
                    if ((key !== '' && key !== ' ') || j === 0) {
                        if (obj[key] === undef) {
                            obj[key] = {};
                        }
                        obj = obj[key];
                    } else { // To insert new dimension
                        ct = -1;
                        for (p in obj) {
                            if (obj.hasOwnProperty(p)) {
                                if (+p > ct && p.match(/^\d+$/g)) {
                                    ct = +p;
                                }
                            }
                        }
                        key = ct + 1;
                    }
                }
                lastObj[key] = value;
            }
        }

        return array;
    }

    return __parseStr(data);
 }
