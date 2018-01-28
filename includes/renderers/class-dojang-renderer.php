<?php
class Dojang_Renderer{
  public $league;
  public function __construct($leagueObject= NULL){
    $this->league= $leagueObject;
  }
  public function renderLeagueCloseAndDistributePoints(){
    $leagueInfo= $this->league->getLeagueInfo();
    if($leagueInfo->pointsDistributed == 0)
      $html='<a href="#'.$leagueInfo->id.'" class="button button-secondary dojang-distribute" x-league-id="'.$leagueInfo->id.'">Close League And Save Players Points</a>';
    else
      $html='<span class="button button-secondary button-disabled" x-league-id="'.$leagueInfo->id.'">League is Closed and Players got their points!</span>';
    return $html;
  }
  private function renderLeaguePointsInput($multiplier, $leagueId){
    $html.= '<input class="dojang-league-points-input" value="'.$multiplier.'" x-league-id="'.$leagueId.'"/>';
    $html.= '<a href="#" class="dojang-update-league-points button button-secondary" x-league-id="'.$leagueId.'">Update</a>';
    return $html;
  }
  private function renderLeagueInfoLine($msg){
    return '<span class="dojang-league-info">'.$msg.'</span>';
  }
  public function renderLeagueInfo(){
    $leagueInfo= $this->league->getLeagueInfo();
    $html.='<a class="dojang-anchor" name="'.$leagueInfo->id.'"></a><br/>';
    $leagueClosed= $leagueInfo->closed;
    $archiveClass= ($leagueClosed==1) ? '-archive dojang-expand' : '';
    $html.='<h3 class="dojang-results'.$archiveClass.'" x-league-id="'.$leagueInfo->id.'"><span class="dashicons dashicons-sticky"></span>'.$leagueInfo->leagueName.' - League Details</h3>';
    //$html.='<span>League Properties: hidden:'.$leagueInfo->hidden.' closed:'.$leagueInfo->closed.'</br></span>';
    //$html.='<span>League Id:'.$leagueInfo->id.' pointsDistributed:'.$leagueInfo->pointsDistributed.'</br></span>';
    //$html.='<span>League Points Multiplier:'.$leagueInfo->multiplier.'</br></span>';
    $html.='<div class="dojang-league-properties" x-league-id="'.$leagueInfo->id.'">';
    if($leagueInfo->closed) $html.= $this->renderLeagueInfoLine('League is closed. Its visible in league archives.');
    else $html.= $this->renderLeagueInfoLine('League is open and is visible as Current League.');
    if($leagueInfo->pointsDistributed){
      $html.= $this->renderLeagueInfoLine('Player league points were distributed. League is in read-only state.');
      $html.= $this->renderLeagueInfoLine('League points multiplier: '.$leagueInfo->multiplier);
    }
    else{
      $html.= $this->renderLeagueInfoLine('League points are to distributed. You can change League multiplier, and check players that won with teacher.');
      $html.= $this->renderLeagueInfoLine('League points multiplier: '.$this->renderLeaguePointsInput($leagueInfo->multiplier, $leagueInfo->id));
    }
    $html.='</div>';
    return $html;
  }
  private function renderResultToApprovePlayers($black, $white, $winner){
    if($black == $winner)
      return '<td class="dojang-result-to-approve-winner">'.$black.'</td><td>'.$white.'</td>';
    return '<td>'.$black.'</td><td class="dojang-result-to-approve-winner">'.$white.'</td>';
  }
  public function renderGamesToApproveTable(){
    $gamesToApproveArray= $this->league->getGamesToApprove();
    $html='<h3><span class="dashicons dashicons-flag"></span>Games to approve</h3>';
    $html.='<table class="dojang-table-to-approve"><thead><th>#</th><th>Black</th><th>White</th><th>Add Date</th><th>Approve</th><th>Reject</th></thead><tbody>';
    $i=1;
    foreach($gamesToApproveArray as $game){
      $html.='<tr x-result-id="'.$game->id.'">
      <td>'.$i.'</td>'.
      $this->renderResultToApprovePlayers($game->playerIdBlack, $game->playerIdWhite, $game->playerIdWinner).
      '<td>'.$game->addDate.'</td>
      <td>'.'<a href="#" class="button secondary dojang-approve-result" x-result-id="'.$game->id.'">Approve Game</a>'.'</td>
      <td>'.'<a href="#" class="button button-red dojang-remove-result" x-result-id="'.$game->id.'">Reject Game</a>'.'</td>
      </tr>';
      $i++;
    }
    $html.='</tbody></table>';
    return $html;
  }
  public function renderGroupsTable(){
    $html='<h3><span class="dashicons dashicons-forms"></span> Group Standings</h3>';
    $groupArray= $this->league->getGroupsDetails();
    $pointsDistributed= $this->league->getLeagueInfo()->pointsDistributed;
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group($group, $pointsDistributed);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
    }
    return $html;
  }
  public function renderPlayersToApprove(){
    $players= new Dojang_Players();
    $playersToApprove= $players->getPlayersToApprove();
    $html.='<table class="dojang-table-to-approve dojang-player-list">';
    $html.='<thead><tr><th>Player Name</th><th>Country</th><th>Nickname</th><th>Rank</th><th>E-mail</th><th>Approve</th><th>Reject</th></tr></thead><tbody>';
    foreach($playersToApprove as $p){
      $pid      = $p['playerId'];
      $pname    = $p['playerName'] ==     '' ? '&nbsp;' : $p['playerName'];
      $pcountry = $p['playerCountry'] ==  '' ? '&nbsp;' : $p['playerCountry'];
      $pkgs     = $p['playerKgs'] ==      '' ? '&nbsp;' : $p['playerKgs'];
      $prank    = $p['playerRank'] ==     '' ? '&nbsp;' : $p['playerRank'];
      $pemail   = $p['playerEmail'] ==    '' ? '&nbsp;' : $p['playerEmail'];

      $html.='<tr x-player-id="'.$p['playerId'].'">';
      $html.='<td>'.$pname.'</td>';
      $html.='<td>'.$pcountry.'</td>';
      $html.='<td>'.$pkgs.'</td>';
      $html.='<td>'.$prank.'</td>';
      $html.='<td>'.$pemail.'</td>';
      $html.='<td>'.'<a href="#" class="button secondary dojang-approve-player" x-player-id="'.$pid.'">Approve Player</a>'.'</td>';
      $html.='<td>'.'<a href="#" class="button button-red dojang-remove-player" x-player-id="'.$pid.'">Reject Player</a>'.'</td>';
      $html.='</tr>';
    }
    $html.='</tbody></table>';
    return $html;

  }
  public function renderPlayersMgmt(){
    $players= new Dojang_Players();
    $playersData= $players->getAllPlayersData();
    $html.='<table class="dojang-table dojang-player-list dojang-editable">';
    $html.='<thead><tr><th>Id</th><th>Player Name</th><th>Country</th><th>Nickname</th><th>Rank</th><th>E-mail</th><th>Timezone</th><th>Approved?</th></tr></thead><tbody>';
    foreach($playersData as $p){
      $pname    = $p['playerName'] ==     '' ? '&nbsp;' : $p['playerName'];
      $pcountry = $p['playerCountry'] ==  '' ? '&nbsp;' : $p['playerCountry'];
      $pkgs     = $p['playerKgs'] ==      '' ? '&nbsp;' : $p['playerKgs'];
      $prank    = $p['playerRank'] ==     '' ? '&nbsp;' : $p['playerRank'];
      $pemail   = $p['playerEmail'] ==    '' ? '&nbsp;' : $p['playerEmail'];
      $ptimezone= $p['playerTimezone'] == '' ? '&nbsp;' : $p['playerTimezone'];
      $papproved= $p['playerApproved'] == '' ? '&nbsp;' : $p['playerApproved'];
      $html.='<tr x-player-id="'.$p['playerId'].'">';
      $html.='<td><a name="pid-'.$p['playerId'].'"></a>'.$p['playerId'].'</td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerName">'.$pname.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerCountry">'.$pcountry.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerKgs">'.$pkgs.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerRank">'.$prank.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerEmail">'.$pemail.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerTimezone">'.$ptimezone.'</div></td>';
      $html.='<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="playerApproved">'.$papproved.'</div></td>';
      $html.='</tr>';
    }
    $html.='</tbody></table>';
    return $html;
  }

}
?>
