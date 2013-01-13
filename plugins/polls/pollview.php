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
// Comment System
if(is_object($pcomments) && $pcomments->version > '1.0.3'){
	$comm_settings = array(
		'attach_id' => $in->get('id', 0),
		'page'      => 'polls',
		'auth'      => 'u_polls_vote'
		);
	$pcomments->SetVars($comm_settings);
}

// Get the poll details
$polldetails = $polls->get_polldetails($in->get('id', 0));

if ($in->get('vote_submit', '')){
	// Check, if editable is true and user didn't vote yet
	$pollcheck = $polls->vote_check($in->get('id', 0));
	if (!$polldetails['editable'] && $pollcheck){

	} else {

		// Reset the existing votes
		$delsql = "DELETE FROM __polls_votes WHERE `poll_id` = '".$db->escape($in->get('id', 0))."' AND `user_id` = '".$db->escape($user->data['user_id'])."'";
		$db->query($delsql);

		foreach($in->getArray('check', 'string') as $id){
			$sql = "INSERT INTO __polls_votes (`poll_id`, `opt_id`, `user_id`) VALUES (
								'".$db->escape($in->get('id', 0))."',
								'".$db->escape($id)."',
								'".$db->escape($user->data['user_id'])."'
							)";
			$db->query($sql);
		}
		$pdc->del_suffix('plugin.polls.poll'.$in->get('id', 0));
	}
	redirect('plugins/polls/pollview.php?id='.$in->get('id', 0));
}

// Get the options
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

// Check the enddate
$outdated = $polls->date_check($polldetails['id']);

// Show the result if the poll is outdated, closed, or the setting 'intermediate result' is set
if ($polldetails['intermed'] or $polldetails['closed'] or $outdated){
	$voteresult = $polls->get_results($in->get('id', 0));

	// The sum of all votes
	$allvoted = $polls->get_allvotesum($in->get('id', 0));

	$f_allvoted = $user->lang['po_usr_pv_votesum'];
}

if ($polls->vote_check($in->get('id', 0))){
	$showbars = true;
}

foreach ($option as $optlist){
// Check for existing votes
	$check_sql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($in->get('id', 0))."' AND `user_id` = '".$db->escape($user->data['user_id'])."' AND `opt_id` = '".$db->escape($optlist['id'])."'";
	$check_result = $db->query($check_sql);
	if ($db->fetch_record($check_result)){
		$voted = true;
		$alreadyvoted = true;
	} else {
		$voted = false;
	}

	// Generate the bars - or not
	if ($voteresult && $showbars){
		$optbar = $jqueryp->ProgressBar('bbar_'.$optlist['id'], round($voteresult[$optlist['id']]['percent']), sanitize($optlist['option']), 'left');
		$percent = $voteresult[$optlist['id']]['absolute'].' ('.round($voteresult[$optlist['id']]['percent']).' %)';
	} else {
		$optbar = sanitize($optlist['option']);
	}

	$tpl->assign_block_vars('optlist', array(
		'OPTID'			=> $optlist['id'],
		'ROWCLASS'	=> $eqdkp->switch_row_class(),
		'OPTION'		=> $optbar,
		'PERCENT'		=> $singlevoted.$percent,
		'CHECKED'		=> ($voted) ? 'checked="true"' : '',
	));
}

// Disable the 'Submit'-button, if the poll ist outdated, closed, or editable is set to 'no'
if (($alreadyvoted && $polldetails['editable'] == false) or $outdated or $polldetails['closed']){
	$disabled = true;
}

// Output of the description
$polldescription = $polldetails['description'];
$polldescription = $bbcode->toHTML($polldescription);
$bbcode->SetSmiliePath($eqdkp_root_path.'libraries/jquery/images/editor/icons');
$polldescription = $bbcode->MyEmoticons($polldescription);

if (!$polldetails['intermed']){
	if ($polldetails['enddate']){
		$enddate = $user->lang['po_pm_enddate1'].'<b>'.$polldetails['enddate'].'</b>'.$user->lang['po_pm_enddate2'];
	} else {
		$enddate = $user->lang['po_pm_noenddate'];
	}
}

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'ID'						=> $polldetails['id'],
	'TITLE'					=> sanitize($polldetails['title']),
	'DESCRIPTION'		=> $polldescription,
	'MULTIPLE'			=> ($polldetails['multiple']) ? true : false,
	'DISABLED'			=> ($disabled) ? 'disabled="true"' : '',
	'CLOSED'				=> ($polldetails['closed']) ? true : false,
	'VOTESUM'				=> $allvoted,
	'ENDDATE'				=> $enddate,

	//comments
	'ENABLE_COMMENTS'     => ($polldetails['comments']) ? true : false,
	'COMMENTS'            => $pcomments->Show(),

	// Lang Strings
	'F_OPTIONS'			=> $user->lang['po_usr_pv_opthl'],
	'F_VOTE'				=> ($alreadyvoted) ? $user->lang['po_pm_changeopinion'] : $user->lang['po_usr_pv_vote'],
	'F_VOTESUM'			=> $f_allvoted,
));

// Init the Template
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['polls'],
	'template_path'	=> $pm->get_data('polls', 'template_path'),
	'template_file' => 'pollview.html',
	'display'       => true)
  );
?>