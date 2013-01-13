<?php
/*************************************************\
*             fightstats 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


if (!class_exists("fightstatsClass")){
  class fightstatsClass
  {
  
function get_document($url){

    $content = '';
    $is_header = TRUE;
    $base_url = parse_url($url);

    if ($fp = @fsockopen($base_url['host'], 80, $errno, $errstr, 5))
    {

        if (!empty($base_url['query']))
        {
            $query = '?'.$base_url['query'];
        }

        else
        {
            $query = '';
        }

        $data = 'GET '.$base_url['path'].$query." HTTP/1.0\r\n".
                        'Host: '.$base_url['host']."\r\n".
                        "Connection: Close\r\n\r\n";

        stream_set_timeout($fp, 5);
        fputs($fp, $data);

        while(!feof($fp))
        {
            $line = fgets($fp, 4096);

            if (!$is_header)
            {
                $content .= $line;
            }

            else
            {

                if (strlen(trim($line)) == 0)
                {
                    $is_header = FALSE;
                }
            }
        }

        fclose($fp);
        return $content;
    }

    else
    {
       return FALSE;
    }
}   

// Build the CopyRight
function Copyright(){
		global $pm, $user;
		$dl_version = $pm->get_data('fightstats', 'version');
	
		$dl_status  = (strtolower($pm->plugins['fightstats']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['fightstats']->vstatus.' ';
	$act_year = date("Y", time());
	$dl_copyyear = ( $act_year == 2010) ? $act_year : '2010 - '.$act_year;
		return $user->lang['fightstats'].' '.$dl_version.$dl_status.' &copy; '.$dl_copyyear.' by '.$pm->plugins['fightstats']->copyright;
}

function update_config($config_value, $config_name){
	global $db;
	
	$updatequery = $db->query("UPDATE __fightstats_config SET config_value = '".$db->escape($config_value)."' WHERE config_name = '".$db->escape($config_name)."'");
}


function is_checked($value){
	
	return ( $value == 1 ) ? ' checked="checked"' : '';
}
  

function update_wol($group_id){
	global $db, $pdc;
	$logs_query = $db->query("SELECT log_id FROM __fightstats");
	while ($row = $db->fetch_record($logs_query)){
		$logs[$row['log_id']] = $row['log_id'];
	}
	
	$xml = simplexml_load_file('http://www.worldoflogs.com/feeds/guilds/'.$group_id.'/raids/?t=xml');
	if (!$xml){
			if ($data = $this->get_document("http://www.worldoflogs.com/feeds/guilds/".$group_id."/raids/?t=xml") ){
				$xml = simplexml_load_string($data);
		}
	}
	
	if ($xml){
		foreach ($xml->Raids->Raid as $raid){
			$data = array();
			foreach ($raid->attributes() as $key=>$value){
				$data[$key] = (string)$value;
			}
			
			if (in_array($data['id'], $logs)){
				$db->query("DELETE FROM __fightstats WHERE date > ".substr($data['date'], 0, -3)); 
			}
		}
		
		$logs_query = $db->query("SELECT log_id FROM __fightstats");
		$logs = array();
		while ($row = $db->fetch_record($logs_query)){
			$logs[$row['log_id']] = $row['log_id'];
		}
		
		
		foreach ($xml->Raids->Raid as $raid){
			$data = array();
			foreach ($raid->attributes() as $key=>$value){
				$data[$key] = (string)$value;
			}
			
			//Teilnehmer
			$participants = array();
			foreach ($raid->Participants->Participant as $participant){
				$attr = $participant->attributes();
				$participants[] = utf8_decode((string)$attr['name']);
			}
			//Zonen
			$i=0;
			$zones = array();
			foreach ($raid->Zones->Zone as $zone){
				foreach($zone->attributes() as $key=>$value){
					$zones[$i][$key] = (string)$value;
				}
				$i++;
			}
			//Bosse
			$i=0;
			$bosses = array();
			foreach ($raid->Bosses->Boss as $boss){
				foreach($boss->attributes() as $key=>$value){
					$bosses[$i][$key] = (string)$value;
				}
				$i++;
			}
			
			if (!in_array($data['id'], $logs)){
			
				$db->query("INSERT INTO __fightstats :params", array(
					'log_id'	=> $data['id'],
					'bossCount'	=> $data['bossCount'],
					'killCount'	=> $data['killCount'],
					'wipeCount'	=> $data['wipeCount'],
					'damageTaken'	=> $data['damageTaken'],
					'damageDone'	=> $data['damageDone'],
					'date'	=> substr($data['date'], 0, -3),
					'duration'	=> substr($data['duration'], 0, -3),
					'healingDone'	=> $data['healingDone'],
					'participants'	=> serialize($participants),
					'zones'	=> serialize($zones),
					'bosses'	=> serialize($bosses),
				));
			}
			
		}
	} else {
		System_Message('The Wordl-of-Logs-Feed could not be reached','Fightstats','red');
	}
	$this->update_config(time(), 'last_update');
	$pdc->del_suffix('plugin.fightstats');
}


function get_config(){
	global $db;
	$query = $db->query("SELECT * FROM __fightstats_config");
	while ($row = $db->fetch_record($query )){
		$result[$row['config_name']] = $row['config_value'];
	}
	return $result;
}

  } //END class
} //END if
?>