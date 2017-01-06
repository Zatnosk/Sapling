<?php
require_once __DIR__."/config.php";
require_once __DIR__."/person/person.php";

class Access {
	public static function logged_in(){
		if(!Person::is_logged_in()){
			header('Location: '.ROOTPATH);
			exit;
		}
	}

	public static function require_get($var, $path = ''){
		if(!isset($_GET[$var])){
			header('Location: '.ROOTPATH.$path);
			exit;
		}
	}

	public static function moderator(){
		if(!Person::is_logged_in()
			|| !Person::is_moderator()){
			header('Location: '.ROOTPATH);
			exit;
		}
	}
}
?>