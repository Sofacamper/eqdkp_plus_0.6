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
  'quicklogin'                 => '로그인',
  'pm_quicklogin_uname'        => '사용자 이름: ',
  'pm_quicklogin_passwd'       => '패스워드: ',
  'pm_quicklogin_autologin'    => '내 정보 기억하기 (Cookie): ',
  'pm_quicklogin_signin'       => '로그인',
  'pm_quicklogin_lostpwd'      => '패스워드 찾기',
  'pm_quicklogin_loggedin'     => 'Logged in as ',
  'pm_quicklogin_logout'       => '로그아웃',
  'pm_quicklogin_register'     => '등록',
));
?>
