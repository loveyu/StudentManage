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
			$id = $db->role_delete($id);
			if($id == 1){
				$rt['status'] = true;
			} else{
				$rt['msg'] = "删除失败";
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
}