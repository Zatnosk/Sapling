<?php
require_once __DIR__."/config.php";
require_once __DIR__."/person/person.php";
function menu(){
	$person = Person::get_current();
?>
<nav>
<a href="<?=ROOTPATH?>"><h1>Sapling</h1></a>
<a href="<?=ROOTPATH?>person/?id=<?=$person->id?>"><?=$person->name?></a>
<a href="<?=ROOTPATH?>forum/">Forum</a>
<a href="<?=ROOTPATH?>person/logout.php">Log out</a>
</nav>
<?}

function open_html(){?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sapling demo</title>
	<link rel="stylesheet" type="text/css" href="<?=ROOTPATH?>style.css">
</head>
<body>
<?}

function close_html(){?>
</body>
</html>
<?}?>