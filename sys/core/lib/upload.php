<?php
/**
 * Created by Loveyu.
 * User: loveyu
 * Date: 14-2-21
 * Time: 下午6:16
 * LyCore
 * Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 * 参照ThinkPHP
 */

namespace CLib;

c_lib()->load('interface/UploadInterface');

/**
 * 文件上传操作类
 * Class Upload
 * @package CLib
 */
class Upload{

	/**
	 * 配置文件
	 * @var array
	 */
	private $config = [
		//存储根目录
		'root_path' => '',
		//最大文件大小，0为不限制
		'max_size' => 0,
		//保存路径
		'save_path' => '',
		//允许的类型列表
		'mime_list' => [],
		//是否创建HASH值
		'make_hash' => false,
		//子目录回调函数
		'sub_path' => [
			'date',
			'Y/md'
		],
		//启用子目录存储
		'sub_status' => false,
		//拓展名
		'exts' => [],
		//强制文件名后缀
		'save_ext' => '',
		//文件名命名回调函数
		'name_callback' => [
			'uniqid',
			''
		],
		//图片文件信息
		'image_info' => false,
		//覆盖同名文件
		'replace' => false,
	];

	/**
	 * @var UploadInterface
	 */
	private $uploader = NULL;

	/**
	 * @var string
	 */
	private $error = '';

	/**
	 * @param array      $config
	 * @param string     $driver
	 * @param array|null $driver_config
	 */
	function __construct($config, $driver = 'Local', $driver_config = NULL){
		$this->config = array_merge($this->config, $config);
		$this->driver($driver, $driver_config);
	}

	/**
	 * 设置驱动
	 * @param $d
	 * @param $c
	 */
	private function driver($d, $c){
		c_lib()->load('upload/' . $d);
		$name = "CLib\\Upload\\$d";
		if(!class_exists($name)){
			$this->throwMsg(-1);
		}
		$this->uploader = new $name($this->root_path, $c);
	}

	/**
	 *
	 */
	private function throwMsg($code){
		throw new \Exception($this->getMsg($code));
	}

	/**
	 * @param $name
	 * @return null
	 */
	function __get($name){
		if(isset($this->config[$name])){
			return $this->config[$name];
		} else{
			return NULL;
		}
	}

	/**
	 * @param $name
	 * @param $value
	 */
	function __set($name, $value){
		if(isset($this->config[$name])){
			$this->config[$name] = $value;
		}
	}

	/**
	 * @param $name
	 * @return bool
	 */
	function __isset($name){
		return isset($this->config[$name]);
	}

	/**
	 * @param null $files
	 * @throw \Exception
	 * @return array
	 */
	public function upload($files = NULL){
		if(empty($files)){
			$files = $_FILES;
		}
		if(empty($files)){
			$this->throwMsg(-2);
		}
		if(!$this->uploader->checkRootPath($this->root_path)){
			$this->throwMsg(1);
		}
		if(!$this->uploader->checkSavePath($this->save_path)){
			$this->throwMsg(1);
		}
		/* 逐个检测并上传文件 */
		$info = array();
		if(function_exists('finfo_open')){
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
		}
		// 对上传文件数组信息处理
		$files = $this->dealFiles($files);
		foreach($files as $key => $file){
			if(!isset($file['key'])){
				$file['key'] = $key;
			}
			/* 通过扩展获取文件类型，可解决FLASH上传$FILES数组返回文件类型错误的问题 */
			if(isset($finfo)){
				$file['type'] = @finfo_file($finfo, $file['tmp_name']);
			}
			/* 获取上传文件后缀，允许上传无后缀文件 */
			$file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);

			/* 文件上传检测 */
			if(!$this->check($file)){
				continue;
			}
			/* 获取文件hash */
			if($this->make_hash){
				$file['md5'] = md5_file($file['tmp_name']);
				$file['sha1'] = sha1_file($file['tmp_name']);
			}
			/* 生成保存文件名 */
			$save_name = $this->getSaveName($file);
			if(false == $save_name){
				continue;
			} else{
				$file['save_name'] = $save_name;
			}

			/* 检测并创建子目录 */
			$sub_path = $this->get_sub_path($file['name']);
			if(false === $sub_path){
				continue;
			} else{
				$file['save_path'] = $this->savePath . $sub_path;
			}
			/* 对图像文件进行严格检测 */
			$ext = strtolower($file['ext']);
			if(in_array($ext, array(
				'gif',
				'jpg',
				'jpeg',
				'bmp',
				'png',
				'swf'
			))
			){
				$imginfo = getimagesize($file['tmp_name']);
				if(empty($imginfo) || ($ext == 'gif' && empty($imginfo['bits']))){
					$this->error = _('Illegal image file!');
					continue;
				}
				if($this->image_info){
					$file['image'] = [];
					c_lib()->load('image');
					$image = new Image(Image::IMAGE_GD, $file['tmp_name']);
					$file['image']['width'] = $image->width();
					$file['image']['height'] = $image->height();
					unset($image);
				}
			}

			/* 保存文件 并记录保存成功的文件 */
			if($this->uploader->save($file, $this->replace)){
				unset($file['error'], $file['tmp_name']);
				$info[$key] = $file;
			} else{
				$this->error = $this->uploader->getError();
			}
		}
		if(empty($info)){
			$this->throwMsg(2);
		}
		return $info;
	}

	/**
	 * 获取要保存的文件名
	 * @param $file
	 * @return bool|string
	 */
	private function getSaveName($file){
		$rule = $this->name_callback;
		if(empty($rule)){ //保持文件名不变
			/* 解决pathinfo中文文件名BUG */
			$filename = substr(pathinfo("_{$file['name']}", PATHINFO_FILENAME), 1);
			$savename = $filename;
		} else{
			$savename = $this->getName($rule, $file['name']);
			if(empty($savename)){
				$this->error = _('File naming mistake!');
				return false;
			}
		}
		/* 文件保存后缀，支持强制更改文件后缀 */
		$ext = empty($this->config['save_ext']) ? $file['ext'] : $this->save_ext;
		return $savename . '.' . $ext;
	}

	/**
	 * 获取子路径
	 * @param string $filename
	 * @return bool|string
	 */
	private function get_sub_path($filename){
		$sub_path = '';
		$rule = $this->sub_path;
		if($this->sub_status && !empty($rule)){
			$sub_path = $this->getName($rule, $filename) . '/';

			if(!empty($sub_path) && !$this->uploader->mkdir($this->save_path . $sub_path)){
				$this->error = $this->uploader->getError();
				return false;
			}
		}
		return $sub_path;
	}

	/**
	 * 根据指定规则获取文件名
	 * @param $rule
	 * @param $filename
	 * @return string
	 */
	private function getName($rule, $filename){
		$name = '';
		if(is_array($rule)){ //数组规则
			$func = $rule[0];
			$param = (array)$rule[1];
			foreach($param as &$value){
				/**
				 * __FILE__ 用作参数替换
				 */
				$value = str_replace('__FILE__', $filename, $value);
			}
			$name = @call_user_func_array($func, $param);
		} elseif(is_string($rule)){ //字符串规则
			if(function_exists($rule)){
				$name = call_user_func($rule);
			} else{
				$name = $rule;
			}
		}
		return $name;
	}

	/**
	 * 检测文件是否符合规则
	 * @param $file
	 * @return bool
	 */
	private function check($file){
		/* 文件上传失败，捕获错误代码 */
		if($file['error']){
			$this->error($file['error']);
			return false;
		}

		/* 无效上传 */
		if(empty($file['name'])){
			$this->error = _('Unknown upload error!');
		}

		/* 检查是否合法上传 */
		if(!is_uploaded_file($file['tmp_name'])){
			$this->error = _('Illegal upload files!');
			return false;
		}

		/* 检查文件大小 */
		if(!$this->checkSize($file['size'])){
			$this->error = _('Upload file size does not match!');
			return false;
		}

		/* 检查文件Mime类型 */
		//TODO:FLASH上传的文件获取到的mime类型都为application/octet-stream
		if(!$this->checkMime($file['type'])){
			$this->error = _('Upload file MIME type does not allow!');
			return false;
		}

		/* 检查文件后缀 */
		if(!$this->checkExt($file['ext'])){
			$this->error = _('Upload file extension is not allowed');
			return false;
		}

		/* 通过检测 */
		return true;
	}

	/**
	 * 获取错误代码信息
	 * @param string $errorNo 错误号
	 */
	private function error($errorNo){
		switch($errorNo){
			case 1:
				$this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
				break;
			case 2:
				$this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
				break;
			case 3:
				$this->error = '文件只有部分被上传！';
				break;
			case 4:
				$this->error = '没有文件被上传！';
				break;
			case 6:
				$this->error = '找不到临时文件夹！';
				break;
			case 7:
				$this->error = '文件写入失败！';
				break;
			default:
				$this->error = '未知上传错误！';
		}
	}

	/**
	 * 检查文件大小是否合法
	 * @param int $size 数据
	 * @return bool
	 */
	private function checkSize($size){
		return !($size > $this->max_size) || (0 == $this->max_size);
	}

	/**
	 * 检查上传的文件MIME类型是否合法
	 * @param string $mime 数据
	 * @return bool
	 */
	private function checkMime($mime){
		return empty($this->config['mime_list']) ? true : in_array(strtolower($mime), $this->mime_list);
	}

	/**
	 * 检查上传的文件后缀是否合法
	 * @param string $ext 后缀
	 * @return bool
	 */
	private function checkExt($ext){
		return empty($this->config['exts']) ? true : in_array(strtolower($ext), $this->exts);
	}

	/**
	 * 转换上传文件数组变量为正确的方式
	 * @param array $files 上传的文件变量
	 * @return array
	 */
	private function dealFiles($files){
		$fileArray = array();
		$n = 0;
		foreach($files as $key => $file){
			if(is_array($file['name'])){
				$keys = array_keys($file);
				$count = count($file['name']);
				for($i = 0; $i < $count; $i++){
					$fileArray[$n]['key'] = $key;
					foreach($keys as $_key){
						$fileArray[$n][$_key] = $file[$_key][$i];
					}
					$n++;
				}
			} else{
				$fileArray[$key] = $file;
			}
		}
		return $fileArray;
	}

	/**
	 * 获取异常信息
	 * @param int $code
	 * @return string
	 */
	private function getMsg($code){
		switch($code){
			case 2:
				//上传失败，抛出错误信息
				return $this->error;
			case 1:
				//获取驱动错误
				return $this->uploader->getError();
			case -1:
				return _("Lib load error.");
			case -2:
				return _("Files is empty");
			case -3:
				return _("Root path check error.");
			case -4:
				return _("Save path check error.");
		}
		return _("Unknown");
	}

	/**
	 * 上传单一文件
	 * @param $file
	 * @return array
	 */
	public function uploadOne($file){
		$info = $this->upload([$file]);
		return $info ? $info[0] : $info;
	}
}