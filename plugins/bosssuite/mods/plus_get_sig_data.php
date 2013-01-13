<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-11-22 20:58:04 +0100 (Sat, 22 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 3235 $
 *
 * $Id: plus_get_sig_data.php 3235 2008-11-22 19:58:04Z sz3 $
 */

if ( !defined('EQDKP_INC') )
{
    die('Do not access this file directly.');
}

function plus_get_sig_data(){
// new mgs class
require_once(dirname(__FILE__).'/../include/bsmgs.class.php');
$mybsmgs = new BSMGS();
if (!$mybsmgs->game_supported('bossbase')){
  return false;
}else{
  # Get configuration data from the database
  ####################################################
  require_once(dirname(__FILE__).'/../include/bssql.class.php');
  $mybcsql = new BSSQL();

  $bb_conf = $mybcsql->get_config('bossbase');
  $bc_conf = $mybcsql->get_config('bosscounter');
  $sbzone = $mybcsql->get_bzone();

  # Get data
  ####################################################
  $data = $mybcsql->get_data($bb_conf, $sbzone);
  return $data;
}
}
?>
