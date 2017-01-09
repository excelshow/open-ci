<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class OrderIndex extends Base {
	public function __construct(){
		parent::__construct();
	}

    public function setHostName(){
        return API_HOST_OPEN_VENUE;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_VENUE => array(
                //'venue/get.json'=>'venue/get.json',
                //'venue/list_by_pk.json'=>'venue/list_by_pk.json'
            )
        );
    }

	public function index(){
		echo $this->xmlpipe2_doc_header();
		echo $this->xmlpipe2_doc_schema();

        $order_id = 0;
		$size = 50;
		while (true) {
            $orders = $this->getOrders($order_id, $size);
			if (empty($orders->data)) {
				break;
			}
			foreach ($orders->data as $d) {
                $venue_area = $this->getVenueAreaInfo($d->venue_area_id);
                if(empty($venue_area) || empty($venue_area->result)){
                    log_message('error', '构建索引异常 getVenueAreaInfo');
                    continue;
                }
                $venue = $this->getVenueInfo($d->venue_id);
                if(empty($venue) || empty($venue->result)){
                    log_message('error', '构建索引异常 getVenueInfo');
                    continue;
                }
                /*
                $app = $this->getAInfo($d->venue_id);
                if(empty($venue) || empty($venue->result)){
                    log_message('error', '构建索引异常 getVenueInfo');
                    continue;
                }
                */

                echo $this->dataXml($d, $venue->result, $venue_area->result);

                $order_id = $d->order_id;
			}
		}
		echo $this->xmlpipe2_doc_footer();
	}

    private function dataXml($order, $venue, $venue_area){
        $order_res_times = $this->getTagInfo($order->res_times, 'venue_area_res_times_id');
        return sprintf(
            $this->xmlpipe2_doc_data(),
            $order->order_id,
            $order->order_id,
            $venue->name,
            '', //app name
            $order->venue_id,
            $order->venue_area_id,
            $order->owner_corp_id,
            $order->seller_corp_id,
            $order->component_authorizer_app_id,
            $order->user_id,
            $order->state,
            $order->is_use,
            $order->out_trade_no,
            $order->out_refund_no,
            $order->channel_id,
            $order->channel_account,
            $order->amount,
            $order->amount_pay,
            $order->mobile,
            strtotime($order->paid_time),
            $order->order_source,
            $venue_area->function,
            strtotime($order->ctime),
            $order_res_times
        );
    }

    private function getOrders($order_id, $size){
        $params = array('order_id'=>$order_id, 'size'=>$size);
        $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/list_by_pk.json', $params, METHOD_GET);
        return $data;
    }

    private function getVenueAreaInfo($venue_area_id){
        $params = array('venue_area_id'=>$venue_area_id);
        $data = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area/get.json', $params, METHOD_GET);
        return $data;
    }

    private function getVenueInfo($venue_id){
        $params = array('venue_id'=>$venue_id);
        $data = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', $params, METHOD_GET);
        return $data;
    }

    private function getTagInfo($tags, $key){
        $infos = array();
        if(!empty($tags)){
            foreach($tags as $tag){
                $infos[] = $tag->$key;
            }
        }

        $info = implode(',', $infos);
        return $info;
    }

    private function xmlpipe2_doc_schema(){
		$schema = '<sphinx:schema>' . PHP_EOL .
		          '<sphinx:field name="venue_name"/>' . PHP_EOL .
		          '<sphinx:field name="app_name"/>' . PHP_EOL .
		          '<sphinx:attr name="order_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="venue_name" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="app_name" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="venue_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="venue_area_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="owner_corp_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="seller_corp_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="component_authorizer_app_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="user_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="state" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="is_use" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="out_trade_no" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="out_refund_no" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="channel_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="channel_account" type="string"/>' . PHP_EOL .
                  '<sphinx:attr name="amount" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="amount_pay" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="mobile" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="paid_time" type="timestamp"/>' . PHP_EOL .
		          '<sphinx:attr name="order_source" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="type" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="ctime" type="timestamp"/>' . PHP_EOL .
		          '<sphinx:attr name="res_times" type="multi"/>' . PHP_EOL .
		          '</sphinx:schema>' . PHP_EOL;

		return $schema;
    }
    
    private function xmlpipe2_doc_data(){
		$data = '<sphinx:document id="%d">' . PHP_EOL .
		          '<order_id>%d</order_id>' . PHP_EOL .
		          '<venue_name><![CDATA[%s]]></venue_name>' . PHP_EOL .
		          '<app_name><![CDATA[%s]]></app_name>' . PHP_EOL .
		          '<venue_id>%d</venue_id>' . PHP_EOL .
		          '<venue_area_id>%d</venue_area_id>' . PHP_EOL .
		          '<owner_corp_id>%d</owner_corp_id>' . PHP_EOL .
		          '<seller_corp_id>%d</seller_corp_id>' . PHP_EOL .
		          '<component_authorizer_app_id>%d</component_authorizer_app_id>' . PHP_EOL .
		          '<user_id>%d</user_id>' . PHP_EOL .
		          '<state>%d</state>' . PHP_EOL .
		          '<is_use>%d</is_use>' . PHP_EOL .
		          '<out_trade_no>%s</out_trade_no>' . PHP_EOL .
		          '<out_refund_no>%s</out_refund_no>' . PHP_EOL .
		          '<channel_id>%d</channel_id>' . PHP_EOL .
		          '<channel_account>%d</channel_account>' . PHP_EOL .
                  '<amount>%d</amount>' . PHP_EOL .
		          '<amount_pay>%d</amount_pay>' . PHP_EOL .
		          '<mobile>%s</mobile>' . PHP_EOL .
		          '<paid_time>%d</paid_time>' . PHP_EOL .
		          '<order_source>%d</order_source>' . PHP_EOL .
		          '<type>%d</type>' . PHP_EOL .
		          '<ctime>%d</ctime>' . PHP_EOL .
		          '<res_times>%s</res_times>' . PHP_EOL .
		          '</sphinx:document>' . PHP_EOL;

		return $data;
    }

}

