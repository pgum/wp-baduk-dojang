<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/piotr-jacek-gumulka/
 * @since      1.0.0
 *
 * @author     Piotr Jacek Gumulka <pjgumulka@gmail.com>
 * @package    Dojang
 * @subpackage Dojang/admin
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
	 * @var  	string 		$options
	 */
	private $options;

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
		wp_enqueue_style( $this->plugin_name.'w2ui', plugin_dir_url( __FILE__ ) . 'css/w2ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'main', plugin_dir_url( __FILE__ ) . 'css/dojang-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_add_inline_script( 'jquery-core', '$ = jQuery;' );
		wp_enqueue_script( $this->plugin_name.'w2ui', plugin_dir_url( __FILE__ ) . 'js/w2ui.js',
			array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'chosen', plugin_dir_url( __FILE__ ) . 'js/chosen.js',
			array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'main', plugin_dir_url( __FILE__ ) . 'js/dojang-admin.js',
			array( 'jquery', 'jquery-ui-sortable'), $this->version, false );


		wp_enqueue_script( $this->plugin_name.'group_editor', plugin_dir_url( __FILE__ ) . 'js/dojang-group-editor.js',
			array( 'jquery', 'jquery-ui-sortable'), $this->version, false );
    $groupEditorVars= array('leagues_items'=> json_encode($this->getLeaguesListItems()),
                            'groups_items'=> json_encode($this->getGroupsListItems()),
                            'group_object'=> $this->renderGroupEditorObject());
    wp_localize_script( $this->plugin_name.'group_editor', 'dojang_editor', $groupEditorVars );

	}
  public function getLeaguesListItems(){
    global $wpdb;
    $leagues= $wpdb->get_results("SELECT id, leagueName FROM {$wpdb->prefix}leagues WHERE pointsDistributed = 0");
    $itemsList=array();
    foreach($leagues as $l) $itemsList[]= array('id'=> $l->id,
                                                'text'=> $l->leagueName,
                                                'icon'=> 'dashicons dashicons-admin-page');
    return $itemsList;
  }
  public function getGroupsListItems(){
    global $wpdb;
    $leagues= $wpdb->get_results("SELECT id, leagueName FROM {$wpdb->prefix}leagues WHERE pointsDistributed = 0");
    $itemsList=array();
    foreach($leagues as $l){
      $groups= $wpdb->get_results("SELECT playerGroupId, groupName FROM {$wpdb->prefix}groups WHERE groupLeagueId = {$l->id}");
      foreach($groups as $g){
        $groupPlayers= $wpdb->get_results("SELECT id, playerId FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = {$g->playerGroupId} ORDER BY tableOrder ASC");
        $groupPlayersData=array();
        foreach($groupPlayers as $p){
          $groupPlayerDetails= $wpdb->get_row("SELECT playerName, playerRank FROM {$wpdb->prefix}players WHERE playerId = {$p->playerId}");
          $groupPlayersData[]= array('id'=> $p->id, 'playerName'=> $groupPlayerDetails->playerName, 'playerRank'=> $groupPlayerDetails->playerRank);
        }
        $itemsList[]= array('id'=> $g->playerGroupId,
                            'league'=> $l->id,
                            'players'=> $groupPlayers,
                            'text'=> $g->groupName,
                            'icon'=> 'dashicons dashicons-forms',
                            'hidden' => true);
      }
    }
    return $itemsList;
  }

  public function renderPlayerSelectOptions(){
    $players= new Dojang_Players();
    $plist= $players->getAllPlayersIdNameRank();
    //print_r($plist);
    $html='<option value="-1"></option>';
    foreach($plist as $p)
      $html.= '<option value="'.$p['playerId'].'">'.$p['playerName'].' - '.$p['playerRank'].'</option>';
    return $html;
  }
  public function renderGroupEditorObject(){
    $html.='<ol class="dojang-group-editor-group-players">';
    $maxPlayersInGroup=10;
    for($i=0; $i<$maxPlayersInGroup; ++$i)
      $html.='<li class="dojang-editor-player"><div class="dojang-group-player-sortable"><span class="dashicons dashicons-menu"></span><select data-placeholder="Choose a player..." name="player-'.$i.'" class="dojang-player-select">'.$this->renderPlayerSelectOptions().'</select></div></li>';
    $html.='</ol>';
    $html.='<a class="dojang-editor-remove-group button button-secondary">Remove Group</a>';
    return $html;
  }

/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		//Top level settings page
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Baduk Dojang Overview', 'baduk-dojang' ),
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
			__( 'Players Management', 'baduk-dojang' ),
			__( 'Players Mgmt', 'baduk-dojang' ),
		'manage_options',
		$this->plugin_name.'players',
		array( $this, 'display_options_page_players' ),
		'dashicons-groups'
			);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
			__( 'Previous Leagues Management', 'baduk-dojang' ),
			__( 'Leagues Mgmt', 'baduk-dojang' ),
		'manage_options',
		$this->plugin_name.'leagues',
		array( $this, 'display_options_page_leagues' ),
		'dashicons-forms'
		);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
			__( 'Edit Existing Group', 'baduk-dojang' ),
			__( 'Edit Group', 'baduk-dojang' ),
		'manage_options',
		$this->plugin_name.'editgroup',
		array( $this, 'display_options_page_editgroup' ),
		'dashicons-plus-alt'
		);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
			__( 'Create New League', 'baduk-dojang' ),
			__( 'New League', 'baduk-dojang' ),
		'manage_options',
		$this->plugin_name.'newleague',
		array( $this, 'display_options_page_newleague' ),
		'dashicons-plus-alt'
		);

		$this->plugin_screen_hook_suffix_submenu = add_submenu_page(
		$this->plugin_name,
			__( 'Dojang Settings', 'baduk-dojang' ),
			__( 'Settings', 'baduk-dojang' ),
		'manage_options',
		'dojangoptions',
		array( $this, 'display_options_page_options' ),
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
		$this->render_dojang_footer();
	}
	public function display_options_page_players() {
		include_once 'partials/dojang-admin-display-players.php';
		$this->render_dojang_footer();
	}
	public function display_options_page_leagues() {
		include_once 'partials/dojang-admin-display-leagues.php';
		$this->render_dojang_footer();
	}
	public function display_options_page_editgroup() {
		include_once 'partials/dojang-admin-display-group-editor.php';
		$this->render_dojang_footer();
	}
	public function display_options_page_newleague() {
		include_once 'partials/dojang-admin-display-newleague.php';
		$this->render_dojang_footer();
	}
	public function display_options_page_options() {
		$this->options = get_option('dojangoptions');
		?>
		<div class="wrap">
			<h2><span class="dashicons dashicons-admin-settings"></span>My Settings</h2>
			<form method="post" action="options.php"><?php
				// This prints out all hidden setting fields
				settings_fields( 'dojangoptions' );
				do_settings_sections( 'dojangoptions');
				submit_button();
			?></form>
		</div>
		<?php
		$this->render_dojang_footer();
	}
  public function render_dojang_footer(){
		echo '<div class="dojang-footer">Made with <span class="dojang-love"></span> by Piotr</div>'	;
	}
	public function dojang_register_settings(){
	// Add a General section
	register_setting('dojangoptions', 'dojangoptions');
	add_settings_section(
		'dojang_section_id',
		__( 'General Settings', 'baduk-dojang' ),
		array( $this, 'render_section_info' ),
		'dojangoptions'
	);
	add_settings_field( 'dojang_email',	__('Notice Email', 'baduk-dojang'),	array($this, 'render_dojang_email'),'dojangoptions','dojang_section_id');
	add_settings_field( 'dojang_welcome',	__('Approved Player Welcome Message', 'baduk-dojang'),	array($this, 'render_dojang_welcome'),'dojangoptions','dojang_section_id');
	add_settings_field( 'dojang_points',	__('League Points Distribution', 'baduk-dojang'),	array($this, 'render_dojang_points'),'dojangoptions','dojang_section_id');
	add_settings_field( 'dojang_bonus',	__('Won Against Teacher Bonus', 'baduk-dojang'),	array($this, 'render_dojang_bonus'),'dojangoptions','dojang_section_id');
	add_settings_field( 'dojang_eligable',	__('Points for prize', 'baduk-dojang'),	array($this, 'render_dojang_eligable'),'dojangoptions','dojang_section_id');
	add_settings_field( 'dojang_pass',	__('Submit Game Password', 'baduk-dojang'),	array($this, 'render_dojang_pass'),'dojangoptions','dojang_section_id');
	}
	public function render_dojang_email(){
		echo '<input type="text" name="dojangoptions[dojang_email]" id="dojang_email" value="'.$this->options['dojang_email'].'"/>';
		echo '<label for="dojangoptions[dojang_email]">E-mail address that will get notices when someone registers to league.</label>';
	}
	public function render_dojang_welcome(){
		echo '<textarea name="dojangoptions[dojang_welcome]" id="dojang_welcome">'.$this->options['dojang_welcome'].'</textarea>';
		echo '<label for="dojangoptions[dojang_welcome]">Welcome message that will be send to approved player.</label>';
	}
	public function render_dojang_points(){
		echo '<input type="text" name="dojangoptions[dojang_points]" id="dojang_points" value="'.$this->options['dojang_points'].'"/>';
		echo '<label for="dojangoptions[dojang_points]">League points distribution in format: <i>1st,2nd,3rd,....</i>  Standard one is: 20,15,12,10,8,6,4,2.</label>';
	}
	public function render_dojang_bonus(){
		echo '<input type="text" name="dojangoptions[dojang_bonus]" id="dojang_bonus" value="'.$this->options['dojang_bonus'].'"/>';
		echo '<label for="dojangoptions[dojang_bonus]">League points bonus for winning with teacher. Standard one is: 15.</label>';
	}
	public function render_dojang_eligable(){
		echo '<input type="text" name="dojangoptions[dojang_eligable]" id="dojang_eligable" value="'.$this->options['dojang_eligable'].'"/>';
		echo '<label for="dojangoptions[dojang_eligable]">Set how much points is needed for a prize. Standard one is: 100.</label>';
	}
	public function render_dojang_pass(){
		echo '<input type="text" name="dojangoptions[dojang_pass]" id="dojang_bonus" value="'.$this->options['dojang_pass'].'"/>';
		echo '<label for="dojangoptions[dojang_pass]">Submit result password.</label>';
	}


	public function render_section_info(){
		echo '<p>' . __( 'Please change these settings accordingly:', 'baduk-dojang' ) . '</p>';
	}
	//AJAX callbacks
	//wp_die(); ir required to terminate immediately and return proper response
	public function ajax_approve_result(){
		global $wpdb;
		$rid= $_POST['result_id'];
		$wpdb->update("{$wpdb->prefix}results", array('isApproved' => 1), array('id' => $rid));
		echo 'Ajax Approve Result Id= '.$_POST['result_id'];
		wp_die();
	}
	public function ajax_remove_result(){
		global $wpdb;
		$rid= $_POST['result_id'];
		$wpdb->delete("{$wpdb->prefix}results", array('id' => $rid));
		echo 'Ajax Remove Result Id= '.$_POST['result_id'];
		wp_die();
	}
	public function ajax_approve_player(){
		global $wpdb;
		$pid= $_POST['player_id'];
		$wpdb->update("{$wpdb->prefix}players", array('playerApproved' => 1), array('playerId' => $pid));

		$options= get_option('dojangoptions');
		$msg= $options['dojang_welcome'];
		$player= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}players WHERE playerId = $pid")[0];
		$email= $player->playerEmail;
		wp_mail($email,'Your registration was approved!',$msg);
		echo 'Ajax Approve Player Id= '.$pid.' Player email= '.$email;
		wp_die();
	}
	public function ajax_remove_player(){
		global $wpdb;
		$pid= $_POST['player_id'];
		$wpdb->delete("{$wpdb->prefix}players", array('playerId' => $pid));
		echo 'Ajax Remove Player Id= '.$_POST['player_id'];
		wp_die();
	}
	public function ajax_update_played_with_teacher(){
		global $wpdb;
		$wwt= $_POST['wonWithTeacher'] == 'true' ? 1 : 0;
		$gpid= $_POST['groupplayer_id'];
		$wpdb->update("{$wpdb->prefix}groupplayers", array('wonAgainstTeacher' => $wwt), array('id' => $gpid));
		echo 'Im gunna update played with teacher in groupplayer table for id='.$_POST['groupplayer_id'].'into value='.$_POST['wonWithTeacher'];
		wp_die();
	}
	public function ajax_close_league_distribute_points(){
		$leagueId = $_POST['league_id'];
		global $wpdb;
		$result= $wpdb->update("{$wpdb->prefix}leagues", array('pointsDistributed' => 1, 'closed' => 1), array('id' => $leagueId));
		$league= new Dojang_League($leagueId);
		$groups= $league->getGroupsDetails();
		foreach($groups as $g)
			foreach($g->groupPlayers as $p){
				$wpdb->update("{$wpdb->prefix}groupplayers", array('leaguePoints' => $p->leaguePoints), array('id' => $p->id));
			}
		echo 'Im gunna update players with their points from league and close that league='.$leagueId.' and result was='.$result;
		wp_die();
	}
	public function ajax_update_player_field(){
		global $wpdb;
		$playerId= $_POST['player_id'];
		$field= $_POST['field'];
		$value= $_POST['value'];
		$pid= $_POST['player_id'];
		$wpdb->update("{$wpdb->prefix}players", array($field => $value), array('playerId' => $playerId));
		echo 'Im gunna update player ('.$playerId.') field '.$field.' to value '.$value;
		wp_die();
	}
	public function ajax_league_points_update(){
		global $wpdb;
		$wpdb->update("{$wpdb->prefix}leagues", array('multiplier' => $_POST['multiplier']), array('id' => $_POST['league_id']));
		echo 'Im gunna update league ('.$_POST['league_id'].') points multiplier= '.$_POST['multiplier'];
		wp_die();
	}
  public function ajax_create_league(){
		global $wpdb;
    $object= json_decode(stripslashes($_POST['league_data']), true);
    $league= $object['league'];
    $groups= $league['groups'];
    $leagueName= $league['name'];
    $leagueMultiplier= $league['multiplier'];
    $response=array();
    $lastLeagueId= $wpdb->get_var("SELECT MAX(id) AS currentLeague FROM {$wpdb->prefix}leagues");
    $wpdb->update("{$wpdb->prefix}leagues", array('closed' => 1), array('id' => $lastLeagueId));
    $leagueQuery="INSERT INTO {$wpdb->prefix}leagues (leagueName, closed, multiplier, pointsDistributed) VALUES ('$leagueName', '0', $leagueMultiplier, '0')";
    $wpdb->query($leagueQuery);
    $newLeagueId = $wpdb->insert_id;
    $i=0;
    foreach($groups as $g){
      $gOrder= $g['order'];
      $gName= $g['name'];
      $gPlayers= $g['players'];
      $gLeagueId= $newLeagueId;
      $playerGroupId= ($newLeagueId*10)+$i;
      $response[]="INSERT INTO {$wpdb->prefix}groups (groupName, groupLeagueId, playerGroupId, groupOrder) VALUES ('$gName', $gLeagueId, $playerGroupId, ".($i++).")";
      $pi=0;
      foreach($gPlayers as $p)
        $response[]="INSERT INTO {$wpdb->prefix}groupplayers (playerGroupId, playerId, tableOrder, wonAgainstTeacher, isPaidMember, leaguePoints) VALUES ($playerGroupId, $p, ".($pi++).", '0', '1', '0')";
    }
    foreach($response as $query){
      $wpdb->query($query);
    }
    print_r($response);
		wp_die();
  }
  public function ajax_create_result(){
		global $wpdb;
    $gid= $_POST['group_id'];
    $pidw= $_POST['playerW'];
    $pidl= $_POST['playerL'];
    $query="INSERT INTO {$wpdb->prefix}results (groupId, playerIdWhite, playerIdBlack, playerIdWinner, addDate, isApproved) VALUES (
          '".$gid."',
          '".$pidw."',
          '".$pidl."',
          '".$pidw."',
          NOW(),
          1);";

    $queryResult= $wpdb->query($query);
		echo 'Ajax Create Result group='.$gid.' playerWinner='.$pidw.' playerLoser='.$pidl.' result '.(false === $queryResult).' affected rows: '.$queryResult;
    echo $query;
		wp_die();
  }
  public function ajax_update_result(){
		global $wpdb;
		$rid= $_POST['result_id'];
    $pid= $_POST['playerW'];
		$wpdb->update("{$wpdb->prefix}results",array('playerIdWinner' => $pid) ,array('id' => $rid));
		echo 'Ajax Update Result Id= '.$rid.' playerWinner= '.$pid;
		wp_die();
  }
  public function ajax_toggle_result(){
		global $wpdb;
		$rid= $_POST['result_id'];
    $reviewState= $wpdb->get_var("SELECT isReviewed FROM {$wpdb->prefix}results WHERE id = $rid");
		$queryResult= $wpdb->query("UPDATE {$wpdb->prefix}results SET isReviewed = ".($reviewState == 0 ? 1 : 0)." WHERE id = $rid");
		echo 'Ajax Toggle Result Reviewed Result Id= '.$rid.' Prev Review State:'. $reviewState.' Query Result: '.(false===$queryResult);
		wp_die();
  }

  public function ajax_update_group(){
		global $wpdb;
    $object= json_decode(stripslashes($_POST['group_data']), true);
    $groupId=$object['group_id'];
    $groupName=$object['group_name'];
    $groupPlayers=$object['group_players'];
    $response=array();
    $response[]="UPDATE {$wpdb->prefix}groups SET groupName = '$groupName' WHERE playerGroupId = ".$groupId;
    $response[]="DELETE FROM {$wpdb->prefix}groupplayers WHERE playerGroupId = ".$groupId;
    $i=0;
    foreach($groupPlayers as $p)
      $response[]="INSERT INTO {$wpdb->prefix}groupplayers (playerGroupId, playerId, tableOrder, wonAgainstTeacher, isPaidMember, leaguePoints) VALUES ($groupId, $p, ".($i++).", '0', '1', '0')";

    foreach($response as $query){
      $wpdb->query($query);
    }
    print_r($response);
		wp_die();
  }
  public function ajax_register_player(){
    $data = $_POST;
    global $wpdb;
    $dataToInsert= array(	'studentId' => '',
                          'playerName' => $data['name'],
                          'playerEmail' => $data['email'],
                          'playerKgs' => $data['kgs'],
                          'playerRank' => $data['rank'],
                          'playerCountry' => $data['country'],
                          'playerTimezone' => $data['timezone'],
                          'playerApproved' => $data['approved']);
    $wpdb->insert("{$wpdb->prefix}players", $dataToInsert);
    print_r($dataToInsert);
    wp_die();
  }

}
