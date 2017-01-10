<?php
require_once __DIR__."/config.php";
class Data {
	protected static $mysqli;

	protected static function query($sql, ...$values){
		if(!isset(self::$mysqli)){
			self::$mysqli = new mysqli('localhost', MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		}

		$stmt = self::$mysqli->prepare($sql);
		if(!empty($values)){
			$types = '';
			foreach($values as $value){
				if(is_int($value)) $types .= 'i';
				elseif(is_float($value)) $types .= 'd';
				else $types .= 's';
			}
			$stmt->bind_param($types, ...$values);
		}
		echo self::$mysqli->error;
		$stmt->execute();
		if(self::$mysqli->error) var_dump(self::$mysqli->error);
		if(self::$mysqli->errno) return false;
		$result = $stmt->get_result();
		return $result;
	}
}

class PersonData extends Data {
	private static $hash_cost = 10;

	public static function get_id_and_hash($name){
		$result = static::query("SELECT id, password_hash as hash FROM people WHERE name=? LIMIT 1",$name);
		if($result && $result->num_rows){
			return $result->fetch_row()+[2=>null];
		} else {
			return [null,null,'unknown person'];
		}
	}

	public static function register($name,$password){
		$hash = password_hash($password, PASSWORD_DEFAULT, ['cost'=>self::$hash_cost]);
		Data::query("INSERT INTO people (name,password_hash) VALUES (?,?)",$name,$hash);
		if(!static::$mysqli->errno) return static::$mysqli->insert_id;
	}

	public static function load($id){
		$result = Data::query("SELECT name, profile, is_moderator, is_administrator FROM people WHERE id=? LIMIT 1",$id);
		if($result && $result->num_rows) return $result->fetch_assoc();
		return [];
	}

	public static function write_profile($id, $profile){
		Data::query("UPDATE people SET profile=? WHERE id=?",$profile,$id);
	}
}

class ForumData extends Data {
	private static $posts_per_page = 10;
	private static $threads_per_page = 20;

	public static function index($page = 1){
		$limit = static::$threads_per_page;
		if($page > 1) $limit .= ' OFFSET '.(($page-1)*self::$threads_per_page);
		$sql = "SELECT * FROM forum_thread LIMIT $limit";
		$threads_result = static::query($sql);
		$threads = [];
		foreach($threads_result as $thread){
			$threads[] = $thread;
		}
		return $threads;
	}

	public static function thread($id, $page = 0){
		$thread_result = static::query("SELECT * FROM forum_thread WHERE id=? LIMIT 1", $id);
		if(!$thread_result || !$thread_result->num_rows) return;
		$thread = $thread_result->fetch_assoc();

		$order = $page == 0 ? 'DESC' : 'ASC';
		$limit = static::$posts_per_page;
		if($page > 1) $limit .= ' OFFSET '.(($page-1)*self::$posts_per_page);
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM forum_post WHERE thread_id=? ORDER BY creation $order LIMIT $limit";
		$posts_result = static::query($sql, $id);

		$count_result = static::query("SELECT FOUND_ROWS() AS posts");

		$count = $count_result->fetch_object()->posts;
		$thread['pages']=ceil($count / self::$posts_per_page);
		$thread['page_num']=$page==0?$thread['pages']:$page;
		$thread['posts']=[];
		foreach($posts_result as $post){
			if($post['removed']) $post['content'] = "[This post has been removed by a moderator]";
			$thread['posts'][]=$post;
		}
		if($page === 0){
			$mod = $count % self::$posts_per_page;
			if($mod == 0) $mod = static::$posts_per_page;
			$thread['posts'] = array_reverse(array_slice($thread['posts'],0,$mod));
		}
		return $thread;
	}

	public static function latest($count = 10){
		$sql = "SELECT a.*, forum_thread.title as thread_title
		        FROM forum_thread
		        LEFT JOIN forum_post as a
		        	ON a.thread_id = forum_thread.id
		        		AND a.removed = 0
		        LEFT JOIN forum_post as b
		        	ON b.thread_id = forum_thread.id
		        		AND a.creation < b.creation
		        		AND b.removed = 0
		        WHERE b.id IS NULL
		        ORDER BY a.creation DESC
		        LIMIT $count";
		$result = static::query($sql);
		$posts = [];
		foreach($result as $post){
			$posts[]=$post;
		}
		return $posts;
	}

	public static function write_thread($title){
		static::query("INSERT INTO forum_thread SET title=?",$title);
		if(!static::$mysqli->errno && static::$mysqli->insert_id) return static::$mysqli->insert_id;
	}

	public static function write_post($thread, $person, $content){
		static::query("INSERT INTO forum_post (thread_id,person_id,content) VALUES (?,?,?)",$thread, $person->id, $content);
	}

	public static function remove_post($post, $person){
		if($person->is_moderator){
			static::query("UPDATE forum_post SET removed=1,removed_by=? WHERE id = ?", $person->id, $post);
		}
	}
}
?>
