<?
require_once __DIR__."/../access.php";
Access::logged_in();
Access::require_get('thread','forum/');
require_once __DIR__."/../data.php";
require_once __DIR__."/../layout.php";
require_once __DIR__."/../person/person.php";

$id = isset($_GET['thread']) ? max(1,intval($_GET['thread'])) : 1;
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 0;
$thread = ForumData::thread($id,$page);

$current_person = Person::get_current();
$people = [];

$section = section(body()->el('main'));
section_nav($section, [
	'forum/'=>'Forum',
	"forum/new_post.php?thread=$_GET[thread]"=>'Nyt indlæg'
]);

$section->el('h1',['class'=>'thread_title'])->te($thread['title']);

foreach($thread['posts'] as $post){
	if(!isset($people[$post['person_id']])) $people[$post['person_id']] = Person::get($post['person_id']);
	$div = $section->el('div',['class'=>'post']);
	$div->a(ROOTPATH."person/?id=$post[person_id]",$people[$post['person_id']]->name)->at('class','author');
	$div->el('time',['class'=>'date','datetime'=>str_replace(' ','T',$post['creation'])])->te($post['creation']);
	$div->el('p')->te(htmlspecialchars($post['content']),HEAL_TEXT_NL2BR);
	if($current_person->is_moderator){
		$div->a(ROOTPATH."forum/remove_post.php?post=$post[id]&thread=$thread[id]",'Fjern indlæg');
	}
}

pagination($section,$thread,"forum/thread.php?thread=$_GET[thread]");
?>