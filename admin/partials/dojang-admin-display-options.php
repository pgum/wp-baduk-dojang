<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h2><span class="dashicons dashicons-admin-settings"></span><?php echo esc_html( get_admin_page_title() ); ?></h2>
<form method="post" action="options.php">
<?php
  settings_fields('dojang_options');
  do_settings_sections('dojang_options');
  submit_button();
?>
</form>
<?

?>
<span class="dojang-footer">Made with <span class="dashicons dashicons-heart" style="color: red"></span> by Piotr</span>
</div>
