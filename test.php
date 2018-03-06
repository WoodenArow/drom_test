<?
	require("../core/general.php");
	//header('Content-type: application/json');

			$user = new User();
			$res = $user->getUser('wooden', '14278249');

			session_start();
			unset($_SESSION['uid']);
			//$_SESSION["uid"] = $res[id];
			print_r($_SESSION);

			echo '<br>';

			print_r(count($res));

?>