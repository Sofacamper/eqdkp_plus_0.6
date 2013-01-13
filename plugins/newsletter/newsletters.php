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
//Redirect user if he is not logged in or subscription is not allowed
if ($user->data['username'] == "") { redirect('login.php'); message_die('User is not logged in.'); }
if ($conf['public_subscribe'] == 0) { redirect(''); message_die('Access denied.'); }

$user->check_auth('u_newsletter_view');

$nldb = new AdditionalDB('newsletter_subscribers');


//Save Subscribed/Unsubscribed Newsletters
if($in->get('submit') != ""){
 	
	$subscr_array = array();
	
	//Load the subscribed newsletters
	//Cache: plugin.newsletter.subscribers.u{USER_ID}
	$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.u'.$user->data['user_id'],false,true);
	if (!$subscriber_cache){
		$data_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE user_id = '".$db->escape($user->data['user_id'])."'");
		$data = $db->fetch_record_set($data_query);
		$db->free_result($data_query);
		$pdc->put('plugin.newsletter.subscribers.u'.$user->data['user_id'],$data,86400,false,true);
	} else{
		$data = $subscriber_cache;
	};
	if (is_array($data)){
		foreach ($data as $elem){
			$subscr[$elem['letter_id']] = $elem;
		};
	};	
	
	//Load all newsletters
	//Cache: plugin.newsletter.letters
	$letter_cache = $pdc->get('plugin.newsletter.letters',false,true);
	if (!$letter_cache){
		$letter_query = $db->query("SELECT * FROM __newsletter_letters ORDER BY permission DESC");
		$letter_data = $db->fetch_record_set($letter_query);
		$pdc->put('plugin.newsletter.letters',$letter_data,86400,false,true);
		$db->free_result($letter_query);
	} else{
		$letter_data = $letter_cache;
	};
	if (is_array($letter_data)){
		foreach ($letter_data as $elem){
			$letter[$elem['id']] = $elem;
		};
	};	
	
	//Create new subscriptions
	foreach ($in->getArray('letters', 'int') as $elem) {
	
			if ($subscr[$elem] != "") {unset($subscr[$elem]);}
			
			else {
				
				if ($letter[$elem]['permission'] == 1) {
					$insert_query = $db->query("INSERT INTO __newsletter_subscribers (letter_id, user_id, status, date) VALUES ('".$db->escape($elem)."', '".$db->escape($user->data['user_id'])."', '0', NOW())");}
					
				if ($letter[$elem]['permission'] == 2) {
				$insert_query = $db->query("INSERT INTO __newsletter_subscribers (letter_id, user_id, status, date) VALUES ('".$db->escape($elem)."', '".$db->escape($user->data['user_id'])."', '1', NOW())");}
				
			};
			
	}; //END foreach

	//Delete Subscriptions
	foreach($subscr as $elem){
		
		if ($elem != "") {
			$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE user_id = '".$db->escape($user->data['user_id'])."' AND letter_id = '".$db->escape($elem['letter_id'])."'");
		};
	
	} //END foreach
	
	//Success-Message
	System_Message($user->lang['nl_success_settings_saved'],'Newsletter-Manager','green');
	
	//Delete Cache
	$pdc->del_prefix('plugin.newsletter.subscribers');

} //END Saving Data
  

//======================================
//Output-Section

	$subscr = array();
	//Load the subscribed newsletters
	//Cache: plugin.newsletter.subscribers.u{USER_ID}
	$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.u'.$user->data['user_id'],false,true);
	if (!$subscriber_cache){
		$data_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE user_id = '".$db->escape($user->data['user_id'])."'");
		$data = $db->fetch_record_set($data_query);
		$db->free_result($data_query);
		$pdc->put('plugin.newsletter.subscribers.u'.$user->data['user_id'],$data,86400,false,true);
	} else{
		$data = $subscriber_cache;
	};
	if (is_array($data)){
		foreach ($data as $elem){
			$subscr[$elem['letter_id']] = $elem;
		};
	};	
	
 
	//Load all newsletters
	//Cache: plugin.newsletter.letters
	$letter_cache = $pdc->get('plugin.newsletter.letters',false,true);
	if (!$letter_cache){
		$letter_query = $db->query("SELECT * FROM __newsletter_letters ORDER BY permission DESC");
		$letter_data = $db->fetch_record_set($letter_query);
		$pdc->put('plugin.newsletter.letters',$letter_data,86400,false,true);
		$db->free_result($letter_query);
	} else{
		$letter_data = $letter_cache;
	};

	$letter_count = 0;	
	if (is_array($letter_data)){
	
		foreach ($letter_data as $row){

			$status = $subscr[$row['id']]['status'];

			//Show only public newsletters AND newsletters he has subscribed
			if ($status != "" || $row['permission'] == 1 || $row['permission'] == 2) {
				$letter_count = $letter_count + 1;

				if (($status == "0") || ($status == "1")){
					$status_msg = $user->lang['nl_status_'.$status];
				} else {
					$status_msg = $user->lang['nl_permission_'.sanitize($row['permission'])];
				};
		
				//Bring the newsletters to template
				$tpl->assign_block_vars('index_list', array(
 						'NL_NAME'			=> sanitize($row['name']),
						'NL_ID' 			=> sanitize($row['id']),
						'NL_DESC'			=> sanitize($row['description']),
						'NL_PERMISSION'		=> $status_msg,
						'NL_CHECKED'		=> ($status != "") ? 'checked="checked"' : '',
						'ROW_CLASS'			=> $eqdkp->switch_row_class(),
					
				));
			}
		} //END foreach
	}; //END if is_array

	$letter_footcount = sprintf($user->lang['nl_letters_footcount'], $letter_count); 
	
	$s_no_letters = ($letter_count < 1 ) ? true : false;
    
	$tpl->assign_vars(array(
				
		//Language
		'L_SUBSCRIBE_HEADLINE'			=> $user->lang['nl_user_subscribe'],
		'L_NAME'						=> $user->lang['nl_name'],
		'L_DESCRIPTION'					=> $user->lang['nl_description'],
		'L_BUTTON_SAVE'					=> $user->lang['nl_button_save'],
		'L_BUTTON_RESET'				=> $user->lang['nl_button_reset'],
		'L_FOOTCOUNT'					=> sanitize($letter_footcount),
		'S_NO_LETTERS'					=> $s_no_letters,
		'L_NO_LETTERS'					=> $user->lang['nl_no_letters'],
	 
         //Common things
		'ABOUT_HEADER'             		=> $user->lang['nl_about_header'],	
		'NL_CREDITS'                	=> 'Credits',
		'NL_COPYRIGHT'              	=> $nlclass->Copyright(),
		'NL_JS_ABOUT'					=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], 'about.php', '500', '350'),
	));
    
    $eqdkp->set_vars(array(
	    'page_title'	 				=> $nlclass->GeneratePageTitle($user->lang['nl_user_subscribe']),
		'template_path' 				=> $pm->get_data('newsletter', 'template_path'),
		'template_file'         		=> 'newsletters.html',
		'display'               		=> true)
    );
?>