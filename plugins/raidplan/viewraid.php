<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2012-01-10 23:49:16 +0100 (Tue, 10 Jan 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 11626 $
 * 
 * $Id: viewraid.php 11626 2012-01-10 22:49:16Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../';
include_once('includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('u_raidplan_view');

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);

class RaidPlan_View_Raid extends EQdkp_Admin
{
	var $raid 			= array();
	var $classes 		= array();
	var $wildcards  = array();
	var $attendees 	= array();
	var $members 		= array();
	var $member 		= array();	
	var $debugtext  = "";
	var $extra_css 	= "";
	
    // ---------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------
    function RaidPlan_View_Raid(){
      global $SID, $db, $eqdkp, $user, $tpl, $pm, $rpclass, $conf, $stime, $cmapi, $rpconvert, $pcomments;
      parent::eqdkp_admin();
      
      // CM API available?
      $this->cmapi_installed = false;
      if(is_object($cmapi)){
        if($cmapi->isInstalled()){
          $this->cmapi_installed = true;
        }
      }
      
  		$this->set_vars(array(
  			'confirm_text'					=> $user->lang['rp_confirm_delete_subscription'],
        'uri_parameter'					=> URI_RAID,
  			'subscribed_member_id'	=> 'subscribed_member_id',
  		));
		
    if (!$this->url_id) { message_die($user->lang['error_invalid_raid_provided']); }		
		
		// Comment System
		if(is_object($pcomments) && $pcomments->version > '1.0.3' && $conf['rp_comments'] == '1'){
      $comm_settings = array(
          'attach_id' => $this->url_id, 
          'page'      => 'raidplan',
          'language'  => 'rp_',
          'auth'      => 'a_raidplan_'
      );
    	$pcomments->SetVars($comm_settings);
      $tpl->assign_vars(array(
            'ENABLE_COMMENTS'     => true,
      			'COMMENTS'            => $pcomments->Show(),
      ));
    }
		
		$sql = 'SELECT raid_id, raid_name, raid_link, raid_distribution, raid_date_added, raid_date_change, raid_date, raid_date_subscription, raid_date_finish, raid_date_invite, raid_note, raid_leader, raid_value, raid_added_by, raid_updated_by, raid_closed, raid_attendees
				    FROM ' . RP_RAIDS_TABLE . "
				    WHERE raid_id='" . (int) $this->url_id . "'";
		$result = $db->query($sql);
		if (!$row = $db->fetch_record($result)) { message_die($user->lang['error_invalid_raid_provided']); }
		$db->free_result($result);

		$this->raid = array(
			'raid_added_by'						=> post_or_db('raid_added_by', $row),
			'raid_updated_by'         => post_or_db('raid_updated_by', $row),
			'raid_name'								=> post_or_db('raid_name', $row),
			'raid_date'								=> post_or_db('raid_date', $row),
			'raid_date_added'					=> post_or_db('raid_date_added', $row),
			'raid_date_subscription'	=> post_or_db('raid_date_subscription', $row),
			'raid_date_invite'				=> post_or_db('raid_date_invite', $row),
			'raid_date_finish'				=> post_or_db('raid_date_finish', $row),
			'raid_date_change'        => post_or_db('raid_date_change', $row),
			'raid_note'								=> post_or_db('raid_note', $row),
			'raid_leader'							=> post_or_db('raid_leader', $row),
			'raid_value'							=> post_or_db('raid_value', $row),
			'raid_link'								=> post_or_db('raid_link', $row),
			'raid_closed'             => post_or_db('raid_closed', $row),
			'raid_attendees'					=> post_or_db('raid_attendees', $row),
			'raid_subscribed'					=> false,
			'raid_distribution'       => post_or_db('raid_distribution', $row),
		);

		if (!$this->raid['raid_value']) { $this->raid['raid_value'] = $rpclass->get_raid_value($this->raid['raid_name']); }

		// Get classes
  	if($this->raid['raid_distribution'] == 2){
      $sql = "SELECT class_id, class_name
    					FROM " . CLASS_TABLE . "
    					WHERE class_name <> 'Unknown'
    					GROUP BY class_name
    					ORDER BY class_name";
    }else{
      $sql = "SELECT class_name, class_count
     					FROM " . RP_CLASSES_TABLE . "
     					WHERE raid_id='" . (int) $this->url_id . "'
     					ORDER BY class_name";

		// this is an dirty fix for the class name & id problem
		if($this->raid['raid_distribution'] != 1){
			$sql_clls = "SELECT class_id, class_name
				FROM " . CLASS_TABLE . "
				WHERE class_name <> 'Unknown'
				GROUP BY class_name
				ORDER BY class_name";
			$result_clls = $db->query($sql_clls);
			while ($row_clls = $db->fetch_record($result_clls)){
				$usealtmode = true;
				$tmpclasses[$rpconvert->classname($row_clls['class_name'])] = array('id'=>$row_clls['class_id'], 'name'=>$row_clls['class_name']);
			}
		}
    }
    $result = $db->query($sql);

    while ($row = $db->fetch_record($result)){
		// the second part of the dirty fix...
      if(isset($usealtmode) && $usealtmode){
		$tmpclsname			= $rpconvert->classname($row['class_name']);
		$row['class_name']	= $tmpclasses[$tmpclsname]['name'];
      	$row['class_id']	= $tmpclasses[$tmpclsname]['id'];
    }
      
      if($this->raid['raid_distribution'] == 1){
        $myclassname = $rpclass->RoleConf($row['class_name']);
      }
      $this->classes[$row['class_name']] = array(
		'id'		=> $row['class_id'],
    	'name'		=> ($this->raid['raid_distribution'] == 1) ? $myclassname['name'] : $rpconvert->classname($row['class_name'], true),
    	'name_en'	=> ($this->raid['raid_distribution'] == 1) ? $myclassname['name_en'] : $rpconvert->classname($row['class_name']),
    	'count'		=> ($this->raid['raid_distribution'] == 2) ? 0 : $row['class_count'],
    );
    }
  	$db->free_result($result);
    
    // Build the Wildcard Array
    if($conf['rp_wildcard'] == 1){
      $rpevent_name = addslashes($rpclass->IdToEventName($this->url_id));
      $wcsql = "SELECT wildcard, date, member_name
                FROM " . RP_WILDCARD_TABLE . "
                WHERE event='".$rpevent_name."';";
      $wcresult = $db->query($wcsql);
      
      while ($wcrow = $db->fetch_record($wcresult)){
        $this->wildcards[$wcrow['member_name']] = array(
                'wcexpire'   => $wcrow['date'],
                'wildcard'   => $wcrow['wildcard']
          );
      }
      $db->free_result($wcresult);
    }
    
		// Get attendees	
    $sql = "SELECT attendees.attendees_statchangedby, attendees.member_id, attendees.`group`, attendees.role, members.member_name, members.member_lastraid, class_name, class_id, members.member_status, members.member_level, member_user.user_id, attendees.attendees_subscribed, attendees.attendees_random, attendees.attendees_note, attendees.attendees_note_lvl, attendees.attendees_signup_time, attendees.attendees_change_time, ranks.rank_name, ranks.rank_prefix, ranks.rank_suffix, memgroups.`group` as savedgroup
			     FROM (" . RP_ATTENDEES_TABLE . " as attendees, " . MEMBERS_TABLE . " as members, " . MEMBER_USER_TABLE . " as member_user, " . CLASS_TABLE . " as classes, " . USERS_TABLE . " as users, " . MEMBER_RANKS_TABLE . " as ranks)";
    if($conf['rp_groupbyevent'] == 1){
      $sql .= "LEFT OUTER JOIN " . RP_MEMGROUPS_TABLE . " as memgroups ON (members.member_id=memgroups.member_id && memgroups.groupevent='".$rpevent_name."')";
    }else{
      $sql .= "LEFT OUTER JOIN " . RP_MEMGROUPS_TABLE . " as memgroups ON (members.member_id=memgroups.member_id)";
    }
    $sql .= "WHERE raid_id='" . (int) $this->url_id . "'
			AND attendees.member_id=members.member_id
			AND members.member_id=member_user.member_id
			AND classes.class_id=members.member_class_id
			AND member_user.user_id=users.user_id
			AND members.member_rank_id = ranks.rank_id";
		if($_GET['group'] && $_GET['group'] != 99){
			if($conf['rp_savegroups'] == 1){
				$sql .= " AND memgroups.group='".(int) $_GET['group']."'";
			}else{
		  	$sql .= " AND attendees.group='".(int) $_GET['group']."'";
		  }
		}
		if($_GET['group'] && $_GET['group'] == 99){
			if($conf['rp_savegroups'] == 1){
				$sql .= " AND (memgroups.group='' OR memgroups.group='0')";
			}else{
		  	$sql .= " AND (attendees.group='' OR attendees.group='0')";
		  }
		}
		if($conf['rp_enable_groups'] == '1'){
			$sql .= " ORDER BY attendees.group ASC, memgroups.group, attendees.attendees_random DESC";
		}else{
			$sql .= " ORDER BY attendees.attendees_random DESC";
		}
		$result = $db->query($sql);
		while ($row = $db->fetch_record($result)){ 
			$this->attendees[$row['member_id']] = array(
        'id'            => $row['member_id'],
        'name'          => $row['member_name'],
				'class_name'		=> $row['class_name'],
				'class_id'      => $row['class_id'],
				'status'				=> $row['attendees_subscribed'],
				'user_id'				=> $row['user_id'],
				'random'				=> $row['attendees_random'],
				'rank'					=> $row['rank_name'],
				'group'         => $row['group'],
				'role'          => $row['role'],
				'mem_lastraid'  => $row['member_lastraid'],
				'admin_signin'  => $row['attendees_statchangedby'],
				'savedgroup'    => $row['savedgroup'],
				'level'         => $row['member_level'],
				'rank_prefix'		=> $row['rank_prefix'],
				'rank_suffix'		=> $row['rank_suffix'],
				'note'					=> stripslashes($row['attendees_note']),
				'note_lvl'      => $row['attendees_note_lvl'],
				'signup_time'  	=> $stime->DoDate($conf['timeformats']['medium'],$row['attendees_signup_time']),
				'change_time'  	=> $stime->DoDate($conf['timeformats']['medium'],$row['attendees_change_time']),
				'wildcard'			=> $this->wildcards[$row['member_name']]['wildcard'],
				'wcexpire'      => $this->wildcards[$row['member_name']]['wcexpire'],
        'member_status' => $row['member_status']);
        
        // Charmanager Profile Additions
        if($this->cmapi_installed){
          $cm_data = $cmapi->MemberProfile($row['member_id']);
          if(is_array($cm_data)){
            $this->attendees[$row['member_id']] = array_merge($this->attendees[$row['member_id']],$cm_data);
          }
        }
		}
    $db->free_result($result);
		
		// get the guests
		$sql = "SELECT tempm.*, class_id
			FROM (" . RP_TEMPMEMBERS_TABLE . " as tempm, " . CLASS_TABLE . " as classes)
			WHERE raid_id='" . (int) $this->url_id . "'
			AND classes.class_id=tempm.class";
		if($_GET['group'] && $_GET['group'] != 99){
			$sql .= " AND tempm.group='".(int) $_GET['group']."'";
		}
		if($_GET['group'] && $_GET['group'] == 99){
		  $sql .= " AND (tempm.group='' OR tempm.group='0')";
		}
		$sql .= " ORDER BY tempm.group ASC, tempm.membername DESC";
		$result = $db->query($sql);

		while ($row = $db->fetch_record($result)){
			$this->guests[$row['tempmember_id']] = array(
				'id'				=> $row['tempmember_id'],
				'name'				=> $row['membername'],
				'class_name'		=> $row['class_name'],
				'class_id'			=> $row['class_id'],
				'class_en'			=> $rpconvert->classname($row['class_name']),
				'group'				=> $row['group'],
				'note'				=> stripslashes($row['note']),
				'signup_time'		=> $stime->DoDate($conf['timeformats']['short'],$row['attendees_signup_time']),
			);
		}
    if($this->guests){
    	$this->guestcount = count($this->guests);
    }
		$db->free_result($result);
		
		//
		// Get members
		//
		if ($user->data['user_id']){		  
			$sql =  "SELECT users.user_id, members.member_id, ranks.rank_id, members.member_name, classes.class_name, classes.class_id, attendees.raid_id, attendees.role, attendees.attendees_subscribed, attendees.attendees_random, attendees.attendees_note
							FROM (" . MEMBERS_TABLE . " as members, " . MEMBER_USER_TABLE . " as users, " . CLASS_TABLE . " as classes, ".MEMBER_RANKS_TABLE." as ranks) 
							LEFT JOIN " . RP_ATTENDEES_TABLE . " as attendees
							ON (members.member_id=attendees.member_id AND attendees.raid_id=" . (int) $this->url_id . ")";
			if($this->raid['raid_distribution'] == 1){
        $sql .= "WHERE members.member_class_id=classes.class_id";
      }else{
        $sql .= "WHERE members.member_class_id=classes.class_id";
			}
			$sql .= " AND members.member_id=users.member_id
							AND members.member_rank_id = ranks.rank_id
							AND users.user_id=" . $user->data['user_id'] ."
							ORDER BY attendees.attendees_random DESC";
			$result = $db->query($sql);
			while ($row = $db->fetch_record($result)){        
				$this->members[$row['member_id']] = array(
					'id'					=> $row['member_id'],
					'name'				=> $row['member_name'],
					'class_name'	=> $row['class_name'],
					'class_id'    => $row['class_id'],
					'subscribed'	=> $row['attendees_subscribed'],
					'random'			=> $row['attendees_random'],
					'rank'				=> $row['rank_id'],
					'role'        => $row['role'],
					'note'				=> stripslashes($row['attendees_note'])
        );
				if($row['attendees_random']>0) { $this->raid['raid_subscribed'] = true; }
			}
			$db->free_result($result);
			
		}
			$sql = "SELECT members.*, class_name, class_id, member_user.user_id, ranks.rank_name, ranks.rank_prefix, ranks.rank_suffix, ranks.rank_hide, attendees.role
	            FROM (" . MEMBERS_TABLE . " as members, " . MEMBER_USER_TABLE . " as member_user, " . CLASS_TABLE . " as classes, " . USERS_TABLE . " as users, " . MEMBER_RANKS_TABLE . " as ranks)
	            LEFT JOIN " . RP_ATTENDEES_TABLE . " as attendees	ON (members.member_id=attendees.member_id AND attendees.raid_id=" . (int) $this->url_id . ")
	      			WHERE members.member_id=member_user.member_id
	      			AND classes.class_id=members.member_class_id
	      			AND member_user.user_id=users.user_id
	      			AND members.member_rank_id = ranks.rank_id";
	    if($eqdkp->config['hide_inactive'] == 1){
			  $sql .= " AND members.member_status='1'";
			}
			$sql .= " ORDER BY members.member_name";
			$result = $db->query($sql);
			while ($row = $db->fetch_record($result)){
        
        // Fill the twink array
		    $this->twinks[$row['user_id']][] = array(
          'id'        => $row['member_id'],
          'name'      => $row['member_name'],
          'class'     => $row['class_name'],
          'class_id'  => $row['class_id'],
          'role'      => $row['role']
        );
        
        // Fill the members array
				$this->member[$row['member_id']] = array(
					'id'				=> $row['member_id'],
					'name'				=> $row['member_name'],
					'class_name'		=> $row['class_name'],
					'class_id'			=> $row['class_id'],
					'status'			=> $row['attendees_subscribed'],
					'user_id'			=> $row['user_id'],
					'random'			=> $row['attendees_random'],
					'rank'				=> $row['rank_name'],
					'rank_prefix'		=> $row['rank_prefix'],
					'rank_suffix'		=> $row['rank_suffix'],
					'level'				=> $row['member_level'],
					'rank_hide'			=> $row['rank_hide'],
					'note'				=> $row['attendees_note'],
					'role'				=> $row['role'],
					'signup_time'		=> $stime->DoDate($conf['timeformats']['short'],$row['attendees_signup_time']),
					'change_time'		=> $stime->DoDate($conf['timeformats']['medium'],$row['attendees_change_time']),
					'wildcard'			=> $this->wildcards[$row['member_name']]['wildcard'],
					'member_status'		=> $row['member_status'],
				);
	      
	      // Charmanager Profile Additions
        if($this->cmapi_installed){
          $cm_data = $cmapi->MemberProfile($row['member_id']);
          if(is_array($cm_data)){
            $this->member[$row['member_id']] = array_merge($this->member[$row['member_id']],$cm_data);
          }
        }
			}
      $db->free_result($result);
      
      $this->assoc_buttons(array(
            'statusupdate' 	=> array(
                'name'   		=> 'update',
                'process' 	=> 'process_status_update',
                'check'   	=> 'u_raidplan_view'),
            'confirmroll' 	=> array(
                'name'    	=> 'allupdate',
                'process' 	=> 'process_allupdate',
                'check'   	=> 'a_raidplan_update'),
            'form' 					=> array(
                'name'    	=> '',
                'process' 	=> 'display_form',
                'check'   	=> 'u_raidplan_view'))
        );
    }

// ---------------------------------------------------------
// Process Update
// ---------------------------------------------------------
  function process_status_update(){
    global $SID, $db, $eqdkp, $user, $tpl, $pm, $conf, $rpclass, $stime, $rpconvert, $khrml, $logmanager;
		$statusID 					= 1;
		$success_msg 				= $pre_member_id = $pre_rank_id = "";
		
		// if empty Raid URL
		$link_list = array(
            $user->lang['rp_view_raid']   => 'viewraid.php' . $SID . '&amp;r=' . $this->url_id,
            $user->lang['list_raids']     => 'listraids.php' . $SID,
		);
		if ($_POST[URI_RAID]== ""){
			$success_msg = $user->lang['error_invalid_name_provided'];
			$link_list[$user->lang['view_raid']] = "";
			$this->admin_die($success_msg, $link_list);			
		}
		
		// If empty Member ID
		if ($_POST['member_id']==""){
			$success_msg = $user->lang['error_invalid_name_provided'];
			$this->admin_die($success_msg, $link_list);			
		}
		
		// If empty Action
		if ($_POST['action']==""){
			$success_msg = $user->lang['rp_err_invalid_action_prov'];
			$this->admin_die($success_msg, $link_list);			
		}
		
		
		// Get the Action Number
		switch ($_POST['action']){
			case "status1"		: $statusID = 1;		break; // Sign On
			case "status2"		: $statusID = 2;		break; // Sign Off
			case "status3"		: $statusID = 3;		break; // Not Sure
			case "status10"		: $statusID = 10;		break; // Update
		}
		
		// Check if the user subscribed earlier
		$success_msg = sprintf($user->lang['rp_raid_status'.$statusID], stripslashes($this->members[$_POST['member_id']]['name']), stripslashes($_POST[URI_RAID]));
		foreach ($this->members as $membercheck){
      //auto confirm part
      if($membercheck['id'] == $_POST['member_id']){
        if($statusID == 1){
          $statusID = (($membercheck['rank'] == $conf['rp_rank_team'] && $conf['rp_enable_team'] == 1) || ($membercheck['rank'] == $conf['rp_rank'] && $conf['rp_enabl_officr'] == 1 && $conf['rp_disabl_cl_ac'] != 1))? 0 : 1;
        }
      } 
      
  		// If the attendee should be updated
      if (!is_null($membercheck['subscribed'])){
  			$pre_member_id  = $membercheck['id'];
  			$statusID       = ($statusID == '10') ? $membercheck['subscribed'] : $statusID;
  		}
		}
		
		// Delete old Wildcard if Member is confirmed for that event
		if($statusID == 0){
		  $rpclass->DeleteOldWildcard($_POST['member_id'], $_POST[URI_RAID]);
    }
		
		// Insert the 
		If ($pre_member_id > 0){
			// Member subscribed earlier - Change Status
			$query = $db->build_query('UPDATE', array(
				'member_id'							=> stripslashes($_POST['member_id']),
				'attendees_subscribed'	=> $statusID,
				'attendees_change_time' => $stime->DoTime(),
				'role'                  => ($this->raid['raid_distribution'] == 1) ? stripslashes($_POST['rp_role']) : '',
				'attendees_note'        => $khrml->CleanInput($_POST['signupnote'])
      ));
			$db->query('UPDATE ' . RP_ATTENDEES_TABLE . ' SET ' . $query . "
				          WHERE raid_id='" . stripslashes($_POST[URI_RAID]) . "'
				          AND member_id='" . $pre_member_id . "'");
		}else{		
			// This is the first subscription with this member
      srand((double)microtime()*1000000);
      $rand_value = rand(1,100);
			$query = $db->build_query('INSERT', array(
				'raid_id'								=> stripslashes($_POST[URI_RAID]),
				'member_id'     				=> stripslashes($_POST['member_id']),
				'attendees_subscribed'	=> $statusID,
				'attendees_signup_time' => $stime->DoTime(),
				'attendees_change_time' => $stime->DoTime(),
				'attendees_random'			=> $rand_value,
				'role'                  => ($this->raid['raid_distribution'] == 1) ? stripslashes($_POST['rp_role']) : '',
				'attendees_note'        => $khrml->CleanInput($_POST['signupnote'])
      ));
			$db->query('INSERT INTO ' . RP_ATTENDEES_TABLE . $query);			
		}
		
		// LogManager entry
		$myrole = ($this->raid['raid_distribution'] == 1) ? stripslashes($_POST['rp_role']) : '';
		$status2name = array('{L_LOG_STATUS_0}','{L_LOG_STATUS_1}','{L_LOG_STATUS_2}','{L_LOG_STATUS_3}');
		$rplog_action = array(
        'header'          => '{L_LOG_CHANGED_STATUS}',
        '{L_LOG_MEMBER}'  => $rpclass->MemberID2Name(stripslashes($_POST['member_id'])),
        '{L_LOG_STATUS}'  => $status2name[$statusID],
        '{L_LOG_ROLE}'    => $myrole,
        '{L_LOG_RANDOM}'  => ($rand_value) ? $rand_value : $membercheck['random'],
    );
		$logmanager->AddEntry(stripslashes($_POST[URI_RAID]), '{L_LOG_CHANGED_STATUS}', $rplog_action);
		
		// Status Message
		$this->admin_die($success_msg, $link_list);
	}
	
	
	 function CloseRaid($raid_id, $status){
      global $db, $eqdkp, $user, $tpl, $pm, $conf, $stime, $logmanager;
      global $eqdkp_root_path, $SID;
      $statID = ($status == 'close') ? '1' : '0';
      $query = $db->build_query('UPDATE', array(
			 'raid_closed'  => $statID,
		  ));
		  if($user->check_auth('a_raidplan_update', false)){
		    $db->query('UPDATE ' . RP_RAIDS_TABLE . ' SET ' . $query . " WHERE raid_id='" . (int) $raid_id . "'");
		  }
		  
		  // LogManager entry
  		$rplog_action = array(
          'header'                => '{L_LOG_CLOSEDOPEN_RAID}',
          '{L_LOG_CLOSEDSTATUS}'  => ($status == 'close') ? 'L_LOG_RAID_CLOSED' : 'L_LOG_RAID_OPENED',
      );
  		$logmanager->AddEntry(stripslashes($_POST[URI_RAID]), '{L_LOG_CLOSEDOPEN_RAID}', $rplog_action);
		  redirect('plugins/'.PLUGIN.'/viewraid.php' . $SID . '&r=' . $raid_id);
   }
	
    // ---------------------------------------------------------
    // Process Confirm all Members
    // ---------------------------------------------------------

    function ConfirmAllSignedIn($raid_id){
        global $db, $eqdkp, $user, $tpl, $pm, $conf, $stime;
        global $eqdkp_root_path, $SID;

				$success_message = "";

				if ($raid_id==""){
					$success_msg = $user->lang['error_invalid_name_provided'];
					$link_list[$user->lang['view_raid']] = "";
					$this->admin_die($success_msg, $link_list);			
				}else{
				$sql = "SELECT attendees.attendees_subscribed, attendees.attendees_random, attendees.member_id as member_id, raid_id
				FROM " . RP_ATTENDEES_TABLE . " as attendees, " . MEMBERS_TABLE. " as members
				WHERE raid_id='" . (int) $raid_id . "'
				AND attendees.attendees_subscribed=1
				AND attendees.member_id=members.member_id";
				if (!($result = $db->query($sql))) { message_die('Could not obtain attendees information', '', __FILE__, __LINE__, $sql); }
				if (!$row = $db->fetch_record($result)) { message_die($user->lang['error_no_users_to_confirm']); }

			$query = $db->build_query('UPDATE', array(
				'attendees_subscribed'	=> 0));
			$db->query('UPDATE ' . RP_ATTENDEES_TABLE . ' SET ' . $query . "
				WHERE raid_id='" . stripslashes($raid_id) . "'
				AND attendees_subscribed=1");

			}
				redirect('plugins/'.PLUGIN.'/viewraid.php' . $SID . '&r=' . stripslashes($raid_id));
		}

    // ---------------------------------------------------------
    // Display form
    // ---------------------------------------------------------
    function display_form()
    {
     	global $db, $eqdkp, $user, $tpl, $pm, $conf, $rpclass, $khrml, $stime, $jquery, $pcomments, $rpconvert, $CharTools, $cmapi;
     	global $eqdkp_root_path, $SID;
			$status = array("0" => array(), "1" => array(), "2" => array(), "3" => array(), "4" => array(), "5" => array());
			$ICLASS = 0;
			$memisop = $rpclass->CheckPermGroupLeader();
			
			if($_POST['savenote'] && ($memisop || $user->check_auth('a_raidplan_update', false))){
				$rpclass->notes_update($_POST['raid_id'], $_POST['raid_note']);
			}
			
			if($_GET['confirmall'] == 'true' && $this->url_id && ($memisop || $user->check_auth('a_raidplan_update', false))){
				$this->ConfirmAllSignedIn($this->url_id);
			}
			
			if($_GET['closeraid'] == 'true' && $this->url_id){
				$this->CloseRaid($this->url_id, 'close');
			}
			
			if($_GET['openraid'] == 'true' && $this->url_id ){
				$this->CloseRaid($this->url_id, 'open');
			}
			
			// Build the Status count array
			$sql_count = "SELECT m.member_id, a.attendees_subscribed, a.raid_id FROM ".RP_ATTENDEES_TABLE." a, ".MEMBERS_TABLE." m WHERE m.member_id=a.member_id";
      $status_result = $db->query($sql_count);
      while($status_row = $db->fetch_record($status_result) ){
        $statuscount[$status_row['raid_id']][$status_row['member_id']] = $status_row['attendees_subscribed'];
      }
      $db->free_result($attendee_result);
			
			for ($istatus=0;$istatus<5;$istatus++){
  			  $icclasscnt = 0;
		  		$count_status = $rpclass->count_repeat_values($istatus, $statuscount[$this->url_id]);
		  		If ($count_status < 10) $count_status="0".$count_status;
		  		$classmaxcount = 0;
					foreach ($this->classes as $class){
						$classmaxcount = $classmaxcount + $class['count'];
					}
					if(($this->raid['raid_distribution'] == 2 || $this->raid['raid_distribution'] == 1) && $classmaxcount ==  0){
						$classmaxcount = ($this->raid['raid_attendees']) ? $this->raid['raid_attendees'] : 0;
					}
          
          // code to hide the unsigned row..
          $extrastatus = ($conf['rp_hide_unsigned'] == '1') ? $istatus : 100;

					$tpl->assign_block_vars('raidplanrow', array(
						'NAME'						=> $user->lang['status'.$istatus],					 // Name of the Status
						'ID'							=> $istatus,                                 // ID of the class
            'EXTRAID'         => $extrastatus,										         // Extra ID
						'COUNT'						=> ($this->guestcount && $istatus == 0) ? $count_status + $this->guestcount : $count_status,
						'MAXCOUNT'				=> $classmaxcount,
						));		
						
						// For each class in the database
						foreach ($this->classes as $class){
						$ICLASS++;
							// If this class should be in the raid
							if ($class['count'] > 0 || $this->raid['raid_distribution'] == 2 || $this->raid['raid_distribution'] == 1){
								If ($istatus == 4){
								
								$att_array = array ();
								foreach ($this->attendees as $attendee){ 
                  array_push($att_array, $attendee['user_id']);
                  
                  // set the memberID if signed in
                  if($attendee['user_id'] == $user->data['user_id']){
                    $drpdwn_members_select  = $attendee['id'];
                    $i_am_signedin          = true;
                  }
                }
                
								// For each Member not in this raid
								foreach ($this->member as $membe){
								$tmproles = array();
								  foreach($rpclass->RoleConf() as $rolearray){
								    if(in_array($membe['class_id'], $rolearray['classes'])){
                      array_push($tmproles, strtolower($rolearray['name_en']));
                    }
                  }
                  
									if (($rpconvert->classname($membe['class_name']) == $class['name_en'] || in_array(strtolower($class['name_en']), $tmproles)) && (!in_array($membe['user_id'], $att_array))){
										// check if the member should be skipped
                    if($conf['rp_enabl_levelcap'] == 1 && isset($conf['rp_levelcap']) && $membe['level'] < $conf['rp_levelcap']){
                      $mem_skip = true;
                    }elseif($conf['rp_hide_hiddngrp'] == 1 && $membe['rank_hide'] == 1){
                      $mem_skip = true;
                    }else{
                      $mem_skip = false;
                    }
                    // If the attendee belongs to this class put him in his Status Array
									  $rp_tooltip = $uc_tooltip = '';
                    $uc_tooltip = ($this->raid['raid_distribution']) ? $rpclass->ClassIcon($membe['class_name'], $membe['class_id']).' '.$membe['class_name'].'<br/>' : '';
									  if ($membe['rank']){
										  $uc_tooltip .= '<b>'.$membe['rank'].'</b><br/>';
									  }
									  
									  if ($this->cmapi_installed && function_exists('cm_tooltip')){
										  $uc_tooltip .= cm_tooltip($membe);
                    }

										// If the attendee belongs to this class put him in his Status Array
										if (!$mem_skip){
											$status[$istatus][$ICLASS][] = array(
												'id'				=> $membe['id'],
												'name'				=> $membe['name'],
												'status'			=> $istatus,
												'rank'				=> $membe['rank'],
												'rank_prefix'		=> $membe['rank_prefix'],
												'rank_suffix'		=> $membe['rank_suffix'],
												'random'			=> false,
												'uc_tooltip'		=> $uc_tooltip,
												'rp_tooltip'		=> $rp_tooltip,
												'class_name'		=> $membe['class_name'],
												'class_id'			=> $membe['class_id']
											);
										}
									}								
								}

							  }else{
							  $attendecount = 0;
								// For each attendee of this raid
								foreach ($this->attendees as $attendee){
									if ($attendee['status'] == $istatus && ($rpconvert->classname($attendee['class_name']) == $class['name_en'] || strtolower($rpconvert->classname($attendee['role'])) == strtolower($class['name_en']))){									
										// If the attendee belongs to this class put him in his Status Array
									$rp_tooltip = $uc_tooltip = '';
									
									if ($this->cmapi_installed && function_exists('cm_tooltip')){
										$uc_tooltip = cm_tooltip($attendee);
                  }
                  if($user->check_auth('a_raidplan_update', false) || is_array($memisop)){
                    if($attendee['admin_signin']){
                      $uc_tooltip .= '<b>'.$user->lang['rp_adminsignin'].'</b>: '.$attendee['admin_signin'].'<br/>';
                    }
                  }
                     // end admin Msg in TT

									// role-class TT
                  $rp_tooltip .= ($this->raid['raid_distribution'] == 1) ? $rpclass->ClassIcon($attendee['class_name'], $attendee['class_id']).' '.$attendee['class_name'].'<br/>' : '';					
									$rp_tooltip .= ($attendee['rank']) ? '<b>'.$attendee['rank'].'</b><br/>' : '';
									$rp_tooltip .= ($attendee['member_lastraid']) ? $lang->user['lastraid'].': '.$stime->DoDate($conf['timeformats']['short'], $attendee['member_lastraid']).'<br/>' : '';
									
										$status[$istatus][$ICLASS][] = array(
											'id'					=> $attendee['id'],
											'count'					=> $attendecount,
											'user_id'				=> $attendee['user_id'],
											'link'					=> "viewmember.php" . $SID . "&amp;user_id=" . $attendee['user_id'],
											'name'					=> $attendee['name'],
											'class_name'			=> $attendee['class_name'],
											'class_id'				=> $attendee['class_id'],
											'status'				=> $attendee['status'],
											'group'					=> $attendee['group'],
											'savedgroup'			=> $attendee['savedgroup'],
											'random'				=> $attendee['random'],
											'note'					=> $attendee['note'],
											'note_lvl'				=> $attendee['note_lvl'],
											'rank'					=> $attendee['rank'],
											'role'					=> $attendee['role'],
											'member_status'			=> $attendee['member_status'],
											'rank_prefix'			=> $attendee['rank_prefix'],
											'rank_suffix'			=> $attendee['rank_suffix'],
											'time'					=> $attendee['signup_time'],
											'chtime'				=> $attendee['change_time'],
											'uc_tooltip'			=> $uc_tooltip,
											'rp_tooltip'			=> $rp_tooltip,
											'wildcard'				=> $attendee['wildcard'],
											'wcexpire'				=> $attendee['wcexpire'],
											);
										$attendecount++;
										}
				  			}}
						// Write data to template
						$icclasscnt++;
						$brkvalue = (isset($conf['rp_breakvalue'])) ? $conf['rp_breakvalue'] : 10;

						$tpl->assign_block_vars('raidplanrow.classes', array(
							'BREAK'				=> ($conf['rp_enable_classbrk'] == 1 && ($icclasscnt%$brkvalue) == 0) ? true : false,
							'ID'				=> $class['id'],						// ID of the class
							'NAME'				=> $class['name'],						// Name of the class
							'NAME_EN'			=> strtolower($class['name_en']),		// Name of the Class in English
							'CLASS_ICON'		=> $rpclass->ClassIcon($class['name'], $class['id'], $this->raid['raid_distribution']),
							'MAX'				=> ($this->raid['raid_distribution'] == '2' && $class['count'] == 0) ? '' : '/'.$class['count'],				// Max of the class
							'COUNT'				=> count($status[$istatus][$ICLASS]),
						));	
						
						If (count($status[$istatus][$ICLASS]) > 0){
              foreach ($status[$istatus][$ICLASS] as $statuss){
  							
              $tmpinfott =  $statuss['rp_tooltip'].$user->lang['rp_time_header'].': '.$statuss['time'].'<br/>'.
                            $user->lang['rp_chtime_header'].': '.$statuss['chtime'].'<br/>'.
                            $user->lang['level'].': '.$attendee['level'].'<br/>'.$statuss['uc_tooltip'];
              
              // wildcard expire:
              if($statuss['wcexpire'] && ($statuss['wcexpire']+($conf['rp_wcexpirrmve']*3600) > $stime->DoTime())){
              	$tmpwcexpire = $stime->DoDate($conf['timeformats']['medium'], $statuss['wcexpire']+($conf['rp_wcexpirrmve']*3600));
              }else{
              	$tmpwcexpire = $user->lang['rp_wcexpired'];
              }
              
              // the right click menu
              $memrightarray = array();
              if(count($this->twinks[$statuss['user_id']]) > 0){
                $twinklink = "admin/action.php" . $SID . "&".URI_RAID."=".$this->url_id."&old_memid=".$statuss['id']."&new_memid=";
                foreach($this->twinks[$statuss['user_id']] as $twinks){
                  if($this->raid['raid_distribution'] == 1){
                    // Role distribution
                    foreach($rpclass->RoleConf() as $rolearray){
                			if(in_array($twinks['class_id'], $rolearray['classes'])){
                			  if($statuss['role'] != $rolearray['name_en']){
                          $memrightarray[strtolower($twinks['id']).'_'.$rolearray['name_en']] = array(
                                    'image'   => 'images/viewraid/personal.png',
                                    'name'    => $twinks['name'].' ['.$rolearray['name'].']',
                                    'jscode'  => "window.location.href='".$twinklink.$twinks['id']."&role=".$rolearray['name_en']."'"
                                );
                        }
                      }
                    }
                  }else{
                    if($statuss['name'] != $twinks['name']){
                      // class/ no distribution
                      $memrightarray[strtolower($twinks['id'])] = array(
                                  'image'   => 'images/viewraid/personal.png',
                                  'name'    => $twinks['name'].' ['.$twinks['class'].']',
                                  'jscode'  => "window.location.href='".$twinklink.$twinks['id']."'"
                                );
                    }
                  }
                }
              }
              
              //Group Number
  						if($statuss['group'] && !$statuss['savedgroup']){
                $membgroup = $statuss['group'];
              }elseif($statuss['savedgroup']){
                $membgroup = $statuss['savedgroup'];
              }else{
                $membgroup = '0';
              }
              
              
              // the right click group menu
              $grplink = "admin/action.php" . $SID . "&".URI_RAID."=".$this->url_id."&name=".$statuss['name']."&member_id=".$statuss['id']."&group=";
              $rpgroupsarry = array(
                0 => array(
                    'name'    => $user->lang['rp_nogroup'],
                    'link'    => $grplink."100",
                    'img'     => 'group/group0.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                1 => array(
                    'name'    => $user->lang['rp_group'].' 1',
                    'link'    => $grplink."1",
                    'img'     => 'group/group1.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                2 => array(
                    'name'    => $user->lang['rp_group'].' 2',
                    'link'    => $grplink."2",
                    'img'     => 'group/group2.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                3 => array(
                    'name'    => $user->lang['rp_group'].' 3',
                    'link'    => $grplink."3",
                    'img'     => 'group/group3.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                4 => array(
                    'name'    => $user->lang['rp_group'].' 4',
                    'link'    => $grplink."4",
                    'img'     => 'group/group4.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                5 => array(
                    'name'    => $user->lang['rp_group'].' 5',
                    'link'    => $grplink."5",
                    'img'     => 'group/group5.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                6 => array(
                    'name'    => $user->lang['rp_group'].' 6',
                    'link'    => $grplink."6",
                    'img'     => 'group/group6.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                7 => array(
                    'name'    => $user->lang['rp_group'].' 7',
                    'link'    => $grplink."7",
                    'img'     => 'group/group7.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                8 => array(
                    'name'    => $user->lang['rp_group'].' 8',
                    'link'    => $grplink."8",
                    'img'     => 'group/group8.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    ),
                9 => array(
                    'name'    => $user->lang['rp_group'].' 9',
                    'link'    => $grplink."9",
                    'img'     => 'group/group9.png',
                    'perm'    => $user->check_auth('a_raidplan_update', false),
                    )
              );
  
						$tpl->assign_block_vars('raidplanrow.classes.status', array(
							'ID'						=> $statuss['id'],
							'COUNT'						=> $statuss['count'],
							'LINK'						=> $statuss['link'],
							'LNAME'						=> $statuss['name'],
							'GROUPIMG'					=> ($membgroup) ? 'group'.$membgroup.'.png' : 'group0.png',
							'GROUPNR'					=> ($membgroup != '0') ? $user->lang['rp_groupnr'].' '.$membgroup : $user->lang['rp_no_group'],
							'RAIDID'					=> '&amp;'.URI_RAID."=".$this->url_id,
							'TOGGLE_LINK'				=> "admin/action.php" . $SID . "&amp;mode=confirm&amp;". URI_RAID."=".$this->url_id."&amp;member_id=".$statuss['id'],
							'UNLOCK_LINK'				=> "admin/action.php" . $SID . "&amp;mode=unlock&amp;".  URI_RAID."=".$this->url_id."&amp;member_id=".$statuss['id'],
							'DELETE_LINK'				=> "admin/action.php" . $SID . "&amp;mode=delete&amp;".  URI_RAID."=".$this->url_id."&amp;member_id=".$statuss['id'],
							'NAME'						=> (($statuss['member_status'] == '0') ? '<i>' . $statuss['name'] . '</i>' : $statuss['name']),
							'RANDOM'					=> $statuss['random'],
							'ROLL_TT'					=> $khrml->HTMLTooltip($statuss['random'], 'rp_tt_roll'),
							'INFO_TT'					=> $khrml->HTMLTooltip($tmpinfott, 'rp_tt_info'),
							'CHAR_TT'					=> $khrml->HTMLTooltip($statuss['uc_tooltip'], 'rp_tt_info'),

							'NOTE'						=> $khrml->HTMLTooltip($rpclass->nl2br2(addslashes($statuss['note'])),'rp_tt_note'),
							'SHOWNOTE'					=> (trim($statuss['note'])) ? true : false,
							'ADMINNOTE'					=> ( $statuss['note_lvl'] == 1) ? true : false,
							'GRPLNOTE'					=> ( $statuss['note_lvl'] == 2) ? true : false,

							'CLASS_EN'					=> strtolower($rpconvert->classname($statuss['class_name'])),
							'CLASS_ICON'				=> $rpclass->ClassIcon($statuss['class_name'], $statuss['class_id']),
							'STATUS'					=> $statuss['status'],
							'RANK'						=> $statuss['rank'],
							'GROUP'						=> $statuss['group'],
							'MEMBER_STATUS'				=> $statuss['member_status'],
							'RANK_PREFIX'				=> $statuss['rank_prefix'],
							'RANK_SUFFIX'				=> $statuss['rank_suffix'],
							'TIME'						=> $statuss['time'],
							'IS_GROUPLEADER'			=> ($memisop[$statuss['class_name']] == 1) ? true : false,
							'WILDCARD'					=> (isset($statuss['wildcard'])) ? true : false,
							'WCEXPIRE'					=> ($conf['rp_wcexpire'] == 1) ? $khrml->HTMLTooltip($rpclass->nl2br2(addslashes($tmpwcexpire)),'rp_tt_note', 'wildcard.png') : '',
							'MENU_GROUP'				=> $rpclass->SingleDropDownMenu('groupmenu_'.$statuss['id'], $statuss['id'], $rpgroupsarry),
							'JS_MEM_MENU'				=> ($rpclass->AdminAccess()) ? $jquery->RightClickMenu($statuss['id'], 'span.membername'.$statuss['id'], $memrightarray) : '',
						));
              } // end foreach statuss
            }   // end check if nil
          }     // end class count
        }       // end foreach classes
      }         // end for loop (status)
    // ---------------------------------------------------------
    // End Classlist. Start ....................................
    // ---------------------------------------------------------

		// If user is allowed to sign in and has members assigned
		if ($user->check_auth('u_raidplan_view', false) && count($this->members)>0){
			$subscribed_member_id = '';
			$signin_member_random = 0;
			$jsmemcode = 'var values = new Array();';
			

      $ssql = "SELECT member_id, role FROM ".RP_SAVEDUSER_TABLE." WHERE user_id='".$user->data['user_id']."'";
      $result = $db->query($ssql);
      $row = $db->fetch_record($result);
      
      $defchar_assigned = ($row['member_id']) ? true : false;
      
      foreach($rpclass->RoleConf() as $rolearray){
        $roledropdwn[$rolearray['name_en']] = $rolearray['name'];
      }
      
			foreach ($this->members as $membercheck){
        if($this->raid['raid_distribution'] == 1){
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
				}
				
				if (empty($whatcanibe) && $this->raid['raid_distribution'] == 1){
          $wrong_roledistri = true;
        }
				
        // Generate the Dropdown values
        $drpdwn_members[$membercheck['id']] = $membercheck['name'].' ['.$membercheck['class_name'].']';
				$drpdwn_members_select = ($row['member_id'] && !$drpdwn_members_select) ? $row['member_id'] : $drpdwn_members_select;
				$drpdwn_members_select = (!empty($drpdwn_members_select)) ? $drpdwn_members_select : $membercheck['id'];
        $dropdown_role_select  = $row['role'];
        if (!is_null($membercheck['subscribed'])){
					$tpl->assign_vars(array(
						'PLAYER_NOTE'						=> $membercheck['note'],
						'SUBSCRIBED_MEMBER_ID'	=> $membercheck['id']));
					$subscribed_member_id 		=  $membercheck['id'];
					$signin_member_random 		=  $membercheck['random'];
				}
			}
			
			//$tpl->assign_vars(array( 'S_DEADLINE_REACHED' => true));
			$deadline_reached = true;
			if ($this->raid['raid_date_subscription'] > $stime->DoTime()){     // If sign in date is NOT expired
				$deadline_reached = false;
				if ($subscribed_member_id){     // If the user already signed in
					if ($signin_member_random > 0){
					 $this->val_subscription = false;    // the member is signed in
					 $this->val_update       = true;     // update the entry
					}else{
					 $this->val_subscription = false;
					 $this->val_update       = false;
					}
				}else{
				  $this->val_subscription = true;
					$this->val_update       = false;
				} 
		  }else{
		    // the user is subsribed && should be able to change the data until xx minutes before the raid
		    $minutes2midnight = ($conf['rp_chsigndvalue']) ? ($conf['rp_chsigndvalue']*60) : (60*30);
		  	if ($subscribed_member_id && $signin_member_random > 0 && ($this->raid['raid_date_invite']-$minutes2midnight) > $stime->DoTime()){
          $this->val_subscription = false;
					$this->val_update       = true;
					$this->val_delete       = ($conf['rp_changesigned'] == 1) ? true : false;
					$deadline_reached = false;
				}
		  }  
		}else{
      $tpl->assign_vars(array('S_NO_USER_ASSIGN' => true));
    }

		// Charmanager hook :)
    if ($this->cmapi_installed){
      $outp_no_user_assigned = $user->lang['rp_no_user_assigned_cm1'].
		 													  '<a href="'.$eqdkp_root_path .'plugins/charmanager/index.php">'.$user->lang['rp_no_user_assigned_cm2'].'</a> '.
		 														$user->lang['rp_no_user_assigned_cm3'];
    }else{
      $outp_no_user_assigned 	= $user->lang['rp_no_user_assigned'];
    }
		// end of Charmanager Hook

		If(count($this->classes)>0 && $conf['rp_enable_classbrk'] != 1){
			$tpl->assign_vars(array(
							'COLUMN_WIDTH' 				=> str_replace(',','.',100/count($this->classes)),
							'L_NO_USER_ASSIGNED'	=> $outp_no_user_assigned,
			));
		}elseif(count($this->classes)>0 && $conf['rp_enable_classbrk'] == 1){
			$tpl->assign_vars(array(
							'COLUMN_WIDTH' 				=> str_replace(',','.',100/$conf['rp_breakvalue']),
							'L_NO_USER_ASSIGNED'	=> $outp_no_user_assigned,
			));
		}else{ 
			$tpl->assign_vars(array(
							'COLUMN_WIDTH' 				=> str_replace(',','.',100),
							'L_NO_USER_ASSIGNED'	=> $user->lang['rp_class_distribution_not_set'],
			));
		}
		$raidicon = $rpclass->generateRaidIcon($this->raid['raid_name']);
			
			// Group Array
			$grparray = array(
                  0   => $user->lang['rp_none'],
                  1   => $user->lang['rp_group'].' 1',
                  2   => $user->lang['rp_group'].' 2',
                  3   => $user->lang['rp_group'].' 3',
                  4   => $user->lang['rp_group'].' 4',
                  5   => $user->lang['rp_group'].' 5',
                  6   => $user->lang['rp_group'].' 6',
                  7   => $user->lang['rp_group'].' 7',
                  8   => $user->lang['rp_group'].' 8',
                  9   => $user->lang['rp_group'].' 9',
                  99  => $user->lang['rp_ungrouped']
      );
			
			// The Group filter row
      foreach ($grparray as $key => $value) {
        $tpl->assign_block_vars('gfilter_row', array(
              'VALUE'    => $key,
              'SELECTED' => ( $_GET['group'] == $key ) ? ' selected="selected"' : '',
              'OPTION'   => $value
          ));
        }
			
			// Check for the Newsletter Bridge:
			if($pm->check(PLUGIN_INSTALLED, 'newsletter') && $user->check_auth('a_newsletter_manage', false)){
				if($pm->get_data('newsletter', 'version') >= '1.1.0'){
					$nl_enabled = true;
				}else{
					$nl_enabled = false;
				}
			}else{
				$nl_enabled = false;
			}
			if($this->guests){			 
	      foreach($this->guests as $guuests){
	      if($user->check_auth('a_raidplan_update', false)){
	        $guest_tt = $user->lang['rp_admin_gclickedit'].'<br/><br/>';
	      }else{
          $guest_tt = '';
        }
         $guest_tt .= $user->lang['rp_time_header'].": ".$guuests['signup_time']."<br/>".
                      $guuests['class_name']."<br/>".
                      $rpclass->nl2br2(addslashes($guuests['note']))."<br/>";
					$tpl->assign_block_vars('guests', array(
						'NAME'			=> $guuests['name'],
						'ID'        => $guuests['id'],
						'TOOLTIP'   => $khrml->HTMLTooltip($guest_tt, 'rp_tt_info')
					));
				}
			}
      
      $memisop = $rpclass->CheckPermGroupLeader();
      
      // Dropdown Menu Array
      $rpoptionsarry = array(
              0 => array(
                  'name'    => $user->lang['rp_addraid'],
                  'link'    => 'javascript:AddRaid()',
                  'img'     => 'menu/add.png',
                  'perm'    => $user->check_auth('a_raidplan_update', false),
                  ),
              1 => array(
                  'name'    => $user->lang['rp_transform'],
                  'link'    => "javascript:TransformRaid('".$this->url_id."')",
                  'img'     => 'menu/transform.png',
                  'perm'    => $user->check_auth('a_raidplan_update', false),
                  ),
              2 => array(
                  'name'    => $user->lang['rp_export'],
                  'link'    => 'javascript:ExportDialog()',
                  'img'     => 'menu/export.png',
                  'perm'    => true,
                  ),
              3 => array(
                  'name'    => $user->lang['rp_editraid'],
                  'link'    => 'javascript:EditRaid()',
                  'img'     => 'menu/edit.png',
                  'perm'    => $user->check_auth('a_raidplan_update', false),
                  ),
              4 => array(
                  'name'    => $user->lang['rp_butn_sendmail'],
                  'link'    => $eqdkp_root_path.'plugins/newsletter/admin/send.php?template=1&raidplan_id='.$this->url_id,
                  'img'     => 'menu/newsletter.png',
                  'perm'    => $nl_enabled,
                  ),
              5 => array(
                  'name'    => $user->lang['rp_confirmall'],
                  'link'    => $eqdkp_root_path.'plugins/raidplan/viewraid.php'.$SID.'&amp;'.URI_RAID."=".$this->url_id.'&amp;confirmall=true',
                  'img'     => 'menu/confirmall.png',
                  'perm'    => $user->check_auth('a_raidplan_update', false),
                  ),
              6 => array(
                  'name'    => ($this->raid['raid_closed'] == 1) ? $user->lang['rp_open_raid'] : $user->lang['rp_close_raid'],
                  'link'    => ($this->raid['raid_closed'] == 1) ? $eqdkp_root_path.'plugins/raidplan/viewraid.php'.$SID.'&amp;'.URI_RAID."=".$this->url_id.'&amp;openraid=true' : $eqdkp_root_path.'plugins/raidplan/viewraid.php'.$SID.'&amp;'.URI_RAID."=".$this->url_id.'&amp;closeraid=true',
                  'img'     => ($this->raid['raid_closed'] == 1) ? 'menu/open.png' : 'menu/close.png',
                  'perm'    => $user->check_auth('a_raidplan_update', false),
                  ),
      );
    
    // Begin of JS Hide Code
    $jshidevaluet = array();
    for ($iii=0;$iii<5;$iii++){
      if( $conf['rp_vr_hide_stat'.$iii] != 1 ){
        $jshidevaluet[$iii] .= $iii;
      }
    }
    $jshidevalue = implode(",", $jshidevaluet);
		// End of JS Hide Code	
		
		// Status dropdown
    $statusdropdwn = array(
              'status1'    => $user->lang['rp_signup'],
              'status3'    => $user->lang['rp_not_sure'],
              'status2'    => $user->lang['rp_bunsign'],
    );
    
    if($this->val_update){
      $statusdropdwn = array_merge($statusdropdwn, array('status10'    => $user->lang['update']));
    }
    $stat10status = ($drpdwn_members_select) ? 'status10' : '';
		
		// JS only if Role Distribution:
		$roleJScode = ($this->raid['raid_distribution'] == 1) ? 'onChange="changeVal(this.value)"' : '';
    
    $tpl->assign_vars(array(
			// Form
			'F_ADD_RAID'					=> "viewraid.php" . $SID,
			'F_SAVE_NOTE'					=> "viewraid.php" . $SID.'&amp;r='.$this->url_id,
			'F_UPDMEMBER'         => "admin/action.php" . $SID,
      'RAID_ID'							=> $this->url_id,
      'RAID_CLOSED'         => ($this->raid['raid_closed'] == 1) ? true : false,
      
      // JS Code
      'JS_MEMCODE'          => $jsmemcode,
      'JS_START_ID'         => $drpdwn_members_select,
      'JS_ROLE'             => $dropdown_role_select,
		  'JS_ABOUT'            => $jquery->Dialog_URL('About', $user->lang['rp_about_header'], 'about.php', '380', '320'),
		  'JS_ADDRAID'          => $jquery->Dialog_URL('RPAddRaid', $user->lang['rp_addraid'], "admin/addraid.php?fe=1", '660', '440', 'viewraid.php?s=&r='.$this->url_id),
		  'JS_EDITRAID'         => $jquery->Dialog_URL('EDITRaid', $user->lang['rp_editraid'], "admin/addraid.php?s=&r=".$this->url_id."&fe=1", '660', '440', 'viewraid.php?s=&r='.$this->url_id),
      'JS_EXPORT'           => $jquery->Dialog_URL('RPExportVR', $user->lang['rp_export_header'], "modules/export/exports.php?raid_id=".$this->url_id, '640', '470'),
      'JS_EDITNOTE'         => $jquery->Dialog_URL('RPEditNote', $user->lang['rp_editnote'], "admin/editnote.php?member='+memberid+'&raid=".$this->url_id, '450', '140', 'viewraid.php?s=&r='.$this->url_id),
      'JS_ADDGUEST'         => $jquery->Dialog_URL('RPAddGuest', $user->lang['rp_addguest'], "admin/addguest.php?raid='+raidid+'", '490', '160', 'viewraid.php?s=&r='.$this->url_id),
      'JS_EDITGUEST'        => $jquery->Dialog_URL('RPEditGuest', $user->lang['rp_title_editguest'], "admin/addguest.php?raid='+raidid+'&edit='+id+'", '490', '160', 'viewraid.php?s=&r='.$this->url_id),
      'JS_USETTINGS'        => $jquery->Dialog_URL('RPUserSettings', $user->lang['rp_usersettings'], "usersettings.php".$SID."&fpopup=".$this->url_id, '640', '130', 'viewraid.php?s=&r='.$this->url_id),
      'JS_TRANSFORMRAID'    => $jquery->Dialog_URL('RPTransRaid', $user->lang['rp_transform'], "admin/transform.php?fe=1&r='+raidid+'", '400', '120', 'viewraid.php?s=&r='.$this->url_id),
      
      //CSS
      'RP_TEMPLATEPATH'     => $user->style['template_path'],
      'S_CSSGAME'           => $rpclass->SelectedGame(),
      
			// Data
			'RAID_NOTE_OFF'       => ($conf['rp_disable_note'] == 1) ? true : false,
			'SHOW_RAIDNOTE'       => (($conf['rp_ghidenotes'] == 1 && $user->data['username'] == '') || $conf['rp_disable_note'] == 1) ? false : true,
			'RAID_ICON_SHOW'			=> ( $raidicon['full'] && $conf['rp_cal_hico'] != 1) ? true : false,
			'RAID_ICON'						=> $raidicon['full'],
			'RAID_ADDED_BY'				=> $this->raid['raid_added_by'],
			'RAID_ADDED_DATE'			=> $stime->DoDate($conf['timeformats']['medium'], $this->raid['raid_date_added']),
			'RAID_DATE'						=> $stime->DoDate($conf['timeformats']['long'], $this->raid['raid_date']),
			'RAID_DATE_INVITE'		=> $stime->DoDate($conf['timeformats']['long'], $this->raid['raid_date_invite']),
			'RAID_DATE_FINISH'		=> ($this->raid['raid_date_finish']) ? $stime->DoDate($conf['timeformats']['long'], $this->raid['raid_date_finish']) : '',
			'RAID_NAME'						=> $this->raid['raid_name'],
			'RAID_NOTE'						=> stripslashes($this->raid['raid_note']),
			'RAID_LEADER'					=> ($this->raid['raid_leader']) ? $rpclass->MemberID2Name($this->raid['raid_leader']): '',
			'RAID_SIGNUP_DEADLINE'=> $stime->DoDate($conf['timeformats']['long'], $this->raid['raid_date_subscription']),
			'RAID_VALUE'					=> $this->raid['raid_value'],
			'RAID_LINK'						=> $this->raid['raid_link'],
			'ATTENDEES_COLSPAN'		=> count($this->classes),
			'GROUPLINK'						=> "admin/action.php" . $SID . "&group=",
			'GUESTCOUNT'					=> ($this->guestcount) ? $this->guestcount : 0,
			'SELECTED_GAME'       => $rpclass->SelectedGame(),
			'USER_ID'             => $user->data['user_id'],
			
			// Dropdown Menu
			'MENU_OPTIONS'        => $rpclass->SingleDropDownMenu('colortab', 'rpvroptions', $rpoptionsarry, $user->lang['rp_jsdm_options']),
			
			// Tooltips
			'UPDATEDON_TT'				=> $khrml->HTMLTooltip($user->lang['rp_raidchanged'].' '.$this->raid['raid_updated_by'].' '.$user->lang['rp_changed_on_date'].' '.$stime->DoDate($conf['timeformats']['medium'], $this->raid['raid_date_change']), 'rp_tt_updated'),
			'HELP_DEFCHAR'        => $khrml->HTMLTooltip($user->lang['rp_help_def_char'], 'rp_tt_lr'),
			
			// Admin Check
			'IS_EDITOR'						=> ($user->check_auth('a_raidplan_update', false)) ? true : false,
      'IS_AD_GL'            => ($user->check_auth('a_raidplan_update', false)) ? true : false,
      'IS_CL'               => (is_array($memisop)) ? true : false,

			// Switches
			'S_SHOW_RANKS'				=> ($conf['rp_show_ranks'] == '1') 	? true : false,
			'S_COLORED_MEMBER'		=> ($conf['rp_colored_memb'] == '1')? true : false,
			'S_SHOW_ROLL'					=> ($conf['rp_roll_systm'] == '1') 	? true : false,
			'S_SHOW_WILDCARD'			=> ($conf['rp_wildcard'] == '1') 		? true : false,
			'S_SHOW_CASSNAMES'		=> ($conf['rp_short_class'] == '1') ? true : false,
			'S_GROUPNUMBER'       => ($conf['rp_enable_groups'] == '1') ? true : false,
			'S_REMOVELOCK'        => ($conf['rp_removelock'] == '1') ? false : true,
			'S_JSHIDEVALUE'       => $jshidevalue,
			'S_GUESTS'            => ($conf['rp_use_guests'] == '1') ? true : false,
			'S_ROLL_TOOLTIP'      => ($conf['rp_rolltooltip'] == '1') ? true : false,
			'S_RAIDUPDATED'       => ($this->raid['raid_date_change']) ? true : false,
			'S_ROLE_DISTRI'       => ($this->raid['raid_distribution'] == 1) ? true : false,
			'S_LOGGEDIN_ROLE'     => ($this->raid['raid_distribution'] == 1 && $user->data['username'] && $drpdwn_members_select && !$deadline_reached) ? true : false,
      'S_DEADLINE_REACHED'  => $deadline_reached,
      'S_ROLE_DISTRI_ERROR' => ($wrong_roledistri) ? true : false,
      'S_SIGNED_IN'         => ($drpdwn_members_select && $user->data['username']) ? true : false,
      'S_NO_DEFAULTCHAR'    => ($defchar_assigned) ? false : true,
      
			// Submit Buttons
			'B_CHANGE'						=> ($i_am_signedin && $user->data['username']) ? $user->lang['update'] : $user->lang['rp_change_status'],
			'B_SAVE'							=> $user->lang['rp_save'],
			'B_ADDGUEST'          => $user->lang['rp_addguest'],
  
      // Dropdown
      'DRDWN_ROLE'			    => $khrml->DropDown('rp_role', $roledropdwn, '', '', 'id="rp_role"', 'input'),
      'DRDWN_MEMBERS'			  => $khrml->DropDown('member_id', $drpdwn_members, $drpdwn_members_select, '', $roleJScode, 'input'),
      'DRDWN_ACTION'			  => $khrml->DropDown('action', $statusdropdwn, $stat10status, '', '', 'input'),
  
			// Language
			'L_ADDALL'							=> $user->lang['rp_add_all'],
			'L_ADDED_BY'						=> $user->lang['added_by'],
			'L_ADDED_ON'						=> $user->lang['rp_raidaddedon'],
			'L_CONFIRMED'						=> $user->lang['rp_confirmed'],
			'L_DATE'								=> $user->lang['date'],
			'L_LEADER'							=> $user->lang['rp_leader'],
			'L_INVITE_TIME'					=> $user->lang['rp_invite_time'],
			'L_START_TIME'					=> $user->lang['rp_start_time'],
			'L_FINISH_TIME'					=> $user->lang['rp_finish_time'],
			'L_NOTE'								=> $user->lang['note'],
			'L_NO_RAIDNOTE'					=> $user->lang['rp_noraidnote'],
			'L_RAID'								=> $user->lang['raid'],
			'L_CHAR'         				=> $user->lang['rp_char'],
			'L_ROLLED'							=> $user->lang['rp_rolled'],
			'L_SIGNED'							=> $user->lang['rp_signed'],
			'L_UNSIGNED'       			=> $user->lang['rp_unsigned'],
			'L_SIGNUP_DEADLINE'			=> $user->lang['rp_signup_deadline'],
			'L_NO_CLASS_SETTING'		=> $user->lang['rp_no_class_setup'],
			'L_VALUE'								=> $user->lang['value'],
			'L_TIME'								=> $user->lang['time'],
		  'L_DEADLINE_REACHED'		=> $user->lang['rp_deadline_reached'],
			'L_NOT_LOGGED_IN'				=> $user->lang['rp_not_logged_in'],
			'L_ROLE_DISTRI_ERROR'   => $user->lang['rp_role_distri_error'],
			'L_WILDCARD'						=> $user->lang['rp_wildcard'],
			'L_CONF_ONE_DOWN'				=> $user->lang['rp_one_down'],
			'L_CONF_CANC_CONF'			=> $user->lang['rp_cancel_confirmation'],
			'L_CONF_CONF_SIGNUP'		=> $user->lang['rp_confirm_signup'],
			'L_LINK'								=> $user->lang['rp_vr_link'],
			'L_GUESTS'              => $user->lang['rp_guests'],
			'L_ADDNOTE'             => $user->lang['rp_addnote'],
			'L_FILTER'              => $user->lang['filter'],
			'L_DEL_WARNING'         => $user->lang['rp_confirm_delete_guest'],
			'L_BUTTON_DELETE'       => $user->lang['rp_button_delete'],
			'L_BUTTON_CANCEL'       => $user->lang['rp_button_cancel'],
			'L_CLOSED_TEXT'         => $user->lang['rp_status_quit'],
			'L_OPEN_USETT_BTN'      => $user->lang['rp_open_usersettings'],
			'L_NO_DEFAULT_CHAR'     => $user->lang['rp_no_default_char'],
			'L_CREDITS'             => $user->lang['rp_credits'],
			'L_COPYRIGHT'           => $rpclass->Copyright(),
		));

		$eqdkp->set_vars(array(
			'page_title'         => $rpclass->GeneratePageTitle($this->raid['raid_name']),
			'template_file'      => 'viewraid.html',
			'template_path'      => $pm->get_data('raidplan', 'template_path'),
			'gen_simple_header'  => ($_GET['widget']) ? true : false,
      'display'            => true)
        );

	}
}

$myRaidList = new RaidPlan_View_Raid;
$myRaidList->process();
?>
