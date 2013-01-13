<?php
/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-08-05 11:41:15 +0200 (Wed, 05 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: Dallandros $
 * @copyright   2008 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 5478 $
 *
 * $Id: 014_to_015.php 5478 2009-08-05 09:41:15Z Dallandros $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$new_version = '0.1.4';
$updateFunction = false;
$reloadSETT = 'settings.php';

$updateDESC = array(
  '',
  'Insert Timezone settings',
);

$updateSQL = array(
  'INSERT INTO `__shoutbox_config` (`config_name`, `config_value`)
   VALUES (\'sb_timezone\', \'0\'), (\'sb_dstcorrect\', \'0\')',
);

?>
