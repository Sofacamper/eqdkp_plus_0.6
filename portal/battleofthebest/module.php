<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-04-23 13:09:47 +0200 (Fri, 23 Apr 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7649 $
 * 
 * $Id: module.php 7649 2010-04-23 11:09:47Z corgan $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['battleofthebest'] = array(
			'name'           => 'Battle of the Best (WoW)',
			'path'           => 'battleofthebest',
			'version'        => '1.0.0',
			'author'         => 'Corgan',
			'contact'        => 'http://www.battleofthebest.com',
			'description'    => 'Battle of the Best - WoW PvE League!',
			'positions'      => array('left1', 'left2', 'right'),
      		'signedin'       => '0',
      		'install'        => array(
			                            'autoenable'        => '1',
			                            'defaultposition'   => 'right',
			                            'defaultnumber'     => '1',
			                            ),
    );

$portal_settings['battleofthebest'] = array(  
  'wol_group_id'     => array(
        'name'      => 'wol_group_id',
        'language'  => 'pk_battleofthebest_WolID',
        'property'  => 'text',
        'size'      => '30',
        'help'      => 'pk_battleofthebest_WolID_H',
      )   ,  
  'pk_battleofthebest_best5'     => array(
        'name'      => 'pk_battleofthebest_best5',
        'language'  => 'pk_battleofthebest_best5',
        'property'  => 'checkbox',
        'size'      => '30',
        'help'      => '',
      ),      
  'pk_battleofthebest_showcount'     => array(
        'name'      => 'pk_battleofthebest_showcount',
        'language'  => 'pk_battleofthebest_showcount',
        'property'  => 'checkbox',
        'size'      => '30',
        'help'      => '',    
      ),      
  'pk_battleofthebest_countLadder'     => array(
        'name'      => 'pk_battleofthebest_countLadder',
        'language'  => 'pk_battleofthebest_countLadder',
        'property'  => 'text',
        'size'      => '3',
        'help'      => '',
      )      
);

if(!function_exists(battleofthebest_module))
{
  function battleofthebest_module()
  {
  		global $eqdkp , $user , $tpl, $db, $plang, $conf_plus, $urlreader, $eqdkp_root_path, $pdc;
    	$url = "http://www.battleofthebest.com";    	
  		
  		$output = $pdc->get('portal.modul.battleofthebest.'.$eqdkp_root_path,false,true);
  		if (!$output) 
  		{ 
  		
		    //Set the header
			if($conf_plus['pk_battleofthebest_headtext'])
			{
			  $output = "<script>document.getElementById('txtmycontent').innerHTML = '".addslashes($conf_plus['pk_battleofthebest_headtext'])."'</script>";
			}       	
	
			//get ladder ID only if we have a valid wolID
			if ($conf_plus[wol_group_id] > 1) 
			{    		
				//cache ladder ID 7 days
				if( (time() - $conf_plus['botb_ladder_time']) > 604800 )
				{	
					$ladder_url = "$url/gruppen/index.php?p=botbWol2Ladder&wolid=$conf_plus[wol_group_id]";     	     	
					$ladder = $urlreader->GetURL($ladder_url) ;	

					if ($conf_plus['botb_ladder_id'] > 0) {
						$sql = "UPDATE __plus_config set config_value='$ladder' WHERE config_name = 'botb_ladder_id' ";
					}else 
					{
						$sql = "INSERT INTO __plus_config set config_name = 'botb_ladder_id' , config_value='$ladder'";	
					}
					$db->query($sql);			
					
					if ($conf_plus['botb_ladder_time'] > 0) {
						$sql = "UPDATE __plus_config set config_value='".time()."' WHERE config_name = 'botb_ladder_time' ";
					}else 
					{
						$sql = "INSERT INTO __plus_config set config_name = 'botb_ladder_time' , config_value='".time()."'";	
					}
					$db->query($sql);
				}					
			}
				
			
			//if guild isnt signed up in the botb, we get a -1. 			
			$ladder = ($conf_plus['botb_ladder_id'] < 1) ? 1 : $conf_plus['botb_ladder_id'] ;
			//get XML from Database 
			$xml = (strlen($conf_plus['botb_xml'])>1) ? unserialize($conf_plus['botb_xml']) : false ;
			
			//if the xml data are to old or not given, we get get new fresh data
			if( (time() - $conf_plus['botb_xml_time']) > 86400 )									
			{			
				//ladder is given from the botbWol2Ladder or set to 1
				$xml_url = "$url/rss/botb-$ladder.xml";
				$xml = xmlObject2Array(simplexml_load_file($xml_url)) ;	
				if (is_array($xml)) 
				{		
					$rss = serialize($xml) ;
					$rss = mysql_real_escape_string($rss);							
					
					if (strlen($conf_plus['botb_xml']) > 1) 
					{
						$sql = "UPDATE __plus_config set config_value='".$rss."' WHERE config_name = 'botb_xml' ";
						$sql2 = "UPDATE __plus_config set config_value='".time()."' WHERE config_name = 'botb_xml_time' ";			
					}else 
					{
						$sql = "INSERT INTO __plus_config set config_name = 'botb_xml' , config_value='".$rss."'";	
						$sql2 = "INSERT INTO __plus_config set config_name = 'botb_xml_time' , config_value='".time()."'";
					}
					$db->query($sql);	
					$db->query($sql2);				
				}
			}	
	
			if (is_array($xml)) 
			{
				//Get ladder infos my guild
		        foreach ($xml[channel][item] as $item) 
		  		{		  		 
		  			if ($item[IDWol] == $conf_plus[wol_group_id]) 
		        	{
		        		$myGuild = $item;        		
		        	}	 
		  		}  		  						
			}

			//Logo
	        $output .= "<table width=100%>
	        			<tr align=center width=100%>
	        				<td align=center width=100%><a href='".$xml[channel][link]."' target=_blank>
	        				<img src=$eqdkp_root_path/portal/battleofthebest/images/botb_logo.png><br>$plang[pk_battleofthebest_wowLeague]</a><br>
	        				</td>
	        			</tr>";
	        if ($conf_plus[pk_battleofthebest_showcount]) 
	        {
	        	$output .= "<tr><td>$plang[pk_battleofthebest_guildCount]:</td><td align=right>". $xml[channel][guildCount] ." </td></tr>";
	        	$output .= "<tr><td>$plang[pk_battleofthebest_por]</td><td align=right>".$xml[channel][ProGuildCount]."</td></tr>";
	        }	
			$output .= "</table><br>";
			
			//my guild
	        if (is_array($myGuild)) 
	        {
	        	$output .= '<table width="100%">';
		        $output .= "<tr class=row2><td colspan=2 align=center><b><a href='$myGuild[link]' target=_blank>$myGuild[title]</b></td></tr>" ;
		        $output .= "<tr class=row2><td>$plang[pk_battleofthebest_league]: </td><td align=right><a href='http://www.battleofthebest.com/gruppen/index.php?p=league#$myGuild[ladderName]' target=_blank>$myGuild[ladderName]</a> </td></tr>" ;
		        $output .= "<tr class=row2><td>$plang[pk_battleofthebest_rank]: </td><td align=right> $myGuild[ladderRank] </td></tr>" ;
		        $output .= ($myGuild[ladderPoints] > 0) ? "<tr class=row2><td>$plang[pk_battleofthebest_points]: </td><td align=right> $myGuild[ladderPoints] </td></tr>" : "" ;
		        $output .= ($plang[pk_battleofthebest_bestPlace] > 0) ? "<tr class=row2><td>$plang[pk_battleofthebest_bestPlace]: </td><td align=right> $myGuild[ladderRankMax] </td></tr>" : "" ;
		        $output .= "<tr class=><td colspan=2 align=center><b><a href='http://www.battleofthebest.com/gruppen/index.php?p=ranking' target=_blank>$plang[pk_battleofthebest_moreInfos]...</a></b></td></tr>" ;
		        $output .= "</table><br>";        	
	        }else{	 
	        	if ($conf_plus[wol_group_id] < 1) 
	    		{
	    			$output .= "<table width=100%><tr align=center><td align=center>$plang[pk_battleofthebest_noID]</td></tr></table><br>";
	    		}else{        	
					$registerstring = "http://www.battleofthebest.com/index.php?p=registrieren&IDWol=".$conf_plus['wol_group_id']."
					&guild=".urlencode($eqdkp->config['guildtag'])."&realm=".urlencode($conf_plus[pk_servername])."&region=$conf_plus[pk_server_region]
					&url=http://".$eqdkp->config['server_name'].$eqdkp->config['server_path']."
					&user=".$user->data['username']."&mail=".$user->data['user_email'];
		            $output .= "<table width=100%><tr align=center><td align=center><a href='$registerstring' target=_blank>$plang[pk_battleofthebest_join]</a></td></tr></table><br>";
	    		}
	        }

	        //top5
	        if (is_array($xml)) 
	        {
	        	if (!$conf_plus[pk_battleofthebest_best5])  
	        	{
	        		$breaker = ($conf_plus[pk_battleofthebest_countLadder]) ? $conf_plus[pk_battleofthebest_countLadder] : 5 ;
	        		$bestHeader = ($xml[channel][league_activ]=='1') ?  $plang[pk_battleofthebest_best].' '.$breaker : $plang[pk_battleofthebest_best_pre] ;
	        		$i=1;        	
			        $output .= '<table width="100%">';
			        $output .= "<tr class=row2><td colspan=2 align=center><b>$bestHeader</b></td></tr>" ;
			        foreach ($xml[channel][item] as $item) 
			  		{  			  						  		   			
			   			$output .= "<tr class=row2><td>$i.</td><td><a href='$item[link]' target=_blank>".utf8_decode($item[title])."</a> </td></tr>" ;
			   			$i++;
			   			if ($i > $breaker) {break;}   			
					}

					$output .= "<tr class=><td colspan=2 align=center><b><a href='http://www.battleofthebest.com/gruppen/index.php?p=BotbGuilds' target=_blank>$plang[pk_battleofthebest_leagueShow]...</a></b></td></tr>" ;
					$output .= "</table><br>";
	        	}	
	
	        	//Challenge
				$task = (getUserLanguage() == 'german') ? utf8_decode($xml[channel][task_de]) : $xml[channel][task_eng] ;
				$output .= "<table width=100%><tr align=center class=row2><td align=center><b> $plang[pk_battleofthebest_challenge] </b></td></tr>" ;	        	
				$output .= "<tr align=center><td align=center>". $task ." </td></tr>" ;	        	
				$output .= "<tr align=center><td align=center class=row2>". $plang[pk_battleofthebest_runtime] ." </td></tr>" ;	        	
				$output .= "<tr align=center><td align=center nowrap=nowrap>". date($user->style['date_time'], $xml[channel][task_start]) ." - ". date($user->style['date_time'], $xml[channel][task_end]) ." </td></tr></table>" ;
					        	
	        }
	        	         		
	    	$pdc->put('portal.modul.battleofthebest.'.$eqdkp_root_path,$output,18000,false,true);
  		}
		
    	
		return $output;
  }
}
?>
