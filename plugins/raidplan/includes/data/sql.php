<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-01-21 23:28:10 +0100 (Wed, 21 Jan 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 3589 $
 * 
 * $Id: sql.php 3589 2009-01-21 22:28:10Z wallenium $
 */

  if ( !defined('EQDKP_INC') ){
      header('HTTP/1.0 404 Not Found');exit;
  }

  $raidplanSQL = array('uninstall'  => array(
      '1'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_raids',
      '2'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_raid_classes',
      '3'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_raid_attendees',
      '4'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_wildcards',
      '5'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_classes',
      '6'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_config',
      '7'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_raidtemplate',
      '8'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_repeat',
      '9'     => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_member_groups',
      '10'    => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_tempmembers',
      '11'    => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_userconfig',
      '12'    => 'DROP TABLE IF EXISTS '.$table_prefix.'raidplan_saveduser',
      '13'    => 'DROP TABLE IF EXISTS '.$table_prefix.'roles',
    ),
    'install'  => array(
      '1'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_raids (
      						raid_id mediumint(8) unsigned NOT NULL auto_increment,
      						raid_name varchar(255) default NULL,
      						raid_date int(11) NOT NULL default '0',
      						raid_date_invite int(11) NOT NULL default '0',
      						raid_date_subscription int(11) NOT NULL default '0',
      						raid_date_change int(11) NOT NULL default '0',
      						raid_date_added int(11) NOT NULL default '0',
      						raid_date_finish int(11) NOT NULL default '0',
      						raid_note text default NULL,
      						raid_leader int(11) NOT NULL default '0',
      						raid_value float(6,2) default NULL,
      						raid_link text DEFAULT NULL,
      						raid_attendees mediumint(8) NOT NULL default '0',
      						raid_added_by varchar(30) NOT NULL default '',
      						raid_updated_by varchar(30) default NULL,
      						raid_repeat varchar(255) NOT NULL default '0',
      						delete_id varchar(255) NOT NULL default '0',
      						raid_closed enum('0','1') NOT NULL default '0',
      						raid_distribution enum('0','1','2') NOT NULL default '0',
      						PRIMARY KEY  (raid_id)
      						)TYPE=InnoDB;",
      '2'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_raid_classes (
      						raid_id mediumint(8) unsigned NOT NULL default '0',
      						class_name varchar(50) default NULL,
      						class_count smallint(3) unsigned NOT NULL default '0',
      						KEY raid_id (raid_id)
      						)TYPE=InnoDB;",
      '3'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_raid_attendees (
      						`raid_id` mediumint(8) unsigned NOT NULL default '0',
      						`member_id` mediumint(5) NOT NULL default '0',
      						`role` varchar(255) default NULL,
      						`attendees_subscribed` tinyint(1) NOT NULL default '0',
      						`attendees_note` text default NULL,
      						`attendees_note_lvl` int(11) NOT NULL default '0',
      						`attendees_statchangedby` text default NULL,
      						`attendees_signup_time` int(11) NOT NULL default '0',
      						`attendees_change_time` int(11) NOT NULL default '0',
      						`attendees_random` mediumint(4) NOT NULL default '0',
      						`group`	varchar(255) default NULL,
      						KEY raid_id (raid_id),
      						KEY member_name (member_id)
      						)TYPE=InnoDB;",
      '4'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_wildcards (
      						`wildcard_id` mediumint(8) unsigned NOT NULL auto_increment,
                  `member_name` varchar(25) default NULL,
                  `member_id` mediumint(5) NOT NULL default '0',
                  `event` varchar(255) default NULL,
                  `date` int(11) NOT NULL default '0',
      						`wildcard` tinyint(1) NOT NULL default '0',
      						PRIMARY KEY  (wildcard_id)
      						)TYPE=InnoDB;",
      '5'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_classes (
      						event_name varchar(50) NOT NULL default '',
      						template_id varchar(50) NOT NULL default '0',
      						class_name varchar(50) NOT NULL default '',
      						class_count smallint(3) NOT NULL default '0'
      						)TYPE=InnoDB;",
      '6'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_config (
      						`config_name` varchar(255) NOT NULL default '',
                  `config_value` varchar(255) default NULL,
                  PRIMARY KEY  (`config_name`)
                  )TYPE=InnoDB;",
      '7'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_raidtemplate (
      						template_id mediumint(8) unsigned NOT NULL auto_increment,
      						template_name varchar(255) default NULL,
      						raid_name varchar(255) default NULL,
      						raid_note text default NULL,
      						raid_leader int(11) NOT NULL default '0',
      						raid_value float(6,2) default NULL,
      						raid_attendees mediumint(8) NOT NULL default '0',
      						raid_date int(11) NOT NULL default '0',
      						raid_date_invite int(11) NOT NULL default '0',
      						raid_date_subscription int(11) NOT NULL default '0',
      						raid_date_finish int(11) NOT NULL default '0',
      						raid_link text DEFAULT NULL,
      						raid_distribution enum('0','1','2') NOT NULL default '0',
      						PRIMARY KEY  (template_id)
      						)TYPE=InnoDB;",
      '8'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_repeat (
      						repeat_id mediumint(8) unsigned NOT NULL auto_increment,
      						raid_id varchar(255) default NULL,
      						next_date int(11) NOT NULL default '0',
      						job_date int(11) NOT NULL default '0',
      						delete_id varchar(255) NOT NULL default '0',
      						PRIMARY KEY  (repeat_id)
      						)TYPE=InnoDB;",
      '9'     => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_member_groups (
      						`member_id` mediumint(5) NOT NULL default '0',
                  `date` int(11) NOT NULL default '0',
      						`group`	varchar(25) default NULL,
      						`groupevent` varchar(255) default NULL,
      						KEY member_name (member_id)
      						)TYPE=InnoDB;",
      '10'    => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_tempmembers (
      				    `tempmember_id` mediumint(8) unsigned NOT NULL auto_increment,
      				    `membername` varchar(255) default NULL,
      				    `raid_id` varchar(255) default NULL,
      						`note` text default NULL,
      						`attendees_signup_time` int(11) NOT NULL default '0',
      						`group` varchar(25) default NULL,
      						`class` smallint(3),
      						KEY member_name (tempmember_id)
      						)TYPE=InnoDB;",
      '11'    => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_userconfig (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`config_name` varchar(255) NOT NULL default '',
      						`user_id` mediumint(5) NOT NULL default '0',
                  `config_value` varchar(255) default NULL,
      						PRIMARY KEY  (id)
                  )TYPE=InnoDB;",
      '12'    => "CREATE TABLE IF NOT EXISTS ".$table_prefix."raidplan_saveduser (
      						`member_id` smallint(5) unsigned NOT NULL,
                  `user_id` smallint(5) unsigned NOT NULL,
                  `role` VARCHAR( 255 ) NULL,
                  KEY `member_id` (`member_id`),
                  KEY `user_id` (`user_id`)
                  )TYPE=InnoDB;",
      '13'    => "CREATE TABLE ".$table_prefix."roles (
                  `role_id` mediumint(8) unsigned NOT NULL auto_increment,
                  `role_name` varchar(50) NOT NULL,
                  `role_classes` varchar(255) default NULL,
                  `role_image` varchar(255) default NULL,
                  PRIMARY KEY (`role_id`)
                )TYPE=InnoDB;",
    ),
  );
?>
