<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/blog.php";
if(isset($_GET['id']) && isset($_GET['vis'])){
	Blog::set_visibility($_GET['id'], $_GET['vis']);
	header('Location: '.ROOTPATH.'blog/?id='.$_GET['id']);
	exit;
}
header('Location: '.ROOTPATH.'blog/');
?>