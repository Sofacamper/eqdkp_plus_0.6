<?php

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['wowprogress'] = array(
	'name'			=> 'Guild rating',
	'path'			=> 'wowprogress',
	'version'		=> '2.0.0',
	'author'		=> 'Grib',
	'contact'		=> 'http://dkp.ruwow.org/',
	'description'   => 'PvE guild rating from www.wowprogress.com',
	'positions'     => array('left1', 'left2', 'right'),
	'install'       => array(
				'autoenable'        => '0',
				'defaultposition'   => 'right',
				'defaultnumber'     => '4', ),
	);

$tier_dropdown = array (							
	'none'					=> '',
	//Achievements
	'ach_tier_8'		=> $plang['pm_wowprgs_tier8'].$plang['pm_wowprgs_achiev'],
	'ach_tier_9_10'	=> $plang['pm_wowprgs_tier9_10'].$plang['pm_wowprgs_achiev'],
	'ach_tier_9_25'	=> $plang['pm_wowprgs_tier9_25'].$plang['pm_wowprgs_achiev'],
	'ach_tier_10_10'=> $plang['pm_wowprgs_tier10_10'].$plang['pm_wowprgs_achiev'],
	'ach_tier_10_25'=> $plang['pm_wowprgs_tier10_25'].$plang['pm_wowprgs_achiev'],
	'ach_tier_11_10'=> $plang['pm_wowprgs_tier11_10'].$plang['pm_wowprgs_achiev'],
	'ach_tier_11_25'=> $plang['pm_wowprgs_tier11_25'].$plang['pm_wowprgs_achiev'],
	'ach_tier_12'	=> $plang['pm_wowprgs_tier12'].$plang['pm_wowprgs_achiev'],
	'ach_tier_12_10'=> $plang['pm_wowprgs_tier12_10'].$plang['pm_wowprgs_achiev'],
	'ach_tier_12_25'=> $plang['pm_wowprgs_tier12_25'].$plang['pm_wowprgs_achiev'],
	'ach_tier_13'	=> $plang['pm_wowprgs_tier13'].$plang['pm_wowprgs_achiev'],
	'ach_tier_13_10'=> $plang['pm_wowprgs_tier13_10'].$plang['pm_wowprgs_achiev'],
	'ach_tier_13_25'=> $plang['pm_wowprgs_tier13_25'].$plang['pm_wowprgs_achiev'],
	
	//Encounter
	'enc_tier_8'		=> $plang['pm_wowprgs_tier8'].$plang['pm_wowprgs_enc'],
	'enc_tier_9_10'	=> $plang['pm_wowprgs_tier9_10'].$plang['pm_wowprgs_enc'],
	'enc_tier_9_25'	=> $plang['pm_wowprgs_tier9_25'].$plang['pm_wowprgs_enc'],
	'enc_tier_10_10'=> $plang['pm_wowprgs_tier10_10'].$plang['pm_wowprgs_enc'],
	'enc_tier_10_25'=> $plang['pm_wowprgs_tier10_25'].$plang['pm_wowprgs_enc'],
	'enc_tier_11_10'=> $plang['pm_wowprgs_tier11_10'].$plang['pm_wowprgs_enc'],
	'enc_tier_11_25'=> $plang['pm_wowprgs_tier11_25'].$plang['pm_wowprgs_enc'],
	'enc_tier_12'	=> $plang['pm_wowprgs_tier12'].$plang['pm_wowprgs_enc'],	
	'enc_tier_12_10'=> $plang['pm_wowprgs_tier12_10'].$plang['pm_wowprgs_enc'],
	'enc_tier_12_25'=> $plang['pm_wowprgs_tier12_25'].$plang['pm_wowprgs_enc'],	
	'enc_tier_13'	=> $plang['pm_wowprgs_tier13'].$plang['pm_wowprgs_enc'],	
	'enc_tier_13_10'=> $plang['pm_wowprgs_tier13_10'].$plang['pm_wowprgs_enc'],
	'enc_tier_13_25'=> $plang['pm_wowprgs_tier13_25'].$plang['pm_wowprgs_enc'],	
	);

// Settings
$portal_settings['wowprogress'] = array(
	'pm_wowprogress_pick1' => array(
		'name'  		=>	'pm_wowprogress_pick1',
		'language'	=>	'pm_wowprogress_pick',
		'property'	=>	'dropdown',
		'options' 	=>	$tier_dropdown,
	),
	
	'pm_wowprogress_pick2' => array(
		'name'  		=>	'pm_wowprogress_pick2',
		'language'	=>	'pm_wowprogress_pick',
		'property'	=>	'dropdown',
		'options' 	=>	$tier_dropdown,
	),
	
	'pm_wowprogress_pick3' => array(
		'name'  		=>	'pm_wowprogress_pick3',
		'language'	=>	'pm_wowprogress_pick',
		'property'	=>	'dropdown',
		'options' 	=>	$tier_dropdown,
	),

);

/* Mortalcoil v2.0.0 @ http://www.eqdkp-plus.com/forum
	delta v1.0.1:
	a) added the new data export format from wowprogress (i.e. json)
	b) added the support for tiers 10
	c) added french language pack
	d) added some comments from ease of use (if you want to show diffrent tier for example)
	e) added the json parser
	f) modify url encoding to support special characters
*/

if(!function_exists(wowprogress_module))
{
    function wowprogress_module()
    {
		global $tpl, $eqdkp, $eqdkp_root_path, $conf_plus, $eqdkp_config, $user, $plang, $pdc, $urlreader;

		$out = $pdc->get('dkp.portal.modul.wowprogress',false,true);
    	if (!$out) 
  		{
			// list available wowprogress achievements
			$tier = array (
				//achievements
				'ach_tier_8'	=> array (	'url' => 'rating.tier8.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier8'].$plang['pm_wowprgs_ar']),
				'ach_tier_9_10'	=> array (	'url' => 'rating.tier9_10.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier9_10'].$plang['pm_wowprgs_ar']),
				'ach_tier_9_25'	=> array (	'url' => 'rating.tier9_25.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier9_25'].$plang['pm_wowprgs_ar']),
				'ach_tier_10_10'=> array (	'url' => 'rating.tier10_10.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier10_10'].$plang['pm_wowprgs_ar']),
				'ach_tier_10_25'=> array (	'url' => 'rating.tier10_25.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier10_25'].$plang['pm_wowprgs_ar']),
				'ach_tier_11_10'=> array (	'url' => 'rating.tier11_10.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier11_10'].$plang['pm_wowprgs_ar']),
				'ach_tier_11_25'=> array (	'url' => 'rating.tier11_25.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier11_25'].$plang['pm_wowprgs_ar']),
				'ach_tier_12'=> array (	'url' => 'rating.tier12.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12'].$plang['pm_wowprgs_ar']),
				'ach_tier_12_10'=> array (	'url' => 'rating.tier12_10.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12_10'].$plang['pm_wowprgs_ar']),
				'ach_tier_12_25'=> array (	'url' => 'rating.tier12_25.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12_25'].$plang['pm_wowprgs_ar']),	
				'ach_tier_13'=> array (	'url' => 'rating.tier13.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13'].$plang['pm_wowprgs_ar']),
				'ach_tier_13_10'=> array (	'url' => 'rating.tier13_10.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13_10'].$plang['pm_wowprgs_ar']),
				'ach_tier_13_25'=> array (	'url' => 'rating.tier13_25.ach/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13_25'].$plang['pm_wowprgs_ar']),
				
				//Encounter
				'enc_tier_8'	=> array (	'url' => 'rating.tier8/json_rank',
										'desc'=> $plang['pm_wowprgs_tier8'].$plang['pm_wowprgs_er']),
				'enc_tier_9_10'	=> array (	'url' => 'rating.tier9_10/json_rank',
										'desc'=> $plang['pm_wowprgs_tier9_10'].$plang['pm_wowprgs_er']),
				'enc_tier_9_25'	=> array (	'url' => 'rating.tier9_25/json_rank',
										'desc'=> $plang['pm_wowprgs_tier9_25'].$plang['pm_wowprgs_er']),
				'enc_tier_10_10'=> array (	'url' => 'rating.tier10_10/json_rank',
										'desc'=> $plang['pm_wowprgs_tier10_10'].$plang['pm_wowprgs_er']),
				'enc_tier_10_25'=> array (	'url' => 'rating.tier10_25/json_rank',
										'desc'=> $plang['pm_wowprgs_tier10_25'].$plang['pm_wowprgs_er']),
				'enc_tier_11_10'=> array (	'url' => 'rating.tier11_10/json_rank',
										'desc'=> $plang['pm_wowprgs_tier11_10'].$plang['pm_wowprgs_er']),
				'enc_tier_11_25'=> array (	'url' => 'rating.tier11_25/json_rank',
										'desc'=> $plang['pm_wowprgs_tier11_25'].$plang['pm_wowprgs_er']),
				'enc_tier_12'=> array (	'url' => 'rating.tier12/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12'].$plang['pm_wowprgs_er']),
				'enc_tier_12_10'=> array (	'url' => 'rating.tier12_10/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12_10'].$plang['pm_wowprgs_er']),
				'enc_tier_12_25'=> array (	'url' => 'rating.tier12_25/json_rank',
										'desc'=> $plang['pm_wowprgs_tier12_25'].$plang['pm_wowprgs_er']),
				'enc_tier_13'=> array (	'url' => 'rating.tier13/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13'].$plang['pm_wowprgs_er']),
				'enc_tier_13_10'=> array (	'url' => 'rating.tier13_10/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13_10'].$plang['pm_wowprgs_er']),
				'enc_tier_13_25'=> array (	'url' => 'rating.tier13_25/json_rank',
										'desc'=> $plang['pm_wowprgs_tier13_25'].$plang['pm_wowprgs_er']),											
				);
			// pick 3 !
			$col[] = ($conf_plus['pm_wowprogress_pick1'] != "") ? $conf_plus['pm_wowprogress_pick1'] : 'none';
			$col[] = ($conf_plus['pm_wowprogress_pick2'] != "") ? $conf_plus['pm_wowprogress_pick2'] : 'none';
			$col[] = ($conf_plus['pm_wowprogress_pick3'] != "") ? $conf_plus['pm_wowprogress_pick3'] : 'none';

			$pm_wowprgs_url = "http://www.wowprogress.com/";		
			$search = array('+',"'"," ");
			$server = urlencode(strtolower(str_replace($search, '-',$conf_plus['pk_servername'])));
			$guild = str_replace($search, '+', urlencode(utf8_encode(strtolower($eqdkp->config['guildtag']))));	

			$pm_wowprgs_guild_url = $pm_wowprgs_url . "guild/" . $conf_plus[pk_server_region] . "/" . $server  . "/" . $guild . "/";
			$count = 0;
			foreach ($col as $key=>$value){
				if ($value != 'none'){
					$pm_wowprgs_guild_rank_url = $pm_wowprgs_guild_url . $tier[$value]['url'];
					$pm_wowprgs_guild_rank[$key] = json_decode($urlreader->GetURL($pm_wowprgs_guild_rank_url),true);
					$count++;
				}
			}
		
			$out .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
			$out .= '<tr class="row1" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row1\';"><td colspan="'.($count+1).'">';
			$out .= '<a href="' . $pm_wowprgs_guild_url . '" target="_blank" title="&quot;' . $eqdkp->config['guildtag'] . '&quot; ' . $plang['pm_wowprgs_on']  . 'wowprogress.com">' . $eqdkp->config['guildtag'] . '</a>';
			$out .= '</td></tr>';
			$out .= '<tr class="row2" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row2\';"><td colspan="4">';
			$out .= $conf_plus['pk_servername'];
			$out .= '</td></tr>';
			$out .= '<tr class="row1" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row1\';"><td><b>' .$plang['pm_wowprgs_tier'];
			$out .= '</b></td>';
			
			foreach ($col as $value){
				if ($value != 'none'){
					$out .= '<td nowrap="nowrap" align="right"><b>' . $tier[$value]['desc'];
					$out .= '</b></td>';
				}
			}
			
			$out .= '</tr>';
			$out .= '<tr class="row2" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row2\';"><td>' . $plang['pm_wowprgs_world'];
			$out .= '</b></td>';
			
			foreach ($col as $key=>$value){
				if ($value != 'none'){
					$out .= '<td align="right">' . $pm_wowprgs_guild_rank[$key][world_rank];
					$out .= '</td>';
				}
			}

			$out .= '</tr>';
			
			$out .= '<tr class="row1" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row1\';"><td>' . strtoupper($conf_plus[pk_server_region]) . ': ';
			$out .= '</b></td>';
			
			foreach ($col as $key=>$value){
				if ($value != 'none'){
						$out .= '<td align="right">' . $pm_wowprgs_guild_rank[$key][area_rank];
						$out .= '</td>';
				}
			}
			$out .= '</tr>';
			
			$out .= '<tr class="row2" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row2\';"><td>' . $plang['pm_wowprgs_realm'];
			$out .= '</b></td>';
			foreach ($col as $key=>$value){
				if ($value != 'none'){
					$out .= '<td align="right">' . $pm_wowprgs_guild_rank[$key][realm_rank];
					$out .= '</td>';
				}
			}

			$out .= '</tr>';
			$out .= '<tr class="row1" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\'row1\';"><td colspan=\''.($count+1).'\' align=\'center\'><a href="http://www.wowprogress.com/" target="_blank"><small>www.wowprogress.com</small></a></td></tr>';
			$out .= '</table>';
			
			$pdc->put('dkp.portal.modul.wowprogress',$out,86400,false,true);
			
			return $out;
	  	}else 
	  	{
	  		return $out;
	  	}
    }
}
?>