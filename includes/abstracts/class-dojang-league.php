<?php

/**
 * Class responsible for High Level League management
 *
 * Class responsible for High Level League management
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
 * @subpackage Dojang/includes
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
//require_once('class-dojang-group.php');
class Dojang_League {
  public $groupIds;
  public $leagueId;
  public $pointsMultiplier;
  public function __construct($leagueId = NULL){
    if($leagueId == NULL)
      $this->leagueId= $this->getCurrentLeagueId();
    else
      $this->leagueId= $leagueId;
    $this->groupIds= $this->getLeagueGroupIds();
    $this->pointsMultiplier= $this->getLeagueMultiplier();
  }
  public function getLeagueMultiplier(){
  global $wpdb;
	return $wpdb->get_var("SELECT multiplier FROM {$wpdb->prefix}leagues WHERE id= $this->leagueId");
  }
  public function getCurrentLeagueId(){
  global $wpdb;
	return $wpdb->get_var("SELECT MAX(id) AS currentLeague FROM {$wpdb->prefix}leagues");
  }
  public function getLeagueGroupIds(){
    global $wpdb;
    $groupIds= $wpdb->get_results("SELECT id, playerGroupId FROM {$wpdb->prefix}groups WHERE groupLeagueId= $this->leagueId ORDER BY playerGroupId ASC");
    return $groupIds;
  }
  public function getGroupsDetails(){
    $groups= array();
    foreach($this->groupIds as $gid)
      $groups[]= new Dojang_Group($gid->id, $this->pointsMultiplier);
    return $groups;
  }
  public function getLeagueInfo(){
    global $wpdb;
    $r= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}leagues WHERE id= $this->leagueId");
    return $r;
  }
  private function fillGamePlayerDetails($game){
    global $wpdb;
    $gamePlayers= $wpdb->get_results("SELECT playerId, playerName FROM {$wpdb->prefix}players WHERE playerId IN ({$game->playerIdWhite}, {$game->playerIdBlack})", OBJECT_K);
    $game->playerIdWhite=$gamePlayers[$game->playerIdWhite]->playerName;
    $game->playerIdBlack=$gamePlayers[$game->playerIdBlack]->playerName;
    $game->playerIdWinner=$gamePlayers[$game->playerIdWinner]->playerName;
    return $game;
  }
  public function getGamesToApprove(){
    global $wpdb;
    $gamesToApprove=array();
    foreach($this->groupIds as $gid)
      $gamesToApprove[]= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}results WHERE groupId= $gid->playerGroupId AND isApproved = 0");
    $merged= call_user_func_array('array_merge', $gamesToApprove);
      if(count($merged)>0)
        foreach($merged as $game)
          $game= $this->fillGamePlayerDetails($game);
    return $merged;
    }

}
