<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-08-09 13:10:44 +0200 (Sun, 09 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 5593 $
 * 
 * $Id: common.php 5593 2009-08-09 11:10:44Z wallenium $
 */

  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  } 
  
  if (!isset($eqdkp_root_path) ){
    $eqdkp_root_path = './';
  }
  include_once($eqdkp_root_path . 'common.php');
  
  /**
  * Database Tables used by RaidPlaner
  */
  if (!defined('RP_RAIDS_TABLE'))         { define('RP_RAIDS_TABLE',        $table_prefix . 'raidplan_raids');}
  if (!defined('RP_CLASSES_TABLE'))       { define('RP_CLASSES_TABLE',      $table_prefix . 'raidplan_raid_classes');}
  if (!defined('RP_ATTENDEES_TABLE'))     { define('RP_ATTENDEES_TABLE', 	  $table_prefix . 'raidplan_raid_attendees');}
  if (!defined('RP_WILDCARD_TABLE'))      { define('RP_WILDCARD_TABLE',     $table_prefix . 'raidplan_wildcards');}
  if (!defined('RP_CLASS_DIST_TABLE'))    { define('RP_CLASS_DIST_TABLE',   $table_prefix . 'raidplan_classes'); }
  if (!defined('RP_RAID_TEMP_TABLE'))     { define('RP_RAID_TEMP_TABLE', 	  $table_prefix . 'raidplan_raidtemplate'); }
  if (!defined('RP_CLASS_TEMP_TABLE'))    { define('RP_CLASS_TEMP_TABLE',   $table_prefix . 'raidplan_template_classes'); }
  if (!defined('RP_REPEAT_TABLE'))        { define('RP_REPEAT_TABLE', 		  $table_prefix . 'raidplan_repeat'); }
  if (!defined('RP_MEMGROUPS_TABLE'))     { define('RP_MEMGROUPS_TABLE', 	  $table_prefix . 'raidplan_member_groups'); }
  if (!defined('RP_TEMPMEMBERS_TABLE'))   { define('RP_TEMPMEMBERS_TABLE', 	$table_prefix . 'raidplan_tempmembers'); }
  if (!defined('RP_CONFIG_TABLE'))        { define('RP_CONFIG_TABLE',       $table_prefix . 'raidplan_config'); }
  if (!defined('RP_USETTING_TABLE'))      { define('RP_USETTING_TABLE',     $table_prefix . 'raidplan_userconfig'); }
  if (!defined('RP_SAVEDUSER_TABLE'))     { define('RP_SAVEDUSER_TABLE',    $table_prefix . 'raidplan_saveduser'); }
  if (!defined('COMMENTS_TABLE'))         { define('COMMENTS_TABLE',        $table_prefix . 'comments'); }
  if (!defined('ROLES_TABLE'))            { define('ROLES_TABLE',           $table_prefix . 'roles'); }
  
  /**
  * Used Classes
  */
  require_once($eqdkp_root_path . 'plugins/raidplan/includes/libloader.inc.php');
  include_once($eqdkp_root_path . 'plugins/raidplan/includes/time.class.php');
  include_once($eqdkp_root_path . 'plugins/raidplan/includes/raidplan.class.php');
  include_once($eqdkp_root_path . 'plugins/raidplan/includes/convertion.class.php');
  $rpclass    = new RaidplanAddition;
  $rpconvert  = new Convertions;
  
  /**
  * Load the global Configuration
  */
  $sql = 'SELECT * FROM ' . RP_CONFIG_TABLE;
  if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
  while($roww = $db->fetch_record($settings_result)) {
    $conf[$roww['config_name']] = $roww['config_value'];
  }
  $db->free_result($settings_result);
  
  /**
  * Load the User Configuration
  */
  if ($user->data['user_id'] && !$user->data['username']=="" && $conf['rp_override_usettings'] != 1){
  		$usql = "SELECT * FROM ".RP_USETTING_TABLE." WHERE `user_id`='".$user->data['user_id']."'";
  		$usettings_result = $db->query($usql);
  		while($rowww = $db->fetch_record($usettings_result)) {
  		  $usersettings[$rowww['config_name']] = $rowww['config_value'];
  		}
  		$db->free_result($usettings_result);
  		
  		// Security feature... only substitude strings on the whitelist..
  		$savewhitelist = array();
  		if($conf['rp_us_email']){                         // Email Settings
        array_push($savewhitelist, 'rp_send_email');
      }
      
      if($conf['rp_us_layout']){                        // Layout Settings
        array_push($savewhitelist, 'rp_cal_startday','rp_mode','rp_cal_ab');
      }
      
      if($conf['rp_us_time']){                          // Time Settings
        array_push($savewhitelist, 'rp_time_12h', 'rp_timezone', 'rp_date_format', 'rp_dstcheck');
      }

  		foreach($savewhitelist as $nhvalue){
  		  if(is_array($usersettings) && $usersettings[$nhvalue] != ''){
          $conf[$nhvalue] = $usersettings[$nhvalue];
        }
      }
  }
  
  /**
  * Alpha/Beta Markup
  */
  if(strtolower($pm->plugins['raidplan']->vstatus) == 'alpha'){
    $tpl->assign_vars(array(
                  'ALPHA_DESTROY'   => true
    ));
  }
  
  /**
  * eqdkpPLUS only Batch
  */
  $tpl->assign_vars(array('EQDKPPLUSONLY'   => '<img src="../images/plus_only.png" alt="Only for EQDKP PLUS" />'));
  
  /**
  * CSS Color Definition
  */
  $tpl->assign_vars(array(
                  'COLOR_STATUS_0'    => $conf['color_status0'],
                  'COLOR_STATUS_1'    => $conf['color_status1'],
                  'COLOR_STATUS_2'    => $conf['color_status2'],
                  'COLOR_STATUS_3'    => $conf['color_status3'],
    ));

  $plus_selects_color = (@constant('EQDKPPLUS_VERSION') && EQDKPPLUS_VERSION >= "0.6.1.0") ? true : false;
  if($plus_selects_color){
    $classsql = "SELECT class, color FROM __classcolors WHERE template='".$user->style['style_id']."'";
    $classresult = $db->query($classsql);
    while ($colorrow = $db->fetch_record($classresult)){
    	$tpl->assign_block_vars('classcss', array(
    		            'COLOR'    => $colorrow['color'],
    								'NAME'     => strtolower($colorrow['class']),
  								));
    }
  }else{
    // Class Color Selection
    $sql = 'SELECT class_name, class_id FROM '.CLASS_TABLE.' GROUP BY class_name ORDER BY class_name';
  	$result = $db->query($sql);
  	while ($row = $db->fetch_record($result)){
      if(strtolower($row['class_name']) != 'unknown'){
        $all_classes[$row['class_id']] = array(
                                         'name'     => $row['class_name'],
                                         'en_name'  => strtolower($rpconvert->classname($row['class_name'])),
                                         'id'       => $row['class_id'],
                                        );
    	}
    }			
    $db->free_result($result);
    foreach($all_classes as $clrow){
    		$tpl->assign_block_vars('classcss', array(
    		            'COLOR'    => $conf['classc_'.$clrow['en_name']],
    								'NAME'     => $clrow['en_name'],
  								));
    }
  }
    
  /**
  * Time Zone Management
  */
  $stime = new DateFormats($user->lang['rp_calendar_lang']); // Init the Time Management Class
  $conf['timeformats'] = $stime->timeFormats(); // Load the Time Format Array
  
  /**
  * LogFile Manager
  */
  include_once($eqdkp_root_path . 'plugins/raidplan/includes/logs.class.php');
  $logmanager   = new LogManager('raidplan');
  
  /**
  * Charmanager Hook
  */
  $cm_include = $eqdkp_root_path.'plugins/charmanager/include/api.class.php';
  if (file_exists($cm_include)){
    require_once($cm_include);
    $cmapi = new cmAPI();
    // Check if the API Version is too old for usage with RP
    if($cmapi->version < "1.2.0"){
      $charmanager_available = false;
      $charmanager_toold     = true;
    }else{
      $charmanager_available = true;
      $charmanager_toold     = false;
      $charmanager_installed = $cmapi->isInstalled();
      $CharTools = $cmapi->CharTools;
      
      $mycmaddfile = $eqdkp_root_path . 'plugins/raidplan/games/'.$cmapi->Game().'/cm_content.php';
      if(is_file($mycmaddfile)){
        include($mycmaddfile);
      }
    }
  }
?>
