<?php

/**
 * 基础
 * 注意：如果是对外接口，注意隐藏sql
 *
 * @author like@wesai.com
 * @date   2016-01-16
 */
class DIY_Model extends CI_Model
{
	private $pdo   = null;
	private $trans = false;

	function __construct()
	{
		parent::__construct();
		// 添加My_Model之后，之前所有的类也开始继承这个类
		include_once __DIR__ . '/Pdo_Mysql.php';
        $this->load->library('mcurl');
        $this->load->library('curl');
	}

	protected function isTransBegan()
	{
		return $this->trans;
	}

	public function insert($dsn_type, $sql, $params)
	{
		try {
			$lastid = false;
			$pdo = $this->pdodb();
			$pdo->Prepare($dsn_type, $sql)
			    ->BindParams($params)
			    ->Execute()
			    ->LastInsertId($lastid);
			if (!$this->trans) {
				$pdo->Close();
			}
			return $lastid;
		} catch (Exception $e) {
			$this->throwException($e, $sql);
		}

	}

	private function throwException(Exception $e, $sql)
	{
		$msg  = $e->getMessage() . ',' . $sql;
		$code = $e->getCode();

		$msg = '[' . $code . '] ' . $msg;
		log_message('error', $msg);

        // 错误日志记录log
		$msg = '系统繁忙';

		throw new Exception($msg, Error_Code::ERROR_DB);
	}

	public function update($dsn_type, $sql, $params)
	{
		try {
			$affected = 0;
			$pdo = $this->pdodb();
			$pdo->Prepare($dsn_type, $sql)
			    ->BindParams($params)
			    ->Execute()
			    ->AffectRows($affected);
			if (!$this->trans) {
				$pdo->Close();
			}
			return $affected;
		} catch (Exception $e) {
			$this->throwException($e, $sql);
		}
	}

	public function getSingle($dsn_type, $sql, $params)
	{
		try {
			$result = false;
			$pdo = $this->pdodb();
			$pdo->Prepare($dsn_type, $sql)
			    ->BindParams($params)
			    ->Execute()
			    ->FetchSingle($result);
			if (!$this->trans) {
				$pdo->Close();
			}
			return ($result === false) ? array() : $result;
		} catch (Exception $e) {
			$this->throwException($e, $sql);
		}
	}

	public function getAll($dsn_type, $sql, $params, $page = 0, $size = 20)
	{
		try {
			if ($page > 0) {
				// 如果参数是字符key
				$params_keys = array_keys($params);
				if (isset($params_keys[0]) && is_numeric($params_keys[0])) {
					$sql .= ' LIMIT ?, ?';
					$params[] = ($page - 1) * $size;
					$params[] = (int)$size;
				} else {
					$sql .= ' LIMIT :offset, :size';
					$params['offset'] = ($page - 1) * $size;
					$params['size']   = (int)$size;
				}
			}
			$result = false;
			$pdo = $this->pdodb();
			$pdo->Prepare($dsn_type, $sql)
			    ->BindParams($params)
			    ->Execute()
			    ->FetchAll($result);
			if (!$this->trans) {
				$pdo->Close();
			}
			return ($result === false) ? array() : $result;
		} catch (Exception $e) {
			$this->throwException($e, $sql);
		}

	}

	public function delete($dsn_type, $sql, $params)
	{
		try {
			$affected = 0;
			$pdo = $this->pdodb();
			$pdo->Prepare($dsn_type, $sql)
			    ->BindParams($params)
			    ->Execute()
			    ->AffectRows($affected);
			if (!$this->trans) {
				$pdo->Close();
			}
			return $affected;
		} catch (Exception $e) {
			$this->throwException($e, $sql);
		}
	}

	protected function beginTransaction()
	{
		$this->pdodb()
		     ->BeginTransaction();
		$this->trans = true;
	}

	private function pdodb()
	{
		if ($this->pdo === null) {
			$db        = $this->get_db();
			$db_config = $this->config->item($db);
			$this->pdo = new Pdo_Mysql($db_config);
		}

		return $this->pdo;
	}

	protected function commit()
	{
		$this->pdodb()
		     ->Commit();
		$this->trans = false;
	}

	protected function rollBack()
	{
		$this->pdodb()
		     ->RollBack();
		$this->trans = false;
	}

    public function key($params){
        return md5(json_encode($params));
    }
   
    public function request($host, $api, $params, $method = 'GET', $headers = array()){
        $host = $this->config->item($host);
        $request = array(
            'host'   => $host, 
            'api' => $api, 
            'method' => $method,
            'params' => $params,
            'headers' => $headers
        );
        return $request;
    }

    /**
     * result
     * 
     * @param mixed $requests 
     * @param bool $ms 
     * @param int $timeout 
     * @param int $timeout_ms 
     * @param bool $is_api  true调用checkApiResult判断是否有error等api必有字段
     * @access public
     * @return void
     */
    public function result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = true){
        try {
            $host = $requests[0]['host'];
            $api = $requests[0]['api'];
            $method = $requests[0]['method'];
            $params = $requests[0]['params'];
            $headers = $requests[0]['headers'];
            $result = $this->curl->capture($host, $api, $method, $params, $headers, $ms, $timeout, $timeout_ms);
            if(!empty($result)){
                if($is_api){
                    return $this->checkApiResult($result, $requests);
                }
                return $result;
            }
            return (object)array();
        } catch (Exception $e) {
            $msg = $e->getMessage().json_encode($requests);
            log_message('error', $msg);
            //throw new Exception($msg, $e->getCode());
        }
    }

    /**
     * 多请求key处理
     */
    public function makeKey($data){
        return md5(json_encode($data)); 
    }

    /**
     * 一次请求多个
     */
    public function results($requests, $ms = true, $timeout = 2, $timeout_ms = 500){
        try {
            $results = $this->mcurl->capture_multi($requests, $ms, $timeout, $timeout_ms);
            return $results;
        } catch (Exception $e) {
            $msg = $e->getMessage().json_encode($requests);
            log_message('error', $msg);
            //throw new Exception($msg, $e->getCode());
        }
    }

    public function callApi($host, $api, $params, $method, $ms = true, $timeout = 2, $timeout_ms = 500){
        $requests   = array();
        $requests[] = $this->request($host, $api, $params, $method);

        $result = $this->result($requests, $ms, $timeout, $timeout_ms);
        return $result;
    }

    protected function checkApiResult($result, $requests){
        $backTrace  = debug_backtrace()[1];
        $methodName = !empty($backTrace['function']) ? $backTrace['function'] : '';
        $className  = !empty($backTrace['class']) ? $backTrace['class'] . '::' : '';
        $line       = !empty($backTrace['line']) ? $backTrace['line'] : 0;
        $file       = !empty($backTrace['file']) ? $backTrace['file'] : '';
        $__METHOD__ = $className . $methodName;

        if (empty($result) || !isset($result->error)) {
            $errMsg = array(
                'msg'      => $__METHOD__ . ' return null',
                'file'     => $file,
                'line'     => $line,
                'result'   => $result,
                'requests' => $requests,
            );

            log_message('error', json_encode($errMsg));

            return false;
        }

        if ($result->error != 0) {
            $errMsg = array(
                'msg'      => $__METHOD__ . ' failed',
                'file'     => $file,
                'line'     => $line,
                'result'   => $result,
                'requests' => $requests,
            );

            //log_message('error', json_encode($errMsg));
        }

        return $result;
    }

}
