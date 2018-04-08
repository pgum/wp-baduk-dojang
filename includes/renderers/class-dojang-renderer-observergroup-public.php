<?php
class Dojang_Renderer_ObserverGroup_Public{
  public $groupDetails;
  public $groupPlayers;
  public $groupResults;
  public function __construct($groupObject){
    $this->groupDetails= $groupObject->groupDetails;
    $this->groupPlayers= $groupObject->groupPlayers;
  }
  public function renderGroupInfo(){
    return '<h4>'.$this->groupDetails->groupName.'</h4>';
  }
  public function renderGroupTable(){
    $html= $this->renderResultsHeader();
    $html.= $this->renderPlayersResults();
    return '<table class="dojang-table dojang-group-table">'.$html.'</table>';
  }
  private function renderResultsHeader(){
    $html= '<tr><th class="dojang-resultNum">#</th><th class="dojang-resultName">Name</th><th class="dojang-resultNick">Nick</th></tr>';
    return '<thead>'.$html.'</thead>';
  }
  private function renderPlayerDetailsCells($player,$i){
    $html='<td>'.($i).'</td>';
    $html.='<td>'.($player->playerDetails->playerName).'</td>';
    $html.='<td>'.($player->playerDetails->playerKgs).'</td>';
    return $html;
  }
  private function renderPlayerRow($player, $i){
    $html= $this->renderPlayerDetailsCells($player, $i);
    return '<tr>'.$html.'</tr>';
  }
  private function renderPlayersResults(){
    $i=1;
    foreach($this->groupPlayers as $p){
      $html.= $this->renderPlayerRow($p,$i);
      $i++;
    }
    return '<tbody>'.$html.'</tbody>';
  }
}
?>
