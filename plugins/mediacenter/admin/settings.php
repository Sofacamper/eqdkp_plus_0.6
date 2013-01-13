<?PHP
 /*
 * Project:     MediaCenter for EQDKPlus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date$
 * ----------------------------------------------------------------------- 
 * @author      $Author$
 * @copyright   2009 GodMod
 * @link        http://eqdkp-plus.com
 * @package     MediaCenter
 * @version     $Rev$
 * 
 * $Id$
 */

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }
$hosting_mode = ($_HMODE) ? true : false;

// Check user permission
$user->check_auth('a_mediacenter_cfg');

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('mediacenter', '', 'mediacenter_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'mediacenter_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'mediacenter',
      'version' => $pm->get_data('mediacenter', 'version'),
      'build' 	=> $pm->plugins['mediacenter']->build,
      'vstatus' => $pm->plugins['mediacenter']->vstatus,
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
	$db->query("DELETE FROM __mediacenter_stats");
}

if ($in->get('submit') != ""){
		

		
		//Update Updatecheck
		$mcclass->update_config($in->get('enable_updatecheck', 0), "enable_updatecheck");
		
		//Update items per page
		$mcclass->update_config($in->get('items_per_page', 50), "items_per_page");
		
		//Update Link in Tab-Menu
		$mcclass->update_config($in->get('show_link_on_tab', 0), "show_link_on_tab");
		
		//Update Admin-Activation
		$mcclass->update_config($in->get('admin_activation', 0), "admin_activation");
		
		$mcclass->update_config($in->get('single_vote', 0), "single_vote");
		$mcclass->update_config($in->get('enable_comments', 0), "enable_comments");
		$mcclass->update_config($in->get('enable_statistics', 0), "enable_statistics");
		$mcclass->update_config($in->get('prune_statistics', ''), "prune_statistics");
		$mcclass->update_config($in->get('default_view', 0), "default_view");
		$mcclass->update_config($in->get('disable_reportmail', 0), "disable_reportmail");
		
			// generate script URL
 			$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
 			$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
 			$server_name = trim($eqdkp->config['server_name']);
 			$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
  			$dl_server_url  = 'http://' . $server_name . $server_port . $script_name;
			$link_url = $dl_server_url.'plugins/mediacenter/index.php';
				 
			if ($in->get('show_link_on_tab', 0) == 1){
		
				 
				//Look, if the Tab was created
				
				$query = $db->query("SELECT * FROM __plus_links WHERE link_url='".$db->escape($link_url)."'");
				$affected_rows = $db->affected_rows();
					if ($affected_rows <1){
				
						$insert = $db->query("INSERT INTO __plus_links (link_url, link_name, link_window, link_menu) VALUES ('".$db->escape($link_url)."', '".$db->escape($user->lang['mc_mediacenter_short'])."', '0', '3')");
						
						//Delete Cache
						$pdc->del_prefix('linkMenu');
						
					};
				
			}	else {
				//Delete Cache
				$pdc->del_prefix('linkMenu');
			
				$delete = $db->query("DELETE FROM __plus_links WHERE link_url='".$db->escape($link_url)."'");
				

			
			}
		
		



//Delete Cache
$pdc->del_prefix('plugin.mediacenter');
//Success-Message
System_Message($user->lang['mc_config_saved_success'],'EQDKPlus MediaCenter','green');
 
}


////Output-Section

	//Load Plugin-Settings
  $out = $pdc->get('plugin.mediacenter.config',false,true);
	if (!$out) {
  		$sql = 'SELECT * FROM __mediacenter_config';
  		if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
  		$roww = $db->fetch_record_set($settings_result);

		$pdc->put('plugin.mediacenter.config',$roww,86400,false,true);
	} else{
		$roww = $out;
	};
  
  foreach($roww as $elem) {
    $conf[$elem['config_name']] = $elem['config_value'];

  }
  $db->free_result($settings_result);



  	$comments_checkbox = '<input type="checkbox" name="enable_comments" value="1"'.$mcclass->is_checked($conf['enable_comments']).'>';
	$single_vote_checkbox = '<input type="checkbox" name="single_vote" value="1"'.$mcclass->is_checked($conf['single_vote']).'>';	
	$statistics_checkbox = '<input type="checkbox" name="enable_statistics" value="1"'.$mcclass->is_checked($conf['enable_statistics']).'>';	
	$updatecheck_checkbox = '<input type="checkbox" name="enable_updatecheck" value="1"'.$mcclass->is_checked($conf['enable_updatecheck']).'>';
	$show_tab_checkbox = '<input type="checkbox" name="show_link_on_tab" value="1"'.$mcclass->is_checked($conf['show_link_on_tab']).'>';
  	$admin_activation_checkbox = '<input type="checkbox" name="admin_activation" value="1"'.$mcclass->is_checked($conf['admin_activation']).'>';
	$admin_reportmail_checkbox = '<input type="checkbox" name="disable_reportmail" value="1"'.$mcclass->is_checked($conf['disable_reportmail']).'>';
    
	$admin_optionsarray = array(
              
			  1 => array(
                  'name'    => $user->lang['mc_manage_videos'],
                  'link'    => 'media.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  2 => array(
                  'name'    => $user->lang['mc_manage_categories'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  3 => array(
                  'name'    => $user->lang['mc_stats'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_mediacenter_stats', false),
                  ),                            
			              
			  4 => array(
                  'name'    => $user->lang['mc_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_mediacenter_cfg', false),
                  ),
			  			  5 => array(
                  'name'    => $user->lang['mc_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_mediacenter_import', false),
                  ),

              
             
      );
$view_array[0] = $user->lang['mc_view_details'];
$view_array[1] = $user->lang['mc_view_thumbs'];

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'S_HMODE'							=> $hosting_mode,						

	'UPDATE_BOX'              				=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  					=> $rbvcheck->OutputHTML(),
	'DB_VERSION'   							=> $conf['inst_version'].' ['.$pm->plugins['mediacenter']->vstatus.']',
	
	//JS
	'MC_JS_FORCE_UPD'    					=> $jquery->Dialog_Confirm('ForceUpdate', $user->lang['mc_force_db_update_warn'], "window.location = 'settings.php?version=reset';"),
	'MC_JS_STATS_RESET'    					=> $jquery->Dialog_Confirm('ResetStats', $user->lang['dl_ad_conf_statistics_reset_warn'], "window.location = 'settings.php?stats=reset';"),
	'MC_ADMIN_MENU'       					=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),
	

		
	//Values
	'UPDATECHECK_CHECKBOX'					=> $updatecheck_checkbox,
	'COMMENTS_CHECKBOX'						=> $comments_checkbox,
	'SINGLE_VOTE_CHECKBOX'					=> $single_vote_checkbox,
	'SHOW_TAB_CHECKBOX'						=> $show_tab_checkbox,
	'STATISTICS_CHECKBOX'					=> $statistics_checkbox,
	'ADMIN_ACTIVATION_CHECKBOX'				=> $admin_activation_checkbox,
	'REPORT_MAIL_CHECKBOX'							=> $admin_reportmail_checkbox,
	'ITEMS_PER_PAGE'						=> sanitize($conf['items_per_page']),
	'PRUNE_STATISTICS'						=> sanitize($conf['prune_statistics']),
	'DEFAULT_VIEW'							=> $khrml->DropDown('default_view', $view_array, $conf['default_view']),
	
	//Language
	'L_SETTINGS'               				=> $user->lang['mc_settings'],
	'L_FORCE_DBUPDATE'						=> $user->lang['mc_force_db_update'],
	'L_UPDATECHECK'							=> $user->lang['mc_updatecheck'],
	'L_EXTENDED_SETTINGS'					=> $user->lang['mc_extended_settings'],
	'L_SAVE'								=> $user->lang['mc_save'],
	'L_VIEW_SETTINGS'						=> $user->lang['mc_view_settings'],
	'L_SHOW_LINK_ON_TAB'					=> $user->lang['mc_show_link_tab'],
	'L_ITEMS_PER_PAGE'						=> $user->lang['mc_items_per_page'],
	'L_ENABLE_COMMENTS'						=> $user->lang['mc_enable_comments'],
	'L_SINGLE_VOTE'							=> $user->lang['mc_single_vote'],
	'L_ADMIN_ACTIVATION'					=> $user->lang['mc_admin_activation'],
	'L_ENABLE_STATISTICS'					=> $user->lang['mc_enable_statistics'],
	'L_RESET_STATISTICS'					=> $user->lang['mc_reset_statistics'],
	'L_PRUNE_STATISTICS'					=> $user->lang['mc_prune_statistics'],
	'L_DEFAULT_VIEW'						=> $user->lang['mc_default_view'],
	'L_DISABLE_REPORTMAIL'					=> $user->lang['mc_disable_reportmail'],
	
	//Help-Tips
	'HELP_DBUPDATE'							=> $khrml->HTMLTooltip($user->lang['mc_help_dbupdate'], 'pk_tt_help'),
	'HELP_COMMENTS'							=> $khrml->HTMLTooltip($user->lang['mc_help_comments'], 'pk_tt_help'),
	'HELP_STATISTICS'						=> $khrml->HTMLTooltip($user->lang['mc_help_statistics'], 'pk_tt_help'),
	'HELP_PRUNE_STATISTICS'					=> $khrml->HTMLTooltip($user->lang['mc_help_prune_statistics'], 'pk_tt_help'),
	'HELP_ADMIN_ACTIVATION'					=> $khrml->HTMLTooltip($user->lang['mc_help_admin_activation'], 'pk_tt_help'),
	
	'MC_JS_CONFIG_TABS'						=> $jquery->Tab_header('config_tabs'),
		

));		



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['mediacenter']." - ".$user->lang['mc_settings'],
	'template_path'		=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 	=> 'admin/settings.html',
	'display'       	=> true)
);

?>
