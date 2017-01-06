<?php
require_once __DIR__."/../access.php";
Access::logged_in();
Access::moderator();
require_once __DIR__."/../data.php";
require_once __DIR__."/../person/person.php";
if(isset($_GET['post']) && isset($_GET['thread'])){
	ForumData::remove_post($_GET['post'], Person::get_current());
	header('Location: '.ROOTPATH.'forum/thread.php?thread='.$_GET['thread']);
	exit;
}
header('Location: '.ROOTPATH.'forum/');
?>