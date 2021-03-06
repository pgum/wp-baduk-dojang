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
          window.location.hash=leagueId;
				}
			});
		});
    var leagueOpened= window.location.hash.split('#')[1];
    $('.dojang-results-archive[x-league-id='+leagueOpened+']').click();
	});
	$(function() {
		/*Baduk Dojank Main View, change not approved results in table when hover over Games to Approve list*/
		$('.dojang-table-to-approve tbody tr')
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
			var resultId= $(this).attr('x-result-id');
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_result', 'result_id': resultId},
				success: function(data){ console.log(data); $('.dojang-table-to-approve tr[x-result-id='+resultId+']').fadeOut(800, function(){$(this).remove();});}
			});
		});
		$('.dojang-remove-result').on('click', function(){
			var resultId= $(this).attr('x-result-id');
			var choice= confirm("Are you sure you want to remove pending result?\n(Players will have to submit result again)");
			if(choice){
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_remove_result', 'result_id': resultId},
					success: function(data){ console.log(data); $('.dojang-table-to-approve tr[x-result-id='+resultId+']').fadeOut(800, function(){$(this).remove();});}
				});
			}
		});
	});
  /*AJAX on click events to approve and remove pending players*/
	$(function(){
		$('.dojang-approve-player').on('click', function(){
			var playerId= $(this).attr('x-player-id');
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_approve_player', 'player_id': playerId},
				success: function(data){ console.log(data); $('.dojang-table-to-approve tr[x-player-id='+playerId+']').fadeOut(800, function(){$(this).remove();});}
			});
		});
		$('.dojang-remove-player').on('click', function(){
			var choice= confirm("Are you sure you want to remove pending player?\n(Player will have to register again)");
			if(choice){
				var playerId= $(this).attr('x-player-id');
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_remove_player', 'player_id': playerId},
					success: function(data){ console.log(data); $('.dojang-table-to-approve tr[x-player-id='+playerId+']').fadeOut(800, function(){$(this).remove();});}
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
    $('.dojang-toggle-add').on('click', function(){ $('.dojang-add').toggle(); });
    $('.dojang-create-player').click(function(){
      console.log('Create Player!');
      var pName = $('.dojang-add-player-playerName').val();
      var pCountry= $('.dojang-add-player-playerCountry').val();
      var pKgs = $('.dojang-add-player-playerKgs').val();
      var pRank = $('.dojang-add-player-playerRank').val();
      var pEmail = $('.dojang-add-player-playerEmail').val();
      var pTimezone = $('.dojang-add-player-playerTimezone').val();
      var pApproved = $('.dojang-add-player-playerApproved').val();
      console.log([pName,pCountry,pKgs,pRank,pEmail,pTimezone,pApproved]);
			$.post({
				url: ajaxurl,
				data: {'action': 'dojang_register_player_admin','name': pName,
                                                        'country': pCountry,
                                                        'rank': pRank,
                                                        'kgs': pKgs,
                                                        'email': pEmail,
                                                        'timezone': pTimezone,
                                                        'approved': pApproved },
				success: function(data){
          console.log(data);
        }
      });
    });
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
			var choice= confirm("Are you sure you want to close league and distribute points to players?\n(League will be read-only and no further changes could be done)\nMake sure all results are there and 'win with teacher' is filled out!");
			if(choice){
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_close_league_distribute_points', 'league_id': leagueToClose},
					success: function(data){
						alert('League Points distributed and league closed.');
						console.log(data);
					}
				});
		}
		});
	});
	$(function(){
		$('.dojang-update-league-points').on('click', function(){
			var leagueId= $(this).attr('x-league-id');
			var multiplier_raw= $('.dojang-league-points-input[x-league-id='+leagueId+']').val();
			var multiplier = parseInt(multiplier_raw);
				$.post({
					url: ajaxurl,
					data: {'action': 'dojang_league_points_update', 'league_id': leagueId, 'multiplier': multiplier},
					success: function(data){
						alert('League Points multiplier updated.');
						console.log(data);
					}
				});
		});
	});
//w2ui overlay menu to update remove and create game results
$(function(){
  function getResultsPlayers(resultCell){
  	var col = resultCell.parent().children().index(resultCell);
  	var playerId_col = resultCell.parents('table').find('thead th:eq('+col+')').attr('x-player-id');
  	var playerName_col= $('td[x-player-id='+playerId_col+']').first().text();
  	var row = resultCell.parent().parent().children().index(resultCell.parent());
  	var playerId_row2 = resultCell.parent().attr('x-player-id');
  	var playerId_row = resultCell.parents('tr').attr('x-player-id');
    console.log('playerId', playerId_row);
    console.log('playerId_2', playerId_row2);
  	var playerName_row= $('td[x-player-id='+playerId_row+']').first().text();
  	var playerRow_result= null;
  	if(resultCell.attr('x-result') != " "){
  		playerRow_result= resultCell.attr('x-result') == 'W' ? playerId_row : playerId_col;
  	}
  	var resultId= resultCell.attr('x-result-id');
    var groupId= resultCell.parents('table').attr('x-group-id');
  	return {pr: {id: playerId_row, name: playerName_row},
            pc: {id: playerId_col, name: playerName_col},
            pw: playerRow_result,
            rid: resultId,
            gid: groupId}
  }

  $('.dojang-group-table .dojang-result-not-approved').on('click', function(){
    var r= $(this).attr('x-result-id');
    $(this).w2menu({
      items: [{ text: 'Approve Result',},
              { id: 'approve:'+r, text: 'Approve Result', icon: 'dashicons dashicons-star-filled'},
              { id: 'remove:'+r, text: 'Reject Result', icon: 'dashicons dashicons-trash'},
              { id: 'refresh', text: 'Refresh the site to see update!'}, ],
      onSelect: function(event){
    	  var iid= event.item.id;
        if(iid=='refresh'){ location.reload(false); return; }
    	  var action= iid.split(':')[0];
    	  var resultId= iid.split(':')[1];
    	  if(action=='approve'){
          $.post({
  					url: ajaxurl,
  					data: {'action': 'dojang_approve_result', 'result_id': resultId},
  					success: function(data){
              console.log(data);	}
  				});
    	  }
    	  if(action=='remove'){
          $.post({
            url: ajaxurl,
            data: {'action': 'dojang_remove_result', 'result_id': resultId},
            success: function(data){
              $('[x-result-id='+r+'] span').removeClass('dashicons-marker').removeClass('dashicons-no');
              console.log(data);	}
          });
    	  }
    }});
  });

  $('.dojang-group-table .dojang-result-approved').on('click', function(){
  	var r= getResultsPlayers($(this));

    //console.log(r);
    $(this).w2menu({
      items: [{ text: 'Update Result',},
              { id: 'update:'+r.rid+':'+r.pr.id, text: r.pr.name+' won', icon: 'dashicons dashicons-star-filled', disabled: (r.pr.id == r.pw)},
              { id: 'update:'+r.rid+':'+r.pc.id, text: r.pc.name+' won', icon: 'dashicons dashicons-star-empty', disabled: (r.pc.id == r.pw)},
              { id: 'remove:'+r.rid, text: 'Remove Result', icon: 'dashicons dashicons-trash'},
              { text: '--' },
              { id: 'toggle_review:'+r.rid, text: "Toggle Reviewed Game", icon: 'dashicons dashicons-welcome-view-site'},
              { text: '--' },
              { id: 'refresh', text: 'Refresh the site to see update!'}, ],
      onSelect: function(event){
    	  var iid= event.item.id;

        if(iid=='refresh'){ location.reload(false); return; }

    	  var action= iid.split(':')[0];
    	  var resultId= iid.split(':')[1];
    	  if(action=='update'){
    		  var playerId= iid.split(':')[2];
    		  //console.log('Update winner of game resultId='+resultId+' to playerId='+playerId);
          $.post({
  					url: ajaxurl,
  					data: {'action': 'dojang_update_result', 'result_id': resultId, 'playerW': playerId},
  					success: function(data){
              $('[x-result-id='+r+'] span').each(function(){
                if($(this).hasClass('dashicons-marker')){
                  $(this).removeClass('dashicons-marker').addClass('dashicons-no');
                }
                else{
                  $(this).removeClass('dashicons-no').addClass('dashicons-marker');
                }
              });
              console.log(data);	}
  				});
    	  }
    	  if(action=='remove'){
    		  //console.log('Remove game resultId='+resultId);
          $.post({
            url: ajaxurl,
            data: {'action': 'dojang_remove_result', 'result_id': resultId},
            success: function(data){ console.log(data);	}
          });
    	  }
      if(action=='toggle_review'){
          $.post({
            url: ajaxurl,
            data: {'action': 'dojang_toggle_review', 'result_id': resultId},
            success: function(data){
              console.log(data);
              $('[x-result-id='+resultId+']').each(function(){
                $(this).toggleClass('dojang_reviewed');
              });
            }
          });
      }
    }});
  });
  $('.dojang-group-table .dojang-result-none').on('click', function(){
  	var r= getResultsPlayers($(this));
  //  console.log(r);
    $(this).w2menu({
      items: [{ text: 'Create Result',},
              { id: 'create:'+r.pr.id+':'+r.pc.id+':'+r.gid, text: r.pr.name+' won', icon: 'dashicons dashicons-star-filled'},
              { id: 'create:'+r.pc.id+':'+r.pr.id+':'+r.gid, text: r.pc.name+' won', icon: 'dashicons dashicons-star-empty'},
              { text: '--' },
              { id: 'refresh', text: 'Refresh the site to see update!'}],
      onSelect: function(event){
  	     var iid= event.item.id;

         if(iid=='refresh'){ location.reload(false); return; }

  	     var playerWinner= iid.split(':')[1];
  	     var playerLoser=  iid.split(':')[2];
  	     var groupId=  iid.split(':')[3];
  	     //console.log('Create game result for group '+groupId+' between players '+playerWinner+' and '+playerLoser+' where winner was:'+playerWinner);
         $.post({
           url: ajaxurl,
           data: {'action': 'dojang_create_result', 'group_id': groupId, 'playerW': playerWinner, 'playerL': playerLoser},
           success: function(data){
             var prc=$('th[x-player-id='+r.pr.id+']').index();
             var pcc=$('th[x-player-id='+r.pc.id+']').index();
             if(r.pr.id == playerWinner){
               $('.dojang-group-table[x-group-id='+r.gid+'] tr:eq('+(1-3 + prc)+') td:eq('+pcc+')').removeClass('dojang-result-none').html('<span class="dashicons dashicons-marker"></span>');
               $('.dojang-group-table[x-group-id='+r.gid+'] tr:eq('+(1-3 + pcc)+') td:eq('+prc+')').removeClass('dojang-result-none').html('<span class="dashicons dashicons-no"></span>');
             }else{
               $('.dojang-group-table[x-group-id='+r.gid+'] tr:eq('+(1-3 + prc)+') td:eq('+pcc+')').removeClass('dojang-result-none').html('<span class="dashicons dashicons-no"></span>');
               $('.dojang-group-table[x-group-id='+r.gid+'] tr:eq('+(1-3 + pcc)+') td:eq('+prc+')').removeClass('dojang-result-none').html('<span class="dashicons dashicons-marker"></span>');
             }
             console.log(data);	}
         });
      }});
  });
});

})( jQuery );
