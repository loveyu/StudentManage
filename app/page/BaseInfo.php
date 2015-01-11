<?php
/**
 * User: loveyu
 * Date: 2015/1/11
 * Time: 8:33
 */

namespace UView;


use ULib\Page;

class BaseInfo extends Page{
	private $info_data = [
		'campus_info' => [
			'name' => '校园信息',
			'table' => 'info_campus',
			'index' => 'ic_name',
			'filed' => [
				'ic_name' => [
					'name' => '名称',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'ic_address' => [
					'name' => '地址',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'ic_postcode' => [
					'name' => '邮编',
					'type' => 'text',
					'vt' => 'number',
					'rule' => '/^[0-9]{3,10}$/'
				],
				'ic_tel' => [
					'name' => '电话',
					'type' => 'text',
					'vt' => 'number',
					'rule' => '/^[0-9]{5,10}$/'
				],
			]
		],

		'discipline_info' => [
			'name' => '专业信息',
			'table' => 'info_discipline',
			'index' => 'id_id',
			'filed' => [
				'id_id' => [
					'name' => '编号',
					'type' => 'text',
					'vt' => 'text',
					'rule' => '/^[0-9]{3,10}$/',
				],
				'id_name' => [
					'name' => '名称',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'id_teacher' => [
					'name' => '系主任',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'id_time' => [
					'name' => '入学年份',
					'type' => 'text',
					'rule' => '/^[0-9]{4}$/',
					'check' => ['not_empty']
				],
				'ico_id' => [
					'name' => '学院',
					'type' => 'select',
					'check_func' => 'check_college_info',
					'select_func' => 'get_college_info',
					'ref_set'=>'ref_college_set',
					'ref_get'=>'ref_college_get',
				],
			]
		],
		'college_info' => [
			'name' => '学院信息',
			'table' => 'info_college',
			'index' => 'ico_id',
			'filed' => [
				'ico_id' => [
					'name' => '编号',
					'type' => 'text',
					'vt' => 'text',
					'rule' => '/^[0-9]{3,10}$/',
					'check' => ['not_empty']
				],
				'ico_name' => [
					'name' => '名称',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'ic_name' => [
					'name' => '校区',
					'type' => 'select',
					'check_func' => 'check_campus_info',
					'select_func' => 'get_campus_info'
				],
				'ico_teacher' => [
					'name' => '主要负责人',
					'type' => 'text',
					'vt' => 'text',
					'check' => ['not_empty']
				],
				'ico_tel' => [
					'name' => '电话',
					'type' => 'text',
					'vt' => 'number',
					'rule' => '/^[0-9]{5,10}$/'
				],
			]
		]
	];

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
		foreach($this->info_data[$type]['filed'] as $name => $v){
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
			$rt['msg'] = '信息检测出错';
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