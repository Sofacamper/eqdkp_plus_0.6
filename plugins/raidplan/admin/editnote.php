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
 * $Id: editnote.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

$memisop = $rpclass->CheckPermGroupLeader();
if (!is_array($memisop)){
	// Check user permission
	$user->check_auth('a_raidplan_update');
}

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);

  if($_GET['mode']== 'save'){
    if($_POST['save'] && $_POST['signupnote']){
      $tmpnoted = str_replace("'", "\'", stripslashes($_POST['signupnote']));
    }else{
      $tmpnoted = '';
    }
    
    // update existing note..
    $sql = "UPDATE ". RP_ATTENDEES_TABLE ."
  					SET `attendees_note`='" .$tmpnoted."',
  					`attendees_note_lvl`='0'
  					WHERE member_id='".(int) $_POST['memberid']."'
  					AND raid_id='".$_POST['raidid']."'";
    $db->query($sql);
    echo "<script>parent.window.location.href = '../viewraid.php".$SID."&r=".$_POST['raidid']."';</script>";
  }else{
  // add
  	// Load the old Note
	  $sql = 'SELECT attendees_note FROM ' . RP_ATTENDEES_TABLE .' WHERE member_id='.(int) $_GET['member'].' AND raid_id='.(int) $_GET['raid'];
    $result = $db->query($sql);
	  $row = $db->fetch_record($result);
	  $db->free_result($result);
  	
    $tpl->assign_vars(array(
              'S_ADD'         => true,
              'F_EDIT_NOTE'  	=> 'editnote.php'.$SID.'&amp;mode=save',
              'MEMBER_ID'			=> $_GET['member'],
              'RAID_ID'				=> $_GET['raid'],
              'NOTE'          => ($row['attendees_note']) ? stripslashes($row['attendees_note']) : '',
              
              'L_NOTE'        => $user->lang['note'],
              'L_BUTTSAVE'	  => $user->lang['rp_save'],
              'L_BUTTDELETE'	=> $user->lang['delete'],

		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_editnote']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/editnote.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );
	}
?>
