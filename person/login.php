<?php
require_once __DIR__."/person.php";

if(isset($_POST['name']) && isset($_POST['password'])){
	 $person = Person::login($_POST['name'], $_POST['password']);
	 if($person){
	 	header('Location: ../index.php');
	 	exit;
	 }
}
header('Location: ../index.php?msg='.urlencode('login failed'));
?>