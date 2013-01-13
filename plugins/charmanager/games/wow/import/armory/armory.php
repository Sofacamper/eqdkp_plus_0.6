<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-08-17 16:46:58 +0200 (Mo, 17 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5703 $
 * 
 * $Id: armory.php 5703 2009-08-17 14:46:58Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../../';
include_once($eqdkp_root_path .'/plugins/charmanager/include/common.php');

// init the Armory Loader
$armory = new ArmoryChars("вов");

if(!$in->get('step')){
  $myServer = ($conf['uc_servername']) ? stripslashes($conf['uc_servername']) : '';
  if($in->get('member_id', 0) > 0){
  	$tmpmemname = $db->query_first('SELECT member_name FROM __members WHERE member_id='.$db->sql_escape($in->get('member_id', 0)));
  	$myMembername = ($tmpmemname) ? $tmpmemname : '';
  }

		// generate output
		$hmtlout .= ' <form name="settings" method="post" action="armory.php?step=1">';
		$hmtlout .= $user->lang['uc_charname'].' <input name="charname" size="15" maxlength="50" value="'.$myMembername.'" class="input" type="text">';
		if($conf['uc_lockserver'] == 1){
			$hmtlout .= ' @'.stripslashes($conf['uc_servername']).'<br/>';
			$hmtlout .= $armory->genHiddenInput('servername',stripslashes($conf['uc_servername']));
		}else{
			$hmtlout .= '<br/>'.$user->lang['uc_servername'].' <input name="servername" size="15" maxlength="50" value="'.$temp_svname.'" class="input" type="text">';
		}	
		if($conf['uc_server_loc']){
			$hmtlout .= $armory->genHiddenInput('server_loc', $conf['uc_server_loc']);
		}else{
			$hmtlout .= "<br/>".$user->lang['uc_server_loc'].$khrml->DropDown('server_loc', $armory->GetLocs(), '', '', '', 'input');
		}
		$hmtlout .= '<br/><input type="submit" name="submiti" value="'.$user->lang['uc_import_forw'].'" class="mainoption" />';
  	$hmtlout .= '</form>';
}elseif($in->get('step', 0) == '1'){
	$isindatabase = '0';
	if($in->get('member_id', 0) > 0){
		// We'll update an existing one...
		$isindatabase = $in->get('member_id', 0);
		$isMemberName	= $db->query_first('SELECT member_name FROM __members WHERE member_id='.$db->sql_escape($in->get('member_id', 0)));
		$isServerName	= stripslashes($conf['uc_servername']);
		$isServerLoc	= $conf['uc_server_loc'];
	}else{
		// Check for existing member name
		$memb = $db->query_first("SELECT member_id FROM __members WHERE member_name = '".$db->sql_escape($in->get('charname'))."'");
		$isindatabase = $memb['member_id'];
		$isMemberName	= $_POST['charname'];
		$isServerName	= $_POST['servername'];
		$isServerLoc	= $_POST['server_loc'];
	}

	// Load the Armory Data
	$chardata = $armory->GetCharacterData($isMemberName,stripslashes($isServerName),$isServerLoc, 'de_de');
	echo $_POST['charname'];
  $arm_data = $armory->BuildMemberArray($chardata[0]);
	$hmtlout = '<style type="text/css">
								p.info { border:1px solid red; background-color:#E0E0E0; padding:4px; margin:0px; }
							</style>'; 
	$hmtlout .= '<form name="settings" method="post" action="armory.php?step=2">';
	// Basics
	$hmtlout .= $armory->genHiddenInput('member_id',    $isindatabase);
	$hmtlout .= $armory->genHiddenInput('member_name',  $isMemberName);
	$hmtlout .= $armory->genHiddenInput('member_level', $arm_data['level']);
	$hmtlout .= $armory->genHiddenInput('gender',       (($arm_data['genderid'] == 1) ? 'Female' : 'Male'));
	$hmtlout .= $armory->genHiddenInput('faction',      (($arm_data['factionid'] == 1) ? 'Horde' : 'Alliance'));
	$hmtlout .= $armory->genHiddenInput('member_race_id',(($arm_data['race_eqdkp']) ? $arm_data['race_eqdkp'] : 0));
	$hmtlout .= $armory->genHiddenInput('member_class_id',(($arm_data['class_eqdkp']) ? $arm_data['class_eqdkp'] : 0));
	$hmtlout .= $armory->genHiddenInput('guild',        utf8_decode($arm_data['guildname']));
	$hmtlout .= $armory->genHiddenInput('last_update',  $armory->Date2Timestamp($arm_data['lastmodified']));
	
	// Resistances
	$hmtlout .= $armory->genHiddenInput('fire',         $arm_data['resistances']->fire['value']);
	$hmtlout .= $armory->genHiddenInput('nature',       $arm_data['resistances']->nature['value']);
	$hmtlout .= $armory->genHiddenInput('shadow',       $arm_data['resistances']->shadow['value']);
	$hmtlout .= $armory->genHiddenInput('arcane',       $arm_data['resistances']->arcane['value']);
	$hmtlout .= $armory->genHiddenInput('ice',          $arm_data['resistances']->frost['value']);
	
	// Bars
	$hmtlout .= $armory->genHiddenInput('health_bar',   $arm_data['characterbars']->health['effective']);
	$hmtlout .= $armory->genHiddenInput('second_bar',   $arm_data['characterbars']->secondBar['effective']);
	$hmtlout .= $armory->genHiddenInput('second_name',  $arm_data['characterbars']->secondBar['type']);
	
	$iii=1;$profarry = array();
	if(is_array($arm_data)){
  	foreach($arm_data['professions']->children() as $professions){
      $profarry[$iii]['name'] 	= $professions['key'];
      $profarry[$iii]['value'] 	= $professions['value'];
      $iii++;
    }
		$hmtlout .= $armory->genHiddenInput('prof1_value', $profarry[1]['value']);
		$hmtlout .= $armory->genHiddenInput('prof1_name', utf8_decode($profarry[1]['name']));
		$hmtlout .= $armory->genHiddenInput('prof2_value', $profarry[2]['value']);
		$hmtlout .= $armory->genHiddenInput('prof2_name', utf8_decode($profarry[2]['name']));
		
		// Skills
  	$hmtlout .= $armory->genHiddenInput('skill_1',	$arm_data['spec1']['treeOne']);
  	$hmtlout .= $armory->genHiddenInput('skill_2',	$arm_data['spec1']['treeTwo']);
  	$hmtlout .= $armory->genHiddenInput('skill_3',	$arm_data['spec1']['treeThree']);
		$hmtlout .= $armory->genHiddenInput('skill2_1',	(($arm_data['dualspec']) ? $arm_data['spec2']['treeOne'] : '0'));
	  $hmtlout .= $armory->genHiddenInput('skill2_2',	(($arm_data['dualspec']) ? $arm_data['spec2']['treeTwo'] : '0'));
	  $hmtlout .= $armory->genHiddenInput('skill2_3',	(($arm_data['dualspec']) ? $arm_data['spec2']['treeThree'] : '0'));
	}else{
		$hmtlout .= $armory->genHiddenInput('prof1_value', '');
		$hmtlout .= $armory->genHiddenInput('prof1_name', '');
		$hmtlout .= $armory->genHiddenInput('prof2_value', '');
		$hmtlout .= $armory->genHiddenInput('prof2_name', '');
	}
	$hmtlout .= $armory->genHiddenInput('debug', $arm_data['race_eqdkp']);
	// viewable Output
	if(is_array($arm_data)){
	$hmtlout .= sprintf($user->lang['uc_charfound'], $isMemberName).'<br>';
	$hmtlout .= sprintf($user->lang['uc_charfound2'], date('d.m.Y', $armory->Date2Timestamp($arm_data['lastmodified'])));
	$hmtlout .= '<br/><p class="info">'.$user->lang['uc_charfound3'].'</p>';
	if(!$isindatabase){
		if($user->check_auth('u_charmanager_connect', false)){
			$hmtlout .= '<br/><input type="checkbox" name="overtakeuser" value="1" checked="checked" />';
		}else{
   		$hmtlout .= '<br/><input type="checkbox" name="overtakeuser" value="1" disabled="disabled" checked="checked" />';
  		$hmtlout .= $armory->genHiddenInput('overtakeuser','1');
  	}
  	$hmtlout .= ' '.$user->lang['overtake_char'];
  }
	$hmtlout .= '<center><input type="submit" name="submiti" value="'.$user->lang['uc_prof_import'].'" class="mainoption"></center>';
	}elseif($arm_data == 'old_char'){
    $hmtlout .= $user->lang['uc_notyetupdated'];
  }else{
		$hmtlout .= $user->lang['uc_noprofile_found'];
	}
	$hmtlout .= '</form>';
}elseif($in->get('step',0) == '2'){
  // insert the Data
  $info			= $CharTools->updateChar($_POST['member_id'], $_POST['member_name'], '', true);
  $hmtlout	= ($info[0] == true) ? $user->lang['uc_upd_succ'].' (ID: '.$_POST['member_id'].')' : $user->lang['uc_imp_failed'].' (ID: '.$_POST['member_id'].')';
}
echo $hmtlout;
?>
