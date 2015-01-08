<?php
namespace CLib;

use Core\Log;

if(!defined('_CorePath_')){
	exit;
}

c_lib()->load('interface/SqlInterface','medoo');

/**
 * Class Sql
 * @package CLib
 */
class Sql implements SqlInterface{
	/**
	 * 数据库连接状态
	 * @var bool
	 */
	private $status = false;
	/**
	 * 连接的异常信息
	 * @var string
	 */
	private $ex_message = "";

	/**
	 * @var \medoo
	 */
	private $_reader = NULL;

	/**
	 * @var \medoo
	 */
	private $_writer = NULL;

	/**
	 * @var array 写入设置
	 */
	private $_setting = NULL;

	/**
	 * @var array 读取设置
	 */
	private $_setting_read = NULL;

	/**
	 * @var bool 数据库可写状态
	 */
	private $_write_status = false;

	/**
	 * @var bool 是否启用读写分离
	 */
	private $_only_write = false;

	/**
	 * 构造器，对生成异常进行捕获
	 * @param array      $setting 可写数据库配置信息，当仅仅配置该数据库时，读写为同一数据库
	 * @param array|null $read    只读数据库配置
	 */
	public function __construct($setting, $read = NULL){
		if(!is_array($setting)){
			return;
		}
		hook()->add("Log_write", [
			$this,
			'log_hook'
		]);
		$this->_setting = $setting;
		$this->_setting_read = $read;
		$this->reopen();
	}

	/**
	 * 写入错误信息的钩子
	 */
	public function log_hook($message, $level){
		if($level === Log::SQL){
			$message .= "\nERROR:" . print_r($this->error(), true) . "SQL:" . print_r($this->last_query(), true);
		}
		return $message;
	}

	/**
	 * @return \medoo
	 */
	public function getWriter(){
		return $this->_writer;
	}

	/**
	 * @return \medoo
	 */
	public function getReader(){
		if(empty($this->_reader)){
			return $this->_writer;
		} else{
			return $this->_reader;
		}
	}


	/**
	 * 重新打开函数
	 */
	public function reopen(){
		$this->ex_message = NULL;
		$this->_write_status = false;
		try{
			if(!empty($this->_setting_read)){
				$this->_reader = new \medoo($this->_setting_read);
			} else{
				$this->_writer = new \medoo($this->_setting);
				$this->_only_write = true;
				$this->_write_status = true;
			}
			$this->status = true;
		} catch(\Exception $ex){
			$this->ex_message = $ex->getMessage();
		}
	}

	/**
	 *
	 */
	public function close(){
		$this->status = false;
		$this->_write_status = false;
		$this->_reader = NULL;
		$this->_writer = NULL;
	}

	/**
	 * 返回数据库连接状态
	 * @return bool
	 */
	public function status(){
		return $this->status;
	}

	/**
	 * 获取异常信息
	 * @return string
	 */
	public function ex_message(){
		return $this->ex_message;
	}

	/**
	 * 返回数据库的查询次数
	 * @return int
	 */
	public function get_query_count(){
		if(!empty($this->_reader)){
			return $this->_writer->get_query_count() + $this->_reader->get_query_count();
		}
		return $this->_writer->get_query_count();
	}

	/**
	 * 打开写入数据库
	 */
	public function open_write(){
		if($this->_write_status === true){
			return true;
		}
		try{
			$this->_writer = new \medoo($this->_setting);
			$this->_write_status = true;
		} catch(\Exception $ex){
			$this->ex_message = $ex->getMessage();
		}
		return $this->_write_status;
	}

	/**
	 * 关闭写入数据库
	 */
	public function close_write(){
		if($this->_only_write){
			return;
		}
		$this->_writer = NULL;
		$this->_write_status = false;
	}

	/**
	 * @param $query
	 * @return \PDOStatement
	 */
	public function query($query){
		return $this->_writer->query($query);
	}

	/**
	 * @param $query
	 * @return int
	 */
	public function exec($query){
		return $this->_writer->exec($query);
	}

	/**
	 * @param $string
	 * @return string
	 */
	public function quote($string){
		return $this->_writer->quote($string);
	}

	/**
	 * @param $where
	 * @return string
	 */
	public function where_clause($where){
		return $this->_writer->quote($where);
	}

	/**
	 * @param      $table
	 * @param      $join
	 * @param null $columns
	 * @param null $where
	 * @return array|bool
	 */
	public function select($table, $join, $columns = NULL, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->select($table, $join, $columns, $where);
		} else{
			return $this->_reader->select($table, $join, $columns, $where);
		}
	}

	/**
	 * @param $table
	 * @param $data
	 * @return int
	 */
	public function insert($table, $data){
		return call_user_func_array([
			$this->_writer,
			'insert'
		], func_get_args());
		//		return $this->_writer->insert($table, $data);
	}

	/**
	 * @param      $table
	 * @param      $data
	 * @param null $where
	 * @return int
	 */
	public function update($table, $data, $where = NULL){
		return $this->_writer->update($table, $data, $where);
	}

	/**
	 * @param $table
	 * @param $where
	 * @return int
	 */
	public function delete($table, $where){
		return $this->_writer->delete($table, $where);
	}

	/**
	 * @param      $table
	 * @param      $columns
	 * @param null $search
	 * @param null $replace
	 * @param null $where
	 * @return int
	 */
	public function replace($table, $columns, $search = NULL, $replace = NULL, $where = NULL){
		return $this->_writer->replace($table, $columns, $search, $replace, $where);
	}

	/**
	 * @param      $table
	 * @param      $columns
	 * @param null $where
	 * @return bool|array
	 */
	public function get($table, $columns, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->get($table, $columns, $where);
		} else{
			return $this->_reader->get($table, $columns, $where);
		}
	}

	/**
	 * @param $table
	 * @param $where
	 * @return bool
	 */
	public function has($table, $where){
		if(empty($this->_reader)){
			return $this->_writer->has($table, $where);
		} else{
			return $this->_reader->has($table, $where);
		}
	}

	/**
	 * @param      $table
	 * @param null $where
	 * @return int
	 */
	public function count($table, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->count($table, $where);
		} else{
			return $this->_reader->count($table, $where);
		}
	}

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return int
	 */
	public function max($table, $column, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->max($table, $column, $where);
		} else{
			return $this->_reader->max($table, $column, $where);
		}
	}

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return int
	 */
	public function min($table, $column, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->min($table, $column, $where);
		} else{
			return $this->_reader->min($table, $column, $where);
		}
	}

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return int
	 */
	public function avg($table, $column, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->avg($table, $column, $where);
		} else{
			return $this->_reader->avg($table, $column, $where);
		}
	}

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return int
	 */
	public function sum($table, $column, $where = NULL){
		if(empty($this->_reader)){
			return $this->_writer->sum($table, $column, $where);
		} else{
			return $this->_reader->sum($table, $column, $where);
		}
	}

	/**
	 * @return array
	 */
	public function error(){
		$rt = array(
			'write' => $this->_writer->error(),
			'read' => NULL
		);
		if(empty($this->_reader)){
			$rt['read'] = $rt['write'];
		} else{
			$rt['read'] = $this->_reader->error();
		}
		return $rt;
	}

	/**
	 * @return array
	 */
	public function last_query(){
		$rt = array(
			'write' => $this->_writer->last_query(),
			'read' => NULL
		);
		if(empty($this->_reader)){
			$rt['read'] = $rt['write'];
		} else{
			$rt['read'] = $this->_reader->last_query();
		}
		return $rt;
	}

	/**
	 * @return array
	 */
	public function info(){
		$rt = array(
			'write' => $this->_writer->info(),
			'read' => NULL
		);
		if(empty($this->_reader)){
			$rt['read'] = $rt['write'];
		} else{
			$rt['read'] = $this->_reader->info();
		}
		return $rt;
	}
}