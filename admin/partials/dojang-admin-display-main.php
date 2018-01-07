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
	$results= getLeagueResultsArray($leagueId, $conn);
	$r='<div id="results-to-approve-table">';
  foreach($results["groups"] as $g){
    $gr='<h2>'.$g['groupName'].'</h2>';
    $gr.='<table class="stdtable" id="leagueB-admin-overview-results-to-approve-table-'.str_replace(' ', '-', $g['groupName']).'">';
    $gr.='<thead>
        <colgroup>
            <col class="con0" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
        </colgroup>';
    $gr.='<thead>';
		$gr.='<tr>
						<td class="head1">#</td>
						<td class="head0">Black</td>
						<td class="head1">White</td>
						<td class="head0">Add Date</td>
						<td class="head1">Approve Result</td>
						<td class="head0">Remove Result</td>
					</tr>';
		$gr.='</thead>';
    $numberOfResultsToApprove=0;
    foreach($g['groupResults'] as $re){
      if(!$re['isApproved']){
        $numberOfResultsToApprove++;
        $classB= $re['playerIdWinner'] == $re['playerIdBlack'] ? "leagueB-winner" : "";
        $classW= $re['playerIdWinner'] == $re['playerIdWhite'] ? "leagueB-winner" : "";
        
        $gr.= '<tr class="result-to-approve" result-id="'.$re['id'].'">'.
             '<td>'.$numberOfResultsToApprove.'</td>'.
             '<td><span class="'.$classB.'">'.getPlayerById($results,$re['playerIdBlack'])['playerName'].'</span></td>'.
             '<td><span class="'.$classW.'">'.getPlayerById($results,$re['playerIdWhite'])['playerName'].'</span></td>'.
             '<td>'.$re['addDate'].'</td>'.
             '<td><a href="#" class="btn btn_book approve-button" result-id="'.$re['id'].'"><span>Approve</span></a></td>'.
             '<td><a href="#" class="btn btn_trash remove-button" result-id="'.$re['id'].'"><span>Remove</span></a></td>'.
             '</tr>';
      }
    }
    if($numberOfResultsToApprove > 0){
      $r.=$gr;
    }
    $r.='</table>';
  }
    $r.='</div>';
  echo $r;
?>    
</div>
