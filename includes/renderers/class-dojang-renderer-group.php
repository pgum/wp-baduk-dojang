<?php
class Dojang_Renderer_Results{
  private $p;
  private $r;
  private function ifPlayerWonGame($p, $r){
    return ($p->playerId == $r->playerIdWinner);
  }
  private function ifPlayerPlayedGame($p, $r){
    return ($r->playerIdWhite == $p->playerId or $r->playerIdBlack == $p->playerId);
  }
  private function ifPlayerPlayedOpponent($p, $o, $r){
    return (($r->playerIdWhite == $p->playerId and $r->playerIdBlack == $o->playerId) or
            ($r->playerIdBlack == $p->playerId and $r->playerIdWhite == $o->playerId));
  }
  public function __construct($players, $results){
    $this->p= $players;
    $this->r= $results;
  }
  public function getWinCount($player){
    $count=0;
    foreach($this->r as $r)
      if($this->ifPlayerPlayedGame($player, $r) and $this->ifPlayerWonGame($player, $r))
        $count++;
    return '<td>'.$count.'</td>';
  }
  public function getLossCount($player){
    $count=0;
    foreach($this->r as $r)
      if($this->ifPlayerPlayedGame($player, $r) and !$this->ifPlayerWonGame($player, $r))
        $count++;
    return '<td>'.$count.'</td>';
  }
  public function getResultBetween($player, $opponent){
    $html=' ';
    if($player->playerId == $opponent->playerId)
      $html='X';
    else
      foreach($this->r as $r)
        if($this->ifPlayerPlayedOpponent($player, $opponent, $r)){
          if($this->ifPlayerWonGame($player, $r)){
            $html='W';
            break;
          }
          else{
            $html='L';
            break;
          }
        }
    return '<td>'.$html.'</td>';
  }
}

class Dojang_Renderer_Group{
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
    return '<table>'.$html.'</table>';//.print_r($this->groupResults,true);
  }
  private function renderResultsHeader(){
    $html.= '<tr><td>#</td><td>Name</td><td>Nick</td> ';
    foreach($this->groupPlayers as $p)
      $html.= '<td class="dojang-resultColumn">'.$p->playerDetails->playerInitials.'</td>';
    $html.= ' <td>Win</td><td>Loss</td><td>Score</td><td>WwT?</td></tr>';
    return '<thead>'.$html.'</thead>';
  }
  private function renderPlayerDetailsCells($player){
    $html.='<td>'.($player->playerDetails->tableOrder+1).'</td>';
    $html.='<td>'.($player->playerDetails->playerName).'</td>';
    $html.='<td>'.($player->playerDetails->playerKgs).'</td>';
    return $html;
  }
  private function renderPlayerResultsCells($player){
    foreach($this->groupPlayers as $p)
      $html.= $this->groupResultsRenderer->getResultBetween($player, $p);
    $html.= $this->groupResultsRenderer->getWinCount($player).$this->groupResultsRenderer->getLossCount($player).'<td>Sc</td><td>WT</td>';
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
