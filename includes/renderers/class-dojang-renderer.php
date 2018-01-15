<?php
class Dojang_Renderer{
  public function renderLeagueDetails($leagueObject){
    $leagueInfo=$leagueObject[0];
    $html='<h3>'.$leagueInfo['leagueName'].'</h3>';
    $html.='<span>League Properties: hidden:'.$leagueInfo['hidden'].' closed:'.$leagueInfo['closed'].'</br></span>';
    $html.='<span>League Id:'.$leagueInfo['id'].' pointsDistributed:'.$leagueInfo['pointsDistributed'].'</br></span>';
    return $html;
  }
  public function renderGroupTable($groupObject){
    $groupDetails= $groupObject->groupDetails;
    $groupPlayers= $groupObject->groupPlayers;
    return print_r(array($groupDetails,$groupPlayers));
  }
  public function renderGroupsTable($groupsArray){
    $html='<h3>Groups Table Renderer</h3>';
    foreach ($groupsArray as $group){
      $html.= "<h4>Group Object</h4>";
      $html.= $this->renderGroupTable($group);
    }
  }
  public function debugData($leagueObject){
    return print_r($leagueObject,true);
  }
}
?>
