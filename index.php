<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Template • TodoMVC</title>
		<link rel="stylesheet" href="css/base.css">
		<link rel="stylesheet" href="css/index.css">
		<!-- CSS overrides - remove if you don't need it -->
		<link rel="stylesheet" href="css/app.css">
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
	</head>
	<body>
		<?
			require 'core/general.php';
/**
			session_start();
			if (!isset($_SESSION[uid])) {
				$_SESSION['anon'] = 1;
			} else {
				session_unregister('anon');
			}
			
			print_r($_SESSION);
*/
		?>
		
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
				<!-- Hidden if no completed items are left ↓ -->
				<button class="clear-completed">Clear completed</button>
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
			
			var ls = localStorage;

			$(function(){
				if (localStorage) {
				    console.log('LocalStaroge enabled!');
				    if (localStorage.getItem('tl-cnt') === null) {
						localStorage.setItem('tl-cnt', 0);
				    }
				}
				
				$('input.new-todo').bind("enterKey",function(e){
					if ($(this).val().length != 0){
						addTodo($(this).val());
						tl_count(1);
						ls_incitem('tl-cnt');
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
					} else {
						$(this).parents("li").removeClass("completed");
						tl_count(1);
					}
				});
				$(document).on('click', 'button.destroy', function(){
					$(this).parents("li").remove();
					if (!$(this).parents("li").hasClass("completed")){
						tl_count(0);
					}
					if ($('ul.todo-list li').length == 0){
						$('section.main').toggle();
						$('footer.footer').toggle();
					}
				})
				$(window).bind('hashchange', function() {
				     var hash = window.location.hash;
				     var shash = hash.substring(2);
				     tabList(shash);
				});
			});

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
				switch (tab){
					case 'active':
						$('ul.todo-list li').css('display','');
						$('ul.todo-list li.completed').css('display','none');
						break;
					case 'completed':
						$('ul.todo-list li').css('display','none');
						$('ul.todo-list li.completed').css('display','');
						break;
					default:
						$('ul.todo-list li').css('display','');
						break;
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

		</script>
	</body>
</html>
