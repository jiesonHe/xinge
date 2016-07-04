<?php

namespace Gutplushe\ApnsPHP;

use Gutplushe\ApnsPHP\XingeApp;
use Gutplushe\ApnsPHP\Model\MessageIOS;

class IosPush
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

		$mess = new MessageIOS();

		// 设置角标 "1"
		if (isset($msg['badge']) && $msg['badge'])
			$mess->setBadge($msg['badge']);

		// 推送消息正文
		if (isset($msg['title']) && $msg['title'])
			$mess->setAlert($msg['title']);

		// 播放消息提醒音,不传默认default
		$sound = 'default';
		if (isset($msg['sound']) && $msg['sound']) {
			$sound = $msg['sound'];
		}
		$mess->setSound($sound);;

		// 自定义参数
		if (isset($msg['custom']) && $msg['custom'])
			$mess->setCustom($msg['custom']);

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


		$env = config('app.env') == 'pro' ? XingeApp::IOSENV_PROD : XingeApp::IOSENV_DEV;

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

		$env = config('app.env') == 'pro' ? XingeApp::IOSENV_PROD : XingeApp::IOSENV_DEV;

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