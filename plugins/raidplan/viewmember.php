<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2012-06-25 15:28:34 +0200 (Mon, 25 Jun 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 11834 $
 * 
 * $Id: viewmember.php 11834 2012-06-25 13:28:34Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../';
include_once('includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('u_raidplan_view');

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);

class RaidPlan_View_Member extends EQdkp_Admin
{
	var $classes = array();
	var $races = array();
	var $members = array();
	var $ranks = array();
	var	$user_id = '';
	var	$username = '';

  // ---------------------------------------------------------
  // Constructor
  // ---------------------------------------------------------
  function RaidPlan_View_Member(){
    global $db, $eqdkp, $user, $tpl, $pm, $SID; 
    parent::eqdkp_admin();
		$user_id = htmlspecialchars(strip_tags($_GET['user_id']), ENT_QUOTES);

		// Get classes
		$sql =  "SELECT class_id, class_name
			       FROM " . CLASS_TABLE . " as classes
			       ORDER BY class_id";
		$result = $db->query($sql);
		while ($row = $db->fetch_record($result)){
			$this->classes[$row['class_id']] = array(
				'id'      => $row['class_id'],
				'name'		=> $row['class_name'],
			);
		}
		$db->free_result($result);

		// Get ranks
		$sql =  "SELECT rank_id, rank_name, rank_prefix, rank_suffix
			       FROM " . MEMBER_RANKS_TABLE . " as ranks
			       ORDER BY rank_id";
		$result = $db->query($sql);
		while ($row = $db->fetch_record($result)){
			$this->ranks[$row['rank_id']] = array(
				'id'      => $row['rank_id'],
				'name'		=> $row['rank_name'],
				'prefix'	=> $row['rank_prefix'],
				'suffix'	=> $row['rank_suffix'],
			);
		}
		$db->free_result($result);

		// Get races
		$sql =  "SELECT race_id, race_name
			       FROM " . RACE_TABLE . " as races
			       ORDER BY race_id";
		$result = $db->query($sql);
		while ($row = $db->fetch_record($result)){
			$this->races[$row['race_id']] = array(
				'id'      => $row['race_id'],
				'name'		=> $row['race_name'],
			);
		}
		$db->free_result($result);

		// Get Users Charakters
		$sql = "SELECT m.*, u.username FROM ".MEMBERS_TABLE." m, ".MEMBER_USER_TABLE." mu, ".USERS_TABLE." u
			 		 	WHERE mu.user_id='" .(int) $user_id."'
			 		 	AND u.user_id = mu.user_id
            AND m.member_id=mu.member_id
					 	ORDER BY m.member_id";
			$result = $db->query($sql);
			while ($row = $db->fetch_record($result)){
				$this->members[$row['member_id']] = array(
					'id'				=> $row['member_id'],
					'name'			=> $row['member_name'],
					'race_id'		=> $row['member_race_id'],
					'class_id'	=> $row['member_class_id'],
					'rank_id'		=> $row['member_rank_id'],
					'level'			=> $row['member_level'],
				);
				$this->username = $row['username'];
			}
			$db->free_result($result);
    
        $this->assoc_buttons(array(
            'form' => array(
                'name'    => '',
                'process' => 'display_form',
                'check'   => 'u_raidplan_view'))
        );		
    }

  // ---------------------------------------------------------
  // Display form
  // ---------------------------------------------------------
  function display_form(){
    global $conf, $eqdkp_root_path, $SID, $db, $eqdkp, $user, $tpl, $pm, $rpclass, $rpconvert;

		foreach ($this->classes as $class){
			$tpl->assign_block_vars('classes', array(
				'ID'					=> $class['id'],					// ID of the class
				'NAME'				=> $class['name']					// Name of the class
				));	
		}
		foreach ($this->races as $race){
			$tpl->assign_block_vars('races', array(
				'ID'					=> $race['id'],					// ID of the class
				'NAME'				=> $race['name']					// Name of the class
				));	
		}
		foreach ($this->ranks as $rank){
			$tpl->assign_block_vars('ranks', array(
				'ID'					=> $rank['id'],
				'NAME'				=> $rank['name'],
				'PREFIX'			=> $rank['prefix'],
				'SUFFIX'			=> $rank['suffix']
				));	
		}
		foreach ($this->members as $member){
			$tpl->assign_block_vars('members', array(
					'ID'			=> $member['id'],
					'NAME'			=> $member['name'],
					'RACE'			=> $this->races[$member['race_id']]['name'],
					'RACE_ICON'		=> get_RaceIcon($member['race_id'],$member['name']),
					//'RACE_ID'		=> $member['race_id'],
					'RANK'			=> $this->ranks[$member['rank_id']]['name'],
					'CLASS'			=> $this->classes[$member['class_id']]['name'],
					'CLASS_ICON'	=> $rpclass->ClassIcon($this->classes[$member['class_id']]['name'], $member['class_id']),
					'LEVEL'			=> $member['level'],
				));	
		}

		$tpl->assign_vars(array(
			// Data
			'USERNAME'				=> $this->username,
			'CHAR_COUNT'			=> sprintf($user->lang['rp_vm_chars_found'], count($this->members)),
			'SELECTED_GAME'			=> $rpclass->SelectedGame(),
			'RPVM_ROOTPATH'			=> $eqdkp_root_path,

			// Language
			'L_VIEW_RAID_TITLE'		=> $user->lang['rp_raidplaner'],
			'L_NAME'				=> $user->lang['name'],
			'L_RANK'				=> $user->lang['rp_rank'],
			'L_RACE'				=> $user->lang['race'],
			'L_LEVEL'				=> $user->lang['level'],
			'L_CLASS'				=> $user->lang['rp_class'],
			'L_CHARS_OF'			=> $user->lang['rp_chars_of'],
		));

		$eqdkp->set_vars(array(
			'page_title'         => $rpclass->GeneratePageTitle($this->username),
			'template_file'      => 'viewmember.html',
			'gen_simple_header'  => true,
			'template_path'      => $pm->get_data('raidplan', 'template_path'),
			'display'            => true)
		);
	}
}

$myRaidList = new RaidPlan_View_Member;
$myRaidList->process();
?>
