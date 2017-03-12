<?php
require_once __DIR__."/../data.php";
require_once __DIR__."/../person/person.php";
require_once __DIR__."/../group/group.php";

class Blog {
	public static function get($id){
		$data = BlogData::get($id, Person::get_current()->id);
		if(isset($data)){
			$data->author = Person::get($data->author);
			return $data;
		}
	}

	public static function list_by_author($person_id){
		$blogs = BlogData::list_by_author($person_id, Person::get_current()->id);
		if(empty($blogs)) return [];
		$bloglist = [];
		foreach($blogs as $blog){
			$bloglist[] = $blog;
		}
		return $bloglist;
	}

	public static function list_by_contacts(){
		$blogs = BlogData::list_by_contacts(Person::get_current()->id);
		if(empty($blogs)) return [];
		$bloglist = [];
		foreach($blogs as $blog){
			$blog['author'] = Person::get($blog['author']);
			$bloglist[] = $blog;
		}
		return $bloglist;
	}

	public static function list_newest_public(){
		$blogs = BlogData::list_newest_public();
		if(empty($blogs)) return [];
		$bloglist = [];
		foreach($blogs as $blog){
			$blog['author'] = Person::get($blog['author']);
			$bloglist[] = $blog;
		}
		return $bloglist;
	}

	public static function write($title, $content, $visibility = 'invisible'){
		switch($visibility){
			case 'contacts': $visible_to = Person::get_contact_list_id(true); break;
			case 'public': $visible_to = null; break;
			default: $visible_to = 0;
		}
		return BlogData::write(Person::get_current()->id,$title,$content,$visible_to);
	}

	public static function set_visibility($blog_id, $new_visibility){
		switch($new_visibility){
			case 'contacts': $visible_to = Person::get_contact_list_id(true); break;
			case 'public': $visible_to = null; break;
			default: $visible_to = 0;
		}
		return BlogData::set_visibility(Person::get_current()->id,$blog_id, $visible_to);
	}
}