<?php
namespace CLib;
/**
 * 文件操作类
 * Class Ip
 */
class File{
	public function path_remove($path, $r = false){
		if(!$r){
			return @rmdir($path);
		} else{
			$handle = opendir($path);
			if(!$handle){
				return false;
			}
			while($file = readdir($handle)){
				if(($file == ".") || ($file == "..")){
					continue;
				}
				if(is_file($path . "/" . $file)){
					if(!unlink($path . "/" . $file)){
						return false;
					}
				} elseif(is_dir($path . "/" . $file)){
					if(!$this->path_remove($path . "/" . $file, $r)){
						return false;
					}
				}
			}
			closedir($handle);
			if(!rmdir($path)){
				return false;
			}
			return true;
		}
	}
}