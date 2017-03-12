<?php
require_once __DIR__."/config.php";
class Data {
	protected static $mysqli;

	protected static function query($sql, ...$values){
		if(!isset(self::$mysqli)){
			self::$mysqli = new mysqli('localhost', MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		}

		$stmt = self::$mysqli->prepare($sql);
		//if(self::$mysqli->error) var_dump($sql,self::$mysqli->error);
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
		//if(self::$mysqli->error) var_dump(self::$mysqli->error);
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
		Data::query("INSERT INTO people (name,password_hash,profile) VALUES (?,?,'')",$name,$hash);
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

	public static function get_contact_group($id){	
		$result = Data::query("SELECT contact_group_id FROM people WHERE id=?",$id);
		if($result && $result->num_rows) return $result->fetch_object()->contact_group_id;
		else return null;
	}

	public static function set_contact_group($person_id, $group_id){
		Data::query("UPDATE people SET contact_group_id = ? WHERE id=?",$group_id,$person_id);
	}

	public static function is_contact_of($idA, $idB){
		$sql = "SELECT 1
		        FROM people
		        INNER JOIN group_membership ON group_membership.group_id = people.contact_group_id
		        WHERE people.id = ? AND group_membership.person_id = ?";
		$result = Data::query($sql, $idA, $idB);
		return $result && $result->num_rows;
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

class GroupData extends Data {
	public static function has_person($group_id, $person_id){
		$sql = "SELECT 1 FROM group_membership WHERE group_id=? AND person_id=?";
		$result = static::query($sql,$group_id,$person_id);
		return $result->num_rows > 0;
	}

	public static function create_group($name, $is_public = false){
		$sql = "INSERT INTO group_metadata (name,visibility) VALUES (?,?)";
		static::query($sql, $name, $is_public ? 'public' : 'private');
		return static::$mysqli->insert_id;
	}

	public static function add_person($group_id, $person_id){
		$sql = "INSERT IGNORE INTO group_membership (group_id,person_id) VALUES (?,?)";
		static::query($sql,$group_id,$person_id);
	}

	public static function remove_person($group_id, $person_id){
		$sql = "DELETE FROM group_membership WHERE group_id=? AND person_id=?";
		static::query($sql,$group_id,$person_id);
	}

	public static function get_members($group_id){
		$sql = "SELECT person_id FROM group_membership WHERE group_id=?";
		return static::query($sql,$group_id);
	}
}

class BlogData extends Data {
	public static function write($author_id, $title, $content, $visibility){
		$sql = "INSERT INTO blog (author,title,content,visible_to) VALUES (?,?,?,?)";
		static::query($sql, $author_id, $title, $content, $visibility);
		return static::$mysqli->insert_id;
	}

	public static function set_visibility($author_id, $blog_id, $new_visibility){
		$sql = "UPDATE blog SET visible_to = ? WHERE id = ? AND author = ?";
		static::query($sql, $new_visibility, $blog_id, $author_id);
	}

	public static function get($blog_id, $reader_id){
		$sql = "SELECT id,author,creation,title,content,
		        IF(visible_to IS NULL,'public',IF(visible_to = 0,'invisible','contacts')) as visibility
		        FROM blog
		        LEFT JOIN group_membership as gm
		        	ON gm.group_id = blog.visible_to
		        	AND gm.person_id = ?
		        WHERE blog.id = ?
		        	AND (gm.person_id IS NOT NULL OR blog.visible_to IS NULL OR author = ?)
		        LIMIT 1";
		$result = static::query($sql, $reader_id, $blog_id, $reader_id);
		if($result->num_rows > 0){
			return $result->fetch_object();
		} else {
			return null;
		}
	}

	public static function list_by_author($author_id, $reader_id){
		if($author_id == $reader_id){
			$sql = "SELECT blog.id,creation,title,
			        IF(visible_to IS NULL,'public',IF(visible_to = 0,'invisible','contacts')) as visibility
			        FROM blog
			        WHERE author = ?
			        ORDER BY creation DESC";
			return static::query($sql, $reader_id);
		} else {
			$sql = "SELECT id,creation,title,
			        IF(visible_to IS NULL,'public',IF(visible_to = 0,'invisible','contacts')) as visibility
			        FROM blog
			        LEFT JOIN group_membership AS gm
			        	ON gm.group_id = blog.visible_to
			        	AND gm.person_id = ?
			        WHERE author = ?
			        	AND (gm.person_id IS NOT NULL OR blog.visible_to IS NULL)
			        ORDER BY creation DESC";
			return static::query($sql, $reader_id, $author_id);
		}
	}

	public static function list_by_contacts($person_id){
		$sql = "SELECT blog.id,blog.creation,blog.title,blog.author,
		        IF(visible_to IS NULL,'public',IF(visible_to = 0,'invisible','contacts')) as visibility
		        FROM blog
		        LEFT JOIN group_membership AS gm
		        	ON gm.group_id = blog.visible_to
		        	AND gm.person_id = ?
		        WHERE author IN
		        		(SELECT gm.person_id
		        		FROM people
		        		INNER JOIN group_membership as gm
		        			ON gm.group_id = people.contact_group_id
		        		WHERE people.id = ?)
		        	AND (gm.person_id IS NOT NULL OR blog.visible_to IS NULL)
		        ORDER BY creation DESC
		        LIMIT 10";
		return static::query($sql, $person_id, $person_id);
	}

	public static function list_newest_public(){
		$sql = "SELECT id,author,creation,title
		        FROM blog
		        WHERE visible_to IS NULL
		        ORDER BY creation DESC
		        LIMIT 10";
		return static::query($sql);
	}
}
?>
