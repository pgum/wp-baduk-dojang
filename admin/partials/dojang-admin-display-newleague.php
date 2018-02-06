<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <div id="dojang-editor">
    <div id="dojang-toolbar"></div>
    <div id="dojang-workspace"><ul id="dojang-sortable"></ul></div>
    <div id="dojang-debug-container" class="dojang-hidden"><pre id="dojang-debug"></pre></div>
  </div>
</div>
<?php
function renderLastGroups(){
  $currentLeague = new Dojang_League();
  $groups = $currentLeague->getGroupsDetails();
  foreach($groups as $g)
  $json.= '{ id:\''.$g->groupDetails->playerGroupId.'\', text: \''.$g->groupDetails->groupName.'\', img: \'dashicons dashicons-arrow-right\' },';
  return $json;
}
function renderLastGroupsData(){
  $currentLeague = new Dojang_League();
  $groups = $currentLeague->getGroupsDetails();
  $js.='[';
  foreach($groups as $g){
    $js.= '{ id:\''.$g->groupDetails->playerGroupId.'\', name: \''.$g->groupDetails->groupName.'\', players: [';
    foreach($g->groupPlayers as $p)
      $js.= $p->playerId.',';
    $js.='] },';
  }
  $js.=']';
  return $js;
}
function renderPlayerSelectOptions(){
  $players= new Dojang_Players();
  $plist= $players->getAllPlayersIdNameRank();
  //print_r($plist);
  $html='<option value="-1"></option>';
  foreach($plist as $p)
    $html.= '<option value="'.$p['playerId'].'">'.$p['playerName'].' - '.$p['playerRank'].'</option>';
  return $html;
}
function renderGroupObject(){
  $html.='<div class="dojang-editor-group-properties"><input class="dojang-editor-group-name"/><input disabled class="dojang-editor-group-order"/></div>';
  $html.='<ol class="dojang-editor-group-players">';
  $maxPlayersInGroup=10;
  for($i=0; $i<$maxPlayersInGroup; ++$i)
    $html.='<li class="dojang-editor-player"><select data-placeholder="Choose a player..." name="player-'.$i.'" class="dojang-player-select">'.renderPlayerSelectOptions().'</select></li>';
  $html.='</ol>';
  $html.='<a class="dojang-editor-remove-group button button-secondary">Remove Group</a>';
  return '\''.$html.'\'';
}
?>
<script>
function groupObjectHtml(){
  return <?php echo renderGroupObject(); ?>;
}
function lastGroupsDataHtml(){
  return <?php echo renderLastGroupsData(); ?>;
}
var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];
var d = new Date();
//var hideSavedDrafts= <?php /*areSavedDrafts();*/ ?> != 0 ? true : false;

function recalculate_order(){
  $('#dojang-sortable .dojang-group-object').each(function(){
  var index= $(this).index()+1;
  $(this).attr('x-group-order',index);
  $(this).find('.dojang-editor-group-order').val(index);
  });
}

function getUrlParameters(){
  var a= window.location.search.substr(1).split('&');
  if (a == "") return {};
    var b = {};
    for (var i = 0; i < a.length; ++i)
    {
        var p=a[i].split('=', 2);
        if (p.length == 1)
            b[p[0]] = "";
        else
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
    }
    return b;
}

function createNewGroup(gid=null){
  var group_name_array=['Group A','Group B','Group C','Group D','Group E','Group F','Group G','Group H'];
  var last_groups= lastGroupsDataHtml();
  var group= $('<div></div>').addClass('dojang-group');
  var num= $('#dojang-sortable .dojang-group-object').length;
  var group_name= group_name_array[num];
  var element= $('<li class="dojang-group-object"></li>').attr('x-group-order',num);
  var group_object= groupObjectHtml();

  group.append(group_object);
  if(gid){
    var playersToSelect=[];
    for(g in last_groups){
      if(last_groups[g].id == gid){
        playersToSelect = last_groups[g].players;
        break;
      }
    }
    group.find('.dojang-player-select').each(function(index){
      var pid = playersToSelect[index] != undefined ? playersToSelect[index] : -1;
      $(this).val(pid);
    });
  }
  group.find('.dojang-editor-group-name').val(group_name);
  group.find('.dojang-editor-group-order').val(num);
  element.append(group);
  element.appendTo($('#dojang-sortable'));
  $('.dojang-player-select').chosen();
  $( "#dojang-sortable" ).sortable( "refresh" );
}
function groupFromJson(jsonGroup){
  var group= $('<div></div>').addClass('dojang-group');
  var num= $('#dojang-sortable .dojang-group-object').length;
  var group_name= jsonGroup.name;
  var element= $('<li class="dojang-group-object"></li>').attr('x-group-order',jsonGroup.order);
  var group_object= groupObjectHtml();
  group.append(group_object);
  var playersToSelect=jsonGroup.players;
  group.find('.dojang-player-select').each(function(index){
    var pid = playersToSelect[index] != undefined ? playersToSelect[index] : -1;
    $(this).val(pid);
  });
  group.find('.dojang-editor-group-name').val(group_name);
  group.find('.dojang-editor-group-order').val(num);
  element.append(group);
  element.appendTo($('#dojang-sortable'));
  $('.dojang-player-select').chosen();
  $( "#dojang-sortable" ).sortable( "refresh" );
}
function convertGroupObjectToJsObject(node){
  var g_name= node.find('.dojang-editor-group-name').val();
  var g_order= node.find('.dojang-editor-group-order').val();
  var g_players= [];
  node.find('.dojang-player-select').each(function(){
    var pid= $(this).val();
    if(pid != -1) g_players.push(pid);
  });
  return {name: g_name, order: g_order, players: g_players};
}
function projectFromJson(jsonProject){
  w2ui.toolbar.get('league_name').value = jsonProject.name;
  w2ui.toolbar.get('league_multiplier').value = jsonProject.multiplier;
  w2ui['toolbar'].refresh('league_name');
  w2ui['toolbar'].refresh('league_multiplier');
  for(g in jsonProject.groups) groupFromJson(jsonProject.groups[g]);
}

function projectToJson(){
  var league_obj= {
    name: w2ui.toolbar.get('league_name').value,
    multiplier: w2ui.toolbar.get('league_multiplier').value
  };
  var l_groups = [];
  $('.dojang-group').each(function(){ l_groups.push(convertGroupObjectToJsObject($(this))); });
  league_obj['groups']= l_groups;
  return league_obj;
}

function projectFromDraft(did){
  var drafts= JSON.parse(localStorage.getItem('drafts'));
  $('#dojang-sortable').text('');
  projectFromJson(drafts[did].league);
}

function saveLeague(){
  var league_obj= projectToJson();
  var dataToSend= { league: league_obj };
  /* show debug info */
  $('#dojang-debug-container').removeClass('dojang-hidden');
  var dataToSendJson= JSON.stringify(dataToSend,null, 2);
  $('#dojang-debug').text(dataToSendJson);
  $.post({
    url: ajaxurl,
    //dataType: 'json',
    data: {'action': 'dojang_create_league', 'league_data': dataToSendJson},
    success: function(data){
      console.log('ajax send!');
      $('#dojang-debug').append(data);
      $('#dojang-notification').val('League'+dataToSend.league.name+' Published!'); }
  });
}
/* remove group button functionality */
$('#dojang-workspace').on('click', '.dojang-editor-remove-group', function(){
  $(this).parents('li').remove();
  $( "#dojang-sortable" ).sortable( "refresh" );
  recalculate_order();
});
/*toolbox handlers*/
$('#dojang-toolbar').w2toolbar({
  name: 'toolbar',
  items: [
  { type: 'menu', id: 'saved_drafts', caption: 'Saved Drafts', disabled:1, icon: 'dashicons dashicons-admin-page',
    items: [ /* This gets populated from separate handler, and enabled */ ] },
  { type: 'break' },
  { type: 'button', id: 'new_group', text: 'New Group', icon: 'dashicons dashicons-plus' },
  { type: 'menu', id: 'new_from_current', caption: 'Current Groups', icon: 'dashicons dashicons-forms',
    items: [ <?php echo renderLastGroups();  ?> ] },
  { type: 'break' },
  { type: 'html',  id: 'league_multiplier', value: 1, icon: 'dashicons dashicons-no-alt',
    html: function (item) {
    return '<div><span class="dashicons dashicons-no-alt"></span>Multiplier:'+
     '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_multiplier\', { value: this.value });" '+
     'size="3" placeholder="1,2,.." value="'+item.value+'"/></div>'; } },
  { type: 'break' },
  { type: 'html',  id: 'league_name', value: monthNames[d.getMonth()], icon: 'dashicons dashicons-format-quote',
    html: function (item) {
      return '<div><span class="dashicons dashicons-format-quote"></span>Name:'+
             '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_name\', { value: this.value });" '+
             'value="'+item.value+'" size="20" placeholder="eg. January..."/></div>'; } },
  { type: 'break' },
  { type: 'html', id:'dojang_notification', icon: 'dashicons dashicons-admin-tools',
    html: function (item) {
      return '<div><span class="dashicons dashicons-admin-tools"></span>'+
             '<input class="dojang-toolbar-input" size="20" id="dojang-notification" disabled/></div>'; } },
  { type: 'spacer' },
  { type: 'html', html: 'Save:'},
  { type: 'radio', id: 'save_as_draft', group: '1', text: 'as Draft', icon: 'dashicons dashicons-edit', checked: true },
  { type: 'radio', id: 'save_publish', group: '1', text: 'Publish on save', icon: 'dashicons dashicons-admin-post' },
  { type: 'break' },
  { type: 'button',  id: 'save_button',  text: '<b>Confirm</b>', icon: 'dashicons dashicons-plus-alt' }
  ]
});
w2ui.toolbar.on('click', function (event) {
  //console.log('EVENT: '+ event.type + ' TARGET: '+ event.target, event);
  if(event.target == 'new_group'){
    createNewGroup();
    recalculate_order();
  }
  if(event.target.match(/new_from_current:[0-9]*/)){
    var group_id=event.target.split(':')[1];

    createNewGroup(group_id);
    recalculate_order();
  }
  if(event.target.match(/saved_drafts:restore_[0-9]*/)){
    var draft_id=event.target.split(':restore_')[1];
    projectFromDraft(draft_id);
  }
  if(event.target.match(/saved_drafts:remove_[0-9]*/)){
    var draft_id=event.target.split(':remove_')[1];
    removeDraft(draft_id);
  }
    if(event.target == 'save_button'){
    var league_multiplier= w2ui.toolbar.get('league_multiplier').value;
    var league_name= w2ui.toolbar.get('league_name').value;
    var saveAsDraft= w2ui.toolbar.get('save_as_draft').checked;
    if(saveAsDraft) saveDraft();
    else saveLeague();
  }
});
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

$(function(){
});
</script>
