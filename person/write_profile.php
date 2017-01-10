<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/../person/person.php";
if(Person::is_logged_in()
	&& isset($_POST['content'])){
	$content = $_POST['content'];
	$content = str_replace(" ","\xC2\xA0",$content);
	Person::get_current()->write_profile($content);
}
header('Location: '.ROOTPATH.'person/?id='.Person::get_current()->id);
?>