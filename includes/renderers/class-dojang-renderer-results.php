<?php
class Dojang_Renderer_Results{
  private $p;
  private $r;
  private $isAdmin;
  private function ifPlayerWonGame($p, $r){
    return ($p->playerId == $r->playerIdWinner and ($r->isApproved > 0 or $this->isAdmin));
  }
  private function ifPlayerPlayedGame($p, $r){
    return ($r->playerIdWhite == $p->playerId or $r->playerIdBlack == $p->playerId) and ($r->isApproved > 0 or $this->isAdmin);
  }
  private function ifPlayerPlayedOpponent($p, $o, $r){
    return (($r->playerIdWhite == $p->playerId and $r->playerIdBlack == $o->playerId) or
            ($r->playerIdBlack == $p->playerId and $r->playerIdWhite == $o->playerId)) and ($r->isApproved > 0 or $this->isAdmin);
  }
  public function __construct($players, $results, $adminView=0){
    $this->p= $players;
    $this->r= $results;
    $this->isAdmin= ($adminView != 0);
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
    $returnClass='';
    if($result->isApproved)
      $returnClass= $classResultApproved;
    else
      $returnClass= $classResultNotApproved;
    if($result->isReviewed)
      $returnClass.=' dojang_reviewed';
    return '<td class="'.$returnClass.'" x-result-id="'.$result->id.'" ';
  }
  private function htmlResultWon(){
    return 'x-result="W"><span class="dashicons dashicons-marker"></span>';
  }
  private function htmlResultLost(){
    return 'x-result="L"><span class="dashicons dashicons-no"></span>';
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
