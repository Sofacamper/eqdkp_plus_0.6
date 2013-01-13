<?php
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

define('EQDKP_INC', true);
define('PLUGIN', 'downloads');
$eqdkp_root_path = '../../';
include_once($eqdkp_root_path . 'common.php');

if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Download-plugin is not installed.'); }

// Build the data in arrays. Thats easier than editing the template file every time.
$additions = array(
  'Design & Code by'    => ' GodMod',
  'Special Thanks to'  	=> ' Lightstalker, Badtwin & zAfLu',
  //'Based on'           	=> ' EQdkp-Plus Gallery by Badtwin & Lunary',
  
);
        
foreach ($additions as $key => $value){
  $tpl->assign_block_vars('addition_row', array(
    'DL_KEY'    => $key,
    'DL_VALUE'  => $value,
    )
  );
}

$dl_status  = (strtolower($pm->plugins['downloads']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['downloads']->vstatus.' ';

$act_year = date("Y", time());

$tpl->assign_vars(array(
  'DL_I_ITEM_NAME'   	=> 'credits/dl_logo.png',
  'DL_L_VERSION'      	=> $pm->get_data('downloads', 'version').$dl_status,
  'DL_L_DEVTEAM'		=> 'Copyright',
  'DL_L_YEARR'			=> ( $act_year == 2009) ? $act_year : '2009 - '.$act_year,
  'DL_L_TXT_DEVTEAM'  	=> 'GodMod',
  'DL_L_URL_WEB'      	=> 'Web',
  'DL_D_WEB_URL'      	=> 'www.eqdkp-plus.com',
  'DL_L_ADDITONS'     	=> $user->lang['dl_additionals'],
  'DL_L_LICENCE'		=> $user->lang['dl_licence'],
));


$eqdkp->set_vars(array(
  'page_title'    => $user->lang['downloads'],
  'template_file' => 'about.html',
  'template_path' => $pm->get_data('downloads', 'template_path'),
  'display'       => true)
);
?>
