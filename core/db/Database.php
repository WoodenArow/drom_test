<?
	require("core/conf/db_conf.php");

	class Database {

		private $con = false;

		public function connect() {
			$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			if ($db->connect_errno > 0) {
				die('Ошибка подключения к базе данных ['.$db->connect_error.']');
			} else {
				$this-con = true;
				return true;
			}
		}

	}

?>