<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dojang
 * @subpackage Dojang/includes
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dojang_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'dojang';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dojang_Loader. Orchestrates the hooks of the plugin.
	 * - Dojang_i18n. Defines internationalization functionality.
	 * - Dojang_Admin. Defines all hooks for the admin area.
	 * - Dojang_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * My classes to include
		 */
		//Helpers
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/class-dojang-result-helper.php';
		//Abstracts
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/class-dojang-calculator.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/class-dojang-league.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/class-dojang-group.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/class-dojang-players.php';
		//Renderers
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-results.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-group.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer.php';
		//public renderers

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-submit-game.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-registration.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-group-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/renderers/class-dojang-renderer-public.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dojang-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dojang-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dojang-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dojang-public.php';



		$this->loader = new Dojang_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dojang_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dojang_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dojang_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
		//Settings register
		$this->loader->add_action('admin_init', $plugin_admin, 'dojang_register_settings');
		//AJAX hooks for dynamic actions
		$this->loader->add_action('wp_ajax_dojang_approve_result', $plugin_admin, 'ajax_approve_result' );
		$this->loader->add_action('wp_ajax_dojang_remove_result', $plugin_admin, 'ajax_remove_result' );
		$this->loader->add_action('wp_ajax_dojang_approve_player', $plugin_admin, 'ajax_approve_player' );
		$this->loader->add_action('wp_ajax_dojang_remove_player', $plugin_admin, 'ajax_remove_player' );
		$this->loader->add_action('wp_ajax_dojang_update_played_with_teacher', $plugin_admin, 'ajax_update_played_with_teacher' );
		$this->loader->add_action('wp_ajax_dojang_close_league_distribute_points', $plugin_admin, 'ajax_close_league_distribute_points' );
		$this->loader->add_action('wp_ajax_dojang_update_player_field', $plugin_admin, 'ajax_update_player_field' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dojang_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('admin_post_nopriv_dojang_register', $plugin_public, 'post_register_data' );
		$this->loader->add_action('admin_post_dojang_register', $plugin_public, 'post_register_data' );

	}

	private function define_shortcodes(){
		//TODO: add shortcodes for player registration form, current league standings, player scores, submit results
		$plugin_public = new Dojang_Public( $this->get_plugin_name(), $this->get_version() );
		add_shortcode('dojang-register-form', array($plugin_public, 'renderRegisterForm'));
		add_shortcode('dojang-current-league', array($plugin_public, 'renderCurrentLeague'));
		add_shortcode('dojang-current-players', array($plugin_public, 'renderCurrentPlayers'));
		add_shortcode('dojang-scoreboard', array($plugin_public, 'renderScoreboard'));
		add_shortcode('dojang-archive', array($plugin_public, 'renderArchive'));
		add_shortcode('dojang-submit-result', array($plugin_public, 'renderSubmitResultForm'));
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dojang_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_current_league_name(){
		return "Current League Name";
	}

}
