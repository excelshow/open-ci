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
           if (rs.error == '0') {
             def.resolve(rs);
           } else {
             alert(rs.info);
           }
         },
         error:function(){
             def.reject();
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
           if (rs.error == '0') {
             def.resolve(rs);
           } else {
             alert(rs.info);
           }
         },
         error:function(){
             def.reject();
         }
       })
       return def;
     }
   }

   venueShelve = new postDataCreator('/venue/venue/ajax_shelve');
   venueunShelve = new postDataCreator('/venue/venue/ajax_unshelve');
   siteStatusDisplay = new getDataCreator('/venue/times/ajax_get_by_area_res_id');
   venueStatusDisplay = new getDataCreator('/venue/times/ajax_get_by_venue_id');
   ajaxPostStopTimesTimes = new postDataCreator('/venue/times/ajax_post_stop_times');
   ajaxPostStartTimesTimes = new postDataCreator('/venue/times/ajxa_post_start_times');
   ajaxgetSearchManage = new getDataCreator('/venue/venue/ajxa_search_manage');
   ajxa_list_by_venue = new getDataCreator('/venue/venue/ajxa_list_by_venue');
   addVenue = new postDataCreator('/venue/venue/ajax_add');
   editVenue = new postDataCreator('/venue/venue/ajax_update');
   getVenueImages = new getDataCreator('/venue/venue/ajax_get_images');
   addAreaRes = new postDataCreator('/venue/area_res/ajax_add');
   editAreaRes = new postDataCreator('/venue/area_res/ajax_update');
   ajaxAreaResShelve = new getDataCreator('/venue/area_res/ajax_shelve');
   ajaxAreaResUnshelve = new getDataCreator('/venue/area_res/ajax_unshelve');
   ajaxAreaResToSell = new getDataCreator('/venue/area_res/ajax_to_sell');
   ajaxOrderSearch = new getDataCreator('/venue/order/ajax_search');
   ajaxDetailOrder = new getDataCreator('/venue/order/ajax_detail_order');
   ajaxStatistics = new getDataCreator('/statistics/list_data');
   order_verify = new postDataCreator('/venue/verifypc/order_verify');
   get_verify_list = new getDataCreator('/venue/verifypc/get_verify_list');













