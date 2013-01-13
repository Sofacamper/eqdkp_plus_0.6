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
$user->check_auth('u_polls_vote');

if ($in->get('vote_submit', '')){
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

$poll = $polls->get_polldetails($in->get('id', 0));
$votes = $polls->get_results($poll['id']);


	// Show the result if the poll is outdated, closed, or the setting 'intermediate result' is set
	if ($poll['intermed'] or $poll['closed'] or $outdated){
		// The Cake thing
		if ($poll['modstyle'] == 1){
			$output .= $polls->pm_cake($poll['id']);
		} else {
			// The Bars Thing
			$output .= $polls->pm_bars($poll['id']);
		}
	} else {
		$output .= $user->lang['po_pm_votecounted'].'<br>';
		if ($poll['enddate']){
			$output .= '<p><center>'.$user->lang['po_pm_enddate1'].'<b>'.$poll['enddate'].'</b>'.$user->lang['po_pm_enddate2'].'</center></p>';
		} else {
			$output .= '<p>'.$user->lang['po_pm_noenddate'].'</p>';
		}
	}

	echo $output;
?>