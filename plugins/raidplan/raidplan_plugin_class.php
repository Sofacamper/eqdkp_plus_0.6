<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2012-10-05 11:27:27 +0200 (Fri, 05 Oct 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: (local) $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 12160M $
 *
 * $Id: raidplan_plugin_class.php 12160M 2012-10-05 09:27:27Z (local) $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');
    exit;
}

class raidplan_Plugin_Class extends EQdkp_Plugin {
  var $version    = '4.1.11';
  var $copyright  = 'WalleniuM';
  var $vstatus    = 'Stable';
  var $build      = '11834';
  var $fwversion  = '2.0.0';  // required framework Version

  function raidplan_plugin_class($pm){
    global $eqdkp_root_path, $user, $SID, $eqdkp, $db, $table_prefix;

    $this->eqdkp_plugin($pm);                     // Call our parent's constructor
    $this->pm->get_language_pack('raidplan');     // Get language pack

		// Data for this plugin
    $this->add_data(array(
        'name'					=> 'RaidPlan',
        'code'					=> 'raidplan',
        'path'					=> 'raidplan',
        'contact'				=> 'raidplan@eqdkp-plus.com',
        'template_path'	=> 'plugins/raidplan/templates/',
        'version'				=> $this->version)
    );

    // Addition Information for eqdkp PLUS
    $this->additional_data = array(
        'author'            => $this->copyright,
        'description'       => $user->lang['rp_short_desc'],
        'long_description'  => $user->lang['rp_long_desc'],
        'homepage'          => 'http://www.eqdkp-plus.com/',
        'manuallink'        => $eqdkp_root_path . 'plugins/'.$this->get_data('path').'/doc/manual.pdf',
    );

		// Register our permissions
    $this->add_permission('501', 'a_raidplan_add',        'N', $user->lang['add']);
    $this->add_permission('502', 'a_raidplan_update',     'N', $user->lang['update']);
    $this->add_permission('503', 'a_raidplan_delete',     'N', $user->lang['delete']);
    $this->add_permission('506', 'a_raidplan_config',     'N', $user->lang['config']);
    $this->add_permission('508', 'a_raidplan_wildcards',  'N', $user->lang['rp_wildcard_perm']);
    $this->add_permission('509', 'a_raidplan_logs',       'N', $user->lang['rp_logmanager']);
    $this->add_permission('504', 'u_raidplan_list',       'Y', $user->lang['list']);
    $this->add_permission('505', 'u_raidplan_view',       'Y', $user->lang['view']);
    $this->add_permission('507', 'u_raidplan_statistic',  'Y', $user->lang['rp_statistic']);

    // Add Log Actions
		$this->add_log_action('{L_ACTION_RPRAID_ADDED}', 		$user->lang['action_rpraid_added']);
		$this->add_log_action('{L_ACTION_RPRAID_DEL}', 			$user->lang['action_rpraid_del']);
		$this->add_log_action('{L_ACTION_RPRAID_CHANGED}',	$user->lang['action_rpraid_changed']);
		$this->add_log_action('{L_ACTION_RPTEMPL_ADDED}',		$user->lang['action_rptempl_add']);
		$this->add_log_action('{L_ACTION_RPTEMPL_DEL}',			$user->lang['action_rptempl_del']);

    // Add Menus
		$this->add_menu('main_menu1', $this->gen_main_menu1());
		$this->add_menu('admin_menu', $this->gen_admin_menu());
		$this->add_menu('settings', $this->gen_settings_menu());

    include($eqdkp_root_path.'plugins/'.$this->get_data('path').'/includes/data/sql.php');
    include($eqdkp_root_path.'libraries/MyGames/MyGames.class.php');

		// ---------------------------------------------------------
    // INSTALL
    // ---------------------------------------------------------
    if (!($this->pm->check(PLUGIN_INSTALLED, 'raidplan'))){
      include($eqdkp_root_path.'plugins/'.$this->get_data('path').'/includes/data/defdata.php');

      // Define installation
		  for ($i = 1; $i <= count($raidplanSQL['install']); $i++){
  		  if($raidplanSQL['uninstall'][$i]){
          $this->add_sql(SQL_INSTALL, $raidplanSQL['uninstall'][$i]);
  			}
        $this->add_sql(SQL_INSTALL, $raidplanSQL['install'][$i]);
      }

      // insert the Permission of the Installing Person
      $perm_array = array('501', '502', '503', '504', '505', '506', '507', '508', '509');
  		$this->set_permissions($perm_array);


		  $rpgfolders = new MyGames();
      include($eqdkp_root_path.'plugins/'.$this->get_data('path').'/games/'.$rpgfolders->GameName().'/init_vars.php');

      // Add Class Colors
		  if(is_array($rpclassColorsCSS)){
        $config_vars = array_merge($config_vars, $rpclassColorsCSS);
      }
      $this->InsertIntoTable($config_vars); // perform the array

      // Add Roles
      foreach($rproleVariables as $roleid=>$rolevalue){
        $this->add_sql(SQL_INSTALL, "INSERT INTO `" . $table_prefix . "roles` (`role_name`, `role_image`, `role_classes`) VALUES ('".$rolevalue['name']."', '".$rolevalue['image']."', '".$rolevalue['classes']."');");
      }
    }

  // ---------------------------------------------------------
  // UNINSTALL
  // ---------------------------------------------------------
	 for ($i = 1; $i <= count($raidplanSQL['uninstall']); $i++){
	   if($raidplanSQL['uninstall'][$i]){
	     $this->add_sql(SQL_UNINSTALL, $raidplanSQL['uninstall'][$i]);
	   }
	 }
	}

	/***************************************
	* Generate raidplan menus              *
	* @return array                        *
	****************************************/
  function gen_main_menu1(){
    global $user, $SID, $eqdkp_root_path, $db;

        if ($this->pm->check(PLUGIN_INSTALLED, 'raidplan') && $user->check_auth('u_raidplan_', false)){
          $main_menu1 = array(
          array(
  					'link' => 'plugins/' . $this->get_data('path') . '/listraids.php' . $SID,
  					'text' => $user->lang['rp_usermenu_raidplaner'],
  					'check' => 'u_raidplan_list'
  				),
  				array(
  					'link' => 'plugins/' . $this->get_data('path') . '/raidstats.php' . $SID,
  					'text' => $user->lang['rp_statistic2'],
  					'check' => 'u_raidplan_statistic'
  				));
          return $main_menu1;
        }
      return;
    }

	/***************************************
	* Generate raidplan admin menu         *
	* @return array                        *
	****************************************/
  function gen_admin_menu() {
    global $user, $SID, $eqdkp, $eqdkp_root_path, $db;
		$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';

    if ($this->pm->check(PLUGIN_INSTALLED, 'raidplan') && $user->check_auth('a_raidplan_', false)){
		  $admin_menu = array(
				'raidplan' => array(
					0 => $user->lang['raidplan'],
					1 => array(
						'link' => $url_prefix . 'plugins/raidplan/admin/settings.php' . $SID,
						'text' => $user->lang['settings'],
						'check' => 'a_raidplan_config'),
					2 => array(
						'link' => $url_prefix . 'plugins/raidplan/admin/logs.php' . $SID,
						'text' => $user->lang['rp_logmanager'],
						'check' => 'a_raidplan_logs'),
					3 => array(
						'link' => $url_prefix . 'plugins/raidplan/admin/index.php' . $SID,
						'text' => $user->lang['rp_manage'],
						'check' => 'a_raidplan_'),
					4 => array(
						'link' => $url_prefix . 'plugins/raidplan/admin/wildcard.php' . $SID,
						'text' => $user->lang['rp_wildcardmanager'],
						'check' => 'a_raidplan_wildcards')
				));
      return $admin_menu;
    }
    return;
  }

	/***************************************
	* Generate raidplan user menu          *
	* @return array                        *
	****************************************/
  function gen_settings_menu(){
    global $db, $user, $SID, $eqdkp, $table_prefix;
    if ( $this->pm->check(PLUGIN_INSTALLED, 'raidplan')){

      $settings_part = array(
          			0 => '<a class="plugins" href="plugins/raidplan/usersettings.php' . $SID . '">' . $user->lang['rp_usersettings'] . '</a>',
      );
      $settings_menu = array($user->lang['raidplan'] => $settings_part);
      return $settings_menu;
    }
    return;
  }

  /***************************************
	* Insert the config value into the DB  *
	* @return --                           *
	****************************************/
  function InsertIntoTable($tarray){
    global $eqdkp_root_path, $user, $SID, $table_prefix;
    foreach($tarray as $fieldname=>$insertvalue){
		$sql = "INSERT INTO " . $table_prefix . "raidplan_config VALUES ('".$fieldname."', '".$insertvalue."');";
		$this->add_sql(SQL_INSTALL, $sql);
		}
   }

  /***************************************
	* Set the perm. for installing user    *
	* @return --                           *
	****************************************/
	function set_permissions($perm_array, $perm_setting='Y'){
		global $table_prefix, $db, $user;
		$userid = ( $user->data['user_id'] != ANONYMOUS ) ? $user->data['user_id'] : '';
		if($userid){
		  foreach ($perm_array as $value) {
		    $sql = "INSERT INTO `".$table_prefix."auth_users` VALUES('".$userid."', '".$value."', '".$perm_setting."');";
    		$this->add_sql(SQL_INSTALL, $sql);
    		$sql = "UPDATE `".$table_prefix."auth_users` SET auth_setting='".$perm_setting."' WHERE user_id='".$userid."' AND auth_id='".$value."';";
    		$this->add_sql(SQL_INSTALL, $sql);
  		}
		}
	}
}
?>
