<?PHP
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.1.0                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


// You have to define the Module Information
$portal_module['lastraidlogs'] = array(        			    				// the same name as the folder!
			'name'			   	=> 'Last Fightlogs',   						// The name to show
			'path'			   	=> 'lastraidlogs',                 			// Folder name again
			'version'		   	=> '0.1.0',            						// Version
			'author'				=> 'GodMod',             					// Author
			'contact'		   	=> 'http://eqdkp-plus.com/forum',   		// email adress
			'description'  	=> 'Displays the latest Fightlogs',  	// Detailed Description
			'positions'    	=> array('left1', 'left2', 'right'), 		// Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      'signedin'     	=> '0',								            // 0 = all users, 1 = signed in only     		
			'install'      => array(
				'autoenable'        => '1',      // 0 = disable on install , 1 = enable on install
				'defaultposition'   => 'left1',  // see blocks above
				'defaultnumber'     => '6',      // default ordering number
			),
);



//Portalmodule-Settings
$portal_settings['lastraidlogs'] = array(
		'pm_lf_maxlogs'	=> array(
		'name'  				=>	'pm_lf_maxlogs',
		'language'			=>	'pm_lf_maxlogs',
		'property'			=>	'text',
		'size'	   			=>	'3',
		),
	
);


if(!function_exists(lastraidlogs_module)){
  function lastraidlogs_module(){
  
  global $eqdkp, $user, $tpl, $db, $plang, $conf_plus, $eqdkp_root_path, $SID, $html, $pdc;
  //Cache: plugin.downloads.portalmodul.lastraidlogs.{USERID}.{EQDKP_ROOT_PATH}
  $output = $pdc->get('plugin.fightstats.portalmodul.lastraidlogs'.$eqdkp_root_path,false,true);
	
	if (!$output)
	{
  	include_once($eqdkp_root_path . '/plugins/fightstats/include/fightstats.class.php');
  	$fsclass = new fightstatsClass();
		$language = $user->data['user_lang'];
		
		//Bosssuite-Dateien
		if (file_exists($eqdkp_root_path . '/plugins/bosssuite/games/WoW/linklist.php')){
			include_once($eqdkp_root_path . '/plugins/bosssuite/games/WoW/linklist.php');
			
			if (file_exists($eqdkp_root_path . '/plugins/bosssuite/games/WoW/lang/'.$language.'/lang_bossbase.php')){
				include_once($eqdkp_root_path . '/plugins/bosssuite/games/WoW/lang/'.$language.'/lang_bossbase.php');
			} else {
				include_once($eqdkp_root_path . '/plugins/bosssuite/games/WoW/lang/english/lang_bossbase.php');
			}
		} else {
			return "Error: This Plugin uses files from the bosssuite-Plugin. Please upload the Bosssuite-Plugin.";
		}
		
		$limit = ($conf_plus['pm_lf_maxlogs'] > 0) ?  $conf_plus['pm_lf_maxlogs'] : 10;
		
		$log_query = $db->query("SELECT * FROM __fightstats ORDER BY date DESC LIMIT 0,".$db->escape($limit));
		
		$output = '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="noborder">';
		
		foreach($idlist['default'] as $key=>$value){
			$my_idlist[$value] = $key;
		}
		$output .= '<tr><td><b></b></td><td><b>'.$user->lang['fs_duration'].'</b></td><td><img src="'.$eqdkp_root_path.'plugins/fightstats/images/boss.png" height="16" title="'.$user->lang['fs_bosses'].'"></td><td><img src="'.$eqdkp_root_path.'plugins/fightstats/images/kills.png" height="16" title="Kills"></td><td><img src="'.$eqdkp_root_path.'plugins/fightstats/images/wipes.png" height="16" title="'.$user->lang['fs_wipes'].'"></td></tr>';
		
		while ($row = $db->fetch_record($log_query)){
			
			$bosses = unserialize($row['bosses']);
			$participants = unserialize($row['participants']);
			$zones = unserialize($row['zones']);
			
			
			$output .= '<tr class="'.$eqdkp->switch_row_class().'"><td><a href="http://www.worldoflogs.com/reports/'.$row['log_id'].'/" target="_blank">';
			
			$tt= '<ul>';
			foreach($participants as $value){
				$tt.='<li>'.$value.'</li>';
			}
			$tt .= '</ul>';
			
			foreach($zones as $zone){
				
				$name = ($user->lang[$zone['name']]) ? $user->lang[$zone['name']] : $zone['name'];
				$zone_output = $name.' ('.$html->Tooltip($tt, $zone['playerLimit']).') ';
				$player = $zone['playerLimit'];
			}

			
			$output .= date("d.m.", $row['date']);
			$output .= ' '.$zone_output;
			$output .= '</a></td>';
			$output .= '<td>'.date("i:s", $row['duration']/60).'h</td>';

			$btt= '<ul>';
			foreach ($bosses as $value){

				if (!$idlist['wolids'][$value['id']]){
					$lang_id = $my_idlist[$value['id']];
				} else {
					$lang_id = $idlist['wolids'][$value['id']];
				}
				
				$lang_id = str_replace('_10', '', $lang_id);
				$lang_id = str_replace('_25', '', $lang_id);
				$lang_id = str_replace('_hm', '', $lang_id);
				$lang_id = $lang_id.'_'.$player.(($value['difficulty'] != 'N') ? '_hm': '');

				$btt .= '<li>'.$lang[$lang_id]['short'].'</li>';		
				
				
			}
			$btt.= '</ul>';
			
			$output .= '<td>'.$html->Tooltip($btt, '<div>'.$row['bossCount'].'</div>').'</td>';
			$output .= '<td class="'.(($row['killCount'] > 0) ? 'positive' : '').'">'.$row['killCount'].'</td>';
			$output .= '<td class="'.(($row['wipeCount'] > 0) ? 'negative' : '').'">'.$row['wipeCount'].'</td>';
							
			$output .= '</tr>';
		}
		$output .= '</table>';
		$pdc->put('plugin.fightstats.portalmodul.lastraidlogs'.$eqdkp_root_path,$output,86400,false,true);
		return $output;
	} //END out-if
  
  return $output;
  
  } //END function
}
?>