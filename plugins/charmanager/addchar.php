<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2011-09-22 21:22:48 +0200 (Do, 22. Sep 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 11311 $
 * 
 * $Id: addchar.php 11311 2011-09-22 19:22:48Z wallenium $
 */

define('EQDKP_INC2', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../';
include_once('include/common.php');
include_once('include/tabs.class.php');
$mode['edit'] = $mode['update'] = false;
echo"Bitte Chars importieren!";
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


  $eqdkp->set_vars(array(
    'page_title'        => $CharTools->GeneratePageTitle($user->lang['manage_members_title']),
    'template_file'     => 'addchar.html',
    'gen_simple_header' => true,
    'template_path'     => $pm->get_data('charmanager', 'template_path'),
    'display'           => true)
  );
?>
