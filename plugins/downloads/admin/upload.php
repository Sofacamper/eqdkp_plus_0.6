<?PHP
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Downloads-plugin is not installed.'); }

// Check user permission
$user->check_auth('u_downloads_upload');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');

$categories_disabled 	= ($conf['disable_categories'] == 1) 	? true : false;
$mirrors_enabled 		= ($conf['enable_mirrors'] == 1) 		? true : false;
$related_links_enabled  = ($conf['enable_related_links'] == 1)  ? true : false;
$hosting_mode = ($_HMODE) ? true : false;

//Make the redirect-url when Download has been uploaded
if ($categories_disabled == true){
	$redirect_url = "../downloads.php".$SID;
}
else {

	if ($in->get('catid') != ""){
		
		$redirect_url = ($in->get('ref') == "acp") ? "downloads.php".$SID : "../downloads.php".$SID."&cat=".sanitize($in->get('catid'));
		
	}
	else {

		if ($in->get('ref') != ""){

			$redirect_url = ($in->get('ref') == "acp") ? "downloads.php".$SID : "../downloads.php".$SID;			
		}
		else {
			$redirect_url = "../downloads.php".$SID;
		}
	}
}


//If form has been submitted
if ($in->get('submit') != ""){

	if ((($_FILES['dl_file']['name'] == "") && ($in->get('dl_url') == "")) || ($in->get('dl_name') == "")){
		
		$error_msg = $user->lang['dl_ad_fields_empty'];
	
	}
	
	else {
		
		//Local File
		if (($_FILES['dl_file']['name'] != "") && ($hosting_mode == false)){
			
			$filename = $_FILES['dl_file']['name'];
			
			if($filename != ""){
				
				if ($conf['enable_debug'] == 1){
							$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
							echo "Mime-Type: ".$_FILES['dl_file']['type']."<br>";
							echo "File-Extension: ".$file_type;
				}
						
				$mirrors = implode('|', $in->getArray('dl_mirrors', 'string'));
				$related_downloads = str_replace(" ", "", $in->get('dl_related_links'));
				$related_downloads = str_replace(",", "|", $related_downloads);
				

				$preview_status = $dlclass->upload_file("dl_previewimage","jpg, gif, png",0, false, "thumbs/");
				switch ($preview_status) {
					case "0": $preview_image = ""; break;
					case "1": $preview_image = ""; break;
					case "2": $preview_image = ""; break;
					default: $preview_image  = $preview_status;
					
					
				}
				

				
				$upload_status = $dlclass->upload_file("dl_file",$conf['accepted_file_types'],$conf['enable_debug'], true, "files/", $conf['filesize_limit'], $conf['folder_limit']);
				
				switch ($upload_status) {
					case "0": $error_msg = $user->lang['dl_ad_fields_empty'];
						break;
					case "1": $error_msg = $user->lang['dl_ad_upload_file_exists'];
						break;
					case "2": $error_msg = $user->lang['dl_ad_upload_fail_file'];
						break;
					default:
						$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
						$sql="INSERT INTO __downloads_links (url, name, description, category, date, user_id, permission, file_type, file_size, local_filename, mirrors, related_downloads, preview_image) VALUES (
						'".$db->escape($filename)."', 
						'".$db->escape($in->get('dl_name'))."',
						'".$db->escape($in->get('dl_description'))."',
						'".$db->escape($in->get('dl_category', 0))."',
						NOW(),
						'".$db->escape($user->data['user_id'])."',
						'".$db->escape($in->get('dl_permission', 0))."',
						'".$db->escape($file_type)."',
						'".$db->escape($_FILES['dl_file']['size'])."',
						'".$db->escape($upload_status)."',
						'".$db->escape($mirrors)."',
						'".$db->escape($related_downloads)."',
						'".$db->escape($preview_image)."'
						)";
						$upload = $db->query($sql);
						
						if (!$upload){
							if (file_exists($pcache->FilePath('files/'.$upload_status, 'downloads'))) {
								unlink($pcache->FilePath('files/'.$upload_status, 'downloads'));
							};
							if (file_exists($pcache->FilePath('thumbs/'.$preview_status, 'downloads'))) {
								unlink($pcache->FilePath('thumbs/'.$preview_status, 'downloads'));
							};
						};
						
						if ($conf['enable_debug'] == 1){
							echo "<br>Status: successfully uploaded";
						} else {
							//Redirect on success
							$error_msg = "<script>parent.window.location.href = '".urldecode($redirect_url)."&error=3';</script>";
						}
						
						//Delete Cache
						$pdc->del_suffix('plugin.downloads');
						break;
				}
		
			} else {$error_msg = $user->lang['dl_ad_fields_empty'];};
		
		}
		//External Link
		else {
			
			$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
			if ((strlen($file_type) < 2) || strlen($file_type) > 5 ){$file_type = "unknown";};

			$mirrors = implode('|', $in->getArray('dl_mirrors', 'string'));
			$related_downloads = str_replace(" ", "", $in->get('dl_related_links'));
			$related_downloads = str_replace(",", "|", $related_downloads);
			
			$preview_status = $dlclass->upload_file("dl_previewimage","jpg, gif, png",0, false, "thumbs/");
				switch ($preview_status) {
					case "0": $preview_image = ""; break;
					case "1": $preview_image = ""; break;
					case "2": $preview_image = ""; break;
					default: $preview_image  = $preview_status;
					
					
				}
			
			$sql="INSERT INTO __downloads_links (url, name, description, category, date, user_id, permission, file_type, file_size, mirrors, related_downloads, preview_image) VALUES (
						'".$db->escape($in->get('dl_url'))."', 
						'".$db->escape($in->get('dl_name'))."',
						'".$db->escape($in->get('dl_description'))."',
						'".$db->escape($in->get('dl_category', 0))."',
						NOW(),
						'".$db->escape($user->data['user_id'])."',
						'".$db->escape($in->get('dl_permission'))."',
						'extern',
						'".$db->escape($dlclass->unhuman_size(sanitize($in->get('dl_url_filesize')), sanitize($in->get('dl_url_filesize_unit'))))."',
						'".$db->escape($mirrors)."',
						'".$db->escape($related_downloads)."',
						'".$db->escape($preview_image)."'
						
						)";
						$upload = $db->query($sql);
						
						//Delete Cache
						$pdc->del_suffix('plugin.downloads');
						
						$error_msg = "<script>parent.window.location.href = '".urldecode($redirect_url)."&error=3';</script>";
		
		
		
		}
	}


};


//Some things for javascript
$result = $db->query("SELECT * FROM __downloads_categories WHERE category_permission = 0 ORDER BY category_sortid");
while ($row = $db->fetch_record($result)){
	
	$tpl->assign_block_vars('js_registered_cats', array(					
 			'DL_CATID'				=> sanitize($row['category_id']),

		));

}

// Create the Category List
$cat_list = $dlclass->category_list($in->get('catid'));

$cache_writable = $pcache->CacheWritable();

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_UP_SEND'               			=> $user->lang['dl_up_send'],
	'ROW_CLASS'                			=> $eqdkp->switch_row_class(),
	'DL_CATEGORY_SELECT'               	=> $cat_list,
	'DL_ACCEPTED_FILETYPES'             => sanitize($conf['accepted_file_types']),
 	'DL_UPLOAD_HEADLINE'				=> $user->lang['dl_upload_headline'],
	'DL_PERM_PUBLIC'                 	=> $user->lang['dl_perm_public'],
	'DL_PERM_REGISTERED'            	=> $user->lang['dl_perm_registered'],
	'DL_AD_SEND'           			 	=> $user->lang['dl_ad_send'],
	'DL_AD_RESET'						=> $user->lang['dl_ad_reset'],
	'DL_ERROR'			       			=> $error_msg,
	'DL_DESC_HEADLINE'					=> $user->lang['dl_desc_headline'],
	'DL_NAME_HEADLINE'					=> $user->lang['dl_name_headline'],
	'DL_URL_HEADLINE'					=> $user->lang['dl_url_headline'],
	'DL_PERM_HEADLINE'					=> $user->lang['dl_perm_headline'],
	'DL_CAT_HEADLINE'					=> $user->lang['dl_cat_headline'],
	'DL_SELECT_FILE_HEADLINE'			=> $user->lang['dl_select_file_headline'],
	'SID'								=> $SID,
	$red 								=> '_red',
	'DL_JS_FIELDS_EMPTY'				=> $jquery->Dialog_Alert('fields_empty', $user->lang['dl_ad_fields_empty'], $user->lang['dl_ad_fields_empty_title'],'300', '36'),
	'DL_REFFERRER'						=> ($in->get('ref') == "acp") ? "acp" : "",
	
	'DL_S_CATEGORIES_DISABLED'			=> $categories_disabled,
	'DL_S_MIRRORS_ENABLED'      		=> $mirrors_enabled,
	'DL_S_MULTIPLE_LINKS_ENABLED'      	=> $related_links_enabled,
	'DL_S_HMODE'						=> $hosting_mode,
	'DL_S_CACHE_WRITABLE'				=> $cache_writable,
	
	'DL_AD_ACCEPTED_FILE_TYPES' 		=> $user->lang['dl_ad_conf_accepted_file_types'],
	'DL_FILESIZE_HEADLINE'				=> $user->lang['dl_filesize_headline'],
	'DL_MIRRORS_HEADLINE'				=> $user->lang['dl_l_mirrors'],
	'DL_RELATED_LINKS_HEADLINE'			=> $user->lang['dl_related_links_headline'],
	'DL_PREVIEW_IMAGE'					=> $user->lang['dl_l_preview_image'],
	
	'DL_HELP_MIRRORS'					=> $khrml->HTMLTooltip($user->lang['dl_help_mirrors_upload'], 'pk_tt_help'),
	'DL_HELP_FILESIZE'					=> $khrml->HTMLTooltip($user->lang['dl_help_filesize'], 'pk_tt_help'),
	'DL_HELP_RELATED_LINKS'				=> $khrml->HTMLTooltip($user->lang['dl_help_related_links'], 'pk_tt_help'),
	
	'DL_CACHE_NOT_WRITABLE'				=> $user->lang['dl_l_cache_not_writable'],


));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['dl_ad_manage_config_ov'],
	'template_path'			=> $pm->get_data('downloads', 'template_path'),
	'template_file' 		=> 'admin/upload.html',
	'gen_simple_header'  	=> true,
	'display'       		=> true)
  );

?>
