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
  public function getCurrentLeagueId(){
  global $wpdb;
	return $wpdb->get_var("SELECT MAX(id) AS currentLeague FROM {$wpdb->prefix}leagues");
  }
  private function getGroupsDetails(){
    global $wpdb;
    $currentLeagueId= $this->getCurrentLeagueId();
    $groupIds= $wpdb->get_results("SELECT id FROM {$wpdb->prefix}groups WHERE groupLeagueId= $currentLeagueId", ARRAY_N);
    $groups= array();
    $groupss=array();
    foreach($groupIds as $gid){
      $groupss[]=$gid;
      $groups[]= new Dojang_Group($gid);
    }
    return array($groupIds, $groupss, $groups);
  }
  public function getCurrentLeagueInfo(){
    global $wpdb;
    $currentLeagueId= $this->getCurrentLeagueId();
    $r= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}leagues WHERE id= $currentLeagueId");
    $rr= $this->getGroupsDetails();
    return array($r,$rr);
  }

}
