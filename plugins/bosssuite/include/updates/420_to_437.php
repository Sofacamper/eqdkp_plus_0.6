<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-05-14 23:20:40 +0200 (Thu, 14 May 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 4864 $
 *
 * $Id: 420_to_437.php 4864 2009-05-14 21:20:40Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '4.3.7';
$updateFunction = 'BS420to437Update';
$reloadSETT = 'settings.php';

$updateDESC = false;
$updateSQL = false;

function BS420to437Update(){
global $db, $user;
  $sql = "SELECT config_value FROM __bs_config WHERE config_name = 'bb_tables'";
  $result = $db->query($sql);
  $prefices = $db->fetch_record($result);
  if($prefices['config_value'] !== ""){
    $prefarr = explode(", ", $prefices['config_value']);
    $outarr = array();
    foreach($prefarr as $prefix){
      $outarr[] = $prefix."_";
    }
    $prefstring = implode(", ", $outarr);
    $sql = "UPDATE __bs_config SET config_value='$prefstring' WHERE config_name = 'bb_tables'";
    $db->query($sql);
  } 
}
?>
