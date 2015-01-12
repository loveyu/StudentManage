<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 21:03
 */


/**
 * 生成一个js引入连接
 * @param array $list 传入名称列表
 * @return string
 */
function mkt_js($list){
	if(is_string($list)){
		$list = ['src' => $list];
	}
	if(!isset($list['type'])){
		$list['type'] = 'text/javascript';
	}
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<script$d></script>";
}

/**
 * 生成css引入连接
 * @param array|string $list 传入名称列表
 * @return string
 */
function mkt_css($list){
	if(!is_array($list)){
		$list = ['href' => $list];
	}
	if(!isset($list['rel'])){
		$list['rel'] = 'stylesheet';
	}
	if(!isset($list['type'])){
		$list['type'] = 'text/css';
	}
	return mkt_link($list);
}

/**
 * 生成引入连接
 * @param array $list
 * @return string
 */
function mkt_link($list){
	if(!is_array($list)){
		$list = ['href' => $list];
	}
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<link$d />";
}

/**
 * 生成标签
 * @param array $list
 * @return string
 */
function mkt_meta($list){
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<meta$d />";
}

//---相关用户信息函数------------
/**
 * 角色相关信息
 * @param $info
 * @return string
 */
function role_info($info){
	if(empty($info)){
		return "未知角色";
	}
	return $info['name'] . "[" . $info['id'] . "]" . (!$info['status'] ? "(<span class='text-success'>正常</span>)" : "(<span class='text-danger'>禁用</span>)");
}

/**
 * 用户状态
 * @param $status
 * @return string
 */
function user_status($status){
	$arr = [
		0 => '正常',
		1 => '限制登录',
		2 => '解除权限'
	];
	return isset($arr[$status]) ? $arr[$status] : "[未知]";
}

/**
 * 获取角色的状态
 * @param $status
 * @return string
 */
function role_status($status){
	switch($status){
		case 0:
			return "<span class='text-success'>正常</span>";
		case 1:
			return "<span class='text-danger'>禁用</span>";
		default:
			return "<span class='text-warning'>未知</span>";
	}
}

/**
 * 获取权限的状态
 * @param $status
 * @return string
 */
function permission_status($status){
	return role_status($status);
}

/**
 * 生成OPTION数据
 * @param array  $value_list
 * @param string $select
 * @return string
 */
function html_option($value_list, $select){
	$rt = "";
	foreach($value_list as $value => $name){
		$rt .= "<option value=\"{$value}\"" . ($value == $select ? " selected" : "") . ">{$name}</option>";
	}
	return $rt;
}

/**
 * 权限状态信息
 * @param $arr
 * @return string
 */
function access_status($arr){
	$r = isset($arr['ac_r']) && $arr['ac_r'];
	$w = isset($arr['ac_w']) && $arr['ac_w'];
	if($r && $w){
		return "读写";
	}
	if($r){
		return "只读";
	}
	if($w){
		return "只写";
	}
	return "无权限";
}

/**
 * 将数据列表转为KeyMap
 * @param array        $list
 * @param string       $key
 * @param string|array $value
 * @return array
 */
function list2keymap($list, $key, $value){
	if(!isset($list[0])){
		return [];
	}
	$rt = [];
	if(is_array($value)){
		foreach($list as $v){
			$rt[$v[$key]] = [];
			foreach($value as $v2){
				$rt[$v[$key]][$v2] = $v[$v2];
			}
		}
	} else{
		foreach($list as $v){
			$rt[$v[$key]] = $v[$value];
		}
	}
	return $rt;
}

function get_campus_info(){
	$db = db_class();
	return list2keymap($db->get_campus_list(), 'ic_name', 'ic_name');
}

/**
 * 如果存在返回True
 * @param $name
 * @return bool
 */
function check_campus_info($name){
	return db_class()->check_campus_name($name);
}

function get_college_info(){
	$db = db_class();
	$rt = [];
	foreach(list2keymap($db->get_college_simple_list(), 'ico_id', [
		'ico_name',
		'ic_name',
	]) as $name => $v){
		$rt[$name] = htmlspecialchars(implode(" - ", $v));
	}
	return $rt;
}

/**
 * 如果存在返回True
 * @param $id
 * @return bool
 */
function check_college_info($id){
	return db_class()->check_college_id($id);
}

function ref_college_set($name){
	if(!isset($GLOBALS['REF_LIST']) || !is_array($GLOBALS['REF_LIST'])){
		$GLOBALS['REF_LIST'] = [];
	}
	$GLOBALS['REF_LIST'][$name] = '';
}

function ref_college_get(){
	if(!isset($GLOBALS['REF_LIST'])){
		return [];
	}
	$list = list2keymap(db_class()->get_college_names(array_keys($GLOBALS['REF_LIST'])), "ico_id", "ico_name");
	unset($GLOBALS['REF_LIST']);
	return $list;
}

function ref_college_get_and_campus(){
	if(!isset($GLOBALS['REF_LIST'])){
		return [];
	}
	$list = list2keymap(db_class()->get_college_names_and_campus(array_keys($GLOBALS['REF_LIST'])), "ico_id", [
		"ico_name",
		"ic_name"
	]);
	unset($GLOBALS['REF_LIST']);
	return $list;
}

function ref_discipline_set($name){
	if(!isset($GLOBALS['REF_LIST']) || !is_array($GLOBALS['REF_LIST'])){
		$GLOBALS['REF_LIST'] = [];
	}
	$GLOBALS['REF_LIST'][$name] = '';
}

function ref_discipline_get(){
	if(!isset($GLOBALS['REF_LIST'])){
		return [];
	}
	$list = list2keymap(db_class()->get_discipline_names(array_keys($GLOBALS['REF_LIST'])), "id_id", "id_name");
	unset($GLOBALS['REF_LIST']);
	return $list;
}

function ref_teacher_set($name){
	if(!isset($GLOBALS['REF_LIST']) || !is_array($GLOBALS['REF_LIST'])){
		$GLOBALS['REF_LIST'] = [];
	}
	$GLOBALS['REF_LIST'][$name] = '';
}

function ref_teacher_get(){
	if(!isset($GLOBALS['REF_LIST'])){
		return [];
	}
	$list = list2keymap(db_class()->get_teacher_names(array_keys($GLOBALS['REF_LIST'])), "it_id", "it_name");
	unset($GLOBALS['REF_LIST']);
	return $list;
}

function ref_curriculum_set($name){
	if(!isset($GLOBALS['REF_LIST']) || !is_array($GLOBALS['REF_LIST'])){
		$GLOBALS['REF_LIST'] = [];
	}
	$GLOBALS['REF_LIST'][$name] = '';
}

function ref_curriculum_get(){
	if(!isset($GLOBALS['REF_LIST'])){
		return [];
	}
	$list = list2keymap(db_class()->get_curriculum_names(array_keys($GLOBALS['REF_LIST'])), "cu_id", "cu_name");
	unset($GLOBALS['REF_LIST']);
	return $list;
}

function textarea_out($name){
	return implode("<br>", array_map('htmlspecialchars', explode("\n", $name)));
}

function implode_out($name){
	if(!is_array($name)){
		return $name;
	}
	return implode(" - ", $name);
}