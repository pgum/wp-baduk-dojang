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
class Dojang_Players {
  	private function playerScoreComparator($scoreOne, $scoreTwo){
		$scores_equal= 0;
		$scoreTwo_better= 1;
		$scoreOne_better= -1;
		if($scoreOne['playerPoints'] == $scoreTwo['playerPoints']) return $scores_equal;
		if($scoreOne['playerPoints'] > $scoreTwo['playerPoints']) return $scoreOne_better;
		return $scoreTwo_better;
	}
	public function getAllPlayersData(){
		global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}players ORDER BY playerApproved ASC", ARRAY_A);
	}
	public function getPlayersToApprove(){
		global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}players WHERE playerApproved = 0 ", ARRAY_A);
	}


	public function getScoreboard(){
		global $wpdb;
    $allPlayers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}players WHERE playerApproved= 1");
		$scoreboard=array();
    foreach($allPlayers as $p){
			$playerPoints= $wpdb->get_results("SELECT leaguePoints FROM {$wpdb->prefix}groupplayers WHERE playerId = $p->playerId", ARRAY_N);
      $playerPointsSum= array_sum($playerPoints);
			$playerData=array('playerName' => $p->playerName,
                        'playerCountry'=> $p->playerCountry,
                        'playerKgs' => $p->playerKgs,
                        'playerRank' => $p->playerRank,
                        'playerPoints' => $playerPointsSum);
			$scoreboard[$p->playerId]= $playerData;
    }
		uasort($scoreboard, array($this, 'playerScoreComparator'));
		return $scoreboard;
	}
}
