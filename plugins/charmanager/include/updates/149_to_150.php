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

$new_version = '1.5.0';

$updateDESC = array(
	'',
	'Added second skills fields to members Table',
	'Add new auth option for member_delete functionality',
	'Add new auth option for member_delete functionality (admin part)',
	'Add table field for request-deletion field',
	'Add table field for confirm-field',
);

$updateSQL = array(
"ALTER TABLE __member_additions 
	ADD `skill2_1` SMALLINT(6) NOT NULL default '0', 
	ADD `skill2_2` SMALLINT(6) NOT NULL default '0', 
	ADD `skill2_3` SMALLINT(6) NOT NULL default '0';",
"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES (953, 'u_charmanager_delete', 'Y');",
"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES (952, 'a_charmanager_delmanager', 'N');",
"ALTER TABLE __member_additions ADD `requested_del` enum('0','1') NOT NULL default '0';",
"ALTER TABLE __member_additions ADD `require_confirm` enum('0','1') NOT NULL default '0';",
);

?>
