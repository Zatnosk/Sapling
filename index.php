<?php
require_once __DIR__."/layout.php";
require_once __DIR__."/person/person.php";
require_once __DIR__."/blog/blog.php";
require_once __DIR__."/blog/component.php";
$logged_in = Person::is_logged_in();
$body = body($logged_in);
if($logged_in){
	$main = $body->el('main');

	$forum = section($main,'Forum');
	$forum->a(ROOTPATH.'forum/new_thread.php','Start ny tråd');
	$posts = ForumData::latest();
	$people = [];
	foreach($posts as $post){
		if(!isset($people[$post['person_id']])) $people[$post['person_id']] = Person::get($post['person_id']);

		$div = $forum->el('div',['class'=>'post']);
		$div->el('time',['class'=>'date','datetime'=>str_replace(' ','T',$post['creation'])])->te($post['creation']);
		$p = $div->el('p');
		$linktext = $people[$post['person_id']]->name . " posted in $post[thread_title].";
		$p->a(ROOTPATH.'forum/thread.php?thread='.$post['thread_id'],$linktext);
	}


	\component\blogs\newest($main);
	\component\blogs\by_contacts($main);
} else {
	$form = $body->form('person/login.php','post');
	$form->label('Name: ')->input('name');
	$form->label('Password: ')->password('password');
	$form->submit('Login');
	$form->submit('Sign Up')->at('formaction','person/signup.php');
}
?>
