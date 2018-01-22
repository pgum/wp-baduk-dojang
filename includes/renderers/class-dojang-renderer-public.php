<?php
class Dojang_Renderer_Public{
  private function renderOptionForDans(){
    for($i=9; $i>1; $i--){
        $html.='<option value="'.$i.'d">'.$i.' Dan</option>';
    }
    return $html.'<option value="1d" selected>1 Dan</option>';
  }
  private function renderOptionForKyus(){
    for($i=1; $i<26; $i++){
        $html.='<option value="'.$i.'k">'.$i.' Kyu</option>';
    }
    return $html;
  }

  public function renderRegisterForm(){
    $html = '<h2 class="dojang-register">Registration Form</h2>';
    $html.='<form class="dojang-register-form" action="'.get_admin_url().'admin-post.php" method="post">
      <input type="hidden" name="action" value="dojang_register" />
      <fieldset>
        <div class="dojang-form-label"><label for="dojang-player-name">Player Name<span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-name" type="text" value="" placeholder="Name that will be visible in Players List"></div>

        <div class="dojang-form-label"><label for="dojang-player-email">Valid Email ()<span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-email" type="text" value="" placeholder="E-mail that will be used to contact"></div>

        <div class="dojang-form-label"><label for="dojang-player-kgs-account">KGS Account <span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-KGS-account" type="text" value="" placeholder="KGS that will be used in league games"></div>

        <div class="dojang-form-label"><label for="dojang-player-rank">Rank <span class="required">*</span></label></div>
        <div class="dojang-form-input"><select name="dojang-player-rank">'.$this->renderOptionForDans().$this->renderOptionForKyus().'</select></div>

        <div class="dojang-form-label"><label for="dojang-player-country">Country <span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-country" type="text" value="" placeholder="What country are you from"></div>
      </fieldset>
      <fieldset>
        <div class="dojang-form-submit">
          <input type="submit" name="submit" class="button" value="Register for On-Line Teaching!">
        </div>
      </fieldset>
    </form>';
    return $html;
  }
  public function renderCurrentLeague(){
    $html='<h2 class="dojang-results"> Group Standings</h2>';/*
    $groupArray= $league->getGroupsDetails();
    foreach ($groupArray as $group){
      $gR = new Dojang_Renderer_Group_Public($group);
      $html.= $gR->renderGroupInfo();
      $html.= $gR->renderGroupTable();
      $html.='a<br/>';
    }*/
    return $html;
  }
  public function renderCurrentPlayers(){
    return '<h2 class="dojang-players">Current League Players List</h2>';
  }
  public function renderScoreboard(){
    return '<h2 class="dojang-scoreboard">Players Scoreboard</h2>';
  }
  public function renderSubmitResultForm(){
    return '<h2 class="dojang-submit">Submit League game result</h2>';
  }
}
 ?>
