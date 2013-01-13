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
 * $Id: english.php 7544 2010-03-30 16:14:19Z corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'battleofthebest'                 => 'Battle of the Best',
  'pk_battleofthebest_headtext'     => 'Module name',
  'pk_battleofthebest_guildCount'   => 'registered guilds',
  'pk_battleofthebest_wowLeague'    => 'WoW PvE league',
  'pk_battleofthebest_por'       	=> 'Pro League',
  'pk_battleofthebest_rank'      	=> 'Challenge rank',
  'pk_battleofthebest_rank_legague' => 'league rank',
  'pk_battleofthebest_points'      	=> 'Points',
  'pk_battleofthebest_league'       => 'League',
  'pk_battleofthebest_bestPlace'    => 'best result',
  'pk_battleofthebest_join'         => 'Sign up your guild now!',
  'pk_battleofthebest_best'      	=> 'Challenge rank top',
  'pk_battleofthebest_best_pre'     => 'Attendees',
  'pk_battleofthebest_challenge'    => 'Current challenge',
  'pk_battleofthebest_noID'         => 'To sign up for the Botb, you have to enter your World of Logs guild ID in the portal Settings!',  
  'pk_battleofthebest_WolID'        => 'World of Logs - Guild ID',
  'pk_battleofthebest_WolID_H'      => 'Having your World of Logs ID is mandatory. You can find the ID in your guild detail screen on WOL at the end of the URL, e.g. http://www.worldoflogs.com/guilds/6316/ . In this case your guild ID would be 6316 !',
  'pk_battleofthebest_best5'        => 'Don’t show top 5 table',
  'pk_battleofthebest_showcount'    => 'Show number of participating guilds.',
  'pk_battleofthebest_leagueShow'   => 'Challenge ranking...',
  'pk_battleofthebest_moreInfos'    => 'more infos',
  'pk_battleofthebest_countLadder'  => 'Count of guilds shown in the ranking. Default 5',
  'pk_battleofthebest_runtime' 		=> 'challenge duration',

));
?>
