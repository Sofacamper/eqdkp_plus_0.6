<?php
/*************************************************\
*             mediacenter 4 EQdkp plus              *
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
define('PLUGIN', 'mediacenter');
$eqdkp_root_path = '../../';
include_once($eqdkp_root_path . 'common.php');

if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }

// Build the data in arrays. Thats easier than editing the template file every time.
$additions = array(
  'Design & Code by'    => ' GodMod',
  'Special Thanks to'  	=> ' Badtwin & zAfLu',
  //'Based on'           	=> ' EQdkp-Plus Gallery by Badtwin & Lunary',
  
);
        
foreach ($additions as $key => $value){
  $tpl->assign_block_vars('addition_row', array(
    'MC_KEY'    => $key,
    'MC_VALUE'  => $value,
    )
  );
}

$mc_status  = (strtolower($pm->plugins['mediacenter']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['mediacenter']->vstatus.' ';

$act_year = date("Y", time());

$tpl->assign_vars(array(
  'MC_I_ITEM_NAME'   	=> 'credits/mc_logo.png',
  'MC_L_VERSION'      	=> $pm->get_data('mediacenter', 'version').$mc_status,
  'MC_L_DEVTEAM'		=> 'Copyright',
  'MC_L_YEARR'			=> ( $act_year == 2009) ? $act_year : '2009 - '.$act_year,
  'MC_L_TXT_DEVTEAM'  	=> 'GodMod',
  'MC_L_URL_WEB'      	=> 'Web',
  'MC_D_WEB_URL'      	=> 'www.eqdkp-plus.com',
  'MC_L_ADDITONS'     	=> $user->lang['mc_additionals'],
  'MC_L_LICENCE'		=> $user->lang['mc_licence'],
));


$eqdkp->set_vars(array(
  'page_title'    => $user->lang['mediacenter'],
  'template_file' => 'about.html',
  'template_path' => $pm->get_data('mediacenter', 'template_path'),
  'display'       => true)
);
?>
