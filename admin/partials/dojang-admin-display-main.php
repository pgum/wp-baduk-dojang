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
<?
$league = new Dojang_League();
$renderer = new Dojang_Renderer($league);
echo $renderer->renderLeagueInfo();
echo $renderer->renderGamesToApproveTable();
echo $renderer->renderGroupsTable();
echo "<br/>";
?>
<span class="">Made with <span class="dashicons dashicons-heart" style="background-color: red"></span> by Piotr</span>
</div>
