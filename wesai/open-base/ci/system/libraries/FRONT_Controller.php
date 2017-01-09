<?php
/**
 * User: zhaodc
 * Date: 8/28/16
 * Time: 15:25
 */
define('METHOD_GET', 'GET');
define('METHOD_POST', 'POST');
// 只有TPL，也需要配置一下
define('REQUEST_TPL', 'ONLY_TPL');

abstract class FRONT_Controller extends CI_Controller
{
    protected $userInfo         = null;
    private   $URL_EXT_CALL_API = ['html'];

    public function __construct()
    {
        parent::__construct();
        if (!is_cli()) {
            $this->load->library('smarty');
            $this->load->library('session');
            $this->load->model('User_model');
        }
        $this->load->model('DIY_model');
        $this->load->helper('diy');
    }

    public function _remap($functionName, $params)
    {
        try {
            call_user_func_array(array($this, $functionName), $params);
        } catch (Exception $e) {
            log_message('error', json_encode($e->getTrace()[0]));
            throw $e;
        }
    }

    public function __call($functionName, $args)
    {
        $host = $this->getHostName();
        $api  = $this->getApi($host);
        if (!empty($api) && $api != REQUEST_TPL) {
            $data = $this->callInnerApi();
        } else {
            $data = new stdClass();
        }

        $data = $this->initPageVars($data);
        $this->display($data);
    }

    private function initPageVars($data)
    {
        $pageVars = array();
        if (method_exists($this, 'setPageVars')) {
            $pageVars = $this->setPageVars();
        }

        if (!empty($pageVars) && is_object($data)) {
            $data->page_vars = $pageVars;
        }

        return $data;
    }

    /**
     * callInnerApi
     *
     * @param bool $params 接口参数:拦截之后，需要传递参数
     *
     * @param bool $ms
     * @param int  $timeout
     * @param int  $timeout_ms
     *
     * @return
     * @access private
     */
    protected function callInnerApi($params = null, $ms = true, $timeout = 2, $timeout_ms = 300)
    {
        $host   = $this->getHostName();
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $api    = $this->getApi($host);

        if (is_null($params)) {
            $params = $this->input->{$method}(null, true);
        }

        return $this->callInnerApiDiy($host, $api, $params, $method, $ms, $timeout, $timeout_ms);
    }

    protected function callInnerApiDiy($host, $api, $params, $method, $ms = true, $timeout = 2, $timeout_ms = 300)
    {
        $modelName = "Inner_model";
        eval("if(!class_exists('$modelName')){class $modelName extends DIY_model {}}");

        $this->load->model($modelName);
        $result = $this->$modelName->callApi($host, $api, $params, $method, $ms, $timeout, $timeout_ms);

        return $result;
    }

    private function getApi($host)
    {
        $api_key        = implode('/', $this->uri->rsegments);
        $allowedApiList = $this->getAllowedApiList();
        if (!is_array($allowedApiList)) {
            exit('setAllowedApiList must return array');
        }

        $apis = array();
        if (!empty($allowedApiList) && !empty($host)) {
            if (!array_key_exists($host, $allowedApiList)) {
                exit('api host not allowed');
            }
            $apis = $allowedApiList[$host];
        }

        $api = isset($apis[$api_key]) ? $apis[$api_key] : '';
        if (empty($api)) {
            exit('api not allowed');
        }

        return $api;
    }

    // 待删除，不要使用
    protected function errorInfo($info, $code = -1)
    {
        exit(json_encode(array('error' => $code, 'info' => $info)));
    }

    protected function displayError($info, $code = -1)
    {
        exit(json_encode(array('error' => $code, 'info' => $info)));
    }

    protected function isWeixinClient()
    {
        $userAgent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($userAgent, 'MicroMessenger') === false && strpos($userAgent, 'Windows Phone') === false) {
            return false;
        }

        return true;
    }

    protected function display($data = array())
    {
        if (is_cli()) {
            exit(json_encode($data));
        }

        if (false === $data) {
            $this->displayError('系统异常，请重试');
        }

        $template_dir = $this->smarty->template_dir[0];
        $tpl          = $this->router->directory . $this->router->class . '/' . $this->router->method . '.tpl';

        if (!file_exists($template_dir . $tpl) || $this->input->get('debugo')) {
            echo json_encode($data);
            exit;
        }

        if (defined('SMARTY_DEBUG') && SMARTY_DEBUG) {
            $this->smarty->assign('CI', '为方便debug，把该变量清空');
            $this->smarty->debugging = true;
        }

        $data = $this->initPageVars($data);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $this->smarty->assign($k, $v);
            }
        }

        $this->smarty->display($tpl);
    }

    /*{{{ 公众号跳转逻辑 */
    /**
     * needLogin
     *  登录校验
     *
     * @access protected
     */
    protected function needLogin()
    {
        $this->userInfo = $this->User_model->getUserInfo();
        if (empty($this->userInfo)) {
            $this->readyGetOpenid();
        }

        return $this->userInfo;
    }

    /**
     * needLoginJson
     *  登录校验（json）
     *
     * @access protected
     * @return void
     */
    protected function needLoginJson()
    {
        $this->userInfo = $this->User_model->getUserInfo();
        if (empty($this->userInfo)) {
            $this->displayError('您还没有登录，请先登录');
        }
    }

    /**
     * readyGetOpenid
     *  准备发起微信授权
     *
     * @access protected
     * @return void
     */
    protected function readyGetOpenid()
    {
        $curUrl       = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $url          = $this->BASE_SITE_URL . '/user/login?url=' . urlencode($curUrl);
        $redirect_uri = WEIXIN_REDIRECT_PROXY_URL . urlencode($url);
        $appid        = $this->User_model->getCurrentAppId();

        $url = WEIXIN_SNSAPI_AUTHORIZE_URL . '?appid=' . $appid;
        $url .= '&redirect_uri=' . urlencode($redirect_uri);
        $url .= '&response_type=code&scope=' . WEIXIN_SNSAPI_SCOPE_BASE;
        $url .= '&state=' . WEIXIN_SNSAPI_SCOPE_BASE . '&component_appid=' . COMPONENT_APPID;
        $url .= '#wechat_redirect';

        header('Location: ' . $url);
        exit;
    }

    /**
     * login
     *  微信授权之后，跳转到该页面，获取用户微信信息并登录
     *
     * @access public
     * @return void
     */
    protected function login()
    {
        // 输入数据判断
        $toUrl       = $this->input->get('url', true);
        $wxAuthCode  = $this->input->get('code', true);
        $wxAuthState = $this->input->get('state', true);
        if (empty($toUrl) || empty($wxAuthCode) || empty($wxAuthState)) {
            log_message('error', '登录失败（-1）');
            show_error('登录失败（-1）', '500', $heading = '');
        }
        // 公众号信息
        $appId             = explode('.', $_SERVER['HTTP_HOST'])[0];
        $params            = array(
            'authorizer_appid' => $appId,
        );
        $componentAuthInfo = $this->callInnerApiDiy(
            API_HOST_OPEN_WEIXIN,
            'component/authorizer/get.json',
            $params,
            METHOD_GET
        );
        if (empty($componentAuthInfo) || empty($componentAuthInfo->result)) {
            log_message('error', '登录失败（-2）');
            show_error('登录失败（-2）', '500', $heading = '');
        }

        // 获取用户信息
        $params   = array(
            'apppk'   => $componentAuthInfo->result->pk_component_authorizer_app,
            'code'    => $wxAuthCode,
            'snsapi'  => $wxAuthState,
        );
        $userInfo = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_wxcode.json', $params, METHOD_GET, false);
        if (empty($userInfo) || $userInfo->error != 0 || empty($userInfo->result)) {
            log_message('error', '登录失败（-3）');
            show_error('登录失败（-3）', '500', $heading = '');
        }

        $user_id           = $userInfo->result->pk_user;
        $uuid              = $userInfo->result->uuid;
        $openid            = $userInfo->result->ext_weixin->openid;
        $authorizer_app_id = $componentAuthInfo->result->pk_component_authorizer_app;
        $qrcode_url        = $componentAuthInfo->result->qrcode_url;
        $corp_id           = $componentAuthInfo->result->fk_corp;
        $this->User_model->setUserInfo($user_id, $uuid, $openid, $authorizer_app_id, $corp_id, '', $qrcode_url);
        header('Location: ' . $toUrl);
        exit();
    }

    /**
     * get_jssdk_sign
     *  微信jssdk处理
     *
     * @access protected
     */
    protected function getJssdkSign($apppk = 0)
    {
        if (empty($apppk)) {
            $userInfo = $this->User_model->getUserInfo();
            $apppk    = $userInfo->apppk;
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $params      = array(
            'apppk' => $apppk,
            'url'   => $url,
        );
        $jsApiConfig = $this->callInnerApiDiy(
            API_HOST_OPEN_WEIXIN,
            'component/authorizer/get_jsapi_config',
            $params,
            METHOD_GET
        );
        if (empty($jsApiConfig) || $jsApiConfig->error != 0 || empty($jsApiConfig->result)) {
            return array();
        }

        return (array)$jsApiConfig->result;
    }

    /*}}}*/

    abstract protected function getHostName();

    abstract protected function getAllowedApiList();
}
