<?
	require("core/db/Database.php");

	class User {

		private $res;

		function getUser() {
			$this->res = new Database();
			return  $this->res->select('Select true as chk;');
		}
		
	}
?>