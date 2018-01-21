<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
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
		//Top level settings page
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Baduk Dojang Settings', 'baduk-dojang' ),
			__( 'Baduk Dojang', 'baduk-dojang' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page_main' ),
			'dashicons-welcome-learn-more',
			7
		);
		//Sub-menus: Players Mgmt, Leagues Mgmt, New League
		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
		"Players Management",
		"Players Mgmt",
		'manage_options',
		$this->plugin_name.'players',
		array( $this, 'display_options_page_players' ),
		'dashicons-groups'
			);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
		"Previous Leagues Management",
		"Leagues Mgmt",
		'manage_options',
		$this->plugin_name.'leagues',
		array( $this, 'display_options_page_leagues' ),
		'dashicons-forms'
		);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
		"Create New League",
		"New League",
		'manage_options',
		$this->plugin_name.'newleague',
		array( $this, 'display_options_page_newleague' ),
		'dashicons-plus-alt'
		);
	}
/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page_main() {
		include_once 'partials/dojang-admin-display-main.php';
	}
	public function display_options_page_players() {
		include_once 'partials/dojang-admin-display-players.php';
	}
	public function display_options_page_leagues() {
		include_once 'partials/dojang-admin-display-leagues.php';
	}
	public function display_options_page_newleague() {
		include_once 'partials/dojang-admin-display-newleague.php';
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
	//AJAX callbacks
	//wp_die(); ir required to terminate immediately and return proper response
	public function ajax_approve_result(){
		echo 'Ajax Approve Result Id= '.$_POST['result_id'];
		wp_die();
	}
	public function ajax_remove_result(){
		echo 'Ajax Remove Result Id= '.$_POST['result_id'];
		wp_die();
	}
	public function ajax_approve_player(){
		echo 'Ajax Approve Player Id= '.$_POST['player_id'];
		wp_die();
	}
	public function ajax_remove_player(){
		echo 'Ajax Remove Player Id= '.$_POST['player_id'];
		wp_die();
	}
	public function ajax_update_played_with_teacher(){
		echo 'Im gunna update played with teacher in groupplayer table for id='.$_POST['groupplayer_id'].'into value='.$_POST['wonWithTeacher'];
		wp_die();
	}
}
