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
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <div id="dojang-group-editor">
    <div id="dojang-group-toolbar"></div>
    <div id="dojang-workspace"><ul id="dojang-sortable"></ul></div>
    <div id="dojang-debug-container" class="dojang-hidden"><pre id="dojang-debug"></pre></div>
  </div>
</div>
<?php
 ?>
