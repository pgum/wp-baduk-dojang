<?php
class Dojang_Renderer{
  public $league;
  public function __construct($leagueObject){
    $this->league= $leagueObject;
  }
  public function renderLeagueInfo(){
    $leagueInfo= $this->league->getLeagueInfo();
    $html='<h2><span class="dashicons dashicons-sticky"></span>'.$leagueInfo->leagueName.' - League Details</h2>';
    $html.='<span>League Properties: hidden:'.$leagueInfo->hidden.' closed:'.$leagueInfo->closed.'</br></span>';
    $html.='<span>League Id:'.$leagueInfo->id.' pointsDistributed:'.$leagueInfo->pointsDistributed.'</br></span>';
    return $html;
  }
  private function renderResultToApprovePlayers($black, $white, $winner){
    if($black == $winner)
      return '<td class="dojang-result-to-approve-winner">'.$black.'</td><td>'.$white.'</td>';
    return '<td>'.$black.'</td><td class="dojang-result-to-approve-winner">'.$white.'</td>';
  }
  public function renderGamesToApproveTable(){
    $gamesToApproveArray= $this->league->getGamesToApprove();
    $html='<h2><span class="dashicons dashicons-flag"></span>Games to approve</h2>';
    $html.='<table class="dojang-games-to-approve"><thead><th>#</th><th>Black</th><th>White</th><th>Add Date</th><th>Approve</th><th>Remove</th></thead><tbody>';
    $i=1;
    foreach($gamesToApproveArray as $game){
      $html.='<tr x-result-id="'.$game->id.'">
      <td>'.$i.'</td>'.
      $this->renderResultToApprovePlayers($game->playerIdBlack, $game->playerIdWhite, $game->playerIdWinner).
      '<td>'.$game->addDate.'</td>
      <td>'.'<a href="#" class="button secondary">Approve Game</a>'.'</td>
      <td>'.'<a href="#" class="button button-red">Reject Game</a>'.'</td>
      </tr>';
      $i++;
    }
    $html.='</tbody></table>';
    return $html;
  }
  public function renderGroupsTable(){
    $html='<h2><span class="dashicons dashicons-forms"></span> Group Standings</h2>';
    $groupArray= $this->league->getGroupsDetails();
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='<br/>';
    }
    return $html;
  }
}
?>
