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
  var last_groups= <?php echo renderLastGroupsData(); ?>;
  var group= $('<div></div>').addClass('dojang-group');
  var group_name_array=['Group A','Group B','Group C','Group D','Group E','Group F','Group G','Group H'];
  var num= $('#dojang-sortable .dojang-group-object').length;
  var group_name= group_name_array[num];
  var element= $('<li class="dojang-group-object"></li>').attr('x-group-order',num);
  var group_object= <?php echo renderGroupObject(); ?>;

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
  } else{ console.log('new group'); }
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
function saveButtonHandler(save_type, l_name, l_multiplier){
  var league_obj= {
    name: l_name,
    multiplier: l_multiplier,
  };
  var l_groups = [];
  $('.dojang-group').each(function(){ l_groups.push(convertGroupObjectToJsObject($(this))); });
  league_obj['groups']= l_groups;
  var dataToSend= {
    save: save_type,
    league: league_obj,
    url: getUrlParameters()
  }
  /* show debug info */
  $('#dojang-debug-container').removeClass('dojang-hidden');
  var dataToSendJson= JSON.stringify(dataToSend,null, 2);
  $('#dojang-debug').text(dataToSendJson);
  $.post({
    url: ajaxurl,
    //dataType: 'json',
    data: {'action': 'dojang_create_league', 'league_data': dataToSendJson},
    success: function(data){ console.log('ajax send!'); $('#dojang-debug').append(data); }
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
  { type: 'button', id: 'new_group', text: 'New Group', icon: 'dashicons dashicons-plus' },
  { type: 'menu', id: 'new_from_current', caption: 'Current Groups', icon: 'dashicons dashicons-forms',
    items: [ <?php echo renderLastGroups();  ?> ] },
  { type: 'break' },
  { type: 'html',  id: 'league_multiplier', value: 1,
    html: function (item) {
    return '<div><span class="dashicons dashicons-no-alt"></span>Multiplier:'+
     '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_multiplier\', { value: this.value });" '+
     'size="3" placeholder="1,2,.." value="'+item.value+'"/></div>'; } },
  { type: 'break' },
  { type: 'html',  id: 'league_name', value: monthNames[d.getMonth()],
    html: function (item) {
      return'<div><span class="dashicons dashicons-format-quote"></span>Name:'+
            '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_name\', { value: this.value });" '+
            'value="'+item.value+'" size="20" placeholder="eg. January..."/></div>'; } },
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
    console.log('Create New Group');
    createNewGroup();
    recalculate_order();
  }
  if(event.target.match(/new_from_current:[0-9]*/)){
    var group_id=event.target.split(':')[1];
    console.log('Group Id to Clone= '+group_id);

    createNewGroup(group_id);
    recalculate_order();
  }
  if(event.target == 'save_button'){
    var league_multiplier= w2ui.toolbar.get('league_multiplier').value;
    var league_name= w2ui.toolbar.get('league_name').value;
    var draft_or_publish= w2ui.toolbar.get('save_as_draft').checked ? 'draft' : 'publish';
    saveButtonHandler(draft_or_publish, league_name, league_multiplier);
  }
});
$('#dojang-sortable').sortable({placeholder: 'dojang-group-placeholder'});
$('#dojang-sortable').on('sortupdate',function( event, ui){console.log('something changed!'); recalculate_order();});

</script>
