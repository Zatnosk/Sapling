<?
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../data.php";
require_once __DIR__."/../layout.php";

$section = section(body()->el('main'));
$section->a(ROOTPATH.'forum/new_thread.php','Ny tråd');
$ul = $section->el('ul');

$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$threads = ForumData::index($page);
foreach($threads as $thread){
	$li = $ul->el('li');
	$li->a(ROOTPATH."forum/thread.php?thread=$thread[id]",$thread['title']);
}
?>