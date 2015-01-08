<?php
namespace CLib;

/**
 * 文件上传接口
 * Interface UploadInterface
 * @package CLib
 */
interface UploadInterface{
	public function checkRootPath($path);

	public function checkPath($path);

	public function checkSavePath($path);

	public function save(&$file, $replace = true);

	public function mkdir($path);

	public function getError();
}