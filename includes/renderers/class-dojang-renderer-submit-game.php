<?php
class Dojang_Renderer_Submit_Game{
  private function renderLeagueGroups($prev = -1){
    $s= ($prev > 0 ? 'selected="selected"' : '');
    $html='<option value="-1" '.$s.'>Select Group</option>';
    $league= new Dojang_League();
    $groups = $league->getGroupsDetails();
    foreach($groups as $g){
      $gId= $g->groupDetails->playerGroupId;
      $gName= $g->groupDetails->groupName;
      $s= ($prev == $gId ? 'selected="selected"' : '');
      $html.='<option value="'.$gId.'" '.$s.'>'.$gName.'</option>';
    }
    return $html;
  }
  private function renderLeaguePlayers($prev){
    $league= new Dojang_League();
    $groups = $league->getGroupsDetails();
    $html='<option x-group-id="-1" value="-1">Select Player</option>';
    foreach($groups as $g){
      $gId= $g->groupDetails->playerGroupId;
      $gName= $g->groupDetails->groupName;
      $gPlayers= $g->groupPlayers;
      foreach($gPlayers as $p){
        $pName= $p->playerDetails->playerName;
        $s= ($prev == $p->playerId ? 'selected="selected"' : '');
        $html.='<option x-group-id="'.$gId.'" value="'.$p->playerId.'" '.$s.'>'.$gName.' - '.$pName.'</option>';
      }
    }
    return $html;
  }
  private function renderWinner($prev){
    $html='<option value="-1">Select Winner</option>';
    $html.='<option id="dojang-sw" value="1">Player White</option>';
    $html.='<option id="dojang-sb" value="0">Player Black</option>';

    return $html;
  }
  public function renderGameNotSubmittedNotice($msg){
    $html='<div class="dojang-notice"><div>';
    $html.='<h3 class="dojang-nok">Your submission had errors!</h3>';
    $html.='<p>Please try again, and remember that all fields are required!</p>';
    $html.='<p>'.$msg.'</p>';
    $html.='</div></div>';
    return $html;
  }

  public function renderGameSubmittedNotice(){
    $html='<div class="dojang-notice"><div>';
    $html.='<h3 class="dojang-ok">Your submission is saved and waiting for approval!</h3>';
    $html.='<p>After approval result will be visible on results table!</p>';
    $html.='</div></div>';
    return $html;
  }

  public function renderSubmitResultForm(){
    $prev_gr= isset($_GET['prev-gr']) ? $_GET['prev-gr'] : '';
    $prev_wh= isset($_GET['prev-wh']) ? $_GET['prev-wh'] : '';
    $prev_bl= isset($_GET['prev-bl']) ? $_GET['prev-bl'] : '';
    $prev_wi= isset($_GET['prev-wi']) ? $_GET['prev-wi'] : '';
    $prev_wg= isset($_GET['prev-wg']) ? $_GET['prev-wg'] : '';
    $prev_bg= isset($_GET['prev-bg']) ? $_GET['prev-bg'] : '';

    $html.= '<p>Please fill out the form below. All fields marked with <span class="dojang-required">*</span> are required for your form to be submitted.</p>';
    $html.= '<p>Please note that your submit will be reviewed before it appears on the results page.</p>';
    $html.='<form class="dojang-submit-game-form" action="'.get_admin_url().'admin-post.php" method="post">
  <input type="hidden" id="dojang-wg" name="dojang-game-w-group" value="'.$prev_wg.'" />
  <input type="hidden" id="dojang-bg" name="dojang-game-b-group" value="'.$prev_bg.'" />
  <input type="hidden" name="action" value="dojang_submit_game" />
  <fieldset>
    <div class="dojang-form-label"><label for="dojang-game-group">Choose Group<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select name="dojang-game-group">'.$this->renderLeagueGroups($prev_gr).'</select></div>

    <div class="dojang-form-label"><label for="dojang-game-white">Who played as White<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select class="dojang-game-player-list" id="dojang-pw" name="dojang-game-white">'.$this->renderLeaguePlayers($prev_wh).'</select></div>

    <div class="dojang-form-label"><label for="dojang-game-black">Who played as Black<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select class="dojang-game-player-list" id="dojang-pb" name="dojang-game-black">'.$this->renderLeaguePlayers($prev_bl).'</select></div>

    <div class="dojang-form-label"><label for="dojang-game-winner">Who won the game<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select name="dojang-game-winner">'.$this->renderWinner($prev_wi).'</select></div>

    <div class="dojang-form-label"><label for="dojang-game-pass">Provide submit game password<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><input name="dojang-game-pass" type="password" value="" placeholder="Provide Password!"></div>
  </fieldset>
  <fieldset>
    <div class="dojang-form-submit">
      <input type="submit" name="submit" class="button" value="Submit game result!">
    </div>
  </fieldset>
</form>';
    return $html;
  }
}
 ?>
