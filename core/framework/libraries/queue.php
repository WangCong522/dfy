<?php

class QueueClient
{
	static 	private $queuedb;

	static public function push($key, $value)
	{
		if (!C("queue.open")) {
			Logic("queue")->{$key}($value);
			return NULL;
		}

		if (!is_object(self::$queuedb)) {
			self::$queuedb = new QueueDB();
		}

		return self::$queuedb->push(serialize(array($key => $value)));
	}
}

class QueueServer
{
	private $_queuedb;

	public function __construct()
	{
		$this->_queuedb = new QueueDB();
	}

	public function pop($key, $time)
	{
		return unserialize($this->_queuedb->pop($key, $time));
	}

	public function scan()
	{
		return $this->_queuedb->scan();
	}
}

class QueueDB
{
	private $_redis;
	private $_tb_prefix = "QUEUE_";
	private $_tb_num = 2;
	private $_tb_tmp = "TMP_TABLE";

	public function __construct()
	{
		if (!extension_loaded("redis")) {
			throw_exception("redis failed to load");
		}

		$this->_redis = new Redis();
		$this->_redis->connect(C("queue.host"), C("queue.port"));
		$this->_tb_prefix = C("redis.prefix") . $this->_tb_prefix;
	}

	public function push($value)
	{
		try {
			return $this->_redis->lPush($this->_tb_prefix . rand(1, $this->_tb_num), $value);
		}
		catch (Exception $e) {
			throw_exception($e->getMessage());
		}
	}

	public function scan()
	{
		$list_key = array();

		for ($i = 1; $i <= $this->_tb_num; $i++) {
			$list_key[] = $this->_tb_prefix . $i;
		}

		return $list_key;
	}

	public function pop($key, $time)
	{
		try {
			if ($result = $this->_redis->brPop($key, $time)) {
				return $result[1];
			}
		}
		catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function clear()
	{
		$this->_redis->flushAll();
	}
}


?>
