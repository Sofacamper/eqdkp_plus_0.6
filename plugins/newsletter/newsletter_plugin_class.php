<?PHP 
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 1.3.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/


// prevent accessing this file directly

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}

// define Class
class newsletter_plugin_class extends EQdkp_Plugin{
  var $version    = '2.0.5';
  var $copyright  = 'GodMod';
  var $vstatus    = '';
  var $build      = '';
  var $fwversion  = '1.0.4';  // required framework Version

    function newsletter_plugin_class($pm)
    {
    // use globals
     global $eqdkp_root_path, $user, $SID, $table_prefix, $pcache, $eqdkp, $db, $pdc, $in;
        
    // call the parent's constructor
    $this->eqdkp_plugin($pm);
		
    // get language pack
    $this->pm->get_language_pack('newsletter');

    // data for this plugin
    $this->add_data(array(
            'name'					=> 'Newsletter Manager',
            'code'					=> 'newsletter',
            'path'					=> 'newsletter',
            'contact'				=> 'godmod23@web.de',
            'template_path'			=> 'plugins/newsletter/templates/',
            'version'				=> $this->version,)
    );
        
    // Addition Information for eqdkpPLUS
    $this->additional_data = array(
            'author'            	=> 'GodMod',    
            'description'       	=> $user->lang['nl_short_desc'],
            'long_description'  	=> $user->lang['nl_long_desc'],
            'homepage'          	=> 'http://www.eqdkp-plus.com',
            'manuallink'        	=> 'http://wiki.eqdkp-plus.com/de/index.php/Newsletter',
      );
        
	// Register permissions... this is for the permissions manager. the permission ID 
	// should not be used by ANY other plugin!
	// permissions of admins starts with "a_", for users with "u_"
      
     // (ID, Name, Enables Y/N, Language cariable)
     $this->add_permission('571', 'a_newsletter_manage',    'N', $user->lang['nl_manage']);
	 $this->add_permission('572', 'u_newsletter_view',    	'Y', $user->lang['nl_user_view']);
			
     // Add Menus
     // Name of menu/function (see above)
			$this->add_menu('main_menu1', $this->gen_main_menu1());    	// This is the main Menu
			$this->add_menu('main_menu2', $this->gen_user_menu());		// This is the newsletter-menu for users
			$this->add_menu('admin_menu', $this->gen_admin_menu());    	// This is the admin Menu
			$this->add_menu('settings',   $this->gen_settings_menu());  // This is the usersettings-Menu
			

      // Define installation.
      // -----------------------------------------------------
      if (!($this->pm->check(PLUGIN_INSTALLED, 'newsletter'))){
	
			$perm_array = array('571', '572');
    		$this->set_permissions($perm_array);
		
  	  } else {
		  
		  	if ($user->check_auth('a_newsletter_manage', false) && ($in->get('mode') != "continue" || $in->get('mode') != "send")){
		 		$queue_cache = $pdc->get('plugin.newsletter.queue',false,true);
				if ($queue_cache == "empty"){
				} else {
			
					$sql = $db->query("SELECT * FROM __newsletter_queue LIMIT 1");
					$sql_affected_rows = $db->affected_rows();
				
					//Write Cache-Entry is there a no mails in the queue
					if ($sql_affected_rows < 1){
						$pdc->put('plugin.newsletter.queue',"empty",86400,false,true);
					} else {
						System_Message($user->lang['nl_message_mails_in_queue'], $user->lang['newsletter'], 'default');			
					
					}
				}

			
			
			}
		
	  }; 
	  // END Define installation.
      
	  	//Template-Table
		$sql = "CREATE TABLE IF NOT EXISTS __newsletter_templates (
						`id` int(10) NOT NULL auto_increment,
						`name` varchar(255) default NULL,
						`subject` varchar(255) default NULL,
						`body` text default NULL,
						`signature` TINYINT NOT NULL DEFAULT 0,
       					 PRIMARY KEY  (`id`)
						 )";
		$this->add_sql(SQL_INSTALL, $sql);
		
		$sql = "INSERT INTO __newsletter_templates ( `id` , `subject` , `body`, `name`, `signature`)
                VALUES ('1', 'Vergessene Anmeldung im Raidplaner', 'Hallo *USERNAME*,\n\nDu hast dich bisher noch nicht für folgenden Raid angemeldet: *RPLINK*\nBitte melde dich entweder an oder ab. \n\nDanke, *AUTHOR*', 'RaidPlan Erinnerung', '1'
                )";
				
		$this->add_sql(SQL_INSTALL, $sql);
		
		//Queue-Table
		$sql = "CREATE TABLE IF NOT EXISTS __newsletter_queue (
						`id` int(10) NOT NULL auto_increment,
						`subject` TEXT,
						`template` TEXT,
						`template_type` varchar(30),
						`template_path` TEXT,
						`bodyvars` TEXT,
						`mail_method` varchar(25),
						`mail_type` varchar(25),
						`recipient_user` mediumint(5) NULL,
						`recipient_mail` varchar(255) NULL,
       					 PRIMARY KEY  (`id`)
						 )";
		$this->add_sql(SQL_INSTALL, $sql);

		//Archive-Table
		$sql = "CREATE TABLE IF NOT EXISTS __newsletter_archive (
					`id` INT PRIMARY KEY AUTO_INCREMENT,
					`letter_id` mediumint(5) NOT NULL default '0',
					`body` TEXT,
					`subject` TEXT,
					`recipients` TEXT,
					`date` DATETIME NOT NULL
		     )";
		  $this->add_sql(SQL_INSTALL, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS __newsletter_config (
        			`config_name` VARCHAR(255) NOT NULL PRIMARY KEY,
        			`config_value` TEXT DEFAULT NULL
            	)";
		
		//Create Signature:
		$signature = "--\n";
		$signature .= $user->lang['nl_signature_value'];
		$signature .= $eqdkp->config['guildtag'];
		$signature .= "\nDKP: ";
		$signature .= $this->BuildLink();
		
		//Insert into config-table
      	$this->add_sql(SQL_INSTALL, $sql);
	  	$this->InsertIntoTable('bridge_items', 10);
		$this->InsertIntoTable('bridge_preselected', 0);
		$this->InsertIntoTable('style_css', '');
		$this->InsertIntoTable('style_bg_color', '');
	  	$this->InsertIntoTable('style_font_color', '');
	  	$this->InsertIntoTable('enable_updatecheck', 1);
	  	$this->InsertIntoTable('public_subscribe', 1);
	  	$this->InsertIntoTable('public_archive', 1);
	  	$this->InsertIntoTable('signature', $signature);
	  	$this->InsertIntoTable('queue_locked', '0');
      	$this->InsertIntoTable('nl_inst_version', $this->version);
	  
	  	//Userconfig-Table
	  	$sql = "CREATE TABLE IF NOT EXISTS __newsletter_userconfig (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`config_name` varchar(255) NOT NULL default '',
      						`user_id` mediumint(5) NOT NULL default '0',
                  			`config_value` varchar(255) default NULL,
      						PRIMARY KEY  (id)
                  )";
		$this->add_sql(SQL_INSTALL, $sql);
	  
		//Letter-Table
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_prefix ."newsletter_letters (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`name` varchar(255) default NULL,
							`description` TEXT,
      						`permission` TINYINT NOT NULL DEFAULT 0,
							`archive` TINYINT NOT NULL DEFAULT 0,
							`preset_archive` TINYINT NOT NULL DEFAULT 0,
							`preset_type` VARCHAR(22),
							`preset_template` TINYINT NOT NULL DEFAULT 0,
      						PRIMARY KEY  (id)
              )";
	  
		$this->add_sql(SQL_INSTALL, $sql);
		
		//Subscriber-Table
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_prefix ."newsletter_subscribers (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`user_id` mediumint(5) NOT NULL default '0',
							`letter_id` mediumint(5) NOT NULL default '0',
      						`status` TINYINT NOT NULL DEFAULT 0,
							`date` DATETIME NOT NULL,
      						PRIMARY KEY  (id)
                  )";
	  
	  
		$this->add_sql(SQL_INSTALL, $sql);
      
	  
		// Define uninstallation
		// -----------------------------------------------------
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_templates");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_archive");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_config");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_userconfig");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_letters");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_subscribers");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __newsletter_queue");
		
		

  }
  
  
  // generate the Main Menu
  function gen_main_menu1(){
    global $user, $SID, $db, $eqdkp;
    
		$config = $this->get_config();
		
    	// check if its enabled
    	if ($this->pm->check(PLUGIN_INSTALLED, 'newsletter')){
		
			if ($config['public_archive'] != 0){
		
      			// Start the Menu array
      			$main_menu1 = array(array(
    						'link' => 'plugins/newsletter/archive.php' . $SID,
    						'text' => $user->lang['nl_archive'],
    						'check' => 'u_newsletter_view'
    					  ));
     	 		return $main_menu1;
			}
    	}
    return;
  }
  

  
  /**
	* Generate admin menu
	*
	* @return array
	*/
function gen_admin_menu()
    {
		global $user, $SID, $eqdkp, $eqdkp_root_path;
		$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
		
        if ($this->pm->check(PLUGIN_INSTALLED, 'newsletter') && $user->check_auth('a_newsletter_manage', false))
        {
            global $db, $user, $eqdkp_root_path;
						
			$admin_menu = array(
				'newsletter' => array(
				  99 => './../../plugins/newsletter/images/nl_logo.png',
					0 => $user->lang['newsletter'],
					1 => array(
						'link' => $url_prefix . 'plugins/newsletter/admin/send.php' . $SID,
						'text' => $user->lang['nl_send'],
						'check' => 'a_newsletter_manage'),
					2 => array(
						'link' => $url_prefix . 'plugins/newsletter/admin/newsletters.php' . $SID,
						'text' => $user->lang['nl_manage'],
						'check' => 'a_newsletter_manage'),	
					3 => array(
						'link' => $url_prefix . 'plugins/newsletter/admin/archive.php' . $SID,
						'text' => $user->lang['nl_ad_archive'],
						'check' => 'a_newsletter_manage'),
					4 => array(
						'link' => $url_prefix . 'plugins/newsletter/admin/templates.php' . $SID,
						'text' => $user->lang['nl_templates'],
						'check' => 'a_newsletter_manage'),
					5 => array(
						'link' => $url_prefix . 'plugins/newsletter/admin/settings.php' . $SID,
						'text' => $user->lang['nl_settings'],
						'check' => 'a_newsletter_manage'),
				  )
			);

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
	
	$config = $this->get_config();
	
    if ( $this->pm->check(PLUGIN_INSTALLED, 'newsletter')){
		
		if ($config['public_subscribe'] != 0){
		
			$settings_menu = array(
            $user->lang['nl_user_newsletter'] => array(
                0 => '<a class="plugins" href="plugins/newsletter/usersettings.php' . $SID . '">' . $user->lang['nl_usersettings'] . '</a>',
				
                ));
       
	   		return $settings_menu;
	   
		}
		
    
    }
    return;
  }
    
	function gen_user_menu(){
    global $db, $user, $SID, $eqdkp, $table_prefix;
	
	$config = $this->get_config();
    if ( $this->pm->check(PLUGIN_INSTALLED, 'newsletter')){
		
		if ($user->data['user_id'] != ANONYMOUS){
			
			if ($config['public_subscribe'] != 0){
		
			$user_menu = array(
           				1 => array(
						'link' => $url_prefix . 'plugins/newsletter/newsletters.php' . $SID,
						'text' => $user->lang['nl_user_subscribe'],
						'check' => 'u_newsletter_view'),
        	);
       
	   		return $user_menu;
			}
		
		
		} else {return;};
		
		
    
    }
    return;
  }
  
   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO __newsletter_config VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   }
   
   function BuildLink(){
      global $eqdkp;
    	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    	$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    	$server_name = trim($eqdkp->config['server_name']);
    	$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
      return 'http://' . $server_name . $server_port . $script_name;
   }

	function get_config(){
	  global $db, $pdc;
  		
		if ($this->pm->check(PLUGIN_INSTALLED, 'newsletter')){
   			  
			//Load the plugin-config
  			//Cache: plugin.newsletter.config
  			$config_cache = $pdc->get('plugin.newsletter.config',false,true);
			if (!$config_cache){
				$config_query = $db->query("SELECT * FROM __newsletter_config");
				$config_data = $db->fetch_record_set($config_query);
				$db->free_result($config_query);
				$pdc->put('plugin.newsletter.config',$config_data,86400,false,true);
			} else{
				$config_data = $config_cache;
			};
			if (is_array($config_data)){
				foreach ($config_data as $elem){
					$conf[$elem['config_name']] = $elem['config_value'];
				};
			};	
			
			return $conf;
  		};
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
		    $sql = "INSERT INTO `__auth_users` VALUES('".$db->escape($userid)."', '".$db->escape($value)."', '".$db->escape($perm_setting)."');";
    		$this->add_sql(SQL_INSTALL, $sql);
    		$sql = "UPDATE `__auth_users` SET auth_setting='".$db->escape($perm_setting)."' WHERE user_id='".$db->escape($userid)."' AND auth_id='".$db->escape($value)."';";
    		$this->add_sql(SQL_INSTALL, $sql);
  		}
		} 
	}
}
?>