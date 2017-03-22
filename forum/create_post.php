<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/../person/person.php";
if(isset($_GET['thread']) && !empty($_POST['content'])){
	$thread = intval($_GET['thread']);
	if($thread) ForumData::write_post($thread, Person::get_current(), $_POST['content']);
}
header('Location: '.ROOTPATH.'forum/thread.php?thread='.$_GET['thread']);
?>
