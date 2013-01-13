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
define('PLUGIN', 'polls');
$eqdkp_root_path = './../../';
include_once('include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'polls')) {
	message_die('The Poll plugin is not installed.');
}

// Check user permission
if ($user->data['user_id'] == ANONYMOUS){
	$user->check_auth(false);
} else {
	$user->check_auth('u_polls_vote');
}

// The Code
$allpolls_cache = $pdc->get('plugin.polls.overview');
if (!$allpolls_cache){
	$allpolls_sql = "SELECT * FROM __polls";
	$allpolls_result = $db->query($allpolls_sql);
	$db->free_result($allpolls_result);
	$allpolls = $db->fetch_record_set($allpolls_result);
	$pdc->put('plugin.polls.overview', $allpolls, 86400);
} else {
	$allpolls = $allpolls_cache;
}

foreach($allpolls as $polllist){
	// Generate the Tooltip
	$polldescription = $polllist['description'];
	$polldescription = $bbcode->toHTML($polldescription);
	$bbcode->SetSmiliePath($eqdkp_root_path.'libraries/jquery/images/editor/icons');
	$polldescription = $bbcode->MyEmoticons($polldescription);

	// Check, if the User voted
	$check_sql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($polllist['id'])."' AND `user_id` = '".$db->escape($user->data['user_id'])."'";
	$check_result = $db->query($check_sql);
	if ($db->fetch_record($check_result)){
		$voted = true;
	} else {
		$voted = false;
	}

	$tpl->assign_block_vars('polllist', array(
		'ROWCLASS'		=> $eqdkp->switch_row_class(),
		'ID'					=> $polllist['id'],
		'TITLE'				=> sanitize($polllist['title']),
		'DESCRIPTION'	=> ($polldescription) ? $html->HTMLTooltip($polldescription, 'po_description') : '',
		'VOTED'				=> ($voted) ? $user->lang['po_usr_ov_yes'] : $user->lang['po_usr_ov_no'],
		'ENDDATE'			=> ($polllist['enddate']) ? sanitize($polllist['enddate']) : '-',
		'STATUS'			=> ($polllist['closed']) ? '<img src="images/closed.png" />' : '<img src="images/opened.png" />',
	));
}

// Send the Output to the template Files.
$tpl->assign_vars(array(

	// Lang Strings
	'F_TITLE'		=> $user->lang['po_usr_ov_title'],
	'F_VOTED'		=> $user->lang['po_usr_ov_voted'],
	'F_ENDDATE'	=> $user->lang['po_usr_ov_enddate'],
	'F_STATUS'	=> $user->lang['po_usr_ov_status'],
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['polls'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'polls.html',
	'display'       => true)
  );
?>