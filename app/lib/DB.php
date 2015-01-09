<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 23:23
 */

namespace ULib;


use CLib\Sql;

class DB{

	/**
	 * @var Sql
	 */
	private $driver;

	function __construct(){
		c_lib()->load('sql');
		$this->driver = new Sql(cfg()->get('database'));
		if(!$this->driver->status()){
			define('HAS_RUN_ERROR', true);
			cfg()->set('HAS_RUN_ERROR', $this->driver->ex_message());
		}
	}

	public function get_admin_info($name){
		return $this->driver->get("admin", "*", ['a_name' => $name]);
	}

}