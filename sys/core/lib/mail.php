<?php
/**
 * Created by PhpStorm.
 * User: hzy
 * Date: 14-2-8
 * Time: 下午3:37
 */

namespace CLib;
c_lib()->load("phpmailer/PHPMailerAutoload");

class Mail extends \PHPMailer{

	public function __construct(){
		parent::__construct(true);
		$this->setLanguage("zh_cn", _CorePath_ . "/lib/phpmailer/language/");
		$this->setConfig();
	}

	private function setConfig(){
		$cfg = hook()->apply("Mail_setConfig", cfg()->get('mail'));
		foreach($cfg as $name => $value){
			if(isset($this->$name)){
				$this->$name = $value;
			}
		}
	}
}