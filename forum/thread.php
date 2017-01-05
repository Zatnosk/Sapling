<?
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('thread','forum/');
require_once __DIR__."/../data.php";
require_once __DIR__."/../layout.php";
require_once __DIR__."/../person/person.php";
open_html();
menu();
?>
<main>
<a href="<?=ROOTPATH?>forum/">Forum</a>
<a href="<?=ROOTPATH?>forum/new_post.php?thread=<?=$_GET['thread']?>">New post</a>
<?
$id = isset($_GET['thread']) ? max(1,intval($_GET['thread'])) : 1;
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 0;
$thread = ForumData::thread($id,$page);
?><h1 class="thread_title"><?=$thread['title']?></h1><?
$people = [];
foreach($thread['posts'] as $post){
	if(!isset($people[$post['person_id']])) $people[$post['person_id']] = Person::get($post['person_id']);
?>
<div class="post">
<div class="author"><?=$people[$post['person_id']]->name?></div>
<time class="date" datetime="<?=str_replace(' ','T',$post['creation'])?>"><?=$post['creation']?></time>
<p>
<?=nl2br($post['content'])?>
</p>
</div>
<?}?>
<nav>
<?
function page($num){ ?><a href="<?=ROOTPATH?>forum/thread.php?thread=<?=$_GET['thread']?>&page=<?=$num?>"><?=$num?></a><? }
$ps = intval($thread['pages']);
$pn = intval($thread['page_num']);
if($pn > 3) page(1);
if($pn > 2) page($pn-2);
if($pn > 1) page($pn-1);
?><span class="-current"><?=$pn?></span><?
if($pn < $ps) page($pn+1);
if($pn < $ps-1) page($pn+2);
if($pn < $ps-2) page($ps);
?>
</nav>
<main>
<?
close_html();
?>