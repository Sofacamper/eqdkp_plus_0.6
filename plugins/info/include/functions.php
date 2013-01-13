<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-02-25 18:06:31 +0100 (Wed, 25 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 3992 $
 *
 * $Id: functions.php 3992 2009-02-25 17:06:31Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
} 

function getPageList() {
	global $db;
	static $pagelist = array();
	if(empty($pagelist)){
  	$sql = 'SELECT page_id, page_title FROM __info_pages';
  	if (!($settings_result = $db->query($sql))) {
  		message_die('Infopages: Could not obtain page list', '', __FILE__, __LINE__, $sql);
  	}
  	
  	while ($row = $db->fetch_record($settings_result)) {
  	  $pagelist[$row['page_id']] = $row['page_title'];
  	}
  	$db->free_result($row);
	}
	return $pagelist;
}

function getPageLinks() {
	global $db;
	static $pagelist = array();
	if(empty($pagelist)){
    $sql = 'SELECT page_id, page_title, page_menu_link, page_visibility FROM __info_pages';
  	if (!($settings_result = $db->query($sql))) {
  		return array();
      //message_die('Infopages: Could not obtain page links', '', __FILE__, __LINE__, $sql);
  	}
  	while ($row = $db->fetch_record($settings_result)) {
      if(allowedToViewPage($row['page_visibility'])){
    		$pagelist[$row['page_id']]['page_title'] = $row['page_title'];
    		$pagelist[$row['page_id']]['page_menu_link'] = $row['page_menu_link'];
  		}
  	}
  	$db->free_result($row);
	}
	return $pagelist;
}

function allowedToViewPage($page_vis){
global $user;
	 //visible for all users
    if($page_vis == 0){
      return true;
		}
    
    //guests only
    if(($page_vis == 1) && ($user->data['username'] == "")){
      return true;
    }
    
    //logged in only
    if(($page_vis == 2) && ($user->data['username'] != "")){
	   	return true;
    }    
    return false;
}

function getPageData($page_id) {
	global $db;
	$sql = "SELECT * FROM __info_pages WHERE page_id='" . $page_id . "'";
	if (!($erg = $db->query($sql))) {
		message_die('Infopages: Could not obtain page data', '', __FILE__, __LINE__, $sql);
	}
	$pagedata = $db->fetch_record($erg, true);
	
	$db->free_result($erg);
	return $pagedata;
}

function getUserName($userid) {
    global $db;
    $sql = "SELECT username FROM __users WHERE user_id='" . $userid . "'";
    if (!($erg = $db->query($sql))) {
        message_die('Infopages: Could not obtain user name', '', __FILE__, __LINE__, $sql);
    }
    $userdata = $db->fetch_record($erg, true);

    $db->free_result($erg);
    return $userdata['username'];
}

function getInfoConfig() {
	global $db;
	$sql = 'SELECT * FROM __info_config';
	if (!($settings_result = $db->query($sql)))
	{
		message_die('Infopages: Could not obtain configuration data', '', __FILE__, __LINE__, $sql);
	}

	while($roww = $db->fetch_record($settings_result)) {
    	$conf[$roww['config_name']] = $roww['config_value'];
	}
	
	$db->free_result($settings_result);
	return $conf;
}
// the "Save my Data to the Database" :D (Wallenium)
function UpdateConfig($fieldname,$insertvalue) {
global $eqdkp_root_path, $db;
	$sql = "UPDATE __info_config SET config_value='".strip_tags(htmlspecialchars($insertvalue))."' WHERE config_name='".$fieldname."';";
if ($db->query($sql)){
	return true;
} else {
	return false;
}
}
													
?>
