<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2010-02-14 13:25:18 +0100 (Sun, 14 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 7245 $
 * 
 * $Id: settings.php 7245 2010-02-14 12:25:18Z wallenium $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// init the database
$wpfcdb = new AdditionalDB('raidplan_config');

// the updater class thing
$rpupdater = new PluginUpdater('raidplan','rp_','raidplan_config','includes');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('a_raidplan_');

// Get the plugin
$raidplan = $pm->get_plugin('raidplan');

// reset the version (to force an update)
if($_GET['version'] == 'reset'){
	$rpupdater->DeleteVersionString();
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}

// reset the Colors...
if($_GET['colors'] == 'reset'){
  include($eqdkp_root_path.'plugins/raidplan/games/'.$rpclass->SelectedGame().'/init_vars.php');
  if(is_array($rpclassColorsCSS)){
  	$wpfcdb->UpdateConfig($rpclassColorsCSS, $wpfcdb->CheckDBFields('config_name'));
  }
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}

// reset the Roles...
if($_GET['roles'] == 'reset'){
  include($eqdkp_root_path.'plugins/raidplan/games/'.$rpclass->SelectedGame().'/init_vars.php');
  $db->query('TRUNCATE TABLE '.ROLES_TABLE);
  if(is_array($rproleVariables)){
  	foreach($rproleVariables as $roleid=>$rolevalue){
      $db->query("INSERT INTO ".ROLES_TABLE." (`role_name`, `role_image`, `role_classes`) VALUES ('".$rolevalue['name']."', '".$rolevalue['image']."', '".$rolevalue['classes']."');");
    }
  }
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}

if($_GET['roles'] && $_GET['roles'] != 'reset'){
  $sql = "DELETE FROM ".ROLES_TABLE." WHERE role_id='".(int) $_GET['roles']."'";
  $db->query($sql);
  redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}

// reset the user settings if the time zone is changed.
if($_GET['usersettings'] == 'truncate'){
  $sql = 'TRUNCATE TABLE '.RP_USETTING_TABLE;
  $db->query($sql);
}

// Save this shit
if ($_POST['issavebu']){
  // global config
  	$savearray = array(
    	'rp_show_ranks' 	=> $_POST['rp_show_ranks'], 		# Show ranks in raid planner?
			'rp_colored_memb' => $_POST['rp_colored_memb'], 	# Show only short ranks?
			'rp_send_email'		=> $_POST['rp_send_email'], 		# Email raids to all users
			'rp_roll_systm'		=> $_POST['rp_roll_systm'], 		# Should we use the roll-system?
			'rp_wildcard'			=> $_POST['rp_wildcard'],   		# Should we use the wildcard-system?
			'rp_last_days'		=> $_POST['rp_last_days'],  		# show recent raids: last x days
			'rp_auto_path'		=> $_POST['rp_auto_path'],  		# Autojoin Secret Path
			'rp_mode'					=> $_POST['rp_mode'], 					# Calendar Mode
			'rp_updatecheck'	=> $_POST['rp_updatecheck'], 		# Updatecheck enabled?
			'rp_rank'					=> $_POST['rp_rank'], 					# Rank for officers
			'rp_cal_ab'				=> $_POST['rp_cal_ab'], 				# Calendar
			'rp_hours_offset'	=> $_POST['rp_hours_offset'], 	# Time Offset (invite): Hours
			'rp_min_offset'		=> $_POST['rp_min_offset'], 		# Time Offset (invite): Minutes
			'rp_end_hour_offset'=> $_POST['rp_end_hour_offset'], # Time Offset (end): Hours
			'rp_end_min_offset'	=> $_POST['rp_end_min_offset'], # Time Offset (end): Minutes
			'rp_days_offset'	=> $_POST['rp_days_offset'], 		# Time Offset (end): Minutes
			'rp_raid_duration'=> $_POST['rp_raid_duration'],  # Raid duration (in h)
			'rp_cal_startday'	=> $_POST['rp_cal_startday'], 	# Week: STart day
			'rp_cal_hico'			=> $_POST['rp_cal_hico'], 			# Hide instance Icons
			'rp_rep_value'		=> $_POST['rp_rep_value'], 			# Repeat Value
			'rp_rep_enable'		=> $_POST['rp_rep_enable'], 		# Repeat enabled 
			'rp_rank_team'		=> $_POST['rp_rank_team'], 			# Rank of Stammteam 
			'rp_enable_team'	=> $_POST['rp_enable_team'], 		# Enable Stammteam
			'rp_short_class'	=> $_POST['rp_short_class'], 		# Viewraid: Disable Class names
			'rp_levelcap'     => $_POST['rp_levelcap'],       # Levelcap for Raidview
      'rp_enabl_levelcap'=> $_POST['rp_enabl_levelcap'],# Enable Levelcap
      'rp_hide_hiddngrp'=> $_POST['rp_hide_hiddngrp'],  # Hide hidden groups
      'rp_enable_classbrk' => $_POST['rp_enable_classbrk'], # enable Linebrak @classes
      'rp_breakvalue'   => $_POST['rp_breakvalue'],     # Linebreak Value
      'rp_enabl_officr' => $_POST['rp_enabl_officr'],   # Classleaders
      'rp_enable_groups'=> $_POST['rp_enable_groups'],  # Enable groups for one raid
      'rp_calhide_rname'=> $_POST['rp_calhide_rname'],  # Hide Raidname when Event Icons
      'rp_timezone'     => $_POST['rp_timezone'],       # Time Offset
      'rp_savegroups'   => $_POST['rp_savegroups'],     # save all groups per member for next raid
      'rp_enbl_resetday'=> $_POST['rp_enbl_resetday'],  # enable the reset day
      'rp_grp_resetday' => $_POST['rp_grp_resetday'],   # day of the week to reset... 
      'rp_hide_unsigned'=> $_POST['rp_hide_unsigned'],  # Hide unsigned Members row
      'rp_vr_hide_stat0'=> $_POST['rp_vr_hide_stat0'],  # Hide Statusrow 0 (confirmed)
      'rp_vr_hide_stat1'=> $_POST['rp_vr_hide_stat1'],  # Hide Statusrow 1 (signed in)
		  'rp_vr_hide_stat2'=> $_POST['rp_vr_hide_stat2'],  # Hide Statusrow 2 (not available)
		  'rp_vr_hide_stat3'=> $_POST['rp_vr_hide_stat3'],  # Hide statusrow 3 (not shure)
		  'rp_vr_hide_stat4'=> $_POST['rp_vr_hide_stat4'],  # hide statusrow 4 (unsigned)
		  'rp_wcexpirrmve'  => $_POST['rp_wcexpirrmve'],    # Offset for the Wildcard Expire
		  'rp_wcexpire'     => $_POST['rp_wcexpire'],       # should wildcards expire?
		  'rp_rolltooltip'  => $_POST['rp_rolltooltip'],    # Should the Roll Value should be displayed in a Tooltip?
      'rp_use_guests'   => $_POST['rp_use_guests'],     # Enable Possibility to add Guests?
      'rp_disbl_admnnte'=> $_POST['rp_disbl_admnnte'],  # Should the admin notes ne disabled?
      'rp_groupbyevent' => $_POST['rp_groupbyevent'],   # Save group by event...
      'rp_disable_cmnotes'=> $_POST['rp_disable_cmnotes'], # disable Charmanager Notes
      'rp_dstcheck'     => $_POST['rp_dstcheck'],       # Daylight saving Auto?
      'rp_time_12h'			=> $_POST['rp_time_12h'],				# 12h Time Format
      'rp_disable_note'	=> $_POST['rp_disable_note'],		# Disable the Member Notes
      'rp_disabl_cl_ac'	=> $_POST['rp_disabl_cl_ac'],		# Disable ClassLeader AutoConfirm
      'rp_date_format'  => $_POST['rp_date_format'],    # Date Format
      'rp_activeonly_sn'=> $_POST['rp_activeonly_sn'],  # Send notification to active users only
      'rp_ghidenotes'   => $_POST['rp_ghidenotes'],     # Hide Signin Notes for guests
      'rp_autoaddbyrnk' => $_POST['rp_autoaddbyrnk'],   # Add all members with a special rank on raid creation
      'rp_rank_add'     => $_POST['rp_rank_add'],       # The rank which should be added on raid creation
      'rp_autoaddconf'  => $_POST['rp_autoaddconf'],    # Autoadd: confirm the members?
      'rp_changesigned' => $_POST['rp_changesigned'],   # Member should be able to change the Subsription status when signin time is over
      'rp_chsigndvalue' => $_POST['rp_chsigndvalue'],   # Offset to raid (see changesigned)
      'rp_hideversion'  => $_POST['rp_hideversion'],    # Hide Raidplan Version in Footer
      'rp_removelock'   => $_POST['rp_removelock'],     # Remove the possibility for members to unsign
      'rp_comments'     => $_POST['rp_comments'],       # Enable the comment System?
      'color_status0'   => $_POST['color_status0'],     # Color Status 0 (confirmed)
      'color_status1'   => $_POST['color_status1'],     # Color Status 1
      'color_status2'   => $_POST['color_status2'],     # Color Status 2
      'color_status3'   => $_POST['color_status3'],     # Color Status 3
      'default_distri'  => $_POST['default_distri'],    # Default Distribution
      'rp_mailmethod'   => $_POST['rp_mailmethod'],     # Mail Method
      'smtp_host'       => $_POST['smtp_host'],         # SMTP Hostname
      'smtp_auth'       => $_POST['smtp_auth'],         # Use SMTP Auth?
      'smtp_username'   => $_POST['smtp_username'],     # SMTP Auth Username
      'smtp_password'   => $_POST['smtp_password'],     # SMTP Auth Password
      'sendmail_path'   => $_POST['sendmail_path'],     # Sendmail Path
      'rp_sender_email' => $_POST['rp_sender_email'],   # Sender Email
      'rp_sender_name'  => $_POST['rp_sender_name'],    # Sender Name
      'rp_raidsort'     => $_POST['rp_raidsort'],       # Raid List sort
      'rp_send_cemail'  => $_POST['rp_send_cemail'],    # Send Confirmation mail
      'rp_autoaddflex'  => $_POST['rp_autoaddflex'],    # AutoAdd flexible select
      'rp_us_email'     => $_POST['rp_us_email'],       # UserSettings EMAIL
      'rp_us_time'      => $_POST['rp_us_time'],        # UserSettings TIME
      'rp_us_layout'    => $_POST['rp_us_layout'],      # UserSettings LAYOUT
    );
    
    // If not EQDKP PLUS 0.6.0.3 or higher
    if(!$plus_selects_color){
      foreach($all_classes as $clroww){
        $tmparry['classc_'.$clroww['id']]   = $_POST['classc_'.$clroww['id']];
      }
      $savearray = array_merge($savearray, $tmparry);
    }
    
    // Update the config
    if ($wpfcdb->UpdateConfig($savearray, $wpfcdb->CheckDBFields('config_name'))){
      redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID.'&save=true');
    }else{
      $is_saved = false;
    }
} 

// Update Check init
$updchk_enbled  = ( $conf['rp_updatecheck'] == 1 ) ? true : false;
$cachedb        = array('table' => 'raidplan_config', 'data' => $conf['vc_data'], 'f_data' => 'vc_data', 'lastcheck' => $conf['vc_lastcheck'], 'f_lastcheck' => 'vc_lastcheck');
$versionthing   = array('name' => 'raidplan', 'inclpath' => 'includes', 'version' => $pm->get_data('raidplan', 'version'), 'build' => $pm->plugins['raidplan']->build, 'vstatus' => $pm->plugins['raidplan']->vstatus,  'enabled' => $updchk_enbled);
$rpvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rpvcheck->PerformUpdateCheck();
// End of Update Check init

// Date Format Dropdown
$datedropdwn = array(
										0 => $user->lang['rp_format_ddmmyyy'],
										1 => $user->lang['rp_format_mmddyyy'],
										);

// Mode Dropdown
$modedropdwn = array(
										0 => $user->lang['rp_mode_classic'],
										1 => $user->lang['rp_mode_calendar'],
										2 => $user->lang['rp_mode_mixed'],
										3 => $user->lang['rp_mode_calendar2lines'],
										4 => $user->lang['rp_mode_mixed2lines'],
										);
//Layout Dropdown
$abdropdwn = array(
										0 => $user->lang['rp_ab_beyond'],
										1 => $user->lang['rp_ab_above'],
										);

// Calendar Sort
$sortdropdwn = array(
										0 => $user->lang['rp_sort_asc'],
										1 => $user->lang['rp_sort_desc'],
										);

// start day
$sddropdwn = array(
										'monday' => $user->lang['rp_monday'],
										'sunday' => $user->lang['rp_sunday'],
										);

// mail methods
$maildropdwn = array(
										'mail'      => $user->lang['rp_mail_mail'],
										'sendmail'  => $user->lang['rp_mail_sendmail'],
										'smtp'      => $user->lang['rp_mail_smtp'],
										);

// weekday of reset
$resetdropdwn = array(
                    0 => $user->lang['rp_sunday'],
                    1 => $user->lang['rp_monday'],
                    2 => $user->lang['rp_tuesday'],
                    3 => $user->lang['rp_wednesday'],
                    4 => $user->lang['rp_thursday'],
                    5 => $user->lang['rp_friday'],
                    6 => $user->lang['rp_saturday'],
                    );

// Distribution types array
$distridropdwn = array(
                    '0'   => $user->lang['rp_class_distribution'],
                    '1'   => $user->lang['rp_role_distribution'],
                    '2'   => $user->lang['rp_no_distributon']
                    );

      // Rank Dropdown
				$rasql = 'SELECT rank_id, rank_name
                FROM ' . MEMBER_RANKS_TABLE . '
                WHERE rank_id > 0
                ORDER BY rank_id';
        $result2 = $db->query($rasql);
        while ( $row2 = $db->fetch_record($result2) )
        {
            $rankdropdwn[$row2['rank_id']] = stripslashes($row2['rank_name']);
        }
        $db->free_result($result2);
        
      // Generate the Timestamp Dropdown
      if(date('I', $stime->DoTime()) == 1 && $conf['rp_dstcheck'] == 1){
        $tsoffset = ( $conf['rp_timezone'] != "" ) ? $conf['rp_timezone'] : ((date('Z', $stime->DoTime())-1)/3600);
      }else{
        $tsoffset = ( $conf['rp_timezone'] != "" ) ? $conf['rp_timezone'] : (date('Z', $stime->DoTime())/3600);
      }
      $time_select = array();
      foreach( $user->lang as $off => $words ){
 			  if (preg_match("/^time_([\d\.\-]+)$/", $off, $match)){
				  $time_select[$match[1]] = $words;
 			  }
      } 
    
    // Class Color Selection
    if(!$plus_selects_color){
  		foreach($all_classes as $clrow){
    			$tpl->assign_block_vars('classes', array(
    									'NAME'     => $clrow['name'],
    									'CPICKER'  => $jquery->colorpicker('classc_'.strtolower($rpconvert->classname($clrow['name'])), $conf['classc_'.strtolower($rpconvert->classname($clrow['name']))]),
  									));
    	}			
  	}
    
    // Autodetect Timezone Value
  	$dstvalue   = (date('I', $stime->DoTime()) == 1 && $conf['rp_dstcheck'] == 1) ? 1 : 0;
    $tzvalue    = date('H.i', $stime->DoTime()) - (gmdate('H.i', $stime->DoTime())+$dstvalue);
    $tzvalue    = str_replace(',', '.', $tzvalue);

// Role Manager
$rolesql  = 'SELECT * FROM '.ROLES_TABLE;
$roleresult = $db->query($rolesql);
while ( $rolerow = $db->fetch_record($roleresult)){
  $tpl->assign_block_vars('roles', array(
    'ID'       => $rolerow['role_id'],
    'NAME'     => $rolerow['role_name'],
    'CLASSES'  => $rpclass->IDlist2Classlist($rolerow['role_classes'])
  ));
}

// Output
$cmapi_status = ($charmanager_installed) ? 'enabled' : 'disabled';
$tpl->assign_vars(array(
      'F_CONFIG'        => 'settings.php' . $SID,
      
      //CSS
      'RP_TEMPLATEPATH' => $user->style['template_path'],
      'RPTEMPL_ADMIN'   => true,
      'SHOW_CLASSCOLOR' => ($plus_selects_color) ? false : true,
      
      // CM API
      'CMAPI_AVAILABLE' => $charmanager_available,
      'CMAPI_TO_OLD'    => $charmanager_toold,
      'CMAPI_ENABLED'   => $cmapi_status,
      'CMAPI_VERSION'   => ($charmanager_available) ? $cmapi->version : '',
      'CM_VERSION'      => $pm->get_data('charmanager', 'version'),
      
      'SHOW_RANKS'     	=> $wpfcdb->isChecked($conf['rp_show_ranks']),
      'COLORED_MEMBERS' => $wpfcdb->isChecked($conf['rp_colored_memb']),
      'SEND_EMAIL'    	=> $wpfcdb->isChecked($conf['rp_send_email']),
      'SEND_CEMAIL'     => $wpfcdb->isChecked($conf['rp_send_cemail']),
      'ROLL_SYSTEM'    	=> $wpfcdb->isChecked($conf['rp_roll_systm']),
      'UPDATE_CHECK'    => $wpfcdb->isChecked($conf['rp_updatecheck']),
      'WILDCARD'        => $wpfcdb->isChecked($conf['rp_wildcard']),
      'HIDE_INSTICON'		=> $wpfcdb->isChecked($conf['rp_cal_hico']),
      'REPEAT_ENABLE'		=> $wpfcdb->isChecked($conf['rp_rep_enable']),
      'ENABLE_TEAM'			=> $wpfcdb->isChecked($conf['rp_enable_team']),
      'SHOW_CLASSES'		=> $wpfcdb->isChecked($conf['rp_short_class']),
      'ENABL_LEVELCAP'  => $wpfcdb->isChecked($conf['rp_enabl_levelcap']),
      'HIDE_HIDDNGRP'   => $wpfcdb->isChecked($conf['rp_hide_hiddngrp']),
      'ENABLE_CLSBREAK' => $wpfcdb->isChecked($conf['rp_enable_classbrk']),
      'ENBL_OFFICERS'   => $wpfcdb->isChecked($conf['rp_enabl_officr']),
      'ENBL_GROUPS'     => $wpfcdb->isChecked($conf['rp_enable_groups']),
      'HIDE_RAIDNAME'   => $wpfcdb->isChecked($conf['rp_calhide_rname']),
      'SAVEGROUPS'      => $wpfcdb->isChecked($conf['rp_savegroups']),
      'ENABL_RESETDAY'  => $wpfcdb->isChecked($conf['rp_enbl_resetday']),
      'HIDE_UNSIGNED'   => $wpfcdb->isChecked($conf['rp_hide_unsigned']),
      'HIDE_VRSTAT0'    => $wpfcdb->isChecked($conf['rp_vr_hide_stat0']),
      'HIDE_VRSTAT1'    => $wpfcdb->isChecked($conf['rp_vr_hide_stat1']),
      'HIDE_VRSTAT2'    => $wpfcdb->isChecked($conf['rp_vr_hide_stat2']),
      'HIDE_VRSTAT3'    => $wpfcdb->isChecked($conf['rp_vr_hide_stat3']),
      'HIDE_VRSTAT4'    => $wpfcdb->isChecked($conf['rp_vr_hide_stat4']),
      'WCEXPIRE'        => $wpfcdb->isChecked($conf['rp_wcexpire']),
      'ROLLTOOLTIP'     => $wpfcdb->isChecked($conf['rp_rolltooltip']),
      'GUESTS'          => $wpfcdb->isChecked($conf['rp_use_guests']),
      'DISABL_ADMNNOTS' => $wpfcdb->isChecked($conf['rp_disbl_admnnte']),
      'GROUPBYEVENT'    => $wpfcdb->isChecked($conf['rp_groupbyevent']),
      'DISABLE_CMNOTES'	=> $wpfcdb->isChecked($conf['rp_disable_cmnotes']),
      'DSTCHECK'	      => $wpfcdb->isChecked($conf['rp_dstcheck']),
      '12HFORMAT'	      => $wpfcdb->isChecked($conf['rp_time_12h']),
      'DISABLE_CL_AC'   => $wpfcdb->isChecked($conf['rp_disabl_cl_ac']),
      'DISABLE_NOTE'	  => $wpfcdb->isChecked($conf['rp_disable_note']),
      'MAILACTIVE_ONLY' => $wpfcdb->isChecked($conf['rp_activeonly_sn']),
      'NOTES_HODE_G'    => $wpfcdb->isChecked($conf['rp_ghidenotes']),
      'AUTO_ADDBYRANK'  => $wpfcdb->isChecked($conf['rp_autoaddbyrnk']),
      'AUTO_CONFIRM'    => $wpfcdb->isChecked($conf['rp_autoaddconf']),
      'CHANGE_STATUS'   => $wpfcdb->isChecked($conf['rp_changesigned']),
      'HIDE_VERSION'    => $wpfcdb->isChecked($conf['rp_hideversion']),
      'REMOVE_LOCK'     => $wpfcdb->isChecked($conf['rp_removelock']),
      'SHOW_COMMENTS'   => $wpfcdb->isChecked($conf['rp_comments']),
      'SMTP_AUTH'       => $wpfcdb->isChecked($conf['smtp_auth']),
      'AUTOADDFLEX'     => $wpfcdb->isChecked($conf['rp_autoaddflex']),
      'US_EMAIL'        => $wpfcdb->isChecked($conf['rp_us_email']),
      'US_LAYOUT'       => $wpfcdb->isChecked($conf['rp_us_layout']),
      'US_TIME'         => $wpfcdb->isChecked($conf['rp_us_time']),
      

      'STATUSCGVALUE'   => $conf['rp_chsigndvalue'],
      'LAST_X_DAYS'     => $conf['rp_last_days'],
      'AUTOHASH'      	=> $conf['rp_auto_hash'],
      'AUTOPATH'      	=> $conf['rp_auto_path'],
      'HOURS_OFFSET'    => $conf['rp_hours_offset'],
      'MIN_OFFSET'    	=> $conf['rp_min_offset'],
      'HOURS_OFFSET_END'=> $conf['rp_end_hour_offset'],
      'DAYS_OFFSET'			=> $conf['rp_days_offset'],
      'MIN_OFFSET_END'  => $conf['rp_end_min_offset'],
      'RAID_DURATION'   => ($conf['rp_raid_duration']) ? $conf['rp_raid_duration'] : 4,
      'REPEAT_VALUE'		=> $conf['rp_rep_value'],
      'LEVELCAP'        => $conf['rp_levelcap'],
      'BREAK_VALUE'     => $conf['rp_breakvalue'],
      'RESETDAY'        => $conf['rp_grp_resetday'],
      'WCEXPIREVALUE'   => $conf['rp_wcexpirrmve'],
      'SMTP_HOST'       => $conf['smtp_host'],
      'SMTP_USER'       => $conf['smtp_username'],
      'SMTP_PASSWORD'   => $conf['smtp_password'],
      'SENDMAIL_PATH'   => $conf['sendmail_path'],
      'PHPINI_SENDMAIL' => $rpclass->CheckPHPvalue('sendmail_path'),
      'SENDER_ADDRESS'  => ($conf['rp_sender_email']) ? $conf['rp_sender_email'] : $eqdkp->config['admin_email'],
      'SENDER_NAME'     => ($conf['rp_sender_name']) ? $conf['rp_sender_name'] : $eqdkp->config['main_title'],
      'TZVALUE'         => $tzvalue,
      'DB_RP_VERSION'   => $conf['rp_inst_version'].' ['.$pm->plugins['raidplan']->vstatus.']',
        
      //DropDown
      'DRDWN_MODE'			=> $khrml->DropDown('rp_mode', $modedropdwn, $conf['rp_mode']),
      'DRDWN_RANK'			=> $khrml->DropDown('rp_rank', $rankdropdwn, $conf['rp_rank']),
      'DRDWN_RANK_2'		=> $khrml->DropDown('rp_rank_team', $rankdropdwn, $conf['rp_rank_team']),
      'DRDWN_RANK_3'		=> $khrml->DropDown('rp_rank_add', $rankdropdwn, $conf['rp_rank_add']),
      'DRDWN_AB'				=> $khrml->DropDown('rp_cal_ab', $abdropdwn, $conf['rp_cal_ab']),
      'DRDWN_SD'				=> $khrml->DropDown('rp_cal_startday', $sddropdwn, $conf['rp_cal_startday']),
     	'DRDWN_RESET'		  => $khrml->DropDown('rp_grp_resetday', $resetdropdwn, $conf['rp_grp_resetday']),
     	'DRDWN_TZONE'		  => $khrml->DropDown('rp_timezone', $time_select, $tsoffset),
     	'DRDWN_DFORMAT'		=> $khrml->DropDown('rp_date_format', $datedropdwn, $conf['rp_date_format']),
     	'DRDWN_DISTRI'		=> $khrml->DropDown('default_distri', $distridropdwn, $conf['default_distri']),
     	'DRDWN_MAIL'      => $khrml->DropDown('rp_mailmethod', $maildropdwn, $conf['rp_mailmethod']),
     	'DRDWN_SORT'      => $khrml->DropDown('rp_raidsort', $sortdropdwn, $conf['rp_raidsort']),

     	// Help Tooltip
     	'HELP_CALHNR'			=> $khrml->HelpTooltip($user->lang['rp_help_hraidname']),
     	'HELP_MOREGRP'		=> $khrml->HelpTooltip($user->lang['rp_help_moregroups']),
     	'HELP_LINEBREAK'	=> $khrml->HelpTooltip($user->lang['rp_help_linebreak']),
     	'HELP_CLONING'		=> $khrml->HelpTooltip($user->lang['rp_help_cloning']),
     	'HELP_TEAM'				=> $khrml->HelpTooltip($user->lang['rp_help_permteam']),
     	'HELP_TIMEOFF'    => $khrml->HelpTooltip($user->lang['rp_help_timezone']),
     	'HELP_RESETDAY'   => $khrml->HelpTooltip($user->lang['rp_help_resetday']),
     	'HELP_SAVEGRP'    => $khrml->HelpTooltip($user->lang['rp_help_savedgrp']),
     	'HELP_HIDEUNSGND' => $khrml->HelpTooltip($user->lang['rp_help_hideunsigned']),
     	'HELP_HIDEROWS'   => $khrml->HelpTooltip($user->lang['rp_help_hiderows']),
     	'HELP_WILDCARDEX' => $khrml->HelpTooltip($user->lang['rp_help_wcexpire']),
     	'HELP_ROLLTOOLTIP'=> $khrml->HelpTooltip($user->lang['rp_help_rolltooltip']),
     	'HELP_GUESTS'     => $khrml->HelpTooltip($user->lang['rp_help_guestadd']),
     	'HELP_VRESET'     => $khrml->HelpTooltip($user->lang['rp_help_vreset']),
     	'HELP_OFFICERS'		=> $khrml->HelpTooltip($user->lang['rp_help_officers']),
     	'HELP_DSTCHECK'   => $khrml->HelpTooltip($user->lang['rp_dsthelp']),
     	'HELP_12HFORMAT'	=> $khrml->HelpTooltip($user->lang['rp_help_12hourformat']),
     	'HELP_COLLATE'	  => $khrml->HelpTooltip($user->lang['rp_help_collatecheck']),
     	'HELP_HIDEGNOTES' => $khrml->HelpTooltip($user->lang['rp_help_hidegnotes']),
     	'HELP_RANKTOADD'  => $khrml->HelpTooltip($user->lang['rp_help_ranktoadd']),
     	'HELP_SIGNEDSTAT' => $khrml->HelpTooltip($user->lang['rp_help_signedstatus']),
     	'HELP_REMOVELOCK' => $khrml->HelpTooltip($user->lang['rp_help_removelock']),
     	'HELP_USERSETT'   => $khrml->HelpTooltip($user->lang['rp_help_usersettings']),
     
     	// Warn Tooltip
     	'WARN_NOADMINAPP' => $khrml->WarnTooltip($user->lang['rp_warn_noadmapprov']),
     	'WARN_DBCHANGES'  => $khrml->WarnTooltip($user->lang['rp_warn_dbchanges']),
     	'WARN_DISA_NOTES' => $khrml->WarnTooltip($user->lang['rp_warn_disablnotes']),
     	'WARN_RANKTOADD'  => $khrml->WarnTooltip($user->lang['rp_warn_ranktoadd']),
     	'WARN_REMOVELOCK' => $khrml->WarnTooltip($user->lang['rp_warn_removelock']),
     	'WARN_HIDEROWS'   => $khrml->WarnTooltip($user->lang['rp_warn_hiderows']),
     
      // Usersettingsaffected
      'USER_CAN_CHANGE' => '<img src="../images/global/personal.png" '.$khrml->HTMLTooltip($user->lang['rp_sett_altereduser'], 'rp_tt_persob').'/>',
     
      // Language
      'L_SUBMIT'        => $user->lang['submit'],
      'L_RESET'         => $user->lang['reset'],
      
      'L_GENERAL'       => $user->lang['rp_header_global'],
      
      // JS Code
		  'JS_COLLATECHK'   => $jquery->Dialog_URL('RPCollateCheck', $user->lang['rp_collatecheck'], 'collate_check.php', '800', '500'),
      'JS_FORCE_UPD'    => $jquery->Dialog_Confirm('ForceUpdate', $user->lang['rp_updwarntxt'], "window.location = 'settings.php?version=reset';"),
      'JS_RESET_COLORS' => $jquery->Dialog_Confirm('ResetColors', $user->lang['rp_resetctext'], "window.location = 'settings.php?colors=reset';"),
      'JS_RESET_ROLES'  => $jquery->Dialog_Confirm('ResetRoles', $user->lang['rp_reset_rolestext'], "window.location = 'settings.php?roles=reset';"),
      'JS_TRUNC_US'     => $jquery->Dialog_Confirm('TruncateUS', $user->lang['rp_truncate_warn'], "window.location = 'settings.php?usersettings=truncate';"),
      'JS_SAVE_MSG'     => ($_GET['save']) ? $jquery->HumanMsg($user->lang['rp_config_saved']) : '',
      'JS_NEWROLE'      => $jquery->Dialog_URL('RPnewRole', $user->lang['rp_role_new'], 'rolemanager.php', '500', '240'),
      'JS_EDITROLE'     => $jquery->Dialog_URL('RPeditRole', $user->lang['rp_edit_role2'], "rolemanager.php?editid='+editid+'", '500', '240'),
      
      // ColorPicker
      'JS_CP_STATUS0'   => $jquery->colorpicker('color_status0', $conf['color_status0']),
      'JS_CP_STATUS1'   => $jquery->colorpicker('color_status1', $conf['color_status1']),
      'JS_CP_STATUS2'   => $jquery->colorpicker('color_status2', $conf['color_status2']),
      'JS_CP_STATUS3'   => $jquery->colorpicker('color_status3', $conf['color_status3']),
      
      //update box
      'UPDATE_BOX'			=> $rpupdater->OutputHTML(),
      'UPDCHECK_BOX'		=> $rpvcheck->OutputHTML(),
      
      'L_HELP'          => $user->lang['rp_help'],
      'L_HELP_DESC'     => $user->lang['rp_help_desc'],
      'L_ALLEXPAND'			=> $user->lang['rp_expand_all'],
      'L_ALLCONTRACT'   => $user->lang['rp_contract_all'],
      'L_H_AUTOJOIN'		=> $user->lang['rp_header_autojoin'],
      'L_H_ADDRAID'			=> $user->lang['rp_header_addraid'],
      'L_HEAD_EXPERT'   => $user->lang['rp_header_expert'],
      'L_H_LAYOUT'      => $user->lang['rp_header_layout'],
      'L_H_WC_ROLL'     => $user->lang['rp_header_wcroll'],
      'L_H_GROUPS'      => $user->lang['rp_header_groups'],
      'L_H_TIME_DATE'		=> $user->lang['rp_header_time'],
      'L_H_MAIL'        => $user->lang['rp_header_mail'],
      'L_H_QUICK_SIGNIN'=> $user->lang['rp_header_qsignin'],
      'L_H_ROLE'        => $user->lang['rp_header_roles'],
      'L_H_AUTOMATIC'   => $user->lang['rp_header_automatics'],
      'L_H_USETTINGS'   => $user->lang['rp_header_usettings'],
      'L_H_NOTES'       => $user->lang['rp_header_notes'],
      'L_H_COLORS'      => $user->lang['rp_header_colors'],
      
      'L_UPDATE_CHECK'	=> $user->lang['rp_updatecheck'],
      'L_SHOW_RANKS'    => $user->lang['rp_show_ranks'],
      'L_COLORED_MEMBER'=> $user->lang['rp_colored_members'],
      'L_SEND_EMAIL'    => $user->lang['rp_send_email'],
      'L_MAIL_ACTIVE'   => $user->lang['rp_send_activesonly'],
      'L_ROLL_SYSTEM'   => $user->lang['rp_roll_system'],
      'L_WILDCARD_SYS'  => $user->lang['rp_wildcard_sys'],
      'L_LAST_X_DAYS'   => $user->lang['rp_last_x_days'],
      'L_RAID_LIST_MO' 	=> $user->lang['rp_mode_caption'],
      'L_RP_HORS_OFFSET'=> $user->lang['rp_hours_offset'],
      'L_RP_HORS_OFFSE2'=> $user->lang['rp_hours_offset2'],
      'L_RAID_DUR'      => $user->lang['rp_raid_duration'],
      'L_RAID_AB_CAL'		=> $user->lang['rp_cal_ab'],
      'L_RAID_CAL_STTD'	=> $user->lang['rp_cal_fweekday'],
      'L_RAID_CAL_HIC'	=> $user->lang['rp_cal_hide_ico'],
      'L_RAID_SORTR'		=> $user->lang['rp_sort_txt'],
      'L_RP_REPEAT_P1'	=> $user->lang['rp_repeat_value_p1'],
      'L_RP_REPEAT_P2'	=> $user->lang['rp_repeat_value_p2'],
      'L_REPEAT_ENABLE'	=> $user->lang['rp_repeat_enable'],
      'L_ENABLE_TEAM'		=> $user->lang['rp_enableteam'],
      'L_RP_DAYS_OFFSET'=> $user->lang['rp_daysoffset'],
      'L_SHOW_CLASSNME'	=> $user->lang['rp_short_classnames'],
      'L_ENABL_LEVELCP' => $user->lang['rp_enable_levelcap'],
      'L_HIDE_HIDDNRNK' => $user->lang['rp_hide_hiddenranks'],
      'L_ENBL_CLSBREAK' => $user->lang['rp_enable_classbrk'],
      'L_OFFICERS'      => $user->lang['rp_enable_officers'],
      'L_ENBL_GROUPS'   => $user->lang['rp_enable_groups'],
      'L_RAID_CAL_HRN'	=> $user->lang['rp_hide_raidname'],
      'L_TIMEZONE'	    => $user->lang['rp_timezone_offset'],
      'L_TZ_CHECK'      => $user->lang['rp_timezone_check'].' '.$stime->DoDate($conf['timeformats']['medium'], $stime->DoTime()),
      'L_RESETDAY'      => $user->lang['rp_resetday'],
      'L_ENABLE_RESETDA'=> $user->lang['rp_enableresetday'],
      'L_HIDE_NOTSIGND' => $user->lang['rp_hidenorsigned'],
      'L_HIDEROWS'      => $user->lang['rp_hiderows'],
      'L_STATUS0'       => $user->lang['status0'],
      'L_STATUS1'       => $user->lang['status1'],
      'L_STATUS2'       => $user->lang['status2'],
      'L_STATUS3'       => $user->lang['status3'],
      'L_STATUS4'       => $user->lang['status4'],
      'L_WCEXPIRE'      => $user->lang['rp_wcexpire'],
      'L_ROLLTOOLTIP'   => $user->lang['rp_rolltooltip'],
      'L_SAVEGROUPS'    => $user->lang['rp_savegroups'],
      'L_GUESTS'        => $user->lang['rp_useguests'],
      'L_ADMINNOTES'		=> $user->lang['rp_adminnotes'],
      'L_GROUPBYEVENT'  => $user->lang['rp_saveperevent'],
      'L_DBVERSION'     => $user->lang['rp_dbversion'],
      'L_RESETUSERSETT' => $user->lang['rp_flush_usersett'],
      'L_FORCE_UPDATE'  => $user->lang['rp_force_update'],
			'L_BUTTON_CANCEL'	=> $user->lang['rp_button_cancel'],
			'L_DOTRUNC'       => $user->lang['rp_button_reset'],
			'L_DISABLE_CMNOT' => $user->lang['rp_disable_cmnotes'],
			'L_DSTCORRECT'    => $user->lang['rp_dstcheck'],
			'L_12HFORMAT'			=> $user->lang['rp_12hourformat'],
			'L_AUTODET_BUTTN'	=> $user->lang['rp_autodet_buttn'],
			'L_DISABL_CL_AC'	=> $user->lang['rp_disbale_officer_ac'],
			'L_DISABLE_NOTE'	=> $user->lang['rp_disable_memnote'],
			'L_DATEFORMAT'    => $user->lang['rp_time_format'],
			'L_COLLATECHK'    => $user->lang['rp_collatecheck'],
			'L_CHECK'         => $user->lang['rp_check_collation'],
			'L_HIDE_MNOTES_G' => $user->lang['rp_hide_memnotes_guest'],
			'L_AUTO_ADDMEM'   => $user->lang['rp_autoadd_byrank'],
			'L_CONFIRMED'     => $user->lang['rp_autoadd_confirm'],
			'L_CHANGE_STATUS' => $user->lang['rp_change_signedstatus'],
			'L_TIMETOCHANGE'  => $user->lang['rp_change_possible_time'],
			'L_HIDE_VERSION'  => $user->lang['rp_hide_rpversion'],
			'L_REMOVE_LOCK'   => $user->lang['rp_remove_lock'],
			'L_PREF_ADDRAID'  => $user->lang['rp_prefix_addraid'],
			'L_CONF_ACTIVE'   => $user->lang['rp_conf_active'],
			'L_CONF_RANK'     => $user->lang['rp_conf_rank'],
			'L_SHOW_COMMENTS' => $user->lang['rp_use_comments'],
			'L_STATUSCOLORS'  => $user->lang['rp_status_colors'],
			'L_CLASSCOLORS'   => $user->lang['rp_class_colors'],
			'L_DISTRI_SELECT' => $user->lang['rp_distri_select'],
			'L_RESET_COLOR'   => $user->lang['rp_reset_colors'],
			'L_MAIL_METHOD'   => $user->lang['rp_mail_method'],
			'L_MAIL_SETTINGS' => $user->lang['rp_mail_settings'],
			'L_SMTP_USER'     => $user->lang['rp_smtp_user'],
			'L_SMTP_PASSWORD' => $user->lang['rp_smtp_password'],
			'L_SMTP_HOST'     => $user->lang['rp_smtp_host'],
			'L_SMTP_AUTH'     => $user->lang['rp_smtp_auth'],
			'L_SENDMAIL_PATH' => $user->lang['rp_sendmail_path'],
			'L_EMAIL_SENDER'  => $user->lang['rp_sender_address'],
			'L_EMAIL_SENDNAM' => $user->lang['rp_sender_name'],
			'L_DEFRAIDSORT'   => $user->lang['rp_default_raid_sort'],
			'L_PHPINI_VALUE'  => $user->lang['rp_phpini_value'],
			'L_SENDCONFIMAIL' => $user->lang['rp_mail_send_confirmation'],
			'L_AUTOADDFLEX'   => $user->lang['rp_autoadd_flexible'],
			'L_EDIT'          => $user->lang['rp_edit_role'],
			'L_ADDROLE'       => $user->lang['rp_add_role'],
			'L_CLASSES'       => $user->lang['rp_role_classes'],
			'L_ROLENAMES'     => $user->lang['rp_role_names'],
			'L_ROLEID'        => $user->lang['rp_role_id'],
			'L_RESET_ROLES'   => $user->lang['rp_reset_roles'],
			'L_US_EMAIL'      => $user->lang['rp_us_email'],
			'L_US_LAYOUT'     => $user->lang['rp_us_layout'],
			'L_US_TIME'       => $user->lang['rp_us_time'],
			'L_USERSETTINGS'  => $user->lang['rp_user_settings'],
			'L_CACHE_UNWRTBL' => $user->lang['rp_cache_unwriteable'],
			'L_CMAPI_TITLE'   => $user->lang['rp_cmapi_title'],
			'L_CMAPI_NOTFOND' => $user->lang['rp_cmapi_notfound'],
			'L_CMAPI_TOO_OLD' => $user->lang['rp_cmapi_too_old'],
			'L_CMAPI_VERSION' => $user->lang['rp_cmapi_version'],
			'L_CM_VERSION'    => $user->lang['rp_cm_version'],
			'L_CMAPI_STATUS'  => $user->lang['rp_cmapi_status'],
			'L_CMAPI_ALTIMG'  => $user->lang['rp_cmapi_'.$cmapi_status],
      ) 
		);
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['config']),
			'template_path' 	       => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/settings.html',
			'display'                => true)
    );
?>
