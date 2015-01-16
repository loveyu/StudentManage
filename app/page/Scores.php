<?php
/**
 * User: loveyu
 * Date: 2015/1/14
 * Time: 17:55
 */

namespace UView;


use ULib\Page;

class Scores extends Page{

	function __construct(){
		parent::__construct();
		if(!login_class()->is_login()){
			redirect([
				'Home',
				'login'
			]);
		}
	}

	public function teacher_list(){
		$access = access_class();
		if(login_class()->is_login() && login_class()->getLoginType() == "teacher" && !$access->read("teacher_curriculum")){
			$this->permission_deny();
			return;
		}
		$this->setTitle("教师课程表");
		$this->__view("scores/teacher_list.php");
	}

	public function my_list(){
		$access = access_class();
		$this->setTitle("学生课程");
		if(login_class()->is_login() && login_class()->getLoginType() == "student" && !$access->read("my_curriculum")){
			$this->permission_deny();
			return;
		}
		$this->__view("scores/my_list.php");
	}

	public function teacher_add_scores(){
		$this->setTitle("成绩添加");
		$access = access_class();
		$login = login_class();
		if($login->is_login() && $login->getLoginType() == "teacher" && !$access->write("teacher_add_scores")){
			$this->permission_deny();
			return;
		}
		$mc_id = $this->__req->get('id');
		if(empty($mc_id)){
			$this->__view("scores/select_mc_id.php");
		} else{
			$db = db_class();
			if($db->check_mc_id_is_teacher($mc_id, $login->uid())){
				$this->__view("scores/teacher_add_scores.php", [
					'list' => $db->get_scores_student_list($mc_id),
					'info' => $db->get_curriculum_info_by_mc_id($mc_id)
				]);
			} else{
				$this->__view("scores/select_mc_id.php", ['msg' => '你无权限管理此课程']);
			}
		}
	}

	public function teacher_add_scores_ajax(){
		$access = access_class();
		$login = login_class();
		if($login->is_login() && $login->getLoginType() == "teacher" && !$access->write("teacher_add_scores")){
			$this->permission_deny();
			return;
		}
		$mc_id = $this->__req->post('mc_id');
		$is_id = $this->__req->post('is_id');
		$sc_work = $this->__req->post('sc_work');
		$sc_test = $this->__req->post('sc_test');
		$rt = [
			'status' => false,
			'msg' => NULL
		];
		if($sc_work === NULL || $sc_test === NULL || $sc_test === "" || $sc_work === ""){
			$rt['msg'] = "成绩不允许为空";
		} else{
			$sc_total = intval($sc_test * 0.8 + $sc_work * 0.2);
			$i = db_class()->base_info_edit("scores", compact('sc_work', 'sc_test', 'sc_total'), compact('mc_id', 'is_id'));
			if($i === false){
				$rt['msg'] = '更新失败';
			} else if($i == 0){
				$rt['msg'] = "数据无修改";
			} else{
				$rt['status'] = true;
				$rt['msg'] = compact('sc_work', 'sc_test', 'sc_total');
			}
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($rt);
	}

	public function get_ajax(){
		$access = access_class();
		$type = $this->__req->get('type');
		$login = login_class();
		switch($type){
			case "student":
				if($login->is_login() && $login->getLoginType() == "student" && !$access->read("my_curriculum")){
					$this->permission_deny();
					return;
				}
				break;
			case "teacher":
				if($login->is_login() && $login->getLoginType() == "teacher" && !$access->read("teacher_curriculum")){
					$this->permission_deny();
					return;
				}
				break;
			default:
				$this->permission_deny();
				return;
		}
		header("Content-Type: application/json; charset=utf-8");
		$mc_grade = $this->__req->post('mc_grade');
		$mc_year = $this->__req->post('mc_year');
		$mc_number = $this->__req->post('mc_number');
		$info = [];
		if(!empty($mc_number)){
			$info['mc_number'] = $mc_number;
		}
		if(!empty($mc_grade)){
			$info['mc_grade'] = $mc_grade;
		}
		if(!empty($mc_year)){
			$info['mc_year'] = $mc_year;
		}
		switch($type){
			case "student":
				unset($info['mc_grade']);
				$list = db_class()->student_scores($login->uid(), $info);
				break;
			case "teacher":
				$list = db_class()->teacher_curriculum($login->uid(), $info);
				break;
			default:
				$this->permission_deny();
				return;
		}
		if($list !== false){
			echo json_encode([
				'status' => true,
				'msg' => $list
			]);
		} else{
			echo json_encode([
				'status' => false,
				'msg' => '查询失败'
			]);
		}
	}

	public function add(){
		$access = access_class();
		if(!$access->write("scores_add")){
			$this->permission_deny();
			return;
		}
		$this->__view("scores/add.php");
	}

	public function add_ajax(){
		$access = access_class();
		if(!$access->write("scores_add")){
			$this->permission_deny();
			return;
		}
		header("Content-Type: application/json; charset=utf-8");
		$id_id = $this->__req->post('id_id');
		$icl_id = $this->__req->post('icl_id');
		$mc_id = $this->__req->post('mc_id');
		$rt = [
			'msg' => NULL,
			'status' => false
		];
		if(!is_numeric($id_id) || !is_numeric($mc_id)){
			$rt['msg'] = '专业字段或专业课程课号为空';
		} else{
			$db = db_class();
			if(!$db->check_mg_id_exists($id_id, $mc_id)){
				$rt['msg'] = "专业与课程不匹配";
			} else{
				if(!empty($icl_id)){
					if(!$db->check_class_exists($id_id, $icl_id)){
						$rt['msg'] = "无法找到对应的班级";
					} else{
						$rt['msg'] = $db->insert_mc_id_class_list($id_id, $mc_id, $icl_id);
						if($rt['msg'] === false){
							$rt['msg'] = "插入失败，检测重复或异常";
						} else{
							$rt['status'] = true;
						}
					}
				} else{
					$rt['msg'] = $db->insert_mc_id_list($id_id, $mc_id);
					if($rt['msg'] === false){
						$rt['msg'] = "插入失败，检测重复或异常";
					} else{
						$rt['status'] = true;
					}
				}
			}
		}
		echo json_encode($rt);
	}
}