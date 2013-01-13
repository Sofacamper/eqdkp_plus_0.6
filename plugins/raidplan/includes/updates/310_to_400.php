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
 * $Id: 310_to_400.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '4.0.0';
$updateFunction = false;

$updateDESC = array(
	'',
  'Added Role field to attendees table',
  'Added Distribution field to raid table',
  'Truncate saved user table',
  'Added Role field to saved user table',
  'Added Distribution field to template table',
  'Add template ID to distribution table',
  'Drop old confirmed db field',
  'Drop old confirmed db field in raidtemplate table',
  'Drop old raid info db field in raids table',
  'Drop old format Raid leader Field Part 1',
  'Drop old format Raid leader Field Part 2',
  'Add new format Raid leader Field Part 1',
  'Add new format Raid leader Field Part 2',
  'Truncate Raid Templates, the old ones are not compatible with RP4',
);

$updateSQL = array(
  "ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` ADD `role` VARCHAR( 255 ) NULL AFTER `member_id`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raids` ADD `raid_distribution` enum('0','1','2') NOT NULL default '0';",
  "TRUNCATE TABLE `" . $table_prefix . "raidplan_saveduser`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_saveduser` ADD `role` VARCHAR( 255 ) NULL;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raidtemplate` ADD `raid_distribution` enum('0','1','2') NOT NULL default '0';",
  "ALTER TABLE `" . $table_prefix . "raidplan_classes` ADD template_id varchar(255) NOT NULL default '0';",
  "ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` DROP `confirmed`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raid_raidtemplate` DROP `raid_info`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raid_raids` DROP `raid_info`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raids` DROP `raid_leader`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raidtemplate` DROP `raid_leader`;",
  "ALTER TABLE `" . $table_prefix . "raidplan_raids` ADD `raid_leader` int(11) NOT NULL default '0';",
  "ALTER TABLE `" . $table_prefix . "raidplan_raidtemplate` ADD `raid_leader` int(11) NOT NULL default '0';",
  
);
$savearray = array(
  'rp_ghidenotes'      => '1',     # Hide game notes for guests
  'rp_activeonly_sn'   => '1',     # E-Mails to active Users only
	'rp_mode'            => '2',     # Calendar + classic mode enabled
	'rp_comments'        => '1',     # Comment system enabled
	'color_status0'      => 'lime',  # Color Status 0
	'color_status1'      => 'yellow',# Color Status 1
	'color_status2'      => 'red',   # Color Status 2
	'color_status3'      => 'purple',# Color Status 3
);

include($eqdkp_root_path.'plugins/raidplan/games/'.$rpclass->SelectedGame().'/init_vars.php');
if(is_array($rpclassColorsCSS)){
  $savearray = array_merge($savearray, $rpclassColorsCSS);
}

$wpfcdb->UpdateConfig($savearray, $wpfcdb->CheckDBFields('config_name'));
?>
