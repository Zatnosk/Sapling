<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/blog.php";
if(isset($_POST['title']) && isset($_POST['content'])){
	$blog_id = Blog::write($_POST['title'], $_POST['content'], $_POST['visibility']);
	header('Location: '.ROOTPATH.'blog/?id='.$blog_id);
	exit;
}
header('Location: '.ROOTPATH.'blog/');
?>