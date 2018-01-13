<?php

/**
 * Class responsible for High Level League management
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
 * @subpackage Dojang/includes
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Group {
  public $groupDetails;
  public function __construct($groupId){
  global $wpdb;
	$this->groupDetails= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
  }
}
