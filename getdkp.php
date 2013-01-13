<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       18 October 2009
 * Date:        $Date: 2010-01-24 18:00:39 +0100 (Sun, 24 Jan 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7079 $
 *
 * $Id: getdkp.php 7079 2010-01-24 17:00:39Z wallenium $
 */

define('EQDKP_INC', true);
$eqdkp_root_path = './';
include_once($eqdkp_root_path . 'common.php');
include_once($eqdkp_root_path . 'pluskernel/include/data_export.class.php');
$myexp = new content_export();
echo $myexp->export();
?>