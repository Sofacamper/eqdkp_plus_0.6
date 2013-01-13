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
if ($in->get('close', '') == 'open'){
	$sql = "UPDATE __polls SET closed = '0' WHERE id = '".$db->escape($in->get('id', 0))."'";
	$db->query($sql);
	$pdc->del('plugin.polls.overview');
	$pdc->del_suffix('plugin.polls.poll'.$in->get('id', 0));
} elseif ($in->get('close', '') == 'close'){
	$sql = "UPDATE __polls SET closed = '1' WHERE id = '".$db->escape($in->get('id', 0))."'";
	$db->query($sql);
	$pdc->del('plugin.polls.overview');
	$pdc->del_suffix('plugin.polls.poll'.$in->get('id', 0));
}

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

// List the existing polls
foreach($allpolls as $poll_ov){
		$polldescription = $poll_ov['description'];
		$polldescription = $bbcode->toHTML($polldescription);
		$bbcode->SetSmiliePath($eqdkp_root_path.'libraries/jquery/images/editor/icons');
		$polldescription = $bbcode->MyEmoticons($polldescription);

	$tpl->assign_block_vars('allpolls', array(
		'ROWCLASS'		=> $eqdkp->switch_row_class(),
		'ID'					=> $poll_ov['id'],
		'TITLE'				=> sanitize($poll_ov['title']),
		'DESCRIPTION'	=> ($polldescription) ? $html->HTMLTooltip($polldescription, 'po_description') : '',
		'MULTIPLE'		=> ($poll_ov['multiple'] == '1') ? '<img src="../images/yes.png" />' : '<img src="../images/no.png" />',
		'COMMENTS'		=> ($poll_ov['comments'] == '1') ? '<img src="../images/yes.png" />' : '<img src="../images/no.png" />',
		'EDITABLE'		=> ($poll_ov['editable'] == '1') ? '<img src="../images/yes.png" />' : '<img src="../images/no.png" />',
		'INTERMED'		=> ($poll_ov['intermed'] == '1') ? '<img src="../images/yes.png" />' : '<img src="../images/no.png" />',
		'MODSTYLE'		=> ($poll_ov['modstyle'] == '1') ? $user->lang['po_ad_sett_cake'] : $user->lang['po_ad_sett_bars'],
		'ENDDATE'			=> ($poll_ov['enddate']) ? $poll_ov['enddate'] : '-',
		'CLOSED'			=> ($poll_ov['closed'] == '1') ? '<a href="editpolls.php?id='.$poll_ov['id'].'&close=open"><img src="../images/closed.png" /></a>' : '<a href="editpolls.php?id='.$poll_ov['id'].'&close=close"><img src="../images/opened.png" /></a>',
	));
}

// Send the Output to the template Files.
$tpl->assign_vars(array(
		// Lang Strings
		'F_TITLE'			=> $user->lang['po_ad_edit_title'],
		'F_MULTIPLE'	=> $user->lang['po_ad_edit_multiple'],
		'F_COMMENTS'	=> $user->lang['po_ad_edit_comments'],
		'F_EDITABLE'	=> $user->lang['po_ad_edit_editable'],
		'F_INTERMED'	=> $user->lang['po_ad_edit_intermed'],
		'F_MODSTYLE'	=> $user->lang['po_ad_edit_modstyle'],
		'F_ENDDATE'		=> $user->lang['po_ad_edit_enddate'],
		'F_CLOSED'		=> $user->lang['po_ad_edit_closed'],
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['po_ad_edit_headlines'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'admin/editpolls.html',
	'display'       => true)
  );
?>