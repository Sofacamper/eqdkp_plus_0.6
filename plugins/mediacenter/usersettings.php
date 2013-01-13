<?php
/*************************************************\
*             MediaCenter 4 EQdkp plus            *
* ----------------------------------------------- *
* Project Start: 08/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1                                  *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');

if ($user->data['username'] == "") { redirect('login.php'); message_die('User is not logged in.'); }

$user->check_auth('u_mediacenter_view');

$nldb = new AdditionalDB('mediacenter_userconfig');

//Delete all settings of an user
if($in->get('default_reset')){
  	$delsql = "DELETE FROM __mediacenter_userconfig WHERE user_id= '".$db->escape($user->data['user_id'])."'";
    $db->query($delsql);
	
	//Delete Cache
	$pdc->del('plugin.mediacenter.usersettings.'.$user->data['user_id']);
}
  
  
 //Save Settings
if ($in->get('submit') != ""){
	
	//Update Mail-Type			
	$nldb->UpdateConfig(array("yt_nick" => $in->get('youtube_nick')), $nldb->CheckDBFields('config_name', $user->data['user_id']), $user->data['user_id']);
	//$nldb->UpdateConfig(array("yt_pw" => $in->get('youtube_pw')), $nldb->CheckDBFields('config_name', $user->data['user_id']), $user->data['user_id']);

	//Success-Message
	System_Message($user->lang['nl_success_settings_saved'],'mediacenter-Manager','green');
	
	//Delete Cache
	$pdc->del('plugin.mediacenter.usersettings.'.$user->data['user_id']);
 
};
  
  //=========================================
  //OUTPUT-SECTION
  
  //Load Usersettings
  //Cache: plugin.mediacenter.usersettings.{USER_ID}
  $usersetting_cache = $pdc->get('plugin.mediacenter.usersettings.'.$user->data['user_id'],false,true);
	if (!$usersetting_cache){
		$data_query = $db->query("SELECT * FROM __mediacenter_userconfig WHERE user_id = '".$db->escape($user->data['user_id'])."'");
		$data = $db->fetch_record_set($data_query);
		$pdc->put('plugin.mediacenter.usersettings.'.$user->data['user_id'],$data,86400,false,true);
	} else{
		$data = $usersetting_cache;
	};
	//Make an array with each usersetting
	if (is_array($data)){
		foreach ($data as $elem){
			$uconf[$elem['config_name']] = $elem['config_value'];
		};
	};		
  
   
  
  	//Bring all to template
    $tpl->assign_vars(array(
		
		//Values						 
		'YOUTUBE_NICK'				=> sanitize($uconf['yt_nick']),
		
		//Language
		'L_BUTTON_SAVE'				=> $user->lang['mc_save'],

		'L_SETTINGS'				=> $user->lang['mc_settings'],
		'L_YOUTUBE_NICK'			=> $user->lang['mc_youtube_nick'],
		'L_YOUTUBE_PW'				=> $user->lang['mc_youtube_pw'],

		

	));
    
    $eqdkp->set_vars(array(
	    'page_title'	 			=> $user->lang['config'],
		'template_path' 			=> $pm->get_data('mediacenter', 'template_path'),
		'template_file'         	=> 'usersettings.html',
		'display'               	=> true)
    );
?>
