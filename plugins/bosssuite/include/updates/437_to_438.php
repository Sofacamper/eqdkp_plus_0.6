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
 * $Id: 437_to_438.php 4864 2009-05-14 21:20:40Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '4.3.8';
$updateFunction = false;
$reloadSETT = 'settings.php';

$updateDESC = array(
  '',
  'Add update check'
);

$updateSQL = array(
  "INSERT INTO __bs_config VALUES ('bb_enable_updatechk', '1')"
);
?>
