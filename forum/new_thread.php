<?
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";

$form = section(body()->el('main'),'Ny tråd')->form(ROOTPATH."forum/create_thread.php",'post');
$form->label('Titel:')->input('title');
$form->textarea('content');
$form->submit('Gem');
?>