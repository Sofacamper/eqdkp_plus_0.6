<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-03-11 11:33:48 +0100 (Wed, 11 Mar 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: ghoschdi $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4193 $
 * 
 * $Id: german.php 4193 2009-03-11 10:33:48Z ghoschdi $
 */


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'teamspeak'                   => 'Teamspeak',
  'pk_portal_tsvoice'           => 'Voice Server',
  'pk_set_ts_title'             => 'TS-Server Title',
  'pk_set_ts_serverAddress'     => 'TS-Server IP',
  'pk_set_ts_serverQueryPort'   => 'Query Port',
  'pk_set_ts_serverUDPPort'     => 'UDP Port',
  'pk_set_ts_serverPasswort'    => 'Server Passwort',
  'pk_set_ts_channelflags'      => 'Sollen die Channelrechte angezeigt werden? (R,M,S,P etc.)',
  'pk_set_ts_userstatus'        => 'Soll der Status des Players angezeigt werden? (U,R,SA etc.)',
  'pk_set_ts_showchannel'       => 'Sollen die Channel angezeigt werden? (0 = nur Playerausgabe)',
  'pk_set_ts_showEmptychannel'  => 'Sollen die leeren Channel angezeigt werden?',
  'pk_set_ts_overlib_mouseover' => 'Soll der Mouseover Effekt vorhanden sein? (german only atm)',
  'pk_set_ts_joinable'          => 'Link zum joinen des Servers anzeigen?',
  'pk_set_ts_joinableMember'    => 'Link zum joinen nur eingelogten Usern anzeigen?',
  'pk_ts_shortmemnames'         => 'Zu lange Mitgliedsnamen kürzen',
  'pk_ts_memnamelength'         => 'Max. Länge des Mitgliedsnamen (danach wird gekürzt)',
  'pk_ts_channel_name'			=> 'Name des Channels, welcher angezeigt werden soll',
  'pk_ts_channel_name_help'     => 'Nur der eingetragene Channel und seine Subchannels werden angezeigt. Um alle anzuzeigen Feld leer lassen. Mehrere Channels per Komma trennen! Bsp.: Channel A,Channel B. Funktioniert nicht mit Subchannelnamen',
));
?>
