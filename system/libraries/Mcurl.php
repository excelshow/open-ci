<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter MCurl Class
 *
 * Work with remote servers via multi-cURL much easier than using the native PHP bindings.
 *
 * @package            CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author            Chad Hutchins , modify by panzhiqi@youku.com
 * @link            http://github.com/chadhutchins/codeigniter-mcurl
 */
if (!defined('MCURL_OPT_TIMEOUT')) {
    define('MCURL_OPT_TIMEOUT', 2);
}
if (!defined('MCURL_OPT_TIMEOUT_MS')) {
    define('MCURL_OPT_TIMEOUT_MS', 300);
}
if (!defined('MCURL_OPT_CONNECTIONTIMEOUT')) {
    define('MCURL_OPT_CONNECTIONTIMEOUT', 5);
}
if (!defined('MCURL_OPT_CONNECTIONTIMEOUT_MS')) {
    define('MCURL_OPT_CONNECTIONTIMEOUT_MS', 500);
}
class CI_Mcurl {

    protected $_ci;                // CodeIgniter instance
    protected $calls = array();    // multidimensional array that holds individual calls and data
    protected $curl_parent;        // the curl multi handle resource

    private $is_debug = false;

    private $timeout = MCURL_OPT_TIMEOUT;
    private $timeout_ms = MCURL_OPT_TIMEOUT_MS;

    const HTTP_OK                   = 200;
    const DEFAULT_METHOD            = 'POST';
    const DEFAULT_SCHEME            = 'http';
    const MAX_REQUESTS              = 10000; //max requests,最大请求数
    const NUM_OF_ONCE_MULTI_REQUEST = 50; //the number of one multi request, 一次并发请求数

    function __construct()
    {
        $this->_ci = & get_instance();
        log_message('debug', 'Mcurl Class Initialized');

        if (!$this->is_enabled())
        {
            log_message('error', 'Mcurl Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.');
        }
        else 
        {
            $this->curl_parent = curl_multi_init();
        }
    }

    // check to see if necessary function exists
    function is_enabled()
    {
        return function_exists('curl_multi_init');
    }

    // method to add curl requests to the multi request queue
    function add_call($key=null, $method, $url, $params = array(), $options = array(), $ms = true)
    {
        if (is_null($key))
        {
            $key = count($this->calls);
        }

        // check to see if the multi handle has been closed
        // init the multi handle again
        $resource_type = get_resource_type($this->curl_parent);
        if(!$resource_type || $resource_type == 'Unknown')
        {
            $this->calls       = array();
            $this->curl_parent = curl_multi_init();
        }

        $this->calls [$key]= array(
            "method"   => $method,
            "url"      => $url,
            "params"   => $params,
            "options"  => $options,
            "curl"     => null,
            "response" => null,
            "error"    => null
        );

        $this->calls[$key]["curl"] = curl_init();

        // If its an array (instead of a query string) then format it correctly
        if (is_array($params))
        {
            $params = http_build_query($params, NULL, '&');
        }

        $method = strtoupper($method);

        // only supports get/post requests
        // set some special curl opts for each type of request
        switch ($method)
        {            
            case "POST":
                curl_setopt($this->calls[$key]["curl"], CURLOPT_URL, $url);
                curl_setopt($this->calls[$key]["curl"], CURLOPT_POST, TRUE);
                curl_setopt($this->calls[$key]["curl"], CURLOPT_POSTFIELDS, $params);
                break;

            case "GET":
                curl_setopt($this->calls[$key]["curl"], CURLOPT_URL, $url."?".$params);
            break;

            default:
                log_message('error', 'Mcurl Class - Provided http method is not supported. Only POST and GET are currently supported.');
            break;
        }

        curl_setopt($this->calls[$key]["curl"], CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->calls[$key]["curl"], CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->calls[$key]["curl"], CURLOPT_FAILONERROR, TRUE);

        //set timeout
        curl_setopt($this->calls[$key]["curl"], CURLOPT_CONNECTTIMEOUT, MCURL_OPT_CONNECTIONTIMEOUT);
        curl_setopt($this->calls[$key]["curl"], CURLOPT_TIMEOUT, $this->timeout);
        if (true === $ms) {
            curl_setopt($this->calls[$key]["curl"], CURLOPT_NOSIGNAL, TRUE);
            curl_setopt($this->calls[$key]["curl"], CURLOPT_CONNECTTIMEOUT_MS, MCURL_OPT_CONNECTIONTIMEOUT_MS);
            curl_setopt($this->calls[$key]["curl"], CURLOPT_TIMEOUT_MS, $this->timeout_ms);
        }

        // curl_setopt($this->calls[$key]["curl"], CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($this->calls[$key]["curl"], CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt_array($this->calls[$key]["curl"], $this->options($options));

        curl_multi_add_handle($this->curl_parent,$this->calls[$key]["curl"]);         
    }

    // run the calls in the curl requests in $this->calls
    function execute()
    {
        if (count($this->calls))
        {
            // kick off the requests
            do
            {
                $multi_exec_handle = curl_multi_exec($this->curl_parent,$active);
            } while ($active>0);


            // after all requests finish, set errors and repsonses in $this->calls
            foreach ($this->calls as $key => $call)
            {
                $error = curl_error($this->calls[$key]["curl"]);
                if (!empty($error))
                {
                    throw new Exception ($error, -99);
                    //$this->calls[$key]["error"] = $error;
                }
                $this->calls[$key]["response"] = curl_multi_getcontent($this->calls[$key]["curl"]);
                $this->calls[$key]["info"] = curl_getinfo($this->calls[$key]["curl"]);
                curl_multi_remove_handle($this->curl_parent,$this->calls[$key]["curl"]);
            }

            curl_multi_close($this->curl_parent);
        }

        return $this->calls;
    }
    // options CONNECTTIMEOUT->CURLOPT_CONNECTTIMEOUT, consistent with curl class
    public function options($options = array())
    {
        $opt = array();
        foreach ($options as $option_code => $option_value)
        {
            if (is_string($option_code) && !is_numeric($option_code))
            {
                $code = constant('CURLOPT_' . strtoupper($option_code));
                $opt[$code] = $option_value;
            }
        }
        return $opt;
    }

    function debug()
    {
        echo "<h2>mcurl debug</h2>";
        foreach ($this->calls as $call)
        {
            echo '<p>url: <b>'.$call["url"].'</b></p>';
            if (!is_null($call["error"]))
            {
                echo '<p style="color:red;">error: <b>'.$call["error"].'</b></p>';
            }
            echo '<textarea cols="100" rows="10">'.htmlentities($call["response"])."</textarea><hr>";
        }
    }
    
    public function __get($name){
        return $this->$name;
    }

    /**
     * multi request
     * @param $uc_host_array = array(
     *     'ucenter.api.qidianji.com'=>array(
     *          10.162.37.xx1,
     *          10.162.37.xx2,
     *      )
     *  );
     * @param $requests = array (
     *      array('api'=>'api_name_1', 'host'=>$uc_host_array, 'method'=>'post', 'params'=>'hello'),
     *      array('api'=>'api_name_2', 'host'=>$uc_host_array, 'method'=>'get', 'params'=>array('k1'=>'v1', 'k2'=>'v2')),
     *      array('api'=>'api_name_3', 'host'=>$uc_host_array, 'scheme' => 'http'),
     * )
     * @param $ms 是否开启毫秒级超时, 默认开启
     *
     * @return  array
     * @zhaodechang add 2014-10-21
     *
     */
    public function capture_multi($params, $ms = true, $timeout = 2, $timeout_ms = 300) {
        $this->timeout = $timeout;
        $this->timeout_ms = $timeout_ms;

        if (!is_array($params)) {
            throw new Exception ('Invalid parameter, array expected, ' . gettype($params) . ' given.', -100);
        }

        $result       = array();
        if(count($params) > self::MAX_REQUESTS) {
            throw new Exception ('Invalid parameter, requests number overflow, max requests number is ' . self::MAX_REQUESTS . ', ' . count($params) . ' given.' , -800);
        }
        
        $count        = 0;
        
        $sub_reqs     = array();
        $i            = 0;
        foreach ($params as $key => $value) {
            if ($i%self::NUM_OF_ONCE_MULTI_REQUEST == 0 && !empty($sub_reqs)) {
                $ret      = $this->muti_curl_execute($sub_reqs, $ms);
                $result   = array_merge($result, $ret);
                $sub_reqs = array();
                //usleep(10);
            }
            $sub_reqs[$key] = $value;
            $i++;
        }
        if (!empty($sub_reqs)) {
            $ret    = $this->muti_curl_execute($sub_reqs, $ms);
            $result = array_merge($result, $ret);
        }
        return $result;
    }
    /**
     * 执行并发请求
     * @param  array $params 请求数组
     * @return array         返回结果
     */
    private function muti_curl_execute ($params, $ms = true) {
        foreach ($params as $key => $val) {
            if (!is_array($val)) {
                throw new Exception ('Invalid parameter, array expected, ' . gettype($val) . ' given.', -200);
            }
            if (empty($val['api']) || empty($val['host']) || !is_array($val['host'])) {
                throw new Exception ('Invalid parameter, api&host check.', -300);
            }

            $scheme        = !empty($val['scheme']) ? $val['scheme'] : self::DEFAULT_SCHEME;
            $method        = !empty($val['method']) ? $val['method'] : self::DEFAULT_METHOD;
            $req_params    = !empty($val['params']) ? $val['params'] : array(); 
            $headers       = !empty($val['headers']) ? $val['headers'] : array(); 
            $hostname      = array_keys($val['host'])[0];
            $serverip_list = $val['host'][$hostname];
            if (empty($serverip_list) || !is_array($serverip_list)) {
                throw new Exception ('Invalid parameter, serverip_list check.', -400);
            }
            $url =  $scheme . '://' . $serverip_list[array_rand($serverip_list)] . '/' . $val['api'];
            $httpheader = array('HOST:' . $hostname);
            if(!empty($headers)){
                foreach($headers as $k=>$v){
                    $httpheader[] = $k.':'.$v;
                }
            }
            $options = array(
                'HTTPHEADER' => $httpheader,
            );

            $this->add_call($key, $method, $url, $req_params, $options, $ms);
        }

        $calls = $this->execute();
        if (empty($calls)) {
            throw new Exception ('Curl muti request error, no reponse.', -500);
        }
        if ($this->is_debug) {
            var_dump('===== PARAMS START =====' . PHP_EOL);
            var_dump($params);
            var_dump('===== PARAMS END=====' . PHP_EOL);
            var_dump('===== RESPONSE START =====' . PHP_EOL);
            var_dump($calls);
            var_dump('===== RESPONSE END =====' . PHP_EOL);
        }
        foreach ($params as $key => $val) {
            $rs = $calls[$key];
            if ($rs['info']['http_code'] != self::HTTP_OK) {
                throw new Exception ('Bad http request, code:' . $rs['info']['http_code'], -600);
            }

            $std = json_decode($rs['response']);
            // 非json类型数据
            if (empty($std)) {
                $std = $rs['response'];
            }

            // edit by zhaodechang
            // if (is_null($std) || !is_object($std)) {
                // throw new Exception ('Bad response:' . $rs['response'], -700);
            // }
            $result[$key] = $std;
        }
        return $result;
    }
    /**
     * 设定debug
     */
    public function setDebug($val) {
        $this->is_debug = $val;
    }

}

/* End of file mcurl.php */
/* Location: ./application/libraries/mcurl.php */
