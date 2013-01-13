<?PHP
/*************************************************\
*             fightstats 4 EQdkp plus              *
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
define('PLUGIN', 'fightstats');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'fightstats')) { message_die('The fightstats-plugin is not installed.'); }
$hosting_mode = ($_HMODE) ? true : false;

// Check user permission
$user->check_auth('a_fightstats_man');

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('fightstats', '', 'fightstats_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'fightstats_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'fightstats',
      'version' => $pm->get_data('fightstats', 'version'),
      'build' 	=> $pm->plugins['fightstats']->build,
      'vstatus' => $pm->plugins['fightstats']->vstatus,
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


if ($in->get('submit') != ""){

	$fsclass->update_config($in->get("enable_updatecheck", 0), "enable_updatecheck");
	

		// get an array with the config fields of the DB
$isplusconfarray = $plusdb->CheckDBFields('plus_config', 'config_name');
$name = 'wol_group_id';
$stripmytags = false;
$implodeme	 = $in->get("wol_group_id");
$plusdb->UpdateConfig($name, $implodeme, $isplusconfarray, $stripmytags);

	
	$conf_plus['wol_group_id'] = $in->get("wol_group_id");
	$conf['enable_updatecheck'] = $in->get("enable_updatecheck",0);
	
	//Success-Message
	System_Message($user->lang['fs_save_success'],'Fightstats','green');
 
}

if ($in->get('fs_update') != ""){
	$fsclass->update_wol($conf_plus['wol_group_id']);	
	$conf['last_update'] = time();
	System_Message($user->lang['fs_update_success'],'Fightstats','green');
}




$updatecheck_box = '<input type="checkbox" name="enable_updatecheck" value="1"'.$fsclass->is_checked($conf['enable_updatecheck']).'>';

$tpl->assign_vars(array(
	'L_CONFIG'	=> $user->lang['fs_settings'],
	'L_SAVE'	=> $user->lang['fs_save'],
	'L_UPDATE'	=> $user->lang['fs_update'],
	'L_LAST_UPDATE'	=> $user->lang['fs_last_update'],
	'L_UPDATE_CHECK'	=> $user->lang['fs_conf_updatecheck'],
	'L_WOL_GROUP_ID'	=> $user->lang['fs_conf_wol_groupid'],
	'LAST_UPDATE'	=> date($user->style['date_time'], $conf['last_update']),	
	'WOL_GROUP_ID'	=> sanitize($conf_plus['wol_group_id']),
	'UPDATECHECK_BOX'	=> $updatecheck_box,
	
));

// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' 		=> sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['fightstats']." - ".$user->lang['fs_settings'],
	'template_path'		=> $pm->get_data('fightstats', 'template_path'),
	'template_file' 	=> 'admin/settings.html',
	'display'       	=> true)
);

?>
