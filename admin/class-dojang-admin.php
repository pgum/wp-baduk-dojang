<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-gumu%C5%82ka-ab329176/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dojang
 * @subpackage Dojang/admin
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'dojang';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dojang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dojang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dojang-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dojang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dojang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dojang-admin.js', array( 'jquery' ), $this->version, false );

	}
/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	/*Generated Menu on own tab on the left in admin page*/
		$this->plugin_screen_hook_suffix_menu = add_menu_page(
			__( 'Baduk Dojang Settings', 'baduk-dojang' ),
			__( 'Baduk Dojang', 'baduk-dojang' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
/*	
		Generates Menu under Settings
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Dojang Settings Window Title I guess', 'baduk-dojang' ),
			__( 'Dojang Settings Menu Text', 'baduk-dojang' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	*/
	}
/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/dojang-admin-display.php';
	}
	public function register_setting(){
	// Add a General section
	add_settings_section(
		$this->option_name . '_general',
		__( 'General', 'outdated-notice' ),
		array( $this, $this->option_name . '_general_cb' ),
		$this->plugin_name
	);
	}
}
