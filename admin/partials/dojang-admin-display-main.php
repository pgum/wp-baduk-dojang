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
$leagueInfo= $league->getCurrentLeagueInfo();
$leagueGroups= $league->getGroupsDetails();
$renderer = new Dojang_Renderer();
echo '<h2>Current League Information</h2>';
echo $renderer->renderLeagueInfo($leagueInfo);
echo "<h2>Games to Approve</h2>";
echo $league->getGamesToApprove();
echo "</br>";
echo "<h2>Current League standings</h2>";
echo $renderer->renderGroupsTable($leagueGroups);
echo "<br/>";
?>
</div>
