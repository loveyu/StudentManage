<?php
namespace CLib\PCache;

c_lib()->load('pcache');

use CLib\PCacheInterface;

/**
 * 文件缓存驱动
 * Class File
 * @package CLib\Cache
 */
class File implements PCacheInterface{
	function __construct($config){
		if(!is_dir(_Cache_ . "/out/00")){
			$this->mk_cache_dir();
		}
	}

	/**
	 * 生成临时文件目录
	 * @throws \Exception 抛出异常信息
	 */
	private function mk_cache_dir(){
		if(!is_writable(_Cache_ . "/out/")){
			throw new \Exception(_("Page cache path can't write!"));
		}
		for($i = 0; $i < 0xff; $i++){
			$x = dechex($i);
			if(strlen($x) == 1){
				$x = "0" . $x;
			}
			if(!file_exists(_Cache_ . "/out/{$x}")){
				//创建目录
				mkdir(_Cache_ . "/out/{$x}", 0777);
			}
		}
	}

	/**
	 * 读取缓存内容
	 * @param string $name 名称
	 * @param int    $exp  超时，秒
	 * @return string|false 返回缓存内容
	 */
	public function read($name, $exp){
		$path = _Cache_ . "/out/" . substr($name, 0, 2) . "/" . $name;
		if(is_file($path) && is_readable($path)){
			if(filemtime($path) + $exp > NOW_TIME){
				//判断是否过期
				return file_get_contents($path);
			} else{
				unlink($path);
			}
		}
		return false;
	}

	/**
	 * 写入缓存内容
	 * @param string $name
	 * @param string $content
	 * @param int    $exp 超时
	 * @return void
	 */
	public function write($name, $content, $exp){
		$path = _Cache_ . "/out/" . substr($name, 0, 2) . "/" . $name;
		file_put_contents($path, $content);
	}

}