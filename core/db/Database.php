<?
	require("core/conf/db_conf.php");

	class Database {

		private $db;
		private $res;

/*
		public function connect() {
			$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			if ($this->db->connect_errno > 0) {
				die('Ошибка подключения к базе данных ['.$db->connect_error.']');
			}
		}
*/

		public function __construct() {
			$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			if ($this->db->connect_errno > 0) {
				die('Ошибка подключения к базе данных ['.$db->connect_error.']');
			}
		}

		public function select($sql) {
			//die('There was an error running the query [' . $db->error . ']');
			$this->res = $this->db->query($sql);
			if (!$this->res){
    			return false;
			} else {
				return $this->res->fetch_array();
			}
		}

		public function uid_query($sql) {
			
			$this->res = $this->db->query($sql);
			if ($this->res){
				if ($this->res->insert_id === 0) {
					return true;
				} else {
					return $this->res->insert_id;
				}
			} else {
				//die('There was an error running the query [' . $db->error . ']');
				return false;
			} 
		}

		function __destruct(){
			if ($this->db) $this->db->close();
		}
	}

?>