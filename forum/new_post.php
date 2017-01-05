<?
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('thread','forum/');
require_once __DIR__."/../layout.php";
open_html();
menu();
?>
<main>
<form action='<?=ROOTPATH?>forum/create_post.php?thread=<?=$_GET['thread']?>' method='post'>
	<textarea name='content'></textarea>
	<input type='submit'>
</form>
<main>
<?
close_html();
?>