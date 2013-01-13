<?PHP
  /**********************************************************************************\
  * Project:   fightstats for EQdkp-Plus                                              *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Project-Start:	 05/2009                                                         *
  * Author:    GodMod                                                                *
  * Copyright: 2009 GodMod                                                           *
  * Link:      http://eqdkp-plus.com/forum                                           *
  * Package:   fightstats                                                             *
  \**********************************************************************************/


// prevent accessing this file directly

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}


// define Class
class fightstats_plugin_class extends EQdkp_Plugin{
  var $version    = '0.0.1';
  var $copyright  = 'GodMod';
  var $vstatus    = '';
  var $build      = '';

    function fightstats_plugin_class($pm)
    {
    // use globals
      global $eqdkp_root_path, $user, $SID, $table_prefix, $pcache, $eqdkp, $db, $conf_plus;
        
    // call the parent's constructor
    $this->eqdkp_plugin($pm);
		
    // get language pack
    $this->pm->get_language_pack('fightstats');

    // data for this plugin
    $this->add_data(array(
            'name'					=> 'Fightstats',
            'code'					=> 'fightstats',
            'path'					=> 'fightstats',
            'contact'				=> 'godmod23@web.de',
            'template_path'			=> 'plugins/fightstats/templates/',
            'version'				=> $this->version,)
    );
        
    // Addition Information for eqdkpPLUS
    $this->additional_data = array(
            'author'            => 'GodMod',    
						'description'     	=> $user->lang['fs_shortdesc'],
            'long_description'  => $user->lang['fs_description'],
            'homepage'          => 'http://www.eqdkp-plus.com',
            'manuallink'        => 'http://wiki.eqdkp-plus.com/de/index.php/Fightstats',
      );
        
	// Register permissions... this is for the permissions manager. the permission ID 
	// should not be used by ANY other plugin!
	// permissions of admins starts with "a_", for users with "u_"
      
      // (ID, Name, Enables Y/N, Language cariable)
      $this->add_permission('8300', 'a_fightstats_man',  'N', $user->lang['fs_manage']);

      $this->add_permission('8301', 'u_fightstats_view', 'N', $user->lang['fs_view']);
			
      // Add Menus
      // Name of menu/function (see above)
	  	//$this->add_menu('main_menu1', $this->gen_main_menu1());    // This is the main Menu
	  	$this->add_menu('admin_menu', $this->gen_admin_menu());    // This is the admin Menu

      // Define installation.
      // -----------------------------------------------------
      if (!($this->pm->check(PLUGIN_INSTALLED, 'fightstats'))){
        	$perm_array = array('8300', '8301');
    		$this->set_permissions($perm_array);
			
			
  		} else{
				
				include_once($eqdkp_root_path.'plugins/fightstats/include/fightstats.class.php');
				$fsclass = new fightstatsClass();
				
				$conf = $fsclass->get_config();
				if (time() > ((int)$conf['last_update'] + 3600*6)){				
					$fsclass->update_wol($conf_plus['wol_group_id']);
				}
		
		} // END Define installation.
		
		//Drop Tables to get no errors
      	//$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __fightstats_categories");
	    //$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __fightstats_links");
	  $this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __fightstats_config");
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __fightstats");
		
		$sql = "CREATE TABLE IF NOT EXISTS __fightstats (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`log_id` varchar(25) DEFAULT NULL,
					`bossCount` int(10) unsigned DEFAULT '0',
					`killCount` int(10) unsigned DEFAULT '0',
					`wipeCount` int(10) unsigned DEFAULT '0',
					`damageDone` int(10) unsigned DEFAULT '0',
					`damageTaken` int(10) unsigned DEFAULT '0',
					`date` bigint(13) DEFAULT '0',
					`duration` bigint(13) DEFAULT '0',
					`healingDone` bigint(20) DEFAULT '0',
					`participants` text,
					`zones` text,
					`bosses` text,
					PRIMARY KEY (`id`)
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);


		$sql = "CREATE TABLE IF NOT EXISTS __fightstats_config (
        `config_name` VARCHAR(255) NOT NULL PRIMARY KEY,
        `config_value` VARCHAR(255) DEFAULT NULL
            )";
     $this->add_sql(SQL_INSTALL, $sql);
	  	$this->InsertIntoTable('enable_updatecheck', '1');
			$this->InsertIntoTable('last_update', '0');
   	 $this->InsertIntoTable('inst_version', $this->version);
			
      // Define uninstallation
      // -----------------------------------------------------
	    //$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __fightstats_categories");
	    //$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __fightstats_links");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __fightstats_config");
			$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __fightstats");

  }
  
  // generate the Main Menu
  function gen_main_menu1(){
    global $user, $SID, $db, $eqdkp;
    
    // check if its enabled
    if ($this->pm->check(PLUGIN_INSTALLED, 'fightstats')){
      // Start the Menu array
      $main_menu1 = array(array(
    						'link' => 'plugins/fightstats/fightstats.php' . $SID,
    						'text' => $user->lang['dl_view'],
    						'check' => 'u_fightstats_view'
    					  )
    					 );
      return $main_menu1;
    }
    return;
  }
  
  /**
	* Generate admin menu
	*
	* @return array
	*/
    function gen_admin_menu(){
  		global $user, $SID, $eqdkp, $eqdkp_root_path;
  		$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
        if ($this->pm->check(PLUGIN_INSTALLED, 'fightstats')){
          global $db, $user, $eqdkp_root_path;
						
    			$admin_menu = array(
    				'fightstats' => array(
    					0 => $user->lang['fightstats'],
    					1 => array(
                			'link' => $url_prefix . 'plugins/fightstats/admin/settings.php' . $SID,
                			'text' => $user->lang['fs_settings'],
                			'check' => 'a_fightstats_man'),

   				  )
    			 );

          return $admin_menu;
        }
      return;
    }
    
   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO " . $table_prefix . "fightstats_config VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
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
		    $sql = "INSERT INTO `".$db->escape($table_prefix)."auth_users` VALUES('".$db->escape($userid)."', '".$db->escape($value)."', '".$db->escape($perm_setting)."');";
    		$this->add_sql(SQL_INSTALL, $sql);
    		$sql = "UPDATE `".$db->escape($table_prefix)."auth_users` SET auth_setting='".$db->escape($perm_setting)."' WHERE user_id='".$db->escape($userid)."' AND auth_id='".$db->escape($value)."';";
    		$this->add_sql(SQL_INSTALL, $sql);
  		}
		} 
	}
}
?>
