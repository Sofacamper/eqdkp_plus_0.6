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
 * $Id: addguest.php 2963 2008-11-03 12:24:11Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

$memisop = $rpclass->CheckPermGroupLeader();
if (!is_array($memisop)){
	// Check user permission
	$user->check_auth('a_raidplan_update');
}

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);
  
  if($_GET['mode']== 'save'){
    if($_POST['membername']){
      if($_POST['guestid'] && $_POST['guestid'] != ''){
        $query = $db->build_query('UPDATE', array(
  					'class'									=> $_POST['class'],
  					'`group`'        				=> $_POST['group'],
  					'note'									=> $_POST['note'],
            ));
        $sql = 'UPDATE ' . RP_TEMPMEMBERS_TABLE . ' SET ' . $query . " WHERE tempmember_id='".$_POST['guestid']."' AND raid_id='".$_POST['raidid']."'";
        $blubb = $db->query($sql);
      }else{
  				$query = $db->build_query('INSERT', array(
  					'membername'						=> $_POST['membername'],
  					'class'									=> $_POST['class'],
  					'`group`'        				=> $_POST['group'],
  					'note'									=> $_POST['note'],
  					'raid_id'								=> $_POST['raidid'],
  					'attendees_signup_time'	=> $stime->DoTime(),
            ));
  				$sql = 'INSERT INTO ' . RP_TEMPMEMBERS_TABLE . $query;
  				$db->query($sql);
  		}
  	}
    echo "<script>parent.window.location.href = '../viewraid.php?s=&r=".$_POST['raidid']."';</script>";
  }else{
  // add
    if($_GET['edit']){
      $sql = 'SELECT * FROM ' . RP_TEMPMEMBERS_TABLE .' WHERE tempmember_id='.(int) $_GET['edit'].' AND raid_id='.(int) $_GET['raid'];
      $result = $db->query($sql);
	    $roww = $db->fetch_record($result);
	    $db->free_result($result);
	  }
	  
  	// build the class dropdown
	 $sql = 'SELECT class_id, class_name, class_min_level, class_max_level FROM ' . CLASS_TABLE .' GROUP BY class_id';
        $result = $db->query($sql);
	  while ( $row = $db->fetch_record($result) ){
      if(strtolower($row['class_name']) != 'unknown'){
  	  	if ( $row['class_min_level'] == '0' ) {
        	$option = ( !empty($row['class_name']) ) ? stripslashes($row['class_name'])." Level (".$row['class_min_level']." - ".$row['class_max_level'].")" : '(None)';
        }else{
        	$option = ( !empty($row['class_name']) ) ? stripslashes($row['class_name'])." Level ".$row['class_min_level']."+" : '(None)';
  	   	}
  			$tpl->assign_block_vars('class_row', array(
                  'VALUE' 	=> $row['class_id'],
  		            'OPTION'  => $option,
  		            'SELECT'  => ( $roww['class'] == $row['class_id']) ? ' selected="selected"' : '',
  		          )
  		        );
  		}
		}
		$db->free_result($result);
  	
    $tpl->assign_vars(array(
              'S_ADD'         => true,
              'F_ADD_GUEST'  	=> 'addguest.php'.$SID.'&amp;mode=save',
              'RAID_ID'				=> $_GET['raid'],
              'GUEST_ID'		  => ($_GET['edit']) ? $_GET['edit'] : '',
              
              // the edit input
              'MEMBER_NAME'   => $roww['membername'],
              'NOTE'          => $roww['note'],
              'GROUP'         => $roww['group'],
              
              'L_CLASS'				=> $user->lang['rp_class'],
              'L_NAME'				=> $user->lang['rp_char'],
              'L_GROUP'				=> $user->lang['rp_group'],
              'L_NOTE'				=> $user->lang['note'],
              'L_BUTTADD'			=> $user->lang['rp_saveguest'],

		));
    
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['rp_addguest']),
			'template_path' 				 => $pm->get_data('raidplan', 'template_path'),
			'gen_simple_header'      => true,
			'template_file'          => 'admin/addguest.html',
			'display'                => true)
    );
	}  
?>
