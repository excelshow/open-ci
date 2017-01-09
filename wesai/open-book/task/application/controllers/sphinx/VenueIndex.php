<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class VenueIndex extends Base {
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

        $venue_id = 0;
		$size = 10;
		while (true) {
            $venues = $this->getVenues($venue_id, $size);
			if (empty($venues->data)) {
				break;
			}
			foreach ($venues->data as $d) {
                $venue = $this->getVenueInfo($d->venue_id);
                if(empty($venue) || empty($venue->result)){
                    log_message('error', '构建索引异常 getVenueInfo');
                    continue;
                }

                echo $this->dataXml($venue->result);

                $venue_id = $venue->result->venue_id;
			}
		}
		echo $this->xmlpipe2_doc_footer();
	}

    private function dataXml($venue){
        $locations = $this->getTagInfo($venue->locations, 'tag_id');
        $locations_level = $this->getTagInfo($venue->locations, 'level');
        $services = $this->getTagInfo($venue->services, 'tag_id');
        $types = $this->getTagInfo($venue->types, 'tag_id');
        $functions = implode(',', $venue->functions);

        return sprintf(
            $this->xmlpipe2_doc_data(),
            $venue->venue_id,
            $venue->name,
            $venue->intro,
            $venue->address,
            $venue->corp_id,
            $venue->venue_id,
            $venue->state,
            $venue->longitude,
            $venue->latitude,
            $venue->service_type,
            $locations,
            $locations_level,
            $types,
            $services,
            $functions,
            strtotime($venue->ctime)
        );
    }

    private function getVenues($venue_id, $size){
        $params = array('venue_id'=>$venue_id, 'size'=>$size);
        $data = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/list_by_pk.json', $params, METHOD_GET);
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
		          '<sphinx:field name="name"/>' . PHP_EOL .
		          '<sphinx:field name="intro"/>' . PHP_EOL .
		          '<sphinx:field name="address"/>' . PHP_EOL .
		          '<sphinx:attr name="name" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="address" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="corp_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="venue_id" type="int" default="0"/>' . PHP_EOL .
		          '<sphinx:attr name="state" type="int" default="0"/>' . PHP_EOL .
                  '<sphinx:attr name="longitude" type="string"/>' . PHP_EOL .
                  '<sphinx:attr name="latitude" type="string"/>' . PHP_EOL .
		          '<sphinx:attr name="service_type" type="int" default="0"/>' . PHP_EOL .
                  '<sphinx:attr name="locations" type="multi"/>' . PHP_EOL .
                  '<sphinx:attr name="locations_level" type="multi"/>' . PHP_EOL .
                  '<sphinx:attr name="types" type="multi"/>' . PHP_EOL .
                  '<sphinx:attr name="services" type="multi"/>' . PHP_EOL .
                  '<sphinx:attr name="functions" type="multi"/>' . PHP_EOL .
                  '<sphinx:attr name="ctime" type="timestamp"/>' . PHP_EOL .
		          '</sphinx:schema>' . PHP_EOL;

		return $schema;
    }
    
    private function xmlpipe2_doc_data(){
		$data = '<sphinx:document id="%d">' . PHP_EOL .
		          '<name><![CDATA[%s]]></name>' . PHP_EOL .
		          '<intro><![CDATA[%s]]></intro>' . PHP_EOL .
		          '<address><![CDATA[%s]]></address>' . PHP_EOL .
		          '<corp_id>%d</corp_id>' . PHP_EOL .
		          '<venue_id>%d</venue_id>' . PHP_EOL .
		          '<state>%d</state>' . PHP_EOL .
		          '<longitude>%s</longitude>' . PHP_EOL .
                  '<latitude>%s</latitude>' . PHP_EOL .
		          '<service_type>%s</service_type>' . PHP_EOL .
		          '<locations>%s</locations>' . PHP_EOL .
		          '<locations_level>%s</locations_level>' . PHP_EOL .
		          '<types>%s</types>' . PHP_EOL .
		          '<services>%s</services>' . PHP_EOL .
		          '<functions>%s</functions>' . PHP_EOL .
		          '<ctime>%d</ctime>' . PHP_EOL .
		          '</sphinx:document>' . PHP_EOL;

		return $data;
    }

}

