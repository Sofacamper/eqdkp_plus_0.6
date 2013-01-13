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
 

//Save the newsletter
if (($in->get('submit') != "") && ($in->get('id') != "")){
	
	if ($in->get('name') != ""){
	
		$update_query = $db->query("UPDATE __newsletter_letters SET
    			name			='".$db->escape($in->get('name', ''))."',
    			description		='".$db->escape($in->get('desc', ''))."',
    			permission		='".$db->escape($in->get('permission', 0))."',
				archive 		='".$db->escape($in->get('archive_permission', 0))."',
				preset_archive 	='".$db->escape($in->get('preset_archive', 0))."',
				preset_template ='".$db->escape($in->get('preset_template', ''))."',
				preset_type 	='".$db->escape($in->get('preset_type', 'email'))."'
  				WHERE id	='".$db->escape($in->get('id'))."'");
		$error_msg = "<script>parent.window.location.href = 'newsletters.php';</script>";
		
		$pdc->del_prefix('plugin.newsletter.letters');
	} else {
	
		$error_msg = $user->lang['nl_fields_empty'];
	
	}
						
};


$permission_dropdown = array(
	'2'     => $user->lang['nl_letter_perm2'],
	'1'  	=> $user->lang['nl_letter_perm1'],
	'0'  	=> $user->lang['nl_letter_perm0'],
);
		
$archive_permission_dropdown = array(
	'1'  	=> $user->lang['nl_archive_perm1'],
	'0'  	=> $user->lang['nl_archive_perm0'],
);

		
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
	
$templates_array = array( '-1' => $user->lang['nl_select_archive'], '0' => "----------");
	
if (is_array($template)){
		
	foreach($template as $row){
		$templates_array[$row['id']] = $row['name'];
			
	}
		
};


//Cache: plugin.newsletter.letters.{LETTER_ID}
$letter_cache = $pdc->get('plugin.newsletter.letters.'.$in->get('id'),false,true);
if (!$letter_cache){
	$letter_query = $db->query("SELECT * FROM __newsletter_letters WHERE id = '".$db->escape($in->get('id'))."'");
	$letter = $db->fetch_record($letter_query);
	$pdc->put('plugin.newsletter.letters.'.$in->get('id'),$letter,86400,false,true);
	$db->free_result($letter_query);
} else{
	$letter = $letter_cache;
};
					
					
		
if ($letter){

    $tpl->assign_vars(array(
		'LETTER_NAME'       			=> sanitize($letter['name']),
		'LETTER_DESC'       			=> sanitize($letter['description']),
		'LETTER_ID'		  				=> sanitize($letter['id']),
		'LETTER_SMS'					=> ($letter['preset_type'] == "sms") ? ' checked="checked"' : "",
		'LETTER_MAIL'					=> ($letter['preset_type'] == "email") ? ' checked="checked"' : "",
		'LETTER_ARCHIVE'				=> $nlclass->is_checked($letter['preset_archive']),
		'PERMISSION_DROPDOWN'			=> $htmlclass->DropDown('permission', $permission_dropdown, $letter['permission']),
		'ARCHIVE_PERMISSION_DROPDOWN'	=> $htmlclass->DropDown('archive_permission', $archive_permission_dropdown, $letter['archive']), 
        'TEMPLATES_DROPDOWN'			=> $htmlclass->DropDown('preset_template', $templates_array, $letter['preset_template']), 
	));
}


$tpl->assign_vars(array(
			
           	'ERROR_MSG'				=> $error_msg,
			
			'L_LETTER_PERM'         => $user->lang['nl_letter_perm'],
			'L_ARCHIVE_PERM'        => $user->lang['nl_archive_perm'],
			'L_EDIT_LETTER'       	=> $user->lang['nl_editletter'],
			'L_PRESETTINGS'       	=> $user->lang['nl_letter_presettings'],
			'L_NAME'       			=> $user->lang['nl_name'],
			'L_DESC'       			=> $user->lang['nl_description'],
			'L_BODY'          		=> $user->lang['nl_mail_body'],
			'B_SAVE'          		=> $user->lang['nl_button_save'],
			'B_RESET'          		=> $user->lang['nl_button_reset'],
			'L_TEMPLATE'			=> $user->lang['nl_letter_standardtemplate'],
			'L_ARCHIVE'				=> $user->lang['nl_letter_archive'],
			'L_TYPE'				=> $user->lang['nl_letter_type'],
			'L_MAIL'				=> $user->lang['nl_mail'],
			'L_SMS'					=> $user->lang['nl_sms'],
			'S_SMS_DISABLED'		=> ($conf_plus['pk_sms_disable'] == "1") ? true : false,
			'JS_FIELDS_EMPTY'		=> $jquery->Dialog_Alert('fields_empty', $user->lang['nl_fields_empty'], $user->lang['nl_fields_empty_title'],'300', '36'),
			
));
    
$eqdkp->set_vars(array(
	    	'page_title'            => $nlclass->GeneratePageTitle($user->lang['nl_templates']),
			'template_path' 	    => $pm->get_data('newsletter', 'template_path'),
			'template_file'         => 'admin/newsletters_edit.html',
			'gen_simple_header'  	=> true,
			'display'               => true)
);

?>