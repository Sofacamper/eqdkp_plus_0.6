<?php
 /*
 * Project:     Infopages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-02-26 10:46:12 +0100 (Do, 26 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     infopages
 * @version     $Rev: 4003 $
 *
 * $Id: 212_to_214.php 4003 2009-02-26 09:46:12Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
 
$new_version    = '2.1.4';
$updateFunction = false;
$reloadSETT = 'settings.php';

$updateDESC = array(
  '',
  'redo: rename config name column in table',
  'redo: rename config value column in table',
  'redo: Add visibility column',
  'alt: Alter visibility column if already exists',
);

$updateSQL = array(
  "ALTER TABLE __info_config CHANGE `name` `config_name` varchar(255) default '';",
  "ALTER TABLE __info_config CHANGE `value` `config_value` varchar(255) default '';",	    
  "ALTER TABLE __info_pages ADD `page_visibility` enum('0','1', '2') NOT NULL default '0';",
  "ALTER TABLE __info_pages CHANGE `page_visibility` `page_visibility` enum('0','1', '2') NOT NULL default '0';",
);

?>