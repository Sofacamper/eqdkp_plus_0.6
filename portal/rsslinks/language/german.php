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
 * $Id: german.php 7261 2010-02-15 20:52:02Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'rsslinks'                 	=> 'Quicklinks & RSS',
  'ql_next_raids'               => 'Geplante Raids RSS',
  'ql_news'                 	=> 'News RSS',
  'ql_last_items'               => 'Letzte Items RSS',
  'ql_last_raid'                => 'Vergangene Raids RSS',
  'ql_sb'                		=> 'Shoutbox RSS',
  
  'ql_getdkp_link' 	            => 'GetDKP Link',
  'ql_getdkp_dl'             	=> 'GetDKP für WoW',
  'ql_ctrt'             		=> 'CTRT für WoW',
  'ql_vista_gadget'             => 'EQdkp Vista Gadget',
  'ql_iphonet'             		=> 'iPhone App!',

));
?>
