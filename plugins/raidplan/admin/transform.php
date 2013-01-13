<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-07-29 13:41:36 +0200 (Di, 29 Jul 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2454 $
 * 
 * $Id: cleanup.php 2454 2008-07-29 11:41:36Z wallenium $
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

if($_POST['raid_id']){
  $myoptions = array($_POST['confirmed'], $_POST['signedin'], $_POST['notsure']);
  echo $rpclass->TransformRaid($_POST['raid_id'], $myoptions);
}else{
    $tpl->assign_vars(array(
              'F_TRANSFORM'   => 'transform.php'.$SID,
              
              'RAID_ID'       => $_GET['r'],
              
              'L_OPT_CONFIRM' => $user->lang['rp_trans_comfirm'],
              'L_OPT_SIGNIN'  => $user->lang['rp_trans_signin'],
              'L_OPT_NOTSURE' => $user->lang['rp_trans_notsure'],
              'L_BUTT_TRANS'  => $user->lang['rp_button_transform'],
              'L_BUTT_CLOSE'  => $user->lang['rp_button_close'],
		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_transform']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/transform.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );
}
?>
