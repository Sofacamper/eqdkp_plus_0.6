<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-06-13 21:53:13 +0200 (Sat, 13 Jun 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5074 $
 * 
 * $Id: u_import.php 5074 2009-06-13 19:53:13Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../';
include_once('../../../include/common.php');

if (!$user->check_auth('u_charmanager_manage', false) && !$user->check_auth('u_charmanager_add', false)) {
	 message_die($user->lang['uc_no_prmissions']);
}
if(!version_compare(phpversion(), '5.0.0', '>=')){
  die('This Plugin needs at least PHP-Version 5. Your Version is: '.phpversion().'.');
}

// Check if we have a MemberID
$myMemberID = ($in->get('member_id',0)) ? '?member_id='.$in->get('member_id',0) : '';
$yes_we_can = ($conf['uc_servername'] && $conf['uc_server_loc'] && $myMemberID) ? '&step=1' : '';
$hmtlout = '<meta http-equiv="refresh" content="0; URL=armory/armory.php'.$myMemberID.$yes_we_can.'">';
echo $hmtlout;
?>