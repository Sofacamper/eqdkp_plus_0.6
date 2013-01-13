<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-09-15 12:58:59 +0200 (Tue, 15 Sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 5851 $
 *
 * $Id: nextraids.php 5851 2009-09-15 10:58:59Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['nextraids'] = array(
			'name'			    => 'nextRaids Module',
			'path'			    => 'nextraids',
			'version'		    => '1.1.0',
			'author'        => 'PLUS Dev Team',
			'contact'		    => 'http://www.eqdkp-plus.com',
			'positions'     => array('left1', 'left2', 'right'),
      'signedin'      => '0',
      'install'       => array(
                            'autoenable'        => '1',
                            'defaultposition'   => 'right',
                            'defaultnumber'     => '1',
                          ),
    );

$portal_settings['nextraids'] = array(
  'pk_nextraids_limit'     => array(
        'name'      => 'rp_nextraids_limit',
        'language'  => 'rp_nextraids_limit',
        'property'  => 'text',
        'size'      => '2',
      ),
);

if(!function_exists(nextraids_module)){
  function nextraids_module(){
  	global $db, $eqdkp_root_path, $table_prefix, $user, $eqdkp, $conf_plus, $stime;

		$total_recent_raids = $total_raids = 0;

		// Load the Raidplan Config
		$sql = 'SELECT * FROM __raidplan_config';
    $settings_result = $db->query($sql);
    while($roww = $db->fetch_record($settings_result)){
      $conf[$roww['config_name']] = $roww['config_value'];
    }
    $db->free_result($settings_result);

    // Load the Raidplan User Settings
    if ($user->data['user_id'] && !$user->data['username']=="" && $conf['rp_override_usettings'] != 1){
  		$usql = "SELECT * FROM __raidplan_userconfig WHERE `user_id`='".$user->data['user_id']."'";
  		$usettings_result = $db->query($usql);
  		while($rowww = $db->fetch_record($usettings_result)){
  		  $usersettings[$rowww['config_name']] = $rowww['config_value'];
  		}
  		$db->free_result($usettings_result);

  		// security - overwrite only selected ones :)
  		if(is_array($usersettings)){
  			$conf['rp_dstcheck']			= $usersettings['rp_dstcheck'];     # Daylight Saving
  			$conf['rp_timezone']			= $usersettings['rp_timezone'];     # TimeZone
  		}
    }

    // Status colors
    $out = "<style>
                .status0 {
                	color	: ".$conf['color_status0'].";
                	align	: right;
                }
                .status1 {
                	color	: ".$conf['color_status1'].";
                	align	: right;
                }
                .status2 {
                	color	: ".$conf['color_status2'].";
                	align	: right;
                }
                .status3 {
                	color	: ".$conf['color_status3'].";
                	align	: right;
                }
              </style>";

    // init the time class
		if(!class_exists('DateFormats')){
		  include($eqdkp_root_path . 'plugins/raidplan/includes/time.class.php');
      $stime = new DateFormats($user->lang['rp_calendar_lang'], $conf['rp_dstcheck'], $conf['rp_timezone']); // Init the Time Management Class
		}
    $conf['timeformats'] = $stime->timeFormats(); // Load the Time Format Array

    // The event icons..
	  $sql = "SELECT event_icon, event_name FROM __events";
		$icon = $db->query($sql);
		while($eventrow = $db->fetch_record($icon)){
  		$pleventicons[$eventrow['event_name']] = $eventrow['event_icon'];
  	}

    // generate an array with the guest stuff
    if($conf['rp_use_guests'] == 1){
      $sql_guest = "SELECT membername, raid_id FROM __raidplan_tempmembers";
    	$guest_result = $db->query($sql_guest);

      while( $guest_row = $db->fetch_record($guest_result) ){
        $guests[$guest_row['raid_id']][] = $guest_row['membername'];
      }
      $db->free_result($guest_result);
    }

		//looking for next Raids
		$sql = "SELECT * FROM __raidplan_raids
            WHERE raid_date > ".$stime->DoTime()."
            AND raid_closed = '0'
            ORDER BY `raid_date`";

		if ($conf_plus['rp_nextraids_limit'] > 0) {
		 $sql .= ' LIMIT '.$conf_plus['rp_nextraids_limit']	;
		}elseif ($conf_plus['rp_nextraids_limit'] == 0 ){
		 $sql .= ' LIMIT 3'	;
		}
		$result = $db->query($sql);
		$raidcount = $db->num_rows($result);

		//Do the rest only if we have at least one next raid
		if ($raidcount > 0){
			//Get all MemberIDs from the active User
			if ( $user->data['user_id'] != ANONYMOUS ){
				$sql2 = 'SELECT member_id	FROM __member_user WHERE user_id = '. $user->data['user_id'] .'';
		 		$result2 = $db->query($sql2);
		 		$member_ids = array();

		 		//get all memberIDs
				while ( $row2 = $db->fetch_record($result2) ){
					$member_ids[] = $row2[member_id]  ;
				}
			}
			$noraids = false;
		}else{
      $noraids = true;
		}
    //Table Header
		$out .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
		//go though all given next raids
	  while ( $row = $db->fetch_record($result) ){
			$own_status      = false;
			$count_status    = $count_array = '';

      // Build the Attendee Array
      $cattsql = "SELECT a.attendees_subscribed, a.member_id
                  FROM __raidplan_raid_attendees a, __members m, __member_user mu
                  WHERE m.member_id=a.member_id
                  AND mu.member_id=m.member_id
                  AND a.raid_id=" . $row['raid_id'];
	    $count_attendees = $db->query($cattsql);
	    while ( $att_row = $db->fetch_record($count_attendees) ){
        $count_array[$att_row['member_id']] = $att_row['attendees_subscribed'];
      }

      if(is_array($count_array)&& count($count_array) != 0){
        if(!function_exists('RaidplanAddition')){
          if (!defined('ROLES_TABLE'))  { define('ROLES_TABLE', $table_prefix . 'roles'); } // prevent white pages...
    		  include($eqdkp_root_path . 'plugins/raidplan/includes/raidplan.class.php');
          $rpclass    = new RaidplanAddition;
    		}
        $count_status =  array(
                          $rpclass->count_repeat_values('0', $count_array),
                          $rpclass->count_repeat_values('1', $count_array),
                          $rpclass->count_repeat_values('2', $count_array),
                          $rpclass->count_repeat_values('3', $count_array)
                        );
      }

	    // count the total sum
	    $sql = "SELECT raid_attendees FROM __raidplan_raids WHERE raid_id='".$row['raid_id']."'";
	    $count_total       = $db->query_first($sql);
	    $count_confirmed   = (is_array($guests[$row['raid_id']])) ? count($guests[$row['raid_id']]) + $count_status[0] : $count_status[0];

			// is the Member signed in?
			if(is_array($member_ids)){
				$sql2 = "SELECT attendees_subscribed,attendees_note,attendees_signup_time FROM __raidplan_raid_attendees
							  WHERE raid_id=".$row['raid_id']."
							  AND member_id in ('".join_array("', '", $member_ids)."')";
				$result2 = $db->query($sql2);

				//found some raids
				$row2 = $db->fetch_record($result2);
				if($row2){
					if ($row2['attendees_signup_time']){     //only if the user has allready signed on
						$own_status['status'] = $row2['attendees_subscribed'];
						$own_status['note'] 	= $row2['attendees_note'];
					}
				}
			}

			// Calculate the Member Counts
      $count_summ       = $count_confirmed + $count_status[1];   // Total Attendees (confirmed (+guest) + signed in)
      $diffangemeldet   = $count_total - $count_summ;         // Needed Members (Needed Members - Total)
      $diffangemeldet   = ($diffangemeldet < 0) ? 0 : $diffangemeldet;

    	$confirmstatus = '';

    	//Flags only for registered user
    	if (is_array($own_status)){
        $confirmstatus = ' <img src='.$eqdkp_root_path.'plugins/raidplan/images/status/status'.$own_status['status'].'.gif />';
    	}

			$out .= "<tr class=row1><td colspan=2><b>".$stime->DoDate($conf['timeformats']['medium'], $row['raid_date']).$confirmstatus.
												  "</b></td></tr>";

      $raidplink = $eqdkp_root_path."plugins/raidplan/viewraid.php?r=";
			$out .= '<tr class="row2" nowrap onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row2\';">'.
							"<td valign=top>
			  		   		<a href='".$raidplink.stripslashes($row['raid_id'])."'>
			  		   		  <img src='".$eqdkp_root_path."/games/".$eqdkp->config['default_game']."/events/".$pleventicons[stripslashes($row['raid_name'])]."' width='40' height='40'>
                  </a>
			  		   	</td>
			  			<td>
			  				<a href='".$raidplink.stripslashes($row['raid_id'])."'>".stripslashes($row['raid_name'])." (".$count_total.") </a><br/>";

    	if (is_array($own_status)){
			  $out .= "<span class='status1'>".$user->lang['rp_nextraids_signon'].": ".$count_status[1]."</span><br/>" ;
			  $out .= "<span class='status0'>".$user->lang['rp_nextraids_confirmed'].": ".$count_confirmed."</span><br/>" ;
			  $out .= "<span class='status2'>".$user->lang['rp_nextraids_signoff'].": ".$count_status[2]."</span><br/>" ;
 			  $out .=  ($diffangemeldet > 0) ? "<span class='negative'><b>".$user->lang['rp_nextraids_missing'].": ".$diffangemeldet."</b></span>" : '' ;
    	}elseif ($user->data['user_id'] != ANONYMOUS){
        if($conf_plus['rp_nextraids_guestinfos']){
      	  $out .= "(<span class='status1'>".$count_status[1]."</span>/ ";
  			  $out .= "<span class='status0'>".$count_confirmed."</span> / ";
  			  $out .= "<span class='status2'>".$count_status[2]."</span> /";
  			  $out .= "<span class='negative'>".$diffangemeldet."</span>)<br/>";
			  }
    		$out .= "<a href='".$eqdkp_root_path."plugins/raidplan/viewraid.php?r=". $row['raid_id']."'>".$user->lang['rp_nextraids_notsigned']."</a>" ;
    	}
			$out .= "</td></tr>";
	  }#end while

    if($noraids){
      $out .= '<tr><td colspan="2" class="smalltitle" align="center">'.$user->lang['rp_nextraids_noraids'].'</td></tr>';
    }

	  $out .= "</table>" ;
    return $out;
  }
}
?>
