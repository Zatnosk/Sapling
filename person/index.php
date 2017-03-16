<?php
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('id');
require_once __DIR__."/../layout.php";
require_once __DIR__."/person.php";

$is_own_profile = $_GET['id'] == Person::get_current()->id;

$person = Person::get($_GET['id']);

$section = section(body()->el('main'), $person->name);

if($is_own_profile){
	 $section->a(ROOTPATH.'person/edit_profile.php','Redigér profil');
}
$section->te(' ');
$section->a(ROOTPATH."blog/?author=$_GET[id]",'Blogs');
$section->te(' ');
if(!$is_own_profile){
	if(Person::has_on_contact_list($person->id)){
		$section->te('[I kontaktliste]');
	} else {
		$section->a(ROOTPATH."person/add_to_contact_list.php?id=$_GET[id]&from=profile",'[Føj til kontaktliste]');
	}
}


$usertext = $section->el('div',['class'=>'usertext']);
if(empty($person->profile)) $usertext->te('Tom profil');
else $usertext->te($person->profile,HEAL_TEXT_NL2BR);
?>