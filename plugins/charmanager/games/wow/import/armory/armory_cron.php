<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:05:50 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 12157 $
 * 
 * $Id: armory_cron.php 12157 2012-09-30 20:05:50Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../../';
include_once('../../../../include/common.php');
die('currently not working');

$armory = new ArmoryChars("вов");
$ucdb = new AdditionalDB('charmanager_config');

$ucoutp = '';
$ii = 0;
//start the import
$sql = "SELECT member_name, member_id FROM __members ORDER BY member_name";
$result = $db->query($sql);
while($row = $db->fetch_record($result)){
	$ucisupdatable = false;
	$chardata = $armory->GetCharacterData($row['member_name'],stripslashes($conf['uc_servername']),$conf['uc_server_loc'], 'de_de');
	$arm_data = $armory->BuildMemberArray($chardata[0], $conf['uc_server_loc']);

	if(is_array($arm_data)){
		$ucoutp .= $armory->UTF8tify($arm_data['name']).' ['.$arm_data['lastmodified'].']';
		$ucisupdatable = true;
	}elseif($arm_data == 'old_char'){
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
			'member_name'		=> $armory->UTF8tify($arm_data['name']),
			'member_id'			=> $row['member_id'],
			'member_race_id'	=> $armory->ValueOrNull($arm_data['race_eqdkp']),
			'member_class_id'	=> $armory->ValueOrNull($arm_data['class_eqdkp']),
			'member_level'		=> $armory->ValueOrNull($arm_data['level']),
			'gender'			=> (($arm_data['genderid'] == 1) ? 'Female' : 'Male'),
			'faction'			=> (($arm_data['factionid'] == 1) ? 'Horde' : 'Alliance'),
			'guild'				=> $CharTools->convertEncoding($arm_data['guildname']),
			'last_update'		=> $armory->Date2Timestamp($arm_data['lastmodified']),
													
			'skill_1'			=> $armory->ValueOrNull($arm_data['spec1']['treeOne']),
			'skill_2'			=> $armory->ValueOrNull($arm_data['spec1']['treeTwo']),
			'skill_3'			=> $armory->ValueOrNull($arm_data['spec1']['treeThree']),
													
			'health_bar'		=> $arm_data['characterbars']->health['effective'],
			'second_bar'		=> $arm_data['characterbars']->secondBar['effective'],
			'second_name'		=> $arm_data['characterbars']->secondBar['type'],
			'prof1_value'		=> $armory->ValueOrNull($profarry[1]['value'], 'string'),
			'prof1_name'		=> $armory->ValueOrNull($profarry[1]['name'], 'string'),
			'prof2_value'		=> $armory->ValueOrNull($profarry[2]['value'], 'string'),
			'prof2_name'		=> $armory->ValueOrNull($profarry[2]['name'], 'string'),
		);
		if($arm_data['dualspec']){
			$dataarray['skill2_1'] = $armory->ValueOrNull($arm_data['spec2']['treeOne']);
			$dataarray['skill2_2'] = $armory->ValueOrNull($arm_data['spec2']['treeTwo']);
			$dataarray['skill2_3'] = $armory->ValueOrNull($arm_data['spec2']['treeThree']);
		}
		$CharTools->updateChar($row['member_id'], '', $dataarray, true);
	}	// end if updatable 
	$ucoutp .= '<br/>';
	$ii++;
	// Wait 1 second.. to prevent armory blacklisting..
	if($ii > 40){
		flush();
		sleep(0.5);
		
	}
}

// Update the config
$dataSet = array('uc_profileimported'=> time());
$ucdb->UpdateConfig($dataSet, $ucdb->CheckDBFields('config_name'));
echo $ucoutp;  
?>