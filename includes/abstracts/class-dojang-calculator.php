<?php
  class Dojang_Calculator{
    public $results;
    public $players;
    public function __construct($players, $results){
      $this->results= $results;
      $this->players= $players;
    }

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
    private function isSameResult($resultsOne, $resultsTwo){
      return ($resultsOne['win'] == $resultsTwo['win'] and $resultsOne['loss'] == $resultsTwo['loss'])
    }
    private function playerResultsComparator($playerOneResults, $playerTwoResults){
      $players_have_equal_results= 0;
      $playerOne_has_better_results= 1;
      $playerTwo_has_better_results=-1;
      if($playerOneResults['win'] == $playerTwoResults['win'])
        if($playerOneResults['loss'] < $playerTwoResults['loss'])
          return $players_have_equal_results;
        else return ($playerOneResults['loss'] < $playerTwoResults['loss']) ? $playerOne_has_better_results : $playerTwo_has_better_results;
      return ($playerOneResults['win'] > $playerTwoResults['win']) ? $playerOne_has_better_results : $playerTwo_has_better_results;
    }

    public function getWinCount($player){
    $count=0;
    foreach($this->results as $r)
      if($this->ifPlayerPlayedGame($player, $r) and $this->ifPlayerWonGame($player, $r))
        $count++;
    return $count;
    }
    public function getLossCount($player){
      $count=0;
      foreach($this->results as $r)
        if($this->ifPlayerPlayedGame($player, $r) and !$this->ifPlayerWonGame($player, $r))
          $count++;
      return $count;
    }
/*
    public function getResultBetween($player, $opponent){
      $result_none= 0;
      $result_self= -1;
      $result_player= $player->playerId;
      $result_opponent= $opponent->playerId;
      $returnResult= $result_none;
      if($player->playerId == $opponent->playerId)
        $returnResult= $result_self;
      else
        foreach($this->results as $result)
          if($this->ifPlayerPlayedOpponent($player, $opponent, $result)){
            if($this->ifPlayerWonGame($player, $result))
              $returnResult= $result_player;
            else
              $returnResult= $result_opponent;
            break;
          }
      return $returnResult;
    }
    */
    public function groupPlayersPlace(){
      $playersPlace= array();
      $playersResults= $this->groupPlayersResults();
      uasort($playersResults, array($this, 'playerResultsComparator'));
      $rank= 1
      $tie_rank= 0;
      $lastResult= array('win' => -1; 'loss' => -1);
      foreach($playersResults as $playerId => $playerResult){
        if(!$this->isSameResult($playerResult, $lastResult)){
          $count= 0;
          $lastResult= $playerResult;
          $playersPlace[$playerId]= $rank;
        }
        else {
          $lastResult= $playerResult;
          $rank--;
          if ($count++ == 0)
            $tie_rank= $rank;
          $playersPlace[$playerId]= $tie_rank;
        }
        $rank++;
      }
    return $playersPlace;
    }
    public function groupPlayersResults(){
      $resultsArray=array();
      foreach($this->players as $p){
        $playerResult=array('win' => $this->getWinCount($p), 'loss' => $this->getLossCount($p));
        $resultsArray[$p->playerId] = $playerResult;
      }
      return $resultsArray;
    }
    public function leaguePlayersPoints($leagueMultiplier){
      $pointsForPlace= array(20,15,12,10,8,6,4,2);
      $bonusForWinWithTeacher= 15;
      $playersPoints= array();
      $playersPlaceArray= $this->groupPlayersPlace();
      $placesCount= array_count_values($playersPlaceArray);
      foreach($this->players as $p){
        $playerPlace= $playersPlaceArray[$p->playerId];
        $playersPoints[$p->playerId]= round($pointsForPlace[$playerPlace] / $placesCount[$playerPlace], 2);
        if($p->wonAgainstTeacher == 1)
          $playersPoints[$p->playerId]+= $bonusForWinWithTeacher;
        $playersPoints[$p->playerId]*= $leagueMultiplier;
      }


    }
  }
?>
