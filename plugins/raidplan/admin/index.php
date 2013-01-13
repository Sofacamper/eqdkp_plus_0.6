<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2010-04-22 19:04:53 +0200 (Thu, 22 Apr 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 7637 $
 * 
 * $Id: index.php 7637 2010-04-22 17:04:53Z wallenium $
 */
 
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('a_raidplan_');

// Get the plugin
$raidplan = $pm->get_plugin('raidplan');

$sort_order = array(
    0 => array('raid_date desc', 'raid_date'),
    1 => array('raid_name', 'raid_name desc'),
    2 => array('raid_note', 'raid_note desc'),
    3 => array('raid_value desc', 'raid_value')
);
 
$current_order = switch_order($sort_order);
$raid_date = ($_GET['showall']) ? 0 : $stime->DoTime();
$total_raids = $db->query_first('SELECT count(*) FROM ' . RP_RAIDS_TABLE . ' WHERE raid_date>' . $raid_date);
$start = ( isset($_GET['start']) ) ? htmlspecialchars(strip_tags($_GET['start']), ENT_QUOTES) : 0;

$sql = 'SELECT raid_id, raid_name, raid_date, raid_note, raid_value, raid_repeat 
        FROM ' . RP_RAIDS_TABLE . '
		    WHERE raid_date >' . $raid_date . '
        ORDER BY '.$current_order['sql']. '
        LIMIT '.$start.','.$user->data['user_rlimit'];

  if (!($raids_result = $db->query($sql))) { message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql); }

  while ( $row = $db->fetch_record($raids_result) ){
    $raidicon = $rpclass->generateRaidIcon($row['raid_name']);
    $tpl->assign_block_vars('raids_row', array(
        'ROW_CLASS'     => $eqdkp->switch_row_class(),
        'RAID_ICON'			=> ($raidicon['full']) ? '<img src="'.$raidicon['full'].'" alt="" width="16px" height="16px"/>' : '',
        'REPEATABLE'    => ($row['raid_repeat'])? '<img src="../images/viewraid/repeatable.png" title="'.$user->lang['rp_is_repeatable'].'" alt="'.$user->lang['rp_is_repeatable'].'"/>' : '',
        'DATE'          => ( !empty($row['raid_date']) ) ? $stime->DoDate($conf['timeformats']['medium'], $row['raid_date']) : '&nbsp;',
        'U_VIEW_RAID'   => 'addraid.php'.$SID.'&amp;' . URI_RAID . '='.$row['raid_id'],
        'RAID_ID'		    => $row['raid_id'],
        'NAME'          => ( !empty($row['raid_name']) ) ? stripslashes($row['raid_name']) : '&lt;<i>Not Found</i>&gt;',
        'NOTE'          => ( !empty($row['raid_note']) ) ? stripslashes($row['raid_note']) : '&nbsp;',
        'VALUE'         => ( !empty($row['raid_value']) ) ? stripslashes($row['raid_value']) : '-1.00',
		    'EDIT'          => ( $user->check_auth('u_raidplan_update', false) ) ? '(<a href="editraid.php'.$SID.'&amp;' . URI_RAID . '='.$row['raid_id'] . '">' . $user->lang['edit_raidplan'] . '</a>)' : '',
	 ));
  }
  $db->free_result($raids_result);
  
  // recent raids
  $sql = 'SELECT raid_id, raid_name, raid_date, raid_note, raid_value, raid_repeat 
        FROM ' . RP_RAIDS_TABLE . '
		    WHERE raid_date <' . $raid_date . '
        ORDER BY '.$current_order['sql']. '
        LIMIT '.$start.','.$user->data['user_rlimit'];

  if (!($raids_result = $db->query($sql))) { message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql); }

  while ( $row = $db->fetch_record($raids_result) ){
  	$raidicon = $rpclass->generateRaidIcon($row['raid_name']);
  		
    $tpl->assign_block_vars('recent_raids_row', array(
        'ROW_CLASS'     => $eqdkp->switch_row_class(),
        'RAID_ICON'			=> ($raidicon['full']) ? '<img src="'.$raidicon['full'].'" alt="" width="16px" height="16px"/>' : '',
        'REPEATABLE'    => ($row['raid_repeat'])? '<img src="../images/viewraid/repeatable.png" title="'.$user->lang['rp_is_repeatable'].'" alt="'.$user->lang['rp_is_repeatable'].'"/>' : '',
        'DATE'          => ( !empty($row['raid_date']) ) ? $stime->DoDate($conf['timeformats']['medium'], $row['raid_date']) : '&nbsp;',
        'U_VIEW_RAID'   => 'addraid.php'.$SID.'&amp;' . URI_RAID . '='.$row['raid_id'],
        'RAID_ID'		    => $row['raid_id'],
        'NAME'          => ( !empty($row['raid_name']) ) ? stripslashes($row['raid_name']) : '&lt;<i>Not Found</i>&gt;',
        'NOTE'          => ( !empty($row['raid_note']) ) ? stripslashes($row['raid_note']) : '&nbsp;',
        'VALUE'         => ( !empty($row['raid_value']) ) ? stripslashes($row['raid_value']) : '-1.00',
		    //'EDIT'          => ( $user->check_auth('u_raidplan_update', false) ) ? '(<a href="editraid.php'.$SID.'&amp;' . URI_RAID . '='.$row['raid_id'] . '">' . $user->lang['edit_raidplan'] . '</a>)' : '',
	 ));
  }
  $db->free_result($raids_result);

$tpl->assign_vars(array(
		'EQDKP_ROOT_PATH'			=> $eqdkp_root_path,
		// JS Code
		'JS_ADDRAID'          => $jquery->Dialog_URL('RPAddRaid', $user->lang['rp_addraid'], 'addraid.php', '660', '400'),
		'JS_EDITRAID'         => $jquery->Dialog_URL('EDITRaid', $user->lang['rp_editraid'], "addraid.php?s=&r='+raidid+'", '660', '400'),
		'JS_CLEANUP'          => $jquery->Dialog_URL('RPCleanUp', $user->lang['rp_cleanraids'], "cleanup.php", '500', '200'),
		'JS_TRANSFORMRAID'    => $jquery->Dialog_URL('RPTransRaid', $user->lang['rp_transform'], "transform.php?r='+raidid+'", '900', '500', 'index.php'),
		
    'L_DATE'							=> $user->lang['date'],
    'L_NAME'							=> $user->lang['name'],
    'L_NOTE'							=> $user->lang['note'],
    'L_VALUE'							=> $user->lang['value'],
    'L_ADDRAID'						=> $user->lang['rp_addraid'],
    'L_CLEANRAIDS'				=> $user->lang['rp_cleanraids'],
    'L_RECENT_RAIDS'      => $user->lang['rp_recent_raid'],
    'L_TRANSFORM'         => $user->lang['rp_transform'],
    'L_EDIT'              => $user->lang['rp_editraid'],
    
    'O_DATE'							=> $current_order['uri'][0],
    'O_NAME'							=> $current_order['uri'][1],
    'O_NOTE'							=> $current_order['uri'][2],
    'O_VALUE'							=> $current_order['uri'][3],
    
    'U_LIST_RAIDS' 				=> 'index.php'.$SID.'&amp;',
    
    'L_VERSION'						=> $pm->get_data('raidplan', 'version'),
    'L_YEAR'							=> $stime->DoDate("%Y", $stime->DoTime()),
    
    'START' 							=> $start,
    'LISTRAIDS_FOOTCOUNT' => sprintf($user->lang['rp_listraids_footcount'], $total_raids, $user->data['user_rlimit'],
		'<a href="index.php'.$SID.'&amp;showall=true">'),
    'RAID_PAGINATION' 		=> generate_pagination('listraids.php'.$SID.'&amp;o='.$current_order['uri']['current'], $total_raids, $user->data['user_rlimit'], $start))
);

$eqdkp->set_vars(array(
	'page_title'    => $rpclass->GeneratePageTitle($user->lang['rp_manage']),
	'template_file' => 'admin/listraids.html',
	'template_path' => $pm->get_data('raidplan', 'template_path'),
	'display'       => true)
);
?>
