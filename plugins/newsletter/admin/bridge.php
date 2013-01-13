<?PHP
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 1.3.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'newsletter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'newsletter')) { message_die('The Newsletter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_newsletter_');


//NEXT RAIDS
//===================================
if ($pm->check(PLUGIN_INSTALLED, 'raidplan')){

	$total_recent_raids = $total_raids = 0;
	
	// Load the Raidplan Config
	$sql = 'SELECT * FROM __raidplan_config';
	$settings_result = $db->query($sql);
	while($roww = $db->fetch_record($settings_result)){
		  $rpconf[$roww['config_name']] = $roww['config_value'];
	}
	$db->free_result($settings_result);
	
	
	// init the time class
	if(!class_exists('DateFormats')){
		include($eqdkp_root_path . 'plugins/raidplan/includes/time.class.php');
		 $stime = new DateFormats($user->lang['rp_calendar_lang'], $rpconf['rp_dstcheck'], $rpconf['rp_timezone']); // Init the Time Management Class
	}
	$rpconf['timeformats'] = $stime->timeFormats(); // Load the Time Format Array
	
	// The event icons..
	$sql = "SELECT event_icon, event_name FROM __events";
	$icon = $db->query($sql);
	while($eventrow = $db->fetch_record($icon)){
		$pleventicons[$eventrow['event_name']] = $eventrow['event_icon'];
	}
	
	//looking for next Raids
	$sql = "SELECT * FROM __raidplan_raids
		WHERE raid_date > ".$db->escape($stime->DoTime())."
		AND raid_closed = '0'
		ORDER BY `raid_date`
		LIMIT ".$db->escape($conf['bridge_items']);
	
	$result = $db->query($sql);
	$raidcount = $db->num_rows($result);
	
			
	//go though all given next raids
	while ( $row = $db->fetch_record($result) ){
		$tpl->assign_block_vars('nextraids_row', array(
							"DATE" => $stime->DoDate($rpconf['timeformats']['long'], $row['raid_date']). " - ".$stime->DoDate($rpconf['timeformats']['time'], $row['raid_date_finish']),	
							"ID"	=> $row['raid_id'],
							"IMG"	=> "<img src='".$eqdkp_root_path."/games/".$eqdkp->config['default_game']."/events/".$pleventicons[stripslashes($row['raid_name'])]."' width='40' height='40'>",
							"NAME"	=> sanitize($row['raid_name']),
							"NOTE"	=> $html->ToolTip(sanitize($row['raid_note']),  $nlclass->wrapText(sanitize($row['raid_note']), 80)),
							"NOTE_LONG"	=> sanitize($row['raid_note']),
							"ROW_CLASS" => $eqdkp->switch_row_class(),
							
		));
	};
}
			
//HOMEPAGE-NEWS
//=================================
$sql = "SELECT * FROM __news ORDER BY news_date DESC LIMIT ".$db->escape($conf['bridge_items']);
$news = $db->query($sql);
while ( $row = $db->fetch_record($news) ){
	
	$tpl->assign_block_vars('news_row', array(
						"DATE" => date($user->style['date_time'], $row['news_date']),
						"ID"	=> $row['news_id'],
						"NAME"	=> sanitize($row['news_headline']),
						"MESSAGE"	=> $html->ToolTip($nlclass->wrapText($nlclass->stripBBcode(sanitize($row['news_message'])), 500),  $nlclass->wrapText($nlclass->stripBBcode(sanitize($row['news_message'])), 80)),
						"MESSAGE_LONG" => sanitize($row['news_message']),
						"ROW_CLASS" => $eqdkp->switch_row_class(),
						
	));
	
}
 

//ALLVATAR-NEWS
//================================
include_once ($eqdkp_root_path."pluskernel/include/rss.class.php");
$avt_news = new rss();

$rss_number = 1 ;
		switch (strtolower($eqdkp->config['default_game']))
		{
			case 'wow': 				$rss_number = 1 ; break;
			case 'daoc': 				$rss_number = 7 ; break;
			case 'everquest': 			$rss_number = 10 ; break;
			case 'everquest2': 			$rss_number = 10 ; break;
			case 'lotro': 				$rss_number = 4 ; break;
			case 'tr': 					$rss_number = 19 ; break;
			case 'vanguard-soh': 		$rss_number = 5 ; break;
			case 'guildwars': 			$rss_number = 3 ; break;
			case 'aoc': 				$rss_number = 13 ; break;
			case 'warhammer': 			$rss_number = 14 ; break;
			case 'aion': 				$rss_number = 22 ; break;
			case 'runesofmagic': 		$rss_number = 37 ; break;
			
			default: $rss_number = '' ;
				break;
		}

		$lang = '-en';

		if (($eqdkp->config['default_lang'] == 'german') or ($user->data['user_lang'] == 'german'))
		{
			$lang = '';
		}

		$avt_news->checkURL_first = $conf_plus['pk_Rss_checkURL'] ;
		$avt_news->rssurl = 'http://rss.allvatar.com/news-'.$rss_number.$lang.'.xml';
		
		$avt_news->parseXML($avt_news->GetRSS($avt_news->rssurl));
		$news = $avt_news->news;
		if (is_array($news)){
			foreach($news as $elem){
				$tpl->assign_block_vars('avtnews_row', array(
						"DATE" => date($user->style['date_time'],strtotime(sanitize($elem['pubdate']))),
						"TITLE"	=> sanitize($elem['title']),
						"LINK"	=> sanitize($elem['link']),
						"MESSAGE"	=> $html->ToolTip(sanitize($elem['description']),  $nlclass->wrapText(sanitize($elem['description']), 80)),
						"MESSAGE_LONG" => sanitize($elem['description']),
						"AUTHOR"	=> sanitize($elem['author']),
						"ROW_CLASS" => $eqdkp->switch_row_class(),
						"ID"		=> rand(),
						
	));
			}
		}





    $tpl->assign_vars(array(

              "DKP_LINK"			=> $nlclass->generate_dkp_link(),
			  'JS_BRIDGE_TABS' 		=> $jquery->Tab_header('bridge_tabs'),
			  "L_NEXT_RAIDS"		=> $user->lang['nl_next_raids'],
			  "L_NEWS"				=> $user->lang['nl_news'],
			  "L_ALLVATAR_NEWS"		=> $user->lang['nl_allvatar_news'],
			  "L_NAME"				=> $user->lang['nl_name'],
			  "L_DESC"				=> $user->lang['nl_description'],
			  "L_DATE"				=> $user->lang['nl_date'],
			  "L_TITEL"				=> $user->lang['nl_titel'],
			  "L_ALLVATAR_READ_MORE"	=> $user->lang['nl_allvatar_readmore'],
			  "L_ALLVATAR_FOOTER"	=> $user->lang['nl_allvatar_footer'],
			  "L_INSERT"			=> $user->lang['nl_insert'],
			  "S_RP_INSTALLED"		=> $pm->check(PLUGIN_INSTALLED, 'raidplan'),
			  "SELECTED"			=> ($conf['bridge_preselected'] == 1 ) ? 'checked="checked"' : "",
				
              		  
  
			  
			  
			 //Common things
			'ABOUT_HEADER'          => $user->lang['dl_about_header'],	
			'NL_CREDITS'            => 'Credits',
			'NL_COPYRIGHT'          => $nlclass->Copyright(),


			  
		));
    
    $eqdkp->set_vars(array(
	    	'page_title'             => $nlclass->GeneratePageTitle($user->lang['nl_templates']),
			'template_path' 	     => $pm->get_data('newsletter', 'template_path'),
			'template_file'          => 'admin/bridge.html',
			'gen_simple_header'  	=> true,
			'display'                => true)
    );

?>