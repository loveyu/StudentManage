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