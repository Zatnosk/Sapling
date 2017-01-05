<?
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/../layout.php";
open_html();
menu();
?>
<main>
<a href="<?=ROOTPATH?>forum/new_thread.php">New thread</a>
<ul>
<?
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$threads = ForumData::index($page);
foreach($threads as $thread){
?>
<li>
<a href="thread.php?thread=<?=$thread['id']?>"><?=$thread['title']?></a>
</li>
<?
}
?>
</ul>
<main>
<?
close_html();
?>