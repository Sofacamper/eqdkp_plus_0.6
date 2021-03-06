<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-12-02 11:54:02 +0100 (Di, 02 Dez 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 3293 $
 * 
 * $Id: roster.php  $
 */

define('EQDKP_INC', true);
$eqdkp_root_path = './';
include_once($eqdkp_root_path . 'common.php');
if($conf_plus['pk_noRoster'] == 1) { redirect('listmembers.php');}
$user->check_auth('u_member_list');
$roster_data = array();

# Amory Stuff
if(isset($conf_plus['pk_servername']) && isset($conf_plus['pk_server_region']) && $eqdkp->config['default_game'] == 'WoW')
{
include_once($eqdkp_root_path . 'libraries/ArmoryChars/ArmoryChars.class.php');
$armory = new ArmoryChars("���");
}

$sql = 'SELECT class_id, class_name from __classes where class_id <> "0" ';

if ( $class_result = $db->query($sql)) 
{
	while ( $class_row = $db->fetch_record($class_result) )
	{			
		//Class Template Vars				
		$class_array = array(
          'CLASS_NAME'    => $class_row['class_name'],
          'CLASS_ID'	  	=> $class_row['class_id'] ,
          'CLASS_ICONS'	  => ($game_icons['class_b']) ? get_ClassIcon('',$class_row['class_id'],true) : get_ClassIcon('',$class_row['class_id'],false) 
			);
	    $tpl->assign_block_vars('class_row', $class_array );	
		
	  //Member SQL
		$sql = 'SELECT m.member_name, m.member_id , m.member_status, m.member_level, m.member_class_id, m.member_race_id, m.member_rank_id, r.rank_id, r.rank_hide
		FROM __member_ranks r, __members m				
		WHERE m.member_class_id ='.$class_row['class_id'] .'
		AND (m.member_rank_id = r.rank_id)
		AND (r.rank_hide = 1 )';
		
		//CM Data
		if ($pm->check(PLUGIN_INSTALLED, 'charmanager'))
		{				           		          
			$sql = 'SELECT m.member_name, m.member_id , m.member_status, m.member_level, m.member_class_id, ma.* , m.member_race_id, m.member_rank_id, r.rank_id, r.rank_hide			
							FROM __member_ranks r, __members m
							LEFT JOIN __member_additions ma ON (ma.member_id=m.member_id)
							WHERE (m.member_class_id ='.$class_row['class_id'] .')
							AND (m.member_rank_id = r.rank_id)
							AND (r.rank_hide = 1 )' ;		           		          
		}		
    $members_result = $db->query($sql);
		
		//Member Array			
		while ( $row = $db->fetch_record($members_result) )
		{
			if ( $row )
			{			
				if(is_object($armory)){
					$menulink = array(); $skill = $mySkill = '';
					$menulink[1] = $armory->Link($conf_plus['pk_server_region'], $row['member_name'], $conf_plus['pk_servername'], 'char');
					$menulink[2] = $armory->Link($conf_plus['pk_server_region'], $row['member_name'], $conf_plus['pk_servername'], 'talent1');
					
					// Spec/ DualSpec
					if(($pm->check(PLUGIN_INSTALLED, 'charmanager')) and ($row['member_id'] > 0)){	
						$menulink[3] = ($row['guild']) ? $armory->Link($conf_plus['pk_server_region'], $row['member_name'], $conf_plus['pk_servername'], 'guild', $row['guild']) : '';
						$last_update = date($user->lang['uc_changedate'],$row['last_update']);
						$skill	= get_wow_talent_spec(ucwords(renameClasstoenglish($class_row['class_name'])), $row['skill_1'],$row['skill_2'] ,$row['skill_3'], $row['member_name'], $last_update, true);
						$mySkill = '<a href=' .$menulink[2] . ' target=_blank>'. $skill[icon].'</a>';
						if($row['skill2_1'] or $row['skill2_2'] or $row['skill2_3']){
							$skill = get_wow_talent_spec(ucwords(renameClasstoenglish($class_row['class_name'])), $row['skill2_1'],$row['skill2_2'] ,$row['skill2_3'], $row['member_name'], $last_update, true);
							$mySkill .= ' <a href=' .$menulink[2] . ' target=_blank>'. $skill[icon].'</a>';
						}
					} // end CM installed
					$roster_data[$class_row['class_name']] = $row['member_name'];
				} // end if object
				
				// Member Template Vars		
				$member_array = array(
		          'MEMBER_NAME'    			=> $row['member_name'],
		          'MEMBER_SPEC'    			=> ($menulink[2] <> '') ? $mySkill : '&nbsp;',
		          'MEMBER_CLASS_ICON'   => get_classImgListmembers($row['member_class'] , $row['member_class_id']),
		          'MEMBER_RACE_ICON'		=> ($menulink[1] <> '') ? '<a href='.$menulink[1].' target=_blank>'.get_RaceIcon($row['member_race_id'],$row['member_name']).'</a>' : get_RaceIcon($row['member_race_id'],$row['member_name']),
		          'MEMBER_LEVEL'        => ( $row['member_level'] > 0 ) ? '['. $row['member_level'].']' : '&nbsp;',		          
		          'MEMBER_GILDE'        => ( $menulink[3] <> '' ) ? '<a href='.$menulink[3].' target=_blank>'.$row['guild'].'</a>' : $row['guild'],
		          'MEMBER_ARMORY_LINK1' => $menulink[1],
		          'MEMBER_ARMORY_LINK2' => $menulink[2],
		          'MEMBER_ARMORY_LINK3' => $menulink[3],
		          'U_VIEW_MEMBER'   		=> 'viewmember.php'.$SID . '&amp;' . URI_NAME . '='.$row['member_name'] 
					);
			   	$tpl->assign_block_vars('class_row.member', $member_array );						
			}	// end of if row
		} // end of while		
	}	// end user
}	// end if class query

$eqdkp->set_vars(array(
    'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['listmembers_title'],
    'template_file' => 'roster.html',
    'display'       => true)
);

?>