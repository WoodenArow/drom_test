<?
	require($_SERVER["DOCUMENT_ROOT"]."/todolist/core/general.php");
	header('Content-type: application/json');

	if (isset($_REQUEST[registration])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$user = new User();
			$res = $user->addUser($_POST["uname"], $_POST["ulogin"], $_POST["upass"], $_POST["uemail"]);
			echo json_encode($res);
		}
	}

?>