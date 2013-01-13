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
 * $Id: rolemanager.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

if (!is_array($memisop)){
	// Check user permission
	$user->check_auth('a_raidplan_config');
}

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);

  if($_POST['performsave']){
    
    $mpdues = ($_POST['editid']) ? 'UPDATE'  : 'INSERT';
    
    // Build the Query
    $query = $db->build_query($mpdues, array(
      'role_name'     => stripslashes($_POST['role_name']),
      'role_image'    => $_POST['role_image'],
      'role_classes'  => implode("|",$_POST['role_classes']),
    ));
    
    // Perform the action
    if($_POST['editid']){
      $db->query('UPDATE '.ROLES_TABLE.' SET '.$query." WHERE role_id='".(int) $_POST['editid']."'");
    }else{
      $db->query('INSERT INTO ' . ROLES_TABLE . $query);
    }
    echo "<script>parent.window.location.href = 'settings.php';</script>";
  }else{
    
    // Class Dropdown
		$rasql = 'SELECT class_id, class_name, class_armor_type
              FROM ' . CLASS_TABLE . '
              WHERE class_id > 0
              ORDER BY class_id';
    $result2 = $db->query($rasql);
    while ( $row2 = $db->fetch_record($result2) ){
      $classdropdwn[$row2['class_id']] = stripslashes($row2['class_name']).' ('.$row2['class_armor_type'].')';
    }
    $db->free_result($result2);
    
  	// Load the roles
	  $sql  = 'SELECT * FROM '.ROLES_TABLE.' WHERE role_id='.(int) $_GET['editid'];
    $result = $db->query($sql);
	  $row = $db->fetch_record($result);
	  $db->free_result($result);
  	
    $tpl->assign_vars(array(
              'S_ADD'         => true,
              'F_ROLEMANAGER' => 'rolemanager.php'.$SID,
              'EDITID'        => $_GET['editid'],
              'MULTISELECT'   => $khrml->MultiSelect('role_classes', $classdropdwn, $row['role_classes'], '', '', 'input', 8),
              'REALNAME'      => $row['role_name'],
              'SHORTNAME'     => $row['role_image'],
              
              'L_LANGNAME'    => $user->lang['rp_role_langname'],
              'L_CLASSES'     => $user->lang['rp_role_classes'],
              'L_NAME'        => $user->lang['rp_role_name'],
              'L_BUTTSAVE'	  => $user->lang['rp_save'],
		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_rolemanager']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/rolemanager.html',
			'gen_simple_header'      => true,
			'display'                => true)
    );
	}
?>
