<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:42:18 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 12161 $
 *
 * $Id: plusversion.php 12161 2012-09-30 20:42:18Z wallenium $
 */

if ( !defined('EQDKP_INC') )
{
    die('Do not access this file directly.');
}

define('EQDKPPLUS_VERSION', '0.6.4.13');
define('REQUIRED_PHP_VERSION', '5.1.2');
define('EQDKPPLUS_VERSION_BETA', FALSE);
if (isset($svn_rev)) {
	define('SVN_REV', $svn_rev);
}
?>