<?php
namespace CLib;

interface SafeInterface{
	/**
	 * 加密函数
	 * @param string $encrypt
	 * @param string $key
	 * @return string
	 */
	public function encrypt($encrypt, $key = '');

	/**
	 * 解密函数
	 * @param string $decrypt
	 * @param string $key
	 * @return string
	 */
	public function decrypt($decrypt, $key = '');
}