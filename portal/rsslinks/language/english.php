<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-02-15 21:54:33 +0100 (Mon, 15 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7262 $
 * 
 * $Id: english.php 7262 2010-02-15 20:54:33Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'rsslinks'                 	=> 'Quicklinks & RSS',
  'ql_next_raids'               => 'Next Raids RSS',
  'ql_news'                 	=> 'News RSS',
  'ql_last_items'               => 'Last Items RSS',
  'ql_last_raid'                => 'Last Raids RSS',
  'ql_sb'                		=> 'Shoutbox RSS',

  
  'ql_getdkp_link' 	            => 'GetDKP Link',
  'ql_getdkp_dl'             	=> 'GetDKP for WoW',
  'ql_ctrt'             		=> 'CTRT for WoW',
  'ql_vista_gadget'             => 'EQdkp Vista Gadget',
  'ql_iphonet'             		=> 'iPhone App!',

));
?>
