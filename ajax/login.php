<?
	
	require("../core/general.php");
	header('Content-type: application/json');

	
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$user = new User();
			$res = $user->getUser($_POST["ulogin"], $_POST["upass"]);
			
			if (count($res)>0) {
				session_start();
				$_SESSION["uid"] = $res[id];
			}

			echo json_encode($res);
		}

?>