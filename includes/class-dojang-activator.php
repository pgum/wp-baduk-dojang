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
 * Main responsibility is to create database schema to put data of dojang players groups and results.
 *
 * @since      1.0.0
 * @package    Dojang
 * @subpackage Dojang/includes
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Activator {

	/**
	 * Activate creates databases schema.
	 *
	 * Five tables created:
	 * > league - holds information about leagues
	 * > players - player information such as name, rank, etc.
	 * > groups - groups information - one league can consist of many groups
	 * > groupplayers - connects player data with group to avoid data duplication
	 * > results - holds results of players (connected via groupplayers.id) in given group
	 * 
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$prefix= $wpdb->prefix;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
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
		dbDelta($sql);
		
		$table_name= $prefix.'groups';
		$sql = "
		CREATE TABLE IF NOT EXISTS $table_name (
  		  id int(11) NOT NULL AUTO_INCREMENT,
  		  groupName text NOT NULL,
  		  groupLeagueId int(11) NOT NULL,
  		  playerGroupId int(11) NOT NULL,
  		  groupOrder int(11) NOT NULL,
  		  isGroupLocked int(11) NOT NULL,
  		  PRIMARY KEY  (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=42;
		";
		dbDelta($sql);
		
		$table_name= $prefix.'leagues';
		$sql = "
		CREATE TABLE IF NOT EXISTS $table_name (
  		  id int(11) NOT NULL AUTO_INCREMENT,
  		  leagueName text NOT NULL,
  		  hidden tinyint(1) NOT NULL,
  		  closed tinyint(1) NOT NULL,
  		  pointsDistributed int(11) NOT NULL COMMENT 'if > 0 then points were distributed to players and league is completed',
    		  PRIMARY KEY  (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=12; 
		";
		dbDelta($sql);
		
		$table_name= $prefix.'players';
		$sql = "
			CREATE TABLE IF NOT EXISTS $table_name (
			  playerId int(11) NOT NULL AUTO_INCREMENT,
			  studentId int(11) NOT NULL,
			  playerName varchar(100) NOT NULL,
			  playerEmail varchar(50) NOT NULL,
			  playerKgs varchar(20) NOT NULL,
			  playerRank varchar(10) NOT NULL,
			  playerCountry varchar(50) NOT NULL,
			  playerTimezone text NOT NULL COMMENT 'timezone i.e. +2 or -6 in relation to GMT',
			  playerApproved int(11) NOT NULL COMMENT 'approval means that player will be visible in creation of new league group',
			  PRIMARY KEY  (playerId)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=398;		
		";
		dbDelta($sql);
	
	
		$table_name= $prefix.'results';
		$sql = "
			CREATE TABLE IF NOT EXISTS $table_name (
			  REATE TABLE nresults (
			  id int(11) NOT NULL,
			  groupId int(11) NOT NULL,
			  playerIdBlack int(11) NOT NULL,
			  playerIdWhite int(11) NOT NULL,
			  playerIdWinner int(11) NOT NULL,
			  file text NOT NULL,
			  linkKGS text NOT NULL,
			  commentedGameId int(11) NOT NULL,
			  addDate date NOT NULL,
			  isApproved tinyint(4) NOT NULL,
			  result text NOT NULL,
			  link mediumtext NOT NULL,
			  oldResultId int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=652;
		";
		dbDelta($sql);
	}

}
