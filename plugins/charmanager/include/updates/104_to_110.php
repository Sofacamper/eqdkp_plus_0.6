<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-04-27 00:02:00 +0200 (Mo, 27 Apr 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 4670 $
 * 
 * $Id: 104_to_110.php 4670 2009-04-26 22:02:00Z wallenium $
 */

$new_version = '1.1.0';

$updateDESC = array(
	'',
	'Added Permission for configuration',
	'Added some new profile fields',
);

$updateSQL = array(
"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES (959, 'a_charmanager_config', 'N');",
"ALTER TABLE __member_additions 
ADD `health_bar`	varchar(255) NOT NULL default '0',
ADD `second_bar` varchar(255) NOT NULL default '0',
ADD `second_name` varchar(255),
ADD `prof1_value`	varchar(255) NOT NULL default '0',
ADD `prof1_name` varchar(255),
ADD `prof2_value`	varchar(255) NOT NULL default '0',
ADD `prof2_name` varchar(255),
ADD `last_update` varchar(255),
ADD `faction`	varchar(255)",
);

?>
