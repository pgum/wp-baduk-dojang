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
				<div id="dojang-workspace"></div>
			</div>
</div>
<script>
$('#dojang-toolbar').w2toolbar({
		name: 'toolbar',
		items: [
			{ type: 'button', id: 'new_group', text: 'New Empty Group', icon: 'dashicons dashicons-plus' },
			{ type: 'break' },
			{ type: 'html' html: 'Import from Current League:'},
			{ type: 'button', id: 'from_group_91', text: 'Group A', icon: 'dashicons dashicons-form' },
			{ type: 'button', id: 'from_group_92', text: 'Group B', icon: 'dashicons dashicons-form' },
			{ type: 'button', id: 'from_group_93', text: 'Group C', icon: 'dashicons dashicons-form' },
			{ type: 'button', id: 'from_group_94', text: 'Group D', icon: 'dashicons dashicons-form' },
			{ type: 'break' },
			{ type: 'menu-check', id: 'item3', text: 'Menu Check', icon: 'dashicons dashicons-heart',
				selected: ['id3', 'id4'],
				onRefresh: function (event) {
					event.item.count = event.item.selected.length;
				},
				items: [
					{ id: 'id1', text: 'Item 1', icon: 'dashicons dashicons-camera' },
					{ id: 'id2', text: 'Item 2', icon: 'dashicons dashicons-menu' },
					{ id: 'id3', text: 'Item 3', icon: 'dashicons dashicons-menu', count: 12 },
					{ text: '--' },
					{ id: 'id4', text: 'Item 4', icon: 'dashicons dashicons-admin-tools' }
				]
			},
			{ type: 'break' },
			{ type: 'spacer' },
			//{ type: 'check', id: 'close_upon_save', text: 'Close Current League upon Saving', icon: 'dashicons dashicons-star-filled' },
			{ type: 'check', id: 'save_draft', text: 'Save as Draft', icon: 'dashicons dashicons-admin-post' },
			{ type: 'break' },
			{ type: 'button',  id: 'publish_league',  text: 'Save League', icon: 'dashicons dashicons-plus-alt' }
	]
});

w2ui.toolbar.on('*', function (event) {
		console.log('EVENT: '+ event.type + ' TARGET: '+ event.target, event);
});

</script>
