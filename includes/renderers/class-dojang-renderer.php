<?php
class Dojang_Renderer{
  public function renderLeagueInfo($leagueInfo){
    $html='<h3>'.$leagueInfo->leagueName.'</h3>';
    $html.='<span>League Properties: hidden:'.$leagueInfo->hidden.' closed:'.$leagueInfo->closed.'</br></span>';
    $html.='<span>League Id:'.$leagueInfo->id.' pointsDistributed:'.$leagueInfo->pointsDistributed.'</br></span>';
    return $html;
  }
  public function renderGroupTable($groupObject){
    $groupDetails= $groupObject->groupDetails;
    $groupPlayers= $groupObject->groupPlayers;
    return print_r($groupObject);
  }
  public function renderGroupsTable($groupsArray){
    $html='<h3>Groups Table Renderer</h3>';
    foreach ($groupsArray as $group){
      $html.= '<h4>'.$group->groupDetails->groupName.'</h4>';
      $html.= '<p>Players list<ol>';
      foreach($group->groupPlayers as $p){
        $html.='<li>'.$p->playerId.' - '.$p->groupPlayerDetails->playerDetails->playerName.' - '.$p->groupPlayerDetails->playerDetails->playerRank.'</li>';
      }
      $html.= '</ol></p>';
      $html.= print_r($group, true);
    }
    return $html;
  }
  public function debugData($leagueObject){
    return print_r($leagueObject,true);
  }
}
?>
