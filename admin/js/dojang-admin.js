(function( $ ) {
	'use strict';
	$(function(){
		$('.dojang-results-archive').each(function(){
			$(this).on('click',function(){
				var leagueId= $(this).attr('x-league-id');
				if($(this).hasClass('dojang-collapse')){
					$(this).removeClass('dojang-collapse').addClass('dojang-expand');
					$('.dojang-league-groups-results-tables-archive[x-league-id='+leagueId+']').addClass('dojang-hidden');
				}
				else if($(this).hasClass('dojang-expand')){
					$(this).removeClass('dojang-expand').addClass('dojang-collapse');
					$('.dojang-league-groups-results-tables-archive[x-league-id='+leagueId+']').removeClass('dojang-hidden');
				}
			});
		});
	});
	$(function() {
		/*Baduk Dojank Main View, change not approved results in table when hover over Games to Approve list*/
		$('.dojang-games-to-approve tbody tr')
			.mouseenter(function(){
				var result_id= $(this).attr('x-result-id');
	     	$('td[x-result-id='+result_id+']').addClass('dojang-result-highlight');
			})
			.mouseleave(function(){
				var result_id= $(this).attr('x-result-id');
	     	$('td[x-result-id='+result_id+']').removeClass('dojang-result-highlight');
	  	});
 	});
  /*AJAX on click events to approve and remove pending result*/
	$(function(){
		$('.dojang-approve-result').on('click', function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_result', 'result_id': $(this).attr('x-result-id')},
				success: function(data){ console.log(data); }
			});
		});
		$('.dojang-remove-result').on('click', function(){
			var choice= confirm("Are you sure you want to remove pending result?\n(Players will have to submit result again)");
			if(choice){
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_remove_result', 'result_id': $(this).attr('x-result-id')},
					success: function(data){ console.log(data); }
				});
			}
		});
	});
  /*AJAX on click events to approve and remove pending players*/
	$(function(){
		$('.dojang-approve-player').on('click', function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_player', 'player_id': $(this).attr('x-player-id')},
				success: function(data){ console.log(data); }
			});
		});
		$('.dojang-remove-player').on('click', function(){
			var choice= confirm("Are you sure you want to remove pending player?\n(Player will have to register again)");
			if(choice){
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_remove_player', 'player_id': $(this).attr('x-player-id')},
					success: function(data){ console.log(data); }
				});
			}
		});
	});
	/*AJAX on click checkbox to update if player played with teacher and won*/
	$(function(){
		$('.dojang-player-won-against-teacher').on('click', function(){
			var groupPlayerIdToChange= $(this).attr('x-groupplayer-id');
			var wonWithTeacher= Boolean($(this).attr('checked')); //Boolean(string) gives true if string is not empty
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_update_played_with_teacher', 'groupplayer_id': groupPlayerIdToChange, 'wonWithTeacher': wonWithTeacher},
				success: function(data){
					$('.won-with-teacher-cell[x-groupplayer-id='+groupPlayerIdToChange+']').addClass('dojang-cell-updated').delay(2000).queue(function(){
					    $(this).removeClass('dojang-cell-updated').dequeue();
					});
					console.log(data); }
			});
		});
	});
	/*Table editable cell UI and ajax call on button OK click*/
	$(function(){
		$('.dojang-editable-cell').each(function(){
			var editedCell= $(this);
			$(this).on('click', '.dojang-update-btn', function(){
				//var inputVal = $(this).prev('input').val();
				var inputVal = $(this).parent().children('input').val();
				var fieldName= $(this).attr('x-field');
				var playerId = $(this).attr('x-player-id');
				//console.log('UPDATE BTN FIELD: '+fieldName+' TO: '+inputVal+' OF PLAYER ID:'+playerId);
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_update_player_field', 'player_id': playerId, 'field': fieldName, 'value': inputVal},
				success: function(data){
					console.log(data);
					editedCell.addClass('dojang-cell-updated').delay(2000).queue(function(){ $(this).removeClass('dojang-cell-updated').dequeue(); });}
			});
				$(this).parent()
				.removeClass('dojang-editable-div-e')
				.addClass('dojang-editable-div')
				.html(inputVal);
			});
			$(this).on('click', '.dojang-editable-div', function(){
				var cellValue= $(this).text();
				var fieldName= $(this).attr('x-field');
				var playerId= $(this).parents('tr').attr('x-player-id');
				$(this)
				.removeClass('dojang-editable-div')
				.addClass('dojang-editable-div-e')
				.html('<input class="dojang-input" value="'+cellValue+'"/><a href="#pid-'+playerId+'" class="dojang-update-btn button button-secondary" x-player-id="'+playerId+'" x-field="'+fieldName+'">OK</a>');
			});
		});
	});
	/*AJAX on click Close League and Distribute points to players*/
	$(function(){
		$('.dojang-distribute').on('click', function(){
			var leagueToClose= $(this).attr('x-league-id');
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_close_league_distribute_points', 'league_id': leagueToClose},
				success: function(data){
					console.log(data);
				}
			});
		});
	});
})( jQuery );
