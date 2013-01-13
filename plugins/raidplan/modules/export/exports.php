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
 * $Id: exports.php 2963 2008-11-03 12:24:11Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../../';
include_once('../../includes/common.php');
include_once('export.class.php');;

// Check user permission
$user->check_auth('u_raidplan_view');

// Get the plugin
$raidplan     = $pm->get_plugin(PLUGIN);
$exportclass  = new RaidplanExport;

$menu_structure = $exportclass->generate(true);
        
$group_structure = array(
										''		=> $user->lang['rp_nonqued_user'],
                    99		=> $user->lang['rp_queued_users'],
                  );

if($conf['rp_enable_groups'] == 1){
$group_structure2 = array(
                      100  	=> $user->lang['rp_ungrouped'],
                    1     => $user->lang['rp_group'].' 1',
                    2     => $user->lang['rp_group'].' 2',
                    3     => $user->lang['rp_group'].' 3',
                    4     => $user->lang['rp_group'].' 4',
                    5     => $user->lang['rp_group'].' 5',
                    6     => $user->lang['rp_group'].' 6',
                    7     => $user->lang['rp_group'].' 7',
                    8     => $user->lang['rp_group'].' 8',
                    9     => $user->lang['rp_group'].' 9'
                  );
  $group_structure = array_merge($group_structure, $group_structure2);
}

if($_GET['raid_id']){
  $raid_id = $_GET['raid_id'];
  $output = "<table><tr><td><form name='form'>
              <select name='link' SIZE='1' onChange='window.location.href = document.form.link.options[document.form.link.selectedIndex].value;'>";
  foreach($menu_structure as $key=>$value){
    $selvalue = ($_GET['output'] == $key) ? 'selected=selected' : '';
    $output .= "<option value='exports.php?raid_id=".$raid_id."&output=".$key."' ".$selvalue."> ".$value." </option>";
  }
  $output .= "</select>";
  if($_GET['output'] == 'WoWMacroexport'){
  	$output .= "  <select name='link2' SIZE='1' onChange='window.location.href = document.form.link2.options[document.form.link2.selectedIndex].value;'>";
  	foreach($group_structure as $key=>$value){
    	$selvalue = ($_GET['group'] == $key) ? 'selected=selected' : '';
    	$output .= "<option value='exports.php?raid_id=".$raid_id."&output=WoWMacroexport&group=".$key."' ".$selvalue."> ".$value." </option>";
  }
  	$output .= "</select>";
  }
  $output .= "</td></tr><tr><td>";
  
  $flipstruc = array_flip($menu_structure);
  if($_GET['output'] && in_array($_GET['output'], $flipstruc)){
    $exportplug = new $_GET['output']($raid_id);
    $output .= $exportplug->output;
	}else{
    $output .= $exportclass->SelectOutput();
  }
	$output .= "</td></tr></table>";
  echo $output;
}
?>
