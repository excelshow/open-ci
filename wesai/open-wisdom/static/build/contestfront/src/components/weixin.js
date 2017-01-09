
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: APPID, // 必填，公众号的唯一标识
    timestamp: TIMESTAMP, // 必填，生成签名的时间戳
    nonceStr: NONCESTR, // 必填，生成签名的随机串
    signature: SIGNATURE,// 必填，签名，见附录1
    jsApiList: [
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'checkJsApi',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'chooseWXPay'
    ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});



//微信分享朋友
function wxshareContent(shareData)
{
  wx.ready(function(){
    wx.onMenuShareAppMessage(shareData);
  });
}
//微信分享朋友圈
function wxshareContentLine(shareData)
{
  wx.ready(function() {
    wx.onMenuShareTimeline(shareData);
  });
}