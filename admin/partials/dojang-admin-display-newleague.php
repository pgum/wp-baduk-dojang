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
				<div id="dojang-workspace">
					<ul id="dojang-sortable">
						<li>
							<div class="dojang_group"></div>
						</li>
						<li>
							<div class="dojang_group"></div>
						</li>
						<li>
							<div class="dojang_group"></div>
						</li>
						<li>
							<div class="dojang_group"></div>
						</li>
						<li>
							<div class="dojang_group"></div>
						</li>
					</ul>
				</div>
			</div>
</div>
<?php
//function areSavedDrafts(){
//	return 0;
//}
?>
<script>

var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];
var d = new Date();
//var hideSavedDrafts= <?php /*areSavedDrafts();*/ ?> != 0 ? true : false;
$('#dojang-toolbar').w2toolbar({
		name: 'toolbar',
		items: [
			{ type: 'button', id: 'new_group', text: 'New Group', icon: 'dashicons dashicons-plus' },
			{ type: 'menu', id: 'new_from_current', caption: 'Current Groups', icon: 'dashicons dashicons-forms',
				items: [
<?php
$currentLeague = new Dojang_League();
$groups = $currentLeague->getGroupsDetails();
foreach($groups as $g)
	echo '{ id:\''.$g->groupDetails->playerGroupId.'\', text: \''.$g->groupDetails->groupName.'\', img: \'dashicons dashicons-arrow-right\' },';
 ?>
	      ]
		  },
			{ type: 'break' },
			{ type: 'html',  id: 'league_multiplier', value: 1,
				html: function (item) {
					return '<div><span class="dashicons dashicons-no-alt"></span>Multiplier:'+
								 '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_multiplier\', { value: this.value });" '+
								 'size="3" placeholder="1,2,.." value="'+item.value+'"/></div>';
				}
			},
			{ type: 'break' },
			{ type: 'html',  id: 'league_name', value: monthNames[d.getMonth()],
				html: function (item) {
					return '<div><span class="dashicons dashicons-format-quote"></span>Name:'+
								 '<input class="dojang-toolbar-input" onchange="var el = w2ui.toolbar.set(\'league_name\', { value: this.value });" '+
								 'value="'+item.value+'" size="20" placeholder="eg. January..."/></div>';
				}
			},
			{ type: 'spacer' },
			{ type: 'html', html: 'Save:'},
			{ type: 'radio', id: 'save_as_draft', group: '1', text: 'as Draft', icon: 'dashicons dashicons-edit', checked: true },
			{ type: 'radio', id: 'save_publish', group: '1', text: 'Publish on save', icon: 'dashicons dashicons-admin-post' },
			{ type: 'break' },
			{ type: 'button',  id: 'save_button',  text: '<b>Confirm</b>', icon: 'dashicons dashicons-plus-alt' }
	]
});

function createNewGroup(gid=null){
	if(gid){console.log(gid);}
	else{console.log('new group');}
}
w2ui.toolbar.on('click', function (event) {
		//console.log('EVENT: '+ event.type + ' TARGET: '+ event.target, event);
		if(event.target == 'new_group'){
			console.log('Create New Group');
			createNewGroup();
		}
		if(event.target.match(/new_from_current:[0-9]*/)){
			var group_id=event.target.split(':')[1];
			console.log('Group Id to Clone= '+group_id);
			createNewGroup(group_id);
		}
		if(event.target == 'save_button'){
			var league_multiplier= w2ui.toolbar.get('league_multiplier').value;
			var league_name= w2ui.toolbar.get('league_name').value;
			var draft_or_publish= w2ui.toolbar.get('save_as_draft').checked ? 'draft' : 'publish';
			console.log('save clicked: name='+league_name+' multiplier='+league_multiplier+' will be: '+draft_or_publish);
		}
});
</script>
