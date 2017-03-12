<?
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('thread','forum/');
require_once __DIR__."/../layout.php";

$form = section(body()->el('main'),'Nyt indlæg')->form(ROOTPATH."forum/create_post.php?thread=$_GET[thread]",'post');
$form->textarea('content');
$form->submit('Gem');
?>