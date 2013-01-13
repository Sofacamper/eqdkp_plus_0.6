<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-11-04 18:35:02 +0100 (Thu, 04 Nov 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 8973 $
 * 
 * $Id: config.inc.php 8973 2010-11-04 17:35:02Z wallenium $
 */

$cms = array( // all supported cms systems.
"clansphere" => "clansphere.bridge.php",
"vbulletin" => "vBulletin.bridge.php",
"phpbb3"    => "phpbb3.bridge.php",
"phpbb2"    => "phpbb2.bridge.php",
"joomla"    => "joomla.bridge.php",
"e107"      => "e107.bridge.php",
"wbb2"		=> "wbb2.bridge.php",
"wbb3"      => "wbb3.bridge.php",
"smf"       => "smf.bridge.php",
"smf2"		=> "smf2.bridge.php",
"evo102" 	=> "evo102.bridge.php",
"php-fusion"=> "php-fusion.bridge.php",
"vb4"		=> "vBulletin4.bridge.php",
);

if(!is_null($conf_plus['pk_bridge_cms_sel']))
{
    $cms_sel = $conf_plus['pk_bridge_cms_sel']; // selected cms
}

//If the CMS is installed in a different Database
if ($conf_plus['pk_bridge_cms_otherDB']==1)
{
	$cms_host = trim($conf_plus['pk_bridge_cms_host']); // host
	$cms_user = trim($conf_plus['pk_bridge_cms_user']); // user
	$cms_pass = trim($conf_plus['pk_bridge_cms_pass']); // password
	$cms_db = trim($conf_plus['pk_bridge_cms_db']); // databasename
}
else
{
	$cms_host = $dbhost;
	$cms_user = $dbuser;
	$cms_pass = $dbpass;
	$cms_db = $dbname;
}

$cms_tableprefix = $conf_plus['pk_bridge_cms_tableprefix']; // Prefix
$cms_group = $conf_plus['pk_bridge_cms_group']; // cms group
?>
