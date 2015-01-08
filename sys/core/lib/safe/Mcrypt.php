<?php
namespace CLib\Safe;

use CLib\SafeInterface;

class Mcrypt implements SafeInterface{
	/**
	 * 加密函数
	 * @param string $encrypt
	 * @param string $key
	 * @return string
	 */
	public function encrypt($encrypt, $key = ''){
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
		$pass_crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
		$encode = base64_encode($pass_crypt);
		return $encode;
	}

	/**
	 * 解密函数
	 * @param string $decrypt
	 * @param string $key
	 * @return string
	 */
	public function decrypt($decrypt, $key = ''){
		$decoded = base64_decode($decrypt);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv);
		return $decrypted;
	}

}