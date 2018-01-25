<?php
class Dojang_Renderer{
  public $league;
  public function __construct($leagueObject){
    $this->league= $leagueObject;
  }
  public function renderLeagueInfo(){
    $leagueInfo= $this->league->getLeagueInfo();
    $html='<h3><span class="dashicons dashicons-sticky"></span>'.$leagueInfo->leagueName.' - League Details</h3>';
    $html.='<span>League Properties: hidden:'.$leagueInfo->hidden.' closed:'.$leagueInfo->closed.'</br></span>';
    $html.='<span>League Id:'.$leagueInfo->id.' pointsDistributed:'.$leagueInfo->pointsDistributed.'</br></span>';
    $html.='<span>League Points Multiplier:'.$leagueInfo->multiplier.'</br></span>';
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
    $html.='<table class="dojang-games-to-approve"><thead><th>#</th><th>Black</th><th>White</th><th>Add Date</th><th>Approve</th><th>Remove</th></thead><tbody>';
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
    $html='<h3><span class="dashicons dashicons-forms"></span> Group Standings</h3>';
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
