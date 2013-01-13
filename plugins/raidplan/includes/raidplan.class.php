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
 * $Id: raidplan.class.php 11626 2012-01-10 22:49:16Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("RaidplanAddition")){
  class RaidplanAddition
  {
      function RaidplanAddition(){
        global $eqdkp, $eqdkp_root_path, $user, $conf, $db;
    		$rpgfolders = new MyGames;
        $this->defaultGame  = $rpgfolders->GameName();

        $sql = 'SELECT * FROM ' . ROLES_TABLE;
        $result = $db->query($sql);

        while ($row = $db->fetch_record($result)){
		$this->rolesimg[$row['role_name']] = $row['role_image'];
          $this->roles[$row['role_image']] = array(
        		          'id'      => $row['role_id'],
          						'name'		=> $row['role_name'],
          						'name_en'	=> $row['role_image'],
          						'classes' => explode("|", $row['role_classes']),
          					);
        }
        $db->free_result($result);
      }

      function RoleConf($name=''){
        return ($name) ? $this->roles[$name] : $this->roles;
      }

    	function SelectedGame(){
      	return $this->defaultGame;
      }

	function ClassIcon($name, $classid, $distribution=false){
		global $eqdkp_root_path, $rpconvert;
		if($name){
			if($distribution == 1){
				$iconlink	= $eqdkp_root_path.'plugins/raidplan/images/roles/'.$this->rolesimg[$name].'.png';
			}else{
				 $iconlink = $eqdkp_root_path .get_ClassIcon($name, $classid, false, true);
			}
			return ($iconlink) ? '<img src="'.$iconlink.'" alt="" width="20px" height="20px" />' : '';
		}else{
			return true;
		}
	}

      function CheckPermGroupLeader(){
      	global $db, $conf, $user;
      	$memisop = false;

      	if($user->data['user_id']){
      	// Check if Groupleader
        $mmsql = 'SELECT m.member_rank_id, c.class_name AS member_class
                  FROM ' .CLASS_TABLE. ' c, ' . MEMBERS_TABLE . ' m
                  LEFT JOIN ' . MEMBER_USER_TABLE . ' mu
                  ON mu.member_id = m.member_id
                  WHERE mu.user_id = '.$user->data['user_id'].'
                  AND (m.member_class_id = c.class_id)
                  GROUP BY m.member_name
                  ORDER BY m.member_name';

         $memb_result = $db->query($mmsql);
        	while ( $memrow = $db->fetch_record($memb_result) ){
  					if($memrow['member_rank_id'] == $conf['rp_rank'] && $conf['rp_enabl_officr'] == 1){
  						// set the operator mode to enabled
  						$memisop[$memrow['member_class']] = 1;
  						$memisop['administrator'] = true;
  					}else{
  						$memisop[$memrow['member_class']] = 0;
  					}
  				}
  				$db->free_result($memb_result);
  			}
  			return $memisop;
      }

      function AdminAccess($block=''){
        global $user;
        // Load the Officer Rights
        $memisop = $this->CheckPermGroupLeader();
        // Check for Admin Rights / Officer Rights
        if(($user->check_auth('a_raidplan_update', false)) || (isset($memisop['administrator']) && $memisop['administrator'] == '1')){
          return true;
        // The rest...
        }else{
          return ($block) ? $user->check_auth('a_raidplan_update'): false;
        }
      }

      function CheckIfClone($multiple=false){
      	global $db, $user, $conf, $stime;
      	$sql = 'SELECT * FROM ' . RP_REPEAT_TABLE . ';';
      	$result = $db->query($sql);
      	$rep_value = ($conf['rp_rep_value']) ? $conf['rp_rep_value'] : 0;
  			if($result){
  			while ($urow = $db->fetch_record($result)){
      		$newewa = $db->query("SELECT * FROM " . RP_REPEAT_TABLE . " WHERE repeat_id='".$urow['repeat_id']."';");
      		$newrow = $db->fetch_record($newewa);

      		if($multiple == true){
      			// this is for the cronjob
      			$tmp_nwdate = $newrow['next_date'];
      			while($tmp_nwdate < ($stime->DoTime()+($rep_value*60*60*24))){
  						$newwrow = $db->fetch_record($newewa);
      				$tmp_nwdate = $newwrow['next_date'];
      				$this->CloneRaidDate($newrow['raid_id'], $newrow['repeat_id'], $newrow['delete_id']);
      			} // end first while
      		}else{
      			// this is for the file include
      			if($newrow['next_date'] < ($stime->DoTime()+($rep_value*60*60*24))){
      				$this->CloneRaidDate($newrow['raid_id'], $newrow['repeat_id'], $newrow['delete_id']);
      			} // end if
      		}
      	} // end while
      	$db->free_result($result);
      	}
      }

      function ChangeRepeatRable($id, $newdate, $newid){
      	global $db, $stime;
      	$sql = "UPDATE " . RP_REPEAT_TABLE . "
  							SET raid_id='".$newid."', next_date='".$newdate."',
  								  job_date='".$stime->DoTime()."'
  							WHERE repeat_id='" . $id."';";
        $db->query($sql);
      }

      function GenerateSeconds($input){
      	switch ($input) {
  				case '1'		: $repeatdays =  7;		break;
  				case '2'		: $repeatdays =  14;	break;
  			}
  			$repeat_seconds = $repeatdays*24*60*60;
  			return $repeat_seconds;
      }

      function CloneRaidDate($id, $repid, $delid){
      	global $db, $user, $class_name, $conf, $stime;

      	// get the old data
      	$rpsql = 'SELECT raid_id, raid_name, raid_date, raid_note, raid_value,
      						raid_attendees, raid_date_subscription, raid_date_invite,
      						raid_leader, raid_added_by, raid_updated_by, raid_repeat,
                  delete_id, raid_date_finish, raid_link, raid_closed, raid_distribution
          				FROM ' . RP_RAIDS_TABLE . '
  								WHERE raid_id=' . $id . ';';
  			$rpraids_result = $db->query($rpsql);
  			$rprow = $db->fetch_record($rpraids_result);
  			$db->free_result($rpraids_result);

  			if($rprow['raid_repeat'] && $rprow['raid_repeat'] != 0){
  			// Build the Days Table
  			$repeat_seconds  = $this->GenerateSeconds($rprow['raid_repeat']);
  			$dstcorrection   = $stime->DST_Correction($rprow['raid_date']+$repeat_seconds, $rprow['raid_date'], true);
  			$repeat_seconds  = ($dstcorrection) ? $repeat_seconds+$dstcorrection : $repeat_seconds;

  			// write a new entry
  			$query = $db->build_query('INSERT', array(
                  'raid_id'									=> 'NULL',
                  'raid_name'								=> $rprow['raid_name'],
  								'raid_date'								=> ($rprow['raid_date']+$repeat_seconds),
  								'raid_date_invite'				=> ($rprow['raid_date_invite']+$repeat_seconds),
  								'raid_date_subscription'	=> ($rprow['raid_date_subscription']+$repeat_seconds),
                  'raid_date_finish'				=> ($rprow['raid_date_finish']+$repeat_seconds),
                  'raid_date_added'					=> $stime->DoTime(),
                  'raid_note'								=> $rprow['raid_note'],
                  'raid_leader'							=> $rprow['raid_leader'],
                  'raid_value'							=> $rprow['raid_value'],
                  'raid_added_by'						=> $rprow['raid_added_by'],
  								'raid_attendees'					=> $rprow['raid_attendees'],
  								'raid_repeat'							=> $rprow['raid_repeat'],
  								'raid_link'								=> $rprow['raid_link'],
  								'delete_id'								=> $delid,
  								'raid_distribution'       => $rprow['raid_distribution'],
  			));
  			$db->query('INSERT INTO ' . RP_RAIDS_TABLE . $query);
  			$theid = $db->insert_id();

  				// Insert class counts
  				$csql = 'SELECT class_name, class_count
  								 FROM ' . RP_CLASSES_TABLE . "
  								 WHERE raid_id='".$delid."'";
  				$cresult = $db->query($csql);

  			while ($crow = $db->fetch_record($cresult)){
  						$cquery = $db->build_query('INSERT', array(
  															'raid_id'		  => $theid,
  															'class_name'	=> $crow['class_name'],
  															'class_count'	=> $crow['class_count'])
  														);
  						$db->query('INSERT INTO ' . RP_CLASSES_TABLE . $cquery);
  			}
  			$db->free_result($cresult);

  			// Auto Populate Attendees
  		if($conf['rp_autoaddbyrnk'] == 1 && isset($conf['rp_rank_add'])){
  		  $this->AddMembersByRank($theid, $rprow['raid_distribution'], $conf['rp_rank_add']);
  		}
  		// end of Auto Populate

  			// all done, change the repeate table
  			$this->ChangeRepeatRable($repid, ($rprow['raid_date']+$repeat_seconds), $theid);
  			return true;
  		}else{
  			return false;
  		}
    }

    function DeleteOldWildcard($memberid, $raidid){
      global $db, $user;
      // Delete old Wildcard if Member is confirmed for that event
    	// get the event name
    	$sql = 'SELECT raid_name
    					FROM ' . RP_RAIDS_TABLE . "
    					WHERE raid_id='".(int) stripslashes($raidid)."'";
    	$result = $db->query($sql);
    	$raid = $db->fetch_record($result);
    	$db->free_result($result);

    	// check if an wilcard exists
    	$sql = 'SELECT wildcard_id	FROM '.RP_WILDCARD_TABLE."
    					WHERE member_id='" . stripslashes($memberid) . "' AND event='".addslashes($raid['raid_name'])."'";
    	$result = $db->query($sql);
    	$wcrow = $db->fetch_record($result);
    	$db->free_result($result);

      // delete wildcard
      if($wcrow['wildcard_id']){
        $sql = 'DELETE FROM ' . RP_WILDCARD_TABLE . " WHERE wildcard_id='" . $wcrow['wildcard_id'] . "'";
        $db->query($sql);
      }
    }

    // remove all saved member groups
    function FlushMemberGroups(){
      global $db, $user;
      $sql = 'TRUNCATE TABLE ' . RP_MEMGROUPS_TABLE;
      $db->query($sql);
    }

    function ExpireWildCards(){
      global $conf, $db, $user, $stime;
        $wcdate = $stime->DoTime() + ($conf['rp_wcexpirrmve']*3600);
      	$sql = 'DELETE FROM ' . RP_WILDCARD_TABLE . " WHERE date>'" . $wcdate . "'";
        $db->query($sql);
        $sql = "UPDATE `".RP_CONFIG_TABLE."` SET config_value='".$stime->DoTime()."' WHERE config_name='rp_tmp_expvalue';";
        $db->query($sql);
    }

    function nl2br2($string){
      $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
      return $string;
    }

	function generateRaidIcon($iraidname){
		global $conf, $db, $user, $eqdkp_root_path, $instance_picture_arry, $eqdkp;
		$inst_icon = getEventIcon($iraidname,'32',true);
		return ($inst_icon) ? array('full'=> $eqdkp_root_path.$inst_icon, 'rss'=>$inst_icon) : false;
	}

    function MemberID2Name($id){
      global $db;
      return $db->query_first("SELECT member_name FROM ".MEMBERS_TABLE." WHERE member_id='".$id."'");
    }

    function User2Members($user_id, $sqlstatement){
      global $db;
      $mmsql = 'SELECT member_id FROM '.MEMBER_USER_TABLE.' WHERE user_id =\''.(int) $user_id.'\'';
      $memb_result = $db->query($mmsql);
      $members_array = array();
      while ( $memrow = $db->fetch_record($memb_result) ){
  		  $members_array[] = $memrow['member_id'];
      }
      if($sqlstatement){
        $cnumb = 1;
        if(count($members_array) > 0){
          $sql_data = (count($members_array) > 1) ? '(' : '';
          foreach($members_array as $memid){
            $sql_data .= ($cnumb == 1) ? "member_id='".$memid."'" : " OR member_id='".$memid."'";
            $cnumb++;
          }
          $sql_data .= (count($members_array) > 1) ? ')' : '';
        }
        return $sql_data;
      }else{
        return $members_array;
      }
    }

    function AddMembersByRank($raid_id, $role='', $rank=''){
      global $conf, $db, $user, $eqdkp_root_path, $eqdkp, $rpclass, $stime;

      $rank = ($rank) ? $rank : $conf['rp_rank_add'];
      $role = ($role) ? $role : '0';

      // get all members of that rank
      $mmsql = 'SELECT m.member_name, m.member_id, mu.user_id, c.class_name AS member_class
                FROM ' .CLASS_TABLE. ' c, ' . MEMBERS_TABLE . ' m
                LEFT JOIN ' . MEMBER_USER_TABLE . ' mu
                ON mu.member_id = m.member_id
                WHERE m.member_rank_id =\''.(int) $rank.'\'
                AND m.member_class_id = c.class_id
                GROUP BY m.member_name
                ORDER BY m.member_name';
      $memb_result = $db->query($mmsql);

      $members_array = array();
      while ( $memrow = $db->fetch_record($memb_result) ){
        if($memrow['user_id']){
  		    $members_array[$memrow['member_id']] = $memrow['user_id'];
  		  }
  		}
  		$db->free_result($memb_result);

  		// Insert all Members with that rank
  		$user2member = array();
      foreach($members_array as $member_id=>$user_id){
        // Only one member per User!
        if(in_array($user_id,$user2member)){
          continue;
        }else{
          array_push($user2member, $user_id);
        }

        // Check if Auto Cofirm
  		  if($conf['rp_autoaddconf'] == 1){
  		    $rpclass->DeleteOldWildcard($member_id, $raid_id);  // Delete old Wildcard if Member is confirmed for that event
        }

        // role shit
        if($role == '1'){
         $tmp_role = $db->query_first("SELECT role FROM ".RP_SAVEDUSER_TABLE." WHERE user_id='".$user_id."'");
         if(!$tmp_role){
          continue;
         }
        }else{
          $tmp_role = $role;
        }

        srand((double)microtime()*1000000);
  			$query = $db->build_query('INSERT', array(
  				'raid_id'								=> $raid_id,
  				'member_id'     				=> $member_id,
  				'attendees_subscribed'	=> ($conf['rp_autoaddconf'] == 1) ? '0' : '1',
  				'attendees_signup_time' => $stime->DoTime(),
  				'attendees_change_time' => $stime->DoTime(),
  				'attendees_random'			=> rand(1,100),
  				'role'                  => $tmp_role,
        ));
  			$db->query('INSERT INTO ' . RP_ATTENDEES_TABLE . $query);
  		}
    }

    function implode_with_options($assoc_array, $prefix = '', $vwrap = '', $seperator = ''){
      foreach ($assoc_array as $k => $v){
        $tmp[] = $vwrap . $v . $vwrap;
      }
      return $prefix . implode($seperator, $tmp);
    }

    // Get event name by ID
    function IdToEventName($id){
      global $db;
      $sql = "SELECT raid_name FROM ".RP_RAIDS_TABLE." WHERE raid_id='" . (int) $id . "'";
  		$result = $db->query($sql);
  		$rowww = $db->fetch_record($result);
  		$ev_name = $rowww['raid_name'];
  		$db->free_result($result);
  		return $ev_name;
    }

    // Update the Note
    function notes_update($id, $notevalue){
      global $db;
      $query = $db->build_query('UPDATE', array(
  				                          'raid_note'     => str_replace("'", "\'", stripslashes($notevalue)),
  			                       ));
  		$db->query('UPDATE '.RP_RAIDS_TABLE.' SET '.$query." WHERE raid_id='".$id."'");
  	}

  	/**
  	* Determine this raid's value
  	*
  	* @param $raid_name
  	* @return string Raid value
  	*/
  	function get_raid_value($raid_name){
      global $db;

      // Check if they entered a one-time value; Get the preset value of the raid(s) if not
      if ( empty($_POST['raid_value']) ){
        $raid_value = $db->query_first('SELECT event_value FROM ' . EVENTS_TABLE . " WHERE event_name='" . addslashes($raid_name) . "'");
      }else{
        $raid_value = $_POST['raid_value'];
      }

      // Still no post value?
      if ( empty($raid_value) ){
        $raid_value = '0.00';
      }
      return $raid_value;
    }

    // join an Array
    function join_array($glue, $pieces, $dimension = 0){
      $rtn = array();
  		foreach($pieces as $key => $value){
  			if(isset($value[$dimension])){
  				$rtn[] = $value[$dimension];
  			}
  		}
  		return join($glue, $rtn);
  	}

  	function DataOrNull($value, $numera=true){
      $output = ($numera) ? 0 : '--';
      return ($value) ? $value : $output;
    }

  	function count_repeat_values($needle, $array){
      if(is_array($array)){
        foreach($array as $key=>$value){
          if($value == $needle){
            $needle_array[] = $key;
          }
        }
      }
      return count($needle_array);
    }

  	function countFutureDates($array1, $startdate, $funcname= 'raw_date'){
      $this->startdate2  = $startdate;
      $this->tmpfuncname = $funcname;
      if(is_array($array1)){
        return count(array_filter($array1, array($this, 'fxxxxzzztttzzz2')));
      }else{
        return false;
      }
    }

  	function countBetweenDates($array1, $startdate, $enddate){
      global $stime;
      $this->enddate    = ($enddate) ? $enddate : $stime->DoTime();
      $this->startdate  = $startdate;
      if(is_array($array1)){
        return count(array_filter($array1, array($this, 'fxxxxzzztttzzz')));
      }else{
        return false;
      }
    }

  	function GeneratePageTitle($name){
      global $user, $eqdkp;
      return sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['rp_raidplaner'].' - '.$name;
    }

  	function Copyright(){
      global $pm, $user, $conf, $stime;
      $rp_version = ($conf['rp_hideversion']) ? '' : ' '.$pm->get_data('raidplan', 'version');
      $rp_status  = (strtolower($pm->plugins['raidplan']->vstatus) == 'stable' && !$conf['rp_hideversion']) ? '' : ' '.$pm->plugins['raidplan']->vstatus.' ';
      return $user->lang['raidplan'].$rp_version.$rp_status.' &copy; 2006 - '.date("Y", $stime->DoTime()).' by '.$pm->plugins['raidplan']->copyright;
    }

    // The functions for the action.php
    function DeleteGuest($guestid, $raidid){
      global $db;

      $delsql = "DELETE FROM ".RP_TEMPMEMBERS_TABLE." WHERE tempmember_id= '". (int) $guestid."' AND raid_id='".(int) $raidid."'";
      $db->query($delsql);
    }

    function ToggleMemberGroup($group, $raidid, $membername){
      global $db, $user, $conf, $stime;

        $tempgroup = ($group && $group == 100) ? '' : $group;

       	$sql = "SELECT attendees.member_id as member_id, raid_id
  				      FROM " . RP_ATTENDEES_TABLE . " as attendees, " . MEMBERS_TABLE. " as members
  				      WHERE raid_id='" . (int) $raidid . "'
  				      AND member_name='" . @mysql_real_escape_string($membername) . "'
  				      AND attendees.member_id=members.member_id";

  			$result = $db->query($sql);
  			if (!$row = $db->fetch_record($result)) { message_die($user->lang['error_invalid_raid_provided']); }
  			$db->free_result($result);

        $sssql = "SELECT raid_name FROM ". RP_RAIDS_TABLE ."
  				        WHERE raid_id='" . (int) $raidid . "'";
  		  $ssresult = $db->query($sssql);
  		  $rowww = $db->fetch_record($ssresult);
  		  $db->free_result($ssresult);
  		  $ev_name = $rowww['raid_name'];

       $sql = "UPDATE ". RP_ATTENDEES_TABLE ."
  						SET `group`='" .(int) $tempgroup."'
  						WHERE member_id='".$row['member_id']."'
  						AND raid_id='".$row['raid_id']."'";
      $db->query($sql);

      // save the groups permanent
      if($conf['rp_savegroups'] == 1){
        // remove saved group first
        $sql = "DELETE FROM ". RP_MEMGROUPS_TABLE ."
  						  WHERE member_id='".$row['member_id']."'
                AND groupevent='".$ev_name."'";
        $db->query($sql);

        // insert new one :)
        $query = $db->build_query('INSERT', array(
                  'member_id'       => $row['member_id'],
  								'`group`'         => $tempgroup,
  								'groupevent'      => $ev_name,
  								'date'            => $stime->DoTime(),
  			         ));
        $db->query('INSERT INTO ' . RP_MEMGROUPS_TABLE . $query);
      }
    }

    function ToggleMemberStatus($memberid, $raidid, $mode, $status, $role){
      global $db, $user, $conf, $stime, $logmanager, $eqdkp_root_path;

  		if (isset($memberid) && isset($raidid) && isset($mode) && $mode == 'delete'){
        if($memberid){
          $sql = "DELETE FROM ".RP_ATTENDEES_TABLE."
  						  WHERE member_id='" .(int) $memberid."'
  						  AND raid_id='".(int) $raidid."'";
        }
        $db->query($sql);
  		}elseif (isset($memberid) && isset($raidid) && isset($mode) && $mode != 'delete'){
        If ($status == 4){
  				// the note part
  				if(!$conf['rp_disbl_admnnte'] == 1){
  					if($user->check_auth('a_raidplan_update', false)){       // The user is an Admin
  						$notevalue = sprintf($user->lang['rp_admin_set'], $user->data['username']);
  						$notelevel = 1;
  					}else{         // the user is a GroupLeader
  						$notevalue = sprintf($user->lang['rp_grpl_set'], $user->data['username']);
  						$notelevel = 2;
  					}
  				}else{
  					$notevalue = '';
  					$notelevel = 0;
  				}

  				// This is the first subscription with this member
        	srand((double)microtime()*1000000);
  				$query = $db->build_query('INSERT', array(
  				'raid_id'								  => stripslashes($raidid),
  				'member_id'     				  => stripslashes($memberid),
  				'attendees_subscribed'	  => 1,
  				'attendees_signup_time'   => $stime->DoTime(),
  				'attendees_change_time'   => $stime->DoTime(),
  				'role'                    => (isset($role)) ? stripslashes(ucfirst($role)) : '',
  				'attendees_random'			  => rand(1,100),
  				'attendees_statchangedby' => $user->data['username'],
  				'attendees_note_lvl'      => $notelevel,
  				'attendees_note'				  => $notevalue,
          ));
  				$db->query('INSERT INTO ' . RP_ATTENDEES_TABLE . $query);
  			}

  			$sql = "SELECT attendees_subscribed, attendees_random, member_id, raid_id
        				FROM " . RP_ATTENDEES_TABLE . "
        				WHERE raid_id='" . (int) $raidid."'
        				AND member_id='" . (int) $memberid."'";

  			$result = $db->query($sql);
  			if (!$row = $db->fetch_record($result)) { message_die($user->lang['error_invalid_raid_provided']); }
  			$db->free_result($result);

  			switch(stripslashes($status)){
  				case '0' : $confirmed = '1';break;
  				case '1' : $confirmed = '2';break;
  				case '2' : $confirmed = '3';break;
  				case '3' : $confirmed = '4';break;
  				case ''  : $confirmed = '0';break;
  			}

  			//$confirmed = ($row['attendees_subscribed'] > '0') ? '0' : '1';
  			$random 	 = ($row['attendees_random'] < 0) ? $row['attendees_random'] * -1 : $row['attendees_random'];

  			switch(stripslashes($mode))
  			{
  				case 'confirm':
  					$sql = "UPDATE " . RP_ATTENDEES_TABLE . "
  						      SET attendees_subscribed='" . $confirmed . "'
  						      WHERE member_id='" . $row['member_id'] . "'
  						      AND raid_id='" . $row['raid_id']  . "'";

  					// Send confirmation Email:
  					if ($conf['rp_send_cemail'] == 1 && $confirmed == '0') {
              include_once($eqdkp_root_path.'/plugins/raidplan/includes/email/email.class.php');
              $mail = new MyMailer();
              $RP_server_url = $mail->BuildLink();

              // Load the raid information
              $asql  = 'SELECT raid_note, raid_name, raid_date FROM '.RP_RAIDS_TABLE." WHERE raid_id='".$row['raid_id']."'";
              $aresult    = $db->query($asql);
  		        $raid_info  = $db->fetch_record($aresult);
              $db->free_result($aresult);

              // Load the Users to send mail to
              $asql  = 'SELECT u.user_email, u.username FROM '.USERS_TABLE." u, ".MEMBER_USER_TABLE." m WHERE u.user_id=m.user_id AND m.member_id='".(int) $memberid."'";
              $asql .= ($conf['rp_activeonly_sn'] == 1) ? " AND user_active='1'" : '';
              $aresult    = $db->query($asql);
  		        $emailrow   = $db->fetch_record($aresult);
              $db->free_result($aresult);

              // Insert the Content in the body:
              $bodyvars = array(
                  'USERNAME'    => $emailrow['username'],
                  'RAID_NAME'   => $raid_info['raid_name'],
                  'RAID_NOTE'   => $raid_info['raid_note'],
                  'ADMIN_USER'  => $this->admin_user,
                  'RAID_LINK'   => $RP_server_url."plugins/raidplan/viewraid.php?s=&r=".$row['raid_id'],
                  'DATE'        => $stime->DoDate($conf['timeformats']['short'], $raid_info['raid_date']),
              );

              $mail->SendMailFromAdmin($emailrow['user_email'], $user->lang['rp_sendconfirmheader'], 'att_confirmed.html', $bodyvars, $conf['rp_mailmethod']);
            }

            if($confirmed == 0){
  					  $this->DeleteOldWildcard($row['member_id'], $row['raid_id']);
  					}
  					break;
  				case 'unlock':
  					$sql = "UPDATE " . RP_ATTENDEES_TABLE . "
  						      SET attendees_random=" . $random . "
  						      WHERE member_id='" . $row['member_id'] . "'
  						      AND raid_id='" . $row['raid_id']  . "'";
  					break;
  				default:
  					message_die($user->lang['error_invalid_mode_provided']);
  					break;
  			}
  			$db->query($sql);

    		// LogManager entry
    		$status2name = array('{L_LOG_STATUS_0}','{L_LOG_STATUS_1}','{L_LOG_STATUS_2}','{L_LOG_STATUS_3}');
    		$rplog_action = array(
            'header'          => '{L_LOG_ADMIN_CHANGE_STAT}',
            '{L_LOG_MEMBER}'  => $this->MemberID2Name($row['member_id']),
            '{L_LOG_STATUS}'  => $status2name[$status],
            '{L_LOG_ADMIN}'   => $user->data['username'],
        );
    		$logmanager->AddEntry($row['raid_id'], '{L_LOG_ADMIN_CHANGE_STAT}', $rplog_action);

  		}else{
  			if (!isset($raidid)) { message_die($user->lang['error_invalid_raid_provided']); }
  			if (!isset($mode))   { message_die($user->lang['rp_error_invalid_mode_provided']); }
  		}
  	}

    function ToggleTwinks($raidid, $new_memid, $old_memid, $role){
      global $db;

      $sql = "UPDATE " . RP_ATTENDEES_TABLE . "
  						SET member_id=" . $new_memid . ", role='" . $role . "'
  						WHERE member_id='" . $old_memid . "'
  						AND raid_id='" . $raidid  . "'";
      $db->query($sql);
    }

    // Transform the planned & finished raid to a dkp raid
    function TransformRaid($raidid, $options){
      global $db, $eqdkp_root_path, $stime;

      // Set the Options
      if($options[0]){
  		  $myselection[] = "attendees.attendees_subscribed='0'";
  		}
  		if($options[1]){
  		  $myselection[] = "attendees.attendees_subscribed='1'";
  		}
  		if($options[2]){
  		  $myselection[] = "attendees.attendees_subscribed='3'";
  		}

      $sql_selection = implode(" OR ", $myselection);

      $sql        = 'SELECT raid_id, raid_name, raid_date, raid_date_finish, raid_note, raid_value, raid_attendees
  				          FROM ' . RP_RAIDS_TABLE . "
  				          WHERE raid_id='" . (int) $raidid . "'";
  		$result     = $db->query($sql);
  		$row        = $db->fetch_record($result);
      $db->free_result($result);

      // The duration of the raid
  		$duration   = $stime->datediff($row['raid_date_finish'],$row['raid_date']);

      // Build the Attendees Array
      $sql = "SELECT attendees.member_id, members.member_name
              FROM ".RP_ATTENDEES_TABLE." as attendees, ".MEMBERS_TABLE." as members
              WHERE raid_id='" .(int) $raidid . "'
  			      AND attendees.member_id=members.member_id
  			      AND (".$sql_selection.")
              ORDER BY members.member_name DESC";
  		$result = $db->query($sql);
  		while ($row2 = $db->fetch_record($result)){
        $attendees[]  = $row2['member_name'];
      }

      $htmlcode   = '<html>
                     <body onload="document.transform.submit();">
                      <form method="post" action="'.$eqdkp_root_path.'admin/addraid.php?nohead=true&s=" name="transform">
                      <input name="raid_name[]" value="'.$row['raid_name'].'" type="hidden">
                      <input name="raid_value" value="'.$row['raid_value'].'" type="hidden">
                      <input name="raid_attendees" value="'.implode("\n", $attendees).'" type="hidden">

                      <input name="mo" size="3" value="'.$stime->DoDate('%m', $row['raid_date']).'" id="mo" type="hidden">
                      <input name="d" size="3"  value="'.$stime->DoDate('%d', $row['raid_date']).'" id="d" type="hidden">
                      <input name="y" size="5" value="'.$stime->DoDate('%Y', $row['raid_date']).'" id="y" type="hidden">
                      <input name="raid_date" value="'.$stime->DoDate('%d.%m.%Y', $row['raid_date']).'" type="hidden"/>

                      <input name="h" size="3" value="'.$duration['hours'].'" id="h" type="hidden">
                      <input name="mi" size="3"  value="'.$duration['minutes'].'" id="mi" type="hidden">
                      <input name="s" size="3"  value="'.$duration['seconds'].'" id="s" type="hidden">

                     </form>
                     </body>
                     </html>';

      return $htmlcode;
    }

    function IDlist2Classlist($list){
      global $db, $rpconvert;
      if(!is_array($this->idlist)){
        $rasql = 'SELECT class_id, class_name
                  FROM ' . CLASS_TABLE . '
                  WHERE class_id > 0
                  ORDER BY class_id';
        $result2 = $db->query($rasql);
        while ($row2 = $db->fetch_record($result2)) {
          $this->idlist[$row2['class_id']] = '<span class="tableheader_'.strtolower($rpconvert->classname($row2['class_name'])).'">'.$row2['class_name'].'</span>';
        }
        $db->free_result($result2);
      }

      foreach(explode("|", $list) as $class_id){
        $output[$class_id] = $this->idlist[$class_id];
      }
      $classnames = implode(", ", $output);
      return ($classnames) ? $classnames : $list;
    }

    function CheckPHPvalue($value){
      global $user;
      $newvalue = @ini_get($value);
      return ($newvalue) ? $newvalue : '<i>'.$user->lang['rp_phpini_novalue'].'</i>';
    }

    function BuildLink(){
      global $eqdkp;
      $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
      $script_name = ( $script_name != '' ) ? $script_name . '/' : '';
      $server_name = trim($eqdkp->config['server_name']);
      $server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
      return 'http://' . $server_name . $server_port . $script_name;
    }

    function php2JSfloat($input){
      if($input){
        $out = str_replace(",", ".", $input);   // Convert comma to point
        $out = floatval($out);                  // Check its a float
        $out = str_replace(",", ".", $out);     // Re-Do the comma to Point thing.
      }else{
        $out = 0;
      }
      return $out;
    }

		function SingleDropDownMenu($id, $cssid, $menuitems, $button=''){
			global $eqdkp_root_path;
			if($button){
			$dmmenu  = '<div id="'.$id.'" class="ddcolortabs">
				                    <ul>
				                      <li><a href="#" rel="'.$cssid.'"><span>'.$button.' <img border="0" src="'.$eqdkp_root_path.'plugins/raidplan/images/menu/down.png"/></span></a></li>
				                    </ul>
				                    </div>';
			}
			$dmmenu .= '<!--1st drop down menu -->
				                  <div id="'.$cssid.'" class="dropmenudiv_a">';
			foreach($menuitems as $key=>$value){
				if($value['perm']){
					$dmimg = ($value['img']) ? '<img src="'.$eqdkp_root_path.'plugins/raidplan/images/'.$value['img'].'" alt="" />' : '';
					$dmmenu .= '<a href="'.$value['link'].'">'.$dmimg.'&nbsp;&nbsp;'.$value['name'].'</a>';
				}
			}
			$dmmenu .= '</div>';
			$dmmenu .= '<script type="text/javascript">
				                    tabdropdown.init("'.$id.'")
				                  </script>';
			return $dmmenu;
    }

    /**********************************************************
     * PRIVATE FUNCTIONS
     * these functions below are private...
     **********************************************************/

  	function fxxxxzzztttzzz($timez){
      return $timez < $this->enddate && $timez > $this->startdate;
    }

  	function fxxxxzzztttzzz2($timez){
      return $timez[$this->tmpfuncname] > $this->startdate2;
    }
  }
}