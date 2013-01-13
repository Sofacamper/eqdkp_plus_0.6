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
 * $Id: 300_to_304.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '3.0.4';
$updateFunction = false;

// Autodetect Timezone
  $dstvalue   = (date('I', time()) == 1) ? 1 : 0;
  $tzvalue    = date('H.i', time()) - (gmdate('H.i', time())+$dstvalue);
  $tzvalue    = str_replace(',', '.', $tzvalue);

$updateDESC = array(
	'',
  'Added close field to Raid Table',
);

$updateSQL = array(
	"ALTER TABLE `" . $table_prefix . "raidplan_raids` ADD `raid_closed` enum('0','1') NOT NULL default '0';",
);
$savearray = array(
  'rp_timezone'	    => $tzvalue,
  'rp_dstcheck'	    => 1,
  'rp_use_guests'	  => 1,
);
$wpfcdb->UpdateConfig($savearray, $wpfcdb->CheckDBFields('config_name'));

?>
