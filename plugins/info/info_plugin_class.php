<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-12-10 12:24:36 +0100 (Thu, 10 Dec 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 6657 $
 *
 * $Id: info_plugin_class.php 6657 2009-12-10 11:24:36Z godmod $
 */

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

class info_Plugin_Class extends EQdkp_Plugin {

  var $additional_data;
  var $version = '2.1.7';
  
	function info_plugin_class($pm) {
		global $eqdkp_root_path, $user;
	
    include_once(dirname(__FILE__) . '/include/functions.php');

		$this->eqdkp_plugin($pm);
		$this->pm->get_language_pack('info');

		$this->add_data(array (
			'name' => 'Infopages',
			'code' => 'info',
			'path' => 'info',
			'contact' => 'sz3@gmx.net',
			'template_path' => 'plugins/info/templates/',
			'version' => $this->version
		));
		
		$this->additional_data = array(
		  'author' => 'sz3',
    	'description' => $user->lang['info_short_desc'],
    	'long_description'  => $user->lang['info_long_desc'],
      'homepage'          => 'http://www.eqdkp-plus.com/',
      'manuallink'        => false,
    );
    
		//Permissions
		$this->add_permission('2312', 'a_information_conf', 'N', $user->lang['pm_info_conf']);
		$this->add_permission('2313', 'a_information_man', 'N', $user->lang['pm_info_man']);
		$this->add_permission('2311', 'u_information_view', 'Y', $user->lang['pm_info_view']);
		
		if (!($this->pm->check(PLUGIN_INSTALLED, 'info'))){

		//Drop table on install
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __info_config");
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __info_pages");


		//Grant permissions to installing user
		if ( $user->data['user_id'] != ANONYMOUS ){
			$sql = "DELETE FROM ".AUTH_USERS_TABLE." WHERE user_id='".$user->data['user_id']."' AND auth_id IN ('2311', '2312', '2313')";
      		$this->add_sql(SQL_INSTALL, $sql);
      
     		$sql = "INSERT INTO ".AUTH_USERS_TABLE." (user_id, auth_id, auth_setting) VALUES ('".$user->data['user_id']."','2311','Y')";
      		$this->add_sql(SQL_INSTALL, $sql);
      
      		$sql = "INSERT INTO ".AUTH_USERS_TABLE." (user_id, auth_id, auth_setting) VALUES ('".$user->data['user_id']."','2312','Y')";
      		$this->add_sql(SQL_INSTALL, $sql);
      
      		$sql = "INSERT INTO ".AUTH_USERS_TABLE." (user_id, auth_id, auth_setting) VALUES ('".$user->data['user_id']."','2313','Y')";
      		$this->add_sql(SQL_INSTALL, $sql);
    	}
		
		//Configuration table
		$sql = "CREATE TABLE IF NOT EXISTS __info_config (
			        `config_name` varchar(255) default '',
			        `config_value` varchar(255) default '',
					PRIMARY KEY  (`config_name`))";
		$this->add_sql(SQL_INSTALL, $sql);

    $this->InsertIntoTable('euchk', '1');
		$this->InsertIntoTable('ee', '1');
		$this->InsertIntoTable('ec', '80');
		$this->InsertIntoTable('er', '20');
    
    //Write version info
    $this->InsertIntoTable('info_inst_version', $this->version);		 
		
		//Pages table
		$sql2 = "CREATE TABLE IF NOT EXISTS __info_pages (
					`page_id` mediumint(8) unsigned NOT NULL auto_increment,
					`page_title` varchar(255) NOT NULL,
					`page_content` text,
					`page_ise` enum('0','1') NOT NULL default '0',
					`page_visibility` enum('0','1', '2') NOT NULL default '0',
					`page_menu_link` varchar(255) NOT NULL,
					`page_edit_user` smallint(5) NOT NULL,
					`page_edit_date` int(11) NOT NULL,
			     PRIMARY KEY  (`page_id`))";
		$this->add_sql(SQL_INSTALL, $sql2);
		}

		if ($this->pm->check(PLUGIN_INSTALLED, 'info')){
	        //Menus
			$page_links = getPageLinks();
			if (is_array($page_links)){
				$this->add_menu('main_menu1', $this->gen_main_menu($page_links, 1));
				$this->add_menu('main_menu2', $this->gen_main_menu($page_links, 2));
			}
			$this->add_menu('admin_menu', $this->gen_admin_menu());
		}
		
		//Drop table on deinstall
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __info_config");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __info_pages");
	}

	function gen_main_menu($page_links, $linkid) {
	global $db, $user, $SID;
  	if ($this->pm->check(PLUGIN_INSTALLED, 'info')) {  
      $main_menu = array();
      $url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
      foreach ($page_links as $page => $pv){
        if ($pv['page_menu_link'] == $linkid){
          $main_menu[] = array (
            'link' => $url_prefix.'plugins/'.$this->get_data('path').'/info.php'.$SID.'&amp;page='.$page,
            'text' => $pv['page_title'],
            'check' => 'u_information_view'
          );  
        }
      }
			return $main_menu;
		}
		return array();
	}


	function gen_admin_menu() {
		if ($this->pm->check(PLUGIN_INSTALLED, 'info')) {
			global $user, $SID, $eqdkp_root_path;
	
			$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';	
			
			$admin_menu = array (
				'info' => array (
					0 => $user->lang['am_info_title'],
					1 => array (
						'link' => $url_prefix . 'plugins/info/admin/settings.php' . $SID,
						'text' => $user->lang['am_info_conf'],
						'check' => 'a_information_conf'
					),
					2 => array (
						'link' => $url_prefix . 'plugins/info/admin/manage.php' . $SID,
						'text' => $user->lang['am_info_man'],
						'check' => 'a_information_man'
					)
				)
			);

			return $admin_menu;
		}
		return;
	}

	function InsertIntoTable($fieldname, $insertvalue) {
		$sql = "INSERT INTO __info_config VALUES ('" . $fieldname . "', '" . $insertvalue . "');";
		$this->add_sql(SQL_INSTALL, $sql);
  }
								 
}
?>
