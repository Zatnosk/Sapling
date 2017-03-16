<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";

$person = Person::get_current();

$section = section(body()->el('main'), $person->name);
$section->p('Redigér profil');
$form = $section->form(ROOTPATH.'person/write_profile.php','post');
$form->textarea('content',$person->profile);
$form->submit('Gem');
?>