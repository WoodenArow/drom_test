<?
	require($_SERVER["DOCUMENT_ROOT"]."/todolist/core/general.php");
	//header('Content-type: application/json');

//			$user = new User();
//			$res = $user->getUser('wooden', '14278249');

//			session_start();
//			unset($_SESSION['uid']);
			//$_SESSION["uid"] = $res[id];
//			print_r($_SESSION);

//			echo '<br>';

//			$td = new Todo();
//			$res = $td->getLastDChange(1);


			$td = new Todo();
			$res = $td->toggleCompleteTodo('52863747-9d47-4c03-ab7b-4ef950e92718', '1');

			print_r($res);

?>