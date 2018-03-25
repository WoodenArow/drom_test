<?
	include_once($_SERVER["DOCUMENT_ROOT"]."/todolist/core/db/Database.php");

	class Todo {

		private $res;
		private $res2;

		function getTodoListByUid($uid) {
			$this->res = new Database();
			return  $this->res->select("
				SELECT tl.id, tl.title, tl.completed, tlu.lskey, tlu.lsorder 
				FROM td_list_user tlu 
				LEFT JOIN td_list tl ON tl.id = tlu.tdid 
				WHERE tlu.uid = '$uid' AND !tlu.deleted
				ORDER BY tlu.lsorder;
			");
		}

		function getLastDChange($uid) {
			$this->res = new Database();
			return $this->res->select("SELECT dchange FROM td_list_dchange WHERE uid = '$uid';");
		}

		function addTodo($title, $uid, $lskey, $lsorder){
			$this->res = new Database();
			$tid = $this->res->uid_query("INSERT INTO td_list(title) VALUES ('$title');");

			$this->res2 = new Database();
			$tlu = $this->res2->uid_query("INSERT INTO td_list_user(uid, tdid, lskey, lsorder) VALUES ('$uid','$tid','$lskey','$lsorder');");

			return $tid;
		}

		function toggleCompleteTodo($lskey, $compl){
			$this->res = new Database();
			$tid = $this->res->select("SELECT tdid FROM td_list_user WHERE lskey='$lskey';");

			$this->res2 = new Database();
			$r = $this->res2->uid_query("UPDATE td_list SET completed = '$compl' WHERE id = '$tid[tdid]'");

			return $tid;
		}

		function changeTodo($lskey, $title){
			$this->res = new Database();
			$tid = $this->res->select("SELECT tdid FROM td_list_user WHERE lskey='$lskey';");

			$this->res2 = new Database();
			$r = $this->res2->uid_query("UPDATE td_list SET title = '$title' WHERE id = '$tid[tdid]'");

			return $tid;
		}

		function removeTodo($lskey){
			$this->res = new Database();
			return $this->res->uid_query("UPDATE td_list_user SET deleted = true WHERE lskey='$lskey';");
		}

		function setLastDChange(){

		}
	}
?>