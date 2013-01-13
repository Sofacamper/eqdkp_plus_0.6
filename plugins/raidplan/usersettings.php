<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: usersettings.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../';
include_once('includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }
if ($user->data['username']=="") { message_die('User is not loegged in'); }
$rpdb = new AdditionalDB('raidplan_userconfig');

$user->check_auth('u_raidplan_view');

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);
  
  if($_POST['rreset']){
  	$delsql = "DELETE FROM ".RP_USETTING_TABLE." WHERE user_id= '".$user->data['user_id']."'";
    $db->query($delsql);
  	redirect('plugins/'.PLUGIN.'/usersettings.php'.$SID);
  }
  
  if($_GET['mode']== 'save'){
  	ob_start();
  	
  	// whitelist
  	$savewhitelist = array('rp_cal_startday','rp_mode','rp_cal_ab','rp_dstcheck',
                          'rp_timezone','rp_time_12h','rp_date_format','rp_send_email');
  	
		// save me
		foreach($_POST as $vname=>$vvalue){
      $checkstring = explode("_",$vname);
      if($checkstring[0] == 'rp' && in_array($vname, $savewhitelist)){
        $savearray[$vname]  = $vvalue;
      }
    }
    if ($rpdb->UpdateConfig($savearray, $rpdb->CheckDBFields('config_name', $user->data['user_id']), $user->data['user_id'])){
      redirect('plugins/'.PLUGIN.'/usersettings.php'.$SID.'&save=true');  // redirect
    }else{
      $is_saved = false;
    }
    ob_get_contents();
  }elseif($_GET['mode']== 'saveChar'){
    ob_start();
    $sql = 'DELETE FROM '.RP_SAVEDUSER_TABLE." WHERE user_id='".$user->data['user_id']."'";
      $db->query($sql);
      $query = $db->build_query('INSERT', array(
				'member_id'     				=> stripslashes($_POST['member_id']),
				'user_id'     				  => $user->data['user_id'],
				'role'                  => $_POST['rp_role'],
			));
			$db->query('INSERT INTO ' . RP_SAVEDUSER_TABLE . $query);
			if($_POST['fpopup']){
        echo "<script>parent.window.location.href = 'viewraid.php".$SID."&r=".$_POST['fpopup']."';</script>";
      }else{
			 redirect('plugins/'.PLUGIN.'/usersettings.php'.$SID.'&save=char');
			 ob_get_contents();
			}
  }
  
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

  // start day
  $sddropdwn = array(
										'monday' => $user->lang['rp_monday'],
										'sunday' => $user->lang['rp_sunday'],
										);
  
	foreach ($rpclass->RoleConf() as $myroles){
    $roledropdwn[$myroles['name_en']] = $myroles['name'];
	}

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
  	
  	// Autodetect Timezone Value
  	$dstvalue   = (date('I', $stime->DoTime()) == 1 && $conf['rp_dstcheck'] == 1) ? 1 : 0;
    $tzvalue    = date('H.i', $stime->DoTime()) - (gmdate('H.i', $stime->DoTime())+$dstvalue);
    $tzvalue    = str_replace(',', '.', $tzvalue);
  	
  	// the code for the char settings
  	//get the users
  if ($user->data['user_id']){	  
			$sql =  "SELECT users.user_id, members.member_id, ranks.rank_id, members.member_name, classes.class_name, classes.class_id
							FROM (" . MEMBERS_TABLE . " as members, " . MEMBER_USER_TABLE . " as users, " . CLASS_TABLE . " as classes, ".MEMBER_RANKS_TABLE." as ranks)
              WHERE members.member_class_id=classes.class_id
			        AND members.member_id=users.member_id
							AND members.member_rank_id = ranks.rank_id
							AND users.user_id=" . $user->data['user_id'] ."
							ORDER BY members.member_name DESC";
			$result = $db->query($sql);
			while ($row = $db->fetch_record($result)){
				$members[$row['member_id']] = array(
					'id'					=> $row['member_id'],
					'name'				=> $row['member_name'],
					'class_name'	=> $row['class_name'],
					'class_id'    => $row['class_id'],
					'rank'				=> $row['rank_id']);
			}
			$db->free_result($result);
		}
  
			$jsmemcode = 'var values = new Array();';
		if(is_array($members) && !empty($members)){
		  $nochars = false;
			foreach ($members as $membercheck){
          $ssql = "SELECT member_id, role FROM ".RP_SAVEDUSER_TABLE." WHERE user_id='".$user->data['user_id']."'";
          $result = $db->query($ssql);
          $row = $db->fetch_record($result);

          // fill the "What role can i be"-Array
          $whatcanibe = array();
          foreach($rpclass->RoleConf() as $rolearray){
  				  if(in_array($membercheck['class_id'], $rolearray['classes'])){
              $whatcanibe[$rolearray['name_en']] = $roledropdwn[$rolearray['name_en']];
            }
          }
          
          // Generate the JS Code
          $jsmemcode .= 'values["'.$membercheck['id'].'"] = new Array(';
          if(is_array($whatcanibe) && !empty($whatcanibe)){
            $tmpjsclass = array();
            foreach ($whatcanibe as $k => $v){
              $tmpjsclass[] = 'new Array("'.strtolower($k).'","'.$v.'")';
            }
            $jsmemcode .= implode(",", $tmpjsclass);
          }
          $jsmemcode .= ');';

				
        // Generate the Dropdown values
        $drpdwn_members[$membercheck['id']] = $membercheck['name'].' ['.$membercheck['class_name'].']';
        $drpdwn_members_select = (!$drpdwn_members_select) ? $membercheck['id'] : $drpdwn_members_select;
				$drpdwn_members_select = ($row['member_id'] ==  $membercheck['id']) ? $membercheck['id'] : $drpdwn_members_select;
				$dropdown_role_select  = $row['role'];
			}
		}else{
      $nochars = true;
    }
  	
  	// Charmanager hook :)
		 if($charmanager_installed){
		 		$outp_no_user_assigned = $user->lang['rp_no_user_assigned_cm1'].
		 															'<a href="'.$eqdkp_root_path .'plugins/charmanager/index.php">'.$user->lang['rp_no_user_assigned_cm2'].'</a> '.
		 															$user->lang['rp_no_user_assigned_cm3'];
		 }else{
		 		$outp_no_user_assigned 	= $user->lang['rp_no_user_assigned'];
		 }
		// end of Charmanager Hook
  	
  	$msg_txt = ($_GET['save'] == 'char') ? $user->lang['rp_char_saved'] : $user->lang['rp_config_saved'];
  	
    $tpl->assign_vars(array(
              'F_USERSETTINGS'				=> 'usersettings.php'.$SID.'&mode=save',
              'F_CHARSETTINGS'				=> 'usersettings.php'.$SID.'&mode=saveChar',
              'DRDWN_MODE'						=> $khrml->DropDown('rp_mode', $modedropdwn, $conf['rp_mode'], '', '', 'input'),
              'DRDWN_SD'							=> $khrml->DropDown('rp_cal_startday', $sddropdwn, $conf['rp_cal_startday'], '', '', 'input'),
     					'DRDWN_AB'							=> $khrml->DropDown('rp_cal_ab', $abdropdwn, $conf['rp_cal_ab'], '', '', 'input'),
              'DRDWN_TZONE'		  			=> $khrml->DropDown('rp_timezone', $time_select, $tsoffset, '', '', 'input'),
              'DRDWN_DFORMAT'		  		=> $khrml->DropDown('rp_date_format', $datedropdwn, $conf['rp_date_format'], '', '', 'input'),
              'DRDWN_ROLE'			      => $khrml->DropDown('rp_role', $roledropdwn, $dropdown_role_select, '', 'id="rp_role"', 'input'),
              'DRDWN_MEMBERS'			    => $khrml->DropDown('member_id', $drpdwn_members, $drpdwn_members_select, '', 'onChange="changeVal(this.value);"', 'input'),
              
              'DSTCHECK'	      			=> $khrml->RadioBox('rp_dstcheck', false , $conf['rp_dstcheck']),
              '12HFORMAT'	      			=> $khrml->RadioBox('rp_time_12h', false , $conf['rp_time_12h']),
              'SENDEMAIL'             => $khrml->RadioBox('rp_send_email', false , $conf['rp_send_email']),
              'TZVALUE'               => $tzvalue,
              'FPOPUP'                => $_GET['fpopup'],
              
              // JS Code
		          'JS_ABOUT'              => $jquery->Dialog_URL('About', $user->lang['rp_about_header'], 'about.php', '380', '320'),
              'JS_CONFIRM_DEL'        => $jquery->Dialog_Confirm('openConfirmDialog', $user->lang['rp_confirm_reset_conf'], "document.getElementById('rreset').value='defaults';document.post.submit();"),
              'JS_SAVE_MSG'           => ($_GET['save']) ? $jquery->HumanMsg($msg_txt) : '',
              
              //CSS
              'S_NO_CHARS'            => $nochars,
              'RP_TEMPLATEPATH'       => $user->style['template_path'],
              
              // Show...
              'SHOW_ALL'              => (!$_GET['fpopup']) ? true : false,
              'SHOW_EMAILSETTINGS'    => $rpdb->isChecked($conf['rp_us_email']),
              'SHOW_LAYOUTSETTINGS'   => $rpdb->isChecked($conf['rp_us_layout']),
              'SHOW_TIMESETTINGS'     => $rpdb->isChecked($conf['rp_us_time']),
              'SHOW_ENGLISHSETTINGS'  => $conf['timeformats']['specialsettings'],
              'SHOW_CHARSETTINGS'     => ($nochars) ? false : true,
              
              'HELP_DSTCHECK'         => $khrml->HelpTooltip($user->lang['rp_dsthelp']),
              'L_TZ_CHECK'      			=> $user->lang['rp_timezone_check'].' '.$stime->DoDate($conf['timeformats']['medium'], $stime->DoTime()),
              'NO_USER_ASSIGNED'      => $outp_no_user_assigned,
              
              // JS
              'JS_MEMCODE'            => $jsmemcode,
              'JS_START_ID'           => $drpdwn_members_select,
              'JS_ROLE'               => $dropdown_role_select,
              
              // Headers
              'L_H_MAIL'              => $user->lang['rp_header_mail'],
              'L_H_LAYOUT'            => $user->lang['rp_header_layout'],
              'L_H_TIME_DATE'		      => $user->lang['rp_header_time'],
              'L_H_ENSETTINGS'        => $user->lang['rp_header_ensettings'],
              
              // Language Strings
              'L_DEF_CHARACTER'       => $user->lang['rp_defchar_config'],
              'L_MEMBERS'             => $user->lang['members'],
              'L_ROLE'                => $user->lang['rp_addition_role'],
              'L_SUBMIT'        			=> $user->lang['submit'],
              'L_RAIDLIST'						=> $user->lang['rp_header_vrserttings'],
              'L_RAID_CAL_STTD'				=> $user->lang['rp_cal_fweekday'],
              'L_RAID_SORTR'					=> $user->lang['rp_sort_txt'],
              'L_RAID_LIST_MO' 				=> $user->lang['rp_mode_caption'],
              'L_RAID_CAL_HIC'				=> $user->lang['rp_cal_hide_ico'],
              'L_RAID_AB_CAL'   			=> $user->lang['rp_cal_ab'],
              'L_DSTCORRECT'    			=> $user->lang['rp_dstcheck'],
              'L_TIMEZONE'	    			=> $user->lang['rp_timezone_us'],
              //'L_USETTINGS_OFF'				=> $user->lang['rp_usettings_off'],
              'L_12HFORMAT'						=> $user->lang['rp_12hourformat'],
              'L_AUTODET_BUTTN'				=> $user->lang['rp_autodet_buttn'],
              'L_DATEFORMAT'          => $user->lang['rp_time_format'],
              'L_RESET_DEFAULT'				=> $user->lang['rp_reset_defaults'],
              'L_SENDEMAIL'           => $user->lang['rp_user_receivemail'],
              'L_CREDITS'             => $user->lang['rp_credits'],
							'L_COPYRIGHT'           => $rpclass->Copyright(),
		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_title_usersettings']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'usersettings.html',
			'gen_simple_header'      => (!$_GET['fpopup']) ? false : true,
			'display'                => true)
    );
?>
