<?php
 /*
 * Project:     EQdkp-Plus Raidlogimport
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008-2010
 * Date:        $Date: 2009-05-07 17:52:03 +0200 (Do, 07 Mai 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2008-2009 hoofy_leon
 * @link        http://eqdkp-plus.com
 * @package     raidlogimport
 * @version     $Rev: 4786 $
 *
 * $Id: 0514-0516.php 4786 2009-05-07 15:52:03Z hoofy_leon $
 */

if(!defined('EQDKP_INC'))
{
	header('HTTP/1.0 404 Not Found');
	exit;
}

$new_version    = '0.5.7.1';
$updateFunction = '';

$updateDESC = array(
	'',
	'Remove Whitespace from end of Magmaw',
	'Rename Omintron to Omnotron',
	'Add Config timezone_offset'
);
$reloadSETT = 'settings.php';

$updateSQL = array(
	"UPDATE __raidlogimport_bz SET bz_string = 'Magmaw' WHERE bz_string = 'Magmaw ';",
	"UPDATE __raidlogimport_bz SET bz_string = 'Omnotron Defense System' WHERE bz_string = 'Omintron Defense System';",
	"INSERT INTO __raidlogimport_config (config_name, config_value) VALUES ('timezone_offset', '0');"
);
?>