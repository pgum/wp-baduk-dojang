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
require_once('class-dojang-group.php');
class Dojang_League {
  public $groupIds;
  public $leagueId;
  public function __construct($leagueId = NULL){
    if($leagueId == NULL)
      $this->leagueId= $this->getCurrentLeagueId();
    else
      $this->leagueId= $leagueId;
    $this->groupIds= $this->getLeagueGroupIds();
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
      $groups[]= new Dojang_Group($gid->id);
    return $groups;
  }
  public function getLeagueInfo(){
    global $wpdb;
    $r= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}leagues WHERE id= $this->leagueId");
    return $r;
  }
  public function getGamesToApprove(){
    global $wpdb;
    $gamesToApprove=array();
    foreach($this->groupIds as $gid){
      $query= "SELECT * FROM {$wpdb->prefix}results WHERE groupId= $gid->playerGroupId AND isApproved = 0";
      $gamesToApprove[]= $wpdb->get_results($query);
    }
    return print_r($gamesToApprove, true);
    }
  public function getGroupResults(){return "placeholder2";}
}
