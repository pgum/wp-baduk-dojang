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
  public function renderRegisterForm(){
    $registerRenderer= new Dojang_Renderer_Registration();
    return $registerRenderer->renderRegisterForm();
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
  public function renderCurrentPlayers(){
    return '<h3 class="dojang-players">Current League Players List</h3>';
  }
  public function renderScoreboard(){
    return '<h3 class="dojang-scoreboard">Players Scoreboard</h3>';
  }
  public function renderSubmitResultForm(){
    return '<h3 class="dojang-submit">Submit League game result</h3>';
  }
}
 ?>
