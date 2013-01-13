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

// The Code
	if ($conf['po_modstyle'] == 1){
		$modstyle_opt1 = 'selected';
	} else {
		$modstyle_opt2 = 'selected';
	}

// Delete the Session Data, if existing. So it's guaranteed, that the next step has an empty session.
session_start();
session_unset();
$_SESSION = array();

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DESCRIPTION'	=> $jquery->wysiwyg('description'),
	'MULTIPLE'	=> ($conf['po_multiple'] == '1') ? 'checked="checked"' : '',
	'COMMENTS'	=> ($conf['po_comments'] == '1') ? 'checked="checked"' : '',
	'EDITABLE'	=> ($conf['po_editable'] == '1') ? 'checked="checked"' : '',
	'INTERMED'	=> ($conf['po_intermed'] == '1') ? 'checked="checked"' : '',
	'MODSTYLE1'	=> $modstyle_opt1,
	'MODSTYLE2'	=> $modstyle_opt2,
	'ENDDATE'		=> $jqueryp->Calendar('po_enddate', '', '', array('change_fields'=>false, 'other_months'=>true)),

	// Lang Strings
	'F_HEADLINE'		=> $user->lang['po_ad_men_create'],
	'F_TITLE'				=> $user->lang['po_ad_sett_title'],
	'F_DESCRIPTION'	=> $user->lang['po_ad_sett_description'],
	'F_MULTIPLE'		=> $user->lang['po_ad_sett_multiple'],
	'F_COMMENTS'		=> $user->lang['po_ad_sett_comments'],
	'F_EDITABLE'		=> $user->lang['po_ad_sett_editable'],
	'F_INTERMED'		=> $user->lang['po_ad_sett_intermed'],
	'F_MODSTYLE'		=> $user->lang['po_ad_sett_modstyle'],
	'F_ENDDATE'			=> $user->lang['po_ad_sett_enddate'],
	'F_CLOSED'			=> $user->lang['po_ad_sett_closed'],
	'F_MODSTYLE1'		=> $user->lang['po_ad_sett_cake'],
	'F_MODSTYLE2'		=> $user->lang['po_ad_sett_bars'],
	'F_STEPTWO'			=> $user->lang['po_ad_sett_steptwo'],

));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['po_ad_men_create'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'admin/createpoll.html',
	'display'       => true)
  );
?>