<?php
/**
 * User: loveyu
 * Date: 2015/1/11
 * Time: 8:33
 */

namespace UView;


use ULib\Page;

class BaseInfo extends Page{
	private $info_data = [];

	function __construct(){
		parent::__construct();
		$this->info_data = cfg()->load(_RootPath_ . "/config/base_info.php");
	}


	public function op($type = NULL, $action = 'list'){
		if(!isset($this->info_data[$type])){
			$this->__load_404();
			return;
		}
		if(!in_array($action, [
			'list',
			'edit',
			'add',
		])
		){
			$this->__load_404();
			return;
		}
		$access = access_class();
		if($action == "list"){
			if(!$access->read($type)){
				$this->permission_deny();
				return;
			}
		} else{
			if(!$access->write($type)){
				$this->permission_deny();
				return;
			}
		}
		$info = [
			'type' => $type,
			'info' => $this->info_data[$type]
		];
		switch($action){
			case 'add':
				$this->__view("base_info/add.php", $info);
				break;
			case 'list':
				$query = query_class();
				$query->setBaseInfo($info);
				$info['query'] = $query;
				$this->__view("base_info/list.php", $info);
				break;
			case 'edit':
				$this->__view("base_info/edit.php", $info);
				break;
		}
	}

	public function ajax($type = ''){
		header("Content-Type: application/json; charset=utf-8");
		$db = db_class()->getDriver();
		$id = $this->__req->get('id');
		$t = $this->__req->get('t');
		switch($type){
			case "college_select_year":
				echo json_encode(array_keys(list2keymap($db->select("info_discipline", ["id_time"], [
					'ico_id' => $id,
					'GROUP' => 'ico_id'
				]), 'id_time', 'id_time')));
				break;
			case "c_and_y_select_id":
				echo json_encode(list2keymap($db->select("info_discipline", [
					"id_id",
					"id_name"
				], [
					'AND' => [
						'ico_id' => $id,
						'id_time' => $t,
					]
				]), 'id_id', 'id_name'));
				break;
		}
	}

	public function add($type = NULL){
		if(!isset($this->info_data[$type])){
			$this->__load_404();
			return;
		}
		$access = access_class();
		if(!$access->write($type)){
			$this->permission_deny();
			return;
		}
		header("Content-Type: application/json; charset=utf-8");

		$info = [];
		$rt = [
			'status' => false,
			'msg' => NULL
		];
		$flag = false;
		$flag_filed = "";
		foreach($this->info_data[$type]['filed'] as $name => $v){
			if(isset($v['hide']) && $v['hide']){
				continue;
			}
			$flag_filed = $v['name'];
			$info[$name] = trim($this->__req->post($name));
			$flag = false;
			if(isset($v['rule'])){
				if(preg_match($v['rule'], $info[$name]) != 1){
					$flag = true;
					break;
				}
			}
			if(isset($v['check_func'])){
				if(!call_user_func($v['check_func'], $info[$name])){
					$flag = true;
					break;
				}
			}
			if(!$flag && isset($v['check']) && is_array($v['check'])){
				foreach($v['check'] as $c){
					switch($c){
						case 'no_empty':
							if(empty($info[$name])){
								$flag = true;
							}
							break;
						case 'is_number':
							if(!is_numeric($info[$name])){
								$flag = true;
							}
							break;
						case 'is_email':
							if(!filter_var($info[$name], FILTER_VALIDATE_EMAIL)){
								$flag = true;
							}
							break;
					}
					if($flag){
						break;
					}
				}
			}
			if($flag){
				break;
			}
		}
		if($flag){
			$rt['msg'] = '信息检测出错:' . $flag_filed;
		}
		if(empty($rt['msg'])){
			$i = db_class()->base_info_insert($this->info_data[$type]['table'], $info);
			if($i >= 0){
				$rt['status'] = true;
			} else{
				$rt['msg'] = "添加数据失败";
			}
		}
		echo json_encode($rt);

	}
}