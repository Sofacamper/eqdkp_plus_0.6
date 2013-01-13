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
 * $Id: 210_to_300.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$new_version    = '3.0.0';
$updateFunction = false;

if($eqdkp->config['default_game'] == 'Everquest2' || $eqdkp->config['default_game'] == 'Everquest'){
  $eqsql = "INSERT INTO " . $table_prefix . "raidplan_config VALUES ('rp_enable_classbrk', '1');";
}else{
  $eqsql = "INSERT INTO " . $table_prefix . "raidplan_config VALUES ('rp_enable_classbrk', '0');";
}

// the colate fix:
$ccheck = new CollateCheck();
$ccheck->CheckCollate($pm->plugins['raidplan']->collchecktable, false, true);

// Update Description
$updateDESC = array(
	'',
  'Added new User permission for statistics',
  'Added new Admin permission for wildcards',
  'Added Group field to raid attendees table',
  'Dropped wildcards Table',
  'Add new wildcards Table',
  'Added config value for Class-Linebreak (enable if Eq or EQ2)',
  'Add Member-Group save table',
  'Add Raidplan Guest Table',
  'Added note & User Changed additions',
  'Added User Settings Table',
  'Add Event to group table',
);

// Update SQL
$updateSQL = array(
"INSERT INTO `" . $table_prefix . "auth_options` ( `auth_id` , `auth_value` , `auth_default` ) VALUES ('507', 'u_raidplan_statistic', 'N');",
"INSERT INTO `" . $table_prefix . "auth_options` ( `auth_id` , `auth_value` , `auth_default` ) VALUES ('508', 'a_raidplan_wildcards', 'N');",
"ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` ADD `group` varchar(255) default NULL;",
"DROP TABLE IF EXISTS " . $table_prefix . "raidplan_wildcards",
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_wildcards (
						wildcard_id mediumint(8) unsigned NOT NULL auto_increment,
            member_name varchar(25) default NULL,
            member_id mediumint(5) NOT NULL default '0',
            event varchar(255) default NULL,
            date int(11) NOT NULL default '0',
						wildcard tinyint(1) NOT NULL default '0',
						PRIMARY KEY  (wildcard_id)
)TYPE=InnoDB;",
$eqsql,
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_member_groups (
						`member_id` mediumint(5) NOT NULL default '0',
            `date` int(11) NOT NULL default '0',
						`group`	varchar(25) default NULL,
						KEY member_name (member_id)
)TYPE=InnoDB;",
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_tempmembers (
				    `tempmember_id` mediumint(8) unsigned NOT NULL auto_increment,
				    `membername` varchar(255) default NULL,
				    `raid_id` varchar(255) default NULL,
						`note` text default NULL,
						`attendees_signup_time` int(11) NOT NULL default '0',
						`group` varchar(25) default NULL,
						`class` smallint(3),
						KEY member_name (tempmember_id)
)TYPE=InnoDB;",
"ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` 
ADD `attendees_note_lvl` int(11) NOT NULL default '0',
ADD `attendees_statchangedby` text default NULL;",
"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_userconfig (
						`id` mediumint(8) unsigned NOT NULL auto_increment,
						`config_name` varchar(255) NOT NULL default '',
						`user_id` mediumint(5) NOT NULL default '0',
            `config_value` varchar(255) default NULL,
        KEY member_name (id)
)TYPE=InnoDB;",
"ALTER TABLE `" . $table_prefix . "raidplan_member_groups` 
ADD `groupevent` varchar(255) default NULL;",
);

?>
