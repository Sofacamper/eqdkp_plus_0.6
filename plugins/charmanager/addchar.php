<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:05:50 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 12157 $
 * 
 * $Id: addchar.php 12157 2012-09-30 20:05:50Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../';
include_once('include/common.php');
include_once('include/tabs.class.php');
$mode['edit'] = $mode['update'] = false;

// Permissions
if (!$user->check_auth('u_charmanager_manage', false) && !$user->check_auth('u_charmanager_add', false)) {
	message_die($user->lang['uc_no_prmissions']);
}
if (!$pm->check(PLUGIN_INSTALLED, 'charmanager')) {
  message_die($user->$lang['uc_not_installed']);
}
if ($user->data['username']==""){
  message_die($user->lang['uc_not_loggedin']);
}

// File Upload..
$cmupload = new AjaxImageUpload;
if($in->get('performupload') == 'true'){
  $cmupload->PerformUpload('member_pic', 'charmanager', 'upload');
  die();
}

// save the data
if ($in->get('member_name') != ''){
  // Save the new Char...
  if($in->get('add') != ''){
    $info = $CharTools->updateChar('', sanitize($in->get('member_name')));
    if($info == 'not_your_char'){
    	$hmtlout = '<style type="text/css">
									p.info { border:1px solid red; background-color:#E0E0E0; padding:4px; margin:0px; }
								</style>';
			$hmtlout .= '<br/><p class="info">'.$user->lang['uc_notyourchar'].'</p>';
    	die($hmtlout);
    }else{
    	echo "<script>parent.window.location.href = 'index.php';</script>";
    }
  }else{
    $info = $CharTools->updateChar($in->get('memberid',0));
    echo "<script>parent.window.location.href = 'index.php';</script>";
  }
  
}

// Fill with data?
if ($in->get('editid', 0) > 0){
  $mode['edit']   = true;
  $MyMemberID     = $in->get('editid', 0);
}

// Read the Data
if($MyMemberID > 0){
  $sql = "SELECT m.*, ma.* FROM __members m
          LEFT JOIN __member_additions ma ON (ma.member_id=m.member_id) 
          WHERE m.member_id = '".$db->sql_escape($MyMemberID)."'";
  $result = $db->query($sql); 
  $member_data = $db->fetch_record($result);
}

// Class DropDown
$eq_classes = array();
$sql = 'SELECT class_id, class_name, class_min_level, class_max_level FROM __classes GROUP BY class_id';
$result = $db->query($sql);
while($row = $db->fetch_record($result)){
  if ( $row['class_min_level'] == '0'){
    $eq_classes[$row['class_id']] = (!empty($row['class_name'])) ? stripslashes($row['class_name'])." Level (".$row['class_min_level']." - ".$row['class_max_level'].")" : '(None)';
  }else{
    $eq_classes[$row['class_id']] = (!empty($row['class_name'])) ? stripslashes($row['class_name'])." Level ".$row['class_min_level']."+" : '(None)';
  } 
}
$db->free_result($result);

// Race Dropdown
$eq_races = array();
$sql = 'SELECT race_id, race_name FROM __races GROUP BY race_name';
$result = $db->query($sql);
while ( $row = $db->fetch_record($result) ){
  $eq_races[$row['race_id']] = (!empty($row['race_name'])) ? stripslashes($row['race_name']) : '(None)';
}
$db->free_result($result);

$customNoPic    = (is_file('games/'.$conf['real_gamename'].'/images/no_pic.png')) ? 'games/'.$conf['real_gamename'].'/images/no_pic.png' : 'images/no_pic.png';
$myCoolPicture  = ( $mode['edit'] == true && $member_data['picture'])  ? $pcache->FolderPath('upload','charmanager').$member_data['picture'] : $customNoPic;
$myCoolPicture2 = ( $mode['edit'] == true && $member_data['picture'])  ? $member_data['picture'] : '';
$tpl->assign_vars(array(
            'TEMPLATENAME'              => $user->style['template_path'],
            'F_ADD_MEMBER'              => 'addchar.php' . $SID,
            'U_IS_EDIT'							    => ($mode['edit'] == true) ? true : false,
            'U_ISNOT_EDIT'              => ($mode['edit'] == true) ? false : true,
						
						// Dropdown
						'DRPWN_PROF1' 							=> $khrml->DropDown('prof1_name', $user->lang['professionsarray'], $member_data['prof1_name'], '', '', 'input'),
						'DRPWN_PROF2' 							=> $khrml->DropDown('prof2_name', $user->lang['professionsarray'], $member_data['prof2_name'], '', '', 'input'),
						'DRPWN_GENDER' 							=> $khrml->DropDown('gender', $user->lang['genderarray'], $member_data['gender'], '', '', 'input'),
						'DRPWN_FACTION' 						=> $khrml->DropDown('faction', $user->lang['factionarray'], $member_data['faction'], '', '', 'input'),
						'DRPWN_CLASS' 						  => $khrml->DropDown('member_class_id', $eq_classes, $member_data['member_class_id'], '', '', 'input'),
						'DRPWN_RACE' 						    => $khrml->DropDown('member_race_id', $eq_races, $member_data['member_race_id'], '', '', 'input'),
						
            // Language
            'L_ADD_MEMBER_TITLE'        => $user->lang['uc_add_member'],
            'L_EDIT_MEMBER_TITLE'       => $user->lang['uc_edit_member'],
            'L_INFO_BOX'                => $user->lang['uc_info_box'],
            'L_NAME'                    => $user->lang['name'],
            'L_RACE'                    => $user->lang['race'],
            'L_CLASS'                   => $user->lang['class'],
            'L_LEVEL'                   => $user->lang['level'],
            'L_ADD_MEMBER'              => $user->lang['uc_add_char'],
            'L_EDIT_MEMBER'             => $user->lang['uc_save_char'],
            'L_ADD_PIC'									=> $user->lang['uc_add_pic'],
            'L_CHANGE_PIC'							=> $user->lang['uc_change_pic'],
            'L_SUCC_ADDED'							=> $user->lang['uc_pic_added'],
            'L_SUCC_CHANGED'						=> $user->lang['uc_pic_changed'],
            'L_RESET'                   => $user->lang['reset'],
            'L_OVERTAKE'                => $user->lang['overtake_char'],
            'L_CANCEL'                  => $user->lang['uc_button_cancel'],
            'L_GUILD'										=> $user->lang['uc_guild'],
            'L_SKILL'										=> $user->lang['uc_tab_skills'],
            'L_GENDER'									=> $user->lang['uc_gender'],
            'L_FACTION'									=> $user->lang['uc_faction'],
            
            'L_FIRST_PROF'							=> $user->lang['uc_first_prof'],
            'L_SECOND_PROF'							=> $user->lang['uc_second_prof'],
            'L_SKILL_LEVEL'							=> $user->lang['uc_prof_skill'],
            'L_NOTES'                   => $user->lang['uc_notes'],
						
						// Profiler 
						'L_PROFILER_EXPL'						=> $user->lang['uc_prifler_expl'],
						'L_BUFFED'									=> $user->lang['uc_buffed'],
						'L_CTPROFILES'							=> $user->lang['uc_ctprofiles'],
						'L_ALLAKHAZAM'							=> $user->lang['uc_allakhazam'],
						'L_CURSE_PROFILER'					=> $user->lang['uc_curse_profiler'],
						'L_TALENTPLANER'						=> $user->lang['uc_talentplaner'],
						
						//tab shit	
						'TAB_HEAD_CM'								=> $jquery->Tab_header('char_tabs'),
						'TAB_NAME1'									=> $user->lang['uc_tab_Character'],
						'TAB_NAME2'									=> $user->lang['uc_tab_skills'],
						'TAB_NAME3'									=> $user->lang['uc_tab_profession'],
						'TAB_NAME4'									=> $user->lang['uc_tab_profilers'],
						'TAB_NAME5'									=> $user->lang['uc_tab_notes'],
						
            // Javascript messages
            'MSG_NAME_EMPTY'            => $user->lang['fv_required_name'],
            'USER_CAN_CONNECT'					=> ($user->check_auth('u_charmanager_connect', false)) ? true : false,
            
            // Picture upload
            'UCV_PICTURE'               => $cmupload->Show('member_pic', 'addchar.php?performupload=true', $myCoolPicture, false),
            'UCV_PICTURE_NAME'          => $myCoolPicture2,
            
            'UCV_IS_WOW'								=> ($conf['real_gamename'] == 'wow') ? true : false,
            'UCV_MEMBER_ID'             => $CharTools->ValueorNull($MyMemberID),
            'UCV_MEMBER_NAME'           => $CharTools->ValueorNull($member_data['member_name']),
            'UCV_MEMBER_LEVEL'          => $CharTools->ValueorNull($member_data['member_level']),
            'UCV_GENDER'								=> $CharTools->ValueorNull($member_data['gender']),
            'UCV_GUILD'									=> $CharTools->ValueorNull($member_data['guild']),
            'UCV_PROF_V1'								=> $CharTools->ValueorNull($member_data['prof1_value']),
            'UCV_PROF_V2'								=> $CharTools->ValueorNull($member_data['prof2_value']),
            'UCV_SKILL_1'								=> $CharTools->ValueorNull($member_data['skill_1']),
            'UCV_SKILL_2'								=> $CharTools->ValueorNull($member_data['skill_2']),
            'UCV_SKILL_3'								=> $CharTools->ValueorNull($member_data['skill_3']),
            'UCV_SKILL2_1'							=> $CharTools->ValueorNull($member_data['skill2_1']),
            'UCV_SKILL2_2'							=> $CharTools->ValueorNull($member_data['skill2_2']),
            'UCV_SKILL2_3'							=> $CharTools->ValueorNull($member_data['skill2_3']),
            'UCV_NOTES'                 => $CharTools->ValueorNull(stripslashes($member_data['notes'])),
            'UCV_BUFFED'								=> $CharTools->ValueorNull($member_data['blasc_id']),
            'UCV_CTPROFILES'						=> $CharTools->ValueorNull($member_data['ct_profile']),
            'UCV_ALLAKHAZAM'						=> $CharTools->ValueorNull($member_data['allakhazam']),
            'UCV_CURSE_PROFILER'				=> $CharTools->ValueorNull($member_data['curse_profiler']),
            'UCV_TALENTPLANER'					=> $CharTools->ValueorNull($member_data['talentplaner']),
  )
);

  $eqdkp->set_vars(array(
    'page_title'        => $CharTools->GeneratePageTitle($user->lang['manage_members_title']),
    'template_file'     => 'addchar.html',
    'gen_simple_header' => true,
    'template_path'     => $pm->get_data('charmanager', 'template_path'),
    'display'           => true)
  );
?>
