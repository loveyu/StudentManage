<?php
namespace CLib;

c_lib()->load('interface/SafeInterface');

/**
 * 安全操作函数类
 * Class Safe
 * @package CLib
 */
class Safe{
	/**
	 * @var SafeInterface
	 */
	private $drive = NULL;

	function __construct($drive = 'Mcrypt'){
		if($drive == 'Mcrypt' && !function_exists('mcrypt_create_iv')){
			$drive = "Simple";
		}
		c_lib()->load('safe/' . $drive);
		$drive_name = "CLib\\Safe\\" . $drive;
		if(!class_exists($drive_name)){
			throw new \Exception(_("Safe Drive Not Found"));
		}
		$this->drive = new $drive_name();
	}

	/**
	 * 加密
	 * @param        $encrypt
	 * @param string $key
	 * @return string
	 */
	public function encrypt($encrypt, $key = ''){
		return call_user_func_array([
			$this->drive,
			'encrypt'
		], func_get_args());
	}

	/**
	 * 解密
	 * @param        $decrypt
	 * @param string $key
	 * @return string
	 */
	public function decrypt($decrypt, $key = ''){
		return call_user_func_array([
			$this->drive,
			'decrypt'
		], func_get_args());
	}
}