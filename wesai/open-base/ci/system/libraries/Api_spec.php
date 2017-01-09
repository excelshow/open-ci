<?php
/**
 * Normalization variables
 *
 * @see 		http://wiki.wefit.com.cn/dev:define:api_spec
 * @author 		xumenghe@wefit.com.cn
 *
 */

class CI_Api_spec
{
	const EXEC_CORRECT = 0;  // 执行正确
	
	private $_canonical = null;

	public function __construct() {

		$this->_canonical = new stdClass();
		$this->_canonical->error = self::EXEC_CORRECT;  // 默认执行成功，只有错误对象才改变error属性的值。
		$this->_canonical->cost  = 0;
	}

	public function gen_empty()
	{
		return $this->_canonical;
	}

	public function gen_error($error_code, $error_info='')
	{
		$this->_canonical->error = $error_code;
		
		if (! empty($error_info)) {
			$this->_canonical->info = $error_info;
		}

		return $this->_canonical;
	}

	public function gen_insert($lastid)
	{
		$this->_canonical->lastid = $lastid;

		return $this->_canonical;
	}

	public function gen_insert_batch($affected_rows)
	{
		$this->_canonical->affected_rows = $affected_rows;

		return $this->_canonical;
	}

	public function gen_update($affected_rows)
	{
		$this->_canonical->affected_rows = $affected_rows;

		return $this->_canonical;
	}

	public function gen_delete($affected_rows)
	{
		$this->_canonical->affected_rows = $affected_rows;

		return $this->_canonical;
	}

	public function gen_list($data = array(), $total = 0, $page = 1, $size = 10, $ext = array())
	{
		$this->_canonical->total = (int)$total;
		$this->_canonical->page = (int)$page;
		$this->_canonical->size = (int)$size;
        if($ext){
            foreach($ext as $k=>$v){
                $this->_canonical->$k = $v;
            }
        }
		$this->_canonical->data = $data;

		return $this->_canonical;
	}

	public function gen_object($data)
	{
        if(empty($data)){
            $this->_canonical->result = null;
            return $this->_canonical;
        }

		is_object($data) or $data = (object)$data;
		$this->_canonical->result = $data;

		return $this->_canonical;
	}

    public function gen_string($data)
    {
        $this->_canonical->result = $data;
        return $this->_canonical;
    }

	public function gen_mqleng($length)
	{
		is_int($length) or $length = (int)$length;
		$this->_canonical->length = $length;

		return $this->_canonical;
	}

	public function calcu_cost($entry_point)
	{
		$this->_canonical->cost = microtime(true) - $entry_point;

		return $this->_canonical;
	}
	
	public function reset()
	{
		unset($this->_canonical);	
		$this->_canonical = new stdClass();
		$this->_canonical->error = self::EXEC_CORRECT;  // 默认执行成功，只有错误对象才改变error属性的值。
		//$this->_canonical->cost  = 0;	
	}	
}

