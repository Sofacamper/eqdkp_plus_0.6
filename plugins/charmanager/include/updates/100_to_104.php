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
 * $Id: 100_to_104.php 4670 2009-04-26 22:02:00Z wallenium $
 */
 
$new_version = '1.0.4';

$updateDESC = array(
	'',
	'Added Permission for connection',
	'Added configuration table',
);

$updateSQL = array(
"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES (958, 'u_charmanager_connect', 'Y');",
"CREATE TABLE IF NOT EXISTS __charmanager_config (
				          `config_name` varchar(255) NOT NULL default '',
                  `config_value` varchar(255) default NULL,
                  PRIMARY KEY  (`config_name`)
		              )"
);

?>
