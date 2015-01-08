<?php
namespace CLib;

/**
 * 缓存驱动接口
 * Interface CacheInterface
 * @package CLib
 */
interface PCacheInterface{
	/**
	 * 读取缓存内容
	 * @param string $name 名称
	 * @param int    $exp  超时，秒
	 * @return string|false 返回缓存内容
	 */
	public function read($name, $exp);

	/**
	 * 写入缓存内容
	 * @param string $name
	 * @param string $content
	 * @param int    $exp 超时
	 * @return void
	 */
	public function write($name, $content, $exp);
}