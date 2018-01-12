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
  public function getCurrentLeagueInfo(){
  global $wpdb;
  $currentLeagueId= $this->getCurrentLeagueId();
  return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}leagues WHERE id= $currentLeagueId");
  }

}
