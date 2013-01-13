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
 * $Id: 100_to_200.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '2.0.0';
$updateFunction = false;

$updateSQL = array(
	"ALTER TABLE `" . $table_prefix . "raidplan_raids` ADD raid_date_invite int(11) NOT NULL default '0';",
	"ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` ADD attendees_note text default NULL;",
	"ALTER TABLE `" . $table_prefix . "raidplan_raid_attendees` ADD attendees_signup_time int(11) NOT NULL default '0';",
	"INSERT INTO `" . $table_prefix . "auth_options` VALUES (506, 'a_raidplan_config', 'N');",
	"CREATE TABLE `" . $table_prefix . "raidplan_config` (
  `config_name` varchar(255) NOT NULL default '',
  `config_value` varchar(255) default NULL,
  PRIMARY KEY  (`config_name`)
)TYPE=InnoDB;",
"INSERT INTO `" . $table_prefix . "raidplan_config` VALUES ('rp_show_ranks', '1');",
"INSERT INTO `" . $table_prefix . "raidplan_config` VALUES ('rp_short_rank', '1');",
"INSERT INTO `" . $table_prefix . "raidplan_config` VALUES ('rp_roll_systm', '1');",
"INSERT INTO `" . $table_prefix . "raidplan_config` VALUES ('rp_wildcard', '1');",
"INSERT INTO `" . $table_prefix . "raidplan_config` VALUES ('rp_last_days', '7');",
);

?>
