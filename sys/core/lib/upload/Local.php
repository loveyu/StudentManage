<?php
/**
 * 参考ThinkPHP
 */
namespace CLib\Upload;

use CLib\UploadInterface;

c_lib()->load('upload');

class Local implements UploadInterface{
	/**
	 * 上传文件根目录
	 * @var string
	 */
	private $rootPath;

	/**
	 * 本地上传错误信息
	 * @var string
	 */
	private $error = ''; //上传错误信息

	/**
	 * 构造函数，用于设置上传根路径
	 * @param string $root             根目录
	 * @param array  $server_root_path 需要的配置文件，此处留空
	 */
	public function __construct($root, $server_root_path = NULL){
		if($server_root_path === NULL || !isset($server_root_path['server_root_path'])){
			$this->rootPath = _BasePath_ . "/" . $root . "/";
		} else{
			$this->rootPath = $server_root_path['server_root_path'] . "/" . $root . "/";
		}
	}

	/**
	 * 检测上传根目录
	 * @param string $path
	 * @return boolean true-检测通过，false-检测失败
	 */
	public function checkRootPath($path){
		if(!(is_dir($this->rootPath) && is_writable($this->rootPath))){
			$this->error = _('Upload the root directory does not exist! Please try to manually create:') . $this->rootPath;
			return false;
		}
		return true;
	}


	public function checkPath($path){
		return is_dir($this->rootPath . $path);
	}

	/**
	 * 检测上传目录
	 * @param  string $save_path 上传目录
	 * @return bool              检测结果，true-通过，false-失败
	 */
	public function checkSavePath($save_path){
		/* 检测并创建目录 */
		if(!$this->mkdir($save_path)){
			return false;
		} else{
			/* 检测目录是否可写 */
			if(!is_writable($this->rootPath . $save_path)){
				$this->error = _('Upload directory') . $save_path . _(' can not writable!');
				return false;
			} else{
				return true;
			}
		}
	}

	/**
	 * 保存指定文件
	 * @param  array   $file    保存的文件信息
	 * @param  boolean $replace 同名文件是否覆盖
	 * @return boolean          保存状态，true-成功，false-失败
	 */
	public function save(&$file, $replace = true){
		if(!file_exists($this->rootPath . $file['save_path'])){
			$this->mkdir($this->rootPath . $file['save_path']);
		}
		$filename = $this->rootPath . $file['save_path'] . $file['save_name'];

		/* 不覆盖同名文件 */
		if(!$replace && is_file($filename)){
			$this->error = _('File exists ') . $file['save_name'];
			return false;
		}

		/* 移动文件 */
		if(!move_uploaded_file($file['tmp_name'], $filename)){
			$this->error = _('Save the file upload error!');
			return false;
		}

		return true;
	}

	/**
	 * 创建目录
	 * @param  string $save_path 要创建的穆里
	 * @return boolean          创建状态，true-成功，false-失败
	 */
	public function mkdir($save_path){
		$dir = $this->rootPath . $save_path;
		if(is_dir($dir)){
			return true;
		}

		if(mkdir($dir, 0777, true)){
			return true;
		} else{
			$this->error = _("Directory ") . $save_path . _(" Creation Failed!");
			return false;
		}
	}

	/**
	 * 获取最后一次上传错误信息
	 * @return string 错误信息
	 */
	public function getError(){
		return $this->error;
	}
}