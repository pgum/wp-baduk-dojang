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
$archiveLeaguesIds= $wpdb->get_col("SELECT id AS pastLeagues FROM {$wpdb->prefix}leagues WHERE closed = 1 ORDER BY pastLeagues DESC");
$html='';
foreach($archiveLeaguesIds as $leagueId){
	$league= new Dojang_League($leagueId);
	$renderer= new Dojang_Renderer($league);
	$leagueClosed= $league->getLeagueInfo()->closed;
	$archiveClass= ($leagueClosed==1) ? '-archive dojang-hidden' : '';
	$html.=$renderer->renderLeagueInfo();
	$html.='<div class="dojang-league-groups-results-tables'.$archiveClass.'" x-league-id="'.$leagueId.'">';
	$html.=$renderer->renderGroupsTable().'<br/>';
	$html.='</div>';
}
echo $html;

?>
</div>
