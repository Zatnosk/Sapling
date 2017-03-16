<?php
require_once __DIR__."/person.php";

$person = Person::get_current();
if(isset($person)){
	$person->logout();
	header('Location: ../index.php?msg='.urlencode('logged out'));
	exit;
}
header('Location: ../index.php');
?>