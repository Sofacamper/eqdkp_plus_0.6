<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-12-13 18:54:23 +0100 (Sa, 13 Dec 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: BadTwin $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 3356$
 *
 * $Id: wrapper.php 3356 2008-12-13 21:51:54Z BadTwin $
 */

/*if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}*/

define('EQDKP_INC', true);
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path . 'common.php');

global $conf_plus, $plang;

if ($_GET['search_item'] != '') {
  // World of Warcraft
  if ($_GET['search_db'] == 1){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://wowdata.buffed.de/?f='.$search_item);
  } elseif ($_GET['search_db'] == 2){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://de.wowhead.com/?search='.$search_item);
  } elseif ($_GET['search_db'] == 3){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://speedydragon.gamestar.de/search?query='.$search_item);
  } elseif ($_GET['search_db'] == 4){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://eu.wowarmory.com/search.xml?searchQuery='.$search_item.'&searchType=all');
  } elseif ($_GET['search_db'] == 5){
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $_GET['search_item']);
    $url = rawurlencode('http://thottbot.com/?s='.$search_item);
  } elseif ($_GET['search_db'] == 6){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://www.wowdb.com/search.aspx?search_text='.$search_item);
  }

  // Warhammer
  if ($_GET['search_db'] == 10){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://wardata.buffed.de/?f='.$search_item);
  } elseif ($_GET['search_db'] == 11){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://www.wardb.com/search.aspx?search_text='.$search_item);
  } elseif ($_GET['search_db'] == 12){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://war.allakhazam.com/search.html?q='.$search_item);
  }

  // LotRO
  if ($_GET['search_db'] == 40){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://lotro.allakhazam.com/search.html?q='.$search_item);
  } elseif ($_GET['search_db'] == 41){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://glingorn.de/index.php?page=search_result&searchstring='.$search_item);
  } elseif ($_GET['search_db'] == 42){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://lorebook.lotro.com/wiki/Special:Advancedsearch?type=item&action=search&item_type%5B%5D=all&name='.$search_item.'&desc=&min_i_level=&max_i_level=&min_r_level=&max_r_level=&min_armour=&max_armour=&min_dps=&max_dps=&min_damage=&max_damage=&min_speed=&max_speed=&min_slayer_bonus=&max_slayer_bonus=&x=0&y=0');
  }

  // Everquest2
  if ($_GET['search_db'] == 50){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://www.lootdb.com/?t=item&q='.$search_item);
  }

	// Runes of Magic
	if ($_GET['search_db'] == 60){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://romdata.buffed.de/?f='.$search_item);
  }

  // AION
  if ($_GET['search_db'] == 70){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = 'http://myaiondb.onlinewelten.com/search?q='.$search_item;
  } elseif ($_GET['search_db'] == 71){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://de.aiondatabase.com/search?q='.$search_item);
  } elseif ($_GET['search_db'] == 72){
    $search_item = utf8_encode($_GET['search_item']);
    $replace = array("\'", '\"', " ");
    $newstring = array("%27", "%22", "+");
    $search_item = str_replace($replace, $newstring, $search_item);
    $url = rawurlencode('http://www.aionarmory.com/search.aspx?search_text='.$search_item);
  }

  if ($conf_plus['pm_quicksearch_newwindow'] != 1){
    header("Location: ".$eqdkp_root_path."wrapper.php?id=quicksearch&f=".$url);
  } else {
    header("Location: ".$url);
  }
}
?>