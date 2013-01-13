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
 * $Id: german.php 5387 2009-07-30 20:24:40Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'membermap'             => 'Mitgliederkarte',
  'pk_membermap_no_data'  => 'Es wurde keine Adresse oder keine Stadt in deinem Userprofil gesetzt. MemberMap wird nur mit diesen Informationen funktionieren.',
  'pk_mmp_googlekey'      => 'Google Maps API Key<br/><a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">Kostenlosen API Key generieren</a>',
  'pk_membermap_no_gmapi' => 'Es wurde kein <i>Google Maps</i> API Key eingegeben. Dieser kann <a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">auf dieser Seite</a> kostenlos erstellt werden.',
  'pk_membermap_window'   => 'Mitgliederkarte',
  'pk_membermap_noaccess'	=> 'Kein Zugriff, bitte Einloggen',
  'pk_mmp_shwusername'    => 'Zeige den Benutzernamen anstatt des richtigen Namens',
  'pk_mmp_useziptown'     => 'Benutze die PLZ anstatt der Stadt (Kann in manchen Ländern zu Problemen führen)',
));
?>
