<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-08-04 01:26:23 +0200 (Tue, 04 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 5441 $
 *
 * $Id: settings.php 5441 2009-08-03 23:26:23Z wallenium $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'info');

$eqdkp_root_path = './../../../';
include_once ($eqdkp_root_path . 'common.php');
include_once ('../include/functions.php');

// Check user permission
$user->check_auth('a_information_conf');

if (!$pm->check(PLUGIN_INSTALLED, 'info')) {
	message_die('The Infopages plugin is not installed.');
}

//Framework include
include_once($eqdkp_root_path . 'plugins/info/include/libloader.inc.php');

//Updater
$infoupdater = new PluginUpdater('info','info_','info_config','include');

// Saving
if ($_POST['infosavebu']){
	UpdateConfig('ee', $_POST['ee']);
	UpdateConfig('er', $_POST['er']);
	UpdateConfig('ec', $_POST['ec']);
	UpdateConfig('euchk', $_POST['euchk']);
}

//get config
$config = getInfoConfig();

//Update check
// Check if the Update Check should ne enabled or disabled...
$updchk_enbled = ( $config['euchk'] == 1 ) ? true : false;
// The Data for the Cache Table
$cachedb = array(
  'table' => 'info_config',
  'data' => $config['vc_data'],
  'f_data' => 'vc_data',
  'lastcheck' => $config['vc_lastcheck'],
  'f_lastcheck' => 'vc_lastcheck'
);

// The Version Information
$versionthing   = array(
  'name' => 'info', 
  'version' => $pm->get_data('info', 'version'),
//  'build' => $pm->plugins['bosssuite']->build, 
//  'vstatus' => $pm->plugins['bosssuite']->vstatus,
  'enabled' => $updchk_enbled
);

// Start Output ?DO NOT CHANGE....
$infovcheck = new PluginUpdCheck($versionthing, $cachedb);
$infovcheck->PerformUpdateCheck();

$tpl->assign_vars(array (
	'F_CONFIG' => 'settings.php' . $SID,
	'UPDATER' => $infoupdater->OutputHTML(),
	'UPDCHECK_BOX' => $infovcheck->OutputHTML(),
	
  // Language
	'L_INFO_SETTINGS' => $user->lang['info_settings'],
	'L_INFO_EC' => $user->lang['info_opt_ec'],
	'L_INFO_ER' => $user->lang['info_opt_er'],
	'L_INFO_EE' => $user->lang['info_opt_ee'],
	'L_INFO_EUCHK' => $user->lang['info_opt_euchk'],
	'INFO_EE' => ($config['ee'] == 1) ? ' checked="checked"' : '',
	'INFO_EUCHK' => ($config['euchk'] == 1) ? ' checked="checked"' : '',
	'INFO_ER' => $config['er'],
	'INFO_EC' => $config['ec'],
	'CREDITS' => $user->lang['info_cp'] . $pm->get_data('info', 'version') . $user->lang['info_cp2'] 
));


$eqdkp->set_vars(array (
	'page_title' => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['info'],
	'template_path' => $pm->get_data('info', 'template_path'),
	'template_file' => 'admin/settings.html', 'display' => true
	)
);

?>
