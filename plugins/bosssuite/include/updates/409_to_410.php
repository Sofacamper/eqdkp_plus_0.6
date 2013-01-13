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
 * $Id: 409_to_410.php 4864 2009-05-14 21:20:40Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '4.1.0';
$updateFunction = 'BS409to410Update';

$updateDESC = false;
$updateSQL = false;

function BS409to410Update(){
  global $db, $table_prefix, $eqdkp;
  $sql = "SELECT * FROM ".$table_prefix."bs_config WHERE config_name='bp_boss_image_type';";
  $result = $db->query($sql); 
  $cnrs = $db->fetch_record();
  if(false == $cnrs){
    $game_arr = explode('_', $eqdkp->config['default_game']);
    $currentgame = $game_arr[0];
    $imagetype = 'gif';
    if($currentgame == 'LOTRO')
      $imagetype = 'jpg';
    $db->query("INSERT INTO ".$table_prefix."bs_config VALUES ('bp_boss_image_type', '".$imagetype."')");	
  }  
}
?>
