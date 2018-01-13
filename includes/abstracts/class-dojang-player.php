<?php

/**
 * Class responsible for High Level Players management
 *
 * It contains methods to get players details and as such works with players table in database
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
class Dojang_Player {
  public $playerDetails;
  public function __construct($playerId){
    global $wpdb;
    $this->playerDetails= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}players WHERE id = $playerId")[0];
  }
}
