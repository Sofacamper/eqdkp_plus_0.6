<?PHP
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Downloads-plugin is not installed.'); }
$hosting_mode = ($_HMODE) ? true : false;

// Check user permission
$user->check_auth('a_downloads_cfg');

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('downloads', '', 'downloads_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'downloads_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'downloads',
      'version' => $pm->get_data('downloads', 'version'),
      'build' 	=> $pm->plugins['downloads']->build,
      'vstatus' => $pm->plugins['downloads']->vstatus,
      'enabled' => $updchk_enbled
      );


// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rbvcheck->PerformUpdateCheck();

// reset the version (to force an update)
if($in->get('version') == 'reset'){
	$gupdater ->DeleteVersionString();
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}
// reset the version (to force an update)
if($in->get('stats') == 'reset'){
	$db->query("DELETE FROM __downloads_stats");
}

if ($in->get('submit') != ""){
		

		
		//Update related downloads
		$dlclass->update_config($in->get('enable_related_links', 0), "enable_related_links");
		
		//Update mirrors
		$dlclass->update_config($in->get('enable_mirrors', 0), "enable_mirrors");
	
		//Update Comments for downloads
		$dlclass->update_config($in->get('enable_comments', 0), "enable_comments");
		
		//Update Statistics
		$dlclass->update_config($in->get('enable_statistics_ov', 0), "enable_statistics_ov");
		
		//Update Stats
		$dlclass->update_config($in->get('enable_statistics', 0), "enable_statistics");
		
		//Update Prune Stats
		$dlclass->update_config($in->get('prune_statistics'), "prune_statistics");
		
		//Update Single-Vote
		$dlclass->update_config($in->get('single_vote', 0), "single_vote");
		

		if ($hosting_mode == false){
			//Update accepted file types
			$dlclass->update_config($in->get('accepted_file_types', ''), "accepted_file_types");
			//Update Debug-Mode
			$dlclass->update_config($in->get('enable_debug', 0), "enable_debug");
			//Update Updatecheck
			$dlclass->update_config($in->get('enable_updatecheck', 0), "enable_updatecheck");
			//Update .htaccess-check
			$dlclass->update_config($in->get("disable_htacc", 0), "disable_htacc_check");
			//Update traffic-limit
			$size = $dlclass->unhuman_size($in->get('dl_traffic_limit'), $in->get('dl_traffic_limit_unit'), false);
			$dlclass->update_config($size, "traffic_limit");
			//Update filesize-limit
			$size = $dlclass->unhuman_size($in->get('dl_filesize_limit'), $in->get('dl_filesize_limit_unit'), false);
			$dlclass->update_config($size, "filesize_limit");
			//Update folder-limit
			$size = $dlclass->unhuman_size($in->get('dl_folder_limit'), $in->get('dl_folder_limit_unit'), false);
			$dlclass->update_config($size, "folder_limit");	
			//Update recaptcha
			$dlclass->update_config($in->get('enable_recaptcha', 0), "enable_recaptcha");
		};
		
		//Update Search-items per page
		$dlclass->update_config($in->get('items_per_page', 50), "items_per_page");
		
		//Update Link in Tab-Menu
		$dlclass->update_config($in->get('show_link_on_tab', 0), "show_link_on_tab");
			
			// generate script URL
 			$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
 			$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
 			$server_name = trim($eqdkp->config['server_name']);
 			$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
  			$dl_server_url  = 'http://' . $server_name . $server_port . $script_name;
			$link_url = $dl_server_url.'plugins/downloads/downloads.php';
				 
			if ($in->get('show_link_on_tab', 0) == 1){
		
				 
				//Look, if the Tab was created
				
				$query = $db->query("SELECT * FROM __plus_links WHERE link_url='".$db->escape($link_url)."'");
				$affected_rows = $db->affected_rows();
					if ($affected_rows <1){
				
						$insert = $db->query("INSERT INTO __plus_links (link_url, link_name, link_window, link_menu) VALUES ('".$db->escape($link_url)."', '".$db->escape($user->lang['dl_view'])."', '0', '3')");
						
						//Delete Cache
						$pdc->del_suffix('linkMenu');
						
					};
				
			}	else {
				//Delete Cache
				$pdc->del_suffix('linkMenu');
			
				$delete = $db->query("DELETE FROM __plus_links WHERE link_url='".$db->escape($link_url)."'");
				

			
			}
		
		

			

	
	//Update: disable Categories
	if ($in->get('disable_categories') == 1){
		 
		 $dlclass->update_config("1", "disable_categories");
  	
	} else {
    	
		$dlclass->update_config("0", "disable_categories");
		
		//Check if there are uncategorized downloads
		$downloads_query = $db->query("SELECT * FROM __downloads_links WHERE category = '0'");
		$downloads_nrs = $db->affected_rows();
		if($downloads_nrs != 0){
			//Check if the category for the uncategorized downloads exists
			$cat = $db->query("SELECT * FROM __downloads_categories WHERE category_name = '".$db->escape($user->lang['dl_ad_name_uncategorized_cat'])."'");
			$cat = $db->fetch_record($cat);
			//If it doesn't exist, create it
			if ($db->affected_rows() == 0){
				$create_cat = $db->query("INSERT INTO __downloads_categories (category_name, category_comment, category_permission) VALUES ('".$db->escape($user->lang['dl_ad_name_uncategorized_cat'])."','".$db->escape($user->lang['dl_ad_comment_uncategorized_cat'])."','0')");
				$cat_id = $db->sql_lastid();
				$set_sort_id = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($cat_id)."' WHERE category_id = '".$db->escape($cat_id)."'");
				$db->sql_freeresult();
				
			}
			else {
				$cat_id = $cat['category_id'];
			}
			//Change the category of all uncategorized downloads
			while($downloads = $db->fetch_record($downloads_query)){
				$downloads = $db->query("UPDATE __downloads_links SET category='".$db->escape($cat_id)."' WHERE category = '0'");
				$db->sql_freeresult();
			};

		}
		
  	}
//Delete Cache
$pdc->del_suffix('plugin.downloads');
//Success-Message
System_Message($user->lang['dl_ad_conf_save_sucess'],'Downloads 4 EQdkp-plus','green');
 
}


////Output-Section

	//Load Plugin-Settings
  $out = $pdc->get('plugin.downloads.config',false,true);
	if (!$out) {
  		$sql = 'SELECT * FROM __downloads_config';
  		if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
  		$roww = $db->fetch_record_set($settings_result);

		$pdc->put('plugin.downloads.config',$roww,86400,false,true);
	} else{
		$roww = $out;
	};
  
  foreach($roww as $elem) {
    $conf[$elem['config_name']] = $elem['config_value'];

  }
  $db->free_result($settings_result);

	$dl_ad_conf_htacc_checkbox = '<input type="checkbox" name="disable_htacc" value="1"'.$dlclass->is_checked($conf['disable_htacc_check']).' onClick="dl_confirm_disable_htacc()">';


  	$dl_ad_comments_checkbox = '<input type="checkbox" name="enable_comments" value="1"'.$dlclass->is_checked($conf['enable_comments']).'>';
	
	$dl_ad_single_vote_checkbox = '<input type="checkbox" name="single_vote" value="1"'.$dlclass->is_checked($conf['single_vote']).'>';

	$dl_ad_conf_cat_checkbox = '<input type="checkbox" name="disable_categories" value="1"'.$dlclass->is_checked($conf['disable_categories']).'>';
	
 	$dl_ad_conf_related_links_checkbox = '<input type="checkbox" name="enable_related_links" value="1"'.$dlclass->is_checked($conf['enable_related_links']).'>';
	
	$dl_ad_conf_mirrors_checkbox = '<input type="checkbox" name="enable_mirrors" value="1"'.$dlclass->is_checked($conf['enable_mirrors']).'>';
	
	$dl_ad_conf_recaptcha_checkbox = '<input type="checkbox" name="enable_recaptcha" value="1"'.$dlclass->is_checked($conf['enable_recaptcha']).'>';
	
	$dl_ad_conf_statistics_ov_checkbox = '<input type="checkbox" name="enable_statistics_ov" value="1"'.$dlclass->is_checked($conf['enable_statistics_ov']).'>';
	
	$dl_ad_conf_statistics_checkbox = '<input type="checkbox" name="enable_statistics" value="1"'.$dlclass->is_checked($conf['enable_statistics']).'>';
		
	$dl_ad_conf_updatecheck_checkbox = '<input type="checkbox" name="enable_updatecheck" value="1"'.$dlclass->is_checked($conf['enable_updatecheck']).'>';
	$dl_ad_conf_debug_checkbox = '<input type="checkbox" name="enable_debug" value="1"'.$dlclass->is_checked($conf['enable_debug']).'>';
	$dl_ad_conf_show_tab_checkbox = '<input type="checkbox" name="show_link_on_tab" value="1"'.$dlclass->is_checked($conf['show_link_on_tab']).'>';
  
  
  	$admin_optionsarray = array(
 
              0 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              1 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
			  3 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			   4 => array(
                  'name'    => $user->lang['dl_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_downloads_import', false),
                 ),
			  
              
             
      );
  

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_S_HMODE'							=> $hosting_mode,						
	'DL_AD_SEND'               				=> $user->lang['dl_ad_send'],
	'L_RESET'               				=> $user->lang['dl_ad_reset'],
	'UPDATE_BOX'              				=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  					=> $rbvcheck->OutputHTML(),
	'DB_DL_VERSION'   						=> $conf['inst_version'].' ['.$pm->plugins['downloads']->vstatus.']',
	
	//JS
	'DL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['dl_about_header'], '../about.php', '500', '350'),
	'DL_JS_HTCHECK_WARNING'					=> $jquery->Dialog_Confirm('htcheck_warning', $user->lang['dl_ad_conf_htcheck_disabled_warning'], 'document.dl_config.disable_htacc.checked = true;'),
	'DL_JS_FORCE_UPD'    					=> $jquery->Dialog_Confirm('ForceUpdate', $user->lang['dl_ad_conf_force_db_warn'], "window.location = 'settings.php?version=reset';"),
	'DL_JS_STATS_RESET'    					=> $jquery->Dialog_Confirm('ResetStats', $user->lang['dl_ad_conf_statistics_reset_warn'], "window.location = 'settings.php?stats=reset';"),
	'DL_JS_CONFIG_TABS'						=> $jquery->Tab_header('config_tabs'),
	
	
	'ADMIN_MENU'       						=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
		
	//Values
	'DL_AD_CONF_VAL_ACCEPTED_FILE_TYPES' 	=> sanitize($conf['accepted_file_types']),
	'DL_AD_CONF_TRAFFIC_LIMIT_INPUT'		=> $dlclass->human_size2("dl_traffic_limit", sanitize($conf['traffic_limit']), false),
	'DL_AD_CONF_FOLDER_LIMIT_INPUT'			=> $dlclass->human_size2("dl_folder_limit", sanitize($conf['folder_limit']), false),
	'DL_AD_CONF_FILESIZE_LIMIT_INPUT'		=> $dlclass->human_size2("dl_filesize_limit", sanitize($conf['filesize_limit']), false),
	'DL_AD_CONF_HTACC_CHECKBOX' 			=> $dl_ad_conf_htacc_checkbox,
	'DL_AD_CONF_CAT_CHECKBOX' 				=> $dl_ad_conf_cat_checkbox,
	'DL_AD_CONF_COMMENTS_CHECKBOX' 			=> $dl_ad_comments_checkbox,
	'DL_AD_CONF_MIRRORS_CHECKBOX'			=> $dl_ad_conf_mirrors_checkbox,
	'DL_AD_CONF_RELATED_LINKS_CHECKBOX'		=> $dl_ad_conf_related_links_checkbox,
	'DL_AD_CONF_RECAPTCHA_CHECKBOX'			=> $dl_ad_conf_recaptcha_checkbox,
	'DL_AD_CONF_STATISTICS_OV_CHECKBOX'		=> $dl_ad_conf_statistics_ov_checkbox,
	'DL_AD_CONF_STATISTICS_CHECKBOX'		=> $dl_ad_conf_statistics_checkbox,
	'DL_AD_CONF_UPDATECHECK_CHECKBOX'		=> $dl_ad_conf_updatecheck_checkbox,
	'DL_AD_CONF_DEBUG_CHECKBOX'				=> $dl_ad_conf_debug_checkbox,
	'DL_AD_CONF_SHOW_TAB_CHECKBOX'			=> $dl_ad_conf_show_tab_checkbox,
	'DL_AD_CONF_SINGLE_VOTE_CHECKBOX'		=> $dl_ad_single_vote_checkbox,
	'DL_AD_CONF_VAL_ITEMS_PER_PAGE' 		=> sanitize($conf['items_per_page']),
	'DL_AD_CONF_VAL_PRUNE_STATISTICS' 		=> sanitize($conf['prune_statistics']),
	
	//Language
	'DL_L_CONFIG'							=> $user->lang['dl_ad_manage_config'],
	'DL_AD_CONF_GEN'           				=> $user->lang['dl_ad_conf_gen'],
	'DL_AD_CONF_VIEW'           			=> $user->lang['dl_ad_conf_view'],
	'DL_AD_CONF_FILE'           			=> $user->lang['dl_ad_conf_file'],
	'DL_AD_CONF_EXTENDED'           		=> $user->lang['dl_ad_conf_extended'],
	'DL_AD_CONF_CAPTCHA'           			=> $user->lang['dl_ad_conf_captcha'],
	'DL_AD_CONF_ACCEPTED_FILE_TYPES' 		=> $user->lang['dl_ad_conf_accepted_file_types'],
	'DL_AD_CONF_RELATED_LINKS'				=> $user->lang['dl_ad_conf_related_links'],
	'DL_AD_CONF_MIRRORS'					=> $user->lang['dl_ad_conf_mirrors'],
	'DL_AD_CONF_HTCHECK_DISABLED'			=> $user->lang['dl_ad_conf_htcheck_disabled'],
	'DL_AD_CONF_MIMECHECK_DISABLED'			=> $user->lang['dl_ad_conf_disable_mimecheck'],
	'DL_AD_CONF_CAT_DISABLED'				=> $user->lang['dl_ad_conf_cat_disabled'],
	'DL_AD_CONF_TRAFFIC_LIMIT'				=> $user->lang['dl_ad_conf_traffic_limit'],
	'DL_AD_CONF_FILESIZE_LIMIT'				=> $user->lang['dl_ad_conf_filesize_limit'],
	'DL_AD_CONF_FOLDER_LIMIT'				=> $user->lang['dl_ad_conf_folder_limit'],
	'DL_AD_CONF_COMMENTS'					=> $user->lang['dl_ad_conf_comments'],
	'DL_AD_CONF_RECAPTCHA'					=> $user->lang['dl_ad_conf_captcha'],
	'DL_AD_CONF_STATISTICS_OV'				=> $user->lang['dl_ad_conf_statistics_ov'],
	'DL_AD_CONF_STATISTICS'					=> $user->lang['dl_ad_conf_statistics'],
	'DL_AD_CONF_PRUNE_STATISTICS'			=> $user->lang['dl_ad_conf_prune_statistics'],
	'DL_AD_CONF_STATISTICS_RESET'			=> $user->lang['dl_ad_conf_statistics_reset'],
	'DL_AD_CONF_UPDATECHECK'				=> $user->lang['dl_ad_conf_updatecheck'],
	'DL_AD_CONF_DEBUG'						=> $user->lang['dl_ad_conf_debug'],
	'DL_AD_CONF_ITEMS_PER_PAGE'				=> $user->lang['dl_ad_conf_items_per_page'],
	'DL_AD_CONF_SHOW_LINK_ON_TAB'			=> $user->lang['dl_ad_conf_show_downloads_tab'],
	'DL_AD_CONF_FORCE_DBUPDATE'				=> $user->lang['dl_ad_conf_force_db_update'],
	'DL_AD_CONF_SINGLE_VOTE'				=> $user->lang['dl_ad_conf_single_vote'],
	
	//Help-Tips
	'DL_HELP_FILE_TYPES'					=> $khrml->HTMLTooltip($user->lang['dl_help_file_types'], 'pk_tt_help'),
	'DL_HELP_CAT_DISABLED'					=> $khrml->HTMLTooltip($user->lang['dl_help_cat_disabled'], 'pk_tt_help'),
	'DL_HELP_HTCHECK'						=> $khrml->WarnTooltip($user->lang['dl_help_htcheck']),
	'DL_HELP_RELATED_LINKS'					=> $khrml->HTMLTooltip($user->lang['dl_help_related_links'], 'pk_tt_help'),
	'DL_HELP_MIRRORS'						=> $khrml->HTMLTooltip($user->lang['dl_help_mirrors'], 'pk_tt_help'),
	'DL_HELP_TRAFFIC_LIMIT'					=> $khrml->HTMLTooltip($user->lang['dl_help_traffic_limit'], 'pk_tt_help'),
	'DL_HELP_COMMENTS'						=> $khrml->HTMLTooltip($user->lang['dl_help_comments'], 'pk_tt_help'),
	'DL_HELP_RECAPTCHA'						=> $khrml->HTMLTooltip($user->lang['dl_help_recaptcha'], 'pk_tt_help'),
	'DL_HELP_STATISTICS_OV'					=> $khrml->HTMLTooltip($user->lang['dl_help_statistics_ov'], 'pk_tt_help'),
	'DL_HELP_STATISTICS'					=> $khrml->HTMLTooltip($user->lang['dl_help_statistics'], 'pk_tt_help'),
	'DL_HELP_PRUNE_STATISTICS'				=> $khrml->HTMLTooltip($user->lang['dl_help_prune_statistics'], 'pk_tt_help'),
	'DL_HELP_DEBUG'							=> $khrml->HTMLTooltip($user->lang['dl_help_debug'], 'pk_tt_help'),
	'DL_HELP_DBRESET'						=> $khrml->HTMLTooltip($user->lang['dl_help_dbreset'], 'pk_tt_help'),
	'DL_HELP_FILESIZE_LIMIT'				=> $khrml->HTMLTooltip($user->lang['dl_help_filesize_limit'], 'pk_tt_help'),
	'DL_HELP_FOLDER_LIMIT'					=> $khrml->HTMLTooltip($user->lang['dl_help_folder_limit'], 'pk_tt_help'),
	

));		



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' 		=> sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_ad_manage_config_ov'],
	'template_path'		=> $pm->get_data('downloads', 'template_path'),
	'template_file' 	=> 'admin/config.html',
	'display'       	=> true)
);

?>
