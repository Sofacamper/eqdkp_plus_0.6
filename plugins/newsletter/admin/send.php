<?PHP
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 1.3.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'newsletter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'newsletter')) { message_die('The Newsletter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_newsletter_');

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

/// Include the Database Updater
$gupdater = new PluginUpdater('newsletter', 'nl_', 'newsletter_config', 'include');

// The Version Information
$versionthing   = array(
      'name' 	=> 'newsletter',
      'version' => $pm->get_data('newsletter', 'version'),
      'build' 	=> $pm->plugins['newsletter']->build,
      'vstatus' => $pm->plugins['newsletter']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing);
$rbvcheck->PerformUpdateCheck();
	
	//Ajax-Mode: xml
	if (($in->get('mode') == "ajax") && ($in->get('do') == "send")){
			
		$status = $nlclass-> send_from_queue();
		
		header("Content-type: text/xml");
		header("Cache-Control: no-cache"); 
		echo $status;
		die();
		
	}
	
	if ($in->get('mode') == "continue"){
		
		$total_query = "SELECT count(*) FROM __newsletter_queue";
		$mails_in_queue = $db->query_first($total_query);
		
		$tpl->assign_vars(array(
				'IS_SEND'		=> true,
				'IS_SEND_MAIL' 	=> true,
				'S_ERROR' 		=> true,
				'NOTICE' 		=> $user->lang['nl_txt_sent_continued'],
				'NOTICE_IMG' 	=> "<img src=".$eqdkp_root_path."/images/ok.png>",
				'MAILS_IN_QUEUE' => $mails_in_queue,
			));
	}
	

//Send-Mode
if ($in->get('mode') == "send" && $in->get('submit') != ""){
				
	$notice_img[0] = "<img src=".$eqdkp_root_path."/images/ok.png>"; 
	$notice_img[1] = "<img src=".$eqdkp_root_path."/images/false.png>";  
		
	//If Body is not empty
	if ($in->get('body') != ""){
	
		$mailcl = new MyMailer('abc');
		

		//Replace a few placeholders
		$body = sanitize($in->get('body'));
		$body = str_replace('*DKP_NAME*', sanitize($eqdkp->config['dkp_name']), $body);
		$body = str_replace('*EQDKP_LINK*', sanitize($mailcl->BuildLink()), $body);
		$body = str_replace('*DATE*', date("Y-m-d"), $body);
		$body = preg_replace('/\[item\](.*?)\[\/item\]/msi', '',$body);
		$body = preg_replace('/\[itemicon\](.*?)\[\/itemicon\]/msi', '',$body);
			
		if ($in->get('signature') == 1){
				
				$body .= "\n".sanitize($conf['signature']);
		}
		
		//Make a smile ;-)
		$bbclass = new BBcode();
		$bbclass->ChangeLightBoxStatus(true); 
		$bbclass->SetSmiliePath($mailcl->BuildLink()."libraries/jquery/images/editor/icons");
		
		$html_body = $bbclass->MyEmoticons($body); //Emoticons
		$html_body = $nlclass->toHTML($html_body); //Remove brs in a table
		$html_body = $nl_bbcode->parse($html_body); //Make Newsletter-Codes: tables, classes
		$html_body = $bbclass->toHTML($html_body); //Make normal BB-Codes into HTML
		if ($conf['style_bg_color'] != "" || $conf['style_font_color'] != ""){
			$bg_color = ($conf['style_bg_color'] != "") ? $conf['style_bg_color'] : "";
			$font_color = ($conf['style_font_color'] != "") ? $conf['style_font_color'] : "";
			
			$html_body = "<style> body { background-color:".sanitize($bg_color)."; color:".sanitize($font_color).";} </style>\n<div style=\"background-color:".sanitize($bg_color)."; color:".sanitize($font_color).";\">".$html_body."</div>";
			
			
			

		};
		if ($conf['style_css'] != ""){
			$html_body = "<style> ".sanitize($conf['style_css'])." </style>\n".$html_body;
		}
		
		//Make a version without any HTML
		$no_html_body = $nlclass->stripBBcode($body);

		//Subject: without any BB-Code
		$subject = $nlclass->stripBBcode($in->get('subject'));

		//Sendto: letter-subscribers
		if ($in->get('sendto_letters')){
			
			  //Load the subscribed newsletters
  			//Cache: plugin.newsletter.subscribers.{USER_ID}
  			$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$in->get('sendto_letters'),false,true);
			if (!$subscriber_cache){
				$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id = '".$db->escape($in->get('sendto_letters'))."'");
				$subscriber = $db->fetch_record_set($subscriber_query);
				$db->free_result($subscriber_query);
				$pdc->put('plugin.newsletter.subscribers.lid'.$in->get('sendto_letters'),$subscriber,86400,false,true);
			} else{
				$subscriber = $subscriber_cache;
			};
			if (is_array($subscriber)){
			
				foreach ($subscriber as $row){
					$empf_array[$row['user_id']] = $row['user_id'];
				}
			}
						
		};

		//Sendto: Members
		if ($in->getArray('sendto_members', 'int')){
		
			foreach ($in->getArray('sendto_members', 'int') as $elem){
				
					if ($elem != 0){
				
						if (!in_array($elem,$empf_array)){
							$empf_array[$elem] = $elem;
						}
				
					};	
				
			};
		
		};
	
	
		//Sendto: Class-Members
		if ($in->getArray('sendto_class', 'string') != ""){

			foreach ($in->getArray('sendto_class', 'string') as $elem){
				
				$sql = 'SELECT user_id
        				FROM __classes cl, __members members
        				LEFT JOIN (__member_user mu)       
        				ON mu.member_id=members.member_id
        				WHERE cl.class_id=members.member_class_id
						AND cl.class_name=\''.$db->escape($elem).'\'';

				$sql .= ($in->get('hide_inactive')) ? " AND members.member_status='1'" : ''	;					
				$class_query = $db->query($sql);
		
					while ($row = $db->fetch_record($class_query)){
		
						if (!in_array($row['user_id'],$empf_array)){
							$empf_array[$row['user_id']] = $row['user_id'];
						};
			
					};
								
			};
		
		};
	
	
		//If somebody wants a mail: send it
		if (is_array($empf_array)){

			$tpl->assign_vars(array(
				'IS_SEND'	=> true,
				'IS_SEND_MAIL' => ($in->get('type') == "sms") ? false : true,
			));
		
		
			//Get personal things of the users: $userdata_array['user_id'] = array()
			$sql = 'SELECT user_id, user_email, username, cellphone FROM __users';
			$result = $db->query($sql);
				while($row = $db->fetch_record($result)){
					$userdata_array[$row['user_id']] = $row;
				};
		
			//Load Usersettings
			//Cache: plugin.newsletter.usersettings
			$usersetting_cache = $pdc->get('plugin.newsletter.usersettings',false,true);
			if (!$usersetting_cache){
				$data_query = $db->query("SELECT * FROM __newsletter_userconfig");
				$data = $db->fetch_record_set($data_query);
				$pdc->put('plugin.newsletter.usersettings',$data,86400,false,true);
			} else{
				$data = $usersetting_cache;
			};
			if (is_array($data)){
				foreach ($data as $row){
					$usersetting_array[$row['user_id']][$row['config_name']] = $row['config_value'];
				};
			};	
		
			$sms_sendtostring = "";
				
				//Foreach recipiend: send mail or sms
				foreach ($empf_array as $elem){
					//Send SMS: just create the recipient-array, sending comes later
					if ($in->get('type') == "sms") {

						//Define because it doesnot exist jet
						$conf_plus['pk_sms_disable'] = 0;
				
						if ($userdata_array[$elem]['cellphone'] != "" && $conf_plus['pk_sms_disable'] <> 1) {

							$sms_sendtostring = trim($sms_sendtostring);
							$sms_sendtostring .= " ".$userdata_array[$elem]['cellphone'].";".$userdata_array[$elem]['username'];
							$sms_sendtostring = trim($sms_sendtostring);
				
						};		
				
					//Send E-Mail
					} else {			
							
						$mailcl = new MyMailer();
						
						$mail_type = ($usersetting_array[$elem]['mailtype']) ? $usersetting_array[$elem]['mailtype'] : 'html';
						
						
						//Select the E-Mail-Format
						if ($in->get("format") == "plain"){
							$body = $no_html_body;
						} else {
			
							if ($mail_type == "html") {
								$body = $html_body;
							}else {
								$body = $no_html_body;
							};
						};
			
						//Replace Username und Author
						$body =  str_replace('*USERNAME*', sanitize($userdata_array[$elem]['username']), $body);
						$body =  str_replace('*AUTHOR*', sanitize($userdata_array[$user->data['user_id']]['username']), $body);
						if ($nlclass->check_email($userdata_array[$elem]['user_email'])){

							//Save it into the queue - it will be send with AJAX later...
							$nlclass->insert_into_queue($in->get('subject'), $body, $bodyvars, '', $userdata_array[$elem]['user_email'], '', 'input',  $conf_plus['lib_email_method'], $mail_type);

						};
						
			
					}; //END Send E-Mail or SMS
					
					//Write the Recipients to the template
					$tpl->assign_block_vars('recipient_row', array(
						"NAME" => sanitize($userdata_array[$elem]['username']),
						"ROW_CLASS"	=> $eqdkp->switch_row_class(),
					));
			
				}; //END foreach
				
				
			
				//Really send the SMS
				if ($in->get('type') == "sms") {

					if ($sms_sendtostring){
				
						$user->check_auth('a');
			
						// TEMPORARY CODE - REMOVE IN 0.7!
						include_once($eqdkp_root_path . 'libraries/sms/sms.class.php');
						// END OF TEMPORARY CODE
			
						$sms = new sms();
						$sms->username = $conf_plus['pk_sms_username'];
						$sms->passwort = $conf_plus['pk_sms_password'];
			
						$sendto_string = trim(sanitize($sms_sendtostring));
						$message = ($no_html_body)? $no_html_body : '';
						$message =  str_replace('*AUTHOR*', $userdata_array[$user->data['user_id']]['username'], $message);

						if(($sendto_string <> '') && $message <> '' ) 
						{
							$sendto_string = implode("\n", $sendto_string);    			  
							$return = $sms->sendSMS($sendto_string,$message,$user->data['username']);    			
						} 	 
    		
						switch($return['status'])
						{
							case '-1' : $notice = $return['msg']; $rs = 0; break ;	
							case '-2' : $notice = $user->lang['sms_error_fopen']." ".$return['msg']; $rs = 1; break;	
							case '100' : $notice = $user->lang['sms_success'];	$rs = 0;  break;
							case '150' : $notice = $user->lang['sms_error_badpw']; $rs = 1;	break; 
							case '159' : $notice = $user->lang['sms_error_159']; $rs = 1;	break;
							case '160' : $notice = $user->lang['sms_error_160']; $rs = 1;	break;
							case '200' : $notice = $user->lang['sms_error_200']; $rs = 1;	break;
							case '254' : $notice = $user->lang['sms_error_254']; $rs = 1; break;
							default: $notice = $user->lang['sms_error']; $rs = 1; break;
						}

						$tpl->assign_vars(array(
							'NOTICE' 		=> $notice,
							'NOTICE_IMG' 	=> $notice_img[$rs],
							'S_ERROR'		=> ($rs = 1) ? true: false,
						));			
		
					} else {
						$tpl->assign_vars(array(
							'NOTICE' 		=> $user->lang['nl_no_recipients_found'],
							'NOTICE_IMG' 	=> $notice_img[1],
							'S_ERROR'		=> true,
						));
					};
			
				} else {
					
					$total_query = "SELECT count(*) FROM __newsletter_queue";
					$mails_in_queue = $db->query_first($total_query);
					
					$tpl->assign_vars(array(
						'NOTICE' 		=> $user->lang['nl_txt_sent'],
						'NOTICE_IMG' 	=> $notice_img[0],
						'MAILS_IN_QUEUE' => $mails_in_queue,
						
					));	
				
				};
				
				//Save the Mail/SMS to the archive
				if ($in->get('archive') == 1){
					$letter = $in->get('sendto_letters').";";
					$classes = implode('|', $in->getArray('sendto_class', 'string')).";";
					$members = implode('|', $in->getArray('sendto_members', 'int'));
					
					$body = nl2br($in->get('body'));
					$body = str_replace("\n", "", $body);
					$body = str_replace("\r", "", $body);
					
					$query = $db->build_query('INSERT', array(
  					'letter_id'			=> $db->escape($in->get('sendto_letters', '')),
  					'body'				=> $db->escape($body),
  					'subject'        	=> $db->escape($in->get('subject', '')),
					'recipients'		=> $db->escape($letter.$classes.$members),
					'date'				=> $db->escape(date("Y-m-d H:i:s")),
					));
					$sql = 'INSERT INTO __newsletter_archive' . $query;
					$db->query($sql);
					
					$pdc->del_prefix('plugin.newsletter.archive');
				
				};
				
				
				
			}; //END if recipients_array is an array
	
		}; //END if body is not empty

	} //END send-mode


//===================================================
//OUTPUT-SECTION

//Load data of a letter when it was selected
if (is_numeric($in->get('lid'))){
	
  	//Load the newsletter
  	//Cache: plugin.newsletter.letters.{LETTER_ID}
  	$letter_cache = $pdc->get('plugin.newsletter.letters.'.$in->get('lid'),false,true);
	if (!$letter_cache){
		$letter_query = $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($in->get('lid'))."'");
		$letter_data = $db->fetch_record($letter_query);
		$pdc->put('plugin.newsletter.letters.'.$in->get('lid'),$letter_data,86400,false,true);
		$db->free_result($letter_query);
	} else{
		$letter_data = $letter_cache;
	};
	
	if ($letter_data['preset_template'] > 0){
		
	//Load the template
  	$template_cache = $pdc->get('plugin.newsletter.templates.'.$letter_data['preset_template'],false,true);
	if (!$template_cache){
		$template_query = $db->query("SELECT * FROM __newsletter_templates WHERE id='".$db->escape($letter_data['preset_template'])."'");
		$template_data = $db->fetch_record($template_query);
		$db->free_result($template_query);
		$pdc->put('plugin.newsletter.templates.'.$letter_data['preset_template'],$template_data,86400,false,true);
	} else{
		$template_data = $template_cache;
	};
		
	}	
	

	
	$tpl->assign_vars(array(
		"BODY" => sanitize($template_data['body']),
		"SUBJECT" => sanitize($template_data['subject']),
	));
	
	$letter_data_type = ($letter_data['preset_type'] == "sms") ? 'sms' : "email";
	$letter_data_archive = ($letter_data['preset_archive'] == 1) ? 'checked="checked"' : '';
	$letter_data_signature = ($template_data['signature'] == 1) ? 'checked="checked"' : '';
	
} else {
	//Load data of Raidplan
	if (is_numeric($in->get('raidplan_id')) && $pm->check(PLUGIN_INSTALLED, 'raidplan')) {
		
		//Load the template
		$template_cache = $pdc->get('plugin.newsletter.templates.1',false,true);
		if (!$template_cache){
			$template_query = $db->query("SELECT * FROM __newsletter_templates WHERE id='1'");
			$template_data = $db->fetch_record($template_query);
			$db->free_result($template_query);
			$pdc->put('plugin.newsletter.templates.1',$template_data,86400,false,true);
		} else{
			$template_data = $template_cache;
		};
		
		// Load the Data of that raid
  		$rsql =  "SELECT raid_id, raid_name, raid_date
				    FROM __raidplan_raids
				    WHERE raid_id='" . $db->escape($in->get('raidplan_id')) . "'";
		$rresult = $db->query($rsql);
    	$rrow = $db->fetch_record($rresult);
  
  		// replace *RP_VALUES* by URL
  		$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
  		$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
  		$server_name = trim($eqdkp->config['server_name']);
  		$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
  		$nl_server_url  = 'http://' . $server_name . $server_port . $script_name;
	
  		$template_data['body'] = str_replace('*RPLINK*', $nl_server_url.'plugins/raidplan/viewraid.php?s=&r='.$rrow['raid_id'], $template_data['body']);
  		$template_data['body'] = str_replace('*RPNAME*', $rrow['raid_name'], $template_data['body']);
  		$template_data['body'] = str_replace('*RPDATE*', date($user->lang['ucc_timeformat2'], $rrow['raid_date']), $template_data['body']);
	
		$tpl->assign_vars(array(
			"BODY" 			=> sanitize($template_data['body']),
			"SUBJECT" 		=> sanitize($template_data['subject']),
			"IS_RAIDPLAN"	=> true,
		));
	
		$letter_data_type = "email";
		$letter_data_archive = ' ';
		$letter_data_signature = 'checked="checked"';
		
		
		 // get the members to send the mail to:
    	$asql = "SELECT members.member_id, members.member_name, attendees.attendees_subscribed, attendees.raid_id, member_user.user_id, users.username, users.user_email
			FROM (__members as members, __member_user as member_user, __classes as classes, __users as users, __member_ranks as ranks)
			LEFT JOIN __raidplan_raid_attendees AS attendees ON members.member_id=attendees.member_id AND attendees.raid_id=".$db->escape($in->get('raidplan_id'))."
			
			WHERE members.member_id=member_user.member_id
			AND classes.class_id=members.member_class_id
			AND member_user.user_id=users.user_id
			AND members.member_rank_id = ranks.rank_id
			ORDER BY member_user.user_id";
    	$aresult = $db->query($asql);
   	 	$members = array();
    	$is_in = array();
    	$is_notin = array();
		
    	
		while ($arow = $db->fetch_record($aresult)) {
		  $members[$arow['username']][$arow['member_name']] = $arow['attendees_subscribed'];
		  $memberss[$arow['username']] = $arow['user_id'];
		}; //close while

		foreach ($members as $key=>$value) {
			foreach ($value as $key2=>$value2) {
				if($value2 != NULL){
					$is_in[$key] = true;
				};
			}; //close foreach
		}; //close foreach

		foreach ($memberss as $key=>$value) {
			if(!in_array($key, array_keys($is_in))){
				$is_notin[$key] = $value;
			};
		}; //close foreach

		foreach ($is_notin as $username => $useremail){
			
			$recipients[sanitize($useremail)] = sanitize($useremail);
  		}; //close foreach
		$members_dropdown_selected = $recipients;
		
	} else {
		
		//Resend an mail from the archiv
		if (is_numeric($in->get('resend'))){
			
			$mail_cache = $pdc->get('plugin.newsletter.archive.id'.$in->get('resend'),false,true);
			if (!$mail_cache){
				$mail_query =  $db->query("SELECT * FROM __newsletter_archive WHERE id = '".$db->escape($in->get('resend'))."'");
				$letter_data = $db->fetch_record($mails_query);
				$db->free_result($mail_query);
				$pdc->put('plugin.newsletter.archive.id'.$in->get('resend'),$letter_data,86400,false,true);
			} else{
				$letter_data = $mail_cache;
			};
			
			$body = (string)$letter_data['body'];
			$body = str_replace("<br />", "\n", $body);
	
			$tpl->assign_vars(array(
				"BODY" => sanitize($body),
				"SUBJECT" => sanitize($letter_data['subject']),
			));
			
			$recipients = explode(";", $letter_data['recipients']);
			
			$letter_dropdown_selected = $recipients[0];
			$class_dropdown_selected = $recipients[1];
			$members_dropdown_selected = $recipients[2];
	
		
		}
	
	}

};


//Create User-Dropdown
$sql = 'SELECT * FROM __users';
if (!($user_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
while($roww = $db->fetch_record($user_result)) {
    $userrow[$roww['user_id']] = $roww;
	$members_array[$roww['user_id']] = $roww['username'];
};
$member_dropdown = $jquery->MultiSelect('sendto_members', $members_array, $members_dropdown_selected, 200, 200, true);


//Create Newsletter-Dropdown
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
	
$letter_array['0'] = "";
if (is_array($letter_data)){
		
	foreach($letter_data as $row){
		$letter_array[$row['id']] = $row['name'];
			
	}
		
}
	

if ($in->get('lid')){
	$letter_dropdown_selected  = $in->get('lid');
};

$letter_dropdown = $khrml->DropDown('sendto_letters', $letter_array, $letter_dropdown_selected, '', 'style="width:200px; height:22px" onChange="top.location.href=\'send.php'.$SID.'&lid=\'+this.value"', 'ui-dropdownchecklist-wrapper');

//Create Class-Dropdown
$sql = "SELECT class_name, class_id FROM __classes GROUP BY class_name ORDER BY class_name";
$result = $db->query($sql);
$class_array = array ();
			
	while ($row = $db->fetch_record($result)){
		if($row['class_name'] != 'Unknown'){
			$class_array[$row['class_name']]  = $row['class_name'];
		};
	};
$class_dropdown = $jquery->MultiSelect('sendto_class', $class_array, $class_dropdown_selected, 200, 200, true);

//Create Template-Dropdown
 $template_cache = $pdc->get('plugin.newsletter.templates',false,true);
if (!$template_cache){
	$template_query = $db->query("SELECT * FROM __newsletter_templates");
	$template = $db->fetch_record_set($template_query);
	$db->free_result($template_query);
	$pdc->put('plugin.newsletter.templates',$template,86400,false,true);
} else{
	$template = $template_cache;
};
	
$template_array[0] = "";
	
if (is_array($template)){
		
	foreach($template as $row){
		$template_array[$row['id']] = $row['name'];
			
	}
		
}
	

$template_dropdown = $khrml->DropDown('template', $template_array, $template_data['id'], '', 'onChange="load_template(this.value)"');


if ($letter_data_type){
	$type_sms  = ($letter_data_type == "sms")   ? 'checked="checked"' : '';
	$type_mail = ($letter_data_type == "email") ? 'checked="checked"' : '';
};
	$admin_optionsarray = array(
			   0 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID,
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
				  
              1 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              2 => array(
                  'name'    => $user->lang['nl_ad_archive'],
                  'link'    => 'archive.php'.$SID,
                  'img'     => 'archive.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              3 => array(
                  'name'    => $user->lang['nl_templates'],
                  'link'    => 'templates.php'.$SID,
                  'img'     => 'styles.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              4 => array(
                  'name'    => $user->lang['nl_settings'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              
             
      );
	
//Bring everything to template
$tpl->assign_vars(array(

    'MEMBER_DROPDOWN'		=> $member_dropdown,
	'LETTER_DROPDOWN'		=> $letter_dropdown,
	'CLASS_DROPDOWN'		=> $class_dropdown,
	'TEMPLATE_DROPDOWN'		=> $template_dropdown,
			  
	'WYSIWYG_EDITOR' 		=> $jquery->wysiwyg('body'),
	'ARCHIVE'				=> ($letter_data_archive) ? $letter_data_archive : 'checked="checked"',
	'SIGNATURE'				=> ($letter_data_signature) ? $letter_data_signature : 'checked="checked"',
	'TYPE_SMS'				=> $type_sms,
	'TYPE_MAIL'				=> ($type_mail) ? $type_mail : 'checked="checked"',
	'S_SMS_DISABLED'		=> ($conf_plus['pk_sms_disable'] == "1") ? true : false,
			  
	'L_NAME'          		=> $user->lang['nl_templatename'],
	'L_SUBJECT'       		=> $user->lang['nl_subject'],
	'L_BODY'          		=> $user->lang['nl_mail_body'],
	'B_SEND'          		=> $user->lang['nl_button_send_letter'],
	'B_RESET'          		=> $user->lang['nl_button_reset'],
	'B_PREVIEW'          	=> $user->lang['nl_preview'],
	'L_LEGENDE'				=> $user->lang['nl_legende'],
	'L_RECEPIENTS'			=> $user->lang['nl_recipients'],
	
	'L_BRIDGE_LONG'			=> $user->lang['nl_bridge_long'],
	'L_SIGNATURE'			=> $user->lang['nl_show_signature'],
	'L_SEND_HEADLINE'		=> $user->lang['nl_send_letter'],
	'L_NEWSLETTER'			=> $user->lang['nl_letter'],
	'L_CLASS'				=> $user->lang['nl_class'],
	'L_MEMBERS'				=> $user->lang['nl_members'],
	'L_ACTIVE_ONLY'			=> $user->lang['nl_hideinactive'],
	'L_RECIPIENT'			=> $user->lang['nl_recipients'],
	'L_TEMPLATE'			=> $user->lang['nl_template'],
	'L_FORMAT'				=> $user->lang['nl_format'],
	'L_TYPE'				=> $user->lang['nl_type'],
	'L_HTML'				=> $user->lang['nl_mail_html'],
	'L_PLAIN'				=> $user->lang['nl_mail_plain'],
	'L_MAIL'				=> $user->lang['nl_mail'],
	'L_SMS'					=> $user->lang['nl_sms'],
	'L_SIGNATURE'			=> $user->lang['nl_show_signature'],
	'L_ARCHIVE'				=> $user->lang['nl_save_archive'],
	'L_HEADER_EMAIL_SENT'	=> $user->lang['nl_header_sent'],
	
	'L_LETTER_IS_SENDING'	=> $user->lang['nl_newsletter_is_sending'],
	'L_DONT_CLOSE'			=> $user->lang['nl_dont_close_window'],
	'L_ERROR'				=> $user->lang['nl_error'],
	'L_ERROR_WHILE_SENDING'	=> $user->lang['nl_error_while_sending'],
	
	//BB-Codes/Placeholder
	        'L_SHOW_BBCODES'		=> $user->lang['nl_bbcode_window'],
			'L_EXPL_LEGENDE'		=> $user->lang['nl_legende_expl'],
			'L_DKPNAME'				=> $user->lang['nl_dkpname'],
			'L_DKPLOGO'				=> $user->lang['nl_dkplogo'],
			'L_DATE'				=> $user->lang['nl_date'],
			'L_USERNAME'			=> $user->lang['nl_username'],
			'L_DKPLINK'				=> $user->lang['nl_dkplink'],
			'L_AUTHOR'				=> $user->lang['nl_author'],
			'L_PLACEHOLDER'			=> $user->lang['nl_placeholder'],
			'L_FUNCTION'			=> $user->lang['nl_function'],
			'L_BBCODE'				=> $user->lang['nl_bbcode'],
			'L_BBCODES_EXPLAIN'		=> $user->lang['nl_bbcodes_explain'],
	
	'JS_FIELDS_EMPTY'		=> $jquery->Dialog_Alert('fields_empty', $user->lang['nl_fields_empty'], $user->lang['nl_fields_empty_title'],'300', '36'),
	'NL_JS_PREVIEW'			=> $jquery->Dialog_URL('preview', $user->lang['nl_preview'].": '+subject+'", 'preview.php', '900', '600'),
	'NL_JS_BRIDGE'			=> $jquery->Dialog_URL('bridge', $user->lang['nl_bridge'], 'bridge.php', '900', '600'),

	'ADMIN_MENU'			=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),	
	  			  
	//Common things
	'ABOUT_HEADER'			=> $user->lang['dl_about_header'],	
	'NL_CREDITS'			=> 'Credits',
	'NL_COPYRIGHT'			=> $nlclass->Copyright(),
	'NL_JS_ABOUT'			=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
	'UPDATE_BOX'			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  	=> $rbvcheck->OutputHTML(),

	));
    
$eqdkp->set_vars(array(
	'page_title'             => $nlclass->GeneratePageTitle($user->lang['nl_send']),
	'template_path' 	     => $pm->get_data('newsletter', 'template_path'),
	'template_file'          => 'admin/send.html',
	'display'                => true,
	));

?>