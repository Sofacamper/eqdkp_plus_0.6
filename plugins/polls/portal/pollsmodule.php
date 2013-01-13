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


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// You have to define the Module Information
$portal_module['pollsmodule'] = array(
			'name'			   => 'Polls Module',
			'path'			   => 'pollsmodule',
			'version'		   => '1.0.0',
			'author'       => 'BadTwin',
			'contact'		   => 'badtwin@gmx.at',
			'description'  => 'Portal module for the \'Polls\'-plugin',
			'positions'    => array('left1', 'left2', 'right'),
      'signedin'     => '1',
      'install'      => array(
			                   'autoenable'        => '1',
			                   'defaultposition'   => 'left1',
			                   'defaultnumber'     => '2',
			                   ),
    );

global $pdc;
$polllist = $pdc->get('plugin.polls.polllist');
if (!$polllist){
	$polllist['last'] = $user->lang['po_pm_last'];
	$polllist['random'] = $user->lang['po_pm_random'];
	$sql = 'SELECT * FROM __polls ORDER BY id DESC';
	$result = $db->query($sql);
	while ($pollarray = $db->fetch_record($result)){
		$polllist[$pollarray['id']] = $pollarray['title'];
	}
	$pdc->put('plugin.polls.polllist', $polllist, 86400);
} else {
	$polllist = $polllist;
}

// Settings
$portal_settings['pollsmodule'] = array(
	'pm_polls_pollshown'	=> array(
		'name'  		=>	'pm_polls_pollshown',
		'language'	=>	'po_pm_pollshown',
		'property'	=>	'dropdown',
		'options'  	=>	$polllist,
	),
);

// The output function
if(!function_exists(pollsmodule_module)){
  function pollsmodule_module(){
  	global $db, $html, $eqdkp_root_path, $eqdkp, $user, $bbcode, $jqueryp, $pdc, $conf_plus;
  	if ($user->data['user_id'] == ANONYMOUS){
			$output = $user->lang['po_pm_guestinfo'];
		} elseif (!$user->check_auth('u_polls_vote', false)){
			$output = $user->lang['po_pm_nopermission'];
		} else {
			require_once($eqdkp_root_path.'plugins/polls/include/polls.class.php');
			$polls = new polls;


			if ($conf_plus['pm_polls_pollshown'] == 'random'){
				$sql = "SELECT * FROM __polls ORDER BY RAND() LIMIT 0,1";
			} elseif (is_numeric($conf_plus['pm_polls_pollshown'])) {
				$sql = "SELECT * FROM __polls WHERE id='".$conf_plus['pm_polls_pollshown']."'";
			} else {
				$sql = "SELECT * FROM __polls ORDER BY id DESC LIMIT 0,1";
			}
			$result = $db->query($sql);
			$poll = $db->fetch_record($result);

			// Output of the description
			$polldescription = $poll['description'];
			$polldescription = $bbcode->toHTML($polldescription);
			$bbcode->SetSmiliePath($eqdkp_root_path.'libraries/jquery/images/editor/icons');
			$polldescription = $bbcode->MyEmoticons($polldescription);

			if (!$poll['title']){
				$output = '<b><center>'.$user->lang['po_pm_nopolls'].'</center></b>';
			} else {

				$output = '<div align="center">
											<h3><a href="'.$eqdkp_root_path.'plugins/polls/pollview.php?id='.$poll['id'].'">'.sanitize($poll['title']).'</a></h3>
										</div>
										<br />'.$polldescription;

				// Show the result, if the user voted, and the intermediate result is acivated, or the poll is closed or outdated, else show the 'vote'-view
				$outdated = $polls->date_check($poll['id']);

				if ($polls->vote_check($poll['id'])){
					// Show the result if the poll is outdated, closed, or the setting 'intermediate result' is set
					if ($poll['intermed'] or $poll['closed'] or $outdated){
						// The Cake thing
						if ($poll['modstyle'] == 1){
							$output .= utf8_decode($polls->pm_cake($poll['id']));
						} else {
							// The Bars Thing
							$output .= utf8_decode($polls->pm_bars($poll['id']));
						}
					} else {
						$output .= $polls->show_uservote($poll['id']);
					}
				// Generate the output for voting
				} else {
					$polldetails = $polls->get_polldetails($poll['id']);
					$optionsarray = $polls->get_polloptions($poll['id']);

					$output .= '<script type=\'text/javascript\'>
									// wait for the DOM to be loaded
									$(document).ready(function() {
										$(\'#pmvotesform\').ajaxForm({
											target: \'#pmvotesoutput\',
											beforeSubmit:  function() {
												$(\'#pmvotesoutput\').html(\'<img src="'.$eqdkp_root_path.'plugins/polls/images/loading_circle_big.gif" alt="Loading...">\');
											},
											success: function() {
												$(\'#pmvotessubmit\').hide();
												$(\'#pmvotesform\').hide();
											},
										});
									});
								</script>
								<form id="pmvotesform" action="'.$eqdkp_root_path.'plugins/polls/portal/include.php" method="POST">
									<input type="hidden" name="id" value="'.$poll['id'].'" />';
										$output .= '<table width="100%">';
										foreach ($optionsarray as $option){
											$output .= '<tr>';
											if ($polldetails['multiple']){
												$output .= '<td><input type="checkbox" name="check[]" id="'.$option['id'].'" value="'.$option['id'].'"</td>
																		<td><label for="'.$option['id'].'">'.$option['option'].'</label></td>';
											} else {
												$output .= '<td><input type="radio" name="check[]" id="'.$option['id'].'" value="'.$option['id'].'"</td>
																		<td><label for="'.$option['id'].'">'.$option['option'].'</label></td>';
											}
											$output .= '</tr>';
										}
										$output .= '<tr><td colspan="2" align="center"><input id="pmvotessubmit" type="submit" class="mainoption" name="vote_submit" value="'.$user->lang['po_usr_pv_vote'].'" /></tr></td>';
										$output .= '</table>';
										$output .= '</form>
								<div id="pmvotesoutput"></div>';
				}
			}
		}
		return $output;
  }
}
?>