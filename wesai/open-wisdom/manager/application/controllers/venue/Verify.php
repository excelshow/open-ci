<?php

require_once dirname(__DIR__) . '/Wxlogin.php';

/**
 * User: zhaodc
 * Date: 8/4/16
 * Time: 23:51
 */
class Verify extends Wxlogin
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('qywx/auth_model');
        $this->load->model('qywx/corp_model');
        $this->load->model('venue/Order_model');
        $this->load->model('contest/Contest_model');
    }

    private function getJsApiConfig()
    {
        $url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $jsApiConfig = $this->corp_model->getJsApiConfig($_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'], $url);

        if (empty($jsApiConfig['result'])) {
            log_message('error', 'getJsApiConfig failed');
            show_error_v2('load jsapi config failed', '-1');
        }

        return $jsApiConfig;
    }

    public function mobile ($corpId)
    {
        $this->needLogin($corpId);

        $data           = array();
        $data['corpId'] = $corpId;

        $jsApiConfig = $this->getJsApiConfig();

        $data['jsApiConfig'] = $jsApiConfig['result'];

        $this->display($data);
    }


    // public function order($corpId)
    // {
    //     $this->needLogin($corpId);

    //     $data           = array();
    //     $data['corpId'] = $corpId;

    //     $jsApiConfig = $this->getJsApiConfig();

    //     $data['jsApiConfig'] = $jsApiConfig['result'];
        
    //     $this->display($data);
    // }

    // public function ajax_list_verifying_items()
    // {
    //     $std        = new stdClass();
    //     $std->error = 0;

    //     $this->needLoginJson($_SESSION['corp_id']);

    //     $listType   = strval($this->input->get('listType', true));
    //     $pageNumber = intval($this->input->get('page', true));
    //     $pageSize   = intval($this->input->get('pageSize', true));

    //     $time = time();
    //     switch ($listType) {
    //         case 1:
    //             break;
    //         case 2:
    //             $time = strtotime('+1 days');
    //             break;
    //         case 3:
    //             $time = strtotime('+2 days');
    //             break;
    //     }
    //     $date = date('Y-m-d', $time);

    //     $itemList = $this->Contest_model->listVerifyingItems($_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'], $date, $pageNumber, $pageSize);
    //     if (empty($itemList) || $itemList->error != 0 || empty($itemList->data)) {

            
    //         $this->display($std);
    //     }
        
        
    //     $data= $itemList;
    //     // $itemList   = $itemList->data;

    //     // $std->html = $this->smarty->fetch('./contest/verify/_verifying_item_list.tpl', compact('itemList'));

    //     $this->display($data);
    // }

    public function ajax_check_qrcode_data()
    {
        $this->needLoginJson($_SESSION['corp_id']);
        $std        = new stdClass();
        $std->error = 0;
        $std->msg   = '';

        $qrCodeData = $this->input->post('data', true);
        if (empty($qrCodeData)) {
            $std->error = -1;
            $std->msg   = '参数错误';
            $this->display($std);
        }

        $decryptData = $this->decryptOrderData($qrCodeData);

        if ($decryptData->error < 0) {
            $std->error = -2;
            $std->msg   = '二维码无效|' . $decryptData->error;
            $this->display($std);

        }

        $std->orderId = $decryptData->code;
        $this->display($std);
    }

    private function decryptOrderData($orderData)
    {
        $std        = new stdClass();
        $std->error = 0;
        $orderData = base64_decode($orderData);
    
        if (empty($orderData)) {
            $std->error = -1;

            return $std;
        }

        $orderData = explode('|', $orderData);
        if (count($orderData) != 3) {
            $std->error = -2;

            return $std;
        }

        $originSign   = $orderData[2];
        $orderData[2] = ORDER_ENCRYPT_KEY;

        $sign = md5(implode('', $orderData));

        if ($sign != $originSign) {
            $std->error = -3;

            return $std;
        }

        $std->code = $orderData[1];

        return $std;
    }

    // public function ajax_do_verify()
    // {
    //     $this->needLoginJson($_SESSION['corp_id']);

    //     $userInfo = $_SESSION[$_SESSION['corp_id']]['userInfo'];

    //     $std        = new stdClass();
    //     $std->error = 0;
    //     $std->msg   = '';

    //     $orderId = $this->input->post('orderId', true);
    //     $itemId  = $this->input->post('itemId', true);
    //     if (empty($orderId)) {
    //         $std->errorno = -1;
    //         $std->itemId = $itemId;
    //         $std->msg     = '订单无效';
    //         $std->html    = $this->smarty->fetch('./contest/verify/_verify_failed.tpl', (array)$std);
    //         $this->display($std);
    //     }
    //     $orderInfo = $this->Order_model->getOrderById($orderId, 1);
    //     if (empty($orderInfo->result)) {
    //         $std->errorno = -2;
    //         $std->itemId = $itemId;
    //         $std->msg     = '订单无效';
    //         $std->html    = $this->smarty->fetch('./contest/verify/_verify_failed.tpl', (array)$std);
    //         $this->display($std);
    //     }
    //     $orderInfo = $orderInfo->result;

    //     $verifyResult = $this->Order_model->verifyRestrict($orderId, $itemId, $userInfo['pkCorp'], $userInfo['userId']);
    //     if (empty($verifyResult)) {
    //         $std->errorno = -3;
    //         $std->itemId = $itemId;
    //         $std->msg     = '订单核销失败';
    //         $std->html    = $this->smarty->fetch('./contest/verify/_verify_failed.tpl', (array)$std);
    //         $this->display($std);
    //     }
    //     if ($verifyResult->error != 0) {
    //         if ($verifyResult->error == -309) {
    //             $std->msg = '已超过最大检票<span> ' . ($orderInfo->verify_number + 1) . '/' . $orderInfo->max_verify . '</span> 次数';
    //         } else {
    //             $std->errorno = $verifyResult->error;
    //             $std->msg     = $verifyResult->info;
    //         }
    //         $std->itemId = $itemId;
    //         $std->html = $this->smarty->fetch('./contest/verify/_verify_failed.tpl', (array)$std);
    //         $this->display($std);
    //     }
    //     if (empty($verifyResult->affected_rows)) {
    //         $std->errorno = -4;
    //         $std->msg     = '订单核销失败';
    //         $std->html    = $this->smarty->fetch('./contest/verify/_verify_failed.tpl', (array)$std);
    //         $std->itemId = $itemId;
    //         $this->display($std);
    //     }

    //     $assignData = array(
    //         'itemId'    => $itemId,
    //         'orderInfo' => $orderInfo,
    //         'msg'       => '已成功检票 <span>' . ($orderInfo->verify_number + 1) . (!empty($orderInfo->max_verify) ? '/' . $orderInfo->max_verify : '') . '</span> 次 ',
    //     );
    //     $std->itemId = $itemId;
    //     $std->html  = $this->smarty->fetch('./contest/verify/_verify_success.tpl', $assignData);
    //     $this->display($std);
    // }

    public function ajax_getOrderById()
    {
        $this->needLoginJson($_SESSION['corp_id']);
        $pkCorp  = $_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'];
        $orderId = $this->input->post('orderId', true);
        $data    = new stdClass();
        if (empty($orderId)) {
            $data->error = -1;
            $data->msg   = '核销码无效';
            $this->display($data);
        }
        $data = $this->Order_model->getOrderByCode($orderId,$pkCorp);
        $data->pkCorp = $pkCorp;
        $this->display($data);
    }
    public function ajax_verifyLoose()
    {
        $this->needLoginJson($_SESSION['corp_id']);
        $code = $this->input->post('orderId', true);
        $pkCorp  = $_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'];
        $userId  = $_SESSION[$_SESSION['corp_id']]['userInfo']['userId']; 
        $data    = new stdClass();
        if (empty($code) && empty($pkCorp) && empty($userId)) {
            $data->error = -1;
            $data->msg   = '参数错误,请重新登录';
            $this->display($data);
        }
        $data = $this->Order_model->verifyLoose($code, $pkCorp, $userId);
        $this->display($data);
    }

}
