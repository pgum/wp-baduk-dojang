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
    $html='<div class="dojang-league-results-wrapper">';
    $html.='<h2 class="dojang-results">'.$leagueName.' Groups Standings</h2>';
    $html= '<div class="dojang-league-groups-results-tables">';
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group_Public($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='</div></div><br/>';
    }
    return $html;
  }
  public function renderCurrentLeague(){
    $league= new Dojang_League();
    return $this->renderLeague($league);
  }
  public function renderCurrentPlayers(){
    return '<h2 class="dojang-players">Current League Players List</h2>';
  }
  public function renderScoreboard(){
    return '<h2 class="dojang-scoreboard">Players Scoreboard</h2>';
  }
  public function renderSubmitResultForm(){
    return '<h2 class="dojang-submit">Submit League game result</h2>';
  }
}
 ?>
