<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:05:50 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 12157 $
 * 
 * $Id: charmanager_plugin_class.php 12157 2012-09-30 20:05:50Z wallenium $
 */

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

class charmanager_plugin_class extends EQdkp_Plugin
{
	// Do not change
	var $version    = '1.7.0';
	var $build      = '11313';
	var $vstatus    = 'Stable';
	var $copyright  = 'WalleniuM';
	var $fwversion  = '2.0.0';  // required framework Version
	
    function charmanager_plugin_class($pm){
			global $eqdkp_root_path, $user, $table_prefix, $SID, $db;
        
      $this->eqdkp_plugin($pm);
      $this->pm->get_language_pack('charmanager');
        
      $this->add_data(array(
            'name'          => 'Character Manager',
            'code'          => 'charmanager',
            'path'          => 'charmanager',
            'contact'       => 'wallenium@eqdkp-plus.com',
            'template_path' => 'plugins/charmanager/templates/',
            'version'       => $this->version)
      );
        
      // Addition Information for eqdkpPLUS
      $this->additional_data = array(
            'author'            => 'WalleniuM',
            'description'       => $user->lang['cm_short_desc'],
            'long_description'  => $user->lang['cm_long_desc'],
            'homepage'          => 'http://www.eqdkp-plus.com/',
            'manuallink'        => false,
      );
        
      // Add Menus
      $this->add_menu('main_menu1', $this->gen_main_menu1());
      $this->add_menu('admin_menu', $this->gen_admin_menu());
	    $this->add_menu('settings', $this->gen_settings_menu()); 

      // Register our permissions
      $this->add_permission('954', 'a_charmanager_edit',  	'N', $user->lang['uc_edit_all']);
      $this->add_permission('959', 'a_charmanager_config',  'N', $user->lang['uc_config']);
      $this->add_permission('952', 'a_charmanager_delmanager','N', $user->lang['uc_delmanager']);
	    $this->add_permission('955', 'u_charmanager_manage',  'Y', $user->lang['uc_manage']);
	    $this->add_permission('956', 'u_charmanager_add',  		'Y', $user->lang['uc_add']);
	    $this->add_permission('957', 'u_charmanager_view',  	'Y', $user->lang['uc_view']);
	    $this->add_permission('958', 'u_charmanager_connect', 'Y', $user->lang['uc_connect']);
	    $this->add_permission('953', 'u_charmanager_delete',	'N', $user->lang['uc_delete']);
     		
     	//Grant permissions to installing user
    	if (!($this->pm->check(PLUGIN_INSTALLED, 'charmanager'))){
				$userid = ( $user->data['user_id'] != ANONYMOUS ) ? $user->data['user_id'] : '';
				$perm_array = array('952', '953', '954', '955', '956', '957', '958', '959');
				
				foreach ($perm_array as $value) {
					$this->set_permission($userid, $value);
				}
     	}
     		
			// Define installation
    	$sql = "CREATE TABLE IF NOT EXISTS __member_additions (
				    		`id` int(10) NOT NULL auto_increment, 
   							`member_id` smallint(5) default NULL, 
   							`picture` varchar(255) default NULL, 
   							`fir` SMALLINT(6) NOT NULL default '0', 
   							`nr` SMALLINT(6) NOT NULL default '0', 
   							`sr` SMALLINT(6) NOT NULL default '0', 
   							`ar` SMALLINT(6) NOT NULL default '0', 
   							`frr` SMALLINT(6) NOT NULL default '0', 
   							`skill_1` SMALLINT(6) NOT NULL default '0', 
   							`skill_2` SMALLINT(6) NOT NULL default '0', 
   							`skill_3` SMALLINT(6) NOT NULL default '0', 
   							`skill2_1` SMALLINT(6) NOT NULL default '0', 
   							`skill2_2` SMALLINT(6) NOT NULL default '0', 
   							`skill2_3` SMALLINT(6) NOT NULL default '0', 
   							`gender` varchar(255) default NULL, 
   							`guild` varchar(255) default NULL, 
   							`faction` varchar(255) default NULL, 
   							
   							`prof1_value`	varchar(255) NOT NULL default '0',
   							`prof1_name` varchar(255) default NULL,
   							`prof2_value`	varchar(255) NOT NULL default '0',
   							`prof2_name` varchar(255) default NULL,
   							
   							`health_bar`	varchar(255) default NULL,
   							`second_bar` varchar(255) default NULL,
   							`second_name` varchar(255) default NULL,
   							`last_update` varchar(255) default NULL,
   							
   							`notes` text default NULL,
   							
   							`blasc_id` varchar(255) default NULL, 
   							`ct_profile` varchar(255) default NULL, 
   							`curse_profiler` varchar(255) default NULL, 
   							`allakhazam` varchar(255) default NULL, 
   							`talentplaner` varchar(255) default NULL,
   							`requested_del` enum('0','1') NOT NULL default '0',
   							`require_confirm` enum('0','1') NOT NULL default '0',
   			PRIMARY KEY  (`id`) 
 			)";
			$this->add_sql(SQL_INSTALL, $sql);
			$sql = "CREATE TABLE IF NOT EXISTS __charmanager_config (
				          `config_name` varchar(255) NOT NULL default '',
                  `config_value` varchar(255) default NULL,
                  PRIMARY KEY  (`config_name`)
		              )";
			$this->add_sql(SQL_INSTALL, $sql);
			$this->InsertIntoTable('uc_inst_version', $this->version);
		    
			// Uninstall
			$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS " . $table_prefix . "charmanager_config");
			$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS " . $table_prefix . "member_additions");

			// System Message of Tasks...
	    if ($this->pm->check(PLUGIN_INSTALLED, 'charmanager')) {
	    	if($user->check_auth('a_charmanager_delmanager', false)){
		      $cm_reqdel = $db->query_first("SELECT count(*) FROM __member_additions WHERE requested_del='1'");
		      $cm_reqcnf = $db->query_first("SELECT count(*) FROM __member_additions WHERE require_confirm='1' && (requested_del!='1' or requested_del IS NULL)");
		      $cmadminjobs = $cm_reqdel + $cm_reqcnf;
		      if($cmadminjobs > 0){
		      	System_Message('<a href="'.$eqdkp_root_path.'plugins/charmanager/admin/admin_tasks.php'.$SID.'">'.sprintf($user->lang['cm_todo_txt'],$cmadminjobs).'</a>', $user->lang['cm_todo_head'], 'default');
		      }
	    	}
	    }
    }

		function gen_main_menu1(){
			global $user, $SID;

	        if ($this->pm->check(PLUGIN_INSTALLED, 'charmanager') && $user->check_auth('u_charmanager_view', false)){
	            global $db, $user, $eqdkp;
	            $main_menu1 = array(
	                array(
						'link' => 'plugins/charmanager/listprofiles.php' . $SID,
						'text' => $user->lang['uc_enu_profiles'],
						'check' => ''
					 ));
	         return $main_menu1;
	        }
	        return;
    }

		 function gen_admin_menu(){
        if ( $this->pm->check(PLUGIN_INSTALLED, 'charmanager') ){
            global $db, $user, $SID, $eqdkp_root_path, $eqdkp;
 						$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
 						
            $admin_menu = array(
				'charmanager' => array(
				  99 => './../../plugins/charmanager/images/cm_icon.png',
					0 => $user->lang['is_adminmenu_uc'],
					1 => array(
						'link' => $url_prefix.'plugins/charmanager/admin/settings.php' . $SID,
						'text' => $user->lang['uc_config'],
						'check' => 'a_charmanager_config'),
					2 => array(
						'link' => $url_prefix.'plugins/charmanager/admin/admin_tasks.php' . $SID,
						'text' => $user->lang['uc_delete_manager'],
						'check' => 'a_charmanager_delmanager'),
				)
			);
            return $admin_menu;
        }
        return;
    }

    function gen_settings_menu(){
    	global $db, $user, $SID, $eqdkp;
    	if ( $this->pm->check(PLUGIN_INSTALLED, 'charmanager') &&  ($user->check_auth('u_charmanager_manage', false) || $user->check_auth('u_charmanager_add', false)))
      {
        $settings_menu = array(
          $user->lang['charmanager'] => array(
              0 => '<a class="plugins" href="plugins/charmanager/index.php' . $SID . '">' . $user->lang['uc_manage_chars'] . '</a>',
          ));
          return $settings_menu;
        }
        return;
    }    
    
   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix;
		  $sql = "INSERT INTO __charmanager_config VALUES ('".$fieldname."', '".$insertvalue."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   }
   
   function set_permission($userid, $permissionid, $value='Y'){
		global $db;
		$sql = "UPDATE __auth_users SET auth_setting='".$value."' WHERE user_id='".$userid."' AND auth_id='".$permissionid."';";
		$db->query($sql);
		if (mysql_affected_rows() == 0){
			$sql = "INSERT INTO __auth_users VALUES('".$userid."', '".$permissionid."', '".$value."');";
			$db->query($sql);
		}
	}
}
?>
