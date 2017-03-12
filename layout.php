<?php
require_once __DIR__."/config.php";
require_once __DIR__."/person/person.php";
require_once __DIR__."/heal-document/lib/HealHTML.php";

function body($use_menu = true){
	$doc = new HealHTML();
	list($head,$body) = $doc->html('zapling.dk');
	$head->css(ROOTPATH.'style.css');

	if($use_menu){
		$person = Person::get_current();
		$nav = $body->el('nav');
		$nav->a(ROOTPATH)->el('h1')->te('Forside');
		$nav->a(ROOTPATH.'person/?id='.$person->id,$person->name);
		$nav->a(ROOTPATH.'forum/','Forum');
		$nav->a(ROOTPATH.'blog/','Mine Blogs');
		$nav->a(ROOTPATH.'person/contact_list.php','Kontakter');
		$nav->a(ROOTPATH.'person/logout.php','Log ud');
	}

	register_shutdown_function(function()use($doc){echo $doc;});
	return $body;
}

function section($main, $title = null){
	$section = $main->el('section');
	if(isset($title)) $section->el('h1')->te($title);
	return $section;
}

function section_nav($section, $links){
	foreach($links as $href => $text){
		$section->a(ROOTPATH.$href,$text);
		$section->te(' ');
	}
}

function pagination($parent, $source, $path){
	$nav = $parent->el('nav');

	$ps = intval($source['pages']);
	$pn = intval($source['page_num']);
	if($pn > 3) pagelink($nav, $path, 1);
	if($pn > 2) pagelink($nav, $path, $pn-2);
	if($pn > 1) pagelink($nav, $path, $pn-1);
	$nav->el('span',['class'=>'-current'])->te($pn);
	if($pn < $ps) pagelink($nav, $path, $pn+1);
	if($pn < $ps-1) pagelink($nav, $path, $pn+2);
	if($pn < $ps-2) pagelink($nav, $path, $ps);
}
function pagelink($parent,$path,$num){
	$parent->a(ROOTPATH.$path."&page=$num",$num);
}
?>
