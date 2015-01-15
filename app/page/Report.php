<?php
/**
 * User: loveyu
 * Date: 2015/1/15
 * Time: 23:15
 */

namespace UView;


use ULib\Page;

class Report extends Page{

	function __construct(){
		parent::__construct();
		if(!login_class()->is_login()){
			redirect([
				'Home',
				'login'
			]);
		}
	}

	public function main(){
		$access = access_class();
		if(!$access->read("scores_list_report")){
			$this->permission_deny();
			return;
		}
		$this->__view("report/main.php");
	}

	public function post(){
		header("Content-Type: text/html; charset=utf-8");
		$access = access_class();
		if(!$access->read("scores_list_report")){
			$this->permission_deny();
			return;
		}
		$id_id = $this->__req->req('id_id');
		$icl_id = $this->__req->req('icl_id');
		$mc_number = $this->__req->req('mc_number');
		$mc_year = $this->__req->req('mc_year');
		$mc_s = compact('id_id', 'mc_number', 'mc_year');
		$msg = "";
		if(count($mc_s) != 3){
			$msg = "提交数据不完全";
		} else{
			foreach($mc_s as $v){
				if(empty($v)){
					$msg = "数据存在空值";
					break;
				}
			}
		}
		if($msg){
			$this->__view('report/error.php', compact('msg'));
			return;
		}
		$db = db_class();
		$mc_list = $db->getDriver()->select("mg_curriculum", ["[><]info_curriculum" => ['cu_id' => "cu_id"]], [
			"mc_id",
			"cu_name"
		], ['AND' => $mc_s]);
		if(count($mc_list) < 1){
			$msg = "无数据可供查询";
			$this->__view('report/error.php', compact('msg'));
			return;
		}
		$mc_list = list2keymap($mc_list, "mc_id", "cu_name");
		if(!empty($icl_id)){
			$icl_id = ['info_student.icl_id' => $icl_id];
		} else{
			$icl_id = [];
		}
		$table = [];
		foreach($mc_list as $mc_id => $cu_name){
			$now_select = $db->report_curriculum($mc_id, $icl_id);
			foreach($now_select as $v){
				if(!isset($table[$v['is_id']])){
					$table[$v['is_id']] = [];
					$table[$v['is_id']]['name'] = $v['is_name'];
					$table[$v['is_id']]['class'] = $v['icl_number'];
				}
				if(!isset($table[$v['is_id']]['list'])){
					$table[$v['is_id']]['list'] = [];
				}
				if(!isset($table[$v['is_id']]['list'][$mc_id])){
					$table[$v['is_id']]['list'][$mc_id] = [
						'test' => $v['sc_test'],
						'work' => $v['sc_work'],
						'total' => $v['sc_total']
					];
				}
			}
		}
		ksort($table);
		$info = $db->get_curriculum_info_by_mc_id(array_keys($mc_list)[0]);
		$this->__view('report/report.php', compact('info', 'table', 'mc_list','mc_s'));
	}
}