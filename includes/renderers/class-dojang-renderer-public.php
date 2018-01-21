<?php
class Dojang_Renderer_Public{
  public function renderRegisterForm(){
    return "<h2>Registration Form</h2>";
  }
  public function renderCurrentLeague(){
    $html='<h2><span class="dashicons dashicons-forms"></span> Group Standings</h2>';
    /*$groupArray= $league->getGroupsDetails();
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group_Public($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='a<br/>';
    }*/
    return $html;
  }
  public function renderCurrentPlayers(){
    return "<h2>Current League Players List</h2>";
  }
  public function renderScoreboard(){
    return "<h2>Players Scoreboard</h2>";
  }
  public function renderSubmitResultForm(){
    return "<h2>Submit League game result</h2>";
  }
}
 ?>
