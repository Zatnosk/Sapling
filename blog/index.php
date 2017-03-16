<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";
require_once __DIR__."/blog.php";
require_once __DIR__."/component.php";

if(isset($_GET['id'])){
	$blog = Blog::get($_GET['id']);
	$section = section(body()->el('main'));
	if(isset($blog)){
		$div = $section->el('div',['class'=>'blog post']);
		$div->a(ROOTPATH."blog/?author={$blog->author->id}",$blog->author->name)->at('class','author');
		$div->el('time',['class'=>'date','datetime'=>str_replace(' ','T',$blog->creation)])->te($blog->creation);
		$vis = $div->el('div',['class'=>'visibility']);
		switch($blog->visibility){
			case 'invisible': $vis->te('<skjult>'); break;
			case 'contacts': $vis->te('<kun for kontakter>'); break;
			case 'public': $vis->te('<offentlig>'); break;
		}
		$div->el('h1')->te($blog->title);
		$div->el('p')->te($blog->content,HEAL_TEXT_NL2BR);
		$actionbar = $div->el('div',['class'=>'actions']);
		$url = ROOTPATH."blog/set_visibility.php?id=$blog->id&vis=";
		if($blog->visibility != 'public') $actionbar->a($url.'public','[vis til alle]');
		if($blog->visibility != 'contacts') $actionbar->a($url.'contacts','[vis kun til kontakter]');
		if($blog->visibility != 'invisible') $actionbar->a($url.'invisible','[skjul]');
	} else {
		$section->p('Blog ikke fundet.');
	}
	
} else {
	if(isset($_GET['author']) && Person::exists($_GET['author'])){
		$author = $_GET['author'];
	} else {
		$author = Person::get_current()->id;
	}

	$main = body()->el('main');
	\component\blogs\by_author($main, $author);
}
