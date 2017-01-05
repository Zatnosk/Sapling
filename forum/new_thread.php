<?
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";
open_html();
menu();
?>
<main>
<form action='<?=ROOTPATH?>forum/create_thread.php' method='post'>
	<label>Title:<input type='text' name='title'></label>
	<textarea name='content'></textarea>
	<input type='submit'>
</form>
<main>
<?
close_html();
?>