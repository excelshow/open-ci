<?php

/**
 * redis list client
 *
 * @author    xumenghe@wefit.com.cn
 *
 */
class CI_Redis_List_Client
{
	private $persistent = false;

	private $host = '127.0.0.1';

	private $port = 6379;

	private $password = '';

	private $timeout = 2;

	private $instance = null;

	private $topic = 'default_redis_mq_topic';

	private $key_suffix = 'mq_';

    private $config = array();

	public function __construct(array $config)
	{
		if (!extension_loaded('redis')) {
			throw new Exception("Redis extension libraries is not installed.");
		}

		if (isset($config['persistent']) and is_bool($config['persistent'])) {
			$this->persistent = $config['persistent'];
		}

		if (isset($config['host'])) {
			$this->host = $config['host'];
		}

		if (isset($config['port']) and is_numeric($config['port'])) {
			$this->port = $config['port'];
		}

		if (isset($config['timeout']) and is_numeric($config['timeout'])) {
			$this->timeout = $config['timeout'];
		}

		if (isset($config['key_suffix']) and is_string($config['key_suffix'])) {
			$this->key_suffix = $config['key_suffix'];
		}

		if (isset($config['password']) and is_string($config['password'])) {
			$this->password = $config['password'];
		}

        $this->config = $config;
        unset($this->config['password']);
	}

	/**
	 * left push a message to redis list
	 * @key_suffix true 默认添加key_suffix
	 * @return The new length of the list in case of success, FALSE in case of Failure
	 */
	public function LeftPush($topic, $msg, $key_suffix=true)
	{
		if (empty($this->instance) or !is_object($this->instance)) {
			$this->_connect();
		}

		$topic or $topic = $this->topic;
        if($key_suffix){
            $key = $this->key_suffix . $topic;
        }else{
            $key = $topic;
        }

		try {
			$length = $this->instance->lPush($key, $msg);
			$this->_close();

		} catch (RedisException $e) {
			$this->_close();
			// $message = "Adds the string value to the head (left) of the list faild.";
			$message = __METHOD__ . '|' . $topic . '|' . $key . '|' . $e->getMessage() . '|' . json_encode($this->config);
			throw new Exception($message, 0, $e);
		}

		return $length;
	}

	private function _connect()
	{
		if (!empty($this->instance) and is_object($this->instance)) {
			return;
		}

		// create redis instance
		try {
			$this->instance = new Redis();

			if ($this->persistent) {
				$this->instance->pconnect(
					$this->host,
					$this->port,
					$this->timeout
				);
			} else {
				$this->instance->connect(
					$this->host,
					$this->port,
					$this->timeout
				);
			}
			if (!empty($this->password)) {
				$this->instance->auth($this->password);
			}
		} catch (RedisException $e) {
			// $message = "Create Redis instance faild.";
			$message = __METHOD__ . '|' . $e->getMessage() . '|' . json_encode($this->config);
			throw new Exception($message, 0, $e);
		}
	}

	private function _close()
	{
		if (empty($this->instance) or !is_object($this->instance)) {
			return;
		}

		try {
			$this->instance->close();

		} catch (RedisException $e) {
			$this->instance = null;
			// $message = 'Disconnects from the Redis instance faild.';
			$message = __METHOD__ . '|' . $e->getMessage() . '|' . json_encode($this->config);
			throw new Exception($message, 0, $e);
		}

		$this->instance = null;
	}

	/**
	 * Returns and removes the last element of the list.
	 *
	 * @return STRING if command executed successfully, BOOL FALSE in case of failure (empty list)
	 */
	public function RightPop($topic)
	{
		if (empty($this->instance) or !is_object($this->instance)) {
			$this->_connect();
		}

		$topic or $topic = $this->topic;
		$key = $this->key_suffix . $topic;

		try {
			$msg = $this->instance->rPop($key);
			$this->_close();

		} catch (RedisException $e) {
			$this->_close();
			// $message = "Returns and removes the last element of the list faild.";
			$message = __METHOD__ . '|' . $topic . '|' . $key . '|' . $e->getMessage() . '|' . json_encode($this->config);
			throw new Exception($message, 0, $e);
		}

		return $msg;
	}

	public function __destruct()
	{
		$this->_close();
	}
}

