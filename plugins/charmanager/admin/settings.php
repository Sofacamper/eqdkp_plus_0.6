<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-08-17 16:46:58 +0200 (Mo, 17 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5703 $
 * 
 * $Id: settings.php 5703 2009-08-17 14:46:58Z wallenium $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../';
include_once('../include/common.php');
// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'charmanager')) { message_die('The Charmanager plugin is not installed.'); }

// Check user permission
$user->check_auth('a_charmanager_');

// Get the plugin
$raidplan = $pm->get_plugin('charmanager');

// init the db addition
$ucupdater = new PluginUpdater('charmanager','uc_','charmanager_config', 'include');

// Save this shit
if ($in->get('issavebu', false)){
  // global config
    $savearray = array(
    	'uc_updtcheck_on'		=> $in->get('uc_updtcheck_on'),
			'uc_servername'			=> addslashes($in->get('uc_servername')),
			'uc_lockserver'			=> $in->get('uc_lockserver'),
			'uc_server_loc'			=> $in->get('uc_server_loc'),
			'uc_showarmlink'		=> $in->get('uc_showarmlink'),
			'uc_noresisave'			=> $in->get('uc_noresisave'),
			'uc_lphideresi'     => $in->get('uc_lphideresi'),
			'uc_defaultrank'		=> $in->get('uc_defaultrank'),
			'uc_reqconfirm'			=> $in->get('uc_reqconfirm'),
			'uc_server_lang'		=> $in->get('uc_server_lang'),
		);
		if ($wpfcdb->UpdateConfig($savearray, $wpfcdb->CheckDBFields('config_name'))){
      redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID.'&save=true');
    }else{
      $info = false;
    }
} 

// reset the version (to force an update)
if($in->get('version') == 'reset'){
	$ucupdater->DeleteVersionString();
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}

// Update Check init
$updchk_enbled  = ( $conf['uc_updtcheck_on'] == 1 ) ? true : false;
$cachedb        = array('table' => 'charmanager_config', 'data' => $conf['vc_data'], 'f_data' => 'vc_data', 'lastcheck' => $conf['vc_lastcheck'], 'f_lastcheck' => 'vc_lastcheck');
$versionthing   = array('name' => 'charmanager', 'inclpath' => 'include', 'version' => $pm->get_data('charmanager', 'version'), 'build' => $pm->plugins['charmanager']->build, 'vstatus' => $pm->plugins['charmanager']->vstatus,  'enabled' => $updchk_enbled);
$ucvcheck       = new PluginUpdCheck($versionthing, $cachedb);
$armory         = new ArmoryChars("âîâ"); 
$ucvcheck->PerformUpdateCheck();

// Save message:
if($in->get('save') == 'true'){
  System_Message($user->lang['uc_setting_saved'], $user->lang['uc_setting_saved_h'], "green");
}

// Rank
$rasql = 'SELECT rank_id, rank_name FROM __member_ranks WHERE rank_id > 0 ORDER BY rank_id';
$result2 = $db->query($rasql);
$rankdropdwn = array(0=>$user->lang['uc_defaultrank_none']);
while ( $row2 = $db->fetch_record($result2) ){
	$rankdropdwn[$row2['rank_id']] = stripslashes($row2['rank_name']);
}
$db->free_result($result2);

// Output
$import_folder = $eqdkp_root_path.'plugins/charmanager/games/'.$conf['real_gamename'].'/import/';
$tpl->assign_vars(array(
      'F_CONFIG'        => 'settings.php' . $SID,
      'UPDATE_BOX'			=> $ucupdater->OutputHTML(),
    	'UPDCHECK_BOX'		=> $ucvcheck->OutputHTML(),
    	
    	// JS Code
      'JS_UPDATEPROF'   => $jquery->Dialog_URL('UCCacheWindow',  $user->lang['uc_cache_update'], $import_folder."a_import.php?step=1", '500', '130', 'settings.php'),
      'JS_IMPORTGUILD'	=> $jquery->Dialog_URL('UCGuildImport',  $user->lang['uc_import_guildb'], $import_folder."armory/guild_importer.php", '470', '340', 'settings.php'),
    	
    	// DropDown
    	'DRPWN_SERVERLOC' => $khrml->DropDown('uc_server_loc', $armory->GetLocs(), $conf['uc_server_loc'], '', '', 'input'),
    	'DRPWN_SRVLANG'   => $khrml->DropDown('uc_server_lang', $armory->GetLanguages(), $conf['uc_server_lang'], '', '', 'input'),
    	'DRPWN_RANK'			=> $khrml->DropDown('uc_defaultrank', $rankdropdwn, $conf['uc_defaultrank'], '', '', 'input'),
      
      // Import Things
      'U_IMPORT_DISBLD' => ($conf['uc_servername'] == '') ? true : false,
      'U_HAS_IMPORT'    => $cmHasImport,
      
      // Variables
      'USE_UPDATECH'    => $wpfcdb->isChecked($conf['uc_updtcheck_on']),
      'LOCK_SERVERFLD'	=> $wpfcdb->isChecked($conf['uc_lockserver']),
      'SHOW_ARM_LINK'		=> $wpfcdb->isChecked($conf['uc_showarmlink']),
      'NO_RESISAVE'			=> $wpfcdb->isChecked($conf['uc_noresisave']),
      'LP_HIDERESI'			=> $wpfcdb->isChecked($conf['uc_lphideresi']),
      'REQCONFIRM'			=> $wpfcdb->isChecked($conf['uc_reqconfirm']),
      'SERVERNAME'      => stripslashes($conf['uc_servername']),
      'LAST_UPDATED'		=> ($conf['uc_profileimported']) ? $user->lang['uc_last_updated'].': '.date($user->style['date_time'], $conf['uc_profileimported']) : $user->lang['uc_never_updated'],
      
      // Language
      'L_SUBMIT'        => $user->lang['submit'],
      'L_RESET'         => $user->lang['reset'],
      'L_GENERAL'       => $user->lang['uc_header_global'],
      'L_USE_UPDATECH'  => $user->lang['uc_enabl_updatecheck'],
      'L_SERVERNAME'    => $user->lang['uc_servername'],
      'L_LOCK_SERVER'		=> $user->lang['uc_lock_server'],
      'L_UPDATE_ALL'		=> $user->lang['uc_update_all'],
      'L_BTTN_UPDATE'		=> $user->lang['uc_bttn_update'],
      'L_SERVERLOC'			=> $user->lang['uc_server_loc'],
      'L_SHOW_ARMLINK'	=> $user->lang['uc_armory_link'],
      'L_NO_WOW'				=> $user->lang['uc_no_wow'],
      'L_NO_RESISAVE'		=> $user->lang['uc_no_resi_save'],
      'L_LP_HIDERESI'   => $user->lang['uc_lp_hideresis'],
      'L_DEFRANK'				=> $user->lang['uc_defaultrank'],
      'L_REQ_CONFIRM'		=> $user->lang['uc_reqconfirm'],
      'L_IMPORT'				=> $user->lang['uc_limport'],
      'L_IMPORT_GUILD'	=> $user->lang['uc_import_guild'],
      'L_BTTN_GIMP'			=> $user->lang['uc_import_guildb'],
      'L_SERVERLANG'    => $user->lang['uc_import_srvlang'],
      )
		);
    
    $eqdkp->set_vars(array(
	    'page_title'             => $CharTools->GeneratePageTitle($user->lang['uc_config']),
			'template_path' 	       => $pm->get_data('charmanager', 'template_path'),
			'template_file'          => 'admin/settings.html',
			'display'                => true)
    );
