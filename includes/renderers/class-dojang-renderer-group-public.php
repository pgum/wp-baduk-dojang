<?php
class Dojang_Renderer_Group_Public{
  public $groupDetails;
  public $groupPlayers;
  public $groupResults;
  public $renderedGroupResults;
  public function __construct($groupObject){
    $this->groupDetails= $groupObject->groupDetails;
    $this->groupPlayers= $groupObject->groupPlayers;
    $this->groupResults= $groupObject->groupResults;
    $this->groupResultsRenderer= new Dojang_Renderer_Results($this->groupPlayers, $this->groupResults);
  }
  public function renderGroupInfo(){
    return '<h4>'.$this->groupDetails->groupName.'</h4>';
  }
  public function renderGroupTable(){
    $html.= $this->renderResultsHeader();
    $html.= $this->renderPlayersResults();
    return '<table class="dojang-group-table">'.$html.'</table>';//.print_r($this->groupResults,true);
  }
  private function renderResultsHeader(){
    $html.= '<tr><th>#</th><th>Name</th><th>Nick</th> ';
    foreach($this->groupPlayers as $p)
      $html.= '<th class="dojang-resultColumn">'.$p->playerDetails->playerInitials.'</th>';
    $html.= ' <th><span class="dashicons dashicons-thumbs-up"></span></th><th><span class="dashicons dashicons-thumbs-down"></span></th>';
    $html.= ' <th><span class="dashicons dashicons-awards"></span></th></tr>';
    return '<thead>'.$html.'</thead>';
  }
  private function renderPlayerDetailsCells($player){
    $html.='<td>'.($player->tableOrder+1).'</td>';
    $html.='<td>'.($player->playerDetails->playerName).'</td>';
    $html.='<td>'.($player->playerDetails->playerKgs).'</td>';
    return $html;
  }
  private function renderCheckboxChecked($flag){
    $html='';
    if($flag)
      $html='checked ';
    return $html;
  }
  private function renderPlayerResultsCells($player){
    foreach($this->groupPlayers as $p)
      $html.= $this->groupResultsRenderer->getResultBetween($player, $p);
    $html.= $this->groupResultsRenderer->getWinCount($player).$this->groupResultsRenderer->getLossCount($player);
    $html.= '<td>#'.$player->place.'</td>';
    return $html;
  }
  private function renderPlayerRow($player){
    $html.= $this->renderPlayerDetailsCells($player);
    $html.= $this->renderPlayerResultsCells($player);
    return '<tr>'.$html.'</tr>';
  }
  private function renderPlayersResults(){
    foreach($this->groupPlayers as $p)
      $html.= $this->renderPlayerRow($p);
    return '<tbody>'.$html.'</tbody>';
  }
}
?>
