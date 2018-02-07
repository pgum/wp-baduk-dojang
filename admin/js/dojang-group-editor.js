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
        items: [ /* This gets populated from separate handler, and enabled */ ] },
      { type: 'break' },
      { type: 'menu-radio',
        id: 'group_selector',
        text: function(item) {
          var text= item.selected;
          var el = this.get('league_selector:'+item.selected);
          return 'Select League Group'},
        disabled:0,
        icon: 'dashicons dashicons-admin-page',
        items: [ /* This gets populated from separate handler, and enabled */ ] },
      { type: 'break' },
      { type: 'html',  id: 'group_name', value: '', icon: 'dashicons dashicons-format-quote',
        html: function (item) {
          return '<div><span class="dashicons dashicons-format-quote"></span>Group Name:'+
                 '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'group_name\', { value: this.value });" '+
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
    w2ui.dojang_group_toolbar.on('click', function (event) {
      console.log('EVENT: '+ event.type + ' TARGET: '+ event.target, event);
    /*  if(event.target == 'new_group'){
        createNewGroup();
        recalculate_order();
      }*/
    //  if(event.target.match(/new_from_current:[0-9]*/)){
    /*    var group_id=event.target.split(':')[1];

        createNewGroup(group_id);
        recalculate_order();
      }*/
    //  if(event.target.match(/saved_drafts:restore_[0-9]*/)){
    /*    var draft_id=event.target.split(':restore_')[1];
        projectFromDraft(draft_id);
      }*/
    //  if(event.target.match(/saved_drafts:remove_[0-9]*/)){
    /*    var draft_id=event.target.split(':remove_')[1];
        removeDraft(draft_id);
      }
        if(event.target == 'save_button'){
        var league_multiplier= w2ui.toolbar.get('league_multiplier').value;
        var league_name= w2ui.toolbar.get('league_name').value;
        var saveAsDraft= w2ui.toolbar.get('save_as_draft').checked;
        if(saveAsDraft) saveDraft();
        else saveLeague();
      }*/
    });
    /*
    function refreshDraftsMenu(){
      var drafts= JSON.parse(localStorage.getItem('drafts'));
      var r= [];
      var i=0;
      if(drafts != null){
        for(did in drafts){
          var d= drafts[did].league;
          r.push({id: ('restore_'+i), text: d.name, icon: 'dashicons dashicons-external'});
          r.push({id: ('remove_'+i), text: 'Remove Draft: "'+d.name+'"', icon: 'dashicons dashicons-trash'});
          i++;
        }
        w2ui.toolbar.get('saved_drafts').items=r;
        w2ui.toolbar.enable('saved_drafts');
      }else{
        w2ui.toolbar.disable('saved_drafts');
      }
      w2ui.toolbar.refresh('saved_drafts');
    }
    function removeDraft(draft_id){
      var draftsArray=JSON.parse(localStorage.getItem('drafts'));
      draftsArray.splice(draft_id,1);
      localStorage.setItem('drafts',JSON.stringify(draftsArray));
      refreshDraftsMenu();
    }
    function saveDraft(){
      var league_obj= projectToJson();
      var dataToSave= { league: league_obj };
      var draftsArray=JSON.parse(localStorage.getItem('drafts'));
      if(draftsArray != null){
        var dii=draftsArray.length;
        for(did in draftsArray){
          if(draftsArray[did].league.name == dataToSave.league.name){
            draftsArray[did] = dataToSave;
            break;
          }
          dii--;
        }
        if(dii == 0) draftsArray.push(dataToSave);
      }
      else draftsArray= [ dataToSave, ];
      localStorage.setItem('drafts',JSON.stringify(draftsArray));
      $('#dojang-notification').val('Draft '+dataToSave.league.name+' saved!');
      refreshDraftsMenu();
    }
    refreshDraftsMenu();


    $('#dojang-sortable').sortable({placeholder: 'dojang-group-placeholder'});
    $('#dojang-sortable').on('sortupdate',function( event, ui){recalculate_order();});
    */
    $(function(){
    });
	});

})( jQuery );
