<?php
/**
 * User: loveyu
 * Date: 2015/1/9
 * Time: 13:42
 */

namespace UView;


use ULib\Page;

class Profile extends Page{
	function __construct(){
		parent::__construct();
	}

	function edit(){
		$this->__view('home/profile_edit.php');
	}

}