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
?>
