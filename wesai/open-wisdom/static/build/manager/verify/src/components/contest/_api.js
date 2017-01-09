   require('../../lib/js/callbacks.js');
   require('../../lib/js/deferred.js');


   function getDataCreator(url) {
     return function(params) {
       params = params || {};
       var def = $.Deferred();
       $.ajax({
         url: url,
         type: 'get',
         dataType: 'json',
         data: params,
         success: function(rs) {
             def.resolve(rs);
         },
         error:function(){
　　　　　　  $.toast('服务器繁忙，请稍后重试',1000);
            $.hidePreloader();
          }
       })
       return def;
     }
   }

   function postDataCreator(url) {
     return function(params) {
       params = params || {};
       var def = $.Deferred();
       $.ajax({
         url: url,
         type: 'post',
         dataType: 'json',
         data: params,
         success: function(rs) {
             def.resolve(rs);
         },
          error:function(){
　　　　　　  $.toast('服务器繁忙，请稍后重试',1000);
            $.hidePreloader();
          }
       })
       return def;
     }
   }


   //那二维码获取核销码
   module.exports.postQRCodeData = postDataCreator('/contest/verify/ajax_check_qrcode_data');
   //获取核销资料
   module.exports.ajax_getOrderById = postDataCreator('/contest/verify/ajax_getOrderById');
   //核销
   module.exports.ajax_verifyLoose = postDataCreator('/contest/verify/ajax_verifyLoose');

   module.exports.getItemInfo = getDataCreator('/contest/verify/getItemInfo');  




   
