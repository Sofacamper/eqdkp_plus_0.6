<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-07-29 00:30:30 +0200 (Tue, 29 Jul 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 2450 $
 * 
 * $Id: german.php 2450 2008-07-28 22:30:30Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'rankimage'             => 'Rangbild',
  'pk_ts_ranking_link'    => 'Link-Ziel des Rankingbildes',
  'pk_ts_ranking_url'     => 'URL zu einem Rankingbild oder Ranking-Skript z.b. von WoWJutsu oder Bosskillers',
  'pk_ts_bosskillers'			=> 'Ranking von bosskillers.com?(aktivieren, wenn das Skript von bosskillers.com als Ranking-Bild-URL verwendet wird)',
));
?>
