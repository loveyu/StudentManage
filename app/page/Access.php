<?php
/**
 * User: loveyu
 * Date: 2015/1/9
 * Time: 18:47
 */

namespace UView;


use ULib\Page;

class Access extends Page{
	function __construct(){
		parent::__construct();
	}

	public function role(){
		if(!$this->check()){
			return;
		}
		$this->__view("access/role.php", ['list' => db_class()->get_role_list()]);
	}

	public function admin(){
		if(!$this->check()){
			return;
		}
		$edit_id = $this->__req->get('edit_id');
		if($edit_id > 0){
			$this->__view("access/admin_edit.php", [
				'info' => db_class()->get_admin_info_by_id($edit_id),
				'role_list' => $this->get_role_keymap()
			]);
		} else{
			$this->__view("access/admin.php", [
				'list' => db_class()->get_admin_list(),
				'role_list' => $this->get_role_keymap()
			]);
		}
	}

	public function permission(){
		if(!$this->check()){
			return;
		}
		$this->__view("access/permission.php", ['list' => db_class()->get_permission_list()]);
	}

	public function access_set(){
		if(!$this->check()){
			return;
		}
		$id = $this->__req->get('id');
		if($id > 0){
			$db = db_class();
			$this->__view("access/access_set.php", [
				'role' => $db->get_role_info($id),
				'role_access' => list2keymap($db->get_role_access($id), "p_id", [
					'ac_w',
					'ac_r'
				]),
				'permission' => list2keymap($db->get_permission_list(), 'p_id', 'p_alias')
			]);
		} else{
			$this->__view("access/access_set_get_role.php", ['role_list' => db_class()->get_role_list()]);
		}
	}

	public function add_access(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$db = db_class();
		$p_id = intval($this->__req->post('p_id'));
		$r_id = intval($this->__req->post('r_id'));
		$ac_w = $this->__req->post('write');
		$ac_r = $this->__req->post('read');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		$ac_r = $ac_r ? 1 : 0;
		$ac_w = $ac_w ? 1 : 0;

		$id = $db->access_add(compact('p_id', 'r_id', 'ac_w', 'ac_r'));
		if($id == 0){
			$rt['status'] = true;
		} else{
			$rt['msg'] = "添加失败";
		}

		echo json_encode($rt);
	}

	public function delete_access(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$r_id = $this->__req->post('r_id');
		$p_id = $this->__req->post('p_id');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		$db = db_class();
		$id = $db->access_delete($r_id, $p_id);
		if($id == 1){
			$rt['status'] = true;
		} else{
			$rt['msg'] = "删除失败";
		}
		echo json_encode($rt);
	}

	public function add_role(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$db = db_class();
		$name = $this->__req->post('name');
		$status = $this->__req->post('status');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if($db->role_exists_check($name)){
			$rt['msg'] = '当前角色已存在';
		} else{
			$id = $db->role_add([
				'r_name' => $name,
				'r_status' => $status
			]);
			if($id > 0){
				$rt['status'] = true;
			} else{
				$rt['msg'] = "添加失败";
			}
		}
		echo json_encode($rt);
	}

	public function add_permission(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$db = db_class();
		$p_name = $this->__req->post('name');
		$p_alias = $this->__req->post('alias');
		$p_status = $this->__req->post('status');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(preg_match("/^[a-z_]+$/", $p_name) != 1){
			$rt['msg'] = '名称检测错误';
		} else{
			if($db->check_permission_exists($p_name)){
				$rt['msg'] = '当前权限已存在';
			} else{
				$id = $db->permission_add(compact('p_name', 'p_alias', 'p_status'));
				if($id > 0){
					$rt['status'] = true;
				} else{
					$rt['msg'] = "添加失败";
				}
			}
		}
		echo json_encode($rt);
	}

	function delete_permission(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$id = $this->__req->post('id');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		$db = db_class();
		if($db->rely_permission($id)){
			$rt['msg'] = "存在依赖关系，无法删除";
		} else{
			$id = $db->permission_delete($id);
			if($id == 1){
				$rt['status'] = true;
			} else{
				$rt['msg'] = "删除失败";
			}
		}
		echo json_encode($rt);
	}

	public function edit_permission(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$p_id = $this->__req->post('id');
		$p_alias = $this->__req->post('alias');
		$p_name = $this->__req->post('name');
		$p_status = $this->__req->post('status');
		$rt = [
			'status' => false,
			'msg' => ''
		];

		$db = db_class();
		$data = $db->permission_get($p_id);
		if(!isset($data['p_name'])){
			$rt['msg'] = "数据不存在";
		} else{
			if($data['p_name'] == $p_name){
				unset($p_name);
			}
			if(isset($p_name)){
				if(preg_match("/^[a-z_]+$/", $p_name) != 1){
					$rt['msg'] = '名称检测错误';
				} else{
					if($db->check_permission_exists($p_name)){
						$rt['msg'] = '当前权限已存在';
					}
				}
			}
		}
		if(empty($rt['msg'])){
			$id = $db->update_permission_by_id($p_id, compact('p_alias', 'p_name', 'p_status'));
			if($id == 1){
				$rt['status'] = true;
			} else{
				$rt['msg'] = "编辑失败或有冲突或无改变";
			}
		}
		echo json_encode($rt);
	}

	function add_admin(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$a_name = trim($this->__req->post('name'));
		$a_pwd = trim($this->__req->post('pwd'));
		$r_id = trim($this->__req->post('role'));
		$a_salt = salt(32);
		$a_status = trim($this->__req->post('status'));
		$a_ip = array_unique(array_map("trim", explode("\n", trim($this->__req->post('ip')))));
		$a_ip = implode("|", explode("|", implode("|", $a_ip)));

		$rt = [
			'status' => false,
			'msg' => ''
		];
		if(empty($a_pwd)){
			$rt['msg'] = "密码不允许为空";
		} else{
			$db = db_class();
			$a_pwd = salt_hash(md5_xx($a_pwd), $a_salt);
			if($db->admin_exists_check($a_name)){
				$rt['msg'] = "用户已存在";
			} else{
				if(!$db->role_id_exists_check($r_id)){
					$rt['msg'] = "角色不存在";
				} else{
					if($db->admin_add(compact('a_name', 'a_status', 'a_ip', 'a_pwd', 'a_salt', 'r_id')) > 0){
						$rt['status'] = true;
					} else{
						$rt['msg'] = "添加失败";
					}
				}
			}
		}
		echo json_encode($rt);
	}

	public function edit_admin(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$a_name = trim($this->__req->post('name'));
		$a_id = trim($this->__req->post('id'));
		$r_id = trim($this->__req->post('role'));
		$a_status = trim($this->__req->post('status'));
		$a_ip = array_unique(array_map("trim", explode("\n", trim($this->__req->post('ip')))));
		$a_ip = implode("|", explode("|", implode("|", $a_ip)));
		$db = db_class();
		$info = $db->get_admin_info_by_id($a_id);
		if(!isset($info['a_id']) || $info['a_id']!=$a_id){
			$rt['msg'] = "数据有误";
		}else{
			if($a_name == $info['a_name']){
				unset($a_name);
			}
			if(isset($a_name) && $db->admin_exists_check($a_name)){
				$rt['msg'] = "用户已存在";
			} else{
				if(!$db->role_id_exists_check($r_id)){
					$rt['msg'] = "角色不存在";
				} else{
					if($db->update_admin_info($a_id, compact('a_name', 'a_status', 'a_ip', 'r_id')) > 0){
						$rt['status'] = true;
					} else{
						$rt['msg'] = "更新失败或无修改";
					}
				}
			}
		}
		echo json_encode($rt);
	}

	function delete_admin(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$id = $this->__req->post('id');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if($id == login_class()->uid()){
			$rt['msg'] = "禁止删除自己";
		} else{
			$db = db_class();
			if($db->rely_admin($id)){
				$rt['msg'] = "存在依赖关系，无法删除";
			} else{
				$id = $db->admin_delete($id);
				if($id == 1){
					$rt['status'] = true;
				} else{
					$rt['msg'] = "删除失败";
				}
			}
		}
		echo json_encode($rt);
	}

	function admin_pwd_reset(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$id = $this->__req->post('id');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		$pwd = salt(12);
		$a_salt = salt(32);
		$a_pwd = salt_hash(md5_xx($pwd), $a_salt);
		$db = db_class();
		$id = $db->update_admin_info($id, compact('a_salt', 'a_pwd'));
		if($id == 1){
			$rt['status'] = true;
			$rt['msg'] = $pwd;
		} else{
			$rt['msg'] = "更新失败";
		}
		echo json_encode($rt);
	}

	function delete_role(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$id = $this->__req->post('id');
		$rt = [
			'status' => false,
			'msg' => ''
		];
		if($id == 1){
			$rt['msg'] = "禁止删除1号角色";
		} else{
			$db = db_class();
			if($db->rely_role($id)){
				$rt['msg'] = "存在依赖关系，无法删除";
			} else{
				$id = $db->role_delete($id);
				if($id == 1){
					$rt['status'] = true;
				} else{
					$rt['msg'] = "删除失败";
				}
			}
		}
		echo json_encode($rt);
	}


	public function edit_role(){
		header("Content-Type: application/json; charset=utf-8");
		if(!$this->check()){
			return;
		}
		$id = $this->__req->post('id');
		$name = $this->__req->post('name');
		$status = $this->__req->post('status');
		$rt = [
			'status' => false,
			'msg' => ''
		];

		$db = db_class();
		$id = $db->role_edit($id, $name, $status);
		if($id == 1){
			$rt['status'] = true;
		} else{
			$rt['msg'] = "编辑失败或有冲突或无改变";
		}
		echo json_encode($rt);
	}

	private function check(){
		if(!login_class()->check_role(1)){
			if($this->__req->is_ajax() && $this->__req->is_post()){
				echo json_encode([
					'status' => false,
					'msg' => '无访问权限'
				]);
			} else{
				$this->__view("home/permission_deny.php");
			}
			return false;
		}
		return true;
	}

	private function get_role_keymap(){
		$list = [];
		foreach(db_class()->get_role_list() as $v){
			$list[$v['r_id']] = $v['r_name'];
		}
		asort($list);
		return $list;

	}
}