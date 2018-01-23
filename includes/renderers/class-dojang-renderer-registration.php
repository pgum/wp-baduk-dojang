<?php
class Dojang_Renderer_Registration{
  private function renderRankOptions($selected = '1d'){
    if($selected=="")
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
    $html='<div class="dojang-notice"><div>';
    $html.='<h2 class="dojang-nok">Your registration had errors!</h2>';
    $html.='<p>Please try again, and remember that all fields are required!</p>';
    $html.='<p>'.$msg.'</p>';
    $html.='</div></div>';
    return $html;
  }

  public function renderPlayerRegisteredNotice(){
    $html='<div class="dojang-notice"><div>';
    $html.='<h3 class="dojang-ok">Your registration is saved and waiting for approval!</h2>';
    $html.='<p>We will write to e-mail address you provided. When your registration is approved account details will be send there too!</p>';
    $html.='</div></div>';
    return $html;
  }
  public function renderRegisterForm(){
    $prev_name=   isset($_GET['prev-name'])   ? $_GET['prev-name']    : "";
    $prev_email=  isset($_GET['prev-email'])  ? $_GET['prev-email']   : "";
    $prev_kgs=    isset($_GET['prev-kgs'])    ? $_GET['prev-kgs']     : "";
    $prev_country=isset($_GET['prev-country'])? $_GET['prev-country'] : "";
    $prev_rank=   isset($_GET['prev-rank'])   ? $_GET['prev-rank']    : "";

    $html = '<h2 class="dojang-register">Registration Form</h2>';
    $html.= '<p>Please fill out form below. All fields marked with <span class="dojang-required">*</span> are required for your form to be submitted.</p>';
    $html.='<form class="dojang-register-form" action="'.get_admin_url().'admin-post.php" method="post">
      <input type="hidden" name="action" value="dojang_register" />
      <fieldset>
        <div class="dojang-form-label"><label for="dojang-player-name">Player Name<span class="dojang-required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-name" type="text" value="'.$prev_name.'" placeholder="Name that will be visible in Players List"></div>

        <div class="dojang-form-label"><label for="dojang-player-email">Valid Email<span class="dojang-required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-email" type="text" value="'.$prev_email.'" placeholder="E-mail that will be used to contact"></div>

        <div class="dojang-form-label"><label for="dojang-player-kgs-account">KGS Account <span class="dojang-required">*</span></label></div>
        <div class="dojang-form-input"><input name="dojang-player-kgs-account" type="text" value="'.$prev_kgs.'" placeholder="KGS that will be used in league games"></div>

        <div class="dojang-form-label"><label for="dojang-player-rank">Rank <span class="dojang-required">*</span></label></div>
        <div class="dojang-form-input"><select name="dojang-player-rank">'.$this->renderRankOptions($prev_rank).'</select></div>

        <div class="dojang-form-label"><label for="dojang-player-country">Country <span class="dojang-required">*</span></label></div>
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
}
 ?>
