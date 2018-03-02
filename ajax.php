<?
require("core/general.php");
header('Content-type: application/json');

if (isset($_REQUEST[login])) {
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$user = new User();
		$res = $user->getUser($_POST["ulogin"], $_POST["upass"]);
		echo json_encode($res);
	}
}

?>