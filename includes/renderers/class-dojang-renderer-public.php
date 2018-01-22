<?php
class Dojang_Renderer_Public{
  private function renderRankOptions($selected = '1d'){
    if($selected="")
      $selected='1d';
    for($i=9; $i>0; $i--){
        $rank=$i.'d';
        $html.='<option value="'.$rank.'" '.($selected == $rank ? 'selected="selected"' : '').'>'.$i.' Dan</option>';
    }
    for($i=1; $i<26; $i++){
        $rank=$i.'k';
        $html.='<option value="'.$rank.'" '.($selected == $rank ? 'selected="selected"' : '').'>'.$i.' Kyu</option>';
    }
    return $html;

  }
  public function renderPlayerNotRegisteredNotice($msg){
    $html='<div class="dojang-notice">';
    $html.='<h2 class="dojang-nok">Your registration had errors!</h2>';
    $html.='<p>Please try again, and remember that all fields are required!</p>';
    $html.='<p>'.$msg.'</p>';
    $html.='</div>';
    return $html;
  }

  public function renderPlayerRegisteredNotice(){
    $html='<div class="dojang-notice">';
    $html.='<h2 class="dojang-ok">Your registration is saved and waiting for approval!</h2>';
    $html.='<p>We will write to e-mail address you provided. When your registration is approved account details will be send there too!</p>';
    $html.='</div>';
    return $html;
  }
  public function renderRegisterForm(){
    $prev_name=   isset($_GET['prev-name'])   ? $_GET['prev-name']    : "";
    $prev_email=  isset($_GET['prev-email'])  ? $_GET['prev-email']   : "";
    $prev_kgs=    isset($_GET['prev-kgs'])    ? $_GET['prev-kgs']     : "";
    $prev_country=isset($_GET['prev-country'])? $_GET['prev-country'] : "";
    $prev_rank=   isset($_GET['prev-rank'])   ? $_GET['prev-rank']    : "";

    $html = '<h2 class="dojang-register">Registration Form</h2>';
    $html.='<form class="dojang-register-form" action="'.get_admin_url().'admin-post.php" method="post">
      <input type="hidden" name="action" value="dojang_register" />
      <fieldset>
        <div class="dojang-form-label"><label for="dojang-player-name">Player Name<span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-name" type="text" value="'.$prev_name.'" placeholder="Name that will be visible in Players List"></div>

        <div class="dojang-form-label"><label for="dojang-player-email">Valid Email ()<span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-email" type="text" value="'.$prev_email.'" placeholder="E-mail that will be used to contact"></div>

        <div class="dojang-form-label"><label for="dojang-player-kgs-account">KGS Account <span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-KGS-account" type="text" value="'.$prev_kgs.'" placeholder="KGS that will be used in league games"></div>

        <div class="dojang-form-label"><label for="dojang-player-rank">Rank <span class="required">*</span></label></div>
        <div class="dojang-form-input"><select name="dojang-player-rank">'.$this->renderRankOptions($prev_rank).'</select></div>

        <div class="dojang-form-label"><label for="dojang-player-country">Country <span class="required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-country" type="text" value="'.$prev_country.'" placeholder="What country are you from"></div>
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
