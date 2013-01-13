<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-02-15 21:52:02 +0100 (Mon, 15 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7261 $
 * 
 * $Id: module.php 7261 2010-02-15 20:52:02Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['rsslinks'] = array(
			'name'           => 'Quicklinks & RSS',
			'path'           => 'rsslinks',
			'version'        => '1.0.0',
			'author'         => 'Corgan',
			'contact'        => 'http://www.eqdkp-plus.com',
			'description'    => 'Shows a Module with links to the News,Items,Raids RSS Feeds',
			'positions'      => array('left1', 'left2', 'right'),
      		'signedin'       => '0',
      		'install'        => array(
			                            'autoenable'        => '1',
			                            'defaultposition'   => 'left2',
			                            'defaultnumber'     => '9',
			                            ),
    );

if(!function_exists(rsslinks_module))
{
  function rsslinks_module()
  {
  	global $eqdkp , $plang, $pcache,$pm;
    
  	//Icons
  	$icon_rss = "<img src=".$pcache->BuildLink().'images/rss_24.png width="16" height="16" >';
  	$icon_getdkp_dl = "<img src=".$pcache->BuildLink().'images/getdkp.png width="16" height="16" >';
  	$icon_getdkp = "<img src=".$pcache->BuildLink().'images/link.png width="16" height="16" >';
  	$icon_vista = "<img src=".$pcache->BuildLink().'images/vista.png width="16" height="16" >';
  	$icon_iphone = "<img src=".$pcache->BuildLink().'images/EQDKPmobile_Logo.png width="16" height="16" >';
  	
  	//Links
	$rp_link = $pcache->BuildLink().$pcache->FileLink('rss.xml', 'raidplan') ;
	$news_link = $pcache->BuildLink().$pcache->FileLink('last_news.xml', 'eqdkp') ;
	$items_link = $pcache->BuildLink().$pcache->FileLink('last_items.xml', 'eqdkp') ;
	$raid_link = $pcache->BuildLink().$pcache->FileLink('last_raids.xml', 'eqdkp') ;
	$sb_link = $pcache->BuildLink().$pcache->FileLink('shoutbox.xml', 'shoutbox') ;
	$getdkp_link = $pcache->BuildLink().'getdkp.php' ;
	$getdkp_dllink = "http://www.eqdkp-plus.com/download.php?view.14" ;
	$ctrt_dllink = "http://www.eqdkp-plus.com/download.php?view.28" ;
	$vista_dllink = "http://www.eqdkp-plus.com/download.php?view.60" ;
	$iphone_dllink = "http://www.eqdkp-plus.com/download.php?view.93" ;

	$output = '<span class="small">';
	
	//Create Output
	if ($pm->check(PLUGIN_INSTALLED, 'raidplan'))
	{
  		$output .= (file_exists($pcache->FilePath('rss.xml', 'raidplan',false))) ? "<a href=".$rp_link.">".$icon_rss.$plang['ql_next_raids']. "</a><br>" : "" ; 
	}
  	$output .= (file_exists($pcache->FilePath('last_news.xml', 'eqdkp',false)))  ? "<a href=".$news_link.">".$icon_rss.$plang['ql_news']."</a><br>" : "";
  	$output .= (file_exists($pcache->FilePath('last_items.xml', 'eqdkp',false))) ? "<a href=".$items_link.">".$icon_rss.$plang['ql_last_items']." </a><br>" : "";
  	$output .= (file_exists($pcache->FilePath('shoutbox.xml', 'shoutbox',false))) ? "<a href=".$sb_link.">".$icon_rss.$plang['ql_sb']." </a><br>" : "";
  	$output .= (file_exists($pcache->FilePath('last_raids.xml', 'eqdkp',false))) ? "<a href=".$raid_link.">".$icon_rss.$plang['ql_last_raid']." </a><br><hr>": "";
  	
  	if ($eqdkp->config['default_game'] == 'WoW') 
  	{
	  	$output .= "<a href=".$getdkp_link." target=_blank>".$icon_getdkp.$plang['ql_getdkp_link']." </a><br>";
	  	$output .= "<a href=".$getdkp_dllink." target=_blank>".$icon_getdkp_dl.$plang['ql_getdkp_dl']." </a><br>";
	  	$output .= "<a href=".$ctrt_dllink." target=_blank>".$icon_getdkp_dl.$plang['ql_ctrt']." </a><br>";  		
  	}
  	
  	$output .= "<a href=".$vista_dllink." target=_blank>".$icon_vista.$plang['ql_vista_gadget']." </a><br>";
  	$output .= "<a href=".$iphone_dllink." target=_blank>".$icon_iphone.$plang['ql_iphonet']." </a><br>";
  	$output .= '</span>';
  	
	return $output;
  }
}
?>
