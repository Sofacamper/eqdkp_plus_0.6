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
 * $Id: 203_to_210.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$new_version    = '2.1.0';
$updateFunction = false;

$updateDESC = array(
	'',
	'Added Raidleader to raid_attendees table',
	'Added some additional fields to the raid table',
	'Added new table for templates if not exists',
	'Added new table for repeating raids not exists',
	'Added some fields to raid template table',
	'Changed raid table for repeating raids',
	'Changed Settings-entry',
	'Changed Settings-entry',
	'Changed Settings-entry',
	'Changed Settings-entry',
	'Added raid link and end time field to raids table',
	'Added raid-added-by field in raid table',
	'Added new Fields to template Table',
);

$updateSQL = array(
"ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` ADD `attendees_change_time` INT( 11 ) NOT NULL DEFAULT '0';",
"ALTER TABLE `" . $table_prefix . "raidplan_raids` 
ADD `raid_leader` VARCHAR( 255 ) NOT NULL,
ADD `raid_date_change` INT( 11 ) NOT NULL DEFAULT '0';",
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_raidtemplate (
						template_id mediumint(8) unsigned NOT NULL auto_increment,
						template_name varchar(255) default NULL,
						raid_name varchar(255) default NULL,
						raid_note text default NULL,
						raid_leader varchar(255) default NULL,
						raid_value float(6,2) default NULL,
						raid_attendees mediumint(8) NOT NULL default '0',
						PRIMARY KEY  (template_id)
						)TYPE=InnoDB;",
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_repeat (
						repeat_id mediumint(8) unsigned NOT NULL auto_increment,
						raid_id varchar(255) default NULL,
						next_date int(11) NOT NULL default '0',
						job_date int(11) NOT NULL default '0',
						delete_id varchar(255) default NULL,
						PRIMARY KEY  (repeat_id)
						)TYPE=InnoDB;",
"ALTER TABLE `" . $table_prefix . "raidplan_raidtemplate` 
ADD `raid_date` int(11) NOT NULL default '0',
ADD	`raid_date_invite` int(11) NOT NULL default '0',
ADD	`raid_date_subscription` int(11) NOT NULL default '0';",
"ALTER TABLE `" . $table_prefix . "raidplan_raids`
ADD `raid_repeat` varchar(255) NOT NULL default '0',
ADD `delete_id` varchar(255) default NULL;",
"UPDATE `" . $table_prefix . "raidplan_config` SET `config_value` = './tmp/' WHERE `config_name` = 'rp_auto_path' LIMIT 1;",
"UPDATE `" . $table_prefix . "raidplan_config` SET `config_value` = '40' WHERE `config_name` = 'rp_rep_value' LIMIT 1;",
"UPDATE `" . $table_prefix . "raidplan_config` SET `config_value` = '30' WHERE `config_name` = 'rp_end_min_offset' LIMIT 1;",
"UPDATE `" . $table_prefix . "raidplan_config` SET `config_value` = '1' WHERE `config_name` = 'rp_hours_offset' LIMIT 1;",
"ALTER TABLE `" . $table_prefix . "raidplan_raids` 
ADD `raid_link` text DEFAULT NULL,
ADD	`raid_date_finish` int(11) NOT NULL default '0'",
"ALTER TABLE `" . $table_prefix . "raidplan_raids` 
ADD `raid_date_added` INT( 11 ) NOT NULL DEFAULT '0';",
"ALTER TABLE `" . $table_prefix . "raidplan_raidtemplate` 
ADD `raid_date_finish` int(11) NOT NULL default '0',
ADD `raid_link` text DEFAULT NULL;",
);

?>
