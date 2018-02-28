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
			require 'core/general.php';
			session_start();

			if (!isset($_SESSION[uid])) {
				$_SESSION['anon'] = 1;
			} else {
				session_unregister('anon');
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
								<form>
										<label>Логин</label>
										<input type="text" />
										<br />

										<label>Пароль</label>
										<input type="password" />
										<br />

										<div class="action_btns">
												<div><a href="#" class="btn btn_red">Авторизоваться</a></div>
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
										<input type="text" />
										<br />

										<label>Email Address</label>
										<input type="email" />
										<br />

										<label>Password</label>
										<input type="password" />
										<br />

										<div class="checkbox">
												<input id="send_updates" type="checkbox" />
												<label for="send_updates">Send me occasional email updates</label>
										</div>

										<div class="action_btns">
												<div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
												<div class="one_half last"><a href="#" class="btn btn_red">Register</a></div>
										</div>
								</form>
						</div>
				</section>
		</div>
		<div class="auth">
			<?if (isset($_SESSION[anon])):?>
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

		<?if ($_SESSION[anon] == 1):?> 
		<script type="text/javascript">
			$(function(){
				console.log('anon detected');
			});
		</script>
		<?endif;?>
		<script type="text/javascript">
			
			(function ($) {
			    $.getUUID = (function() {
			        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
			            var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
			            return v.toString(16);
			        });
			        return uuid;
			    });
			})(jQuery);

			var ls = localStorage;

			$(function(){
				if (localStorage) {
				    console.log('LocalStorage enabled!');
				    if (localStorage.getItem('tl-cnt') === null) {
						localStorage.setItem('tl-cnt', 0);
				    } else {
				    	console.log('LocalStorage not empty');
				    	ls_build();
				    }
				}
				
				$('input.new-todo').bind("enterKey",function(e){
					if ($(this).val().length != 0){
						addTodo($(this).val());
						tl_count(1);
						$(this).val('');
						if ($('section.main').is(':hidden')){
							$('section.main').toggle();
							$('footer.footer').toggle();
						}
					}
				});
				$('input.new-todo').keyup(function(e){
					if(e.keyCode == 13) {
						$(this).trigger("enterKey");
					}
				});

				$(document).on('change', 'input.toggle', function(){
					if (this.checked){
						$(this).parents("li").addClass("completed");
						tl_count(0);

						var tl = ls_getitem('tl');
						var tl_arr = tl.split(',');
						for(var i=0; i<tl_arr.length; i++){
							if (i==$(this).parents("li").prevAll().length) {
								var ls_item = ls_getitem('tl-'+tl_arr[i]);
								var ls_obj = $.parseJSON(ls_item);
								ls_obj.completed = true;

								ls_changeitem('tl-'+tl_arr[i], JSON.stringify(ls_obj));
							} 
						}
					} else {
						$(this).parents("li").removeClass("completed");
						tl_count(1);

						var tl = ls_getitem('tl');
						var tl_arr = tl.split(',');
						for(var i=0; i<tl_arr.length; i++){
							if (i==$(this).parents("li").prevAll().length) {
								var ls_item = ls_getitem('tl-'+tl_arr[i]);
								var ls_obj = $.parseJSON(ls_item);
								ls_obj.completed = false;

								ls_changeitem('tl-'+tl_arr[i], JSON.stringify(ls_obj));
							} 
						}
					}
					check_completed();

 					if (window.location.hash.length > 0) {
				    	var hash = window.location.hash;
				    	var shash = hash.substring(2);
				    	tabList(shash);					
				    }

				    console.log($('input.toggle').length + ' / ' + $('input.toggle:checked').length);
				    if ($('input.toggle').length == $('input.toggle:checked').length) {
				    	console.log('t');
				    	$('input#toggle-all').prop('checked', true);
				    } else {
				    	console.log('f');
				    	$('input#toggle-all').prop('checked', false);
				    }
				});

				$(document).on('change', 'input#toggle-all', function(){
					if(this.checked) {
      					$('input.toggle').each(function() {
      						if (!this.checked) {
								this.click();
      						}
      					});
  					} else {
    					$('input.toggle').each(function() {
          					this.click();
      					});
  					}

  					if (window.location.hash.length > 0) {
				    	var hash = window.location.hash;
				    	var shash = hash.substring(2);
				    	tabList(shash);					
					}
				});

				$(document).on('dblclick', 'div.view label', function(){
					$(this).parents("li").addClass('editing');
					$(this).parents("li").children("input.edit").focus();
				});
				$('input.edit').bind("enterKey",function(e){
					if ($(this).val().length != 0){
						$(this).parents("li").removeClass('editing');
						$(this).parents("li").children("div.view").children("label").text($(this).val());

						var tl = ls_getitem('tl');
						var tl_arr = tl.split(',');
						for(var i=0; i<tl_arr.length; i++){
							if (i==$(this).parents("li").prevAll().length) {
								var ls_item = ls_getitem('tl-'+tl_arr[i]);
								var ls_obj = $.parseJSON(ls_item);
								ls_obj.title = $(this).val();

								ls_changeitem('tl-'+tl_arr[i], JSON.stringify(ls_obj));
							} 
						}
					}
				});
				$('input.edit').keyup(function(e){
					if(e.keyCode == 13) {
						$(this).trigger("enterKey");
					}
				});
				$(document).on('focusout', 'input.edit', function(){
					if ($(this).val().length != 0){
						$(this).parents("li").removeClass('editing');
						$(this).parents("li").children("div.view").children("label").text($(this).val());

						var tl = ls_getitem('tl');
						var tl_arr = tl.split(',');
						for(var i=0; i<tl_arr.length; i++){
							if (i==$(this).parents("li").prevAll().length) {
								var ls_item = ls_getitem('tl-'+tl_arr[i]);
								var ls_obj = $.parseJSON(ls_item);
								ls_obj.title = $(this).val();

								ls_changeitem('tl-'+tl_arr[i], JSON.stringify(ls_obj));
							} 
						}
					}
				});
				$(document).on('click', 'button.destroy', function(){
					console.log();
					var new_tl = '';
					var tl = ls_getitem('tl');
					var tl_arr = tl.split(',');
					for(var i=0; i<tl_arr.length; i++){
						if (i!=$(this).parents("li").prevAll().length) {
							if (new_tl.length == 0) {
								new_tl += tl_arr[i];
							} else {
								new_tl += ',' + tl_arr[i];
							}
						} else {
							ls_delitem('tl-'+tl_arr[i]);
						} 
					}
						
					ls_delitem('tl');
					ls_additem('tl', new_tl);

					$(this).parents("li").remove();
					if (!$(this).parents("li").hasClass("completed")){
						tl_count(0);
					}
					if ($('ul.todo-list li').length == 0){
						$('section.main').toggle();
						$('footer.footer').toggle();
					}
					check_completed();
				});
				$(document).on('click', 'button.clear-completed', function(){
					clear_completed();
				});
				$(window).bind('hashchange', function() {
				     var hash = window.location.hash;
				     var shash = hash.substring(2);
				     tabList(shash);
				});

				if (window.location.hash.length > 0) {
				     var hash = window.location.hash;
				     var shash = hash.substring(2);
				     tabList(shash);					
				}

				$("#modal_trigger").leanModal();
			});

			function ls_build(){
				var arr = ls_getitem('tl').split(',');

				arr.forEach(function(item){
					var elem = ls_getitem('tl-'+item);
					var obj = $.parseJSON(elem);
					var cl = '';
					var ch = ''
					
					if (obj.completed) {
						cl = 'class="completed"';
						ch = 'checked="checked"';
					} else {
						tl_count(1);
					}
					var li = '\
							<li '+cl+'>\
								<div class="view">\
									<input class="toggle" type="checkbox" '+ch+'>\
									<label>'+obj.title+'</label>\
									<button class="destroy"></button>\
								</div>\
								<input class="edit" value="'+obj.title+'">\
							</li>\
						';

					$('ul.todo-list').append(li);
				});

				if ($('section.main').is(':hidden')){
					$('section.main').toggle();
					$('footer.footer').toggle();
				}

				check_completed();
			}

			function addTodo(label){
				var li = '\
							<li>\
								<div class="view">\
									<input class="toggle" type="checkbox">\
									<label>'+label+'</label>\
									<button class="destroy"></button>\
								</div>\
								<input class="edit" value="'+label+'">\
							</li>\
						';
				$('ul.todo-list').append(li);
				ls_incitem('tl-cnt');

				var tl_key = $.getUUID();
				if (ls_getitem('tl') === null){
					ls_additem('tl', tl_key);
				} else {
					ls_changeitem('tl', ls_getitem('tl') + ',' + tl_key);
				}

				ls_additem('tl-'+tl_key, '{"title":"' + label + '","order":' + ls_getitem('tl-cnt') + ',"completed":false,"id":"' + tl_key + '"}');

			}

			function tl_count(cnt){
				var c;
				if (cnt == 1) {
					c = parseInt($('.todo-count strong').text());
					c++;
					$('.todo-count strong').text(c);
				} else {
					var c = parseInt($('.todo-count strong').text())-1;
					$('.todo-count strong').text(c);
				}
			}

			function tabList(tab){
				$('.filters>li>a.selected').removeClass("selected");
				switch (tab){
					case 'active':
						$('ul.todo-list li').css('display','');
						$('ul.todo-list li.completed').css('display','none');

						$('.filters>li>a').addClass(function(index){
							if (index == 1){
								return "selected";
							} 
						});
						break;
					case 'completed':
						$('ul.todo-list li').css('display','none');
						$('ul.todo-list li.completed').css('display','');
						$('.filters>li>a').addClass(function(index){
							if (index == 2){
								return "selected";
							} 
						});
						break;
					default:
						$('ul.todo-list li').css('display','');
						$('.filters>li>a').addClass(function(index){
							if (index == 0){
								return "selected";
							} 
						});
						break;
				}
			}

			function clear_completed(){
				var new_tl = '';
				if ($('li.completed').length > 0) {
					$('ul.todo-list li').each(function(index){
						var tl = ls_getitem('tl');
						var tl_arr = tl.split(',');
						if ($(this).hasClass('completed')){
							for(var i=0; i<tl_arr.length; i++){
								if (i!=index) {
									if (new_tl.length == 0) {
										new_tl += tl_arr[i];
									} else {
										new_tl += ',' + tl_arr[i];
									}
								} else {
									ls_delitem('tl-'+tl_arr[i]);
								} 
							}
						}
					});
					$('li.completed').remove();
					ls_delitem('tl');
					ls_additem('tl', new_tl);
					check_completed();
				}
			}

			function check_completed(){
				var button = '<button class="clear-completed">Clear completed</button>';
				if ($('li.completed').length > 0){
					if ($('button.clear-completed').length == 0) {
						$('.footer').append(button);
					}
				} else {
					if ($('button.clear-completed').length > 0) {
						$('button.clear-completed').remove();
					}
				}
			}

			function ls_additem(title, value){
				ls.setItem(title, value);
				return true;
			}

			function ls_incitem(title){
				var v = parseInt(ls.getItem(title));
				v++;
				ls[title] = v;
				return true;
			}

			function ls_getitem(title){
				return ls.getItem(title);
			}

			function ls_changeitem(title, value){
				ls[title] = value;
				return true;
			}

			function ls_delitem(title){
				ls.removeItem(title);
				return true;
			}

		</script>
	</body>
</html>
