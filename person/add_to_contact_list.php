<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/person.php";

if(isset($_GET['id']) && Person::exists($_GET['id'])){
	$group_id = Person::get_contact_list_id(true);
	if(isset($group_id)){
		GroupData::add_person($group_id,$_GET['id']);
	}
	if(isset($_GET['from']) && $_GET['from']=='profile'){
		header('Location: '.ROOTPATH.'person/?id='.$_GET['id']);
		exit;
	}
}
header('Location: '.ROOTPATH);
?>