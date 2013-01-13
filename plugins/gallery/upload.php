<?PHP
/*
* Project:     EQdkp-Plus
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* ----------------------------------------------------------------------------------
* Gallery 4 EQdkp plus
* ---------------------------------------------------------------------------------- 
* Project Start: 09/2008
* Author: BadTwin
* Copyright: Andreas (BadTwin) Schrottenbaum
* Link: http:// bloody-midnight.eu
* Version: 2.0.11
* ----------------------------------------------------------------------------------
* Based on the HelloWorld Plugin by Wallenium
*
* $Id: $
*/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'gallery');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once('include/libloader.inc.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }

// Check user permission
$user->check_auth('u_gallery_upload');

// UPLOAD-VIEW

$ergebnis = $db->query("SELECT * FROM __gallery_categories");

while ($row = $db->fetch_record($ergebnis)){
	if ($row['category_id'] == $_GET['category']){
		$tpl->assign_block_vars('categories', array(
			'GL_CATEGORY_ID'		=>	$row['category_id'].'" selected="selected',
			'GL_CATEGORY_NAME'	=>	$row['category_name'],
		));
	} else {
		$tpl->assign_block_vars('categories', array(
			'GL_CATEGORY_ID'		=>	$row['category_id'],
			'GL_CATEGORY_NAME'	=>	$row['category_name'],
		));
	}
}

// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);

// Send the Output to the template Files.

$tpl->assign_vars(array(
	'GL_HEADLINE'              =>	$user->lang['gl_index_headline'],
  'GL_HEADLINE_UP'           =>	$user->lang['gl_up_header'],
	'GL_FILENAME'              =>	$user->lang['gl_up_file'],
	'GL_NAME'                  =>	$user->lang['gl_up_name'],
	'GL_CATEGORY'              =>	$user->lang['gl_up_category'],
	'GL_DESCRIPTION'           =>	$user->lang['gl_up_description'],
	'GL_INFO'                  =>	$user->lang['gl_info'],
	'GL_FILENAME_DESCRIBE'     =>	$user->lang['gl_filename_describe'],
	'GL_NAME_DESCRIBE'         =>	$user->lang['gl_name_describe'],
	'GL_CATEGORY_DESCRIBE'     =>	$user->lang['gl_cat_describe'],
	'GL_DESCRIBTION_DESCRIBE'  =>	$user->lang['gl_describtion_describe'],
	'GL_SEND'                  =>	$user->lang['gl_up_send'],
	'GL_RESET'                 =>	$user->lang['gl_up_reset'],
	'GL_ABOUT_HEADER'          => $user->lang['gl_about_header'],
	'L_COPYRIGHT'            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
	'L_CREDITS'   => $jquery->Dialog_URL('GLAbout', $user->lang['gl_about_header'], 'about.php', '400', '300'),
  'NO_UPLOAD'                => $no_upload,
	'GL_ROW_CLASS'             =>	$eqdkp->switch_row_class(),
));

// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'           => $user->lang['gallery'],
	'template_path'        => $pm->get_data('gallery', 'template_path'),
	'template_file'        => 'upload.html',
	'display'              => true)
  );

?>