<?php
class Dojang_Renderer_Submit_Game{
  public function renderSubmitResultForm(){
    $html.= '<h3 class="dojang-submit">Submit League game result</h3>';
    $html.= '<p>Please fill out form below. All fields marked with <span class="dojang-required">*</span> are required for your form to be submitted.</p>';
    $html.='<form class="dojang-register-form" action="'.get_admin_url().'admin-post.php" method="post">
  <input type="hidden" name="action" value="dojang_submit_game" />
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
