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
 * $Id: collate_check.php 2963 2008-11-03 12:24:11Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');
include_once('../includes/collate.class.php');

  // Check if plugin is installed
  if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }
  $raidplan = $pm->get_plugin(PLUGIN);
  $user->check_auth('a_raidplan_update');
  
  $ccheck = new CollateCheck();
  
  $collchecktable = array( 	 
	                 'eqdkp_raidplan_raid_attendees', 	 
	                 'eqdkp_members', 	 
	                 'eqdkp_member_user', 	 
	                 'eqdkp_classes', 	 
	                 'eqdkp_users', 	 
	                 'eqdkp_member_ranks', 	 
	                 'eqdkp_raidplan_wildcards', 	 
	                 'eqdkp_raidplan_member_groups' 	 
	                 );
  
  // Which tables should be checked?
  $istrue = ($_POST['db_collation']) ? $_POST['db_collation'] : false;
  echo $ccheck->CheckCollate($collchecktable, true, $istrue);
?>
