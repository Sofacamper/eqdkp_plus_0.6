<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2011-09-22 21:55:41 +0200 (Thu, 22 Sep 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 11314 $
 * 
 * $Id: updateprofile.php 11314 2011-09-22 19:55:41Z wallenium $
 */
 
define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../../';
include_once($eqdkp_root_path .'/plugins/charmanager/include/common.php');

if(!$in->get('cron') == 'true'){
	$user->check_auth('a_charmanager_config');
}

$ucdb = new AdditionalDB('charmanager_config');
$armory = new bnet_armory($conf['uc_server_loc'], 'de_de');
if($_HMODE){
	$armory->setSettings(array('apiKeyPrivate'=>$bnetapi_private, 'apiKeyPublic'=>$bnetapi_public));
}

$sql = "SELECT member_name FROM __members ORDER BY member_name";
$members = array();
$ii = 0;
if (!($result = $db->query($sql))) { message_die('Could not obtain custom item data', '', __FILE__, __LINE__, $sql); }
while($row = $db->fetch_record($result)) {
  $members[$ii] = $row['member_name'];
  $ii++;
}

if($in->get('actual',0) < $in->get('count',0)){
	$nextcount 		= $in->get('actual',0)+1;
	$actualcount	= $in->get('actual',0);
	//Load the Armory Data
	$chardata = $armory->character($members[$actualcount], stripslashes($conf['uc_servername']), true);
	$errorornot = (isset($chardata['status'])) ? false : true;

	// Show the Char icon
	$myOutput = '';
	if($errorornot){
		echo "<script type='text/javascript'>
		function replace_img(){
      if (load_img.complete) {
        document['char_icon'].src=load_img.src;
        clearInterval(timerid);
      } 
    } 
		load_img = new Image();
		load_img.src = '".$armory->characterIcon($chardata)."';
		timerid = setInterval('replace_img()', 500);
		</script>";

		$myOutput .= '<img src="'.$armory->characterIcon($chardata).'" name="char_icon" alt="icon" width="44px" height="44px" align="middle"/> ';
	}
	
	$myOutput .= '('.($actualcount+1).'/'.$in->get('count',0).') ';

	if($errorornot){
		$myOutput  .= $members[$actualcount].' ['.$user->lang['uc_lastupdate'].': '.date('d.m.Y', ($chardata['lastModified']/ 1000)).']';
		$skipme			= false;
	}else{
		$myOutput  .= $members[$actualcount].': <span style="color:red">'.$chardata['reason'].'</span>';
		$skipme 		= true;
	}
	
	if($skipme){
		echo $myOutput;
	}else{
  	// Check for existing member name
  	$tmp_membeid = $db->query_first("SELECT member_id FROM __members WHERE member_name = '".$members[$actualcount]."'");
  	$isindatabase = ($tmp_membeid > 0) ? $tmp_membeid : '0';
  
  	if(!$isindatabase or $isindatabase == 0 or $isindatabase == '0'){
  		$myOutput  .= $members[$actualcount].': <span style="color:red">'.$user->lang['uc_error_with_id'].'</span>';
  	}else{
		$dataarray = array(
			'member_name'		=> $members[$actualcount],
			'member_id'			=> $isindatabase,
			'member_race_id'	=> $armory->ConvertID($chardata['race'], 'int', 'races'),
			'member_class_id'	=> $armory->ConvertID($chardata['class'], 'int', 'classes'),
			'member_level'		=> (isset($chardata['level'])) ? $chardata['level'] : 0,
			'gender'			=> ($chardata['gender'] == 1) ? 'Female' : 'Male',
			#'faction'			=> ($arm_data['factionid'] == 1) ? 'Horde' : 'Alliance',
			'guild'				=> $CharTools->convertEncoding($chardata['guild']['name'], 'decode'),
			'last_update'		=> ($chardata['lastModified']/ 1000),

			'skill_1'			=> ($chardata['talents'][0]['trees'][0]['total'] > 0) ? $chardata['talents'][0]['trees'][0]['total'] : 0,
			'skill_2'			=> ($chardata['talents'][0]['trees'][1]['total'] > 0) ? $chardata['talents'][0]['trees'][1]['total'] : 0,
			'skill_3'			=> ($chardata['talents'][0]['trees'][2]['total'] > 0) ? $chardata['talents'][0]['trees'][2]['total'] : 0,

			'health_bar'		=> $chardata['stats']['health'],
			'second_bar'		=> $chardata['stats']['power'],
			'second_name'		=> $chardata['stats']['powerType'],
			'prof1_value'		=> ($chardata['professions']['primary'][0]['rank']) ? $chardata['professions']['primary'][0]['rank'] : '',
			'prof1_name'		=> ($chardata['professions']['primary'][0]['name']) ? $CharTools->convertEncoding($chardata['professions']['primary'][0]['name'], 'decode') : '',
			'prof2_value'		=> ($chardata['professions']['primary'][1]['rank']) ? $chardata['professions']['primary'][1]['rank'] : '',
			'prof2_name'		=> ($chardata['professions']['primary'][1]['name']) ? $CharTools->convertEncoding($chardata['professions']['primary'][1]['name'], 'decode') : '',
		);

		// Skill tree 2 / DualSpec
		$dataarray['skill2_1'] = ($chardata['talents'][1]['trees'][0]['total'] > 0) ? $chardata['talents'][1]['trees'][0]['total'] : 0;
		$dataarray['skill2_2'] = ($chardata['talents'][1]['trees'][1]['total'] > 0) ? $chardata['talents'][1]['trees'][1]['total'] : 0;
		$dataarray['skill2_3'] = ($chardata['talents'][1]['trees'][2]['total'] > 0) ? $chardata['talents'][1]['trees'][2]['total'] : 0;

		echo $myOutput;
	  	if($errorornot){
	  		$CharTools->updateChar($isindatabase, '', $dataarray, true);
	    }
		} // end of null member id check
	} // end of skipme
	
	// Wait 2 second.. to prevent armory blacklisting..
	flush();
	sleep(2);
	
	// end of import into DB
	if($in->get('cron')){
    redirect('plugins/charmanager/games/wow/import/armory/updateprofile.php?cron=true&count='.$in->get('count',0).'&actual='.$nextcount);
	}else{
    redirect('plugins/charmanager/games/wow/import/armory/updateprofile.php?count='.$in->get('count',0).'&actual='.$nextcount);
  }
}

if($in->get('actual',0) == $in->get('count',0)){
  if($in->get('cron')){
    $dataSet = array('uc_profileimported'=> time());
    $ucdb->UpdateConfig($dataSet, $ucdb->CheckDBFields('config_name'));
    die('fertig');
  }else{
    $dataSet = array('uc_profileimported'=> time());
    $ucdb->UpdateConfig($dataSet, $ucdb->CheckDBFields('config_name'));
    $output = '<table><tr><td width="48px"><img src="../../../images/ok.png" alt="update" \></td><td>'. $user->lang['uc_profile_ready'].'</td></tr></table>';
    echo "<script>parent.document.getElementById('loadingtext').innerHTML = '".$output." ';</script>";
  }
}
?>