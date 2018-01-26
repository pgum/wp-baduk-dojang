(function( $ ) {
	'use strict';
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
