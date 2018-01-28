<?php
class Dojang_Renderer_Submit_Game{
  public $league;
  public $groups;
  public function __construct(){
    $this->league= new Dojang_League();
    $this->groups = $this->league->getGroupsDetails();
  }
  private function renderLeagueGroups($prev){
    $html='<options value="-1">Select Group</options>';
    foreach($this->groups as $g){
      $gId= $g->groupDetails->groupId;
      $gName= $g->groupDetails->groupName;
      $s= ($prev == $grId ? 'selected="selected"' : '');
      $html.='<option value="'.$grId.'" '.$s.'>'.$gName.'</option>';
    }
    return $html;
  }
  private function renderLeaguePlayers($prev){
    $html='<options x-group-id="-1" value="-1">Select Player</options>';
    foreach($this->groups as $g){
      $gId= $g->groupDetails->groupId;
      $gName= $g->groupDetails->groupName;
      $gPlayers= $g->groupPlayers;
      foreach($gPlayers as $p){
        $s= ($prev == $p->id ? 'selected="selected"' : '');
        $html.='<option x-group-id="'.$grId.'" value="'.$p->id.'" '.$s.'>'.$gName.' - '.$pName.'</option>';
      }
    }
    return $html;
  }
  private function renderWinner($prev){
    $html='<options value="-1">Select Winner</options>';
    $html.='<options value="1">Player White</options>';
    $html.='<options value="0">Player Black</options>';

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
    $prev_wi= isset($_GET['prev-wg']) ? $_GET['prev-wg'] : '';
    $prev_bl= isset($_GET['prev-bg']) ? $_GET['prev-bg'] : '';

    $html.= '<h3 class="dojang-submit">Submit League game result</h3>';
    $html.= '<p>Please fill out form below. All fields marked with <span class="dojang-required">*</span> are required for your form to be submitted.</p>';
    $html.= '<p>Please note that your submit will be reviewed before it appears on results page.</p>';
    $html.='<form class="dojang-submit-game-form" action="'.get_admin_url().'admin-post.php" method="post">
  <input type="hidden" name="dojang-game-w-group" value="'.$prev_wg.'" />
  <input type="hidden" name="dojang-game-b-group" value="'.$prev_bg.'" />
  <input type="hidden" name="action" value="dojang_submit_game" />
  <fieldset>
    <div class="dojang-form-label"><label for="dojang-game-group">Choose Group<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select name="dojang-game-group">'.$this->renderLeagueGroups($prev_group).'</select></div>

    <div class="dojang-form-label"><label for="dojang-group-white">Who played as White<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select disabled name="dojang-game-white">'.$this->renderLeaguePlayers($prev_wh).'</select></div>

    <div class="dojang-form-label"><label for="dojang-group-black">Who played as Black<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select disabled name="dojang-game-black">'.$this->renderLeaguePlayers($prev_bl).'</select></div>

    <div class="dojang-form-label"><label for="dojang-group-winner">Who won the game<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><select disabled name="dojang-game-group">'.$this->renderWinner($prev_wi).'</select></div>

    <div class="dojang-form-label"><label for="dojang-group-pass">Provide submit game password<span class="dojang-required">*</span></label></div>
    <div class="dojang-form-input"><input name="dojang-group-pass" type="password" value="" placeholder="Provide Password!"></div>
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
