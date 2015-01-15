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
		if(!login_class()->is_login()){
			redirect([
				'Home',
				'login'
			]);
		}
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
				$x_info = [];
				foreach($this->info_data[$type]['filed'] as $name => $v){
					if(isset($v['pk'])){
						$x_info[$name] = $this->__req->req($name);
						if(is_null($x_info[$name])){
							unset($x_info[$name]);
						}
					}
				}
				if(count($x_info) > 0){
					$info['data'] = db_class()->base_info_get($this->info_data[$type]['table'], array_keys($this->info_data[$type]['filed']), $x_info);
				} else{
					$info['data'] = "";
				}
				$this->__view("base_info/edit.php", $info);
				break;
		}
	}

	public function edit($type = ''){
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
		$pk = [];
		$rt = [
			'status' => false,
			'msg' => NULL
		];
		foreach($this->info_data[$type]['filed'] as $name => $v){
			if(isset($v['edit']) && $v['edit']){
				$info[$name] = $this->__req->post($name);
			}
			if(isset($v['pk'])){
				$pk[$name] = $this->__req->post($name);
			}
		}
		if(!count($info) || !count($pk)){
			$rt['msg'] = "提交字段异常";
		} else{
			$in = [];
			$rt['msg'] = $this->filed_check($type, $in, "edit");
			if(!empty($rt['msg'])){
				$rt['msg'] = "字段验证失败:" . $rt['msg'];
			} else{
				$i = db_class()->base_info_edit($this->info_data[$type]['table'], $info, $pk);
				if($i === false){
					$rt['msg'] = "编辑数据失败，请检查依赖值是否存在";
				} elseif($i !== 1){
					$rt['msg'] = "数据无修改";
				} else{
					$rt['status'] = true;
				}
			}
		}

		echo json_encode($rt);
	}

	public function ajax($type = ''){
		header("Content-Type: application/json; charset=utf-8");
		$access = access_class();
		if(!$access->read("base_info_ajax")){
			$this->permission_deny();
			return;
		}
		$db = db_class()->getDriver();
		$id = $this->__req->get('id');
		$t = $this->__req->get('t');
		switch($type){
			case "college_select_year":
				echo json_encode(array_keys(list2keymap($db->select("info_discipline", ["id_time"], [
					'ico_id' => $id,
					'GROUP' => 'id_time'
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
			case "campus_select_college":
				echo json_encode(list2keymap($db->select("info_college", [
					"ico_id",
					'ico_name'
				], ['ic_name' => $id]), "ico_id", 'ico_name'));
				break;
			case "id_select_class":
				echo json_encode(list2keymap($db->select("info_class", [
					"icl_id",
					'icl_number'
				], ['id_id' => $id]), "icl_id", 'icl_number'));
				break;
			case "college_curriculum":
				echo json_encode(list2keymap($db->select("info_curriculum", [
					"cu_id",
					'cu_name'
				], ['ico_id' => $id]), "cu_id", 'cu_name'));
				break;
			default:
				echo ['msg'=>'未定义内容','status'=>false];
				break;
		}
	}

	public function del($type){
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
		foreach($this->info_data[$type]['filed'] as $name => $v){
			if(isset($v['pk'])){
				$info[$name] = $this->__req->post($name);
			}
		}
		if(!count($info)){
			$rt['msg'] = "提交字段异常";
		} else{
			$i = db_class()->base_info_delete($this->info_data[$type]['table'], $info);
			if($i !== 1){
				$rt['msg'] = "删除数据失败，请检查依赖关系";
			} else{
				$rt['status'] = true;
			}
		}

		echo json_encode($rt);
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
		$filed_check = $this->filed_check($type, $info, "add");
		if(!empty($filed_check)){
			$rt['msg'] = '信息检测出错:' . $filed_check;
		}
		if(empty($rt['msg'])){
			if(isset($this->info_data[$type]['full_check']) && !$this->info_data[$type]['full_check']($info)){
				$rt['msg'] = "无法通过完整性约束检查";
			} else{
				$i = db_class()->base_info_insert($this->info_data[$type]['table'], $info);
				if($i >= 0){
					$rt['status'] = true;
				} else{
					$rt['msg'] = "添加数据失败";
				}
			}
		}
		echo json_encode($rt);

	}

	private function filed_check($type, &$info, $action_type){
		foreach($this->info_data[$type]['filed'] as $name => $v){
			if(isset($v['hide']) && $v['hide']){
				continue;
			}
			if(!isset($v['pk'])){
				if($action_type == "edit" && (!isset($v['edit']) || $v['edit'] == 0)){
					continue;
				}
			}
			$info[$name] = trim($this->__req->post($name));
			$flag = false;
			if(isset($v['rule'])){
				if(preg_match($v['rule'], $info[$name]) != 1){
					return $v['name'];
				}
			}
			switch($action_type){
				case "add":
					if(isset($v['check_func'])){
						if(!call_user_func($v['check_func'], $info[$name])){
							return $v['name'];
						}
					}
					break;
				case "edit":
					//将CheckFunc设置为无效
					break;
			}
			if(!$flag && isset($v['check']) && is_array($v['check'])){
				foreach($v['check'] as $c){
					switch($c){
						case 'no_empty':
						case 'not_empty':
							if(empty($info[$name])){
								return $v['name'];
							}
							break;
						case 'is_number':
							if(!is_numeric($info[$name])){
								return $v['name'];
							}
							break;
						case 'is_email':
							if(!filter_var($info[$name], FILTER_VALIDATE_EMAIL)){
								return $v['name'];
							}
							break;
						case 'is_tel':
							if(preg_match("/^[0-9]{5,20}$/", $info[$name]) != 1){
								return $v['name'];
							}
							break;
						case 'is_date':
							if($info[$name] != date("Y-m-d", strtotime($info[$name]))){
								return $v['name'];
							}
							break;
					}
				}
			}
		}
		return NULL;
	}
}