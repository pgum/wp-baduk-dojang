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
		$renderer= new Dojang_Renderer_Public();
		$html='';
		if(isset($_GET['suc']) && $_GET['suc'] == 1)
			$html.= $renderer->renderPlayerRegisteredNotice();
		if(isset($_GET['suc']) && $_GET['suc'] == 0){
			$msg.=  isset($_GET['dname'])   ? 'NAME cannot be empty!<br/>'    : "";
			$msg.=  isset($_GET['demail'])  ? '"'.$_GET['prev-email'].'" is not valid E-MAIL!<br/>'   : "";
			$msg.=  isset($_GET['dkgs'])    ? 'KGS ACCOUNT cannot be empty!<br/>'     : "";
			$msg.=  isset($_GET['dcountry'])? 'COUNTRY cannot be empty!<br/>' : "";
			$html.= $renderer->renderPlayerNotRegisteredNotice($msg);
		}
		$html.= $renderer->renderRegisterForm();
		return $html;
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
	public function renderArchive(){
		global $wpdb;
		$archiveLeaguesIds= $wpdb->get_col("SELECT id AS pastLeagues FROM {$wpdb->prefix}leagues WHERE closed = 1");
		$html='';
		foreach($archiveLeaguesIds as $leagueId){
			$league= new Dojang_League($leagueId);
			$renderer= new Dojang_Renderer_Public();
			$html.=$renderer->renderLeague($league).'<br/>';
		}
		return $html;
	}
  /*POST DATA handlers*/
	private function validate_post_data($data){
		$returnArray=array();
		$returnResult=1;
		if(strlen($data['dojang-player-name'])== 0){
			$returnArray['dname']= 1;
			$returnResult=0;
		}
		if(is_email($data['dojang-player-email'])== false){
			$returnArray['demail']= 1;
			$returnResult=0;
		}
		if(strlen($data['dojang-player-kgs-account'])== 0){
			$returnArray['dkgs']= 1;
			$returnResult=0;
		}
		if(strlen($data['dojang-player-country'])== 0){
			$returnArray['dcountry']= 1;
			$returnResult=0;
		}
		if($returnResult == 0){
			if(isset($data['dojang-player-name'])) $returnArray['prev-name'] = '"'.$data['dojang-player-name'].'"';
			if(isset($data['dojang-player-kgs-account'])) $returnArray['prev-kgs'] = '"'.$data['dojang-player-kgs-account'].'"';
			if(isset($data['dojang-player-email'])) $returnArray['prev-email'] = '"'.$data['dojang-player-email'].'"';
			if(isset($data['dojang-player-country'])) $returnArray['prev-country'] = '"'.$data['dojang-player-country'].'"';
			$returnArray['prev-rank'] = $data['dojang-player-rank'];
		}
		$returnArray['suc'] = $returnResult;
		return $returnArray;
	}
	public function post_register_data(){
		$validation_result= $this->validate_post_data($_POST);
		wp_safe_redirect(add_query_arg( $validation_result, home_url('/register-to-online-teaching')));
		exit;
	}
}
