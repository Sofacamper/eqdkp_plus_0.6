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

//Save the template
if ($in->get('submit') != ""){

	if (($in->get('name') != "") && ($in->get('subject') != "") && ($in->get('body') != "")){
	
		$query = $db->query('INSERT INTO __newsletter_templates :params', array(
  					'name'				=> $in->get('name', ''),
  					'subject'			=> $in->get('subject', ''),
  					'body'        		=> $in->get('body', ''),
					'signature'			=> $in->get('signature', 0),
            ));
  				
		$pdc->del_prefix('plugin.newsletter.templates');
		$error_msg = "<script>parent.window.location.href = 'templates.php';</script>";

	};
						
};



    $tpl->assign_vars(array(

              
			'ERROR_MSG'				=> $error_msg,
			'L_NAME'          		=> $user->lang['nl_templatename'],
			'L_SUBJECT'       		=> $user->lang['nl_subject'],
			'L_BODY'          		=> $user->lang['nl_mail_body'],
			'B_SAVE'          		=> $user->lang['nl_save_template'],
			'B_RESET'          		=> $user->lang['nl_button_reset'],
			'L_RECEPIENTS'			=> $user->lang['nl_recipients'],
			'L_SIGNATURE'			=> $user->lang['nl_show_signature'],
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
			'WYSIWYG_EDITOR' 		=> $jquery->wysiwyg('body'),
			 //Common things
			'ABOUT_HEADER'             				=> $user->lang['dl_about_header'],	
			'NL_CREDITS'                			=> 'Credits',
			'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
			'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
				  
		));
    
    $eqdkp->set_vars(array(
	    	'page_title'            => $nlclass->GeneratePageTitle($user->lang['nl_templates']),
			'template_path' 	    => $pm->get_data('newsletter', 'template_path'),
			'template_file'         => 'admin/template_add.html',
			'gen_simple_header'  	=> true,
			'display'               => true)
    );

?>