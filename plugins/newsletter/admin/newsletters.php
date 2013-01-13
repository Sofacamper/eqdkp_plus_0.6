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

// Include the Database Updater
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

//Letter-Query
//Load the newsletters
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
	foreach ($letter_data as $row){
		$letter_array[$row['id']] = $row;
	};
};

//User-Query
$sql = 'SELECT * FROM __users';
if (!($user_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
while($roww = $db->fetch_record($user_result)) {
    $userrow[$roww['user_id']] = $roww;
};

//=============================================
//MASSEDIT

if ($in->get('massedit') && is_numeric($in->get('lid'))){

	switch($in->get('action')){
	
		//Massedit: Confirm Subscription
		case "confirm":
								
			$sql = $db->query("SELECT id, username, user_email FROM __users ut LEFT JOIN (__newsletter_subscribers nt) ON nt.user_id=ut.user_id WHERE nt.letter_id='".$db->escape($in->get('lid'))."'");
			while ($row = $db->fetch_record($sql)){
				$subscr_data[$row['id']] = $row;
			};
				
				foreach ($in->getArray('subscriber', 'int') as $elem){
				
					$sql = $db->query("UPDATE __newsletter_subscribers SET status='1' WHERE id='".$db->escape($elem)."'");
					$options = array(
						'sender_mail'		=> $conf_plus['lib_email_sender_email'],
						'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
						'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
						'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
						'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
						'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
					);
					
					$mail = new MyMailer($options);
        			
					
					//Set E-Mail-Otions
					$mail->SetPath('../');

 					$bodyvars = array(
                		'USERNAME'    		=> sanitize($subscr_data[$elem]['username']),
						'LETTER_NAME'   	=> sanitize($letter_array[$in->get('lid')]['name']),
						'SIGNATURE'			=> sanitize($conf['signature']),
						'UNSUBSCRIBE_LINK' 	=> sanitize($mail->BuildLink()."plugins/newsletter/newsletters.php"),
          			);
					//Send Confirm-Email
          			$mail->SendMailFromAdmin($subscr_data[$elem]['user_email'], $user->lang['nl_subject_user_confirmed'], 'subscr_confirmed.html', $bodyvars, $conf_plus['lib_email_method']);
				
				};
				
			break;
			
		//Massedit: Deny Subscription		
		case "deny":
				
			$sql = $db->query("SELECT id, username, user_email FROM __users ut LEFT JOIN (__newsletter_subscribers nt) ON nt.user_id=ut.user_id WHERE nt.letter_id='".$db->escape($in->get('lid'))."'");
				
				while ($row = $db->fetch_record($sql)){
					$subscr_data[$row['id']] = $row;
				}
				
				foreach ($in->getArray('subscriber', 'int') as $elem){
				
					$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE id='".$db->escape($elem)."'");
					$options = array(
						'sender_mail'		=> $conf_plus['lib_email_sender_email'],
						'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
						'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
						'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
						'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
						'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
					);
					
					$mail = new MyMailer($options);
       				
	
					
					//Set E-Mail-Otions
					$mail->SetPath('../');

 					$bodyvars = array(
                		'USERNAME'    		=> sanitize($subscr_data[$elem]['username']),
						'LETTER_NAME'   	=> sanitize($letter_array[$in->get('lid')]['name']),
						'SIGNATURE'			=> sanitize($conf['signature']),
						'UNSUBSCRIBE_LINK' 	=> sanitize($mail->BuildLink()."plugins/newsletter/newsletters.php"),

          			);
					//Send Mail
          			$mail->SendMailFromAdmin($subscr_data[$elem]['user_email'], $user->lang['nl_subject_user_denied'], 'subscr_denied.html', $bodyvars, $conf_plus['lib_email_method']);
				
				};
				
			break;
			
		//Massedit: Delete User	
		case "delete":
			
				foreach ($in->getArray('subscriber', 'int') as $elem){
				
					$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE id='".$db->escape($elem)."'");	
				
				};
			break;
			
		//Massedit: Move User to another Newsletter
			case "move":
				if (is_numeric($in->get('target_lid'))) {
				
					//Get subscriber of this letter
					$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$in->get('lid'),false,true);
					if (!$subscriber_cache){
						$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id='".$db->escape($in->get('lid'))."'");
						$subscriber_data = $db->fetch_record_set($subscriber_query);
						$pdc->put('plugin.newsletter.subscribers.lid'.$in->get('lid'),$subscriber_data,86400,false,true);
						$db->free_result($subscriber_data);
					} else{
						$subscriber_data = $subscriber_cache;
					};

	
					if (is_array($subscriber_data)){
						foreach($subscriber_data as $row){
			
							$lsubscriber[$row['id']] = $row;
			
						};
					};
				
				
					//Get all subscriber from the target-letter
					$target_subscriber = array();
				
					$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$in->get('target_lid'),false,true);
					if (!$subscriber_cache){
						$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id='".$db->escape($in->get('target_lid'))."'");
						$subscriber_data = $db->fetch_record_set($subscriber_query);
						$pdc->put('plugin.newsletter.subscribers.lid'.$in->get('target_lid'),$subscriber_data,86400,false,true);
						$db->free_result($subscriber_data);
					} else{
						$subscriber_data = $subscriber_cache;
					};

	
					if (is_array($subscriber_data)){
						foreach($subscriber_data as $row){
			
							$target_subscriber[$row['user_id']] = $row['id'];
			
						};
					};
				
				
					//foreach selected user to move
					foreach ($in->getArray('subscriber', 'int') as $elem){
				
						//If user is not subscriber of the target-letter
						if (!isset($target_subscriber[$lsubscriber[$elem]['user_id']])){
							$sql = $db->query("UPDATE __newsletter_subscribers SET letter_id = '".$db->escape($in->get('target_lid'))."' WHERE id ='".$db->escape($elem)."'"); 
						} else {
							$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE id='".$db->escape($elem)."'");
						};
				
					};
				

				}; //END if is numeric
			
				break;
			
			//Massedit: Copy User to another newsletter	
			case "copy":
			
				//Get Subscriber-Information of the letter
				$sql = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id = '".$db->escape($in->get('lid'))."'");
				while($row = $db->fetch_record($sql)) {
    				$lsubscriber[$row['id']] = $row;
				}
				$db->free_result($sql);
				
				//Get all subscriber from the target-letter
				$target_subscriber = array();
				$sql = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id = '".$db->escape($in->get('target_lid'))."'");
				while($row = $db->fetch_record($sql)) {
    				$target_subscriber[$row['user_id']] = $row['id'];
				}
				$db->free_result($sql);
				
				//foreach selected user to move
				foreach ($in->getArray('subscriber', 'int') as $elem){
				
					//If user is not subscriber of the target-letter
					if (!isset($target_subscriber[$lsubscriber[$elem]['user_id']])){
						
						$sql = $db->query("INSERT INTO __newsletter_subscribers (user_id, letter_id, status, date) VALUES ('".$db->escape($lsubscriber[$elem]['user_id'])."', '".$db->escape($in->get('target_lid'))."', '1', NOW())");

					};
				
				};
			
				break;
				
	//Delete Cache
	$pdc->del_prefix('plugin.newsletter.subscriber');
	
	}	//END switch
	
} else {
	
	//Normal Mode, not Massedit
	switch($in->get('mode')){
			//Delete User
			case "delete": 
					if (is_numeric($in->get('id'))){
						$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE id='".$db->escape($in->get('id'))."'");
						$pdc->del_prefix('plugin.newsletter.subscribers');
					};
					
				break;
			
			//Delete Newsletter
			case "delete_l": 
					if (is_numeric($in->get('id'))){
						$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE letter_id='".$db->escape($in->get('id'))."'");
						$sql = $db->query("DELETE FROM __newsletter_letters WHERE id='".$db->escape($in->get('id'))."'");
						$pdc->del_prefix('plugin.newsletter.subscribers');
						$pdc->del_prefix('plugin.newsletter.letters');
					};
				break;
			
			//Confirm User
			case "user_add": 
					if (is_numeric($in->get('id'))){
						$sql = $db->query("UPDATE __newsletter_subscribers SET status='1' WHERE id='".$db->escape($in->get('id'))."'");
						$pdc->del_prefix('plugin.newsletter.subscribers');
						
						$sql = $db->query("SELECT id, username, user_email FROM __users ut LEFT JOIN (__newsletter_subscribers nt) ON nt.user_id=ut.user_id WHERE nt.id='".$db->escape($in->get('id'))."'");
						$subscr_data = $db->fetch_record($sql);
						$options = array(
							'sender_mail'		=> $conf_plus['lib_email_sender_email'],
							'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
							'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
							'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
							'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
							'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
						);
						
						$mail = new MyMailer($options);

					
						//Set E-Mail-Otions
						$mail->SetPath('../');

						$bodyvars = array(
							'USERNAME'    	=> sanitize($subscr_data['username']),
							'LETTER_NAME'   => sanitize($letter_data[$in->get('lid')]['name']),
							'SIGNATURE'		=> sanitize($conf['signature']),
							'UNSUBSCRIBE_LINK' => sanitize($mail->BuildLink()."plugins/newsletter/newsletters.php"),

						);

						$mail->SendMailFromAdmin($subscr_data['user_email'], $user->lang['nl_subject_user_denied'], 'subscr_denied.html', $bodyvars, $conf_plus['lib_email_method']);
					};
				break;
			
			//Deny User
			case "user_deny":
					if (is_numeric($in->get('id'))){
						
						
						$sql = $db->query("SELECT id, username, user_email FROM __users ut LEFT JOIN (__newsletter_subscribers nt) ON nt.user_id=ut.user_id WHERE nt.id='".$db->escape($in->get('id'))."'");
						$subscr_data = $db->fetch_record($sql);
						
						$sql = $db->query("DELETE FROM __newsletter_subscribers WHERE id='".$db->escape($in->get('id'))."'");
						$pdc->del_prefix('plugin.newsletter.subscribers');
						$options = array(
							'sender_mail'		=> $conf_plus['lib_email_sender_email'],
							'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
							'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
							'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
							'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
							'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
						);
						
						$mail = new MyMailer($options);

					
						//Set E-Mail-Otions
						$mail->SetPath('../');

						$bodyvars = array(
							'USERNAME'    		=> sanitize($subscr_data['username']),
							'LETTER_NAME'   	=> sanitize($letter_data[$in->get('lid')]['name']),
							'SIGNATURE'			=> sanitize($conf['signature']),
							'UNSUBSCRIBE_LINK' 	=> sanitize($mail->BuildLink()."plugins/newsletter/newsletters.php"),

						);

						$mail->SendMailFromAdmin($subscr_data['user_email'], $user->lang['nl_subject_user_confirmed'], 'subscr_confirmed.html', $bodyvars, $conf_plus['lib_email_method']);
					};
				break;
			
			//Add subscribers to a newsletter	
			case "add":
				
				foreach ($in->getArray('new_members', 'int') as $elem){
					if ($elem != 0){
						$sql = $db->query("INSERT INTO __newsletter_subscribers (user_id, letter_id, status, date) VALUES ('".$db->escape($elem)."', '".$db->escape($in->get('lid'))."', '1', NOW())");
					}
				
				};
				$pdc->del_prefix('plugin.newsletter.subscribers');
				break;
	} //END Switch	
	
	
};


//===================================================
//Output-Section

//Letter-Query
//Cache: plugin.newsletter.letters
$letter_cache = $pdc->get('plugin.newsletter.letters',false,true);
if (!$letter_cache){
	$letter_query = $db->query("SELECT * FROM __newsletter_letters ORDER BY permission DESC");
	$letter_data = $db->fetch_record_set($letter_query);
	$pdc->put('plugin.newsletter.letters',$letter_data,86400,false,true);
	$db->free_result($letter_query);
} else {
	$letter_data = $letter_cache;
};
if (is_array($letter_data)){	
	foreach ($letter_data as $row){
		$letter_array[$row['id']] = $row;
	};
};

if (count($letter_array) < 1){
	$tpl->assign_vars(array(
		"S_NO_LETTERS"	=> true,
		"L_NO_LETTERS" 	=> $user->lang['nl_no_letters'],
	));
};
if (is_array($letter_array)) {
foreach ($letter_array as $row) {
	
	$all_user = $userrow;
		
	//Build Add-User-Dropdown
	 $add_user_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$row['id'],false,true);
	if (!$add_user_cache){
		$add_user_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id='".$db->escape($row['id'])."'");
		$add_user = $db->fetch_record_set($add_user_query);
		$pdc->put('plugin.newsletter.subscribers.lid'.$row['id'],$add_user,86400,false,true);
		$db->free_result($add_user_query);
	} else{
		$add_user = $add_user_cache;
	};

	
	if (is_array($add_user)){
		foreach($add_user as $elem){
			
			unset($all_user[$elem['user_id']]);
			
		}
	}
	

	$add_user_array = array();
		foreach ($all_user as $elem){
			$add_user_array[$elem['user_id']] =  $elem['username'];
		}
	
	//Build Action-Dropdown

	if ($row['permission'] == 1){
			
		$action_dropdown = array(
			'confirm'     => $user->lang['nl_confirm'],
			'deny'  	  => $user->lang['nl_deny'],
			'delete'  	  => $user->lang['nl_delete'],
			'move'  	  => $user->lang['nl_move'],
			'copy'  	  => $user->lang['nl_copy'],
		);

	} else {
		
		$action_dropdown = array(

			'delete'  	  => $user->lang['nl_delete'],
			'move'  	  => $user->lang['nl_move'],
			'copy'  	  => $user->lang['nl_copy'],
		);
		
	}
		
		
		
	//Build Target_letter-array
	$target_letter_array = array();
	foreach ($letter_array as $elem){	
		$target_letter_array[$elem['id']] =  $elem['name'];
	}
	unset($target_letter_array[$row['id']]);
		
	$tpl->assign_block_vars('index_list', array(
		          'ROW_CLASS' 				=> $eqdkp->switch_row_class(),
		          'ID'              		=> sanitize($row['id']),
				  'NAME'            		=> sanitize($row['name']),
				  'DESC'					=> sanitize($row['description']),
				  'S_UNCONFIRMED'   		=> ($row['permission'] == 1) ? true : false,
				  'ADD_USER_DROPDOWN'		=> $jquery->MultiSelect('new_members', $add_user_array, '', 200, 200, true, 'new_members_'.$row['id']),
				  'ACTION_DROPDOWN'      	=> $khrml->DropDown('action', $action_dropdown, 'confirm', '', 'onChange="nl_check_action_dropdown('.$row['id'].')"', 'input', 'action_drpdwn_'.$row['id']),
				  'TARGET_LETTER_DROPDOWN'  => $khrml->DropDown('target_lid', $target_letter_array, '', '', '', 'input', 'target_drpdwn_'.$row['id']),	
	));
		  
		  	
	$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$row['id'].".stat1",false,true);
	if (!$subscriber_cache){
		$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id='".$db->escape($row['id'])."' AND status='1'");
		$subscriber_data = $db->fetch_record_set($subscriber_query);
		$pdc->put('plugin.newsletter.subscribers.lid'.$row['id'].".stat1",$subscriber_data,86400,false,true);
		$db->free_result($subscriber_data);
	} else {
		$subscriber_data = $subscriber_cache;
	};

	
	if (is_array($subscriber_data)){
		foreach($subscriber_data as $elem){
			
			$tpl->assign_block_vars('index_list.user_list', array(
		          'ROW_CLASS' 		=> $eqdkp->switch_row_class(),
		          'ID'              => sanitize($elem['id']),
				  'DATE'			=> sanitize($elem['date']),
				  'NAME'            => sanitize($userrow[$elem['user_id']]['username']),
				  'MAIL'			=> sanitize($userrow[$elem['user_id']]['user_email']),
				));
			
		};
	};
		  
		  
			
	if ($row['permission'] == 1){

		$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.lid'.$row['id'].".stat0",false,true);
		if (!$subscriber_cache){
			$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE letter_id='".$db->escape($row['id'])."' AND status='0'");
			$subscriber_data = $db->fetch_record_set($subscriber_query);
			$pdc->put('plugin.newsletter.subscribers.lid'.$row['id'].".stat0",$subscriber_data,86400,false,true);
			$db->free_result($subscriber_data);
		} else{
			$subscriber_data = $subscriber_cache;
		};
	
		if (is_array($subscriber_data)){
			foreach($subscriber_data as $row){

				$tpl->assign_block_vars('index_list.unconfirmed_list', array(
					'ROW_CLASS' 		=> $eqdkp->switch_row_class(),
					'ID'              	=> sanitize($row['id']),
					'DATE'				=> sanitize($row['date']),
					'NAME'            	=> sanitize($userrow[$row['user_id']]['username']),
					'MAIL'				=> sanitize($userrow[$row['user_id']]['user_email']),
				));
			
			};
		};	
			
	};
			
}; //END foreach
};

$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_addletter'],
                  'link'    => 'javascript:newLetter()',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
			  1 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID,
                  'img'     => 'nl_logo.png',
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

$tpl->assign_vars(array(

              
              'JS_ADD_NL' 				=> $jquery->Dialog_URL('nlAddLetter', $user->lang['nl_addletter'], "newsletters_add.php".$SID, '650', '320', 'newsletters.php'),
              'JS_EDIT_NL' 				=> $jquery->Dialog_URL('nlEditLetter', $user->lang['nl_editletter'], "newsletters_edit.php".$SID."&id='+id+'", '650', '320', 'newsletters.php'),
              
			  'L_TITLE_EDITLETTER'      => $user->lang['nl_editletter'],
			  'L_TITLE_DELETELETTER'   	=> $user->lang['nl_deleteletter'],
			  
			  'L_DELETE'        		=> $user->lang['nl_button_delete'],
              'L_TEMPLATENAME'  		=> $user->lang['nl_templatename'],
              'L_ADDTEMPLATE'   		=> $user->lang['nl_addtemplate'],
              'L_EDITTEMPLATE'  		=> $user->lang['nl_edittemplate'],
			  'L_MANAGE_NEWSLETTERS'  	=> $user->lang['nl_manage'],
			  'L_USERNAME'  			=> $user->lang['nl_username'],
			  'L_USEREMAIL'  			=> $user->lang['nl_useremail'],
			  'L_DATE'  				=> $user->lang['nl_date_subscription'],
			  'L_ACTION'  				=> $user->lang['nl_action'],
			  'L_UNCONFIRMED_USERS'  	=> $user->lang['nl_unconfirmed_users'],
			  'L_GO'  					=> $user->lang['nl_go'],
			  'L_ALL_MARKED'  			=> $user->lang['nl_all_marked'],
			  'L_ADD_SUBSCRIBER'		=> $user->lang['nl_add_subscriber'],
			  'L_TO'  					=> $user->lang['nl_to'],
			  
			  'ACTION_DROPDOWN'      	=> $html->DropDown('action', $action_dropdown, 'confirm'),
			  
			  'ADMIN_MENU'		=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),		  
			 //Common things
			'ABOUT_HEADER'             	=> $user->lang['dl_about_header'],	
			'NL_CREDITS'                => 'Credits',
			'NL_COPYRIGHT'              => $nlclass->Copyright(),
			'NL_JS_ABOUT'				=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
	
			'UPDATE_BOX'              	=> $gupdater->OutputHTML(),
			'UPDCHECK_BOX'		  		=> $rbvcheck->OutputHTML(),

			  
		));
    
    $eqdkp->set_vars(array(
	    	'page_title'				=> $nlclass->GeneratePageTitle($user->lang['nl_send']),
			'template_path' 	      	=> $pm->get_data('newsletter', 'template_path'),
			'template_file'          	=> 'admin/newsletters.html',
			'display'                	=> true)
    );

?>