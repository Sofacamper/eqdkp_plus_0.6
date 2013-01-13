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
 * $Id: 305_to_310.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '3.1.0';
$updateFunction = false;

$updateDESC = array(
	'',
  'Added Table for default Members',
);

$updateSQL = array(
	"CREATE TABLE IF NOT EXISTS " . $table_prefix . "raidplan_saveduser (
						`member_id` smallint(5) unsigned NOT NULL,
            `user_id` smallint(5) unsigned NOT NULL,
            KEY `member_id` (`member_id`),
            KEY `user_id` (`user_id`)
          )TYPE=InnoDB;",
);

?>
