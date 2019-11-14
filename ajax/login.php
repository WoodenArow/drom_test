<?
	
	require($_SERVER["DOCUMENT_ROOT"]."/todolist/core/general.php");
	header('Content-type: application/json');

	if (isset($_REQUEST['login'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$user = new User();
			$res = $user->getUser($_POST["ulogin"], $_POST["upass"]);

			if (count($res)>0) {
				session_start();
				unset($_SESSION['anon']);
				$_SESSION["uid"] = $res[id];
				$_SESSION["uname"] = $res[uname];
			}

			echo json_encode($res);
		}
	}
	if (isset($_REQUEST['logout'])){
		session_start();
		unset($_SESSION['uid']);
		unset($_SESSION['uname']);
		$_SESSION["anon"] = 1;

		echo json_encode('{"logout":"true"}');
	}
	if (isset($_REQUEST['reg'])){
			$user = new User();
			$res = $user->addUser($_POST["uname"], $_POST["ulogin"], $_POST["upass"], $_POST["uemail"]);

			if (count($res)>0) {
				session_start();
				unset($_SESSION['anon']);
				$_SESSION["uid"] = $res[id];
				$_SESSION["uname"] = $_POST["uname"];
			}

			echo json_encode($res);

	}
	

?>