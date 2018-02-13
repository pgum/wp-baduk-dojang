<?php
class Dojang_Renderer{
  public $league;
  public function __construct($leagueObject= NULL){
    $this->league= $leagueObject;
  }
  private function renderLeagueCloseAndDistributePoints(){
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
    return '<span class="dojang-league-info">'.$msg.'</span><br/>';
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
      $html.= $this->renderLeagueInfoLine('League points are to calculated but not yet distributed. You can change League multiplier, and check players that won with teacher.');
      $html.= $this->renderLeagueInfoLine('League points multiplier: '.$this->renderLeaguePointsInput($leagueInfo->multiplier, $leagueInfo->id));
    }
    $html.= $this->renderLeagueCloseAndDistributePoints();
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
    $html='<h3><span class="dashicons dashicons-forms"></span> Group Results</h3>';
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
  private function renderEditableCell($rawVal, $fieldName){
    $val = $rawVal == '' ? '&nbsp;' : $rawVal;
    return '<td class="dojang-editable-cell"><div class="dojang-editable-div" x-field="'.$fieldName.'">'.$val.'</div></td>';
  }
  public function renderAddPlayerInput($type){
    if($type == 'submit'){return '<a class="button button-secondary dojang-create-player" href="#"><span class="dashicons dashicons-admin-users"></span>Create Player</a>';}
    if($type == 'playerApproved'){return '<select class="dojang-add-player dojang-add-player-'.$type.'"><option value="0">Not Approved</option><option value="1" selected>Approved</option></select>';}
    return '<input type="text" size="30" class="dojang-add-player dojang-add-player-'.$type.'" />';
  }
  public function renderAddPlayer(){
    $html.='<table class="dojang-table dojang-player-list dojang-editable dojang-add"><tbody>';
    $html.='<tr><td>Player Name</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerName').'</td></tr>';
    $html.='<tr><td>Player Country</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerCountry').'</td></tr>';
    $html.='<tr><td>Player KGS Nickname</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerKgs').'</td></tr>';
    $html.='<tr><td>Player Rank</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerRank').'</td></tr>';
    $html.='<tr><td>Player E-mail</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerEmail').'</td></tr>';
    $html.='<tr><td>Player Timezone</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerTimezone').'</td></tr>';
    $html.='<tr><td>Player Approved</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('playerApproved').'</td></tr>';
    $html.='<tr><td>Create Player</td></tr>';
    $html.='<tr><td>'.$this->renderAddPlayerInput('submit').'</td></tr>';
    $html.='</tbody></table>';
    return $html;
  }
  public function renderPlayersMgmt(){
    $players= new Dojang_Players();
    $playersData= $players->getAllPlayersData();
    $scoreboard= $players->getScoreboard();
    $html.='<table class="dojang-table dojang-player-list dojang-editable">';
    $html.='<thead><tr><th>Id</th>
                       <th>Player Name</th>
                       <th>Country</th>
                       <th>Nickname</th>
                       <th>Rank</th>
                       <th>E-mail</th>
                       <th>Timezone</th>
                       <th>Approved?</th>
                       <th>League Points</th></tr></thead><tbody>';
    foreach($playersData as $p){
      $html.='<tr x-player-id="'.$p['playerId'].'">';
      $html.='<td><a name="pid-'.$p['playerId'].'" class="dojang-anchor"></a>'.$p['playerId'].'</td>';
      $html.= $this->renderEditableCell($p['playerName'], 'playerName');
      $html.= $this->renderEditableCell($p['playerCountry'], 'playerCountry');
      $html.= $this->renderEditableCell($p['playerKgs'], 'playerKgs');
      $html.= $this->renderEditableCell($p['playerRank'], 'playerRank');
      $html.= $this->renderEditableCell($p['playerEmail'], 'playerEmail');
      $html.= $this->renderEditableCell($p['playerTimezone'], 'playerTimezone');
      $html.= $this->renderEditableCell($p['playerApproved'], 'playerApproved');
      $html.='<td>'.$scoreboard[$p['playerId']]['playerPoints'].'</td>';
      $html.='</tr>';
    }
    $html.='</tbody></table>';
    return $html;
  }

}
?>
