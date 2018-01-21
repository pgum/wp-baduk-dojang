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

  private function htmlOpenTdResultTag($result){
    $classResultNotApproved= 'dojang-result-not-approved';
    $classResultApproved= 'dojang-result-approved';
    if($result->isApproved)
      $returnClass= $classResultApproved;
    else
      $returnClass= $classResultNotApproved;
    return '<td class="'.$returnClass.'" x-result-id="'.$result->id.'">';
  }
  private function htmlResultWon(){
    return '<span class="dashicons dashicons-marker"></span>';
  }
  private function htmlResultLost(){
    return '<span class="dashicons dashicons-no"></span>';
  }
  private function htmlResultGray(){
    return '<td class="dojang-result-own" style="background:gray"></td>';
  }
  private function htmlResultNone(){
    return '<td class="dojang-result-none"></td>';
  }

  public function getResultBetween($player, $opponent){
    $html=$this->htmlResultNone();
    if($player->playerId == $opponent->playerId)
      $html=$this->htmlResultGray();
    else
      foreach($this->r as $r)
        if($this->ifPlayerPlayedOpponent($player, $opponent, $r)){
          $html= $this->htmlOpenTdResultTag($r);
          if($this->ifPlayerWonGame($player, $r)){
            $html.=$this->htmlResultWon().'</td>';
            break;
          }
          else{
            $html.=$this->htmlResultLost().'</td>';
            break;
          }
        }
    return $html;
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
    $html.= '<td>#'.$player->place.'</td><td><input type="checkbox" class="dojang-player-won-against-teacher" '.$this->renderCheckboxChecked($player->wonAgainstTeacher).'x-groupplayer-id="'.$player->id.'"/></td><td>'.$player->leaguePoints.'</td>';
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
