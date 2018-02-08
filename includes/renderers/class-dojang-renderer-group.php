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
    $cls= 'dojang-group-table'.($this->pointsDistributed != 0 ? '-readonly' : '');
    return '<table class="'.$cls.'" x-group-id="'.$this->groupDetails->playerGroupId.'">'.$html.'</table>';//.print_r($this->groupResults,true);
  }
  private function renderResultsHeader(){
    $html.= '<tr><th>#</th><th>Name</th><th>Nick</th> ';
    foreach($this->groupPlayers as $p)
      $html.= '<th class="dojang-resultColumn" x-player-id="'.$p->playerId.'">'.$p->playerDetails->playerInitials.'</th>';
    $html.= ' <th><span class="dashicons dashicons-thumbs-up"></span></th><th><span class="dashicons dashicons-thumbs-down"></span></th>';
    $html.= ' <th><span class="dashicons dashicons-awards"></span></th><th><span class="dashicons dashicons-welcome-learn-more"></span></th><th>Score</th></tr>';
    return '<thead>'.$html.'</thead>';
  }
  private function renderPlayerDetailsCells($player){
    $html.='<td class="dojang-player-name" x-player-id="'.$player->playerId.'">'.($player->playerDetails->playerName).'</td>';
    $html.='<td>'.($player->playerDetails->playerKgs).'</td>';
    return $html;
  }
  private function renderPlayerResultsCells($player){
    foreach($this->groupPlayers as $p)
      $html.= $this->groupResultsRenderer->getResultBetween($player, $p);
    $html.= '<td>'.$player->win.'</td><td>'.$player->loss.'</td>';
    $html.= '<td class="dojang-place-'.$player->place.'">#'.$player->place.'</td><td class="won-with-teacher-cell" x-groupplayer-id="'.$player->id.'">';
    $disabled= ($this->pointsDistributed == 1 ? 'disabled="disabled" ' : '');
    $checked= ($player->wonAgainstTeacher ? 'checked="checked" ' : '');
    $html.= '<input type="checkbox" '.$disabled.' class="dojang-player-won-against-teacher" '.$checked.'x-groupplayer-id="'.$player->id.'"/></td>';
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
    foreach($this->groupPlayers as $p)
      $html.='<tr x-player-id="'.$p->playerId.'">'.'<td>'.++$i.'</td>'.$this->renderPlayerRow($p).'</tr>';
    return '<tbody>'.$html.'</tbody>';
  }
}
?>
