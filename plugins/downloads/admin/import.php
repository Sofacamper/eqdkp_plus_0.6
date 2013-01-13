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
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

if ($_HMODE){message_die($user->lang['noauth_default_title'], $user->lang['noauth_default_title']);};

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Downloads-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_downloads_import');
($user->check_auth('u_downloads_upload', false) == true) ? $u_is_admin_upload = true : $u_is_admin_upload = false;



//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');


$cats_disabled = ($conf['disable_categories'] == 1) ? true : false;

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('downloads', '', 'downloads_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'downloads_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'downloads',
      'version' => $pm->get_data('downloads', 'version'),
      'build' 	=> $pm->plugins['downloads']->build,
      'vstatus' => $pm->plugins['downloads']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rbvcheck->PerformUpdateCheck();


//Massedits
if ($in->get('mode') == 'import'){
	
  if ($in->get('dl_mass_option') == 'delete'){
	
		foreach ($in->getArray('links', 'string') as $elem) {
			

				if (file_exists($pcache->FilePath('files/'.$elem, 'downloads'))) {
					unlink($pcache->FilePath('files/'.$elem, 'downloads'));
				};
		}
		System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
		
		//Delete Cache
		$pdc->del_suffix('plugin.downloads');
  	};
	
	//IMPORT IT
	if ($in->get('dl_mass_option') == 'import'){

		$name = $in->getArray('name', 'string');
		$desc = $in->getArray('desc', 'string');
		$cat = $in->getArray('cat', 'string');
		$perm = $in->getArray('perm', 'string');
		$size = $in->getArray('size', 'string');
		
		foreach ($in->getArray('links', 'string') as $elem) {
			$filename = $elem;
			$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
			if ((strlen($file_type) < 2) || strlen($file_type) > 5 ){$file_type = "unknown";};
			
			$local_filename = $dlclass->generate_hash();
			$rename = rename($pcache->FilePath('files', 'downloads')."/".$elem, $pcache->FilePath('files', 'downloads')."/".$local_filename);

			if (!$rename){$local_filename = $elem;};
			
			if ($name[$elem] != ""){
				$sql="INSERT INTO __downloads_links (url, local_filename, name, description, category, date, user_id, permission, file_type, file_size) VALUES (
						'".$db->escape($elem)."',
						'".$db->escape($local_filename)."', 
						'".$db->escape($name[$elem])."',
						'".$db->escape($desc[$elem])."',
						'".$db->escape($cat[$elem])."',
						NOW(),
						'".$db->escape($user->data['user_id'])."',
						'".$db->escape($perm[$elem])."',
						'".$db->escape($file_type)."',
						'".$db->escape($size[$elem])."'
						
						
						)";
						$upload = $db->query($sql);
			} else {
				$error_msg = $user->lang['dl_ad_fields_empty'];
			}
		
		};
		//Delete Cache
		$pdc->del_suffix('plugin.downloads');
		System_Message($user->lang['dl_import_success'],'Downloads 4 EQdkp-plus','green');
	}
}


// Delete one download
if ($in->get('mode') =='delete'){
	if (file_exists($pcache->FilePath('files/'.$in->get('file'), 'downloads'))) {
		unlink($pcache->FilePath('files/'.$in->get('file'), 'downloads'));
	};
	System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
	$pdc->del_suffix('plugin.downloads');
}


// Create the Category List
$categories_cache = $pdc->get('plugin.downloads.categories',false,true);
	if (!$categories_cache){
		$catlist_query = $db->query('SELECT * FROM __downloads_categories');
		$new_catlist = $db->fetch_record_set($catlist_query);
		$pdc->put('plugin.downloads.categories',$data,86400,false,true);
	} else{
		$new_catlist = $categories_cache;
	};
	foreach ($new_catlist as $elem){
		$category[$elem['category_id']] = $elem['category_name'];
	}

$permission[0] = $user->lang['dl_perm_registered'];
$permission[1] = $user->lang['dl_perm_public'];
// Get all Files from the upload-folder

$path = $pcache->FolderPath('downloads/files');
if($dir=opendir($path))
{
 while($file=readdir($dir))
 {
  if (!is_dir($file) && $file != "." && $file != ".." && $file != "index.html" && $file != ".htaccess")
  {
   $files[$file]=$file;
   $sizes[$file] = filesize($path."/".$file);
   $times[$file] = filemtime($path."/".$file);
  }
 }
closedir($dir);
}

//Get all files from the DB
$downloads = $db->query('SELECT * FROM __downloads_links WHERE local_filename != ""');

while ($elem = $db->fetch_record($downloads)){

	if (in_array($elem['local_filename'], $files)){

		unset($files[$elem['local_filename']]);
	}
}

if (is_array($files)) {
				foreach ($files as $key => $elem){
						$file_type = substr($key, strrpos($key, '.') + 1);
												
						$tpl->assign_block_vars('import_list', array(
							'NAME'			=> sanitize($elem),
							'TYPE'			=> $file_type,
							'TYPE_IMG'		=> $dlclass->extension_image($file_type),
							'HUMAN_SIZE'	=> $dlclass->human_size($sizes[$key]),
							'SIZE' 			=> sanitize($sizes[$key]),
							'DATE' 			=> date("Y-m-d H:m",sanitize($times[$key])),
							'ROW_CLASS'		=> $eqdkp->switch_row_class(),
							'CATEGORY_SELECT'	=> $khrml->DropDown('cat['.sanitize($key).']', $category, '', '', 'onChange="dl_check_perm(\''.sanitize($key).'\')"', 'input', 'cat['.sanitize($key).']'),
							'PERMISSION_SELECT' => $khrml->DropDown('perm['.sanitize($key).']', $permission, '', '', '', 'input', 'perm['.sanitize($key).']'),
							
						));
				}; //End foreach
};
$s_no_files = (count($files) > 0) ? false : true; 
//If an error with the download occures
			switch ($in->get('error')){
				case "1": System_Message($user->lang['dl_ad_update_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['dl_ad_upload_success'],'Downloads 4 EQdkp-plus','green');
					break;
			}

$massedit_action_dropdown = array(

			'delete'  	  => $user->lang['dl_ad_delete'],

		);

if ($cats_disabled == false){
	$massedit_action_dropdown['move'] = $user->lang['dl_ad_move'];
}
	
//Admin-Menu	
$admin_optionsarray = array(
 
              0 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              1 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'downloads.php'.$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              3 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			  			  4 => array(
                  'name'    => $user->lang['dl_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_downloads_import', false),
                  ),
			  
             
);
$massedit_action_dropdown = array(

			'delete'  	  => $user->lang['dl_ad_delete'],
			'import'	=> $user->lang['dl_import'],

		);

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
	'ADMIN_MENU'       					=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
	'DL_PERM_PUBLIC'                 	=> $user->lang['dl_perm_public'],
	'DL_PERM_REGISTERED'            	=> $user->lang['dl_perm_registered'],
	'DL_MASSEDIT_ACTION'            	=> 'downloads.php' . $SID.'&mode=massedit',
	'DL_AD_GO'           			 	=> $user->lang['dl_ad_go'],
	'S_CATS_DISABLED'					=> $cats_disabled,
	'DL_ALL_MARKED'						=> $user->lang['dl_ad_all_marked'],
	'DL_AD_SELECT_ALL'					=> $user->lang['dl_ad_select_all'],
	'DL_AD_DESELECT_ALL'				=> $user->lang['dl_ad_deselect_all'],
	'MASSEDIT_ACTION_DROPDOWN'      	=> $khrml->DropDown('dl_mass_option', $massedit_action_dropdown, 'import', '', '', 'input', 'massedit_action_drpdwn'),

	'S_NO_FILES'						=> $s_no_files,
	'DL_ERROR'			       			=> $error_msg,
	'L_IMPORT_HEADLINE'					=> $user->lang['dl_import_headline'],
	'L_NAME'							=> $user->lang['dl_name_headline'],
	'L_DESC'							=> $user->lang['dl_desc_headline'],
	'L_PERMISSION'						=> $user->lang['dl_perm_headline'],
	'L_DATE'							=> $user->lang['dl_date_headline'],
	'L_CATEGORY'						=> $user->lang['dl_cat_headline'],
	'L_SIZE'							=> $user->lang['dl_filesize_headline'],
	'L_TYPE'							=> $user->lang['dl_type_headline'],
	'L_ACTION'							=> $user->lang['dl_action_headline'],
	'L_NO_FILES'						=> $user->lang['dl_import_no_files'],
	'L_IMPORT_INFO'						=> $user->lang['dl_import_info'],
	'L_DELETE'							=> $user->lang['dl_ad_delete'],
	'IMPORT_FOLDER'						=> $pcache->FileLink('files', 'downloads'),
	'FOOTCOUNT'							=> sprintf($user->lang['dl_import_footcount'], count($files)),
	'SID'								=> $SID,
	$red 								=> '_red',
	
	'UPDATE_BOX'              			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  				=> $rbvcheck->OutputHTML(),
	'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "edit.php".$SID."&ref=acp&id='+id+'", '750', '400', 'downloads.php'),
	'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "upload.php".$SID."&ref=acp&catid='+catid+'", '750', '400', 'downloads.php'),

	

));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_import_headline'],
	'template_path'			=> $pm->get_data('downloads', 'template_path'),
	'template_file' 		=> 'admin/import.html',
	'display'       		=> true)
  );

?>
