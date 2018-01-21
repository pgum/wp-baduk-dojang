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
		$(".dojang-approve-result").on("click", function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_result', 'result_id': $(this).attr('x-result-id')},
				success: function(data){ console.log(data); }
			});
		});
		$(".dojang-remove-result").on("click", function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_remove_result', 'result_id': $(this).attr('x-result-id')},
				success: function(data){ console.log(data); }
			});
		});
	});
  /*AJAX on click events to approve and remove pending players*/
	$(function(){
		$(".dojang-approve-player").on("click", function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_player', 'player_id': $(this).attr('x-player-id')},
				success: function(data){ console.log(data); }
			});
		});
		$(".dojang-remove-player").on("click", function(){
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_remove_player', 'player_id': $(this).attr('x-player-id')},
				success: function(data){ console.log(data); }
			});
		});
	});
})( jQuery );
