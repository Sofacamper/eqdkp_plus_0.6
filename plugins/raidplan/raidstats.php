<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2011-07-10 14:29:06 +0200 (Sun, 10 Jul 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 10591 $
 * 
 * $Id: raidstats.php 10591 2011-07-10 12:29:06Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../';
include_once('includes/common.php');

$user->check_auth('u_raidplan_statistic');

if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }
$raidplan = $pm->get_plugin('raidplan');

$sort_order = array(
    0 => array('member_name', 'member_name desc'),
    1 => array('member_class', 'member_class desc'),
    2 => array('member_current desc', 'member_current'),
    3 => array('member_lastraid desc', 'member_lastraid'),
);

// set the possible times:
$daysvalarray = array(
  30  => '30',
  45  => '45',
  60  => '60',
  90  => '90',
  120  => '120',
);


$current_order    = switch_order($sort_order);  
$member_count     = 0;

// generate periods
$days_value       = ($_GET['days']) ? $_GET['days'] : 30;
$variable_days    = gmmktime(0, 0, 0, date('m'), date('d')-$days_value, date('Y'));

// get the general counts
$sql_count        = 'SELECT raid_id, raid_date FROM '.RAIDS_TABLE.';';
$sql_count2       = 'SELECT raid_id, raid_date FROM '.RP_RAIDS_TABLE.';';
$count_result     = $db->query($sql_count);
$count2_result    = $db->query($sql_count2);
while( $counter_row = $db->fetch_record($count_result) ){
  $counters[$counter_row['raid_id']] = $counter_row['raid_date'];
}
$db->free_result($count_result);

while( $counter2_row = $db->fetch_record($count2_result) ){
  $counters_rp[$counter2_row['raid_id']] = $counter2_row['raid_date'];
}
$db->free_result($count2_result);

// calculate the raid counts
$raid_count             = count($counters);    
$raid_count_variable    = $rpclass->countBetweenDates($counters, $variable_days, $stime->DoTime());
$rpraid_count_variable  = $rpclass->countBetweenDates($counters_rp, $variable_days, $stime->DoTime());


$tpl->assign_vars(array(
    'ROW_CLASS'         => $eqdkp->switch_row_class(),
    'TOTAL_RAIDS'       => sprintf(intval($raid_count_variable)),
    'TOTAL_SCHED'       => sprintf(intval($rpraid_count_variable)),
    )
);
    
// Build SQL query based on GET options
$sql = 'SELECT m.*, (m.member_earned-m.member_spent+m.member_adjustment) AS member_current, 
    member_status, r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix, 
    c.class_name AS member_class
    FROM ' . MEMBERS_TABLE . ' m, ' . MEMBER_RANKS_TABLE . ' r, ' . CLASS_TABLE . ' c
	  WHERE c.class_id = m.member_class_id
    AND (m.member_rank_id = r.rank_id)';
$sql .= ' ORDER BY '.$current_order['sql'];

if ( !($members_result = $db->query($sql)) ) {
    message_die('Could not obtain member information', '', __FILE__, __LINE__, $sql);
}

// build the member count array
$memc_sql     = 'SELECT ra.member_name, ra.raid_id, r.raid_date
                 FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra
                 WHERE ra.raid_id = r.raid_id;";
$memc_result  = $db->query($memc_sql);
while( $memc_row = $db->fetch_record($memc_result) ){
  $memcount[$memc_row['member_name']][$memc_row['raid_id']] = $memc_row['raid_date'];
}
$db->free_result($memc_result);

$memcrp_sql   = 'SELECT rra.raid_id, rra.member_id, rr.raid_date
                 FROM ' . RP_ATTENDEES_TABLE . ' rra, ' . RP_RAIDS_TABLE . " rr
                 WHERE rra.raid_id = rr.raid_id
                 AND (rra.attendees_subscribed = 0 OR rra.attendees_subscribed = 1);";
$memcrp_result  = $db->query($memcrp_sql);
while( $memcrp_row = $db->fetch_record($memcrp_result) ){
  $memrpcount[$memcrp_row['member_id']][$memcrp_row['raid_id']] = $memcrp_row['raid_date'];
}
$db->free_result($memcrp_result);

$memcrp2_sql   = 'SELECT rra.raid_id, rra.member_id, rr.raid_date
                 FROM ' . RP_ATTENDEES_TABLE . ' rra, ' . RP_RAIDS_TABLE . " rr
                 WHERE rra.raid_id = rr.raid_id
                 AND rra.attendees_subscribed = 2;";
$memcrp2_result  = $db->query($memcrp2_sql);
while( $memcrp2_row = $db->fetch_record($memcrp2_result) ){
  $memrpoffcount[$memcrp2_row['member_id']][$memcrp2_row['raid_id']] = $memcrp2_row['raid_date'];
}
$db->free_result($memcrp2_result);

$rc_sql         = 'SELECT ra.member_name, ra.raid_id, r.raid_date
                   FROM '.RAIDS_TABLE.' r, '.RAID_ATTENDEES_TABLE.' ra
                   WHERE ra.raid_id = r.raid_id;';

while ( $row = $db->fetch_record($members_result) ) {
    $individual_raid_count = $individual_signup_count = 0;
    $total_since_firstraid = 0;
    
    // calculate the needed Data!
    $individual_raid_count        = $rpclass->countBetweenDates($memcount[$row['member_name']], $variable_days, $stime->DoTime());
    $individual_signup_count      = $rpclass->countBetweenDates($memrpcount[$row['member_id']], $variable_days, $stime->DoTime());
    $individual_signoff_count     = $rpclass->countBetweenDates($memrpoffcount[$row['member_id']], $variable_days, $stime->DoTime());
    $raids_since_firstraid        = $rpclass->countBetweenDates($memcount[$row['member_name']], $row['member_firstraid'], $stime->DoTime());
    $total_since_firstraid        = $rpclass->countBetweenDates($memcount, $row['member_firstraid'], $stime->DoTime());
    
    // generate the Output
    $percent_of_raids   = ( $raid_count_variable > 0 )    ? round(($individual_raid_count / $raid_count_variable) * 100)      : 0;
    $percent_of_signup  = ( $rpraid_count_variable > 0 )  ? round(($individual_signup_count / $rpraid_count_variable) * 100)  : 0;      

    $percent_since_firstraid = ( $total_since_firstraid > 0 && $raids_since_firstraid > 0 ) ? round(($raids_since_firstraid / $total_since_firstraid) * 100) : 0;
        
    $member_count++;
    $tpl->assign_block_vars('members_row', array(
            'ROW_CLASS'               => $eqdkp->switch_row_class(),
            'ID'                      => $row['member_id'],
            'COUNT'                   => $member_count,
            'NAME'                    => $row['rank_prefix'] . (( $row['member_status'] == '0' ) ? '<i>' . $row['member_name'] . '</i>' : $row['member_name']) . $row['rank_suffix'],
            'CLASS'                   => ( !empty($row['member_class']) ) ? $row['member_class'] : '&nbsp;',
            'CLASS_EN'                => ($row['member_class']) ? strtolower($rpconvert->classname($row['member_class'])) : '',
            'CLASS_ICON'              => ($row['member_class']) ? $rpclass->ClassIcon($rpconvert->classname($row['member_class']), $row['member_class_id'], false) : '',
            'CURRENT'                 => $row['member_current'],
            'FIRSTRAID'               => ( !empty($row['member_firstraid']) ) ? $stime->DoDate($conf['timeformats']['short'], $row['member_firstraid']) : '&nbsp;',
            'RAIDS_FIRSTRAID'         => sprintf($raids_since_firstraid),
            'TOTAL_FIRSTRAID'         => sprintf($total_since_firstraid),
            'LASTRAID'                => ( !empty($row['member_lastraid']) ) ? $stime->DoDate($conf['timeformats']['short'], $row['member_lastraid']) : '&nbsp;',
            'RAIDS_COUNT'             => sprintf($individual_raid_count),
            'C_RAIDS_COUNT'           => color_item($individual_raid_count, true),
            'SIGNUP_COUNT'            => sprintf($individual_signup_count),
            'C_SIGNUP_COUNT'          => color_item($individual_signup_count, true),
            'SIGNOFF_COUNT'           => sprintf($individual_signoff_count),
            'C_SIGNOFF_COUNT'         => color_item($individual_signoff_count, true),
            'PERC_RAIDS_COUNT'        => sprintf($user->lang['rp_percent'], $percent_of_raids),
            'C_PERC_RAIDS_COUNT'      => color_item($percent_of_raids, true),
            'PERC_SIGNUP_COUNT'       => sprintf($user->lang['rp_percent'], $percent_of_signup),
            'C_PERC_SIGNUP_COUNT'     => color_item($percent_of_signup, true),
            'PERC_RAIDCOUNT'          => sprintf($user->lang['rp_percent'], $percent_since_firstraid),
            'C_ADJUSTMENT'            => color_item($row['member_adjustment']),
            'C_CURRENT'               => color_item($row['member_current']),
            'C_PERC_RAIDCOUNT'        => color_item($percent_since_firstraid, true),
            'C_RAIDS_FIRSTRAID'       => color_item($raids_since_firstraid, true),
            'C_TOTAL_FIRSTRAID'       => color_item($total_since_firstraid, true), 
            'U_VIEW_MEMBER'           => $eqdkp_root_path.'viewmember.php' . $SID . '&amp;' . URI_NAME . '='.$row['member_name'])
      );
}
$db->free_result($members_result);

$tpl->assign_vars(array(
    'F_MEMBERS'           => 'raidstats.php'.$SID,
    'U_LIST_MEMBERS'      => 'raidstats.php' . $SID . '&amp;',
    'C_NEUTRAL'           => 'neutral',
    'RP_TEMPLATEPATH'     => $user->style['template_path'],
    
    'L_STATS'             => $user->lang['rp_stats'],
    'L_NAME'              => $user->lang['name'],
    'L_CLASS'             => $user->lang['class'],
    'L_CURRENT'           => $user->lang['current'],
    'L_LASTRAID'          => $user->lang['lastraid'],
    'L_FIRSTRAID'         => $user->lang['rp_firstraid'],
    'L_TOTAL'             => $user->lang['rp_total'],
    'L_ATTENDED'          => $user->lang['rp_attended'],
    'L_SIGNED_OFF'        => $user->lang['rp_signed_off'],
    'L_SIGNED_IN'         => $user->lang['rp_signed_in'],
    'L_SINCE_FIRST'       => $user->lang['rp_sincefirst'],
    'L_DAYS'              => $user->lang['rp_days'],
    
    'L_TOTAL_RUN'         => sprintf($user->lang['rp_total_run'], $days_value),
    'L_TOTAL_RAID'        => sprintf($user->lang['rp_total_raids'], $days_value),
    'L_LAST_X_DAYS'       => sprintf($user->lang['rp_last_days'], $days_value),
    
    'DR_DAYS'             => $khrml->DropDown('days', $daysvalarray, $days_value, '', 'onchange="javascript:form.submit();"', 'input'),
    
    'O_NAME'              => $current_order['uri'][0],
    'O_CLASS'             => $current_order['uri'][1],
    'O_CURRENT'           => $current_order['uri'][2],
    'O_LASTRAID'          => $current_order['uri'][3],
    
    // JS Code
	  'JS_ABOUT'            => $jquery->Dialog_URL('About', $user->lang['rp_about_header'], 'about.php', '380', '320'),
    
    // Footer
    'L_CREDITS'           => $user->lang['rp_credits'],
    'L_COPYRIGHT'         => $rpclass->Copyright(),
    'RS_FOOTCOUNT'        => sprintf($user->lang['listmembers_footcount'], $member_count)
    )
);

$eqdkp->set_vars(array(
    'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['rp_raidplaner'].' - '.$user->lang['rp_title_statistic'],
    'template_file' => 'raidstats.html', 
    'template_path' => $pm->get_data('raidplan', 'template_path'),
    'display'       => true)
);
?>
