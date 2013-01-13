<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2010-02-14 14:07:46 +0100 (Sun, 14 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 7249 $
 * 
 * $Id: listraids.php 7249 2010-02-14 13:07:46Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../';
include_once('includes/common.php');

$user->check_auth('u_raidplan_list');

if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }
$raidplan = $pm->get_plugin('raidplan');
$total_recent_raids = $total_raids = 0;

// Time Debug thing
$debugtime1 = $stime->rp_mktime('14',0,0,'10','23','2008');
$debugtime2 = $stime->rp_mktime('14',0,0,'10','30','2008');
$myTimeDebug  = $debugtime1.' -> '.$stime->DoDate('%d.%m.%Y; %H:%M', $debugtime1).'<br/>';
$myTimeDebug .= $debugtime2.' -> '.$stime->DoDate('%d.%m.%Y; %H:%M', $debugtime2).'<br/>';
$myTimeDebug .= $stime->DoTime().' -> '.$stime->DoDate('%d.%m.%Y; %H:%M', $stime->DoTime()).'<br/>';
if($_GET['timedebug']=='true'){echo $myTimeDebug;die('time DEBUG');}

// RSS Feed
if($pcache->CacheWritable()){
  include_once($eqdkp_root_path."libraries/UniversalFeedCreator/UniversalFeedCreator.class.php");
  $rss = new UniversalFeedCreator();
  
  $rss->title           = $user->lang['rp_rss_title'];
  $rss->description     = $user->lang['rp_rss_description'];
  $rss->link            = $rpclass->BuildLink();
  $rss->syndicationURL  = $rpclass->BuildLink().$_SERVER['PHP_SELF'];
}

// Check for repeatable raids
if($conf['rp_rep_enable'] == 1){
	$rpclass->CheckIfClone();
}

// Flush the Member Groups...
if($conf['rp_enbl_resetday'] == 1 && ($stime->DoDate("%w", $stime->DoTime()) == $conf['rp_grp_resetday']) && (!$conf['rp_tmp_expvalue'] || $conf['rp_tmp_expvalue'] == '')){
	$rpclass->FlushMemberGroups();
}

// remove the repeate blocker for flush
if($conf['rp_tmp_expvalue'] && ($stime->DoTime() > ($conf['rp_tmp_expvalue']+(2*24*3600)))){
  $sql = "UPDATE `".RP_CONFIG_TABLE."` SET config_value='' WHERE config_name='rp_tmp_expvalue';";
  $db->query($sql);
}

// multi confirm
if(isset($_POST['subscribed_member_id']) && is_array($_POST['sign_id'])){
  // Build the Open/Close Array
  $ccsql = "SELECT raid_id,raid_closed, raid_date_subscription FROM ".RP_RAIDS_TABLE.";";
  $ccresult = $db->query($ccsql);
  while( $closed_row = $db->fetch_record($ccresult) ){
    $multisign_array[$closed_row['raid_id']] = array(
        'closed'      => ($closed_row['raid_closed'] == 1) ? true : false,
        'sign_open'   => ($closed_row['raid_date_subscription'] > $stime->DoTime()) ? true : false,
    );
  }
  $db->free_result($ccresult);
  // Build the rank row
  $ssql = "SELECT member_rank_id FROM ".MEMBERS_TABLE." WHERE member_id='".(int) $_POST['subscribed_member_id']."'";
  $result = $db->query($ssql);
  $rank_row = $db->fetch_record($result);
  
  switch ($_POST['rp_multisign']){
			case "status1"		: $statusID = 1;		break; // Sign On
			case "status2"		: $statusID = 2;		break; // Sign Off
			case "status3"		: $statusID = 3;		break; // Not Sure
	}
	
	// if its an officer or auto-confirm is on:
  if(($rank_row['member_rank_id'] == $conf['rp_rank_team'] && $conf['rp_enable_team'] == 1) || ($rank_row['member_rank_id'] == $conf['rp_rank'] && $conf['rp_enabl_officr'] == 1 && $conf['rp_disabl_cl_ac'] != 1)){
    $statusID = ($statusID == 1) ? 0: $statusID; 
	}
	
  foreach($_POST['sign_id'] as $cmraid_id){
    if(!$multisign_array[$cmraid_id]['closed'] && $multisign_array[$cmraid_id]['sign_open']){
      srand((double)microtime()*1000000);
      // Delete old Raid signin with provided ID
      $del_sql = 'DELETE FROM ' . RP_ATTENDEES_TABLE . " 
                  WHERE raid_id='".stripslashes($cmraid_id)."' 
                  AND ".$rpclass->User2Members($user->data['user_id'], true).";";
      $db->query($del_sql);
      
      // Add the new Status for that Raid ID
			$query = $db->build_query('INSERT', array(
				'raid_id'								=> stripslashes($cmraid_id),
				'member_id'     				=> stripslashes($_POST['subscribed_member_id']),
				'attendees_subscribed'	=> $statusID,
				'attendees_signup_time' => $stime->DoTime(),
				'attendees_change_time' => $stime->DoTime(),
				'attendees_random'			=> rand(1,100),
				'role'                  => ($_POST['subscribed_role']) ? $_POST['subscribed_role'] : '',
				'attendees_note'        => ($_POST['multi_note']) ? str_replace("'", "\'", stripslashes($_POST['multi_note'])) : '',
        ));
				$db->query('INSERT INTO ' . RP_ATTENDEES_TABLE . $query);
	 }
  }
} // end of multiconfirm

$datesortby = ($conf['rp_raidsort'] == 1) ? array('raid_date desc', 'raid_date') : array('raid_date', 'raid_date desc');

$sort_order = array(
    0 => $datesortby,
    1 => array('raid_name', 'raid_name desc'),
    2 => array('raid_note', 'raid_note desc'),
    3 => array('raid_value desc', 'raid_value'),
		4 => array('raid_date_subscription', 'raid_date_subscription desc')
);
 
$current_order = switch_order($sort_order);
$start = $in->get('start', 0);

// Which raids should be selected?
$raidatime = $stime->DoTime() - (60*60*24*$conf['rp_last_days']);     // last days for classic list

// calendar time
$cal_rpmonth    = $stime->DoDate("%m",$stime->DoTime());
$cal_year       = $stime->DoDate("%Y",$stime->DoTime());
if ($cal_rpmonth > 12){
  $cal_rpmonth  = sprintf ("%02d", ($cal_rpmonth - 12));
  $cal_year     = $cal_year + 1;
}
$raidctime = gmmktime(0,0,0,$rpmonth,1,$year);
$raidbtime = ($raidctime < $raidatime) ? $raidctime : $raidatime;

// select the raid values
$sql = 'SELECT raid_id, raid_name, raid_date, raid_note, raid_value, raid_attendees, raid_date_subscription, raid_date_invite, raid_closed, raid_date_finish, raid_leader
        FROM ' . RP_RAIDS_TABLE . '
        WHERE raid_date>' . $raidbtime.'
        ORDER BY '.$current_order['sql'];
if (!($raids_result = $db->query($sql))) { message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql); }

// generate an array with the attendee stuff
$sql_attendee  = 'SELECT m.member_id, a.attendees_subscribed, a.raid_id, mu.user_id
                  FROM '.RP_ATTENDEES_TABLE.' a, '.MEMBERS_TABLE." m, ".MEMBER_USER_TABLE." mu
                  WHERE m.member_id=a.member_id
                  AND mu.member_id=m.member_id";
$attendee_result = $db->query($sql_attendee);

while( $attendee_row = $db->fetch_record($attendee_result) ){
  $attendees[$attendee_row['raid_id']][$attendee_row['member_id']] = array(
        'subscribed'  => $attendee_row['attendees_subscribed'],
        'user_id'     => $attendee_row['user_id']
    );
}
$db->free_result($attendee_result);

// generate an array with the guest stuff
if($conf['rp_use_guests'] == 1){
  $sql_guest = "SELECT membername, raid_id FROM " . RP_TEMPMEMBERS_TABLE . ";";
	$guest_result = $db->query($sql_guest);

  while( $guest_row = $db->fetch_record($guest_result) ){
    $guests[$guest_row['raid_id']][] = $guest_row['membername'];
  }
  $db->free_result($guest_result);
}

while ( $row = $db->fetch_record($raids_result) ){
  	$confirmstatus    = '<img src="./images/status/status5.gif" align="absmiddle"/>';
    $short_confstatus = 5;
    $count_status     = $count_array = '';
    $tmp_attendees    = $attendees[$row['raid_id']];
    $tmp_guests       = $guests[$row['raid_id']];
    
    if(is_array($tmp_attendees)){
    	foreach($tmp_attendees as  $memberid=>$memdata){
        $count_array[$memberid] = $memdata['subscribed'];
        if ($user->data['user_id'] == $memdata['user_id']){
          switch ($memdata['subscribed']) {
            case 0: $confirmstatus = '<img src="./images/status/status0.gif" align="absmiddle" />';$ccstat = true;$short_confstatus = 0;break;
            case 1: $confirmstatus = '<img src="./images/status/status1.gif" align="absmiddle" />';$ccstat = false;$short_confstatus = 1;break;
            case 2: $confirmstatus = '<img src="./images/status/status2.gif" align="absmiddle" />';$ccstat = false;$short_confstatus = 2;break;
            case 3: $confirmstatus = '<img src="./images/status/status3.gif" align="absmiddle" />';$ccstat = false;$short_confstatus = 3;break;
          }
        }
      } // end foreach
    } // end foreach-if
    if(is_array($count_array)&& count($count_array) != 0){
      $count_status =  array(
                        $rpclass->count_repeat_values('0', $count_array),
                        $rpclass->count_repeat_values('1', $count_array),
                        $rpclass->count_repeat_values('2', $count_array),
                        $rpclass->count_repeat_values('3', $count_array)
                      );
    }
	
    $count_guests = (is_array($tmp_guests)) ? count($tmp_guests) : 0;
  
    // check the status of raid
    // get the time of 24h later
    $actualtime = $stime->DoTime();
    $timeh = $actualtime + 86400;
      
    // time to signin
    $datediff_stat = $stime->datediff($actualtime, $row['raid_date_subscription']);
    $status_diff = $datediff_stat['days']."<b>".$user->lang['rp_status_day']."</b> ".
                     $datediff_stat['hours']."<b>".$user->lang['rp_status_hours']."</b> ".
                     $datediff_stat['minutes']."<b>".$user->lang['rp_status_minutes']."</b> ";
      
    // The time Tooltip
    if ($row['raid_date_subscription'] > $timeh && $row['raid_closed'] != 1){
      $raidstatusicon = "<img src='images/status/ok.png' align='absmiddle' />";
      $overlibmaintxt =  $user->lang['rp_status_signintime']."<br />".$status_diff;
    }elseif ($row['raid_date_subscription'] < $timeh && $row['raid_date_subscription'] > $actualtime && $row['raid_closed'] != 1){
      $raidstatusicon = "<img src='images/status/24hours.png' align='absmiddle' />";
      $overlibmaintxt =  $user->lang['rp_status_signintime']."<br />".$status_diff;
    }elseif ($row['raid_closed'] == 1){
      $raidstatusicon = "<img src='images/status/closed.png' align='absmiddle' />";
      $overlibmaintxt =  $user->lang['rp_status_quit'];
    }else{
      $raidstatusicon = "<img src='images/status/closed.png' align='absmiddle' />";
      $overlibmaintxt =  $user->lang['rp_status_closed'];
    }
    
    // The Status Count Information
    $statuscount_info = '<div class="status0">'.($count_guests + $count_status[0]).' '.$user->lang['rp_tt_status0'].'</div>'.
                        '<div class="status1">'.intval($count_status[1]).' '.$user->lang['rp_tt_status1'].'</div>'.
                        '<div class="status2">'.intval($count_status[2]).' '.$user->lang['rp_tt_status2'].'</div>'.
                        '<div class="status3">'.intval($count_status[3]).' '.$user->lang['rp_tt_status3'].'</div>'.
                        '<div class="status_total">'.$row['raid_attendees'].' '.$user->lang['rp_tt_needed'].'</div>';
    
		If ($row['raid_attendees'] < 10) $row['raid_attendees']="0".$row['raid_attendees'];
		$raidicon = $rpclass->generateRaidIcon($row['raid_name']);
    
    $tmpraidname = ($row['raid_closed'] == 1) ? '<strike>'.$row['raid_name'].'</strike>' : $row['raid_name'];
    
    $raiddataarray[$row['raid_id']] = array(
        'raid_id'						=> $row['raid_id'],
        'raw_date'          => $row['raid_date'],
        'raid_date' 				=> ( !empty($row['raid_date']) ) ? $stime->DoDate($conf['timeformats']['short'], $row['raid_date']) : '&nbsp;',
        'invite_time'       => $stime->DoDate($conf['timeformats']['time'], $row['raid_date_invite']),
        'start_time'	 			=> $stime->DoDate($conf['timeformats']['time'], $row['raid_date']),
        'finish_time'       => $stime->DoDate($conf['timeformats']['time'], $row['raid_date_finish']),
        'raid_date_finish'  => $stime->DoDate($conf['timeformats']['medium'], $row['raid_date_finish']),
        'day'		 						=> $stime->DoDate($conf['timeformats']['day'], $row['raid_date']),
        'deadline'          => ( !empty($row['raid_date_subscription']) ) ? $stime->DoDate($conf['timeformats']['medium'], $row['raid_date_subscription']) : ' ',
        'raid_name' 				=> ( !empty($row['raid_name']) ) ? stripslashes($tmpraidname) : '&lt;<i>Not Found</i>&gt;',
        'count' 						=> intval($count_status[1])  . '/' . ($count_guests + $count_status[0]) . '/' .intval($count_status[2]).'/' .intval($count_status[3]).'/<b>' .$row['raid_attendees']. '</b>',
        'raid_note' 				=> ( !empty($row['raid_note']) ) ? stripslashes($row['raid_note']) : '',
        'raid_value' 				=> ( !empty($row['raid_value']) ) ? stripslashes($row['raid_value']) : '-1.00',
        'raidleader'        => $rpclass->MemberID2Name($row['raid_leader']),
        'raid_icon_plane'		=> ($raidicon['rss']) ? $raidicon['rss'] : '',
        'raid_icon'			    => ($raidicon['full']) ? '<img class="raid_icon" src="'.$raidicon['full'].'" alt="" width="16px" height="16px"/>' : '',
        'raid_icon_large'		=> ($raidicon['full']) ? '<img class="raid_icon" src="'.$raidicon['full'].'" alt="" width="30px" height="30px"/>' : '',
        'confirmed_status'	=> $confirmstatus,
        'calendar_status'   => ($short_confstatus != 5) ? true : false,
        'raid_status_icon'  => $raidstatusicon,
        'raidstatus_txt' 		=> $khrml->HTMLTooltip($overlibmaintxt, 'rp_tt_time'),
        'raidcount_txt'     => $statuscount_info,
        'closed'            => ($row['raid_closed'] == 1) ? true : false,
        'count_guests'      => $count_guests,
        'count_status0'     => intval($count_status[0]),
        'count_status1'     => intval($count_status[1]),
        'count_status2'     => intval($count_status[2]),
        'count_status3'     => intval($count_status[3]),
        'count_attendees'   => $row['raid_attendees'],
      );  
}
$db->free_result($raids_result); // free the memory

  // ---------------------------------------------------------
  // RSS Feed
  // ---------------------------------------------------------
  if($pcache->CacheWritable() && is_array($raiddataarray)){
    // copy the array for sorting
    $sortedraiddata = $raiddataarray;
    
    // Sort the array by Date!
    foreach($sortedraiddata as $raiddata_line){
			$raiddata_rows_fsort[] = $raiddata_line['raw_date'];
		}
		array_multisort($raiddata_rows_fsort, SORT_ASC, $sortedraiddata);
    
    // Add Feed items
    foreach($sortedraiddata as $rssdata){
      // show only open and future raids...
      if($rssdata['closed'] != '1' && $rssdata['raw_date'] > $stime->DoTime()){
        $placesfree = $rssdata['count_attendees']-($rssdata['count_status1']+$rssdata['count_guests'] + $rssdata['count_status0']);
        $placesfree = ($placesfree > 0) ? $placesfree : 0;
        $item = new FeedItem();
        $item->title        = sprintf($user->lang['rp_rss_itemtitle'],$rssdata['raid_name'],$rssdata['raid_date']);
        $item->link         = $rss->link .'plugins/raidplan/viewraid.php?r='.$rssdata['raid_id'];
        $item->description  = sprintf($user->lang['rp_rss_itemdesc'],$rssdata['raid_name'],$placesfree,$rssdata['deadline']);
        $item->date         = $stime->DoRFC2822($rssdata['raw_date']);
        $item->source       = $rss->link;
        $item->author       = "Raidplaner";
				$item->guid					= $rssdata['raid_id'];
				
				// Additional Feeds
				$additional_data = array(
					'icon'				=> $pcache->BuildLink().$rssdata['raid_icon_plane'],
					'raidleader'	=> $rssdata['raidleader'],
					'note'				=> $rssdata['raid_note'],
					'raid_value'	=> $rssdata['raid_value'],
					'deadline'		=> $rssdata['deadline'],
					'starttime'		=> $rssdata['start_time'],
					'invitetime'	=> $rssdata['invite_time'],
					'endtime'			=> $rssdata['finish_time'],
					'enddate'			=> $rssdata['raid_date_finish'],
					'raiddate'		=> $rssdata['raid_date'],
					'raidname'		=> $rssdata['raid_name'],
					'c_attendees'	=> $rssdata['count_attendees'],
					'c_confirmed'	=> $rssdata['count_status0'],
					'c_signedin'	=> $rssdata['count_status1'],
					'c_signedoff'	=> $rssdata['count_status2'],
					'c_backup'		=> $rssdata['count_status3'],
					'c_guests'		=> $rssdata['count_guests'],
					'freeplaces'	=> $placesfree,
				);
				$item->additionalElements	= $additional_data;
        $rss->addItem($item);
      }
    }
  }
    
  // ---------------------------------------------------------
  // Raid List
  // ---------------------------------------------------------
  if($conf['rp_mode'] == 0 || $conf['rp_mode'] == 2 || $conf['rp_mode'] == 4){
    $lastraidtime = ($stime->DoTime() - (60*60*24*$conf['rp_last_days']));
    $recentcount = 0;
    if(is_array($raiddataarray)){
      foreach($raiddataarray as $raid_values){
        // The Raid List
        if($raid_values['raw_date'] > $stime->DoTime(true)){
          $raidrow = 'raids_row';
          $total_raids++;
        }else{
          $raidrow =  'recent_raids';
        }
        
        if($raid_values['raw_date'] > $lastraidtime){
          if($raidrow ==  'recent_raids'){
            $total_recent_raids++;
          }
          $tpl->assign_block_vars($raidrow, array(
                'ROW_CLASS' 				=> $eqdkp->switch_row_class(),
                'RAID_ID'						=> $raid_values['raid_id'],
                'DATE' 							=> $raid_values['raid_date'],
                'INVITE' 						=> $raid_values['invite_time'],
                'START'	 						=> $raid_values['start_time'],
                'END'               => $raid_values['finish_time'],
                'DAY'		 						=> $raid_values['day'],
                'U_VIEW_RAID' 			=> 'viewraid.php'.$SID.'&amp;' . URI_RAID . '='.$raid_values['raid_id'],
                'NAME' 							=> $raid_values['raid_name'],
                'COUNT' 						=> $raid_values['count'],
                'RAIDATTCOUNT_TXT'  => $khrml->HTMLTooltip($raid_values['raidcount_txt'], 'rp_tt_stats'),
                'NOTE' 							=> $raid_values['raid_note'],
                'VALUE' 						=> $raid_values['raid_value'],
                'RAID_ICON'			    => $raid_values['raid_icon'],
                'NOT_SIGNED_IN'     => ($short_confstatus == 5) ? true : false,
                'CONFIRMED' 				=> $raid_values['confirmed_status'],
                'RAIDSTATUS_ICON' 	=> $raid_values['raid_status_icon'],
                'RAIDSTATUS_TXT' 		=> $raid_values['raidstatus_txt'],
        	));
        }
      }
    }
  }
  
  // ---------------------------------------------------------
  // Calendar Mode
  // ---------------------------------------------------------
  if($conf['rp_mode'] == 1 || $conf['rp_mode'] == 2 || $conf['rp_mode'] == 3 || $conf['rp_mode'] == 4){
    $output       = "";
    $rpmonth      = ($_GET['month'])  ? $_GET['month']  : $stime->DoDate("%m",$stime->DoTime());
    $year         = ($_GET['year'])   ? $_GET['year']   : $stime->DoDate("%Y",$stime->DoTime());
    $actualmonth  = $stime->DoDate("%m",$stime->DoTime());
    $amountmonths = ($conf['rp_mode'] == 3 || $conf['rp_mode'] == 4) ? 2 : 1;
    
    // save the next & last month to a variable
    $tmpnextmonth = $rpmonth +1;
    $tmplastmonth = $rpmonth -1;
    $tmpyear_n = $tmpyear_l = $year;
    if ($tmpnextmonth > 12){            // if december
      $tmpnextmonth = sprintf ("%02d", ($tmpnextmonth - 12));
      $tmpyear_n = $year + 1;
    }elseif($tmplastmonth < 1){
      $tmplastmonth = sprintf ("%02d", ($tmplastmonth + 12));
      $tmpyear_l = $year - 1;
    }
    
    for ($idays = 1; $idays <= $amountmonths; $idays++){  
      // Get the actual month name...
      if($idays >= '2'){
        $year         = ($tmpnextmonth > 12 || $tmpnextmonth == 1)  ? $year+1          : $year;
        $tmpnextmonth = ($tmpnextmonth == 12) ? $tmpnextmonth-11 : $tmpnextmonth+1;
        $rpmonth      = ($rpmonth == 12)      ? $rpmonth-11      : $rpmonth+1;
      }
      //echo "(".$rpmonth."|".$tmpnextmonth.")";
      $mnthnames  = $stime->MonthName($rpmonth, $year);
      
      // the weekday for the header
      $weekdays   = $stime->weekdays($conf['rp_cal_startday']);
  
      // the Tooltip for the next Month
      $future_monthname   = $stime->MonthName($tmpnextmonth, $tmpyear_n);
      $future_raid_count  = $rpclass->countFutureDates($raiddataarray, gmmktime(0,0,0,$tmpnextmonth,'1',$tmpyear_n));
      $next_month_tt      = $user->lang['rp_next_month'].': '.$future_monthname['name'].'<br/>';
      $next_month_tt     .= $user->lang['rp_count_futureraids'].': '.$future_raid_count;
      $next_month_tt      = $khrml->HTMLTooltip($next_month_tt, 'rp_tt_lr');
      
      // The Tooltip for the past month
      $past_monthname     = $stime->MonthName($tmplastmonth, $tmpyear_l);
      $past_month_tt      = $user->lang['rp_last_month'].': '.$past_monthname['name'].'<br/>';
      $past_month_tt      = $khrml->HTMLTooltip($past_month_tt, 'rp_tt_lr');
      
      $output .= '<table width="100%" cellpadding="2" cellspacing="1">
      							<tr>';
      if($conf['rp_mode'] == 3){
        $output .= '<th colspan="7" class="RP-HeadInfo" align="center">'.$mnthnames['name'].'</th>';
      }else{
        $output .= '<td colspan="7">
                    <table width="100%" cellpadding="0" cellspacing="0" class="borderless">
                      <tr>
                        <th width="15%"><a href="listraids.php?month='.$tmplastmonth.'&year='.$tmpyear_l.'" '.$past_month_tt.'><img src="images/calendar/leftarrow.png" alt="" /></a></th>
                        <th width="70%" class="RP-HeadInfo" align="center">'.$mnthnames['name'].'</th>
                        <th width="15%"><a href="listraids.php?month='.$tmpnextmonth.'&year='.$tmpyear_n.'" '.$next_month_tt.'><img src="images/calendar/rightarrow.png" alt="" /></a></th>
                      </tr>
                    </table>
                    </td>';
      }
      $output .= '</tr><tr>';
      
      foreach($weekdays as $dday){
        $output .= 	'<th class="RP-days" width="14%" height="35px">'.$dday.'</th>';
      }
      $output .= '</tr><tr>';
              
      for ($i=1; $i != ($mnthnames['days']+1); $i++){
        // Modify the entries per event name
        $tmpbreakrow = '';
      	if($raiddataarray){
      		$tmpoutput = "";
          foreach($raiddataarray as $raid_values){
            $overlibwindow = "";
        		// reset all Status...
            $subscribedtrue 	= false;	// User is subscribed
            $signedouttrue 		= false;	// User is signed out
            $confirmedtrue		= false; 	// User is confirmed
            $notshuretrue			= false;	// User is not sure
    
            if($raid_values['raid_date'] == $stime->DoDate($conf['timeformats']['short'],gmmktime(0,0,0,$rpmonth,$i,$year), false)) {
            	if($tmpbreakrow == true){
            		$tmpoutput .= '<br />';
            	}
              // Temporary Output for  Status: (confirmed/signin/signout/away)      
              $tmpoutput .= ' <a class="RP-view" href="viewraid.php'.$SID.'&' . URI_RAID . '='.$raid_values['raid_id'].'" ';
              $raid_lead = (!empty($raid_values['raidleader'])) ? '<b>'.$user->lang['rp_leader'].'</b>: '.$raid_values['raidleader'].'<br/>' : '';
            	if($user->check_auth('a_raid_upd', false)){
            		$tmpoutput .= 'oncontextmenu="RightClickSettings(\''.$raid_values['raid_id'].'\');" ';
            	}
            	
            	// The Info Tooltip
              $ovclatt       = addslashes($raid_lead);
              if($raid_values['close']){
                $ovclatt      .= '<br/><font color="red"><b>'.$user->lang['rp_status_quit_sh'].'</b></font><br/>';
              }else{
                $ovclatt      .= '<b>'.$user->lang['rp_start_time'].'</b><br/>'.
                      					 $raid_values['raid_date'].', '.$raid_values['start_time'].'<br/>'.
                      					 '<br/><b>'.$user->lang['rp_finish_time'].'</b><br/>'.
                      					 $raid_values['raid_date_finish'].'<br/>';
                $ovclatt      .= ($raid_values['deadline']) ? '<br/><b>'.$user->lang['rp_signup_deadline'].'</b><br/>'.$raid_values['deadline'].'<br/>' : '';
              }
              $ovclatt      .= '<br/>'.$raid_values['raidcount_txt'].'<br/>';
              $ovclatt      .= (trim($raid_values['raid_note'])) ? '<br/><b>'.$user->lang['rp_multi_notes'].'</b><br/>'.$rpclass->nl2br2(addslashes($raid_values['raid_note'])) : '';
              
              $tmpoutput .= $khrml->HTMLTooltip($ovclatt, 'rp_tt_info').'>';
            	
    					if($raid_values['raid_icon'] && $conf['rp_cal_hico'] != 1){
    						// start performing this array
    						$tmpoutput .= $raid_values['raid_icon_large'].' ';
              	if($conf['rp_calhide_rname'] != 1){
              		$tmpoutput .= $raid_values['raid_name'];
              	}
            	}else{
              	$tmpoutput .= $raid_values['raid_name'];
              }
              $tmpoutput .= '</a>';
              $tmpbreakrow = true;
              
            	if($raid_values['calendar_status']){
                $tmpoutput .= ' '.$raid_values['confirmed_status'];
              }      
           } // End if Wert     
        } // End foreach
    	}// End if
               $iiii = sprintf("%02d", $i);   
              	if ($i==1){
              	if($conf['rp_cal_startday'] == 'monday'){
              		$dayplus_mon = 0;
              		$dayplus_tue = 1;
              		$dayplus_wed = 2;
              		$dayplus_thu = 3;
              		$dayplus_fri = 4;
              		$dayplus_sat = 5;
              		$dayplus_sun = 6;
              		$htmlcode_mon = '<td valign="top" class="calBox">';
              		$htmlcode_tue = '<td></td><td valign="top" class="calBox">';
              		$htmlcode_wed = '<td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_thu = '<td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_fri = '<td></td> <td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_sat = '<td></td> <td></td> <td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_sun = '<td></td><td></td><td></td><td></td><td></td><td></td><td valign="top" class="calBox">';
              	}else{
              		$dayplus_mon = 1;
              		$dayplus_tue = 2;
              		$dayplus_wed = 3;
              		$dayplus_thu = 4;
              		$dayplus_fri = 5;
              		$dayplus_sat = 6;
              		$dayplus_sun = 0;
              		$htmlcode_sun = '<td valign="top" class="calBox">';
              		$htmlcode_mon = '<td></td><td valign="top" class="calBox">';
              		$htmlcode_tue = '<td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_wed = '<td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_thu = '<td></td> <td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_fri = '<td></td> <td></td> <td></td> <td></td> <td></td> <td valign="top" class="calBox">';
              		$htmlcode_sat = '<td></td><td></td><td></td><td></td><td></td><td></td><td valign="top" class="calBox">';
              	}
                switch($stime->DoDate("%w",gmmktime(0,0,0,$rpmonth,$i,$year), false)){
                	 	case 0:	{ $output .= $htmlcode_sun; $dayplus = $dayplus_sun; break;    }
                    case 1:	{ $output .= $htmlcode_mon; $dayplus = $dayplus_mon; break;    }
                    case 2:	{ $output .= $htmlcode_tue; $dayplus = $dayplus_tue; break;    }
                    case 3:	{ $output .= $htmlcode_wed; $dayplus = $dayplus_wed; break;    }
                    case 4:	{ $output .= $htmlcode_thu; $dayplus = $dayplus_thu; break;    }
                    case 5:	{ $output .= $htmlcode_fri; $dayplus = $dayplus_fri; break;    }
                    case 6:	{ $output .= $htmlcode_sat; $dayplus = $dayplus_sat; break;    } 
            		}
        		}else{
        			if($iiii == $stime->DoDate("%d",$stime->DoTime()) && $rpmonth == $actualmonth) {
            			$output .= '<td valign="top" class="calBoxToday">';
        			}else{
            			$output .= '<td valign="top" class="calBox">';
            	}
        		}
        $tstamp         = gmmktime(12,0,0,$rpmonth,$i,$year);
        $dst            = date('I', $tstamp);
        $addlink_date   = gmmktime(12-$dst,0,0,$rpmonth,$i,$year);
        if ($iiii == $stime->DoDate("%d",$stime->DoTime()) && $rpmonth == $actualmonth) {
        	$addraidlink = ( $user->check_auth('a_raidplan_add', false) ) ? 'onclick="javascript:AddRaid(\''.$addlink_date.'\')"' : '';
          $output .= '<div class="calBoxDayLabel textHighlight large" '.$addraidlink.'>'.$i.'</div>';
        }else{
          $addraidlink = ( $user->check_auth('a_raidplan_add', false) ) ? 'onclick="javascript:AddRaid(\''.$addlink_date.'\')"' : '';
        	$output .= '<div class="calBoxDayLabel textLight large" '.$addraidlink.'>'.$i.'</div>';
        }
        $output .= $tmpoutput;
        // da rein       
                    
            $output .= '</td>';
            $rest = ($i+$dayplus)%7;
            if ($rest == 0){
            	$output .= '</tr>';
            	$output .= '<tr>';
    				}        
    	} // end of for (days)
    	$output .= '</tr> </table><br/>';
    } // many months loop
  }// end of calendar-enabled

// the Upgrade warning thing
// This feature is for all the stupid admins, which won't read
// upgrade steps in readmes!
$pluginlist	= array(
										'raidplan'	=> array(
              											'table'      => 'raidplan_config',
              											'fieldprefix'=> 'rp_',
              											'inclfolder' => 'includes'
											),
										'charmanager'	=> array(
              											'table'      => 'charmanager_config',
              											'fieldprefix'=> 'uc_',
              											'inclfolder' => 'includes'
											)
							);

// legend tooltip
$legend_tt  = '<b>'.$user->lang['rp_legend'].'</b><br/>'.
              '<img src="images/status/closed.png" /> '.$user->lang['rp_signup_over'].'<br/>'.
              '<img src="images/status/ok.png" /> '.$user->lang['rp_signup_possible'].'<br/>'.
              '<img src="images/status/24hours.png" /> '.$user->lang['rp_signup_24h'].'<br/><br/>'.
              '<img src="images/status/status1.gif" /> '.$user->lang['status1'].'<br/>'.
              '<img src="images/status/status0.gif" /> '.$user->lang['status0'].'<br/>'.
              '<img src="images/status/status2.gif" /> '.$user->lang['status2'].'<br/>'.
              '<img src="images/status/status3.gif" /> '.$user->lang['status3'].'<br/>'.
              '<img src="images/status/status5.gif" /> '.$user->lang['status4'];

// check if default member is set:
if($user->data['user_id'] != '-1'){
  $ssql = "SELECT member_id, role FROM ".RP_SAVEDUSER_TABLE." WHERE user_id='".$user->data['user_id']."'";
  $result = $db->query($ssql);
  $durow = $db->fetch_record($result);
}else{
  $durow = array();
}

// multisign dropdown
$multisign_dropdown = array(
          'status1'     => $user->lang['rp_signup'],
          'status2'     => $user->lang['rp_bunsign'],
          'status3'     => $user->lang['rp_not_sure'],
);

$close_array = array('RPAddRaid', 'EDITRaid');

// RSS Feed
if($pcache->CacheWritable()){  
  $rss->saveFeed("RSS2.0", $pcache->FilePath('rss.xml', 'raidplan'), false);
}

// XML out
if($_GET['xmlout'] == 'true'){
  readfile($pcache->FilePath('rss.xml', 'raidplan'));
  die();
}

$tpl->assign_vars(array(
		'CALENDAR' 				=> ($output) ? $output : '',
		'RP_ADMIN'				=> ( $user->check_auth('a_raid_upd', false)) ? true : false,
		'F_MULTISIGNIN'   => 'listraids.php',
		
		// JS Code
		'JS_CLOSE'        => $jquery->Dialog_close($close_array, false, false, true),
		'JS_ABOUT'        => $jquery->Dialog_URL('About', $user->lang['rp_about_header'], 'about.php', '380', '320'),
		'JS_ADDRAID'      => $jquery->Dialog_URL('RPAddRaid', $user->lang['rp_addraid'], "admin/addraid.php?tstamp='+timestamp+'&fe=1", '660', '440', 'listraids.php'),
		'JS_ADDRAID2'     => $jquery->Dialog_URL('RPAddRaid2', $user->lang['rp_addraid'], "admin/addraid.php?fe=1", '660', '440', 'listraids.php'),
    'JS_EDITRAID'     => $jquery->Dialog_URL('EDITRaid', $user->lang['rp_editraid'], "admin/addraid.php?s=&r='+raidid+'&fe=1", '660', '440', 'listraids.php'),
		
		//CSS
    'RP_TEMPLATEPATH' => $user->style['template_path'],
    'S_CSSGAME'       => $gameiconfolder,
    'VALUE_MEM_ID'    => $durow['member_id'],
    'VALUE_ROLE'      => $durow['role'],
    
    // RSS
    'RSS_FEED'        => ($pcache->CacheWritable())  ? '<link rel="alternate" type="application/rss+xml" title="'.$user->lang['rp_rss_title'].'" href="'.$rpclass->BuildLink().$pcache->FileLink('rss.xml', 'raidplan').'" />' : false,
		
		'DRDWN_MULTISIGN'	=> $khrml->DropDown('rp_multisign', $multisign_dropdown, 'status1', '', '', 'input'),
		'LEGEND_TT' 	    => $khrml->HTMLTooltip($legend_tt, 'rp_tt_ruler'),
		'SHOW_MULTISIGN'  => is_numeric($durow['member_id']),
		'HELP_MULTISIGN'  => $khrml->HTMLTooltip($user->lang['rp_help_multisignin'], 'rp_tt_info'),
		'HELP_MULTINOTE'  => $khrml->HTMLTooltip($user->lang['rp_help_multinote'], 'rp_tt_info'),
		
		'B_PERFORM'       => $user->lang['rp_multi_perform'],
		'L_MULTINOTE'     => $user->lang['rp_multi_notes'],
		'L_LEGEND'        => $user->lang['rp_legend'],
    'L_DATE' 					=> $user->lang['date'],
    'L_INVITE' 				=> $user->lang['rp_invite'],
    'L_START' 				=> $user->lang['rp_start'],
    'L_END'           => $user->lang['rp_end'],
    'L_DAY' 					=> $user->lang['rp_day'],
    'L_NAME' 					=> $user->lang['name'],
    'L_NOTE' 					=> $user->lang['note'],
    'L_VALUE' 				=> $user->lang['value'],
    'L_STATUS' 				=> $user->lang['rp_status'],

    'L_CURRENT_RAID' 	=> $user->lang['rp_current_raid'],
    'L_RECENT_RAID' 	=> $user->lang['rp_recent_raid'],
    'L_ABOUT_HEADER'	=> $user->lang['rp_about_header'],
    'L_EDITRAID'			=> $user->lang['rp_editraid'],
    'L_ADDRAID'				=> $user->lang['rp_addraid'],
    'L_COPYRIGHT'     => $rpclass->Copyright(),
    
    'O_DATE' 					=> $current_order['uri'][0],
    'O_NAME' 					=> $current_order['uri'][1],
    'O_NOTE' 					=> $current_order['uri'][2],
    'O_VALUE' 				=> $current_order['uri'][3],
    
    'OPT_CALENDAR'		=> ( $conf['rp_mode'] == 1 || $conf['rp_mode'] == 2 || $conf['rp_mode'] == 3 || $conf['rp_mode'] == 4) ? true : false,
    'OPT_CLASSIC'			=> ( $conf['rp_mode'] == 0 || $conf['rp_mode'] == 2 || $conf['rp_mode'] == 4) ? true : false,
    'OPT_CAL_BEYOND'	=> ( $conf['rp_cal_ab'] == 1 ) ? true : false,
		'OPT_CAL_ABOVE'		=> ( !$conf['rp_cal_ab'] || $conf['rp_cal_ab'] == 0 ) ? true : false,
		
    'U_LIST_RAIDS' 		=> 'listraids.php'.$SID.'&amp;',
    
    'START' 					=> $start,
    'LISTRAIDS_FOOTCOUNT' => sprintf($user->lang['listraids_footcount'], $total_raids, $user->data['user_rlimit']),
    'LISTRECENTRAIDS_FOOTCOUNT' => sprintf($user->lang['rp_listrecentraids_footcount'], $total_recent_raids, $stime->DoDate('%d',($stime->DoTime()-$lastraidtime-86400))),
    'RAID_PAGINATION' => generate_pagination('listraids.php'.$SID.'&amp;o='.$current_order['uri']['current'], $total_raids, $user->data['user_rlimit'], $start))
);

if ($user->check_auth('u_raidplan_add', false)) {
	$tpl->assign_vars(array(
		'L_ADD_RAID' 			=> $user->lang['add_raid'],
		'F_ADD_RAID' 			=> "editraid.php",
		'S_ADD_RAID' 			=> true));
}

$eqdkp->set_vars(array(
	'page_title'    		=> $rpclass->GeneratePageTitle($user->lang['rp_title_listraids']),
	'template_file' 		=> 'listraids.html',
	'template_path' 		=> $pm->get_data('raidplan', 'template_path'),
	'display'       		=> true)
);
?>