<?php
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('id');
require_once __DIR__."/../layout.php";
require_once __DIR__."/person.php";
open_html();
menu();
$person = Person::get($_GET['id']);
?><main>
<h1><?=$person->name?></h1>
<?if($_GET['id'] == Person::get_current()->id){?>
<a href="<?=ROOTPATH?>person/edit_profile.php">Redigér profil</a>
<?}?>
<div class="usertext"><?
$profile = $person->profile;
if(!empty($profile)) echo nl2br(htmlspecialchars($profile));
else echo 'Tom profil';
?></div></main><?
close_html();
?>