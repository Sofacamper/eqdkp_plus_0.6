<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-09-24 17:05:08 +0200 (Do, 24 Sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5938 $
 * 
 * $Id: armory_cron.php 5938 2009-09-24 15:05:08Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../../';
include_once('../../../../include/common.php');


$armory = new ArmoryChars("вов");
$ucdb = new AdditionalDB('charmanager_config');

$ucoutp = '';
//start the import
$sql = "SELECT member_name, member_id FROM __members ORDER BY member_name";
$result = $db->query($sql);
while($row = $db->fetch_record($result)){
	$ucisupdatable = false;
	$chardata = $armory->GetCharacterData($row['member_name'],stripslashes($conf['uc_servername']),$conf['uc_server_loc'], 'de_de');
  $arm_data = $armory->BuildMemberArray($chardata[0], $conf['uc_server_loc']);
	if($arm_data['name'] && ($arm_data['treeone'] or $arm_data['treetwo'] or $arm_data['treethree'])){
		$ucoutp .= $armory->UTF8tify($arm_data['name']).' ['.$arm_data['lastmodified'].']';
		$ucisupdatable = true;
	}elseif(!$arm_data['treeone'] && !$arm_data['treetwo'] && !$arm_data['treethree']){
		$ucoutp .= $row['member_name'].': <font color="orange">'.$user->lang['uc_notyetupdated'].'</font>'; 
	}else{
		$ucoutp .= $row['member_name'].': <font color="red">'.$user->lang['uc_noprofile_found'].'</font>'; 
	}

	if($ucisupdatable && is_array($arm_data)){
		$iii=1;$profarry = array();
		foreach($arm_data['professions']->children() as $professions){
			$profarry[$iii]['name'] 	= $professions['name'];
			$profarry[$iii]['value'] 	= $professions['value'];
			$iii++;
		}
		
		$dataarray = array(
										'member_name'			=> $armory->UTF8tify($arm_data['name']),
										'member_id'				=> $row['member_id'],
										'member_race_id'	=> $armory->ValueOrNull($arm_data['race_eqdkp']),
										'member_class_id'	=> $armory->ValueOrNull($arm_data['class_eqdkp']),
										'member_level'		=> $armory->ValueOrNull($arm_data['level']),
										'gender'					=> (($arm_data['genderid'] == 1) ? 'Female' : 'Male'),
										'faction'					=> (($arm_data['factionid'] == 1) ? 'Horde' : 'Alliance'),
										'guild'						=> $CharTools->convertEncoding($arm_data['guildname']),
										'last_update'			=> $armory->Date2Timestamp($arm_data['lastmodified']),
										
										'skill_1'					=> $armory->ValueOrNull($arm_data['spec1']['treeOne']),
	  								'skill_2'					=> $armory->ValueOrNull($arm_data['spec1']['treeTwo']),
	  								'skill_3'					=> $armory->ValueOrNull($arm_data['spec1']['treeThree']),
										
										'health_bar'			=> $arm_data['characterbars']->health['effective'],
										'second_bar'			=> $arm_data['characterbars']->secondBar['effective'],
										'second_name'			=> $arm_data['characterbars']->secondBar['type'],
										'prof1_value'			=> $armory->ValueOrNull($profarry[1]['value'], 'string'),
										'prof1_name'			=> $armory->ValueOrNull($profarry[1]['name'], 'string'),
										'prof2_value'			=> $armory->ValueOrNull($profarry[2]['value'], 'string'),
										'prof2_name'			=> $armory->ValueOrNull($profarry[2]['name'], 'string'),
										
    	              'fire'						=> $armory->ValueOrNull($arm_data['resistances']->fire['value']),
  									'nature'					=> $armory->ValueOrNull($arm_data['resistances']->nature['value']),
  									'shadow'					=> $armory->ValueOrNull($arm_data['resistances']->shadow['value']),
  									'arcane'					=> $armory->ValueOrNull($arm_data['resistances']->arcane['value']),
  									'frost'					  => $armory->ValueOrNull($arm_data['resistances']->frost['value']),
    	               );
					  	   		if($arm_data['dualspec']){
											$dataarray['skill2_1'] = $armory->ValueOrNull($arm_data['spec2']['treeOne']);
											$dataarray['skill2_2'] = $armory->ValueOrNull($arm_data['spec2']['treeTwo']);
											$dataarray['skill2_3'] = $armory->ValueOrNull($arm_data['spec2']['treeThree']);
										}
		        $CharTools->updateChar($row['member_id'], '', $dataarray, true);
	}	// end if updatable 
	$ucoutp .= '<br/>';
}

// Update the config
$dataSet = array('uc_profileimported'=> time());
$ucdb->UpdateConfig($dataSet, $ucdb->CheckDBFields('config_name'));
echo $ucoutp;  
?>
