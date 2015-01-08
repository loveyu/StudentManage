<?php
namespace CLib;

/**
 * Session 操作接口
 * Interface SessionInterface
 * @package CLib
 */
interface SessionInterface
{
	/**
	 * GET操作
	 * @param $name string 数组键名
	 * @return mixed
	 */
	public function get($name);

	/**
	 * 设置操作
	 * @param $name string 数组键名
	 * @param $value string 对应的值
	 * @return bool
	 */
	public function set($name, $value);

	/**
	 * 删除操作
	 * @param $name string 数组键名
	 * @return bool
	 */
	public function delete($name);

	/**
	 * 彻底删除SESSION
	 * @return void
	 */
	public function destroy();
}