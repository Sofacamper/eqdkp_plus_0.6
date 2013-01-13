<?PHP
/*************************************************\
*             mediacenter 4 EQdkp plus            *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod & BadTwin                        *
* Copyright:  GodMod & BadTwin  	              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/


// prevent accessing this file directly

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}

// define Class
class mediacenter_plugin_class extends EQdkp_Plugin{
  var $version    = '0.1.1';
  var $copyright  = 'GodMod';
  var $vstatus    = '';
  var $build      = '';

    function mediacenter_plugin_class($pm)
    {
    // use globals
      global $eqdkp_root_path, $user, $SID, $table_prefix, $pcache, $eqdkp, $db;
        
    // call the parent's constructor
    $this->eqdkp_plugin($pm);
		
    // get language pack
    $this->pm->get_language_pack('mediacenter');
	

    // data for this plugin
    $this->add_data(array(
            'name'					=> 'EQdkp Plus MediaCenter',
            'code'					=> 'mediacenter',
            'path'					=> 'mediacenter',
            'contact'				=> 'godmod23@web.de',
            'template_path'			=> 'plugins/mediacenter/templates/',
            'version'				=> $this->version,)
    );
        
    // Addition Information for eqdkpPLUS
    $this->additional_data = array(
            'author'            => 'GodMod',    
			'description'     	=> $user->lang['mc_shortdesc'],
            'long_description'  => $user->lang['mc_description'],
            'homepage'          => 'http://www.eqdkp-plus.com',
            'manuallink'        => 'http://wiki.eqdkp-plus.com/de/index.php/MediaCenter',
      );
        
	// Register permissions... this is for the permissions manager. the permission ID 
	// should not be used by ANY other plugin!
	// permissions of admins starts with "a_", for users with "u_"
      
      // (ID, Name, Enables Y/N, Language cariable)
      $this->add_permission('8100', 'a_mediacenter_media',  'N', $user->lang['mc_manage_videos']);
      $this->add_permission('8101', 'a_mediacenter_cfg',  'N', $user->lang['mc_settings']);
	  $this->add_permission('8102', 'a_mediacenter_stats',  'N', $user->lang['mc_stats']);
      $this->add_permission('8105', 'a_mediacenter_import',  'N', $user->lang['mc_import']);
	  $this->add_permission('8103', 'u_mediacenter_view',  'Y', $user->lang['mc_view']);
	  $this->add_permission('8104', 'u_mediacenter_upload',  'Y', $user->lang['mc_upload']);
	  
			
      // Add Menus
      // Name of menu/function (see above)
			$this->add_menu('main_menu1', $this->gen_main_menu1());    // This is the main Menu
			$this->add_menu('admin_menu', $this->gen_admin_menu());    // This is the admin Menu
			$this->add_menu('settings',   $this->gen_usersettings_menu());  // This is the usersettings-Menu

      // Define installation.
      // -----------------------------------------------------
      if (!($this->pm->check(PLUGIN_INSTALLED, 'mediacenter'))){	
			$perm_array = array('8100', '8101', '8102', '8103', '8104', '8105');
    		$this->set_permissions($perm_array);
  		} 
		else{
			//If it is installed
			if ($user->check_auth('a_mediacenter_media', false)){
				$query = $db->query("SELECT * FROM __mediacenter_media WHERE status = '0'");
				$count = $db->affected_rows();
				$query = $db->query("SELECT * FROM __mediacenter_media WHERE reported != ''");
				$count = $count + $db->affected_rows();

				if ($count > 0){

					System_Message($user->lang['mc_admin_todo'],'EQdkp-MediaCenter');
				}
			}
		} // END Define installation.
      	
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __mediacenter_categories");
	  $this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __mediacenter_media");
	  $this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __mediacenter_config");
		$this->add_sql(SQL_INSTALL, "DROP TABLE IF EXISTS __mediacenter_userconfig");
			  	//Userconfig-Table
	  	$sql = "CREATE TABLE IF NOT EXISTS __mediacenter_userconfig (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`config_name` varchar(255) NOT NULL default '',
      						`user_id` mediumint(5) NOT NULL default '0',
                  			`config_value` varchar(255) default NULL,
      						PRIMARY KEY  (id)
                  )";
		$this->add_sql(SQL_INSTALL, $sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS __mediacenter_categories (
			`category_id` INT PRIMARY KEY AUTO_INCREMENT,
			`category_name` VARCHAR(255) NOT NULL,
			`category_comment` VARCHAR(255),
			`category_sortid` INT NOT NULL DEFAULT 0,
			`category_permission` VARCHAR(255) NOT NULL
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);
			
			if (strtolower($eqdkp->config['default_game']) == strtolower('WoW')){			
				$this->InsertNewCategory('Bossguides: Eiskronenzitadelle', 'Video-Bossguides Eiskronenzitadelle - powered by allvatar.com', 2);
				$this->InsertNewCategory('Bossguides: Kolosseum der Kreuzfahrer', 'Video-Bossguides Kolosseum der Kreuzfahrer - powered by allvatar.com', 3);
				$this->InsertNewCategory('Bossguides: Ulduar', 'Video-Bossguides Ulduar - powered by allvatar.com', 4);
				$this->InsertNewCategory('Bossguides: Sartharion', 'Video-Bossguide Sartharion - powered by allvatar.com', 5);
				$this->InsertNewCategory('Bossguides: Malygos', 'Video-Bossguide Malygos - powered by allvatar.com', 6);
				$this->InsertNewCategory('Bossguides: Pechschwingenabstieg', 'Video-Bossguides Cataclysm Pechschwingenabstieg - powered by allvatar.com', 1);		
			}	

		$sql = "CREATE TABLE IF NOT EXISTS __mediacenter_media (
			`id` INT PRIMARY KEY AUTO_INCREMENT,
			`url` TEXT,
			`name` TEXT,
			`extension` VARCHAR(20),
			`type` VARCHAR(35),
			`description` TEXT,
			`story` TEXT,
			`duration` VARCHAR(35) DEFAULT 0,
			`category` INT NOT NULL DEFAULT 0,
			`date` DATETIME NOT NULL,
			`user_id` INT NOT NULL,
			`thumbnail` VARCHAR(255),
			`preview_image` VARCHAR(255),
			`tags` TEXT,
			`views` INT NOT NULL DEFAULT 0,
			`votes` INT NOT NULL DEFAULT 0,
			`rating_points` INT NOT NULL DEFAULT 0,
			`rating` INT NOT NULL DEFAULT 0,
			`user_voted` TEXT,
			`status` INT NOT NULL DEFAULT 0,
			`reported` TEXT,
			`local_filename` VARCHAR(255),
			
			FULLTEXT(name,tags,description)
			
		       ) ENGINE=MyISAM";
		  $this->add_sql(SQL_INSTALL, $sql);
			
			if (strtolower($eqdkp->config['default_game']) == strtolower('WoW')){
						//Cata Pechschwingenabstieg
						$this->InsertNewVideo('http://www.youtube.com/watch?v=Aoudjj5AnGI', 'Guide Pechschwingenabstieg: Magmaul (by Irae AoD)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/2100-0-WoW-Cataclysm-Pechschwingenabstieg-Boss-Video-Guide-Magmaul.html', '666', '6', 'allvatar, Boss Guide, Pechschwingenhorst, Cata', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=VUC7ZRgGmV4', 'Guide Pechschwingenabstieg: Omnitron Verteidigungssystem (by Irae AoD)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/2101-0-WoW-Cataclysm-Pechschwingenabstieg-Boss-Video-Guide-Omnitron-Verteidigungssystem.html', '720', '6', 'allvatar, Boss Guide, Pechschwingenhorst, Cata', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=eQVzzQrr668', 'Guide Pechschwingenabstieg: Maloriak (by Irae AoD)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/2102-0-WoW-Cataclysm-Pechschwingenabstieg-Boss-Video-Guide-Maloriak.html', '835', '6', 'allvatar, Boss Guide, Pechschwingenhorst, Cata', '', '');
						
						//Icecrowncitadell - Guides
						$this->InsertNewVideo('http://www.youtube.com/watch?v=BKwkyXpElus', 'Guide Eiskronenzitadelle: Valithria Dreamwalker (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1451-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Valithria-Dreamwalker.html', '351', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=plY4iP3sRAI', 'Guide Eiskronenzitadelle: Blood-Queen Lana\'thel (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1450-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Blood-Queen-Lana-thel.html', '341', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=mLxf_-WwTrU', 'Guide Eiskronenzitadelle: Blood Council (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1449-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Blood-Council.html', '278', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						
						
						$this->InsertNewVideo('http://www.youtube.com/watch?v=KH_CAtXm0sI', 'Guide Eiskronenzitadelle: Professor Putricide (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1448-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Professor-Putricide.html', '428', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=nHjJDZCAqKA', 'Guide Eiskronenzitadelle: Rotface (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1447-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Rotface.html', '289', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=1-K-mgu0Ohs', 'Guide Eiskronenzitadelle: Festergut (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1446-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Festergut.html', '245', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						
						$this->InsertNewVideo('http://www.youtube.com/watch?v=p3xMIzsV7xs', 'Guide Eiskronenzitadelle: The Deathbringer (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1444-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-The-Deathbringer.html', '335', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=AQ0wMJhbkl0', 'Guide Eiskronenzitadelle: Gunship Battle  (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1443-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Gunship-Battle-.html', '304', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');						
						$this->InsertNewVideo('http://www.youtube.com/watch?v=DKYBAFzu6EE', 'Guide Eiskronenzitadelle: Lady Deathwhisper (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1445-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Lady-Deathwhisper.html', '363', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=27txYcKe704', 'Guide Eiskronenzitadelle: Lord Marrowgar (by For the Horde)', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1442-0-WoW-WotLK-Eiskronenzitadelle-For-the-Horde-Video-Guide-Lord-Marrowgar.html', '209', '1', 'allvatar, Boss Guide, Eiskronenzitadelle', '', '');
								
						//Crusader-Guides
						$this->InsertNewVideo('http://www.youtube.com/watch?v=BMJlmYijwMM', 'Guide Kolosseum der Kreuzfahrer Northrend Beasts Heroi by Entropy', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1233-0-WoW-WotLK-Kolosseum-der-Kreuzfahrer-Heroic-Boss-Video-Guides-Northrend-Beasts.html', '594', '2', 'allvatar, Boss Guide, Kolloseum der Kreuzfahrer', '', '');
						$this->InsertNewVideo('http://www.youtube.com/watch?v=131L7PVNiZg', 'Guide Kolosseum der Kreuzfahrer Jaraxxus Heroic by Entropy', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1234-0-WoW-WotLK-Kolosseum-der-Kreuzfahrer-Heroic-Boss-Video-Guides-Lord-Jarraxus.html', '381', '2', 'allvatar, Boss Guide, Kolloseum der Kreuzfahrer', '', '');
								
						$this->InsertNewVideo('http://www.youtube.com/watch?v=OySlxBnpwbc', 'Guide Kolosseum der Kreuzfahrer Faction Champions Heroic by Entropy', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1235-0-WoW-WotLK-Kolosseum-der-Kreuzfahrer-Heroic-Boss-Video-Guides-Factions-Champions.html', '433', '2', 'allvatar, Boss Guide, Kolloseum der Kreuzfahrer', '', '');
						
						$this->InsertNewVideo('http://www.youtube.com/watch?v=a8G6XZtwms4', 'Guide Kolosseum der Kreuzfahrer Valkyre Twins Heroic by Entropy', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1236-0-WoW-WotLK-Kolosseum-der-Kreuzfahrer-Heroic-Boss-Video-Guides-Valkyre-Twins.html', '420', '2', 'allvatar, Boss Guide, Kolloseum der Kreuzfahrer', '', '');
						
						$this->InsertNewVideo('http://www.youtube.com/watch?v=8jayGY8e0Jc', 'Guide Kolosseum der Kreuzfahrer Anub\'Arak Heroic by Entropy', 'youtube', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1237-0-WoW-WotLK-Kolosseum-der-Kreuzfahrer-Heroic-Boss-Video-Guides-Anub-Arak.html', '446', '2', 'allvatar, Boss Guide, Kolloseum der Kreuzfahrer', '', '');
						
						//Boss-Guides Ulduar
						$this->InsertNewVideo('http://dailymotion.com/search/video/x92ey4_allvatar-presents-iron-council-guid_videogames', 'allvatar presents: Iron Council Guide by In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1014-0-WoW-WotLK-Ulduar-Video-Guide-The-Iron-Council.html', '423', '3', 'allvatar, Boss Guide, Ulduar', '', '');
						$this->InsertNewVideo('http://dailymotion.com/search/video/x92jun_allvatar-presents-razorscale-guide_videogames', 'allvatar presents: Razorscale Guide by In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/1019-0-WoW-WotLK-Ulduar-Video-Guide-Razorscale.html', '401', '3', 'allvatar, Boss Guide, Ulduar', '', '');
						
						$this->InsertNewVideo('http://dailymotion.com/search/video/x92i8y_allvatar-presents-xt002-deconstruct_videogames', 'allvatar presents: XT002 Deconstructor Guide by In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1020-0-WoW-WotLK-Ulduar-Video-Guide-XT-002-Deconstructor.html', '398', '3', 'allvatar, Boss Guide, Ulduar', '', '');
						
						$this->InsertNewVideo('http://dailymotion.com/search/video/x9279f_allvatar-presents-yogg-saron-video_videogames', 'allvatar presents: Yogg Saron Video Guide by In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://wow.allvatar.com/rex/1024-0-WoW-WotLK-Ulduar-Video-Guide-Yogg-Saron.html', '756', '3', 'allvatar, Boss Guide, Ulduar', '', '');	
						
						//Boss-Guide Sartharion
							$this->InsertNewVideo('http://www.dailymotion.com/search/video/x7oqw7_sartharion-videoguide-by-allvatarco_videogames', 'Sartharion Videoguide by allvatar.com & In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/780-0-WotLK-Naxxramas-Video-Guide-Sartharion.html', '491', '4', 'allvatar, Boss Guide, Sartharion', '', '');		
						
						//Boss-Guide Malygos
							
							$this->InsertNewVideo('http://www.dailymotion.com/search/video/x7ossm_malygos-videoguide-by-allvatarcom-i_videogames', 'Malygos Videoguide by allvatar.com & In Harmony', 'dailymotion', 'video', 'Eine ausführliche Beschreibung dieses Encounters findet ihr unter http://www.allvatar.com/rex/779-0-WotLK-Naxxramas-Video-Guide-Mylygos.html', '793', '5', 'allvatar, Boss Guide, Malygos', '', '');		
			
			}

		$sql = "CREATE TABLE IF NOT EXISTS " .$table_prefix ."mediacenter_config (
        `config_name` VARCHAR(255) NOT NULL PRIMARY KEY,
        `config_value` VARCHAR(255) DEFAULT NULL
            )";
    $this->add_sql(SQL_INSTALL, $sql);
	  $this->InsertIntoTable('enable_statistics', '1');
	  $this->InsertIntoTable('prune_statistics', '');
	  $this->InsertIntoTable('single_vote', '1');
	  $this->InsertIntoTable('admin_activation', '1');
	  $this->InsertIntoTable('enable_comments', '1');
	  $this->InsertIntoTable('enable_updatecheck', '1');
	  $this->InsertIntoTable('show_link_on_tab', '0');
	  $this->InsertIntoTable('items_per_page', '25');
	  $this->InsertIntoTable('default_view', '0');
	  $this->InsertIntoTable('disable_reportmail', '0');
	  
      $this->InsertIntoTable('inst_version', $this->version);
	  
	  		  $sql = "CREATE TABLE __mediacenter_stats (
  				`ID` int(22) unsigned NOT NULL AUTO_INCREMENT,
  				`fileID` int(22) unsigned DEFAULT NULL,
  				`category` int(22) unsigned DEFAULT NULL,
  				`date` date DEFAULT NULL,
				`count` TEXT,
  				PRIMARY KEY (`ID`)
				)";
			  $this->add_sql(SQL_INSTALL, $sql);
			
      // Define uninstallation
      // -----------------------------------------------------
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __mediacenter_categories");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __mediacenter_media");
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __mediacenter_config");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __mediacenter_stats");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __mediacenter_userconfig");
		$this->add_sql(SQL_UNINSTALL, "DELETE FROM __plus_links WHERE link_url='".$db->escape($this->dkp_link()."plugins/mediacenter/index.php")."'");
		

  }
  
  // generate the Main Menu
  function gen_main_menu1(){
    global $user, $SID, $db, $eqdkp;
    
    // check if its enabled
    if ($this->pm->check(PLUGIN_INSTALLED, 'mediacenter')){
      // Start the Menu array
      $main_menu1 = array(array(
    						'link' => 'plugins/mediacenter/index.php' . $SID,
    						'text' => $user->lang['mc_mediacenter_short'],
    						'check' => 'u_mediacenter_view'
    					  )
    					 );
      return $main_menu1;
    }
    return;
  } //Close function gen_main_menu1
  
  /**
	* Generate admin menu
	*
	* @return array
	*/
    function gen_admin_menu(){
  		global $user, $SID, $eqdkp, $eqdkp_root_path;
  		$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
        if ($this->pm->check(PLUGIN_INSTALLED, 'mediacenter')){
          global $db, $user, $eqdkp_root_path;
						
    			$admin_menu = array(
    				'mediacenter' => array(
    					0 => $user->lang['mc_mediacenter_short'],
    					1 => array(
    						'link' => $url_prefix . 'plugins/mediacenter/admin/categories.php' . $SID,
    						'text' => $user->lang['mc_categories'],
    						'check' => 'a_mediacenter_media'),
    					2 => array(
    						'link' => $url_prefix . 'plugins/mediacenter/admin/media.php' . $SID,
    						'text' => $user->lang['mc_manage_videos'],
    						'check' => 'a_mediacenter_media'), 
						3 => array(
    						'link' => $url_prefix . 'plugins/mediacenter/admin/statistics.php' . $SID,
    						'text' => $user->lang['mc_stats'],
    						'check' => 'a_mediacenter_stats'), 
    					4 => array(
                			'link' => $url_prefix . 'plugins/mediacenter/admin/settings.php' . $SID,
                			'text' => $user->lang['mc_config'],
                			'check' => 'a_mediacenter_cfg'),
						5 => array(
                			'link' => $url_prefix . 'plugins/mediacenter/admin/import.php' . $SID,
                			'text' => $user->lang['mc_import'],
                			'check' => 'a_mediacenter_import'),
    					99 => './../../plugins/mediacenter/images/admin_logo.png',
   				  )
    			 );

          return $admin_menu;
        }
      return;
    } //close function gen_admin_menu
	
  function gen_usersettings_menu(){
    global $db, $user, $SID, $eqdkp, $table_prefix;

	
    if ( $this->pm->check(PLUGIN_INSTALLED, 'mediacenter')){
		

		
			$settings_menu = array(
            $user->lang['mc_mediacenter'] => array(
                0 => '<a class="plugins" href="plugins/mediacenter/usersettings.php' . $SID . '">' . $user->lang['mc_settings'] . '</a>',
				
                ));
       
	   		return $settings_menu;
	   

		
    
    }
    return;
  }
    
   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO __mediacenter_config VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   } //close function InsertIntoTable

   function InsertNewCategory($fieldname,$insertvalue, $sortid){
   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		  $sql = "INSERT INTO __mediacenter_categories (category_name, category_comment, category_sortid) VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."', '".$db->escape($sortid)."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   } //close function InsertNewCategory
      
	 function InsertNewVideo($url, $name, $extension, $type, $desc, $duration, $cat, $tags, $preview, $thumb){

   		global $eqdkp_root_path, $user, $SID, $table_prefix, $db;
		
		if ($extension == "youtube"){
			$video_id = preg_replace("#(^|[\n ])([\w]+?://)(www\.youtube|youtube)(\.[\w\.]+?/watch\?v=)([\w-]+)([&][\w=+&;%]*)*(^[\t <\n\r\]\[])*#is", '\\5', $url);
			if ($video_id){
				$thumb = 'http://i.ytimg.com/vi/'.$video_id.'/2.jpg';
				$preview = 'http://i.ytimg.com/vi/'.$video_id.'/0.jpg';								
			}
		}
		
		if ($extension == "dailymotion"){
			$video_id =  preg_replace("#(^|[\n])([\w]+?://)(www\.dailymotion|dailymotion)(.*?/)([\w\.]+?/video/)([\w-]+)(^[\t <\n\r\]\[])*#is", 'http://www.dailymotion.com/thumbnail/video/\\6', $url);
			$thumb = $video_id;
			$preview = $video_id;
		}
		
		  $sql = "INSERT INTO __mediacenter_media (url, name, extension, type, description, duration, category, tags, preview_image, thumbnail, user_id, date, status) VALUES ('".$db->escape($url)."', '".$db->escape($name)."', '".$db->escape($extension)."', '".$db->escape($type)."', '".$db->escape($desc)."', '".$db->escape($duration)."', '".$db->escape($cat)."', '".$db->escape($tags)."', '".$db->escape($preview)."', '".$db->escape($thumb)."', '".$db->escape($user->data['user_id'])."', NOW(), '1'
																																																										 																																																										 );";
		  $this->add_sql(SQL_INSTALL, $sql);
   } //close function InsertNewCategory
   
 
   
   
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
