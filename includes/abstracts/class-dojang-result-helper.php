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
 //obsolete
class Dojang_Results_Helper{
  public $groupResults;
  public $query;
  public $groupPlayers;
  public function __construct($groupId, $groupPlayers){
    global $wpdb;
    $this->query="SELECT playerIdBlack, playerIdWhite, playerIdWinner, isApproved FROM {$wpdb->prefix}results WHERE groupId = $groupId";
    $this->groupResultsRaw= $wpdb->get_results($this->query);
    $this->groupPlayers= $groupPlayers;
  }
  public function prepareHeaderResultRow(){
    $prefix= "|| ";
    $separator= " # ";
    $postfix=" ||<br/>";
    $html=$prefix;
    foreach($this->groupPlayers as $player){
      $html.= "{$player->playerInitials}.$separator";
    }
    $html.= "W/L".$postfix;
    return $html;
  }
  public function preparePlayerResultRow($player){
    $prefix= "|| ";
    $separator= " # ";
    $postfix=" ||<br/>";
    $html=$prefix;
    $html.= "{$player->playerDetails->playerName}.$separator";
    foreach($this->groupPlayers as $p){
      $html.= "X".$separator;
    }
    $html.= "W/L".$postfix;
    return $html;

  }
  public function getResultsTable(){
    $html.=$this->prepareHeaderResultRow();
    foreach($this->groupPlayers as $p){
      $html.= $this->preparePlayerResultRow($p);
    }
    return $html;
  }
}
