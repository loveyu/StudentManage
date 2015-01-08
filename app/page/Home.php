<?php
namespace UView;

use \Core\Page;

class Home extends Page{


	/**
	 * Home page
	 */
	public function main(){
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo 404;
	}
}