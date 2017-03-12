<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/../person/person.php";
if(isset($_POST['title']) && isset($_POST['content'])){
	$thread_id = ForumData::write_thread($_POST['title']);
	if(is_int($thread_id)) ForumData::write_post($thread_id, Person::get_current(), $_POST['content']);
	header('Location: '.ROOTPATH.'forum/thread.php?thread='.$thread_id);
	exit;
}
header('Location: '.ROOTPATH.'forum/');
?>