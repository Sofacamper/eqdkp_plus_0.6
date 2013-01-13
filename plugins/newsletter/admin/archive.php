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

if ($in->get('delete') == "all"){
	
	$sql = $db->query("DELETE FROM __newsletter_archive");
	$pdc->del_prefix('plugin.newsletter.archive');
	
} else {

	if (is_numeric($in->get('delete'))){
	
		$sql = $db->query("DELETE FROM __newsletter_archive WHERE id='".$db->escape($in->get('delete'))."'");
		$pdc->del_prefix('plugin.newsletter.archive');

	};

}


if (is_numeric($in->get('delete_l'))){
	
	$sql = $db->query("DELETE FROM __newsletter_archive WHERE letter_id='".$db->escape($in->get('delete_l'))."'");
	$pdc->del_prefix('plugin.newsletter.archive');

};


//Show Mails in a newsletter
if (is_numeric($in->get('lid'))){

	
	//Load the letter-information
	$letter_cache = $pdc->get('plugin.newsletter.letters.'.$in->get('lid'),false,true);
	if (!$letter_cache){
		$letter_query = $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($in->get('lid'))."'");
		$letter = $db->fetch_record($letter_query);
		$pdc->put('plugin.newsletter.letters.'.$in->get('lid'),$letter,86400,false,true);
		$db->free_result($letter_query);
	} else{
		$letter = $letter_cache;
	};
	

	//Pagination
	$start = $in->get('start', 0);
	
	//Get number of all mails in a letter
	//Cache: plugin.newsletter.archive.total.{LETTER_ID}
	$mails_total_cache = $pdc->get('plugin.newsletter.archive.total.'.$in->get('lid'),false,true);
	if (!$mails_total_cache) {
		$total_query = "SELECT count(*) FROM __newsletter_archive WHERE letter_id = '".$db->escape($in->get('lid'))."'";
		$total_mails = $db->query_first($total_query);
		$pdc->put('plugin.newsletter.archive.total.'.$in->get('lid'),$total_mails,86400,false,true);
	} else{
		$total_mails = $mails_total_cache;
	};
		
	$limit = ($user->data['user_ilimit']) ? $user->data['user_ilimit'] : 50;
	$pagination = generate_pagination('archive.php'.$SID.'&amp;lid='.$in->get('lid'), $total_mails, $limit, $start);
		
	
	//Get all mails in a letter
	//Cache: plugin.newsletter.archive.lid{LETTER_ID}.s{START}.i{ITEMS_PER_PAGE}
	$mails_cache = $pdc->get('plugin.newsletter.archive.lid'.$in->get('lid').'.s'.$start.".i".$limit,false,true);
	if (!$mails_cache) {
		
		$mails_query = "SELECT * FROM __newsletter_archive WHERE letter_id = '".$db->escape($in->get('lid'))."' ORDER BY date DESC LIMIT ".$db->escape($start).",".$db->escape($limit);
		$mails_query = $db->query($mails_query);
		$mails_data = $db->fetch_record_set($mails_query);
		$pdc->put('plugin.newsletter.archive.lid'.$in->get('lid').'.s'.$start.".i".$limit,$mails_data,86400,false,true);
	} else{
		$mails_data = $mails_cache;
	};
		
			

	if (is_array($mails_data)){
		foreach ($mails_data as $row){
	
			//Bring the newsletters to template
			$tpl->assign_block_vars('index_list', array(
				'NL_SUBJECT'		=> $nlclass->stripBBcode(sanitize($row['subject'])),
				'NL_DATE' 			=> sanitize($row['date']),
				'NL_MESSAGE'		=> $nlclass->wrapText($nlclass->stripBBcode(sanitize($row['body'])), 30),
				'NL_ID' 			=> sanitize($row['id']),
				'ROW_CLASS'			=> $eqdkp->switch_row_class(),
			));
		}; //END foreach
	};
			
	$s_no_mails = ($total_mails >0) ? false : true;
	
	$mails_footcount = sprintf($user->lang['nl_mails_footcount'], $total_mails, $limit);
	
	$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_delete_all_lettermails'],
                  'link'    => 'archive.php'.$SID.'&lid='.sanitize($letter['id']).'&delete_l='.sanitize($letter['id']),
                  'img'     => 'delete.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
			  1 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID,
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              2 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
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
		'PAGINATION'							=> $pagination,
		'NL_NAME'								=> sanitize($letter['name']),
		'NL_ID'									=> sanitize($letter['id']),
		'L_ARCHIVE'								=> $user->lang['nl_archive'],
		'L_SUBJECT'								=> $user->lang['nl_subject'],
		'L_MESSAGE'								=> $user->lang['nl_message'],
		'L_DATE'								=> $user->lang['nl_date'],
		'L_FOOTCOUNT'							=> $mails_footcount,
		'ERROR'									=> $error_msg,
		'S_NO_MAILS'							=> $s_no_mails,
		'S_ERROR'								=> $s_error,
		'L_NO_MAILS'							=> $user->lang['nl_no_mails'],
		'L_ACTION'								=> $user->lang['nl_action'],
		'L_DELETE'								=> $user->lang['nl_delete_mail'],
		'L_RESEND'								=> $user->lang['nl_resend_mail'],
		
		'ADMIN_MENU'							=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),
         //Common things
		'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
		'NL_CREDITS'                			=> 'Credits',
		'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
		'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
	));
    
    $eqdkp->set_vars(array(
	    	'page_title'	 					=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
			'template_path' 					=> $pm->get_data('newsletter', 'template_path'),
			'template_file'         			=> 'admin/archive_letter.html',
			'display'              				=> true));

} else { //END show mails in a newsletter
	
	//Show a single mail
	if (is_numeric($in->get('id'))){
		
		//Load the mail
  		//Cache: plugin.newsletter.archive.id{MAIL_ID}
  		$mail_cache = $pdc->get('plugin.newsletter.archive.id'.$in->get('id'),false,true);
		if (!$mail_cache){
			$mail_query =  $db->query("SELECT * FROM __newsletter_archive WHERE id = '".$db->escape($in->get('id'))."'");
			$mail = $db->fetch_record($mails_query);
			$db->free_result($mail_query );
			$pdc->put('plugin.newsletter.archive.id'.$in->get('id'),$mail,86400,false,true);
		} else{
			$mail = $mail_cache;
		};

			
		//Load the letter-information
		$letter_cache = $pdc->get('plugin.newsletter.letters.'.$mail['letter_id'],false,true);
		if (!$letter_cache){
			$letter_query =  $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($mail['letter_id'])."'");
			$letter = $db->fetch_record($letter_query);
			$db->free_result($letter_query );
			$pdc->put('plugin.newsletter.letters.'.$mail['letter_id'],$letter,86400,false,true);
		} else{
			$letter = $letter_cache;
		};
		

		$back_text = ($letter != "") ? sprintf($user->lang['nl_back_to_letter'], sanitize($letter['name'])) : $user->lang['nl_back_to_archive'];
		
	
			if ($mail != ""){
				
				$recipients = explode(";", $mail['recipients']);
			
				$recipients_letter = $recipients[0];
				$recipients_class = $recipients[1];
				$recipients_members = $recipients[2];
				$recipients_output = "";
				
				if ($recipients_letter){
					$recipients_output .= "<b>".$user->lang['nl_letter'].":</b> ".sanitize($letter['name'])."<br>";
				};
				if ($recipients_class){
					$classes = explode("|", $recipients_class);
					$recipients_output .= "<b>".$user->lang['nl_class'].":</b> ";
					
						foreach ($classes as $elem){
							if ($elem != ""){
								$recipients_output .= sanitize($elem).", ";
							};
						};
					$recipients_output .= "<br>";	
				};
				if ($recipients_members){
					$members = explode("|", $recipients_members);
					
					$sql = 'SELECT * FROM __users';
					$user_result = $db->query($sql);
					while($roww = $db->fetch_record($user_result)) {
    					$userrow[$roww['user_id']] = $roww;
					};
					$recipients_output .= "<b>".$user->lang['nl_members'].":</b> ";
					foreach ($members as $elem){
						if ($elem != "0"){
						$recipients_output .= sanitize($userrow[$elem]['username']).", ";
						}
					};
				};
								
				$body = $mail['body'];
				$body = str_replace("<br />", "\n", $body);
				$bbcode = new BBcode();
				$bbcode->SetSmiliePath("../../../libraries/jquery/images/editor/icons");
				//Bring the newsletters to template
				$tpl->assign_vars(array(
 						'NL_SUBJECT'		=> $bbcode->MyEmoticons($bbcode->toHTML(sanitize($mail['subject']))),
						'NL_DATE' 			=> sanitize($mail['date']),
						'NL_BODY'			=> $nlclass->toHTML($bbcode->MyEmoticons($bbcode->toHTML(sanitize($body)))),
						'NL_ID'				=> sanitize($mail['id']),
						'NL_RECIPIENTS'		=> $recipients_output,
				));
			};


			$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_delete_mail'],
                  'link'    => 'archive.php'.$SID.'&delete='.sanitize($mail['id']),
                  'img'     => 'delete.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
			 1 => array(
                  'name'    => $user->lang['nl_resend_mail'],
                  'link'    => 'send.php'.$SID.'&resend='.sanitize($mail['id']),
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
            ),
						  
			  
			  2 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID.'&resend='.sanitize($mail['id']),
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              3 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              4 => array(
                  'name'    => $user->lang['nl_templates'],
                  'link'    => 'templates.php'.$SID,
                  'img'     => 'styles.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              5 => array(
                  'name'    => $user->lang['nl_settings'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              
             
      );
			
 		$tpl->assign_vars(array(
        	 //Common things
			'NL_LETTER_NAME'						=> sanitize($letter['name']),
			'NL_LETTER_ID'							=> sanitize($letter['id']),
			'L_BACK_TO_LETTER'						=> $back_text,
			'S_ERROR'								=> $s_error,
			'ERROR'									=> $error_msg,
			'L_ARCHIVE'								=> $user->lang['nl_archive'],
			'L_SUBJECT'								=> $user->lang['nl_subject'],
			'L_MESSAGE'								=> $user->lang['nl_message'],
			'L_RECIPIENT'							=> $user->lang['nl_recipients'],
			'L_DATE'								=> $user->lang['nl_date'],
			'ADMIN_MENU'							=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),
						
			'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
			'NL_CREDITS'                			=> 'Credits',
			'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
			'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
		));
    
    	$eqdkp->set_vars(array(
	    	'page_title'	 		=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
			'template_path' 		=> $pm->get_data('newsletter', 'template_path'),
			'template_file'         => 'admin/archive_mail.html',
			'display'               => true));
		
		
		
	
	} //END show a single mail
	else { //Show archive overviews
	
		
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
		
		$i = count($letter_data);
		if (is_array($letter_data)){
			foreach ($letter_data as $row){
			
				//Get number of all mails in a letter
				//Cache: plugin.newsletter.archive.total.{LETTER_ID}
				$mails_cache = $pdc->get('plugin.newsletter.archive.lid'.$row['id'],false,true);
				if (!$mails_cache) {
					$mails_query = $db->query("SELECT * FROM __newsletter_archive WHERE letter_id='".$db->escape($row['id'])."' ORDER BY date DESC");
					$mails = $db->fetch_record_set($mails_query);
					$pdc->put('plugin.newsletter.archive.lid'.$row['id'],$mails,86400,false,true);
				} else{
					$mails = $mails_cache;
				};

				$mails_count = count($mails);
				
		
				//Bring the newsletters to template
				$tpl->assign_block_vars('index_list', array(
					'NL_NAME'			=> sanitize($row['name']),
					'NL_ID' 			=> sanitize($row['id']),
					'NL_DESC'			=> sanitize($row['description']),
					'NL_MAILS'			=> $mails_count,
					'NL_LAST_MAIL'		=> $mails[0]['date'],
					'NL_LAST_MAIL_ID'	=> $mails[0]['id'],
					'ROW_CLASS'			=> $eqdkp->switch_row_class(),
					
				));

			};
		};
	
		$mail_footcount = sprintf($user->lang['nl_letters_footcount'], $i);
		$s_no_letters = ($i < 1) ? true : false;
	
		//Show uncategorized Mails
		
		//Pagination
		$start = $in->get('start', 0);
	
		//Get number of all mails in a letter
		//Cache: plugin.newsletter.archive.total.{LETTER_ID}
		$mails_total_cache = $pdc->get('plugin.newsletter.archive.total.0',false,true);
		if (!$mails_total_cache) {
			$total_query = "SELECT count(*) FROM __newsletter_archive WHERE letter_id = '0'";
			$total_mails = $db->query_first($total_query);
			$pdc->put('plugin.newsletter.archive.total.0',$total_mails,86400,false,true);
		} else{
			$total_mails = $mails_total_cache;
		};
		
		$limit = ($user->data['user_ilimit']) ? $user->data['user_ilimit'] : 50;
		$pagination = generate_pagination('archive.php'.$SID.'&amp;lid='.$in->get('lid'), $total_mails, $limit, $start);
		
	
		//Get all mails in a letter
		//Cache: plugin.newsletter.archive.lid{LETTER_ID}.s{START}.i{ITEMS_PER_PAGE}
		$mails_cache = $pdc->get('plugin.newsletter.archive.lid0.s'.$start.".i".$limit,false,true);
		if (!$mails_cache) {
		
			$mails_query = "SELECT * FROM __newsletter_archive WHERE letter_id = '0' ORDER BY date DESC LIMIT ".$db->escape($start).",".$db->escape($limit);
			$mails_query = $db->query($mails_query);
			$mails_data = $db->fetch_record_set($mails_query);
			$pdc->put('plugin.newsletter.archive.lid0.s'.$start.".i".$limit,$mails_data,86400,false,true);
		} else{
			$mails_data = $mails_cache;
		};
		
			

		if (is_array($mails_data)){
			foreach ($mails_data as $row){
	
				//Bring the newsletters to template
				$tpl->assign_block_vars('mail_list', array(
					'NL_SUBJECT'		=> $nlclass->stripBBcode(sanitize($row['subject'])),
					'NL_DATE' 			=> sanitize($row['date']),
					'NL_MESSAGE'		=> $nlclass->wrapText($nlclass->stripBBcode(sanitize($row['body'])), 30),
					'NL_ID' 			=> sanitize($row['id']),
					'ROW_CLASS'			=> $eqdkp->switch_row_class(),
				));
			};
		};
			
		$s_no_mails = ($total_mails >0) ? false : true;
	
		$mails_footcount = sprintf($user->lang['nl_mails_footcount'], $total_mails, $limit);
	  
	  			$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_delete_archive'],
                  'link'    => 'archive.php'.$SID.'&delete=all',
                  'img'     => 'delete.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
			 1 => array(
                  'name'    => $user->lang['nl_delete_all_uncategorized'],
                  'link'    => 'archive.php'.$SID.'&delete_l=0',
                  'img'     => 'delete.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
            ),
						  
			  
			  2 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID.'&resend='.sanitize($mail['id']),
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              3 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              4 => array(
                  'name'    => $user->lang['nl_templates'],
                  'link'    => 'templates.php'.$SID,
                  'img'     => 'styles.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              5 => array(
                  'name'    => $user->lang['nl_settings'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              
             
      );
	  
	  
		$tpl->assign_vars(array(
				
		//Language
		'L_BUTTON_SAVE'							=> $user->lang['nl_button_save'],
		'L_BUTTON_RESET'						=> $user->lang['nl_button_reset'],
		'L_FOOTCOUNT'							=> $mail_footcount,
		'L_NAME'								=> $user->lang['nl_name'],
		'L_DESCRIPTION'							=> $user->lang['nl_description'],
		'L_MAILS'								=> $user->lang['nl_mails'],
		'L_LAST_MAIL'							=> $user->lang['nl_last_mail'],
		'L_ARCHIVE'								=> $user->lang['nl_archive'],
		'S_NO_LETTERS'							=> $s_no_letters,
		'L_NO_LETTERS'							=> $user->lang['nl_no_letters'],
		
		'L_MAIL_FOOTCOUNT'						=> $mails_footcount,
		'L_SUBJECT'								=> $user->lang['nl_subject'],
		'L_MESSAGE'								=> $user->lang['nl_message'],
		'L_DATE'								=> $user->lang['nl_date'],
		'L_UNCATEGORIZED'						=> $user->lang['nl_uncategorized'],
		'L_ACTION'								=> $user->lang['nl_action'],
		'L_DELETE'								=> $user->lang['nl_delete_mail'],
		'L_RESEND'								=> $user->lang['nl_resend_mail'],
		'S_NO_MAILS'							=> $s_no_mails,
		'L_NO_MAILS'							=> $user->lang['nl_no_mails'],
		
		'ADMIN_MENU'							=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),
	
         //Common things
		'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
		'NL_CREDITS'                			=> 'Credits',
		'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
		'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
		));
    
    $eqdkp->set_vars(array(
	    	'page_title'	 		=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
			'template_path' 		=> $pm->get_data('newsletter', 'template_path'),
			'template_file'         => 'admin/archive.html',
			'display'               => true)
    );
	
	
	} //END

} //END


?>