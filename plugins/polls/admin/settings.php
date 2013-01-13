<?PHP
/*********************************************************************************\
* Project:	EQdkp-Plus																														*
* License:	Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/											*
* --------------------------------------------------------------------------------*
* Polls 4 EQdkp plus																															*
* --------------------------------------------------------------------------------*
* Project Start: 10/2009																													*
* Author: BadTwin																																	*
* Copyright: Andreas (BadTwin) Schrottenbaum																			*
* Link: http://badtwin.dyndns.org																									*
* Version: 0.0.1																																	*
\*********************************************************************************/

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'polls');
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'polls')) {
	message_die('The Poll plugin is not installed.');
}

// Check user permission
$user->check_auth('a_polls_manage');

// Include the Database Updater
$pollupdater = new PluginUpdater('polls', 'po_', 'polls_settings', 'include');

// The Version Information
$plugindetails = array(
	'name' 		=> 'polls',
	'version' => $pm->get_data('polls', 'version'),
	'build' 	=> $pm->plugins['polls']->build,
	'vstatus' => $pm->plugins['polls']->vstatus,
	'enabled' => $conf['po_updcheck'],
);

// Start Output DO NOT CHANGE....
$pollvcheck = new PluginUpdCheck($plugindetails);
$pollvcheck->PerformUpdateCheck();

// The Code
	if ($in->get('po_submit', '')){
		$polls->update_settings('po_multiple', $in->get('multiple', '0'));
		$polls->update_settings('po_comments', $in->get('comments', '0'));
		$polls->update_settings('po_editable', $in->get('editable', '0'));
		$polls->update_settings('po_intermed', $in->get('intermed', '0'));
		$polls->update_settings('po_modstyle', $in->get('modstyle', '0'));
		$polls->update_settings('po_updcheck', $in->get('updcheck', '0'));
		$pdc->del_suffix('plugin.polls');

		$config_query = $db->query("SELECT * FROM __polls_settings");
		$config_data = $db->fetch_record_set($config_query);
		$db->free_result($config_query);

		foreach ($config_data as $sett){
			$conf[$sett['config_name']] = $sett['config_value'];
		}
		System_Message($user->lang['po_ad_sett_saved'], $user->lang['po_ad_sett_hlsaved'], 'green');
	}

	if ($conf['po_modstyle'] == 1){
		$modstyle_opt1 = 'selected';
	} else {
		$modstyle_opt2 = 'selected';
	}

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'MULTIPLE'	=> ($conf['po_multiple'] == '1') ? 'checked="checked"' : '',
	'COMMENTS'	=> ($conf['po_comments'] == '1') ? 'checked="checked"' : '',
	'EDITABLE'	=> ($conf['po_editable'] == '1') ? 'checked="checked"' : '',
	'INTERMED'	=> ($conf['po_intermed'] == '1') ? 'checked="checked"' : '',
	'MODSTYLE_OPT1'	=> $modstyle_opt1,
	'MODSTYLE_OPT2'	=> $modstyle_opt2,
	'UPDCHECK'	=> ($conf['po_updcheck'] == '1') ? 'checked="checked"' : '',

	'UPDATE_BOX'            => $pollupdater->OutputHTML(),
	'UPDCHECK_BOX'     		  => $pollvcheck->OutputHTML(),

	// Lang strings
	'F_LEGEND'		=> $user->lang['po_ad_sett_legend'],
	'F_MULTIPLE'	=> $user->lang['po_ad_sett_multiple'],
	'F_COMMENTS'	=> $user->lang['po_ad_sett_comments'],
	'F_EDITABLE'	=> $user->lang['po_ad_sett_editable'],
	'F_INTERMED'	=> $user->lang['po_ad_sett_intermed'],
	'F_MODSTYLE'	=> $user->lang['po_ad_sett_modstyle'],
	'F_CAKE'			=> $user->lang['po_ad_sett_cake'],
	'F_BARS'			=> $user->lang['po_ad_sett_bars'],
	'F_COMMON'		=> $user->lang['po_ad_sett_common'],
	'F_UPDCHK'		=> $user->lang['po_ad_sett_updchk'],
	'F_UPDATE'		=> $user->lang['po_ad_sett_update'],
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['po_ad_sett_manage'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'admin/settings.html',
	'display'       => true)
  );
?>