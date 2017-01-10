<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";
open_html();
menu();
$person = Person::get_current();
?>
<main>
<p>Redigér profil</p>
<form action='<?=ROOTPATH?>person/write_profile.php' method='post'>
	<textarea name='content'><?=$person->profile?></textarea>
	<input type='submit' value='Gem'>
</form>
</main>
<?
close_html();
?>