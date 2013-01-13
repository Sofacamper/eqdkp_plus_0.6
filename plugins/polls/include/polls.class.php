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

class polls {
	function update_settings($confname, $confvalue){
		global $db;
		$sql = "UPDATE __polls_settings SET config_value = '".$db->escape($confvalue)."' WHERE config_name = '".$db->escape($confname)."'";
		$db->query($sql);
	}

	function create_poll(){
		global $db, $in;
		$sql = "INSERT INTO __polls (title, description, multiple, comments, editable, intermed, modstyle, enddate, closed) VALUES (
							'".$db->escape($in->get('po_title', ''))."',
							'".$db->escape($in->get('po_description', ''))."',
							'".$db->escape($in->get('po_multiple'))."',
							'".$db->escape($in->get('po_comments'))."',
							'".$db->escape($in->get('po_editable'))."',
							'".$db->escape($in->get('po_intermed'))."',
							'".$db->escape($in->get('po_modstyle'))."',
							'".$db->escape($in->get('po_enddate'))."',
							'".$db->escape($in->get('po_closed'))."')";
		$db->query($sql);

		$pollid = $db->insert_id();
		foreach ($in->getArray('po_opt', 'string') as $option){
			$sql = "INSERT INTO __polls_options (`poll_id`, `option`) VALUES (
								'".$db->escape($pollid)."',
								'".$db->escape($option)."')";
			$db->query($sql);
		}
		session_unset();
		$_SESSION = array();
		header('Location: editpolls.php');
	}

	function update_poll($pollid, $title, $description, $multiple, $comments, $editable, $intermed, $modstyle, $enddate, $closed){
		global $db, $in, $pdc;
		$sql = "UPDATE __polls SET
							title	= '".$db->escape($title)."',
							description = '".$db->escape($description)."',
							multiple = '".$db->escape($multiple)."',
							comments = '".$db->escape($comments)."',
							editable = '".$db->escape($editable)."',
							intermed = '".$db->escape($intermed)."',
							modstyle = '".$db->escape($modstyle)."',
							enddate = '".$db->escape($enddate)."',
							closed = '".$db->escape($closed)."'
						WHERE id = '".$pollid."'";
		$db->query($sql);
		$pdc->del('plugin.polls.overview');
		$pdc->del_suffix('plugin.polls.poll'.$pollid);
	}

	function update_options($optid, $value){
		global $db;
		$sql = "UPDATE __polls_options SET
							`option` = '".$db->escape($value)."'
						WHERE id = '".$db->escape($optid)."'";
		$db->query($sql);
	}

	function insert_options($pollid, $value){
		global $db;
		$sql = "INSERT INTO __polls_options (`poll_id`, `option`) VALUES (
							'".$db->escape($pollid)."',
							'".$db->escape($value)."')";
		$db->query($sql);
	}

	function delete_options($pollid){
		global $db;
		$sql = "DELETE FROM __polls_options WHERE id='".$db->escape($pollid)."'";
		$db->query($sql);

		// Delete the votes
		$sql = "DELETE FROM __polls_votes WHERE `opt_id` = '".$db->escape($pollid)."'";
		$db->query($sql);
	}

	function get_results($pollid){
		global $db, $pdc;
		$pollresult = $pdc->get('plugin.polls.poll'.$pollid.'.result');
		if (!$pollresult){
			$sql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($pollid)."'";
			$allresult = $db->query($sql);
			$allvotes = $db->num_rows($allresult);

			$sql = "SELECT * FROM __polls_options WHERE `poll_id` = '".$db->escape($pollid)."'";
			$optcount = $db->query($sql);

			while ($option = $db->fetch_record($optcount)){
				$singlesql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($pollid)."' AND `opt_id` = '".$db->escape($option['id'])."'";
				$singleresult = $db->query($singlesql);
				$singlevoted = $db->num_rows($singleresult);

				$singlepercent = ($singlevoted / $allvotes) * 100;

				$percent[$option['id']]['percent'] = $singlepercent;
				$percent[$option['id']]['absolute'] = $singlevoted;
			}
			$pdc->put('plugin.polls.poll'.$pollid.'.result', $percent, 86400);
		} else {
			$percent = $pollresult;
		}
		return $percent;
	}

	function vote_check($pollid){
		global $db, $user, $pdc;
		$votecheck = $pdc->get('plugins.polls.poll'.$pollid.'.votecheck.'.$user->data['user_id']);
		if (!$votecheck){
			$sql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($pollid)."' AND `user_id` = '".$db->escape($user->data['user_id'])."'";
			$voteresult = $db->query($sql);
			$votecheck = $db->num_rows($voteresult);
			$db->free_result($votecheck);
			$pdc->put('plugins.polls.poll'.$pollid.'.votecheck.'.$user->data['user_id'], $votecheck, 86400);
		}

		if ($votecheck == 0){
			return false;
		} else {
			return true;
		}
	}

	function get_polldetails($pollid){
		global $db, $pdc;
		$polldetails = $pdc->get('plugins.polls.poll'.$pollid.'.details');
		if (!$polldetails){
			$sql = "SELECT * FROM __polls WHERE id = '".$db->escape($pollid)."' LIMIT 0,1";
			$detailresult = $db->query($sql);
			$polldetails = $db->fetch_record($detailresult);
			$db->free_result($detailresult);
			$pdc->put('plugins.polls.poll'.$pollid.'.details', $polldetails, 86400);
		} else {
			$polldetails = $polldetails;
		}
		return $polldetails;
	}

	function get_polloptions($pollid){
		global $db, $pdc;
		$polloptions = $pdc->get('plugins.polls.poll'.$pollid.'.options');
		if (!$polloptions){
			$sql = "SELECT * FROM __polls_options WHERE `poll_id` = '".$db->escape($pollid)."' ORDER BY id";
			$optionsresult = $db->query($sql);
			$polloptions = $db->fetch_record_set($optionsresult);
			$db->free_result($optionsresult);
			$pdc->put('plugins.polls.poll'.$pollid.'.options', $polloptions, 86400);
		} else {
			$polloptions = $polloptions;
		}
		return $polloptions;
	}

	function get_allvotesum($pollid){
		global $pdc, $db;
		$allvoted = $pdc->get('plugin.polls.poll'.$pollid.'.votecount');
		if (!$allvoted){
			$allsql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($pollid)."'";
			$allresult = $db->query($allsql);
			$allvoted = $db->num_rows($allresult);
			$db->free_result($allresult);
			$pdc->put('plugin.polls.poll'.$pollid.'.votecount', $allvoted, 86400);
		} else {
			$allvoted = $allvoted;
		}
		return $allvoted;
	}

	function pm_cake($pollid){
		global $eqdkp, $eqdkp_root_path, $jqueryp, $user;
		$votes = $this->get_results($pollid);

		$color[1]  = '#FF7C0A';
		$color[2]  = '#9382C9';
		$color[3]  = '#AAD372';
		$color[4]  = '#C69B6D';
		$color[5]  = '#68CCEF';
		$color[6]  = '#F48CBA';
		$color[7]  = '#FFFFFF';
		$color[8]  = '#1A3CAA';
		$color[9]  = '#FFF468';
		$color[10] = '#C41F3B';

		$i = 1;

		foreach ($votes as $percent){
			$chartarray[] = array(
				'value' => $percent['percent'],
				'color' => $color[$i],
			);
			$i++;
			if ($i == 11){ $i = 1; }
		}

		// Define the options of the cake
		$chartoptions['border'] = '0.0';
		$chartoptions['background'] = 'transparent';
		$chartoptions['piemargin'] = '1';

		$output = '<style type="text/css">
								#pollmodulechart {
									margin-left: auto;
									margin-right: auto;
									width: 290px;
								}
							</style>';

		$output .= $jqueryp->PieChart('pollmodulechart', $chartarray, '', $chartoptions, '1', true);
		$output .= '<table align="center" width="80%" cellspacing="2" cellpadding="0" style="border: 1px white solid;">';

		$textset = $this->get_polloptions($pollid);

		$i = 1;
		foreach ($textset as $text){
			$output .=	'<tr><td bgcolor="'.$color[$i].'" width="13px"></td>
									<td style="padding-left: 10px;" class="'.$eqdkp->switch_row_class().'">'.sanitize(utf8_encode($text['option'])).' ('.$votes[$text['id']]['absolute'].' - '.round($votes[$text['id']]['percent']).'%)</td></tr>';
			$i++;
			if ($i == 11){ $i = 1; }
		}
		$output .= '</table><br />';
		$output .= '<div align="center">'.$user->lang['po_usr_pv_votesum'].$this->get_allvotesum($pollid).'</div>';
		$output .= '<div align="center"><a href="'.$eqdkp_root_path.'plugins/polls/polls.php">'.$user->lang['po_pm_allpolls'].'</a></div>';

		return $output;
	}

	function pm_bars($pollid){
	global $user, $jqueryp, $eqdkp_root_path;
		$votes = $this->get_results($pollid);
		foreach ($votes as $percent){
			$chartarray['percent'][] = $percent['percent'];
		}

		$textset = $this->get_polloptions($pollid);

		$output = '<table width ="300px">';
		foreach ($textset as $text){
			if (strlen($text['option']) >= 25) {
				$textoutput = substr($text['option'], 0, 25).'...';
			} else {
				$textoutput = $text['option'];
			}

			$output .= '<tr><td>'.$jqueryp->ProgressBar('bar_'.$text['id'], round($votes[$text['id']]['percent']), sanitize(utf8_encode($textoutput)).' ('.round($votes[$text['id']]['percent']).'%)').'</td></tr>';
		}
		$output .= '</table>';
		$output .= '<div align="center">'.$user->lang['po_usr_pv_votesum'].$this->get_allvotesum($pollid).'</div>';
		$output .= '<div align="center"><a href="'.$eqdkp_root_path.'plugins/polls/polls.php">'.$user->lang['po_pm_allpolls'].'</a></div>';

		return $output;
	}

	function date_check($pollid){
		$polldetails = $this->get_polldetails($pollid);

		$var = $polldetails['enddate'];
		$day   = substr($var,0,2);
		$month = substr($var,3,2);
		$year  = substr($var,6,4);
		$timestamp = mktime(0,0,0, $month, $day, $year);
		$now = time();
		if ($var && $now > $timestamp){
			return true;
		} else {
			return false;
		}
	}

	function show_uservote($pollid){
		global $eqdkp_root_path, $user, $db, $pdc;

		$optionsarray = $this->get_polloptions($pollid);
		$polldetails = $this->get_polldetails($pollid);

		if (!$polldetails['editable']){
			$disabled = 'disabled="true"';
		}

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
				<input type="hidden" name="id" value="'.$pollid.'" />';
					if ($polldetails['enddate']){
						$output .= '<p><center>'.$user->lang['po_pm_enddate1'].'<b>'.$polldetails['enddate'].'</b>'.$user->lang['po_pm_enddate2'].'</center></p>';
					} else {
						$output .= '<p>'.$user->lang['po_pm_noenddate'].'</p>';
					}

					$output .= '<table width="100%">';
					foreach ($optionsarray as $option){
						$votecheck = $pdc->get('plugin.polls.poll'.$pollid.'.opt'.$option['id'].'.'.$user->data['user_id']);
						if (!$votecheck){
							$check_sql = "SELECT * FROM __polls_votes WHERE `poll_id` = '".$db->escape($pollid)."' AND `user_id` = '".$db->escape($user->data['user_id'])."' AND `opt_id` = '".$db->escape($option['id'])."'";
							$check_result = $db->query($check_sql);
							$votecheck = $db->fetch_record($check_result);
							if ($votecheck){
								$votecheck = 'yes';
							} else {
								$votecheck = 'no';
							}
							$db->free_result($check_result);
							$pdc->put('plugin.polls.poll'.$pollid.'.opt'.$option['id'].'.'.$user->data['user_id'], $votecheck, 86400);
						}
						if ($votecheck == 'yes'){
							$voted = 'checked = "true"';
						} else {
							$voted = '';
						}
						$output .= '<tr>';
						if ($polldetails['multiple']){
							$output .= '<td><input type="checkbox" '.$voted.' name="check[]" id="'.$option['id'].'" value="'.$option['id'].'" '.$disabled.' /></td>
													<td><label for="'.$option['id'].'">'.$option['option'].'</label></td>';
						} else {
							$output .= '<td><input type="radio" '.$voted.' name="check[]" id="'.$option['id'].'" value="'.$option['id'].'" '.$disabled.' /></td>
													<td><label for="'.$option['id'].'">'.$option['option'].'</label></td>';
						}
						$output .= '</tr>';
					}
					$output .= '<tr><td colspan="2" align="center"><input id="pmvotessubmit" type="submit" class="mainoption" name="vote_submit" value="'.$user->lang['po_pm_changeopinion'].'" '.$disabled.' /></tr></td>';
					$output .= '</table>';
					$output .= '</form>
			<div id="pmvotesoutput"></div>';

		return $output;
	}
}
?>