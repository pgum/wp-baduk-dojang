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
	<h2><span class="dashicons dashicons-welcome-learn-more"></span><?php echo esc_html( get_admin_page_title() ); ?></h2>
<?
$league = new Dojang_League();
$renderer = new Dojang_Renderer($league);
echo $renderer->renderPlayersToApprove();
echo "<br/>";
echo $renderer->renderLeagueInfo();
echo $renderer->renderGamesToApproveTable();
echo '<div class="dojang-league-groups-results-tables" x-league-id="'.$leagueId.'">';
echo $renderer->renderGroupsTable();
echo '</div>';
echo "<br/>";
?>
</div>
