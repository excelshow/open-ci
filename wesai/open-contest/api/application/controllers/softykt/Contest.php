<?php
/**
 * User: zhaodc
 * Date: 05/12/2016
 * Time: 20:51
 */


require_once __DIR__ . '/../Base.php';

class Contest extends Base
{

    /**
     * Contest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/ContestSoftykt_model');
        $this->load->model('Contest_model');
        $this->load->model('ContestItem_model');
        $this->load->model('Order_model');
    }

    private function checkCreateParams()
    {
        $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//商品修改时不传corp_id以及user_id
        $this->post_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('name', PARAM_NULL_EMPTY);
        $this->post_check('producttype', PARAM_NULL_EMPTY);
        $this->post_check('usepeoplenum', PARAM_NULL_EMPTY);
        $this->post_check('validbegindate', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('validenddate', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('price', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('agentprice', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('fee', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('returnflag', PARAM_NULL_EMPTY);
        $this->post_check('numberflag', PARAM_NULL_EMPTY);
        $this->post_check('number', PARAM_NULL_EMPTY);
        $this->post_check('memo', PARAM_NULL_EMPTY);
        $this->post_check('webpic', PARAM_NULL_EMPTY);
        $this->post_check('consumebegindate', PARAM_NULL_EMPTY);
        $this->post_check('consumeenddate', PARAM_NULL_EMPTY);
        $this->post_check('consumestate', PARAM_NULL_EMPTY);
        $this->post_check('hour', PARAM_NULL_EMPTY);

        $params = $this->get_request_params();

        $params['price'] = empty($params['price']) ? 0 : $params['price'] * 100;
        $params['agentprice'] = empty($params['agentprice']) ? 0 : $params['agentprice'] * 100;
        $params['fee'] = empty($params['fee']) ? 0 : $params['fee'] * 100;
        //金飞鹰0为可退票  1为不可退
        $params['returnflag'] = $params['returnflag'] > 0? CONTEST_ITEM_REFUND_NONSURPORT : CONTEST_ITEM_REFUND_SURPORT;

        return $params;
    }

    private function getContestParamsForCreate($params)
    {
        $returnVal = [
            'fk_corp'       => $params['corp_id'],
            'fk_corp_user'  => $params['user_id'],
            'name'          => !empty($params['name']) ? $params['name'] : ' ',
            'gtype'         => CONTEST_GTYPE_DEFAULT,
            'intro'         => !empty($params['memo']) ? $params['memo'] : ' ',
            'logo'          => ' ',
            'poster'        => ' ',
            'banner'        => ' ',
            'sdate_start'   => !empty($params['validbegindate']) ? $params['validbegindate'] : '0000-00-00 00:00:00',
            'sdate_end'     => !empty($params['validenddate']) ? $params['validenddate'] : '0000-00-00 00:00:00',
            'country_scope' => CONTEST_COUNTRY_INTERNAL,
            'location'      => ' ',
            'publish_state' => CONTEST_PUBLISH_STATE_DRAFT, //默认暂存
            'template'      => CONTEST_TEMPLATE_TICKET, //默认卖票
            'partner'       => CONTEST_PARTNER_SOFTYKT  //来源 金飞鹰
        ];

        return $returnVal;
    }

    private function getContestItemParamsForCreate($params)
    {
        $returnVal = [
            'fk_corp'            => $params['corp_id'],
            'name'               => !empty($params['name']) ? $params['name'] : ' ',
            'fee'                => !empty($params['fee']) ? $params['fee'] : 0,
            'fee_origin'         => !empty($params['price']) ? $params['price'] : 0,
            'invite_required'    => CONTEST_ITEM_INVITE_REQUIRED_NO,  //默认不邀请
            'start_time'         => !empty($params['validbegindate']) ? $params['validbegindate'] : '0000-00-00 00:00:00',
            'end_time'           => !empty($params['validenddate']) ? $params['validenddate'] : '0000-00-00 00:00:00',
            'type'               => CONTEST_ITEM_TYPE_SINGLE, //默认为单人
            'max_stock'          => !empty($params['number']) ? $params['number'] : 0,
            'cur_stock'          => !empty($params['number']) ? $params['number'] : 0,
            'state'              => CONTEST_ITEM_STATE_OK,  //状态正常
            'multi_buy'          => 1,
            'max_verify'         => 1,
            'consume_start_time' => !empty($params['consumebegindate']) ? $params['consumebegindate'] : '0000-00-00 00:00:00',
            'consume_end_time'   => !empty($params['consumeenddate']) ? $params['consumeenddate'] : '0000-00-00 00:00:00',
            'refund_flag'        => !empty($params['returnflag']) ? $params['returnflag'] : CONTEST_ITEM_REFUND_NONSURPORT,
        ];

        return $returnVal;
    }

    private function getContestSoftyktParamsForCreate($params)
    {
        $returnVal = [
            'scenicid'     => !empty($params['scenicid']) ? $params['scenicid'] : 0,
            'productid'    => !empty($params['productid']) ? $params['productid'] : 0,
            'producttype'  => !empty($params['producttype']) ? $params['producttype'] : 0,
            'numberflag'   => !empty($params['numberflag']) ? $params['numberflag'] : 0,
            'usepeoplenum' => !empty($params['usepeoplenum']) ? $params['usepeoplenum'] : 0,
            'price'        => !empty($params['price']) ? $params['price'] : 0,
            'agentprice'   => !empty($params['agentprice']) ? $params['agentprice'] : 0,
            'consumestate' => !empty($params['consumestate']) ? $params['consumestate'] : 0,
            'hour'         => !empty($params['hour']) ? $params['hour'] : 0,
            'webpic'       => !empty($params['webpic']) ? $params['webpic'] : 0,
        ];

        return $returnVal;
    }

    public function add_post()
    {
        $params = $this->checkCreateParams();

        $contestSoftyktInfo = $this->ContestSoftykt_model->getByUnqKey($params['scenicid'], $params['productid']);
        if (!empty($contestSoftyktInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_ALREADY_EXISTS);
        }

        $contestParams = $this->getContestParamsForCreate($params);

        $contestItemParams = $this->getContestItemParamsForCreate($params);

        $contestSoftyktParams = $this->getContestSoftyktParamsForCreate($params);

        $lastId = $this->ContestSoftykt_model->create($contestParams, $contestItemParams, $contestSoftyktParams);
        if (empty($lastId)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_SAVE_FAILED);
        }

        return $this->response_insert($lastId);
    }

    private function checkUpdateParams()
    {
        $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('name', PARAM_NULL_EMPTY);
        $this->post_check('producttype', PARAM_NULL_EMPTY);
        $this->post_check('usepeoplenum', PARAM_NULL_EMPTY);
        $this->post_check('validbegindate', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('validenddate', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('price', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('agentprice', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('fee', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
        $this->post_check('returnflag', PARAM_NULL_EMPTY);
        $this->post_check('numberflag', PARAM_NULL_EMPTY);
        $this->post_check('number', PARAM_NULL_EMPTY);
        $this->post_check('memo', PARAM_NULL_EMPTY);
        $this->post_check('webpic', PARAM_NULL_EMPTY);
        $this->post_check('consumebegindate', PARAM_NULL_EMPTY);
        $this->post_check('consumeenddate', PARAM_NULL_EMPTY);
        $this->post_check('consumestate', PARAM_NULL_EMPTY);
        $this->post_check('hour', PARAM_NULL_EMPTY);

        $params = $this->get_request_params();

        if (!empty($params['price'])) {
            $params['price'] = $params['price'] * 100;
        }

        if (!empty($params['agentprice'])) {
            $params['agentprice'] = $params['agentprice'] * 100;
        }

        if (!empty($params['fee'])) {
            $params['fee'] = $params['fee'] * 100;
        }
        if(isset($params['returnflag'])){
            $params['returnflag'] = $params['returnflag'] > 0? CONTEST_ITEM_REFUND_NONSURPORT : CONTEST_ITEM_REFUND_SURPORT;
        }
        return $params;
    }


    private function getContestParamsForUpdate($params)
    {
        $returnVal = [];
        if (!empty($params['name'])) {
            $returnVal['name'] = $params['name'];
        }
        if (!empty($params['memo'])) {
            $returnVal['intro'] = $params['memo'];
        }
        if (!empty($params['validbegindate'])) {
            $returnVal['sdate_start'] = $params['validbegindate'];
        }
        if (!empty($params['validenddate'])) {
            $returnVal['sdate_end'] = $params['validenddate'];
        }

        return $returnVal;
    }

    private function getContestItemParamsForUpdate($params)
    {
        $returnVal = [];
        if (!empty($params['name'])) {
            $returnVal['name'] = $params['name'];
        }
        if (!empty($params['fee'])) {
            $returnVal['fee'] = $params['fee'];
        }
        if (!empty($params['price'])) {
            $returnVal['fee_origin'] = $params['price'];
        }
        if (!empty($params['validbegindate'])) {
            $returnVal['start_time'] = $params['validbegindate'];
        }
        if (!empty($params['validenddate'])) {
            $returnVal['end_time'] = $params['validenddate'];
        }
        if (!empty($params['number'])) {
            $returnVal['max_stock'] = $params['number'];
            $returnVal['cur_stock'] = $params['number'];
        }
        if (!empty($params['consumebegindate'])) {
            $returnVal['consume_start_time'] = $params['consumebegindate'];
        }
        if (!empty($params['consumeenddate'])) {
            $returnVal['consume_end_time'] = $params['consumeenddate'];
        }
        if (!empty($params['returnflag'])) {
            $returnVal['refund_flag'] = $params['returnflag'];
        }

        return $returnVal;
    }

    private function getContestSoftyktParamsForUpdate($params)
    {
        $returnVal = [];
        if (!empty($params['scenicid'])) {
            $returnVal['scenicid'] = $params['scenicid'];
        }
        if (!empty($params['productid'])) {
            $returnVal['productid'] = $params['productid'];
        }
        if (!empty($params['producttype'])) {
            $returnVal['producttype'] = $params['producttype'];
        }
        if (!empty($params['numberflag'])) {
            $returnVal['numberflag'] = $params['numberflag'];
        }
        if (!empty($params['usepeoplenum'])) {
            $returnVal['usepeoplenum'] = $params['usepeoplenum'];
        }
        if (!empty($params['price'])) {
            $returnVal['price'] = $params['price'];
        }
        if (!empty($params['agentprice'])) {
            $returnVal['agentprice'] = $params['agentprice'];
        }
        if (!empty($params['consumestate'])) {
            $returnVal['consumestate'] = $params['consumestate'];
        }
        if (!empty($params['hour'])) {
            $returnVal['hour'] = $params['hour'];
        }
        if (!empty($params['webpic'])) {
            $returnVal['webpic'] = $params['webpic'];
        }

        return $returnVal;
    }

    public function update_post()
    {
        $params = $this->checkUpdateParams();

        $contestSoftyktInfo = $this->ContestSoftykt_model->getByUnqKey($params['scenicid'], $params['productid']);
        if (empty($contestSoftyktInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS);
        }
        $contestInfo = $this->Contest_model->getById($contestSoftyktInfo['fk_contest']);
        if (empty($contestInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }

        $total = 0;
        $itemInfo = $this->ContestItem_model->listByPage(
            $fk_corp = $contestInfo['fk_corp'],
            $fk_comntest = $contestInfo['pk_contest'],
            $type = CONTEST_ITEM_TYPE_SINGLE,
            $page = 1,
            $size = 1,
            $total
        );
        if (empty($itemInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
        }
        $itemInfo = $itemInfo[0];

        if ((!empty($params['fee']) || !empty($params['price'])) && $contestInfo['publish_state'] == CONTEST_PUBLISH_STATE_SELLING) {
            $affectedRows = $this->Contest_model->offline($contestInfo['pk_contest'], $contestInfo['publish_state']);
            if (empty($affectedRows)) {
                return $this->response_error(Error_Code::ERROR_MALATHION_OFFLINE_CONTEST);
            }
        }

        $contestParams = $this->getContestParamsForUpdate($params);
        if (!empty($contestParams)) {
            $contestParams['pk_contest'] = $contestInfo['pk_contest'];
        }

        $contestItemParams = $this->getContestItemParamsForUpdate($params);
        if (!empty($contestItemParams)) {
            $contestItemParams['pk_contest_items'] = $itemInfo['pk_contest_items'];
        }

        $contestSoftyktParams = $this->getContestSoftyktParamsForUpdate($params);
        if (!empty($contestSoftyktParams)) {
            $contestSoftyktParams['pk_contest_ext_partner_softykt'] = $contestSoftyktInfo['pk_contest_ext_partner_softykt'];
        }

        $affectedRows = $this->ContestSoftykt_model->modify($contestParams, $contestItemParams, $contestSoftyktParams);

        return $this->response_update($affectedRows);
    }

    public function get_by_scenicid_productid_get()
    {
        $scenicid = $this->get_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid = $this->get_check('productid', PARAM_NOT_NULL_NOT_EMPTY);

        $extInfo = $this->ContestSoftykt_model->getByUnqKey($scenicid, $productid);
        if (empty($extInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS);
        }

        $contestInfo = $this->Contest_model->getById($extInfo['fk_contest']);
        if (empty($contestInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }

        $extInfo['contest_info'] = $contestInfo;

        return $this->response_object($extInfo);
    }

    public function offline_post()
    {
        $scenicid = $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid = $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY);

        $contestSoftyktInfo = $this->ContestSoftykt_model->getByUnqKey($scenicid, $productid);
        if (empty($contestSoftyktInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS);
        }

        $contestInfo = $this->Contest_model->getById($contestSoftyktInfo['fk_contest']);
        if (empty($contestInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }

        $affectedRows = $this->Contest_model->offline($contestInfo['pk_contest'], $contestInfo['publish_state']);

        return $this->response_update($affectedRows);
    }

    public function get_by_oid_get()
    {
        $oid = $this->get_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $orderInfo = $this->Order_model->getById($oid);
        if (empty($orderInfo)) {
            return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
        }


        $contestInfo = $this->Contest_model->getById($orderInfo['fk_contest']);
        if (empty($contestInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }

        $partnerContestInfo = $this->ContestSoftykt_model->getByContestId($contestInfo['pk_contest']);
        if (empty($partnerContestInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_CONTEST_EXT_NOT_EXISTS);
        }

        $partnerContestInfo['order_info'] = $orderInfo;
        $partnerContestInfo['contest_info'] = $contestInfo;

        return $this->response_object($partnerContestInfo);
    }
}
