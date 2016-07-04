<?php

namespace Gutplushe\ApnsPHP;

use Gutplushe\ApnsPHP\XingeApp;
use Gutplushe\ApnsPHP\Model\Message;
use Gutplushe\ApnsPHP\Model\Style;
use Gutplushe\ApnsPHP\Model\ClickAction;

class AndroidPush
{

	protected $push = null;
	public $errno;
	public $errMsg;

	/**
	 * 初始化推送实例
	 */
	public function __construct($accessId = '', $secretKey = '') {

		$this->push = new XingeApp($accessId, $secretKey);

		return $this;
	}

	/**
	 * 添加消息
	 */
	public function add($dt, $msg)
	{

		$mess = new Message();

		$style = new Style(0, 0, 0, 1, 0, 1, 0, 1);

		$action = new ClickAction();
		$action->setActionType(1);
		$action->setActivity('AndroidActivity');

		// 设置标题
		if (isset($msg['title']) && $msg['title'])
			$mess->setTitle($msg['title']);

		// 推送消息正文
		if (isset($msg['content']) && $msg['content'])
			$mess->setContent($msg['content']);

		// 自定义参数
		if (isset($msg['custom']) && $msg['custom'])
			$mess->setCustom($msg['custom']);

		$mess->setType(1);
		$mess->setStyle($style);
		$mess->setAction($action);
		$mess->setExpireTime(86400);

		$this->body = $mess;

		return $this;
	}

	/**
	 * 推送单个设备
	 * $dt deviceToken
	 */
	public function push($dt = null, $data = null) {

		$this->add($dt, $data);

		$env = 0;

		$result = $this->push->PushSingleDevice($dt, $this->body, $env);

		if (isset($result['ret_code']) && $result['ret_code']<0) {
			$this->errno = $result['ret_code'];
			$this->errmsg = $result['err_msg'];
		}

		return $result;
	}

	/**
	 * 推送给App所有设备
	 * $dt deviceType
	 */
	public function pushAll($dt = null, $data = null) {

		$this->add($dt, $data);

		$env = 0;

		$result = $this->push->PushAllDevices($dt, $this->body, $env);
		if (isset($result['ret_code']) && $result['ret_code']<0) {
			$this->errno = $result['ret_code'];
			$this->errmsg = $result['err_msg'];
		}

		return $result;
	}

	/**
	 * 获取错误信息
	 */
	public function getErrors() {

		$errNo = $this->errno;
		$errMsg = $this->errmsg;

		return array($errNo, $errMsg);
	}

}