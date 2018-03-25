<?
	include_once($_SERVER["DOCUMENT_ROOT"]."/todolist/core/db/Database.php");

	class User {

		private $res;

		function getUser($ulogin, $upass) {
			$this->res = new Database();
			return  $this->res->select("SELECT id, uname FROM td_users WHERE ulogin = '$ulogin' AND upass = '$upass';");
		}

		function addUser($uname, $ulogin, $upass, $uemail) {
			$this->res = new Database();
			return $this->res->uid_query("INSERT INTO td_users(uname, ulogin, upass, uemail) VALUES ('$uname', '$ulogin', '$upass', '$uemail');");
		}
		
	}
?>