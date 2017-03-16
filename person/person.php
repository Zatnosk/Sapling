<?php
session_start();
require_once __DIR__."/../data.php";

class Person {
	private static $session_prefix = 'demo';
	private static $current_person;

	public static function login($name, $password){
		list($id, $hash, $error) = PersonData::get_id_and_hash($name);
		if(!isset($error) && password_verify($password,$hash)){
			$person = new Person($id);
			$person->write_session();
			return $person;
		}
		return false;
	}

	public static function is_logged_in(){
		if(isset(self::$current_person)) return true;
		$person = self::read_session();
		return isset($person);
	}

	public static function is_moderator(){
		$person = self::get_current();
		return $person->is_moderator;
	}

	public static function has_on_contact_list($other_id){
		$person = self::get_current();
		return PersonData::is_contact_of($person->id, $other_id);
	}

	public static function get_contact_list_id($create_if_not_exists = false){
		$person = self::get_current();
		$group_id = PersonData::get_contact_group($person->id);
		if($create_if_not_exists && !isset($group_id)){
			$group_name = $person->name.'s kontakter';
			$group_id = GroupData::create_group($group_name);
			PersonData::set_contact_group($person->id, $group_id);
		}
		return $group_id;
	}

	public static function get_contact_list(){
		$contact_list = [];
		$person = self::get_current();
		$group_id = self::get_contact_list_id();
		if(isset($group_id)){
			$contacts = GroupData::get_members($group_id);
			foreach($contacts as $contact){
				$contact_list[] = Person::get($contact['person_id']);
			}
		}
		return $contact_list;
	}

	public static function get_current(){
		if(isset(self::$current_person)) return self::$current_person;
		$person = self::read_session();
		if(isset($person)) return $person;
	}

	public static function register($name, $password){
		$id = PersonData::register($name,$password);
		if($id){
			$person = new Person($id);
			$person->write_session();
			return;
		}
		return 'registration failed';
	}

	public static function get($id){
		return new Person($id);
	}

	public static function exists($id){
		return isset(self::get($id)->name);
	}

	private static function read_session(){
		if(isset(self::$current_person)) $person = $current_person;
		else {
			$key = self::$session_prefix.'user_id';
			if(isset($_SESSION[$key])){
				$id = $_SESSION[$key];
				$person = new Person($id);
				$current_person = $person;
			} else {
				$person = null;
			}
		}
		return $person;
	}



	private $id;
	private $data;
	private $profile;

	public function logout(){
		$this->unwrite_session();
	}

	public function write_profile($content){
		PersonData::write_profile($this->id, $content);
	}

	private function write_session(){
		$_SESSION[self::$session_prefix.'user_id'] = $this->id;
	}

	private function unwrite_session(){
		$_SESSION[self::$session_prefix.'user_id'] = null;
	}

	private function __construct($user_id){
		$this->id = $user_id;
	}

	public function __isset($name){
		if(!isset($this->data)){
			$this->data = PersonData::load($this->id);
		}
		return isset($this->data[$name]);
	}

	public function __get($name){
		if($name == 'id') return $this->id;
		if(!isset($this->data)){
			$this->data = PersonData::load($this->id);
		}
		if(isset($this->data[$name])) return $this->data[$name];
	}

	
}
?>
