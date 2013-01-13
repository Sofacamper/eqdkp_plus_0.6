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
if($in->get('po_delete', '')){
	// Delete a poll
	$delpollsql = "DELETE FROM __polls_votes WHERE `poll_id` = '".$db->escape($in->get('po_id', 0))."'";
	$db->query($delpollsql);

	$deloptionssql = "DELETE FROM __polls_options WHERE `poll_id` = '".$db->escape($in->get('po_id', 0))."'";
	$db->query($deloptionssql);

	$delvotessql = "DELETE FROM __polls WHERE `id` = '".$db->escape($in->get('po_id', 0))."'";
	$db->query($delvotessql);
	$pdc->del('plugin.polls.overview');
	header('Location: editpolls.php');
}elseif ($in->get('po_submit', '')) {

	// Update the poll data
	session_start();
	$polls->update_poll($in->get('po_id', 0), $in->get('po_ed_title', ''), $in->get('po_ed_description', ''), $in->get('po_ed_multiple', 0), $in->get('po_ed_comments', 0), $in->get('po_ed_editable', 0), $in->get('po_ed_intermed', 0), $in->get('po_ed_modstyle', 0), $in->get('po_ed_enddate', ''), $in->get('po_ed_closed', 0));

	foreach ($in->getArray('opt', 'string') as $optid => $value){
		if ($in->get($optid, 0)){
			$polls->update_options($optid, $value);
		} else {
			$polls->insert_options($in->get('po_id', 0), $value);
		}
	}
	foreach ($in->getArray('po_opt_del', 'string') as $delid) {
		$polls->delete_options($delid);
	}

	// Reset all votes
	if ($in->get('po_reset', '')){
		$resetsql = "DELETE FROM __polls_votes WHERE `poll_id` = '".$db->escape($in->get('po_id', 0))."'";
		$db->query($resetsql);
	}
	$pdc->del_suffix('plugins.polls.poll'.$in->get('po_id', 0));
	session_unset();
	header('Location: editpolls.php');
} elseif ($in->get('opt_add', '')){

	// Add a new option - Not written in the database, just in the session
	session_start();
	$_SESSION['po_opt'][] = array(
		'option' => $in->get('newanswer', '')
	);
	$tpl->assign_vars(array(
		'TAB_PRESET'	=> $jqueryp->Tab_Select('config_tabs', '1'),
	));
} elseif ($in->get('opt_delete', '')) {
	session_start();
	unset($_SESSION['po_opt'][$in->get('opt_delete', '0')]);

	// Delete an option from the session
	if ($in->get($in->get('opt_delete', 0), 0)){
		$_SESSION['po_opt_del'][] = $in->get('opt_delete', '');
	}
	$tpl->assign_vars(array(
		'TAB_PRESET'	=> $jqueryp->Tab_Select('config_tabs', '1'),
	));
} elseif ($in->get('id', 0)) {
	session_start();
	session_unset();
	$_SESSION = array();

	// Get the poll settings
	$poll_cache = $pdc->get('plugin.polls.poll'.$in->get('id', 0));
	if (!$poll_cache){
		$sql = "SELECT * FROM __polls WHERE id='".$db->escape($in->get('id', 0))."'";
		$result = $db->query($sql);
		$polldetails = $db->fetch_record($result);
		$db->free_result($result);
		$pdc->put('plugin.polls.poll'.$in->get('id', 0), $polldetails, 86400);
	} else{
		$polldetails = $poll_cache;
	};

	// Copy the settings to the session
	$_SESSION['po_id']					= $polldetails['id'];
	$_SESSION['po_title']				= $polldetails['title'];
	$_SESSION['po_description'] = $polldetails['description'];
	$_SESSION['po_multiple']		= $polldetails['multiple'];
	$_SESSION['po_comments']		= $polldetails['comments'];
	$_SESSION['po_editable']		= $polldetails['editable'];
	$_SESSION['po_intermed']		= $polldetails['intermed'];
	$_SESSION['po_modstyle']		= $polldetails['modstyle'];
	$_SESSION['po_enddate']			= $polldetails['enddate'];
	$_SESSION['po_closed']			= $polldetails['closed'];

	$option_cache = $pdc->get('plugin.polls.poll'.$in->get('id', 0).'.options');
	if (!$option_cache){
		$sql = "SELECT * FROM __polls_options WHERE `poll_id` = ".$db->escape($in->get('id', 0))." ORDER BY id";
		$optresult = $db->query($sql);
		$option = $db->fetch_record_set($optresult);
		$db->free_result($optresult);
		$pdc->put('plugin.polls.poll'.$in->get('id', 0).'.options', $option, 86400);
	} else {
		$option = $option_cache;
	}

	// Copy the options to the $_SESSION
	foreach ($option as $opt){
		$_SESSION['po_opt'][$opt['id']] = $opt['option'];
	}
} else {
	redirect('plugins/polls/admin/editpolls.php');
}

// Set the pre-selection of the module style
if ($in->getArray('po_modstyle', 'string') == 1){
	$modstyle_opt1 = 'selected';
} else {
	$modstyle_opt2 = 'selected';
}

// Hide the 'Submit'-button when no options are defined
if ($in->getArray('po_opt', 'string') == array()){
	$submit = false;
} else {
	$submit = true;
}

// Send the Output to the template Files.
foreach($in->getArray('po_opt', 'string') as $id => $opt){
	if (is_array($opt)){
		$dbid = 'new';
		$opt = $opt['option'];
	} else {
		$dbid = $id;
	}
	$tpl->assign_block_vars('options', array(
		'ROWCLASS'	=> $eqdkp->switch_row_class(),
		'ID'				=> $id,
		'OPTION'		=> sanitize($opt),
		'DBID'			=> $dbid,
	));
}

$tpl->assign_vars(array(
	'PO_JS_CONFIG_TABS'	=> $jquery->Tab_header('config_tabs'),

	'ID'					=> $in->get('po_id', ''),
	'TITLE'				=> sanitize($in->get('po_title', '')),
	'DESCRIPTION'	=> sanitize($in->get('po_description')),
	'DESCAREA'		=> $jquery->wysiwyg('description'),
	'MULTIPLE'		=> ($in->get('po_multiple', 0) == '1') ? 'checked="checked"' : '',
	'COMMENTS'		=> ($in->get('po_comments', 0) == '1') ? 'checked="checked"' : '',
	'EDITABLE'		=> ($in->get('po_editable', 0) == '1') ? 'checked="checked"' : '',
	'INTERMED'		=> ($in->get('po_intermed', 0) == '1') ? 'checked="checked"' : '',
	'MODSTYLE1'		=> $modstyle_opt1,
	'MODSTYLE2'		=> $modstyle_opt2,
	'ENDDATE'			=> $jqueryp->Calendar('po_ed_enddate', $in->get('po_enddate', ''), '', array('change_fields'=>false, 'other_months'=>true)),
	'CLOSED'			=> ($in->get('po_closed', 0) == '1') ? 'checked="checked"' : '',
	'SUBMIT'			=> $submit,

	// Lang Strings
	'F_HEADLINE'		=> $user->lang['po_ad_edit_headline'],
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
	'F_NEWOPTION'		=> $user->lang['po_ad_sett_newoption'],
	'F_UPDATE'			=> $user->lang['po_ad_sett_update'],
	'F_RESET'				=> $user->lang['po_ad_edit_reset'],
	'F_DELETE'			=> $user->lang['po_ad_edit_delete'],
	'F_COMMON'			=> $user->lang['po_ad_edit_common'],
	'F_OPTIONS'			=> $user->lang['po_ad_edit_options'],
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['po_ad_edit_headline'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'admin/editpoll.html',
	'display'       => true)
  );
?>