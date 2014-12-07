<?php
/**
 * http请求发送类
 *
 * @author icesyc
 */

class HttpClient{

	public static $instance;
	private $ch;
	public $timeout = 5;
	public $error;

	public function __construct(){
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		//防止post的时候发送100-continue
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Expect:'));
		//curl_setopt($this->ch, CURLOPT_VERBOSE, true);
		//curl_setopt($this->ch, CURLOPT_STDERR, fopen('curl.log', 'w'));
	}

	public function method($method){
		$method = strtoupper($method);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
		return $this;
	}

	public function url($url){
		curl_setopt($this->ch, CURLOPT_URL, $url);
		return $this;
	}

	public function data($data){
		$data && curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		return $this;
	}

	public function exec(){
		$res = curl_exec($this->ch);
		if(curl_errno($this->ch) > 0){
			$this->error = curl_error($this->ch);
			return false;
		}
		return $res;
	}

	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function get($url){
		$http = self::getInstance();
		return $http->url($url)->method('get')->exec();
	}

	public static function post($url, $data=array()){
		$http = self::getInstance();
		return $http->url($url)->method('post')->data($data)->exec();
	}

	public static function getError(){
		return self::getInstance()->error;
	}
}
