(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
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

  $(function(){
	  $('.dojang-notice').detach().prependTo('.entry-content');
	});

  $(function(){
	  $('.dojang-game-player-list').each(function(){
			$(this).on('change',function(){
				var color= $(this).attr('id');
				var group= $(this).find(':selected').attr('x-group-id');
				if(color==='dojang-pw') $('#dojang-wg').val(group);
				if(color==='dojang-pb') $('#dojang-bg').val(group);
			});
		});
    $('select[name=dojang-game-winner]').attr('disabled',true);
    $('.dojang-game-player-list').attr('disabled',true);
    $('.dojang-game-player-list option').attr('disabled',true);
    $('select[name="dojang-game-group"]').change(function() {
   	  var g=jQuery(this).val();
   	  if(g>0){
        $('select[name=dojang-game-winner]').attr('disabled',false);
        $('.dojang-game-player-list').attr('disabled',false);
   		  $('.dojang-game-player-list option').attr('disabled',true);
   		  $('.dojang-game-player-list option[x-group-id='+g+']').attr('disabled',false);
   	  }else{
        $('select[name=dojang-game-winner]').attr('disabled',true);
        $('select[name=dojang-game-winner]').val(-1);
        $('.dojang-game-player-list').attr('disabled',true);
        $('.dojang-game-player-list').val(-1);
      }
    });
  });
})( jQuery );
