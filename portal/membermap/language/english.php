<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-07-30 22:24:40 +0200 (Thu, 30 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5387 $
 * 
 * $Id: english.php 5387 2009-07-30 20:24:40Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'membermap'             => 'Member Map',
  'pk_membermap_no_data'  => 'No Address or town set in your user profile. MemberMap will only show information if you\'ve set up your Profile information.',
  'pk_mmp_googlekey'      => 'Google Maps API key<br/><a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">Get free API key</a>',
  'pk_membermap_no_gmapi' => 'You have not entered an Google Maps API Key. You can get it for free on <a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">this</a> Page.',
  'pk_membermap_window'   => 'Member Map',
  'pk_membermap_noaccess'	=> 'No access, please sign in',
  'pk_mmp_shwusername'    => 'Show Username instead of Real Name',
  'pk_mmp_useziptown'     => 'Use ZIP Code instead of Town (might not work in all countries)',
));
?>
