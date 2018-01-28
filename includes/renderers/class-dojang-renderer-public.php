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
    $html.='<h3 class="dojang-results'.$archiveClass.'" x-league-id="'.$leagueId.'">'.$leagueName.' Groups Standings</h3>';
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
    $html.='<td>'.$i++.'</td><td>'.$playerName.'</td><td>'.$playerCountry.'</td><td>'.$playerKgs.'</td><td>'.$playerRank.'</td><td>'.$playerGroupName.'</td>';
    return '<tr>'.$html.'</tr>';
  }
  public function renderCurrentPlayers(){
    $league= new Dojang_League();
    $groupArray= $league->getGroupsDetails();
    $leagueName= $league->getLeagueInfo()->leagueName;
    $html.='<h3 class="dojang-players">'.$leagueName.' League Players List</h3>';
    $html.='<table class="dojang-table dojang-player-list">';
    $html.='<thead><tr><th>#</th><th>Player Name</th><th>Country</th><th>Nickname</th><th>Rank</th><th>Group</th></tr></thead><tbody>';
    $i=1;
    foreach ($groupArray as $group){
      $players= $group->groupPlayers;
      $groupName= $group->groupDetails->groupName;
      foreach($players as $player)
        $html.= $this->renederPlayerRow($player, $groupName);
    }
    $html.='</tbody></table>';
    return $html;

  }
  public function renderScoreboard(){
    $options= get_option('dojangoptions');
    $eligable_points = $options['dojang_eligable'] != '' ? $options['dojang_eligable'] : 100;
    $players= new Dojang_Players();
    $scoreboard= $players->getScoreboard();
    $html.= '<h3 class="dojang-scoreboard">Players Scoreboard</h3>';
    $html.='<table class="dojang-table dojang-player-list">';
    $html.='<thead><tr><th>#</th><th>Player Name</th><th>Country</th><th>Nickname</th><th>Rank</th><th>Points</th></tr></thead><tbody>';
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
