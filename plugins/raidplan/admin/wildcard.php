<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-01-22 00:09:17 +0100 (Thu, 22 Jan 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 3591 $
 * 
 * $Id: wildcard.php 3591 2009-01-21 23:09:17Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('a_raidplan_wildcards');
  
  // Delete
  if($_POST['doDelete'] && $_POST['delete']){
    foreach ($_POST['delete'] as $delete_id){
      $delsql = "DELETE FROM ".RP_WILDCARD_TABLE." WHERE wildcard_id= '".$delete_id."'";
      $db->query($delsql);
    }
  }
  
  // add
  if($_GET['mode'] == 'add'){
    $sql = 'SELECT raid_id, raid_name, raid_date
            FROM '.RP_RAIDS_TABLE.'
		        WHERE raid_date<'.$stime->DoTime().'
            ORDER BY raid_date DESC
            LIMIT 30';
    $raids_result = $db->query($sql);
            
    while ( $row = $db->fetch_record($raids_result) ){
      $tpl->assign_block_vars('raids', array(
		          'ID'              => $row['raid_id'],
				      'NAME'            => $row['raid_name'],
				      'DATE'            => $stime->DoDate($conf['timeformats']['medium'], $row['raid_date']),
			));
    }
    $db->free_result($raids_result);
    
    $tpl->assign_vars(array(
              'S_ADD'           => true,
              'F_ADD_WILDCARD'  => 'wildcard.php'.$SID.'&amp;mode=save',
              'L_SELECT_RAID'   => $user->lang['rp_selectraid'],
              'B_SET_WILDCARD'  => $user->lang['rp_set_wildcards'],
		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['rp_wildcard'],
			'template_path' 	       => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/wildcard.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );
  }elseif($_GET['mode']== 'save'){
    $sql = 'SELECT raid_id, raid_name, raid_date, raid_date_invite, raid_date_finish
		    							raid_date_subscription, raid_note, raid_leader, 
		    							raid_value, raid_attendees, raid_added_by, raid_updated_by,
		    							raid_repeat, delete_id, raid_link 
											FROM ' . RP_RAIDS_TABLE . "
											WHERE raid_id='".(int) $_POST['raid_id']."'";
			$result = $db->query($sql);
			$raid = $db->fetch_record($result);
			$db->free_result($result);
    
		$sql = "SELECT users.username, attendees.raid_id, attendees.attendees_subscribed, members.member_name, members.member_id
				FROM (" . MEMBERS_TABLE . " as members, " . RP_ATTENDEES_TABLE . " as attendees, " . MEMBER_USER_TABLE . " as member_user, " . USERS_TABLE . " as users)
				WHERE raid_id='" . (int) $_POST['raid_id'] . "'
				AND members.member_id=member_user.member_id
				AND attendees.member_id=member_user.member_id
				AND member_user.user_id=users.user_id
        AND attendees.attendees_subscribed=1";
		$result = $db->query($sql);
		while ($row = $db->fetch_record($result)){
			$attendees[$row['username']] = array(
				'user_name'		=> $row['username'],
				'member_name' => $row['member_name'],
				'member_id'   => $row['member_id'],
				'subscribed'	=> $row['attendees_subscribed']);
		}
		$db->free_result($result);

      if($attendees){
  		foreach ($attendees as $attendee){
  			if ($attendee['subscribed'] == 0){
  				$sql = 'DELETE FROM ' . RP_WILDCARD_TABLE . " WHERE member_name='" . $attendee['member_name'] . "' AND event=".$this->url_id;
  				$db->query($sql);
        }elseif ($attendee['subscribed'] == 1){
  				$query = $db->build_query('INSERT', array(
  					'member_name'				=> $attendee['member_name'],
  					'member_id'				  => $attendee['member_id'],
  					'event'             => $raid['raid_name'],
  					'date'              => $stime->DoTime(),
  					'wildcard'					=> 1));
  				$sql = 'INSERT INTO ' . RP_WILDCARD_TABLE . $query;
  				$db->query($sql);
  			}
  		}// foreach end
  		// Close window
      echo "<script>parent.window.location.href = 'wildcard.php';</script>";
  	}else{
  	 $tpl->assign_vars(array(
              'S_ADD'           => true,
              'S_WINDOW'        => true,
              'L_INFO_BOX'      => $user->lang['rp_wc_error'],
              'IMAGE'           => 'global/false.png',
              'L_ERROR'         => $user->lang['rp_no_wc_users'],
		  ));
    }
      $eqdkp->set_vars(array(
	    'page_title'             => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['rp_wildcard'],
			'template_path' 	       => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/wildcard.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );

  }else{
  // the char addition Data
    $sql = "SELECT * from ".RP_WILDCARD_TABLE;
    $result = $db->query($sql);
    while ($row = $db->fetch_record($result))
		{
		  $tpl->assign_block_vars('wildcard_row', array(
		          'ROW_CLASS' 			=> $eqdkp->switch_row_class(),
		          'ID'              => $row['wildcard_id'],
				      'MEMBER'          => $row['member_name'],
				      'EVENT'           => $row['event'],
				      'WILDCARD'        => $row['wildcard'],
				      'DATE'            => $stime->DoDate($conf['timeformats']['medium'], $row['date']),
			));
		}
    $db->free_result($result);

    $tpl->assign_vars(array(
              'S_ADD'           => false,
              'F_DEL_FORM'      => 'wildcard.php',
              
              // JS Code
		          'JS_ADDWILDCARD'  => $jquery->Dialog_URL('RPAddWildcard', $user->lang['rp_addwildcard'], 'wildcard.php?mode=add', '500', '100'),
              
              'L_DELETE'        => $user->lang['rp_button_delete'],
              'L_WILDCARD'      => $user->lang['rp_wildcard'],
              'L_EVENTNAME'     => $user->lang['rp_eventname'],
              'L_MEMBERNAME'    => $user->lang['rp_membername'],
              'L_ADDWILDCARD'   => $user->lang['rp_addwildcard'],
              'L_DATE'          => $user->lang['rp_added_on_date'],
		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_wildcardmanager']),
			'template_path' 	       => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/wildcard.html',
			'display'                => true)
    );
  }   
?>
