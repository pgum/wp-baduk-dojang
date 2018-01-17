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
  public function __construct($groupId){
    global $wpdb;
	  $this->groupDetails= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
    $this->groupPlayers= $wpdb->get_results("SELECT id, playerId, tableOrder, playedWithTeacher, wonAgainstTeacher, isPaidMember FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = {$this->groupDetails->playerGroupId} ORDER BY tableOrder ASC");
    $this->groupResults= $wpdb->get_results("SELECT playerIdBlack, playerIdWhite, playerIdWinner, isApproved FROM {$wpdb->prefix}results WHERE groupId = {$this->groupDetails->playerGroupId}");
    foreach($this->groupPlayers as $p){
        $p->playerDetails = $wpdb->get_row("SELECT studentId, playerName, playerEmail, playerKgs, playerRank, playerCountry, playerApproved FROM {$wpdb->prefix}players WHERE playerId = {$p->playerId}");
        $p->playerDetails->{"playerInitials"} = $this->getInitials($p->playerDetails->playerName);
      }
      //new Dojang_Player($p->playerId);
  }
}
