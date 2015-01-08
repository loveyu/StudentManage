<?php
/**
 * Created by Loveyu.
 * User: loveyu
 * Date: 14-3-1
 * Time: 下午12:26
 * LyCore
 * Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Core;


/**
 * 日志记录类
 * Class Log
 * @package Core
 */
class Log{
	/**
	 * 是否读取了配置的状态
	 * @var bool
	 */
	private static $status = false;
	/**
	 * 严重错误: 导致系统崩溃无法使用
	 */
	const EMERG = 'EMERG';
	/**
	 * 警戒性错误: 必须被立即修改的错误
	 */
	const ALERT = 'ALERT';
	/**
	 * 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
	 */
	const CRIT = 'CRIT';
	/**
	 * 一般错误: 一般性错误
	 */
	const ERR = 'ERR';
	/**
	 * 警告性错误: 需要发出警告的错误
	 */
	const WARN = 'WARN';
	/**
	 * 通知: 程序可以运行但是还不够完美的错误
	 */
	const NOTICE = 'NOTICE';
	/**
	 * 信息: 程序输出信息
	 */
	const INFO = 'INFO'; //
	/**
	 * 调试: 调试信息
	 */
	const DEBUG = 'DEBUG';
	/**
	 *S QL
	 */
	const SQL = 'SQL';

	/**
	 * 配置数组
	 * @var array
	 */
	private static $config = [
		'time_format' => 'Y-m-d H:i:s',
		'type' => 3,
		'destination' => NULL,
		'headers' => NULL
	];

	/**
	 * 写入记录
	 * @param string $message
	 * @param string $level
	 */
	public static function write($message, $level = self::ERR){
		if(!self::$status){
			self::getConfig();
		}
		$now = date(self::$config['time_format']);
		$message = Core::getInstance()->getHook()->apply("Log_write", $message, $level);
		error_log("[{$now}] " . URL_NOW . "; {$level}: {$message}\r\n", self::$config['type'], self::$config['destination'], self::$config['headers']);
	}

	/**
	 * 读取记录配置
	 */
	private static function getConfig(){
		$cfg = cfg()->get('system', 'error_log');
		if(is_array($cfg)){
			self::$config = array_merge(self::$config, $cfg);
		}
		if(empty(self::$config['destination']) && self::$config['type'] == 3){
			self::$config['destination'] = _LogPath_ . "/" . date("Y_m_d") . ".log";
		}
	}

	/**
	 * 程序结束错误信息输出
	 */
	public static function phpShowdownLog(){
		if(($e = error_get_last()) && $e['type']==E_ERROR || $e['type']==E_COMPILE_ERROR){
			call_user_func_array('\Core\Log::phpErrorLog', $e);
		}
	}

	/**
	 * 记录系统错误提示信息
	 * @param $error
	 * @param $message
	 * @param $file
	 * @param $line
	 */
	public static function phpErrorLog($error, $message, $file, $line){
		$type = NULL;
		switch($error){
			case E_COMPILE_ERROR:
				$type = "E_COMPILE_ERROR";
				break;
			case E_ERROR:
				$type = "E_ERROR";
				break;
			case E_WARNING:
				$type = "E_WARNING";
				break;
			case E_NOTICE:
				$type = "E_NOTICE";
				break;
			default:
				$type = "UNKNOWN";
		}
		if($type !== NULL){
			Log::write("[$type]:$message;$file:$line", Log::ERR);
		}
		if(_Debug_){
			echo "<br />\n<b>{$type}</b>:  {$message} in <b>{$file}</b> on line <b>{$line}</b><br />\n";
		}
	}
} 