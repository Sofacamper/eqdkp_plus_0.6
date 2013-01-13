<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: action.php 2963 2008-11-03 12:24:11Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }
$raidplan = $pm->get_plugin(PLUGIN);
$rpclass->AdminAccess(true);


// if no raid ID provided, return false
if(!$_GET['r']){
  message_die('No raid ID');
}

if($_GET['group']){
  $rpclass->ToggleMemberGroup($_GET['group'], $_GET['r'], $_GET['name']);
}elseif($_GET['guestid']){
  $rpclass->DeleteGuest($_GET['guestid'], $_GET['r']);
}elseif($_GET['old_memid'] && $_GET['new_memid']){
  $rpclass->ToggleTwinks($_GET['r'], $_GET['new_memid'], $_GET['old_memid'], $_GET['role']);
}else{
  $rpclass->ToggleMemberStatus($_GET['member_id'], $_GET['r'], $_GET['mode'], $_GET['status'], $_GET['role']);
}

// back to viewraid
if(!$_GET['transform']){
  redirect('plugins/'.PLUGIN.'/viewraid.php' . $SID . '&r=' . $_GET['r']);
}
?>
