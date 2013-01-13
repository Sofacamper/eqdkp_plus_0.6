<?php
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 2.0.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

define('EQDKP_INC', true);
define('PLUGIN', 'newsletter');
$eqdkp_root_path = './../../';
include_once('include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'newsletter')) { message_die('The Newsletter - plugin is not installed.'); }

if ($user->data['username'] == "") { redirect('login.php'); message_die('User is not logged in.'); }
if ($conf['public_subscribe'] == 0) { redirect(''); message_die('Access denied.'); }

$user->check_auth('u_newsletter_view');

$nldb = new AdditionalDB('newsletter_userconfig');

//Delete all settings of an user
if($in->get('default_reset')){
  	$delsql = "DELETE FROM __newsletter_userconfig WHERE user_id= '".$db->escape($user->data['user_id'])."'";
    $db->query($delsql);
	
	//Delete Cache
	$pdc->del('plugin.newsletter.usersettings.'.$user->data['user_id']);
}
  
  
 //Save Settings
if ($in->get('submit') != ""){
	
	//Update Mail-Type			
	$nldb->UpdateConfig(array("mailtype" => $in->get('user_mailtype', 'text')), $nldb->CheckDBFields('config_name', $user->data['user_id']), $user->data['user_id']);

	//Success-Message
	System_Message($user->lang['nl_success_settings_saved'],'Newsletter-Manager','green');
	
	//Delete Cache
	$pdc->del('plugin.newsletter.usersettings.'.$user->data['user_id']);
 
};
  
  //=========================================
  //OUTPUT-SECTION
  
  //Load Usersettings
  //Cache: plugin.newsletter.usersettings.{USER_ID}
  $usersetting_cache = $pdc->get('plugin.newsletter.usersettings.'.$user->data['user_id'],false,true);
	if (!$usersetting_cache){
		$data_query = $db->query("SELECT * FROM __newsletter_userconfig WHERE user_id = '".$db->escape($user->data['user_id'])."'");
		$data = $db->fetch_record_set($data_query);
		$pdc->put('plugin.newsletter.usersettings.'.$user->data['user_id'],$data,86400,false,true);
	} else{
		$data = $usersetting_cache;
	};
	//Make an array with each usersetting
	if (is_array($data)){
		foreach ($data as $elem){
			$uconf[$elem['config_name']] = $elem['config_value'];
		};
	};		
  
  
	//Mail-Type-Dropdown
	$mailtype_dropdwn = array(
		'html'      => $user->lang['nl_mail_html'],
		'plain'  	=> $user->lang['nl_mail_plain'],
	);
  
  
  	//Bring all to template
    $tpl->assign_vars(array(
		
		//Values					 
		'DRDWN_MAIL_TYPE'      		=> $khrml->DropDown('user_mailtype', $mailtype_dropdwn, sanitize($uconf['mailtype'])),
		
		//Language
		'L_BUTTON_SAVE'				=> $user->lang['nl_button_save'],
		'L_BUTTON_RESET_DEFAULT'	=> $user->lang['nl_button_reset_default'],
		'L_CONF_GEN'				=> $user->lang['nl_general_settings'],
		'L_MAIL_TYPE'				=> $user->lang['nl_mail_type'],
		
		//Common things
		'ABOUT_HEADER'             	=> $user->lang['nl_about_header'],	
		'NL_CREDITS'                => 'Credits',
		'NL_COPYRIGHT'              => $nlclass->Copyright(),
		'NL_JS_ABOUT'				=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], 'about.php', '500', '350'),
	));
    
    $eqdkp->set_vars(array(
	    'page_title'	 			=> $nlclass->GeneratePageTitle($user->lang['config']),
		'template_path' 			=> $pm->get_data('newsletter', 'template_path'),
		'template_file'         	=> 'usersettings.html',
		'display'               	=> true)
    );
?>