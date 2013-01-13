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
session_start();
// Copy the settings to the session
if ($in->get('po_submit', '')){
	$_SESSION = array();
	$_SESSION['po_title']				= $in->get('polltitle', '');
	$_SESSION['po_description'] = $in->get('description', '');
	$_SESSION['po_multiple']		= $in->get('multiple', '');
	$_SESSION['po_comments']		= $in->get('comments', '');
	$_SESSION['po_editable']		= $in->get('editable', '');
	$_SESSION['po_intermed']		= $in->get('intermed', '');
	$_SESSION['po_modstyle']		= $in->get('modstyle', '');
	$_SESSION['po_enddate']			= $in->get('po_enddate', '');
	$_SESSION['po_closed']			= $in->get('closed', '');
}

// Add an option
if ($in->get('new_opt', '')){
	$_SESSION['po_opt'][0] = '';
	$_SESSION['po_opt'][] = $in->get('newanswer', '');
	unset($_SESSION['po_opt'][0]);
}

// Delete an option
if ($in->get('del_opt', '0')){
	unset($_SESSION['po_opt'][$in->get('del_opt', '0')]);
}

// Create an output message, if no option exists
if ($in->getArray('po_opt', 'string')){
	$button = TRUE;
} else {
	$nooptions = $user->lang['po_ad_sett_nooptions'];
	$button = FALSE;
}

// Create the options-list
foreach ($in->getArray('po_opt', 'string') as $id => $answer){
	$tpl->assign_block_vars('answers', array(
		'ROWCLASS'	=> $eqdkp->switch_row_class(),
		'ID'				=> $id,
		'ANSWER'		=> sanitize($answer),
	));
}

// Redirect to the First page, if this file is accessed directly
if ($in->get('po_title','') == ''){
	redirect('plugins/polls/admin/createpoll.php');
}

// Save the Poll
if ($in->get('createpoll', '')){
	$polls->create_poll();
	$pdc->del('plugin.polls.overview');
}

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'NOOPTIONS'			=> $nooptions,
	'SUBMIT'				=> $button,

	// Lang Strings
	'OPTIONS'				=> $user->lang['po_ad_sett_options'],
	'NEWOPTION'			=>$user->lang['po_ad_sett_newoption'],
	'CREATEPOLL'		=> $user->lang['po_ad_sett_createpoll'],
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['po_ad_men_create'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'admin/createoptions.html',
	'display'       => true)
  );
?>