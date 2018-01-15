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
require_once('class-dojang-player.php');
class Dojang_Group {
  public $groupDetails;
  public $groupPlayers;
  public $groupPlayersDetails;
  public function __construct($groupId){
    global $wpdb;
	   $this->groupDetails= $wpdb->get_row("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
     $this->groupPlayers= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = {$this->groupDetails->playerGroupId}", ARRAY_A);
     $this->groupPlayerDetails=array();
     foreach($this->groupPlayers as $p){
       $p->groupPlayerDetails = new Dojang_Player($p->playerId);
     }
  }
}
