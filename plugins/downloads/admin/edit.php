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
$user->check_auth('a_downloads_links');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');

$categories_disabled 	= ($conf['disable_categories'] == 1) 	? true : false;
$mirrors_enabled 		= ($conf['enable_mirrors'] == 1) 		? true : false;
$related_links_enabled  = ($conf['enable_related_links'] == 1)  ? true : false;
$hosting_mode = ($_HMODE) ? true : false;

//Make the redirect-url when Download has been edited
if ($in->get('dl_edit_referrer') != ""){
	$redirect_url = sanitize($in->get('dl_edit_referrer'));
	$redirect_url = str_replace('&amp;','&',$redirect_url);
} else {
	if ($categories_disabled == true){
		if ($in->get('file') != ""){
			$redirect_url = "../downloads.php".$SID."&file=".sanitize($in->get('file'));
		} 
		else {
			$redirect_url = "../downloads.php".$SID;
		}
	}
	else {
		if ($in->get('file') != ""){
				$redirect_url = "../downloads.php".$SID."&file=".sanitize($in->get('file'));
		} 
		else {	
			if ($in->get('catid') != ""){
				$redirect_url = "../downloads.php".$SID."&cat=".sanitize($in->get('catid'));
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
	}
}
if (!is_numeric($in->get('id'))){$error_msg = "<script>parent.window.location.href = '".$redirect_url."';</script>";};

// Delete one download
if ($in->get('mode') == 'delete'){
	$dlclass->delete_one_link($in->get('id'));
	$error_msg = "<script>parent.window.location.href = '".$redirect_url."&error=2';</script>";
};

if ($in->get('mode') == 'del_pi'){
	
	$link_cache = $pdc->get('plugin.downloads.links.'.$in->get('id'),false,true);
	if (!$link_cache){
		$data_query = $db->query('SELECT local_filename, preview_image FROM __downloads_links WHERE id='.$db->escape($in->get('id')));
		$data = $db->fetch_record($data_query);
		$pdc->put('plugin.downloads.links.'.$elem,$data,86400,false,true);
	} else{
		$data = $link_cache;
	};


	if ($data['preview_image']){

		if (file_exists($pcache->FilePath('thumbs/'.$data['preview_image'], 'downloads'))) {
			unlink($pcache->FilePath('thumbs/'.$data['preview_image'], 'downloads'));
		};
		
		$db->query("UPDATE __downloads_links SET
				preview_image='',
				date=NOW()
  				WHERE id=".$db->escape($in->get('id')));
		
		//Delete Cache
		$pdc->del_suffix('plugin.downloads');
	};

}

//If a download was edited
if (($in->get('mode') == 'update') && ($in->get(submit) != "")){


		//If the edited download is a local file
		if($in->get('dl_localfile') == true){

		
			if (($in->get('dl_name') != "") || ($in->get('dl_url') != "")){
				
				$mirrors = implode('|', $in->getArray('dl_mirrors', 'string'));
				$related_downloads = str_replace(" ", "", $in->get('dl_related_links'));
				$related_downloads = str_replace(",", "|", $related_downloads);
				
			
				if ($in->get('dl_edit_is_preview') == "true"){
					
					$preview_image = $in->get('dl_edit_previewimage');
					
				} else {
				
					$imagename = $_FILES['dl_edit_previewimage']['name'];
					if ($imagename != ""){
					
						$preview_status = $dlclass->upload_file("dl_edit_previewimage","jpg, gif, png", 0, false, "thumbs/");
							switch ($preview_status) {
								case "0": $preview_image = ""; break;
								case "1": $preview_image = ""; break;
								case "2": $preview_image = ""; break;
								default: $preview_image  = $preview_status;		
					
						}

					}
				
				}
				
				$update_query = $db->query("UPDATE __downloads_links SET
    			name='".$db->escape($in->get('dl_name'))."',
    			description='".$db->escape($in->get('dl_description'))."',
    			category='".$db->escape($in->get('dl_category'))."',
				permission='".$db->escape($in->get('dl_permission'))."',
				related_downloads='".$db->escape($related_downloads)."',
				mirrors='".$db->escape($mirrors)."',
				preview_image='".$db->escape($preview_image)."',
				date=NOW()
  				WHERE id=".$db->escape($in->get('id')));
				
				//Delete Cache
				$pdc->del_suffix('plugin.downloads');
				$error_msg = "<script>parent.window.location.href = '".$redirect_url."&error=1';</script>";
				
			}
			else {$error_msg = $user->lang['dl_ad_fields_empty'];};
		
		} else {
		
			if (($in->get('dl_name') != "") || ($in->get('dl_url') != "")){
				
				$mirrors = implode('|', $in->getArray('dl_mirrors', 'string'));
				$related_downloads = str_replace(" ", "",  $in->get('dl_related_links'));
				$related_downloads = str_replace(",", "|", $related_downloads);
				
				$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
				if ((strlen($file_type) < 2) || strlen($file_type) > 5 ){$file_type = "unknown";};
				
				//Preview Image
				if ($in->get('dl_edit_is_preview') == "true"){
					
					$preview_image = $in->get('dl_edit_previewimage');
					
				} else {
				
					$imagename = $_FILES['dl_edit_previewimage']['name'];
					if ($imagename != ""){
					
						$preview_status = $dlclass->upload_file("dl_edit_previewimage","jpg, gif, png",0, false, "thumbs/");
							switch ($preview_status) {
								case "0": $preview_image = ""; break;
								case "1": $preview_image = $dlclass->trans_chars($_FILES['dl_edit_previewimage']['name']); break;
								case "2": $preview_image = ""; break;
								default: $preview_image  = $preview_status;		
					
						}

					}
				
				}; //END Preview image
				
				
				$update_query = $db->query("UPDATE __downloads_links SET
    			url='".$db->escape($in->get('dl_url'))."',
				name='".$db->escape($in->get('dl_name'))."',
    			description='".$db->escape($in->get('dl_description'))."',
    			category='".$db->escape($in->get('dl_category'))."',
				permission='".$db->escape($in->get('dl_permission'))."',
				related_downloads='".$db->escape($related_downloads)."',
				mirrors='".$db->escape($mirrors)."',
				file_type='".$db->escape($file_type)."',
				file_size='".$db->escape($dlclass->unhuman_size(sanitize($in->get('dl_url_filesize')), sanitize($in->get('dl_url_filesize_unit'))))."',
				preview_image='".$db->escape($preview_image)."',
				date=NOW()
  				WHERE id=".$db->escape($in->get('id')));
				
				//Delete Cache
				$pdc->del_suffix('plugin.downloads');
				$error_msg = "<script>parent.window.location.href = '".$redirect_url."&error=1';</script>";
				
				
			}
			else {$error_msg = $user->lang['dl_ad_fields_empty'];};
		
		}


}; //END Edit-Mode



	//Get data of Download
	//Cache: plugin.downloads.links.{ID}
	$link_cache = $pdc->get('plugin.downloads.links.'.$in->get('id'),false,true);
	if (!$link_cache) {
		$data_query = $db->query('SELECT * FROM __downloads_links WHERE id='.$db->escape($in->get('id')));
		$data = $db->fetch_record($data_query);
		$pdc->put('plugin.downloads.links.'.$in->get('id'),$data,86400,false,true);
	} else {$data = $link_cache;};
	
	
	
	if ($data['mirrors'] != ""){

		$mirror_parts = explode("|", $data['mirrors']);

			foreach ($mirror_parts as $elem){
				
				if($elem != ""){
					
					$tpl->assign_block_vars('mirror_list', array(
						'DL_EDIT_MIRROR'			=> sanitize($elem),
				
					));
					
				}
			}
	
	}
	
	($data['local_filename'] != "") ? $is_localfile = true : $is_localfile = false;
	
  
	$cat_list = $dlclass->category_list(sanitize($data['category']));


//Fill Edit-Area
$tpl->assign_vars(array(
	'DL_S_IS_LOCALFILE'           	=> $is_localfile,
	'DL_S_CATEGORIES_DISABLED'		=> $categories_disabled,
	'DL_S_MIRRORS_ENABLED'      	=> $mirrors_enabled,
	'DL_S_MULTIPLE_LINKS_ENABLED'   => $related_links_enabled,
	'DL_S_PREVIEWIMAGE'				=> ($data['preview_image'] != "") ? true : false,
	'DL_S_HMODE'					=> $hosting_mode,
	
	'DL_EDIT_CATEGORY_SELECT'       => $cat_list,
	'DL_EDIT_ID'			       	=> sanitize($data['id']),
	'DL_EDIT_URL'			       	=> sanitize($data['url']),
	'DL_EDIT_NAME'      		 	=> sanitize($data['name']),
	'DL_EDIT_DESCRIPTION'      		=> sanitize($data['description']),
	'DL_EDIT_CATEGORY'      		=> sanitize($data['category']),
	'DL_EDIT_PREVIEW_IMAGE'			=> sanitize($data['preview_image']),
	'DL_EDIT_RELATED_LINKS'			=> sanitize(str_replace("|", ", ", $data['related_downloads'])),
	'DL_PERM_EDIT_SELECT_0'       	=> ($data['permission'] == 0) ? 'selected="selected"' : '',
	'DL_PERM_EDIT_SELECT_1'       	=> ($data['permission'] == 1) ? 'selected="selected"' : '',
	'DL_LINK_DELETE_URL' 			=> 'edit.php' . $SID.'&mode=delete'.$edit_url.'&id='.$in->get('id'),
	'DL_PREVIEWIMAGE_DELETE_URL' 	=> 'edit.php' . $SID.'&mode=del_pi'.$edit_url.'&id='.$in->get('id'),
	'DL_TITLE_LINK_DELETE'			=> $user->lang['dl_title_link_delete'],
	'DL_EDIT_REFFERER'			    => sanitize($redirect_url),
	'DL_EDIT_FILESIZE'				=> ($data['file_size'] != 0) ? $dlclass->human_size2(sanitize($data['file_size'])) : $dlclass->human_size2(""),

));



//Some things for javascript
$result = $db->query("SELECT * FROM __downloads_categories WHERE category_permission = 0 ORDER BY category_sortid");
while ($row = $db->fetch_record($result)){
	
	$tpl->assign_block_vars('js_registered_cats', array(					
 			'DL_CATID'				=> sanitize($row['category_id']),

		));

}
  

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_UP_SEND'               			=> $user->lang['dl_up_send'],
	'DL_ROW_CLASS'                		=> $eqdkp->switch_row_class(),
	
	'DL_PERM_PUBLIC'                 	=> $user->lang['dl_perm_public'],
	'DL_PERM_REGISTERED'            	=> $user->lang['dl_perm_registered'],
	'DL_AD_SEND'           			 	=> $user->lang['dl_ad_manage_cat_update'],
	'DL_AD_RESET'						=> $user->lang['dl_ad_reset'],
	'DL_ERROR'			       			=> $error_msg,
	'DL_LINKS_HEADLINE'					=> $user->lang['dl_links_headline'],
	'DL_MIRRORS_HEADLINE'				=> $user->lang['dl_l_mirrors'],
	'DL_RELATED_LINKS_HEADLINE'			=> $user->lang['dl_related_links_headline'],
	'DL_DESC_HEADLINE'					=> $user->lang['dl_desc_headline'],
	'DL_NAME_HEADLINE'					=> $user->lang['dl_name_headline'],
	'DL_URL_HEADLINE'					=> $user->lang['dl_url_headline'],
	'DL_URL_HEADLINE'					=> $user->lang['dl_url_headline'],
	'DL_FILESIZE_HEADLINE'				=> $user->lang['dl_filesize_headline'],
	'DL_CAT_HEADLINE'					=> $user->lang['dl_cat_headline'],
	'DL_PERM_HEADLINE'					=> $user->lang['dl_perm_headline'],
	'DL_FILE_HEADLINE'					=> $user->lang['dl_file_headline'],
	'DL_EDIT_HEADLINE'					=> $user->lang['dl_edit_headline'],
	'DL_PREVIEW_IMAGE'					=> $user->lang['dl_l_preview_image'],
	'SID'								=> $SID,
	'DL_JS_FIELDS_EMPTY'				=> $jquery->Dialog_Alert('fields_empty', $user->lang['dl_ad_fields_empty'], $user->lang['dl_ad_fields_empty_title'],'300', '36'),
	
	'DL_HELP_MIRRORS'					=> $khrml->HTMLTooltip($user->lang['dl_help_mirrors_upload'], 'pk_tt_help'),
	'DL_HELP_RELATED_LINKS'				=> $khrml->HTMLTooltip($user->lang['dl_help_related_links'], 'pk_tt_help'),

));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['dl_ad_manage_config_ov'],
	'template_path'			=> $pm->get_data('downloads', 'template_path'),
	'template_file' 		=> 'admin/edit.html',
	'gen_simple_header'  	=> true,
	'display'       		=> true,
));

?>
