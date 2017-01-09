<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Base.php';

/**
 * 金飞鹰对接api产品类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Product extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/Product_model');
        $this->load->model('softykt/Softykt_contest_item_model');
    }

    /**
     * 金飞鹰创建 活动 项目
     */
    public function create_post(){

        $param = $this->checkCreateparam();
        log_message('error',var_export($param,true));

        //查询商品是否存在  状态是否正确
        $extInfo = $this->Product_model->get_by_scenicid_productid($param['scenicid'], $param['productid']);
        if(!empty($extInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_ADD_EXIST);
        }
        //活动参数
        $contestParam = $this->contestParam($param);

        //项目参数
        $contestItemParam = $this->contestItemParam($param);

        //ext关联参数
        $extContestparam = $this->extContestparam($param);

        $result = $this->Product_model->create($contestParam, $contestItemParam, $extContestparam);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_ADD_FAILED);
        }
        return $this->response_insert($result);


    }

    /**
     * 创建项目参数校验
     * @return array
     */
    private function checkCreateparam(){

        $corp_id        = $this->post_check('corp_id', PARAM_NULL_EMPTY);//商品修改时不传corp_id以及user_id
        $user_id        = $this->post_check('user_id', PARAM_NULL_EMPTY);
        $scenicid       = $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid      = $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY);
        $name           = $this->post_check('name', PARAM_NULL_EMPTY);
        $producttype    = $this->post_check('producttype', PARAM_NULL_EMPTY);
        $usepeoplenum   = $this->post_check('usepeoplenum', PARAM_NULL_EMPTY);
        $validbegindate = $this->post_check('validbegindate', PARAM_NOT_NULL_NOT_EMPTY);
        $validenddate   = $this->post_check('validenddate', PARAM_NOT_NULL_NOT_EMPTY);
        $price          = $this->post_check('price', PARAM_NULL_EMPTY);
        $price          = $price * 100; //转成分
        $agentprice     = $this->post_check('agentprice', PARAM_NULL_EMPTY);
        $agentprice     = $agentprice * 100; //转成分
        $fee            = $this->post_check('fee', PARAM_NOT_NULL_NOT_EMPTY);
        $fee            = $fee * 100; //转成分
        $returnflag     = $this->post_check('returnflag', PARAM_NULL_EMPTY);
        //金飞鹰0为可退票  1为不可退
        $returnflag     = $returnflag > 0 ? CONTEST_ITEM_REFUND_NONSURPORT : CONTEST_ITEM_REFUND_SURPORT;
        $numberflag     = $this->post_check('numberflag', PARAM_NULL_EMPTY);
        $number         = $this->post_check('number', PARAM_NULL_EMPTY);
        $memo           = $this->post_check('memo', PARAM_NULL_EMPTY);
        $webpic         = $this->post_check('webpic', PARAM_NULL_EMPTY);
        $consumebegindate = $this->post_check('consumebegindate', PARAM_NULL_EMPTY);
        $consumeenddate = $this->post_check('consumeenddate', PARAM_NULL_EMPTY);
        $consumestate   = $this->post_check('consumestate', PARAM_NULL_EMPTY);
        $hour   = $this->post_check('hour', PARAM_NULL_EMPTY);

        $return = compact('scenicid', 'productid', 'name', 'producttype', 'usepeoplenum',
            'validbegindate', 'validenddate', 'price', 'agentprice', 'fee', 'returnflag',
            'numberflag', 'number', 'memo', 'webpic', 'consumebegindate', 'consumeenddate','consumestate', 'hour'
        );
        if(!empty($corp_id)){
            $return['fk_corp'] = $corp_id;
        }
        if(!empty($user_id)){
            $return['fk_corp_user'] = $user_id;
        }
        return $return;
    }

    /**
     * 活动参数补充
     * @param $param
     */
    private function contestParam($param){
        //部分参数临时补充
        $return = array(
            'name'          => $param['name'],
            'gtype'         => '1',
            'intro'         => $param['memo'],
            'logo'          => '1',
            'poster'        => '1',
            'banner'        => '1',
            'sdate_start'   => $param['validbegindate'],
            'sdate_end'     => $param['validenddate'],
            'country_scope' => CONTEST_COUNTRY_INTERNAL,
            'location'      => '1',
            'publish_state' => CONTEST_PUBLISH_STATE_DRAFT, //默认暂存
            'template'      => CONTEST_TEMPLATE_TICKET, //默认卖票
            'partner'       => CONTEST_PARTNER_SOFTYKT  //来源 金飞鹰
        );
        if(!empty($param['fk_corp'])){
            $return['fk_corp'] = $param['fk_corp'];
        }
        if(!empty($param['fk_corp_user'])){
            $return['fk_corp_user'] = $param['fk_corp_user'];
        }
        return $return;
    }

    /**
     * 项目参数补充
     * @param $param
     * @return array
     */
    private function contestItemParam($param){
        $return = array(
            'name'              => $param['name'],
            'fee'               => $param['fee'],
            'invite_required'   => CONTEST_ITEM_INVITE_REQUIRED_NO,  //默认不邀请
            'start_time'        => $param['validbegindate'],
            'end_time'          => $param['validenddate'],
            'type'              => CONTEST_ITEM_TYPE_DEFAULT, //默认为单人
            'max_stock'         => $param['number'],
            'cur_stock'         => $param['number'],
            'state'             => CONTEST_ITEM_STATE_OK,  //状态正常
            'consume_begin_time' => $param['consumebegindate'],
            'consume_end_time'   => $param['consumeenddate'],
            'refund_flag'        => $param['returnflag']
        );
        if(!empty($param['fk_corp'])){
            $return['fk_corp'] = $param['fk_corp'];
        }
        return $return;
    }

    private function extContestparam($param){
        $return = array(
            'scenicid'      => $param['scenicid'],
            'productid'     => $param['productid'],
            'producttype'   => $param['producttype'],
            'numberflag'    => $param['numberflag'],
            'usepeoplenum'  => $param['usepeoplenum'],
            'price'         => $param['price'],
            'agentprice'    => $param['agentprice'],
            'consumestate'  => $param['consumestate'],
            'hour'          => $param['hour'],
            'webpic'        => $param['webpic']
        );
        return $return;
    }

    /**
     * 修改项目信息
     * @param param 景区id
     * @return json
     */
    public function modify_post(){

        $param = $this->checkCreateparam();
        log_message('error',var_export($param,true));
        //查询商品是否存在  状态是否正确
        $extInfo = $this->Product_model->get_by_scenicid_productid($param['scenicid'], $param['productid']);
        if(empty($extInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_EXT_NOT_FOUND);
        }
        //活动参数
        $contestParam = $this->contestParam($param);

        //项目参数
        $contestItemParam = $this->contestItemParam($param);

        //ext关联参数
        $extContestparam = $this->extContestparam($param);

        $result = $this->Product_model->modify($contestParam, $contestItemParam, $extContestparam, $extInfo);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_CONTEST_SOFTYKT_SAVE_FAILED);
        }
        return $this->response_insert($result);

    }

    /**
     * 修改商品状态
     * @param scenicid 景区id
     * @param productid 产品id
     */
    public function productStaticSave_post(){
        $scenicid  = $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid = $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY);
        log_message('error',var_export($_POST,true));

        //查询商品是否存在  状态是否正确
        $extInfo = $this->Product_model->get_by_scenicid_productid($scenicid, $productid);
        if(empty($extInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_EXT_NOT_FOUND);
        }
        //查询活动是否存在
        $contestInfo = $this->Product_model->get_by_fk_contest($extInfo['fk_contest']);
        if(empty($contestInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_FOUND);
        }
        $fromState = $contestInfo['publish_state'];
        $result = $this->Product_model->productStaticSve($extInfo['fk_contest'], $fromState);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_STATE_SAVE_FAILED);
        }
        return $this->response_update($result);
    }


    /**
     * 根据景区id 产品id 获取关联信息
     */
    public function get_by_scenicid_productid_post(){
        $scenicid  = $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid  = $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY);

        $conItem = $this->Product_model->get_by_scenicid_productid($scenicid, $productid);
        if(!empty($conItem)){
            return $this->response_Object($conItem);
        }
    }






}