<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dojang
 * @subpackage Dojang/includes
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$prefix= $wpdb->prefix;
		$table_name= $prefix.'groupplayers';
		$sql = "
		CREATE TABLE IF NOT EXISTS $table_name (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  playerGroupId int(11) NOT NULL,
		  playerId int(11) NOT NULL,
		  tableOrder int(11) NOT NULL COMMENT 'Lower number is higher in results table for group',
		  playedWithTeacher smallint(6) NOT NULL,
		  wonAgainstTeacher smallint(6) NOT NULL,
		  isPaidMember int(11) NOT NULL,
		  PRIMARY KEY  (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=267;
		";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	}

}
