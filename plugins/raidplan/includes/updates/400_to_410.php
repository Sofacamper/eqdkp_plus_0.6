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
 * $Id: 400_to_410.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '4.1.0';
$updateFunction = 'RP400to410Update';

$updateDESC = array(
	'',
	'Remove old Permissions ID',
  'Added Permissions for LogManager',
  'Drop old Role Table if exists',
  'Added Roles Table',
);

$updateSQL = array(
  "DELETE FROM `" . $table_prefix . "auth_options` WHERE auth_id='509'",
  "INSERT INTO `" . $table_prefix . "auth_options` VALUES (509, 'a_raidplan_logs', 'N');",
  "DROP TABLE IF EXISTS `" . $table_prefix . "roles`",
  "CREATE TABLE `" . $table_prefix . "roles` (
  `role_id` mediumint(8) unsigned NOT NULL auto_increment,
  `role_name` varchar(50) NOT NULL,
  `role_classes` varchar(255) default NULL,
  `role_image` varchar(255) default NULL,
  PRIMARY KEY (`role_id`)
)TYPE=InnoDB;",
);

$savearray = array(
  'rp_us_layout'    => '1',     # Usersettings Layout
	'rp_us_time'      => '1',     # Usersettings Time
);

$wpfcdb->UpdateConfig($savearray, $wpfcdb->CheckDBFields('config_name'));

function RP400to410Update(){
  global $db, $table_prefix, $user, $eqdkp_root_path, $rpclass;
  
  $tablearray = array(
    $table_prefix.'raidplan_raid_classes'   => 'class_name',
    $table_prefix.'raidplan_saveduser'      => 'role',
  );
  
  // Update the old roles:
  foreach($tablearray as $mytable=>$myfield){
    $db->query("UPDATE ".$mytable." SET ".$myfield."='melee'  WHERE ".$myfield."='Melee';");
    $db->query("UPDATE ".$mytable." SET ".$myfield."='range'  WHERE ".$myfield."='Range';");
    $db->query("UPDATE ".$mytable." SET ".$myfield."='tank'   WHERE ".$myfield."='Tank';");
    $db->query("UPDATE ".$mytable." SET ".$myfield."='healer' WHERE ".$myfield."='Healer';");
  }
  
  // Add the new roles into the table..
  include($eqdkp_root_path.'plugins/raidplan/games/'.$rpclass->SelectedGame().'/init_vars.php');

  foreach($rproleVariables as $roleid=>$rolevalue){
    $db->query("INSERT INTO `" . $table_prefix . "roles` (`role_name`, `role_image`, `role_classes`) VALUES ('".$rolevalue['name']."', '".$rolevalue['image']."', '".$rolevalue['classes']."');");
  }
}

?>
