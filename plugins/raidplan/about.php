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
 * $Id: about.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = '../../';
include_once('includes/common.php');

if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die($user->lang['is_not_installed']); }

  // Build the data in arrays. Thats easier than editing the template file every time.
     $additions = array(
      'Raidplan Logo'     	=> ' by Cattiebrie',
      'Icons'								=> ' Nuvola Package for Linux',
      'Former Versions' 		=> ' by A.Stranger & Urox',
      'Premium Sponsor'			=> ' Klaus Damschen, Clanos',
      'Special Thanks'			=> ' all the testers',
  );
        
  foreach ($additions as $key => $value){
    $tpl->assign_block_vars('addition_row', array(
        'KEY'    => $key,
        'VALUE' => $value,
      )
    );
  }

$tpl->assign_vars(array(
    'I_ITEM_NAME'               => 'credits/rp_logo.png',
    
    'D_AUTHOR_NAME'             => $pm->plugins['raidplan']->copyright,
    'D_AUTHOR_CITY'             => 'Germany',
    'D_WEB_URL'                 => 'eqdkp-plus.com',
    'L_CREATED_BY'              => $user->lang['rp_created by'],
    'L_CONTACT_INFO'            => $user->lang['rp_contact_info'],
    'L_URL_PERSONAL'            => $user->lang['rp_url_personal'],
    'L_URL_WEB'                 => $user->lang['rp_url_web'],
    'L_ADDITONS'                => $user->lang['rp_additions'],
    'L_TXT_DEVTEAM'							=> $user->lang['rp_created_devteam'],
    'L_TXT_DEVTEAM2'						=> $user->lang['rp_created_devteam2'],
    'L_DEVTEAM'									=> $user->lang['rp_copyright'],
    'L_VERSION'                 => $pm->get_data('raidplan', 'version'),
    'L_BUILD'                   => $pm->plugins['raidplan']->build,
    'L_STATUS'                  => $pm->plugins['raidplan']->vstatus,
    'L_YEARR'										=> $stime->DoDate("%Y", $stime->DoTime()),
));


$eqdkp->set_vars(array(
	'page_title'    => $rpclass->GeneratePageTitle($user->lang['rp_credits']),
	'template_file' => 'about.html',
	'template_path' => $pm->get_data('raidplan', 'template_path'),
	'display'       => true)
);
?>
