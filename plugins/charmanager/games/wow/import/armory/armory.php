<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2011-09-22 21:55:41 +0200 (Thu, 22 Sep 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 11314 $
 * 
 * $Id: armory.php 11314 2011-09-22 19:55:41Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../../';
include_once($eqdkp_root_path .'/plugins/charmanager/include/common.php');

# i know its bad, but as i do not want to spent hours to get it work again…... here a workaround...
function genHiddenInput($name, $input){
	return "<input name='".$name."' value=\"".$input."\" type='hidden'>\n";
}
# end of workarounds

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
			$hmtlout .= genHiddenInput('servername',stripslashes($conf['uc_servername']));
		}else{
			$hmtlout .= '<br/>'.$user->lang['uc_servername'].' <input name="servername" size="15" maxlength="50" value="'.$temp_svname.'" class="input" type="text">';
		}	
		if($conf['uc_server_loc']){
			$hmtlout .= genHiddenInput('server_loc', $conf['uc_server_loc']);
		}else{
			$hmtlout .= "<br/>".$user->lang['uc_server_loc'].$khrml->DropDown('server_loc', $armory->GetLocs(), '', '', '', 'input');
		}
		$hmtlout .= '<br/><input type="submit" name="submiti" value="'.$user->lang['uc_import_forw'].'" class="mainoption" />';
  	$hmtlout .= '</form>';
}elseif($in->get('step', 0) == '1'){
	$isindatabase = '0';
	if($in->get('member_id', 0) > 0){
		// We'll update an existing one...
		$isindatabase	= $in->get('member_id', 0);
		$memberdetails	= $db->fetch_record($db->query('SELECT m.member_name, mu.user_id FROM __members m, __member_user mu WHERE m.member_id='.$db->sql_escape($in->get('member_id', 0)).' AND m.member_id=mu.member_id'));
		$isMemberName	= $memberdetails['member_name'];
		$isServerName	= stripslashes($conf['uc_servername']);
		$isServerLoc	= $conf['uc_server_loc'];
		$is_mine		= ($memberdetails['user_id'] == $user->data['user_id']) ? true : false;
	}else{
		// Check for existing member name
		$tmp_member	= $db->query_first("SELECT member_id FROM __members WHERE member_name='{$db->sql_escape($in->get('charname'))}'");
		
		// Check if its mine! -- Security thing to prevent highjacking of chardata
		$is_mine = false;
		if($user->check_auth('a_charmanager_config', false)){
			// We are an administrator, its always mine..
			$is_mine	= true;
		}else{
			// we are a normal user
			$tmp_mine	= $db->query_first("SELECT user_id FROM __member_user WHERE member_id='{$tmp_member['member_id']}'");
			$is_mine	= (($tmp_mine['user_id'] > 0) ? (($tmp_mine['user_id'] == $user->data['user_id']) ? true : false) : true);
		}
		$isindatabase	= $tmp_member['member_id'];
		$isMemberName	= $_POST['charname'];
		$isServerName	= $_POST['servername'];
		$isServerLoc	= $_POST['server_loc'];
	}
	
	if($is_mine){
		// Load the Armory Data
		$armory		= new bnet_armory($isServerLoc, 'de_de');
		if($_HMODE){
			$armory->setSettings(array('apiKeyPrivate'=>$bnetapi_private, 'apiKeyPublic'=>$bnetapi_public));
		}
		$chardata	= $armory->character($isMemberName, stripslashes($isServerName), true);

		$hmtlout = '<style type="text/css">
									p.info { border:1px solid red; background-color:#E0E0E0; padding:4px; margin:0px; }
								</style>'; 
		$hmtlout .= '<form name="settings" method="post" action="armory.php?step=2">';
		// Basics
		$hmtlout .= genHiddenInput('member_id',    $isindatabase);
		$hmtlout .= genHiddenInput('member_name',  $isMemberName);
		$hmtlout .= genHiddenInput('member_level', $chardata['level']);
		$hmtlout .= genHiddenInput('gender',       (($chardata['gender'] == 1) ? 'Female' : 'Male'));
		#$hmtlout .= genHiddenInput('faction',      (($armdata['factionid'] == 1) ? 'Horde' : 'Alliance'));
		$hmtlout .= genHiddenInput('member_race_id',(($armory->ConvertID($chardata['race'], 'int', 'races') > 0) ? $armory->ConvertID($chardata['race'], 'int', 'races') : 0));
		$hmtlout .= genHiddenInput('member_class_id',(($armory->ConvertID($chardata['class'], 'int', 'classes') > 0) ? $armory->ConvertID($chardata['class'], 'int', 'classes') : 0));
		$hmtlout .= genHiddenInput('guild',        utf8_decode($chardata['guild']['name']));
		$hmtlout .= genHiddenInput('last_update',  ($chardata['lastModified']/ 1000));

		// Bars
		$hmtlout .= genHiddenInput('health_bar',   $chardata['stats']['health']);
		$hmtlout .= genHiddenInput('second_bar',   $chardata['stats']['power']);
		$hmtlout .= genHiddenInput('second_name',  $chardata['stats']['powerType']);

		$hmtlout .= genHiddenInput('prof1_value', (($chardata['professions']['primary'][0]['rank']) ? $chardata['professions']['primary'][0]['rank'] : ''));
		$hmtlout .= genHiddenInput('prof1_name', (($chardata['professions']['primary'][0]['name']) ? $CharTools->convertEncoding($chardata['professions']['primary'][0]['name'], 'decode') : ''));
		$hmtlout .= genHiddenInput('prof2_value', (($chardata['professions']['primary'][1]['rank']) ? $chardata['professions']['primary'][1]['rank'] : ''));
		$hmtlout .= genHiddenInput('prof2_name', (($chardata['professions']['primary'][1]['name']) ? $CharTools->convertEncoding($chardata['professions']['primary'][1]['name'], 'decode') : ''));

		// Skills
	  	$hmtlout .= genHiddenInput('skill_1',	(($chardata['talents'][0]['trees'][0]['total'] > 0) ? $chardata['talents'][0]['trees'][0]['total'] : 0));
	  	$hmtlout .= genHiddenInput('skill_2',	(($chardata['talents'][0]['trees'][1]['total'] > 0) ? $chardata['talents'][0]['trees'][1]['total'] : 0));
	  	$hmtlout .= genHiddenInput('skill_3',	(($chardata['talents'][0]['trees'][2]['total'] > 0) ? $chardata['talents'][0]['trees'][2]['total'] : 0));
		$hmtlout .= genHiddenInput('skill2_1',	(($chardata['talents'][1]['trees'][0]['total'] > 0) ? $chardata['talents'][1]['trees'][0]['total'] : 0));
		$hmtlout .= genHiddenInput('skill2_2',	(($chardata['talents'][1]['trees'][1]['total'] > 0) ? $chardata['talents'][1]['trees'][1]['total'] : 0));
		$hmtlout .= genHiddenInput('skill2_3',	(($chardata['talents'][1]['trees'][2]['total'] > 0) ? $chardata['talents'][1]['trees'][2]['total'] : 0));
		$hmtlout .= genHiddenInput('debug', $armory->ConvertID($chardata['race'], 'int', 'races'));

		// viewable Output
		if(!isset($chardata['status'])){
		$hmtlout .= sprintf($user->lang['uc_charfound'], $isMemberName).'<br>';
		$hmtlout .= sprintf($user->lang['uc_charfound2'], date('d.m.Y', ($chardata['lastModified']/ 1000)));
		$hmtlout .= '<br/><p class="info">'.$user->lang['uc_charfound3'].'</p>';
		if(!$isindatabase){
			if($user->check_auth('u_charmanager_connect', false)){
				$hmtlout .= '<br/><input type="checkbox" name="overtakeuser" value="1" checked="checked" />';
			}else{
	   		$hmtlout .= '<br/><input type="checkbox" name="overtakeuser" value="1" disabled="disabled" checked="checked" />';
	  		$hmtlout .= genHiddenInput('overtakeuser','1');
	  	}
	  	$hmtlout .= ' '.$user->lang['overtake_char'];
	  }
		$hmtlout .= '<center><input type="submit" name="submiti" value="'.$user->lang['uc_prof_import'].'" class="mainoption"></center>';
	  }else{
			$hmtlout .= $chardata['reason'];
		}
		$hmtlout .= '</form>';
	}else{
		$hmtlout = '<style type="text/css">
									p.info { border:1px solid red; background-color:#E0E0E0; padding:4px; margin:0px; }
								</style>';
		$hmtlout .= '<br/><p class="info">'.$user->lang['uc_notyourchar'].'</p>';
	}
}elseif($in->get('step',0) == '2'){
  // insert the Data
  $info			= $CharTools->updateChar($_POST['member_id'], $_POST['member_name'], '', true);
  $hmtlout	= ($info[0] == true) ? $user->lang['uc_upd_succ'].' (ID: '.$_POST['member_id'].')' : $user->lang['uc_imp_failed'].' (ID: '.$_POST['member_id'].')';
}
echo $hmtlout;
?>
