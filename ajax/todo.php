<?	
	require($_SERVER["DOCUMENT_ROOT"]."/todolist/core/general.php");
	header('Content-type: application/json');

	if (isset($_REQUEST['checkdchange'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$td = new Todo();
			$res = $td->getLastDChange($_POST["uid"]);

			echo json_encode($res);
		}
	}

	if (isset($_REQUEST['buildtodo'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		}
	}

	if (isset($_REQUEST['addtodo'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$td = new Todo();
			$res = $td->addTodo($_POST["title"], $_POST["uid"], $_POST["lskey"], $_POST["lsorder"]);

			echo json_encode($res);
		}
	}

	if (isset($_REQUEST['togglecompletetodo'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$td = new Todo();
			$res = $td->toggleCompleteTodo($_POST["lskey"], $_POST["compl"]);

			echo json_encode($res);
		}
	}

	if (isset($_REQUEST['changetodo'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$td = new Todo();
			$res = $td->changeTodo($_POST["lskey"], $_POST["title"]);

			echo json_encode($res);
		}
	}

	if (isset($_REQUEST['removetodo'])){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$td = new Todo();
			$res = $td->removeTodo($_POST["lskey"]);

			echo json_encode($res);
		}
	}
?>