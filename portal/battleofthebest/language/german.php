<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-03-30 18:14:19 +0200 (Tue, 30 Mar 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7544 $
 * 
 * $Id: german.php 7544 2010-03-30 16:14:19Z corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'battleofthebest'                 => 'Battle of the Best',
  'pk_battleofthebest_headtext'     => 'Titel des Moduls',
  'pk_battleofthebest_guildCount'   => 'angemeldete Gilden',
  'pk_battleofthebest_wowLeague'   	=> 'WoW PvE Liga',
  'pk_battleofthebest_por' 	    	=> 'Pro League',
  'pk_battleofthebest_rank' 	    => 'Challenge Rank',
  'pk_battleofthebest_rank_legague' => 'Liga Rank',
  'pk_battleofthebest_points' 	    => 'Punkte',
  'pk_battleofthebest_league'       => 'Liga',
  'pk_battleofthebest_bestPlace'    => 'beste Platzierung',
  'pk_battleofthebest_join'         => 'Deine Gilde zum Battle of the Best anmelden!',
  'pk_battleofthebest_best' 	    => 'Challenge Ranking Top',
  'pk_battleofthebest_best_pre'     => 'Teilnehmer',
  'pk_battleofthebest_challenge'    => 'Aktuelle Challenge',
  'pk_battleofthebest_noID'         => 'Zum Anmelden in den Portaleinstellungen die World of Logs Gilden ID eingeben!',
  'pk_battleofthebest_WolID'        => 'World of Logs - Gilden ID',
  'pk_battleofthebest_WolID_H'      => 'Die World of Logs Gilden ID wird zwingend benötigt. Ihr findet die ID wenn ich auf eurer Gildenansicht bei WoL seid am Ende der der URL. z.b. http://www.worldoflogs.com/guilds/6316/  Dann wäre eure GildenID 6316!',
  'pk_battleofthebest_best5'        => 'Tabelle der besten 5 nicht anzeigen.',
  'pk_battleofthebest_showcount'    => 'Zeige die Anzahl der teilnehmenden Gilden.',
  'pk_battleofthebest_leagueShow'   => 'Challenge Ranking...',
  'pk_battleofthebest_moreInfos'    => 'mehr Infos',
  'pk_battleofthebest_countLadder'  => 'Anzahl der im Ranking angezeigten Gilden. Standart 5',
  'pk_battleofthebest_runtime' 		=> 'Laufzeit der Challenge',
  
));
?>
