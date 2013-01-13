<?php
/*
 * Project:     Quicklogin 4 EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-11-12 01:42:54 +0100 (Mi, 12 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: BadTwin $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 3120
 * 
 * $Id: german.php 3120 2008-11-12 01:42:54 BadTwin $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'quicklogin'                 => 'Quick Login',
  'pm_quicklogin_uname'        => 'Benutzername: ',
  'pm_quicklogin_passwd'       => 'Passwort: ',
  'pm_quicklogin_autologin'    => 'Login merken (Cookie): ',
  'pm_quicklogin_signin'       => 'Anmelden',
  'pm_quicklogin_lostpwd'      => 'Passwort verloren',
  'pm_quicklogin_loggedin'     => 'Angemeldet als ',
  'pm_quicklogin_logout'       => 'Ausloggen',
  'pm_quicklogin_register'     => 'Registrieren',
));
?>
