<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";
require_once __DIR__."/person.php";

$ul = section(body()->el('main'), 'Kontaktliste')->el('ul');

$contact_list = Person::get_contact_list();
foreach($contact_list as $contact){
	$li = $ul->el('li');
	$li->a(ROOTPATH."person/?id=$contact->id",$contact->name);
	$li->te(' ');
	$li->a(ROOTPATH."person/remove_from_contact_list.php?id=$contact->id",'[Fjern]');
}

?>