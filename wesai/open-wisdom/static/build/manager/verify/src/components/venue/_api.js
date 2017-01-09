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

   module.exports.getList = getDataCreator('/contest/verify/ajax_list_verifying_items');
   module.exports.postQRCodeDataVenue = postDataCreator('/venue/verify/ajax_check_qrcode_data');
   module.exports.postDoVerifyVenue = postDataCreator('/venue/verify/ajax_do_verify');
   module.exports.ajax_getOrderByIdVenue = postDataCreator('/venue/verify/ajax_getOrderById');
   module.exports.ajax_verifyLooseVenue = postDataCreator('/venue/verify/ajax_verifyLoose');


