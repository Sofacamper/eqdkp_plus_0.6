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

if ($conf['public_archive'] == 0) { redirect(''); message_die('Access denied.'); }

$user->check_auth('u_newsletter_view');

//Show Mails in a newsletter
if (is_numeric($in->get('lid'))){

	//Load the subscribed newsletters
	//Cache: plugin.newsletter.subscribers.u{USER_ID}
	$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.u'.$user->data['user_id'].".lid".$in->get('lid'),false,true);
	if (!$subscriber_cache){
		$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE user_id = '".$db->escape($user->data['user_id'])."' AND letter_id='".$db->escape($in->get('lid'))."'");
		$subscriber = $db->fetch_record($subscriber_query);
		$db->free_result($subscriber_query);
		$pdc->put('plugin.newsletter.subscribers.u'.$user->data['user_id'].".lid".$in->get('lid'),$subscriber,86400,false,true);
	} else{
		$subscriber = $subscriber_cache;
	};

	//Load the newsletter the mail was send to
	//Cache: plugin.newsletter.letters.{LETTER_ID}
	$letter_cache = $pdc->get('plugin.newsletter.letters.'.$in->get('lid'),false,true);
	if (!$letter_cache){
		$letter_query = $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($in->get('lid'))."'");
		$letter = $db->fetch_record($letter_query);
		$pdc->put('plugin.newsletter.letters.'.$in->get('lid'),$letter,86400,false,true);
		$db->free_result($letter_query);
	} else{
		$letter = $letter_cache;
	};
	
	
	//If archiv is public and the user has the letter subscribed or the letter is public
	if ($letter['archive'] == 1 && ($subscriber['status'] == 1 || $letter['permission'] == 2)){

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
						'NL_MESSAGE'		=> $nlclass->wrapText($nlclass->stripBBcode(sanitize($row['body'])), 60),
						'NL_ID' 			=> sanitize($row['id']),
						'ROW_CLASS'			=> $eqdkp->switch_row_class(),
				));
			}; //END foreach
		}; //END is_array
			
		$s_no_mails = ($total_mails >0) ? false : true;
		
	} else { //If the user has no permission to get this newsletter
	
		$error_msg = $user->lang['nl_no_permission_letter'];
		$s_error = true;
	};
	
	$mails_footcount = sprintf($user->lang['nl_mails_footcount'], $total_mails, $limit);

	
 	$tpl->assign_vars(array(
		'PAGINATION'							=> $pagination,
		'NL_NAME'								=> sanitize($letter['name']),
		'L_ARCHIVE'								=> $user->lang['nl_archive'],
		'L_SUBJECT'								=> $user->lang['nl_subject'],
		'L_MESSAGE'								=> $user->lang['nl_message'],
		'L_DATE'								=> $user->lang['nl_date'],
		'L_FOOTCOUNT'							=> $mails_footcount,
		'ERROR'									=> $error_msg,
		'S_NO_MAILS'							=> $s_no_mails,
		'S_ERROR'								=> $s_error,
		'L_NO_MAILS'							=> $user->lang['nl_no_mails'],
		
         //Common things
		'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
		'NL_CREDITS'                			=> 'Credits',
		'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
		'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], 'about.php', '500', '350'),
	));
    
    $eqdkp->set_vars(array(
	    'page_title'	 						=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
		'template_path' 						=> $pm->get_data('newsletter', 'template_path'),
		'template_file'         				=> 'archive_letter.html',
		'display'               				=> true));

} else { //END show mails in a newsletter
	
	//======================================================
	//Show a single mail
	if (is_numeric($in->get('id'))){
		
		//Load the mail from the archive
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

		//Load if user has subscribed this newsletter
		//Cache: plugin.newsletter.subscribers.u{USER_ID}.lid{LETTER_ID}
		$subscriber_cache = $pdc->get('plugin.newsletter.subscribers.u'.$user->data['user_id'].".lid".$mail['letter_id'],false,true);
		if (!$subscriber_cache){
			$subscriber_query = $db->query("SELECT * FROM __newsletter_subscribers WHERE user_id = '".$db->escape($user->data['user_id'])."' AND letter_id='".$db->escape($mail['letter_id'])."'");
			$subscriber = $db->fetch_record($subscriber_query);
			$db->free_result($subscriber_query);
			$pdc->put('plugin.newsletter.subscribers.u'.$user->data['user_id'].".lid".$mail['letter_id'],$subscriber,86400,false,true);
		} else{
			$subscriber = $subscriber_cache;
		};
	  
		//Load the newsletter-information
		//Cache: plugin.newsletter.letters.{LETTER_ID}
		$letter_cache = $pdc->get('plugin.newsletter.letters.'.$mail['letter_id'],false,true);
		if (!$letter_cache){
			$letter_query = $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($mail['letter_id'])."'");
			$letter = $db->fetch_record($letter_query);
			$pdc->put('plugin.newsletter.letters.'.$mail['letter_id'],$letter,86400,false,true);
			$db->free_result($letter_query);
		} else{
			$letter = $letter_cache;
		};
	
		//If archiv is public and the user has the letter subscribed or the letter is public
		if ($mail != "" && $letter['archive'] == 1 && ($subscriber['status'] == 1 || $letter['permission'] == 2)){
				
			//Make a smile :-)
			$bbcode = new BBcode();
			$bbcode->SetSmiliePath("../../libraries/jquery/images/editor/icons");
				
			$body = $mail['body'];
			$body = str_replace("<br />", "\n", $body);
			//Bring the newsletters to template
			$tpl->assign_vars(array(
 				'NL_SUBJECT'		=> $bbcode->MyEmoticons($bbcode->toHTML(sanitize($mail['subject']))),
				'NL_DATE' 			=> sanitize($mail['date']),
				'NL_BODY'			=> $nlclass->toHTML($bbcode->MyEmoticons($bbcode->toHTML(sanitize($body)))),
			));

		} else { //If the user has no permission to get this newsletter
			$error_msg = $user->lang['nl_no_permission_letter'];
			$s_error = true;
		};


 		$tpl->assign_vars(array(
        	 //Common things
			'NL_LETTER_NAME'						=> sanitize($letter['name']),
			'NL_LETTER_ID'							=> sanitize($letter['id']),
			'L_BACK_TO_LETTER'						=> sprintf($user->lang['nl_back_to_letter'], sanitize($letter['name'])),
			'S_ERROR'								=> $s_error,
			'ERROR'									=> $error_msg,
			'L_ARCHIVE'								=> $user->lang['nl_archive'],
			'L_SUBJECT'								=> $user->lang['nl_subject'],
			'L_MESSAGE'								=> $user->lang['nl_message'],
			'L_DATE'								=> $user->lang['nl_date'],
			'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
			'NL_CREDITS'                			=> 'Credits',
			'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
			'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], 'about.php', '500', '350'),
		));
    
    	$eqdkp->set_vars(array(
	    	'page_title'	 						=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
			'template_path' 						=> $pm->get_data('newsletter', 'template_path'),
			'template_file'         				=> 'archive_mail.html',
			'display'               				=> true));
		
		
		
	
	} //END show a single mail
	else { 
	//===================================================
	//Show archive overviews
	
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
				$subscribed_letters[$elem['letter_id']] = $elem;
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
	
		
		$i = 0;
		if (is_array($letter_data)){
			foreach ($letter_data as $row){
			
				$status = $subscribed_letters[$row['id']]['status'];	
			
				//Show only public newsletters AND newsletters he has subscribed
				if ($row['archive'] == 1 && ($status == 1 || $row['permission'] == 2)) {
					$i = $i + 1;
	
					//Get number of all mails in a letter
					//Cache: plugin.newsletter.archive.total.lid{LETTER_ID}
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
						'NL_MAILS'			=> sanitize($mails_count),
						'NL_LAST_MAIL'		=> sanitize($mails[0]['date']),
						'NL_LAST_MAIL_ID'	=> sanitize($mails[0]['id']),
						'ROW_CLASS'			=> $eqdkp->switch_row_class(),
					
					));
				};			
			};
		};	

	
		$mail_footcount = sprintf($user->lang['nl_letters_footcount'], $i);
		$s_no_letters = ($i < 1) ? true : false;
  
		$tpl->assign_vars(array(
				
			//Language
			'L_BUTTON_SAVE'							=> $user->lang['nl_button_save'],
			'L_BUTTON_RESET'						=> $user->lang['nl_button_reset'],
			'L_FOOTCOUNT'							=> sanitize($mail_footcount),
			'L_NAME'								=> $user->lang['nl_name'],
			'L_DESCRIPTION'							=> $user->lang['nl_description'],
			'L_MAILS'								=> $user->lang['nl_mails'],
			'L_LAST_MAIL'							=> $user->lang['nl_last_mail'],
			'L_ARCHIVE'								=> $user->lang['nl_archive'],
			'S_NO_LETTERS'							=> $s_no_letters,
			'L_NO_LETTERS'							=> $user->lang['nl_no_letters'],
				
			//Common things
			'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
			'NL_CREDITS'                			=> 'Credits',
			'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
			'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], 'about.php', '500', '350'),
		));
    
		$eqdkp->set_vars(array(
			'page_title'	 						=> $nlclass->GeneratePageTitle($user->lang['nl_ad_archive']),
			'template_path' 						=> $pm->get_data('newsletter', 'template_path'),
			'template_file'        			 		=> 'archive.html',
			'display'               				=> true)
		);
		
	} //END show archive overview

} //END


?>