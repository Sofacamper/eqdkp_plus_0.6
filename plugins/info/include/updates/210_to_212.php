<?php
 /*
 * Project:     Infopages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-12-26 19:56:37 +0100 (Fr, 26 Dez 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     infopages
 * @version     $Rev: 3527 $
 *
 * $Id: 2011_to_210.php 3527 2008-12-26 18:56:37Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '2.1.2';
$updateFunction = false;
$reloadSETT = 'settings.php';

$updateDESC = array(
  '',
  'Add update check'
);

$updateSQL = array(
  "INSERT INTO __info_config VALUES ('euchk', '1')"
);

?>
