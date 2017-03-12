<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/person.php";

if(isset($_GET['id']) && Person::exists($_GET['id'])){
	$group_id = Person::get_contact_list_id();
	if(isset($group_id)){
		GroupData::remove_person($group_id,$_GET['id']);
	}
}
header('Location: '.ROOTPATH.'person/contact_list.php');
?>