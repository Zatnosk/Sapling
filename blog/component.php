<?php
namespace component\blogs;
require_once __DIR__."/blog.php";
require_once __DIR__."/../layout.php";

function newest($parent){
	$section = section($parent,'Nyeste blogs');
	section_nav($section, ['blog/new_blog.php'=>'Skriv ny blog']);
	ul($section, \Blog::list_newest_public(), true);
}

function by_contacts($parent){
	$section = section($parent,'Blogs af kontakter');
	ul($section, \Blog::list_by_contacts(), true);
}

function by_author($parent, $author_id){
	$author = \Person::get($author_id);
	$section = section($parent);
	$section->el('h1')->te('Blogs af ')->a(ROOTPATH."person/?id=$author->id",$author->name);
	if(\Person::get_current()->id == $author->id){
		section_nav($section, ['blog/new_blog.php'=>'Skriv ny blog']);
	}
	ul($section, \Blog::list_by_author($author_id), false);
}

function ul($section, $blogs, $show_author){
	if(empty($blogs)){
		$section->p('Ingen blogs at vise.');
	} else {
		$ul = $section->el('ul');
		foreach($blogs as $blog){
			$li = $ul->el('li');
			$li->a(ROOTPATH."blog/?id=$blog[id]",$blog['title']);
			if(isset($blog['visibility'])){
				switch($blog['visibility']){
					case 'contacts': $visibility = 'kun kontakter'; break;
					case 'public': $visibility = 'offentlig'; break;
					case 'invisible': $visibility = 'skjult';
				}
				$li->te(" <$visibility>");
			}
			$li->te(', ');
			$li->el('time',['class'=>'date','datetime'=>str_replace(' ','T',$blog['creation'])])->te($blog['creation']);
			
			if($show_author){
				$li->te(' af ');
				$li->a(ROOTPATH."person/?id={$blog['author']->id}",$blog['author']->name);
			}
		}
	}
}

?>