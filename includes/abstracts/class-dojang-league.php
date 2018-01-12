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
class Dojang_League {
  public function getCurrentLeagueId(){
  global $wpdb;
	return $wpdb->get_var("SELECT MAX(id) AS currentLeague FROM {$wpdb->prefix}leagues");
  }
  private function getGroupsDetails(){
    global $wpdb;
    $currentLeagueId= $this->getCurrentLeagueId();
    $groupIds= $wpdb->get_results("SELECT id FROM {$wpdb->prefix}groups WHERE groupLeagueId= $currentLeagueId");
    return $groupIds;
  }
  public function getCurrentLeagueInfo(){
    global $wpdb;
    $currentLeagueId= $this->getCurrentLeagueId();
    $r= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}leagues WHERE id= $currentLeagueId");
    $r.= getGroupsDetails();
    return $r;
  }

}
