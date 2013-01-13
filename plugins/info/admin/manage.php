<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-02-25 18:06:31 +0100 (Wed, 25 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 3992 $
 *
 * $Id: manage.php 3992 2009-02-25 17:06:31Z sz3 $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'info');

$eqdkp_root_path = './../../../';
include_once ($eqdkp_root_path . 'common.php');
include_once ('../include/functions.php');
include_once ('../include/kompeditor.php');

// Check user permission
$user->check_auth('a_information_man');

if (!$pm->check(PLUGIN_INSTALLED, 'info')) {
	message_die('Infopages plugin is not installed.');
}

// get config
$config = getInfoConfig();

// tinymce
if ($config[ee] == 1){
	$editor = New kompEditor;
	$settings_array = array(
		'language' => $user->lang['editor_language'],
		'textbox_cols' => $config[ec],
		'textbox_rows' => $config[er]
		);
	$editorinit = $editor->generate($settings_array, $eqdkp_root_path);
}else{
	$editorinit = '';
}

if ($_POST['infoupdatebu']) {
	$info_page_data['page_title'] = $_POST['title'];
	$info_page_data['page_content'] = $_POST['page_content'];
	$info_page_data['page_ise'] = 0+$_POST['ise'];
	$info_page_data['page_menu_link'] =  $_POST['ml'];
	$info_page_data['page_vis'] =  $_POST['vis'];
	$info_page_data['page_edit_user'] = $user->data['user_id'];
	$info_page_data['page_edit_date'] = time();

	$info_updpage_id = intval($_POST['pagelist']);


    $sql = 	"UPDATE __info_pages SET " 
			. "page_title='" . $info_page_data['page_title'] . "', "
			. "page_content='" . $info_page_data['page_content'] . "', "
			. "page_ise='" . $info_page_data['page_ise'] . "', "
			. "page_menu_link='" . $info_page_data['page_menu_link'] . "', "
			. "page_edit_user='" . $info_page_data['page_edit_user'] . "', "
			. "page_visibility='" . $info_page_data['page_vis'] . "', "
			. "page_edit_date='" . $info_page_data['page_edit_date'] . "' "
			. "WHERE page_id='" . $info_updpage_id . "';";
	$db->query($sql)
		or message_die("SQL ERROR: " . mysql_errno() ." ". mysql_error());  
}

if ($_POST['infodelbu']) {
	$info_cpage_id = intval($_POST['pagelist']);
	$sql = "DELETE FROM __info_pages WHERE page_id='" . $info_cpage_id . "';";
	$db->query($sql)
		or message_die("SQL ERROR: " . mysql_errno() ." ". mysql_error());  
}

if ($_POST['infoaddbu']) {
	$info_page_data['page_title'] = $_POST['title'];
	$info_page_data['page_content'] = $_POST['page_content'];
	$info_page_data['page_ise'] = 0+$_POST['ise'];
	$info_page_data['page_menu_link'] =  $_POST['ml'];
	$info_page_data['page_edit_user'] = $user->data['user_id'];
	$info_page_data['page_edit_date'] = time();
  $info_page_data['page_vis'] =  $_POST['vis'];
  
	$fields = "(page_title, page_content, page_ise, page_visibility, page_menu_link, page_edit_user, page_edit_date)";
    $values =  "('".$info_page_data['page_title']."',"
			  ." '".$info_page_data['page_content']."',"
			  ." '".$info_page_data['page_ise']."',"
			  ." '".$info_page_data['page_vis']."',"
			  ." '".$info_page_data['page_menu_link']."',"
			  ." '".$info_page_data['page_edit_user']."',"
			  ." '".$info_page_data['page_edit_date']."');";
    
	$sql = "INSERT INTO __info_pages " .$fields. " VALUES " . $values;
    $db->query($sql)
	 or message_die("SQL ERROR: " . mysql_errno() ." ". mysql_error());
	
	$info_pages = getPageList();
	$info_page_ids = array_keys($info_pages);
    $info_updpage_id = $info_page_ids[count($info_page_ids)-1];

}

// get image/title list
$info_pages = getPageList();

if (count($info_pages) > 0){
	if ( ($_POST['changed'] == 'PAGE') && (array_key_exists(intval($_POST['pagelist']), $info_pages)) ){
		//valid page => data for selected page
		$info_cpage_id = intval($_POST['pagelist']);
		$info_page_data = getPageData($info_cpage_id);	
	}else{
		//Get page data for first page
		if (isset($info_updpage_id)){
			$info_cpage_id = $info_updpage_id;
		}else{
			$info_page_ids = array_keys($info_pages);
			$info_cpage_id = $info_page_ids[0];
		}
		$info_page_data = getPageData($info_cpage_id);	
	}
}else{
	//No page data availible, set defaults
	$info_page_data['page_title'] = "empty";
	$info_page_data['page_content'] = "";
	$info_page_data['page_ise'] = 0;
	$info_page_data['page_vis'] = 0;
	$info_page_data['page_menu_link'] = 0;
}

$mlvals[0] = $user->lang['info_opt_ml_0'];
$mlvals[1] = $user->lang['info_opt_ml_1'];
$mlvals[2] = $user->lang['info_opt_ml_2'];
$mlvals[99] = $user->lang['info_opt_ml_99'];

foreach ($mlvals as $value => $name){
	$tpl->assign_block_vars('ml_row', array (
		'VALUE' => $value,
		'SELECTED' => ($info_page_data['page_menu_link'] == $value) ? ' selected="selected"' : '',
		'OPTION' => $name
		)
	);
}

$visvals[0] = $user->lang['info_opt_vis_0'];
$visvals[1] = $user->lang['info_opt_vis_1'];
$visvals[2] = $user->lang['info_opt_vis_2'];

foreach ($visvals as $value => $name){
	$tpl->assign_block_vars('vis_row', array (
		'VALUE' => $value,
		'SELECTED' => ($info_page_data['page_visibility'] == $value) ? ' selected="selected"' : '',
		'OPTION' => $name
		)
	);
}

if (is_array($info_pages)){
	foreach ($info_pages as $pageid => $pagetitle) {
		$tpl->assign_block_vars('pages_row', array (
			'VALUE' => $pageid,
			'SELECTED' => ($info_cpage_id == $pageid) ? ' selected="selected"' : '',
			'OPTION' => $pagetitle.' : '.$pageid
			)
		);
	}
}

$tpl->assign_vars(array (
	'F_ACTION' 			=> 'manage.php' . $SID,
	'EDITOR_INIT'		=> $editorinit,
	'DISABLE_BUTTON'	=> (count($info_pages) == 0) ? 'disabled' : '',
	
	'INFO_PAGE_TITLE'		=> $info_page_data['page_title'],
	'INFO_PAGE_CONTENT'		=> $info_page_data['page_content'],
	'INFO_PAGE_ISE'			=> ($info_page_data['page_ise'] == 1) ? 'checked="checked"' : '',

	'INFO_TEXT_COLS'	=> $config['ec'],
	'INFO_TEXT_ROWS'	=> $config['er'],
	
	'L_INFO_PAGELIST'	=> $user->lang['info_opt_tsel'],
	'L_INFO_CONTENTOPT'	=> $user->lang['info_contentopt'],
	'L_INFO_PAGEOPT'	=> $user->lang['info_pageopt'],
	'L_INFO_PAGE_TITLE'		=> $user->lang['info_opt_title'],
	'L_INFO_PAGE_CONTENT'		=> $user->lang['info_opt_content'],
	'L_INFO_PAGE_ISE'		=> $user->lang['info_opt_ise'],
	'L_INFO_PAGE_ML'		=> $user->lang['info_opt_ml'],
	'L_INFO_PAGE_VIS'  => $user->lang['info_opt_visibility'],
  'EDITED' => $user->lang['info_edit_user'].getUserName($info_page_data['page_edit_user']).$user->lang['info_edit_date'].date($user->style['date_notime_long'], $info_page_data['page_edit_date']),

	 'CREDITS'     => $user->lang['info_cp'] . $pm->get_data('info', 'version') . $user->lang['info_cp2']
));

$eqdkp->set_vars(array (
	'page_title' => sprintf($user->lang['info'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']) . ': ' . $info_page_data['page_title'],
	'template_path' => $pm->get_data('info', 'template_path'),
	'template_file' => 'admin/manage.html',
	'display' => true));
?>
