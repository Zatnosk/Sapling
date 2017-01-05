<?php
require_once __DIR__."/layout.php";
require_once __DIR__."/person/person.php";
open_html();
if(Person::is_logged_in()){
	menu();
?>
<main>Welcome!
<?
$posts = ForumData::latest();
$people = [];
foreach($posts as $post){
	if(!isset($people[$post['person_id']])) $people[$post['person_id']] = Person::get($post['person_id']);
?>
<div class="post">
<time class="date" datetime="<?=str_replace(' ','T',$post['creation'])?>"><?=$post['creation']?></time>
<p>
<a href="<?=ROOTPATH?>forum/thread.php?thread=<?=$post['thread_id']?>"><?=$people[$post['person_id']]->name?> posted in "<?=$post['thread_title']?>".</a>
</p>
</div>
<?}?>
</main>
<?} else {?>
<form action='person/login.php' method='post'>
	<label>Name: <input type='text' name='name'></label>
	<label>Password: <input type='password' name='password'></label>
	<input type='submit' value='Login'>
	<input type='submit' value='Sign Up' formaction='person/signup.php'>
</form>
<?}
close_html();
?>