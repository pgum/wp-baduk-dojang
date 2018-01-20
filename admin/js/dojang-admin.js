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
})( jQuery );
