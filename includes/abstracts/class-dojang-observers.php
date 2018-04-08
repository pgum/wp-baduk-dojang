<?php

/**
 * Class responsible for High Level Observer Group management
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
class Dojang_ObserverGroup {
  public $groupDetails;
  public $groupPlayers;
  public function __construct($groupId, $leaguePointsMultiplier=1){
    global $wpdb;
    $this->groupDetails= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
    $this->groupPlayers= $wpdb->get_results("SELECT id, playerId, tableOrder, wonAgainstTeacher, isPaidMember FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = {$this->groupDetails->playerGroupId} ORDER BY tableOrder ASC");
    foreach($this->groupPlayers as $p){
      $p->playerDetails= $wpdb->get_row("SELECT studentId, playerName, playerEmail, playerKgs, playerRank, playerCountry, playerApproved FROM {$wpdb->prefix}players WHERE playerId = {$p->playerId}");
    }
  }
}
