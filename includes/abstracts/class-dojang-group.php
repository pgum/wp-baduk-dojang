<?php

/**
 * Class responsible for High Level Group management
 *
 * It contains methods to get group details and as such works with groups and groupplayers tables in database
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      2.1.1
 *
 * @package    Dojang
 * @subpackage Dojang/includes/abstracts
 */

/**
 *
 * @since      2.1.1
 * @package    Dojang
 * @subpackage Dojang/includes/abstracts
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Group {
  public $groupDetails;
  public $groupPlayers;
  public $groupResults;
  //public $renderedGroupResults;
  private function getInitials($name){
    foreach(explode(' ', $name) as $word) $initials .= mb_substr($word, 0, 1, 'utf-8');
    return $initials;
  }
  private function calculateLeaguePoints($players, $results, $multiplier=2){
    $arr=array();
    for($i=0; $i < 1000; $i++)
      $arr[$i]=$i* $multiplier;
    return $arr;
  }
  private function calculatePlayerPlaceInGroup($players, $results){
    $arr=array();
    for($i=0; $i < 1000; $i++)
      $arr[$i]=$i;
    return $arr;
  }
  public function __construct($groupId, $leaguePointsMultiplier=1){
    global $wpdb;
    $this->groupDetails= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
    $this->groupPlayers= $wpdb->get_results("SELECT id, playerId, tableOrder, playedWithTeacher, wonAgainstTeacher, isPaidMember FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = {$this->groupDetails->playerGroupId} ORDER BY tableOrder ASC");
    $this->groupResults= $wpdb->get_results("SELECT id, playerIdBlack, playerIdWhite, playerIdWinner, isApproved FROM {$wpdb->prefix}results WHERE groupId = {$this->groupDetails->playerGroupId}");
    $leaguePointsArray= $this->calculateLeaguePoints($this->groupPlayers, $this->groupResults);
    $playersPlaceArray= $this->calculatePlayerPlaceInGroup($this->groupPlayers, $this->groupResults);
    foreach($this->groupPlayers as $p){
        $p->playerDetails= $wpdb->get_row("SELECT studentId, playerName, playerEmail, playerKgs, playerRank, playerCountry, playerApproved FROM {$wpdb->prefix}players WHERE playerId = {$p->playerId}");
        $p->playerDetails->{"playerInitials"}= $this->getInitials($p->playerDetails->playerName);
        $p->{"leaguePoints"}= $leaguePointsArray[$p->playerId];
        $p->{"place"}= $playersPlaceArray[$p->playerId];
      }
  }
}
