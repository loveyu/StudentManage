<?php
namespace CLib\Safe;

use CLib\SafeInterface;

class Simple implements SafeInterface{
	/**
	 * 加密函数
	 * @param string $encrypt
	 * @param string $key
	 * @return string
	 */
	public function encrypt($encrypt, $key = ''){
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000));
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($encrypt); $i++){
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $encrypt_key[$ctr] . ($encrypt[$i] ^ $encrypt_key[$ctr++]);
		}
		return base64_encode($this->passport_key($tmp, $key));
	}

	/**
	 * 密钥生成
	 * @param $txt
	 * @param $encrypt_key
	 * @return string
	 */
	private function passport_key($txt, $encrypt_key){
		$encrypt_key = md5($encrypt_key);
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++){
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
		}
		return $tmp;
	}

	/**
	 * 解密函数
	 * @param string $decrypt
	 * @param string $key
	 * @return string
	 */
	public function decrypt($decrypt, $key = ''){
		//当不存在mcrypt函数库时的解密方法
		$txt = $this->passport_key(base64_decode($decrypt), $key);
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++){
			$md5 = $txt[$i];
			$tmp .= $txt[++$i] ^ $md5;
		}
		return $tmp;
	}

}