<?php
class Dojang_Renderer_Group{
  public $groupDetails;
  public $groupPlayers;
  public $groupResults;
  public $renderedGroupResults;
  public $pointsDistributed;
  public function __construct($groupObject, $pointsDistributed){
    $this->groupDetails= $groupObject->groupDetails;
    $this->groupPlayers= $groupObject->groupPlayers;
    $this->groupResults= $groupObject->groupResults;
    $this->pointsDistributed = $pointsDistributed;
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
    $html.= ' <th><span class="dashicons dashicons-awards"></span></th><th><span class="dashicons dashicons-welcome-learn-more"></span></th><th>Score</th></tr>';
    return '<thead>'.$html.'</thead>';
  }
  private function renderPlayerDetailsCells($player){
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
    $html.= '<td>'.$player->win.'</td><td>'.$player->loss.'</td>';
    $html.= '<td class="dojang-place-'.$player->place.'">#'.$player->place.'</td><td class="won-with-teacher-cell" x-groupplayer-id="'.$player->id.'">';
    $html.= '<input type="checkbox" '.($this->pointsDistributed == 1 ? 'disabled="disabled"' : '').' class="dojang-player-won-against-teacher" '.$this->renderCheckboxChecked($player->wonAgainstTeacher).'x-groupplayer-id="'.$player->id.'"/></td>';
    $html.= '<td>'.$player->leaguePoints.'</td>';
    return $html;
  }
  private function renderPlayerRow($player){
    $html.= $this->renderPlayerDetailsCells($player);
    $html.= $this->renderPlayerResultsCells($player);
    return $html;
  }
  private function renderPlayersResults(){
    $i=0;
    foreach($this->groupPlayers as $p){
      $i++;
      $html.='<tr>'.'<td>'.$i.'</td>';
      $html.= $this->renderPlayerRow($p).'</tr>';
    }
    return '<tbody>'.$html.'</tbody>';
  }
}
?>
