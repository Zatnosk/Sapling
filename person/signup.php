<?php
require_once __DIR__."/person.php";

if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['password']) && !empty($_POST['password'])){
	$error = Person::register($_POST['name'], $_POST['password']);
	if(isset($error)){
		var_dump($error);
		exit;
		header('Location: ../index.php?msg='.htmlspecialchars($error));
		exit;
	}
	header('Location: ../index.php?msg='.htmlspecialchars('registration successful'));
	exit;
}
	header('Location: ../index.php');
?>
