<?php
class Dojang_Renderer{
  public $league;
  public function __construct($leagueObject){
    $this->league= $leagueObject;
  }
  public function renderLeagueInfo(){
    $leagueInfo= $this->league->getLeagueInfo();
    $html='<h3>'.$leagueInfo->leagueName.'</h3>';
    $html.='<span>League Properties: hidden:'.$leagueInfo->hidden.' closed:'.$leagueInfo->closed.'</br></span>';
    $html.='<span>League Id:'.$leagueInfo->id.' pointsDistributed:'.$leagueInfo->pointsDistributed.'</br></span>';
    return $html;
  }
  public function renderGroupsTable($groupsArray){
    $html='<h3>Groups Renderer</h3>';
    foreach ($groupsArray as $group){
      $gR = new Dojang_Renderer_Group($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='<br/>';
    }
    return $html;
  }
}
?>
