<?
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";

$form = section(body()->el('main'),'Ny blog')->form(ROOTPATH."blog/create_blog.php",'post');
$form->label('Titel:')->input('title');
$form->textarea('content');
$form->label('Synlighed')->select('visibility')->options(['public'=>'Offentlig','contacts'=>'Kun for kontakter','invisible'=>'Skjult']);

$form->submit('Gem');
?>