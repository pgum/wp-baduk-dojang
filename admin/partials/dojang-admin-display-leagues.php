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
<?php
global $wpdb;
//TODO: sculpt this more...
$archiveLeaguesIds= $wpdb->get_col("SELECT id AS pastLeagues FROM {$wpdb->prefix}leagues ORDER BY pastLeagues DESC");
$html='';
foreach($archiveLeaguesIds as $leagueId){
	$league= new Dojang_League($leagueId);
	$renderer= new Dojang_Renderer($league);
	$html.=$renderer->renderLeagueInfo().'<br/>';
	$html.=$renderer->renderLeagueCloseAndDistributePoints().'<br/>';
	$html.=$renderer->renderGroupsTable().'<br/>';
}
echo $html;

?>
</div>
