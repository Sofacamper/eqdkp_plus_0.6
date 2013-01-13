<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-08-08 15:03:42 +0200 (Fr, 08 Aug 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 2502 $
 * 
 * $Id: versions.php 2502 2008-08-08 13:03:42Z wallenium $
 */

$new_version = '1.2.0';

$updateDESC = array(
	'',
	'Added notes field to User Table',
);

$updateSQL = array(
"ALTER TABLE __member_additions ADD `notes`	text default NULL;",
);

?>
