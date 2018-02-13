<?php
class Dojang_Renderer_Public{
  public function renderPlayerNotRegisteredNotice($msg){
    $registerRenderer= new Dojang_Renderer_Registration();
    return $registerRenderer->renderPlayerNotRegisteredNotice($msg);
  }
  public function renderPlayerRegisteredNotice(){
    $registerRenderer= new Dojang_Renderer_Registration();
    return $registerRenderer->renderPlayerRegisteredNotice();
  }
  public function renderGameNotSubmittedNotice($msg){
    $registerRenderer= new Dojang_Renderer_Submit_Game();
    return $registerRenderer->renderGameNotSubmittedNotice($msg);
  }
  public function renderGameSubmittedNotice(){
    $registerRenderer= new Dojang_Renderer_Submit_Game();
    return $registerRenderer->renderGameSubmittedNotice();
  }
  public function renderRegisterForm(){
    $registerRenderer= new Dojang_Renderer_Registration();
    return $registerRenderer->renderRegisterForm();
  }
  public function renderSubmitResultForm(){
    $submitGameRenderer= new Dojang_Renderer_Submit_Game();
    return $submitGameRenderer->renderSubmitResultForm();
  }
  public function renderLeague($league){
    $groupArray= $league->getGroupsDetails();
    $leagueName= $league->getLeagueInfo()->leagueName;
    $leagueClosed= $league->getLeagueInfo()->closed;
    $archiveClass= ($leagueClosed==1) ? '-archive dojang-expand' : '';
    $hideResultTableArchiveClass= ($leagueClosed==1) ? 'dojang-hidden' : '';
    $leagueId= $league->leagueId;
    if($leagueClosed==1) $html.='<h3 class="dojang-results'.$archiveClass.'" x-league-id="'.$leagueId.'">'.$leagueName.' Groups Results</h3>';
    $html.= '<div class="dojang-league-groups-results-tables'.$archiveClass.' '.$hideResultTableArchiveClass.'" x-league-id="'.$leagueId.'">';
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group_Public($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='<br/>';
    }
    $html.='</div>';
    return $html;
  }
  public function renderCurrentLeague(){
    $league= new Dojang_League();
    return $this->renderLeague($league);
  }
  private function renederPlayerRow($player, $playerGroupName){
    $playerName= $player->playerDetails->playerName;
    $playerCountry= $player->playerDetails->playerCountry;
    $playerKgs= $player->playerDetails->playerKgs;
    $playerRank= $player->playerDetails->playerRank;
    $html.='<td>'.$playerName.'</td><td>'.$playerCountry.'</td><td>'.$playerKgs.'</td><td>'.$playerRank.'</td><td>'.$playerGroupName.'</td>';
    return $html;
  }
  public function renderCurrentPlayers(){
    $league= new Dojang_League();
    $groupArray= $league->getGroupsDetails();
    $leagueName= $league->getLeagueInfo()->leagueName;
    $html.='<table class="dojang-table dojang-player-list">';
    $html.='<thead><tr><th>#</th>';
    $html.='<th>Player Name</th>';
    $html.='<th>Country</th>';
    $html.='<th>Nickname</th>';
    $html.='<th>Rank</th>';
    $html.='<th>Group</th>';
    $html.='</tr></thead><tbody>';
    $i=1;
    foreach ($groupArray as $group){
      $players= $group->groupPlayers;
      $groupName= $group->groupDetails->groupName;
      foreach($players as $player)
        $html.= '<tr>'.'<td>'.$i++.'</td>'.$this->renederPlayerRow($player, $groupName).'</tr>';
    }
    $html.='</tbody></table>';
    return $html;

  }
  public function renderScoreboard(){
    $options= get_option('dojangoptions');
    $eligable_points = $options['dojang_eligable'] != '' ? $options['dojang_eligable'] : 100;
    $players= new Dojang_Players();
    $scoreboard= $players->getScoreboard();
    $html.='<table class="dojang-table dojang-player-list">';
    $html.='<thead><tr><th class="dojang-num">#</th>';
    $html.='<th class="dojang-playerName">Player Name</th>';
    $html.='<th class="dojang-playerCountry">Country</th>';
    $html.='<th class="dojang-playerKgs">Nickname</th>';
    $html.='<th class="dojang-playerRank">Rank</th>';
    $html.='<th class="dojang-playerPoints">Points</th></tr></thead><tbody>';
    $i=1;
    foreach($scoreboard as $s){
      $html.='<tr '.($s['playerPoints'] >= $eligable_points ? 'class="dojang-eligable"' : '').'><td>'.$i++.'</td><td>'.$s['playerName'].'</td><td>'.$s['playerCountry'].'</td>';
      $html.='<td>'.$s['playerKgs'].'</td><td>'.$s['playerRank'].'</td><td>'.$s['playerPoints'].'</td></tr>';
    }
    $html.='</tbody></table>';
    return $html;
  }
}
 ?>
