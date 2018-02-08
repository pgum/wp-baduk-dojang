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
	    <h2><span class="dashicons dashicons-groups"></span><?php echo esc_html( get_admin_page_title() ); ?></h2>
	    <?php
				$renderer= new Dojang_Renderer();
        echo '<h3 class="button button-secondary dojang-toggle-add">Add new Player</h3><br/>';
        echo $renderer->renderAddPlayer();
        echo '<h3>Players Edit List</h3>';
				echo $renderer->renderPlayersMgmt();
			 ?>
</div>
