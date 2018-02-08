(function( $ ) {
	'use strict';

  $(function(){
    $('#dojang-group-toolbar').w2toolbar({
      name: 'dojang_group_toolbar',
      items: [
      { type: 'menu-radio',
        id: 'league_selector',
        text: function(item) {
          var text= item.selected;
          var el = this.get('league_selector:'+item.selected);
          return 'Select League'},
        disabled:0,
        icon: 'dashicons dashicons-admin-page',
        items: JSON.parse(dojang_editor.leagues_items) },
      { type: 'break' },
      { type: 'menu-radio',
        id: 'group_selector',
        text: function(item) {
          var text= item.selected;
          var el = this.get('league_selector:'+item.selected);
          return 'Select League Group'},
        disabled:0,
        icon: 'dashicons dashicons-admin-page',
        items: JSON.parse(dojang_editor.groups_items) },
      { type: 'break' },
      { type: 'html',  id: 'group_name', value: '', icon: 'dashicons dashicons-format-quote',
        html: function (item) {
          return '<div><span class="dashicons dashicons-format-quote"></span>Group Name:'+
                 '<input class="dojang-toolbar-input" onchange="var el = w2ui.dojang_group_toolbar.set(\'group_name\', { value: this.value });" '+
                 'value="'+item.value+'" size="20" placeholder="Group A,B..."/></div>'; } },
      { type: 'break' },
      { type: 'html', id:'dojang_notification', icon: 'dashicons dashicons-admin-tools',
        html: function (item) {
          return '<div><span class="dashicons dashicons-admin-tools"></span>Editor Message:'+
                 '<input class="dojang-toolbar-input" size="20" id="dojang-notification" disabled/></div>'; } },
      { type: 'spacer' },
      { type: 'html', html: 'Save:'},
      { type: 'button',  id: 'save_button',  text: '<b>Confirm</b>', icon: 'dashicons dashicons-plus-alt' }
      ]
    });

    function createGroup(gid, gName, playerList){
      console.log(gid);
      console.log(playerList);
      var group= $('<div></div>').addClass('dojang-editor-group');
      var num=0;
      console.log(gName);
      var group_object= dojang_editor.group_object;

      group.append(group_object);
      group.find('.dojang-player-select').each(function(index){
          var pid = playerList[index] != undefined ? playerList[index].playerId : -1;
          $(this).val(pid);
        });
      group.find('.dojang-editor-group-order').val(num);
      group.find('.dojang-editor-remove-group').hide();
      group.find('.dojang-editor-group-order').hide();
      $('#dojang-workspace').html(group);
      $('.dojang-player-select').chosen();
      $( ".dojang-group-editor-group-players" ).sortable();
    }
    function showGroupsFromLeague(leagueId){
        var groupItems=w2ui.dojang_group_toolbar.get('group_selector').items;
        for(var i=0; i< groupItems.length; i++){
          if(groupItems[i].league == leagueId) w2ui.dojang_group_toolbar.show('group_selector:'+groupItems[i].id);
          else w2ui.dojang_group_toolbar.hide('group_selector:'+groupItems[i].id);
        }
    }
    function showGroupToWorkspace(groupId, groupName, groupPlayers){
        createGroup(groupId, groupName, groupPlayers);
    }
    function convertGroupObjectToJsObject(){
      var g_players= [];
      $('.dojang-player-select').each(function(){
        var pid= $(this).val();
        if(pid != -1) g_players.push(pid);
      });
      return {players: g_players};
    }

    w2ui.dojang_group_toolbar.on('click', function (event) {
      if(event.target.match(/league_selector:[0-9]*/)){
        var league_id=event.target.split(':')[1];
        showGroupsFromLeague(league_id);
      }
      if(event.target.match(/group_selector:[0-9]*/)){
        var group_id= event.target.split(':')[1];
        var group_obj= w2ui.dojang_group_toolbar.get(event.target);
        w2ui.dojang_group_toolbar.set('group_name', {value: group_obj.text});
        showGroupToWorkspace(group_id, group_obj.text, group_obj.players);
      }
      if(event.target == 'save_button'){
      //console.log('EVENT: '+ event.type + ' TARGET: '+ event.target, event);
      var group_obj= w2ui.dojang_group_toolbar.get('group_selector');
      var g_name= w2ui.dojang_group_toolbar.get('group_name').value;
      var g_id= group_obj.selected;
      var g_players= convertGroupObjectToJsObject().players;
      var dataToSend= {group_id: g_id, group_name: g_name, group_players: g_players};
      var dataToSendJson= JSON.stringify(dataToSend, null, 2);
      $('#dojang-debug').text(dataToSendJson).show();
      $.post({
        url: ajaxurl,
        //dataType: 'json',
        data: {'action': 'dojang_update_group', 'group_data': dataToSendJson},
        success: function(data){
          console.log('ajax send!');
          $('#dojang-debug').append(data);
          $('#dojang-notification').val('Group'+dataToSend.group_name+' Updated!'); }
      });
      }
    });

    $(function(){
      $('#dojang-debug').text(JSON.stringify(dojang_editor));
    });
	});

})( jQuery );
