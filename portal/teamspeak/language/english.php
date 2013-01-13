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
 * $Id: english.php 4193 2009-03-11 10:33:48Z ghoschdi $
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
  'pk_set_ts_serverPasswort'    => 'Server Password',
  'pk_set_ts_channelflags'      => 'Show Channelflags (R,M,S,P etc.)',
  'pk_set_ts_userstatus'        => 'Show Userstatus (U,R,SA etc.)',
  'pk_set_ts_showchannel'       => 'Show Channels? (0 = only Player)',
  'pk_set_ts_showEmptychannel'  => 'Show empty Channels?',
  'pk_set_ts_overlib_mouseover' => 'Show mouseover informations? (german only atm)',
  'pk_set_ts_joinable'          => 'Show link to join the server?',
  'pk_set_ts_joinableMember'    => 'Show the join link only registeres users?',
  'pk_ts_shortmemnames'         => 'Shorten long member names',
  'pk_ts_memnamelength'         => 'Max. length of the member names, shorten after xx chars.',
  'pk_ts_channel_name'          => 'Name of the parent channel which you want to display',
  'pk_ts_channel_name_help'		=> 'Only the entered channel and its subchannels will be displayed. Leave empty to display all. Separate multiple channels by comma! Ex. Channel A,Channel B. Does not work for subchannel names!',	
));
?>
