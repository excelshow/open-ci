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
               error: function() {　
                    $.hidePreloader();
                    $.alert("服务器繁忙");
                    return;
                    def.reject()　　　
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
               error: function() {　
                    $.hidePreloader();
                    $.alert("服务器繁忙");
                    return;　　　　
                    def.reject()
               }
           })
           return def;
       }
   }

   exports.getVenueType = getDataCreator('/venue/get_functions_by_corp');
   exports.getVenueList = getDataCreator('/book/venue/search');
   exports.getVenueEvent = getDataCreator('/book/venue/get_times');
   exports.getUserTel = getDataCreator('/user/checkmobile');
   exports.getVenueDetails = getDataCreator('/venue/get');
   exports.getVenueImgList = getDataCreator('/venue/get_image_list');
   exports.getOrderMyList = getDataCreator('/order/mylist');
   exports.getOrderInfo = getDataCreator('/order/get_info');
   exports.getUser = getDataCreator('/order/get_user');
   exports.getUnused = getDataCreator('/order/get_unused');

   
   exports.sendTelCode = postDataCreator('/user/ajax_send_sms_verify_code');
   exports.getTelCode = postDataCreator('/user/ajax_verify_sms_code');
   exports.addOrder = postDataCreator('/venue/add_order');
   exports.orderClosed = postDataCreator('/order/state_closed');
