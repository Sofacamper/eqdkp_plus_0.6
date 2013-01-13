<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-05-20 13:35:11 +0200 (Mi, 20 Mai 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 4920 $
 * 
 * $Id: common.php 4920 2009-05-20 11:35:11Z wallenium $
 */

  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  } 
  
  if (!isset($eqdkp_root_path) ){
    $eqdkp_root_path = './';
  }
  include_once($eqdkp_root_path . 'common.php');
  
  /**
  * Framework include
  **/
  require_once($eqdkp_root_path . 'plugins/charmanager/include/libloader.inc.php');
  $wpfcdb     = new AdditionalDB('charmanager_config');
  
  // Load Charmanager Library
  require_once($eqdkp_root_path . 'plugins/charmanager/include/chartools.class.php');
  $CharTools  = new CharTools();
  
  /**
  * Load the global Configuration
  */
  $sql = 'SELECT * FROM __charmanager_config';
  $settings_result = $db->query($sql);
  while($roww = $db->fetch_record($settings_result)) {
    $conf[$roww['config_name']] = $roww['config_value'];
  }
  $db->free_result($settings_result);

  /**
  * JQUEry
  */
  $tpl->assign_vars(array('JQUERY_INCLUDES'   => $jquery->Header()));
  
  if(!is_object($pcache)){
    $pcache = new cacheHandler;
  }
  
  /**
  * Game
  */
  $rpgfolders = new MyGames();
  $conf['real_gamename'] = $rpgfolders->GameName();
  
  /**
  * Check if we have an importer for our game...
  */
	$cmHasImport = (is_dir($eqdkp_root_path.'plugins/charmanager/games/'.$conf['real_gamename'].'/import/')) ? true : false;
	
	/**
  * Check requirements..
  */
  $ucReqVersions = array('php' => '5.0.0', 'eqdkp' => '0.6.2.7');
  if (version_compare(phpversion(), $ucReqVersions['php'], "<")){
  	message_die(sprintf($user->lang['uc_php_version'], $ucReqVersions['php'], phpversion()));
	}
	if (version_compare(EQDKPPLUS_VERSION, $ucReqVersions['eqdkp'], "<")){
  	message_die(sprintf($user->lang['uc_plus_version'], $ucReqVersions['eqdkp'], ((EQDKPPLUS_VERSION > 0) ? EQDKPPLUS_VERSION : '[non-PLUS]')));
	}
?>