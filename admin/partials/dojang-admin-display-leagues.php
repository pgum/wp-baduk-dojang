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
$archiveLeaguesIds= $wpdb->get_col("SELECT id AS pastLeagues FROM {$wpdb->prefix}leagues WHERE closed = 1 ORDER BY pastLeagues DESC");
$html='';
foreach($archiveLeaguesIds as $leagueId){
	$league= new Dojang_League($leagueId);
	$renderer= new Dojang_Renderer($league);
	$html.=$renderer->renderLeagueInfo().'<br/>';
	$leagueClosed= $league->getLeagueInfo()->closed;
	$archiveClass= ($leagueClosed==1) ? '-archive dojang-hidden' : '';
	$html.='<div class="dojang-league-groups-results-tables'.$archiveClass.'" x-league-id="'.$leagueId.'">';
	$html.=$renderer->renderLeagueCloseAndDistributePoints().'<br/>';
	$html.=$renderer->renderGroupsTable().'<br/>';
	$html.='</div>';
}
echo $html;

?>
<span class="dojang-footer">Made with <span class="dashicons dashicons-heart" style="color: red"></span> by Piotr</span>
</div>
