   require('../lib/js/callbacks.js');
   require('../lib/js/deferred.js');


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

   module.exports.getList = getDataCreator('/contest/verify/ajax_list_verifying_items');
   module.exports.postQRCodeData = postDataCreator('/contest/verify/ajax_check_qrcode_data');
   module.exports.postDoVerify = postDataCreator('/contest/verify/ajax_do_verify');
   module.exports.ajax_getOrderById = postDataCreator('/contest/verify/ajax_getOrderById');
   module.exports.ajax_verifyLoose = postDataCreator('/contest/verify/ajax_verifyLoose');







   
