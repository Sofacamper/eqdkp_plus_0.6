<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: cleanup.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Get the plugin
$user->check_auth('a_raidplan_');
$raidplan = $pm->get_plugin(PLUGIN);

  if($_GET['mode']== 'cleanitnow' && isset($_POST['cleanup_days'])){
    $raid_date = $stime->DoTime()-($_POST['cleanup_days']*24*60*60);
    $sql = 'DELETE FROM '.RP_RAIDS_TABLE.' WHERE raid_date < '.$raid_date;
    $db->query($sql);
    
     echo "<script>parent.window.location.href = 'index.php';</script>";
  }else{
    $tpl->assign_vars(array(
              'CLOSE_WINDOW'      => false,
              'F_CLEANUP'  	      => 'cleanup.php'.$SID.'&amp;mode=cleanitnow',
              'CLEANUP_DAYS'      => 30,
              
              'JS_CONFIRM_DEL'    => $jquery->Dialog_Confirm('PerformCleanup', $user->lang['rp_confirmclean'], "document.cleanRaids.submit();"),

              'L_WARN_MSSG'       => $user->lang['rp_cleanwarn'],
              'L_CLEAN_ALL_TXT1'  => $user->lang['rp_cleantxt1'],
              'L_CLEAN_ALL_TXT2'  => $user->lang['rp_cleantxt2'],
              'L_BUTT_CLEAN'	    => $user->lang['rp_button_clean'],
              'L_BUTT_CLOSE'      => $user->lang['rp_button_close'],

		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_editnote']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/cleanup.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );
	}
?>
