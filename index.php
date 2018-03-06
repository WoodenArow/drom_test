<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Template • TodoMVC</title>
		<link rel="stylesheet" href="css/base.css">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/modal.css">
		<!-- CSS overrides - remove if you don't need it -->
		<link rel="stylesheet" href="css/app.css">
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
	</head>
	<body>
		<?
			session_start();

print_r($_SESSION);

			if (!isset($_SESSION["uid"])) {
				$_SESSION["anon"] = 1;
			} else {
				unset($_SESSION['anon']);
			}

		?>
		<div id="modal" class="popupContainer" style="display:none;">
				<header class="popupHeader">
						<span class="header_title">Авторизация</span>
						<span class="modal_close"><i class="fa fa-times"></i></span>
				</header>

				<section class="popupBody">
						<!-- Username & Password Login form -->
						<div class="user_login">
								<form id="auth">
										<label>Логин</label>
										<input type="text" id="ulogin" name="ulogin" />
										<br />

										<label>Пароль</label>
										<input type="password" id="upass" name="upass" />
										<br />

										<div class="action_btns">
												<div><a href="javascript:void(0);" id="log_in" class="btn btn_red">Авторизоваться</a></div>
										</div>
								</form>
								<br />
										
								<div class="action_btns">
									<div><a id="register_form" href="javascript:void(0);" class="btn ">Регистрация</a></div>
								</div>
						</div>

						<!-- Register Form -->
						<div class="user_register">
								<form>
										<label>Имя</label>
										<input type="text" id="uname" name="uname"/>
										<br />
										<label>Логин</label>
										<input type="text" id="ulogin" name="ulogin"/>
										<br />
										<label>Пароль</label>
										<input type="password" id="upass" name="upass"/>
										<br />
										<label>E-mail</label>
										<input type="text" id="uemail" name="uemail"/>
										<br />
										<div class="action_btns">
												<div class="one_half"><a href="javascript:void(0);" id="back" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Назад</a></div>
												<div class="one_half last"><a href="javascript:void(0);" id="reg" class="btn btn_red">Регистрация</a></div>
										</div>
								</form>
						</div>
				</section>
		</div>
		<div class="auth">
			<?if (isset($_SESSION["anon"])):?>
				Вы не авторизованны. Данные будут храниться в localstorage данного браузера. Доступ к данным на другом устройстве будет не доступен.
				<br>
				<a href="#modal" id="modal_trigger">Авторизоваться?</a>
			<?else:?>
				Вы авторизованны как ...
				<br>
				<a href="javascript:void(0);">Выйти?</a>
			<?endif;?>
		</div>		
		<section class="todoapp">
			<header class="header">
				<h1>todos</h1>
				<input class="new-todo" placeholder="What needs to be done?" autofocus>
			</header>
			<!-- This section should be hidden by default and shown when there are todos -->
			<section class="main" style="display: none;">
				<input id="toggle-all" class="toggle-all" type="checkbox">
				<label for="toggle-all">Mark all as complete</label>
				<ul class="todo-list"></ul>
			</section>
			<!-- This footer should hidden by default and shown when there are todos -->
			<footer class="footer" style="display: none;">
				<!-- This should be `0 items left` by default -->
				<span class="todo-count"><strong>0</strong> item left</span>
				<!-- Remove this if you don't implement routing -->
				<ul class="filters">
					<li>
						<a class="selected" href="#/">All</a>
					</li>
					<li>
						<a href="#/active">Active</a>
					</li>
					<li>
						<a href="#/completed">Completed</a>
					</li>
				</ul>
				<!-- Hidden if no completed items are left ↓ 
				<button class="clear-completed">Clear completed</button>
				-->
			</footer>
		</section>
		<footer class="info">
			<p>Double-click to edit a todo</p>
			<!-- Change this out with your name and url ↓ -->
			<p>Created by <a href="https://github.com/WoodenArow/">Wooden</a></p>
			<p>Part of <a href="http://todomvc.com">TodoMVC</a></p>
		</footer>

		<?if ($_SESSION["anon"] == 1):?> 
		<script type="text/javascript">
			$(function(){
				console.log('anon detected - maybe');
			});
		</script>
		<?endif;?>
		<script type="text/javascript">
			$(function(){
				$("#modal_trigger").leanModal();
				$(document).on('click', 'a#register_form', function(){
					$('div.user_login').css('display','none');
					$('div.user_register').css('display','block');
				});
				$(document).on('click', 'a#back', function(){
					$('div.user_login').css('display','block');
					$('div.user_register').css('display','none');
				});

				$(document).on('click', 'a#log_in', function(){
					$.ajax({
						method: "POST",
						url: "ajax/login.php?login",
						dataType: 'json',
						data: {
							ulogin: $('form#auth input#ulogin').val(),
							upass: $('form#auth input#upass').val(),
						},
						success: function(data){
							if (!jQuery.isEmptyObject(data)){
								console.log(data);

							} else {
								console.log(data);
								alert('Пользователя с такими данными не существует.\n\rПроверьте введенные данные.');
							}
						},
						error: function(data){
							alert('Произошла ошибка при обработке данных.\n\rПовторите попытку позже.');
						}

					});
				});


			});
		</script>
		<script type="text/javascript" src="js/general.js"></script>>
	</body>
</html>
