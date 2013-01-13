<?PHP
  /**********************************************************************************\
  * Project:   Downloads for EQdkp-Plus                                              *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Project-Start:	 05/2009                                                         *
  * Author:    GodMod                                                                *
  * Copyright: 2009 GodMod                                                           *
  * Link:      http://eqdkp-plus.com/forum                                           *
  * Package:   Downloads                                                             *
  \**********************************************************************************/


// prevent accessing this file directly

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}


// define Class
class downloads_plugin_class extends EQdkp_Plugin{
  var $version    = '0.4.3';
  var $copyright  = 'GodMod';
  var $vstatus    = '';
  var $build      = '';
  //var $fwversion  = '1.0.4';  // required framework Version

    function downloads_plugin_class($pm)
    {
    // use globals
      global $eqdkp_root_path, $user, $SID, $table_prefix, $pcache, $eqdkp, $db;
        
    // call the parent's constructor
    $this->eqdkp_plugin($pm);
		
    // get language pack
    $this->pm->get_language_pack('downloads');

    // data for this plugin
    $this->add_data(array(
            'name'					=> 'EQdkp Plus Downloads',
            'code'					=> 'downloads',
            'path'					=> 'downloads',
            'contact'				=> 'godmod23@web.de',
            'template_path'			=> 'plugins/downloads/templates/',
            'version'				=> $this->version,)
    );
        
    // Addition Information for eqdkpPLUS
    $this->additional_data = array(
            'author'            => 'GodMod',    
			'description'     	=> $user->lang['dl_shortdesc'],
            'long_description'  => $user->lang['dl_description'],
            'homepage'          => 'http://www.eqdkp-plus.com',
            'manuallink'        => 'http://wiki.eqdkp-plus.com/de/index.php/Downloads',
      );
        
	// Register permissions... this is for the permissions manager. the permission ID 
	// should not be used by ANY other plugin!
	// permissions of admins starts with "a_", for users with "u_"
      
      // (ID, Name, Enables Y/N, Language cariable)
      $this->add_permission('8000', 'a_downloads_links',  'N', $user->lang['dl_ad_manage_links_ov']);
      $this->add_permission('8001', 'a_downloads_cat',  'N', $user->lang['dl_ad_manage_categories_ov']);
	  $this->add_permission('8002', 'a_downloads_cfg', 'N', $user->lang['dl_ad_manage_config_ov']);
	  $this->add_permission('8005', 'a_downloads_stats', 'N', $user->lang['dl_ad_statistics_ov']);
	  $this->add_permission('8006', 'a_downloads_import', 'N', $user->lang['dl_import']);
	  
      $this->add_permission('8003', 'u_downloads_upload', 'N', $user->lang['dl_ad_manage_upload_ov']);
	  $this->add_permission('8004', 'u_downloads_view',  'Y', $user->lang['dl_view_downloads_ov']);
			
      // Add Menus
      // Name of menu/function (see above)
	  $this->add_menu('main_menu1', $this->gen_main_menu1());    // This is the main Menu
	  $this->add_menu('admin_menu', $this->gen_admin_menu());    // This is the admin Menu

      // Define installation.
      // -----------------------------------------------------
      if (!($this->pm->check(PLUGIN_INSTALLED, 'downloads'))){
        	$perm_array = array('8000', '8001', '8002', '8003', '8004', '8005', '8006');
    		$this->set_permissions($perm_array);
			
			//Delete .htaccess if plugin is not installed.
			//unlink($pcache->FolderPath('uploads', 'files').".htaccess");
			
  		} 
		else{
			//Check if .htaccess-Check is enabled
			if ($this->CheckDisabledHtacc() == 0){

				//Check if .htaccess exists
				if(file_exists($pcache->FolderPath('downloads/').".htaccess")){} 
		
				else{	
						//Creates an empty index.html to prevent directory-listening if .htaccess doesn't work
						$pcache->FilePath('index.html', 'downloads/files');
						//Create a .htaccess
    					$htaccess = fopen($pcache->FolderPath('files', 'downloads').".htaccess", "w");
						$result = fputs($htaccess, 
             			"<Files *>\nOrder Allow,Deny\nDeny from All\n</Files>\n");
						fclose($htaccess);

						if ($result > 0){
							//System_Message('.htaccess has been created successfully','Downloads 4 EQdkp-plus','green');
						}
						else{
							System_Message('.htaccess could not be created. Please check the <a href="http://wiki.eqdkp-plus.com/en/index.php/Downloads">WIKI</a> to get information about securing your download-folder.','Error - Downloads 4 EQdkp-plus','red');
						}
				} //END Check .htaccess	
			
			}; //END Check is ht-check is enabled
		
		} // END Define installation.
		
		//Drop Tables to get no errors
      	//$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __downloads_categories");
	    //$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __downloads_links");
	    $this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __downloads_config");
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __downloads_stats");
		
		$sql = "CREATE TABLE IF NOT EXISTS __downloads_categories (
			`category_id` INT PRIMARY KEY AUTO_INCREMENT,
			`category_name` VARCHAR(255) NOT NULL,
			`category_comment` VARCHAR(255),
			`category_sortid` INT NOT NULL DEFAULT 0,
			`category_permission` TINYINT NOT NULL DEFAULT 0
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);
		  $this->InsertNewCategory('Standard', 'Standard', '1');

		$sql = "CREATE TABLE IF NOT EXISTS __downloads_links (
			`id` INT PRIMARY KEY AUTO_INCREMENT,
			`url` TEXT,
			`name` VARCHAR(255),
			`description` TEXT,
			`views` INT NOT NULL DEFAULT 0,
			`category` INT NOT NULL DEFAULT 0,
			`date` DATETIME NOT NULL,
			`local_filename` VARCHAR(255),
			`file_type` VARCHAR(20),
			`file_size` BIGINT,
			`preview_image` VARCHAR(255),
			`traffic` BIGINT NOT NULL DEFAULT 0,
			`related_downloads` TEXT,
			`mirrors` TEXT,
			`votes` INT NOT NULL DEFAULT 0,
			`rating_points` INT NOT NULL DEFAULT 0,
			`rating` INT NOT NULL DEFAULT 0,
			`voted_users` TEXT,
			`user_id` INT NOT NULL,
			`permission` TINYINT NOT NULL DEFAULT 0,
			`reported` TEXT
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);
		  
		  $sql = "CREATE TABLE __downloads_stats (
  				`ID` int(22) unsigned NOT NULL AUTO_INCREMENT,
  				`fileID` int(22) unsigned DEFAULT NULL,
  				`category` int(22) unsigned DEFAULT NULL,
  				`date` date DEFAULT NULL,
  				PRIMARY KEY (`ID`)
				)";
		  $this->add_sql(SQL_INSTALL, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS __downloads_config (
        `config_name` VARCHAR(255) NOT NULL PRIMARY KEY,
        `config_value` VARCHAR(255) DEFAULT NULL
            )";
      $this->add_sql(SQL_INSTALL, $sql);
	  $this->InsertIntoTable('disable_htacc_check', '0');
	  $this->InsertIntoTable('disable_categories', '0');
	  $this->InsertIntoTable('enable_related_links', '1');
	  $this->InsertIntoTable('enable_mirrors', '1');
	  $this->InsertIntoTable('enable_comments', '1');
	  $this->InsertIntoTable('accepted_file_types', 'jpg, png, gif, zip, rar, gz, bz');
	  $this->InsertIntoTable('traffic_limit', '');
	  $this->InsertIntoTable('traffic_month', '0');
	  $this->InsertIntoTable('traffic_total', '0');
	  $this->InsertIntoTable('traffic_reset', '0');
	  $this->InsertIntoTable('enable_recaptcha', '0');
	  $this->InsertIntoTable('enable_statistics', '1');
	  $this->InsertIntoTable('enable_statistics_ov', '1');
	  $this->InsertIntoTable('enable_updatecheck', '1');
	  $this->InsertIntoTable('enable_debug', '0');
	  $this->InsertIntoTable('items_per_page', '50');
	  $this->InsertIntoTable('show_link_on_tab', '0');
	  $this->InsertIntoTable('single_vote', '1');
	  $this->InsertIntoTable('folder_limit', '');
	  $this->InsertIntoTable('filesize_limit', '');
      $this->InsertIntoTable('inst_version', $this->version);
			
      // Define uninstallation
      // -----------------------------------------------------
	    //$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __downloads_categories");
	    //$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __downloads_links");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __downloads_config");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __downloads_stats");
		$this->add_sql(SQL_UNINSTALL, "DELETE FROM __plus_links WHERE link_url='".$db->escape($this->dkp_link()."plugins/downloads/downloads.php")."'");
		

  }
  
  // generate the Main Menu
  function gen_main_menu1(){
    global $user, $SID, $db, $eqdkp;
    
    // check if its enabled
    if ($this->pm->check(PLUGIN_INSTALLED, 'downloads')){
      // Start the Menu array
      $main_menu1 = array(array(
    						'link' => 'plugins/downloads/downloads.php' . $SID,
    						'text' => $user->lang['dl_view'],
    						'check' => 'u_downloads_view'
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
        if ($this->pm->check(PLUGIN_INSTALLED, 'downloads')){
          global $db, $user, $eqdkp_root_path;
						
    			$admin_menu = array(
    				'downloads' => array(
    					0 => $user->lang['downloads'],
    					1 => array(
    						'link' => $url_prefix . 'plugins/downloads/admin/categories.php' . $SID,
    						'text' => $user->lang['dl_ad_manage_categories'],
    						'check' => 'a_downloads_cat'),
    					2 => array(
    						'link' => $url_prefix . 'plugins/downloads/admin/downloads.php' . $SID,
    						'text' => $user->lang['dl_ad_manage_links'],
    						'check' => 'a_downloads_links'), 
    					3 => array(
                			'link' => $url_prefix . 'plugins/downloads/admin/settings.php' . $SID,
                			'text' => $user->lang['dl_ad_manage_config'],
                			'check' => 'a_downloads_cfg'),
						4 => array(
                			'link' => $url_prefix . 'plugins/downloads/admin/statistics.php' . $SID,
                			'text' => $user->lang['dl_ad_statistics'],
                			'check' => 'a_downloads_stats'),
						5 => array(
                			'link' => $url_prefix . 'plugins/downloads/admin/import.php' . $SID,
                			'text' => $user->lang['dl_import'],
                			'check' => 'a_downloads_import'),
    					99 => './../../plugins/downloads/images/admin_logo.png',
   				  )
    			 );

          return $admin_menu;
        }
      return;
    }
    
   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO " . $table_prefix . "downloads_config VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   }

   function InsertNewCategory($fieldname,$insertvalue, $sortid){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO " . $table_prefix . "downloads_categories (category_name, category_comment, category_sortid) VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."', '".$db->escape($sortid)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   }
   
      function CheckDisabledHtacc(){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db, $pdc;
			//Cache: plugin.downloads.config.disable_htacc_check
  			$config_cache = $pdc->get('plugin.downloads.config.disable_htacc_check',false,true);
			if (!$config_cache) {
  				$disable_htacc_query = $db->query("SELECT * FROM __downloads_config WHERE config_name = 'disable_htacc_check'");
  				$htacc = $db->fetch_record($disable_htacc_query);
				$pdc->put('plugin.downloads.config.disable_htacc_check',$htacc,86400,false,true);
			} else{
				$htacc = $config_cache;
			};
		

		  return $htacc['config_value'];
   }
   
    
	function dkp_link(){
			global $eqdkp; 
			// generate script URL
 			$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
 			$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
 			$server_name = trim($eqdkp->config['server_name']);
 			$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
  			$dl_server_url  = 'http://' . $server_name . $server_port . $script_name;
			return $dl_server_url;
	
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
