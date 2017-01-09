<?php

require_once dirname(dirname(__DIR__)) . '/Base.php';
require_once __DIR__ . '/Schema.php';

class ContestIndex extends Base
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getHostName()
	{
		return API_HOST_OPEN_CONTEST;
	}

	public function getAllowedApiList()
	{
		return array();
	}

    private function getValidSellerCorpIds($contest_id, $owner_fk_corp){
        $seller_fk_corp = array();
        $seller_fk_corp[] = $owner_fk_corp;

        $page      = 1;
        $size      = 100;
        while (true) {
            $sellers = $this->callInnerApiDiy(API_HOST_OPEN_DIST, 'contest/choose/list_valid_seller_by_contest_id.json', compact('contest_id', 'page', 'size'), METHOD_GET);
            if(empty($sellers) || empty($sellers->data)){
                break;
            }
            foreach($sellers->data as $k=>$v){
                $seller_fk_corp[] = $v->seller_corp_id;
            }
            $page++;
        }

        return implode(',', $seller_fk_corp);
    }

	public function index()
	{
		try {
			echo Schema::getDocHeader();
			echo Schema::getDocSchemaContest();

			$docFormat = Schema::getDocFormatContest();
			$page      = 1;
			$size      = 20;
			while (true) {
				$contestList = $this->callInnerApiDiy($this->getHostName(), 'contest/list.json', compact('page', 'size'), METHOD_GET);
				$contestList = $this->obj2array($contestList);
				if (empty($contestList['data'])) {
					break;
				}
				$contestList = $contestList['data'];

				$contestIds = array_column($contestList, 'pk_contest');

				$cids    = implode(',', $contestIds);
				$tagList = $this->callInnerApiDiy($this->getHostName(), 'tag/list_location_by_cids.json', compact('cids'), METHOD_GET);
				$tagList = $this->obj2array($tagList);
				if (!empty($tagList['data'])) {
					$tagList = $tagList['data'];
					foreach ($contestList as $k => &$contest) {
						foreach ($tagList as $key => $tag) {
							if ($contest['pk_contest'] == $tag['fk_contest']) {
								$contestList[$k]['locations'][] = $tag['fk_tag'];
								$contestList[$k]['levels'][]    = $tag['level'];

								unset($tagList[$key]);
							}
						}
                        $contest['seller_fk_corp'] = $this->getValidSellerCorpIds($contest['pk_contest'], $contest['fk_corp']);
					}
				}

				foreach ($contestList as $key => $value) {
					echo sprintf(
						$docFormat,
						$value['pk_contest'],
						$value['fk_corp'],
						$value['name'] . ' ' . $value['ename'],
						$value['gtype'],
						$value['publish_state'],
						strtotime($value['sdate_start']),
						strtotime($value['sdate_end']),
						!empty($value['locations']) ? implode(',', $value['locations']) : 0,
						!empty($value['levels']) ? implode(',', $value['levels']) : 0,
                        !empty($value['seller_fk_corp'])?$value['seller_fk_corp'] : '',
						strtotime($value['ctime']),
						$value['template'],
                        $value['sort_weight'],
                        strtotime($value['utime'])
					);
				}
				$page++;
			}
			echo Schema::getDocFooter();
		} catch (Exception $e) {
			$this->catchException($e);
		}
	}
}

