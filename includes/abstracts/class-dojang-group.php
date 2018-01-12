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
class Dojang_Group {
  public $groupDetails;
  public function __construct($groupId){
  global $wpdb;
	$this->$groupDetails= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}groups WHERE id = $groupId");
  }
}
