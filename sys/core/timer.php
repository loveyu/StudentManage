<?php
namespace Core;
if(!defined('_CorePath_'))
	exit;
/**
 * 时间计时器类
 * @author loveyu
 */
class Timer{
	/**
	 * @var float 开始时间
	 */
	private $begin_time = 0;

	/**
	 * @var float 结束时间
	 */
	private $end_time = 0;


	/**
	 * 构造函数，默认开始计时
	 * @param null|string $microtime 可以使用一个默认时间，但必须是从microtime取得
	 */
	public function  __construct($microtime = null){
		if(empty($microtime)){
			$this->start();
		} else{
			$this->setBeginTime($microtime);
		}
	}

	/**
	 * 启动或重置计时器
	 */
	public function start(){
		$this->begin_time = $this->get_microtime();
		$this->end_time = 0;
	}

	/**
	 * 获取运行的微秒数
	 * @return float
	 */
	private function get_microtime(){
		list($u_sec, $sec) = explode(' ', microtime());
		return ((float)$u_sec + (float)$sec);
	}

	/**
	 * 停止计时,设置一个时间点，如果要获取时间点的时间传递参数时使用true
	 */
	public function stop(){
		$this->end_time = $this->get_microtime();
	}

	/**
	 * 设置开始时间
	 * @param string $microtime 使用microtime()函数获取的时间
	 */
	public function setBeginTime($microtime){
		list($u_sec, $sec) = explode(' ', $microtime);
		$this->begin_time = ((float)$u_sec + (float)$sec);
	}

	/**
	 * 获取运行的微秒数
	 * @param bool $last 是否显示上次计时的时间，默认false显示当前计时
	 * @return float|int
	 */
	public function get_micro($last = false){
		if(!$last || $this->end_time == 0)
			$this->stop();
		return round(($this->end_time - $this->begin_time) * 1000000, 5);
	}

	/**
	 * 获取运行的秒数
	 * @param bool $last 是否显示上次计时的时间，默认false显示当前计时
	 * @return float
	 */
	public function get_second($last = false){
		if(!$last || $this->end_time == 0)
			$this->stop();
		return round($this->end_time - $this->begin_time, 5);
	}

	/**
	 * 获取运行的分钟数
	 * @param bool $last 是否显示上次计时的时间，默认false显示当前计时
	 * @return float
	 */
	public function get_minute($last = false){
		if(!$last || $this->end_time == 0)
			$this->stop();
		return round(($this->end_time - $this->begin_time) / 60, 5);
	}

	/**
	 * 获取运行的毫秒数
	 * @param bool $last 是否显示上次计时的时间，默认false显示当前计时
	 * @return float
	 */
	public function get_Millisecond($last = false){
		if(!$last || $this->end_time == 0)
			$this->stop();
		return round(($this->end_time - $this->begin_time) * 1000, 5);
	}

	/**
	 * 获取启动时间
	 * @return float
	 */
	public function getBeginTime(){
		return $this->begin_time;
	}

	/**
	 * 获取结束时间
	 * @return float
	 */
	public function getEndTime(){
		return $this->end_time;
	}
}

?>