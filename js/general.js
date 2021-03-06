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
	    if (localStorage.getItem('tl-cnt'+suid) === null) {
			localStorage.setItem('tl-cnt'+suid, 0);
	    } else {
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

			var tl = ls_getitem('tl'+suid);
			var tl_arr = tl.split(',');
			for(var i=0; i<tl_arr.length; i++){
				if (i==$(this).parents("li").prevAll().length) {
					var ls_item = ls_getitem('tl'+suid+'-'+tl_arr[i]);
					var ls_obj = $.parseJSON(ls_item);
					ls_obj.completed = true;

					ls_changeitem('tl'+suid+'-'+tl_arr[i], JSON.stringify(ls_obj));

					if (suid.length>0){
						$.ajax({
							method: "POST",
							url: "ajax/todo.php?togglecompletetodo",
							dataType: 'json',
							data: {
								lskey: tl_arr[i],
								compl: '1'
							},
							success: function(data){
								if (!jQuery.isEmptyObject(data)){
									console.log(data);
								} else {
									console.log(data);
								}
							}
						});	
					}		
				} 
			}
		} else {
			$(this).parents("li").removeClass("completed");
			tl_count(1);

			var tl = ls_getitem('tl'+suid);
			var tl_arr = tl.split(',');
			for(var i=0; i<tl_arr.length; i++){
				if (i==$(this).parents("li").prevAll().length) {
					var ls_item = ls_getitem('tl'+suid+'-'+tl_arr[i]);
					var ls_obj = $.parseJSON(ls_item);
					ls_obj.completed = false;

					ls_changeitem('tl'+suid+'-'+tl_arr[i], JSON.stringify(ls_obj));

					if (suid.length>0) {
						$.ajax({
							method: "POST",
							url: "ajax/todo.php?togglecompletetodo",
							dataType: 'json',
							data: {
								lskey: tl_arr[i],
								compl: '0'
							},
							success: function(data){
								if (!jQuery.isEmptyObject(data)){
									console.log(data);
								} else {
									console.log(data);
								}
							}
						});
					}
				} 
			}
		}
		check_completed();

			if (window.location.hash.length > 0) {
	    	var hash = window.location.hash;
	    	var shash = hash.substring(2);
	    	tabList(shash);					
	    }

	    if ($('input.toggle').length == $('input.toggle:checked').length) {
	    	$('input#toggle-all').prop('checked', true);
	    } else {
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
	$('input.edit').bind("enterKey2",function(e){
		if ($(this).val().length != 0){
			$(this).parents("li").removeClass('editing');
			$(this).parents("li").children("div.view").children("label").text($(this).val());

			var tl = ls_getitem('tl'+suid);
			var tl_arr = tl.split(',');
			for(var i=0; i<tl_arr.length; i++){
				if (i==$(this).parents("li").prevAll().length) {
					var ls_item = ls_getitem('tl'+suid+'-'+tl_arr[i]);
					var ls_obj = $.parseJSON(ls_item);
					ls_obj.title = $(this).val();

					ls_changeitem('tl'+suid+'-'+tl_arr[i], JSON.stringify(ls_obj));

					if (suid.length>0){
						$.ajax({
							method: "POST",
							url: "ajax/todo.php?changetodo",
							dataType: 'json',
							data: {
								lskey: tl_arr[i],
								title: ls_obj.title
							},
							success: function(data){
								if (!jQuery.isEmptyObject(data)){
									console.log(data);
								} else {
									console.log(data);
								}
							}
						});
					}
				} 
			}
		}
	});
	$('input.edit').keyup(function(e){
		if(e.keyCode == 13) {
			$(this).trigger("enterKey2");
		}
	});
	$(document).on('focusout', 'input.edit', function(){
		if ($(this).val().length != 0){
			$(this).parents("li").removeClass('editing');
			$(this).parents("li").children("div.view").children("label").text($(this).val());

			var tl = ls_getitem('tl'+suid);
			var tl_arr = tl.split(',');
			for(var i=0; i<tl_arr.length; i++){
				if (i==$(this).parents("li").prevAll().length) {
					var ls_item = ls_getitem('tl'+suid+'-'+tl_arr[i]);
					var ls_obj = $.parseJSON(ls_item);
					ls_obj.title = $(this).val();

					ls_changeitem('tl'+suid+'-'+tl_arr[i], JSON.stringify(ls_obj));
				} 
			}
		}
	});
	$(document).on('click', 'button.destroy', function(){
		var new_tl = '';
		var tl = ls_getitem('tl'+suid);
		var tl_arr = tl.split(',');
		for(var i=0; i<tl_arr.length; i++){
			if (i!=$(this).parents("li").prevAll().length) {
				if (new_tl.length == 0) {
					new_tl += tl_arr[i];
				} else {
					new_tl += ',' + tl_arr[i];
				}
			} else {
				ls_delitem('tl'+suid+'-'+tl_arr[i]);

				if (suid.length>0){
					$.ajax({
						method: "POST",
						url: "ajax/todo.php?removetodo",
						dataType: 'json',
						data: {
							lskey: tl_arr[i]
						},
						success: function(data){
							if (!jQuery.isEmptyObject(data)){
								console.log(data);
							} else {
								console.log(data);
							}
						}
					});					
				}
			} 
		}
			
		ls_delitem('tl'+suid);
		ls_additem('tl'+suid, new_tl);

		$(this).parents("li").remove();
		if (!$(this).parents("li").hasClass("completed")){
			tl_count(0);
		}
		if ($('ul.todo-list li').length == 0){
			$('section.main').toggle();
			$('footer.footer').toggle();

			$('input#toggle-all').prop('checked', false);
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

	//$("#modal_trigger").leanModal();
	$(document).on('click', 'a#register_form', function(){
		$('div.user_login').css('display','none');
		$('div.user_register').css('display','block');
	});
	$(document).on('click', 'a#back', function(){
		$('div.user_login').css('display','block');
		$('div.user_register').css('display','none');
	});
});

function ls_build(){
	if (ls_getitem('tl'+suid) != null && ls_getitem('tl'+suid).length > 0) {
		var arr = ls_getitem('tl'+suid).split(',');

		arr.forEach(function(item){

			var elem = ls_getitem('tl'+suid+'-'+item);
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
	ls_incitem('tl-cnt'+suid);

	var tl_key = $.getUUID();
	if (ls_getitem('tl'+suid) === null || ls_getitem('tl'+suid).length == 0){
		ls_additem('tl'+suid, tl_key);
	} else {
		ls_changeitem('tl'+suid, ls_getitem('tl'+suid) + ',' + tl_key);
	}

	ls_additem('tl'+suid+'-'+tl_key, '{"title":"' + label + '","order":' + ls_getitem('tl-cnt'+suid) + ',"completed":false,"id":"' + tl_key + '"}');

	if (suid.length>0) {
		$.ajax({
			method: "POST",
			url: "ajax/todo.php?addtodo",
			dataType: 'json',
			data: {
				title: label,
				uid: suid,
				lskey: tl_key,
				lsorder: ls_getitem('tl-cnt'+suid),
			},
			success: function(data){
				if (!jQuery.isEmptyObject(data)){
					console.log(data);
				} else {
					console.log(data);
				}
			}
		});
	}

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

		var tl = ls_getitem('tl'+suid);
		var tl_arr = tl.split(',');
		for(var i=0; i<tl_arr.length; i++){
			var ls_item = ls_getitem('tl'+suid+'-'+tl_arr[i]);
			var ls_obj = $.parseJSON(ls_item);
			
			if (ls_obj.completed == true){
				ls_delitem('tl'+suid+'-'+ls_obj.id);
			} else {
				if (new_tl.length == 0) {
					new_tl += ls_obj.id;
				} else {
					new_tl += ',' + ls_obj.id;
				}
			}			
		}

		console.log(new_tl);

		$('li.completed').remove();
		ls_delitem('tl'+suid);
		ls_additem('tl'+suid, new_tl);

		if ($('ul.todo-list li').length == 0){
			$('section.main').toggle();
			$('footer.footer').toggle();

			$('input#toggle-all').prop('checked', false);
		}

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

function clear_ls(){
	if (ls_getitem('tl') != null && ls_getitem('tl').length > 0) {
		var tl = ls_getitem('tl');
		var tl_arr = tl.split(',');
		for(var i=0; i<tl_arr.length; i++){
			ls_delitem('tl-'+tl_arr[i]);
		}
		ls_delitem('tl');
		ls_delitem('tl-cnt');
	}
	if (ls_getitem('tl-cnt') != null && ls_getitem('tl-cnt').length > 0) {
		ls_delitem('tl-cnt');
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
