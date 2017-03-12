<?php
require_once __DIR__."/../data.php";

class Group {
	public static function has_person($group_id, $person_id){
		GroupData::has_person($group_id, $person_id);
	}
}
?>