<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @package    Dojang
 * @subpackage Dojang/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dojang
 * @subpackage Dojang/public
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 */
class Dojang_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dojang-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dojang-public.js', array( 'jquery' ), $this->version, false );

	}
	/*Shortcodes callbacks*/

	public function renderRegisterForm(){
		print_r($_GET);

		if(isset($_GET['suc'])){
			echo "THX 4 registration. Waiting for approval!";
		}
		$renderer= new Dojang_Renderer_Public();
		return $renderer->renderRegisterForm();
	}
	public function renderCurrentLeague(){
		//$league= new Dojang_League();
		$renderer= new Dojang_Renderer_Public();
		return $renderer->renderCurrentLeague();
	}
	public function renderCurrentPlayers(){
		$renderer= new Dojang_Renderer_Public();
		return $renderer->renderCurrentPlayers();
	}
	public function renderScoreboard(){
		$renderer= new Dojang_Renderer_Public();
		return $renderer->renderScoreboard();
	}
	public function renderSubmitResultForm(){
		$renderer= new Dojang_Renderer_Public();
		return $renderer->renderSubmitResultForm();
	}
  /*POST DATA handlers*/
	public function post_register_data(){
		wp_safe_redirect(add_query_arg( 'suc', '1', home_url('/register-to-online-teaching')));
		exit;
	}
}
