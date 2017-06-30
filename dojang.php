<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/piotr-gumu%C5%82ka-ab329176/
 * @since             1.0.0
 * @package           Dojang
 *
 * @wordpress-plugin
 * Plugin Name:       Baduk Dojang
 * Plugin URI:        https://github.com/pgum/wp-baduk-dojang
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Piotr Jacek Gumulka
 * Author URI:        https://www.linkedin.com/in/piotr-gumu%C5%82ka-ab329176/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dojang
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dojang-activator.php
 */
function activate_dojang() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dojang-activator.php';
	Dojang_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dojang-deactivator.php
 */
function deactivate_dojang() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dojang-deactivator.php';
	Dojang_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dojang' );
register_deactivation_hook( __FILE__, 'deactivate_dojang' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dojang.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dojang() {

	$plugin = new Dojang();
	$plugin->run();

}
run_dojang();
