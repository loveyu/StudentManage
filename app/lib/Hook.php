<?php
/**
 * User: loveyu
 * Date: 2015/1/6
 * Time: 19:00
 */

namespace ULib;


class Hook{
	public function add() {
		l_h("system.php","theme.php");
		lib()->load('Page');
	}
} 