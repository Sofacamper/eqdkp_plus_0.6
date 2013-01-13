<?php
 /*
 * Project:		EQdkp RaidPlanner
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2005
 * Date:		$Date: 2011-01-20 20:24:37 +0100 (Thu, 20 Jan 2011) $
 * -----------------------------------------------------------------------
 * @author		$Author: wallenium $
 * @copyright	2005-2008 Simon (Wallenium) Wallmann
 * @link		http://eqdkp-plus.com
 * @package		raidplan
 * @version		$Rev: 9596 $
 * 
 * $Id: lua.class.php 9596 2011-01-20 19:24:37Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

$rpexport_plugin['lua.class.php'] = array(
	'name'			=> 'LUA',
	'function'		=> 'LUAexport',
	'contact'		=> 'Flintman@thenoblealliance.com',
	'game'			=> array('wow'),
	'version'		=> '1.4.0'
);

class LUAexport extends RaidplanExport {

	public function __construct($raid_id){
		global $houtput, $db, $user, $pcache;
		$lua_version = "250";
		$output = "<b>".$user->lang['rp_start_lua_export']."</b><br>";

		// open/create file
		$file = fopen($pcache->FilePath('phpRaid_Data.lua', 'raidplan'),'w');

		// base output
		$lua_output  = "phpRaid_Data = {\n";
		$lua_output .= "\t[\"lua_version\"] = \"{$lua_version}\",\n";


		// Roles... should be dynamically
		$role_qury = $db->query("SELECT * FROM __roles"); $i=1;
		while($role_data = $db->fetch_record($role_qury)){
			$this->prv_role[$role_data['role_image']] = array(
				'id'		=> $role_data['role_id'],
				'prv_id'	=> $i,
			);
			$lua_output .= "\t[\"role".$i."\"] = \"".$role_data['role_name']."\",\n";
			$i++;
		}
		$lua_output .= "\t[\"role_count\"] = \"".$db->sql_numrows()."\",\n";

		// MY DATE FILTER
		$sql = "SELECT * FROM __raidplan_raids WHERE raid_date_finish >=" . time() . " ORDER BY raid_date_invite";
		if ( !($raids_result = $db->query($sql)) ){
			message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql);
		}

		$lua_output .= "\t[\"raid_count\"] = \"".$db->num_rows($raids_result)."\",\n";
		$lua_output .= "\t[\"raids\"] = {\n";

		// parse result
		$count = 0;

		while ( $raid_data = $db->fetch_record($raids_result) ){
			$invite_time_hour		= Date( 'H', $raid_data['raid_date_invite']);
			$invite_time_minute		= Date( 'i', $raid_data['raid_date_invite'] );
			$start_time_hour		= Date( 'H', $raid_data['raid_date'] );
			$start_time_minute		= Date( 'i', $raid_data['raid_date'] );
			$lua_output .= "\t\t[{$count}] = {\n";
			$lua_output .= "\t\t\t[\"location\"] = \"{$raid_data['raid_name']}\",\n";
			$lua_output .= "\t\t\t[\"date\"] = \"" . date('m/d/y',$raid_data['raid_date']) . "\",\n";
			$lua_output .= "\t\t\t[\"invite_time\"] = \"" . $invite_time_hour.":" .$invite_time_minute . "\",\n";
			$lua_output .= "\t\t\t[\"start_time\"] = \"" .  $start_time_hour.":" .$start_time_minute . "\",\n";
			$raid_id = $raid_data['raid_id'];

			// sql string for signups
			$sql = "SELECT * FROM __raidplan_raid_attendees
					WHERE raid_id ='" . $raid_id . "'
					AND (attendees_subscribed ='1' OR attendees_subscribed ='3')";

			$queue = array();
			if ( !($raid_members = $db->query($sql)) ){
				message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql);
			}

			while($member = $db->fetch_record($raid_members)){
				$sql1 = $db->query("SELECT m.member_name, m.member_level, r.race_name, c.class_name
						FROM __members m, __races r, __classes c
						WHERE member_id = '".$db->escape($member['member_id'])."'
						AND m.member_race_id= r.race_id
						AND m.member_class_id=c.class_id");
				if (!($signup1 = $db->fetch_record($sql1))){
					continue;
				}

				array_push($queue, array(
					'name'		=> ucfirst(strtolower($signup1['member_name'])),
					'level'		=> $signup1['member_level'],
					'race'		=> $signup1['race_name'],
					'class'		=> $signup1['class_name'],
					'comment'	=> preg_replace("/\r|\n/s", " ", str_replace('"', '\"', $member['attendees_note'])),
					'timestamp'	=> date('m/d/y',$member['attendees_signup_time']) . ' - ' . date('h:i A',$member['attendees_signup_time']),
					'role_id'	=> $this->prv_role[strtolower($member['role'])]['prv_id'],
				));
			}

			$sql = "SELECT * FROM __raidplan_raid_attendees WHERE raid_id ='" . $raid_id . "'and attendees_subscribed ='0'";

			$signups = array();
			if ( !($raid_members = $db->query($sql)) ){
				message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql);
			}

			while($member = $db->fetch_record($raid_members)){
				$sql1 = $db->query("SELECT m.member_name, m.member_level, r.race_name, c.class_name
					FROM __members m, __races r, __classes c
					WHERE member_id = '".$db->escape($member['member_id'])."'
					AND m.member_race_id= r.race_id
					AND m.member_class_id=c.class_id");
				if (!($signup1 = $db->fetch_record($sql1))){
					continue;
				}

				array_push($signups, array(
					'name'		=> ucfirst(strtolower($signup1['member_name'])),
					'level'		=> $signup1['member_level'],
					'race'		=> $signup1['race_name'],
					'class'		=> $signup1['class_name'],
					'comment'	=> preg_replace("/\r|\n/s", " ", str_replace('"', '\"', $member['attendees_note'])),
					'timestamp'	=> date('m/d/y',$member['attendees_signup_time']) . ' - ' . date('h:i A',$member['attendees_signup_time']),
					'role_id'	=> $this->prv_role[strtolower($member['role'])]['prv_id'],
				));
			}

			// begin - add data to lua output
			for($i=0; $i<11; $i++){
				$lua_signups[$i] = "\t\t\t[\"".$this->Id2ClassName($i)."\"] = {\n";
			}

			// init counter vars
			$cnt[0] = $cnt[1] = $cnt[2] = $cnt[3] = $cnt[4] = $cnt[5] = 0;
			$cnt[6] = $cnt[7] = $cnt[8] = $cnt[9] = $cnt[10] = 0;

			foreach($queue as $char){
				$lua_signups[0] .= "\t\t\t\t[{$cnt[0]}] = {\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"name\"] = \"{$char['name']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"level\"] = \"{$char['level']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"class\"] = \"{$char['class']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"race\"] = \"{$char['race']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"comment\"] = \"{$char['comment']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"timestamp\"] = \"{$char['timestamp']}\",\n";
				$lua_signups[0] .= "\t\t\t\t\t[\"role_id\"] = \"{$char['role_id']}\",\n";
				$lua_signups[0] .= "\t\t\t\t},\n";
				$cnt[0]++;
			}

			foreach($signups as $char){
				$class_id = $this->ClassName2Id($char['class']);
				$lua_signups[$class_id] .= "\t\t\t\t[{$cnt[$class_id]}] = {\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"name\"] = \"{$char['name']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"level\"] = \"{$char['level']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"class\"] = \"{$char['class']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"race\"] = \"{$char['race']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"comment\"] = \"{$char['comment']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"timestamp\"] = \"{$char['timestamp']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t\t[\"role_id\"] = \"{$char['role_id']}\",\n";
				$lua_signups[$class_id] .= "\t\t\t\t},\n";
				$cnt[$class_id]++;
			}

			// add class counts
			$lua_output .= "\t\t\t[\"queue_count\"] = \"".$cnt[0]."\",\n";
			$lua_output .= "\t\t\t[\"druids_count\"] = \"".$cnt[1]."\",\n";
			$lua_output .= "\t\t\t[\"hunters_count\"] = \"".$cnt[2]."\",\n";
			$lua_output .= "\t\t\t[\"mages_count\"] = \"".$cnt[3]."\",\n";
			$lua_output .= "\t\t\t[\"shamans_count\"] = \"".$cnt[4]."\",\n";
			$lua_output .= "\t\t\t[\"paladins_count\"] = \"".$cnt[5]."\",\n";
			$lua_output .= "\t\t\t[\"priests_count\"] = \"".$cnt[6]."\",\n";
			$lua_output .= "\t\t\t[\"rogues_count\"] = \"".$cnt[7]."\",\n";
			$lua_output .= "\t\t\t[\"warlocks_count\"] = \"".$cnt[8]."\",\n";
			$lua_output .= "\t\t\t[\"warriors_count\"] = \"".$cnt[9]."\",\n";
			$lua_output .= "\t\t\t[\"deathknights_count\"] = \"".$cnt[10]."\",\n";

			for($i=0; $i<11; $i++)
				$lua_output .= $lua_signups[$i] . "\t\t\t},\n";

			$lua_output .= "\t\t},\n";
			$count++;
		}

		$lua_output .= "\t}\n}";
		// end - add data to lua output

		// write to file
		fwrite($file,utf8_encode($lua_output));

		// output to textarea
		$output .= $user->lang['rp_lua_file_created'].'.</b><br>';
		$output .= sprintf($user->lang['rp_lua_file_download'], $pcache->BuildLink().$pcache->FileLink('phpRaid_Data.lua', 'raidplan'), false).'<br>';
		$this->output = $output;
	}

	// Get the class ID by Class Name
	public function ClassName2Id($class){
		global $rpconvert;
		switch(strtolower($rpconvert->classname($class))){
			case 'druid':			return 1;
			case 'hunter':			return 2;
			case 'mage':			return 3;
			case 'shaman':			return 4;
			case 'paladin':			return 5;
			case 'priest':			return 6;
			case 'rogue':			return 7;
			case 'warlock':			return 8;
			case 'warrior':			return 9;
			case 'deathknight':		return 10;
		}
	}

	// Get the class name by ID
	public function Id2ClassName($id){
		switch($id){
			case 0:		return 'queue';
			case 1:		return 'druids';
			case 2:		return 'hunters';
			case 3:		return 'mages';
			case 4:		return 'shamans';
			case 5:		return 'paladins';
			case 6:		return 'priests';
			case 7:		return 'rogues';
			case 8:		return 'warlocks';
			case 9:		return 'warriors';
			case 10:	return 'deathknights';
		}
	}
}
?>