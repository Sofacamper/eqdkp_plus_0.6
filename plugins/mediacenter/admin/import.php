<?PHP
 /*
 * Project:     MediaCenter for EQDKPlus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date$
 * ----------------------------------------------------------------------- 
 * @author      $Author$
 * @copyright   2009 GodMod
 * @link        http://eqdkp-plus.com
 * @package     MediaCenter
 * @version     $Rev$
 * 
 * $Id$
 */

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

if ($_HMODE){message_die($user->lang['noauth_default_title'], $user->lang['noauth_default_title']);};

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The Mediacenter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_mediacenter_import');
($user->check_auth('u_mediacenter_upload', false) == true) ? $u_is_admin_upload = true : $u_is_admin_upload = false;



//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');


$cats_disabled = ($conf['disable_categories'] == 1) ? true : false;

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('mediacenter', '', 'mediacenter_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'mediacenter_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'mediacenter',
      'version' => $pm->get_data('mediacenter', 'version'),
      'build' 	=> $pm->plugins['mediacenter']->build,
      'vstatus' => $pm->plugins['mediacenter']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rbvcheck->PerformUpdateCheck();


//Massedits
if ($in->get('mode') == 'import'){
	
  if ($in->get('mc_mass_option') == 'delete'){
	
		foreach ($in->getArray('links', 'string') as $elem) {
			

				if (file_exists($pcache->FilePath('videos/'.$elem, 'mediacenter'))) {
					unlink($pcache->FilePath('videos/'.$elem, 'mediacenter'));
				};
		}
		System_Message($user->lang['mc_delete_success'],'EQdkp-MediaCenter','green');
		
		//Delete Cache
		$pdc->del_prefix('plugin.mediacenter');
  	};
	
	//IMPORT IT
	if ($in->get('mc_mass_option') == 'import'){

		$name = $in->getArray('name', 'string');
		$desc = $in->getArray('desc', 'string');
		$cat = $in->getArray('cat', 'string');
		$duration = $in->getArray('duration', 'string');
		
		foreach ($in->getArray('links', 'string') as $elem) {
			$filename = $elem;
			$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
			if ((strlen($file_type) < 2) || strlen($file_type) > 5 ){$file_type = "unknown";};
			
			
			if ($name[$elem] != ""){
				$sql="INSERT INTO __mediacenter_media (url, local_filename, name, description, category, date, user_id, type, extension, duration, status) VALUES (
						'".$db->escape($elem)."',
						'".$db->escape($elem)."', 
						'".$db->escape($name[$elem])."',
						'".$db->escape($desc[$elem])."',
						'".$db->escape($cat[$elem])."',
						NOW(),
						'".$db->escape($user->data['user_id'])."',

						'video',
						'".$db->escape($file_type)."',
						'".$db->escape($duration[$elem])."',
						'1'
						)";
						$upload = $db->query($sql);
						
			} else {
				$error_msg = $user->lang['mc_fields_empty'];
			}
		
		};
		//Delete Cache
		$pdc->del_prefix('plugin.mediacenter');
		System_Message($user->lang['mc_import_success'],'EQdkp-MediaCenter','green');
	}
}


// Delete one download
if ($in->get('mode') == 'delete'){
	if (file_exists($pcache->FilePath('videos/'.$in->get('file'), 'mediacenter'))) {
		unlink($pcache->FilePath('videos/'.$in->get('file'), 'mediacenter'));
	};
	System_Message($user->lang['mc_delete_success'],'EQdkp-Mediacenter','green');
	$pdc->del_prefix('plugin.mediacenter');
}


// Create the Category List
$categories_cache = $pdc->get('plugin.mediacenter.categories',false,true);
	if (!$categories_cache){
		$catlist_query = $db->query('SELECT * FROM __mediacenter_categories');
		$new_catlist = $db->fetch_record_set($catlist_query);
		$pdc->put('plugin.mediacenter.categories',$data,86400,false,true);
	} else{
		$new_catlist = $categories_cache;
	};
	foreach ($new_catlist as $elem){
		$category[$elem['category_id']] = $elem['category_name'];
	}


// Get all Files from the upload-folder
$extensions = array("wma", "wmv", "flv", "swf", "avi", "dixv", "mkv");

$path = $pcache->FolderPath('mediacenter/videos');
if($dir=opendir($path))
{
 while($file=readdir($dir))
 {
  if (!is_dir($file) && $file != "." && $file != ".." && $file != "index.html" && $file != ".htaccess")
  {
	  $file_type = strtolower(substr($file, strrpos($file, '.') + 1));

   if (in_array($file_type, $extensions)){
	    $files[$file]=$file;
   		$sizes[$file] = filesize($path."/".$file);
   		$times[$file] = filemtime($path."/".$file);
   }
  }
 }
closedir($dir);
}

//Get all files from the DB
$downloads = $db->query('SELECT * FROM __mediacenter_media WHERE local_filename != ""');

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
							
							'SIZE' 			=> sanitize($sizes[$key]),
							'DATE' 			=> date("Y-m-d H:m",sanitize($times[$key])),
							'ROW_CLASS'		=> $eqdkp->switch_row_class(),
							'CATEGORY_SELECT'	=> $khrml->DropDown('cat['.sanitize($key).']', $category, '', '', 'onChange="dl_check_perm(\''.sanitize($key).'\')"', 'input', 'cat['.sanitize($key).']'),
							
						));
				}; //End foreach
};
$s_no_files = (count($files) > 0) ? false : true; 


$massedit_action_dropdown = array(

			'delete'  	  => $user->lang['mc_delete'],

		);

if ($cats_disabled == false){
	$massedit_action_dropdown['move'] = $user->lang['mc_move'];
}
	
//Admin-Menu	
	$admin_optionsarray = array(
              
			  1 => array(
                  'name'    => $user->lang['mc_manage_videos'],
                  'link'    => 'media.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  2 => array(
                  'name'    => $user->lang['mc_manage_categories'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  3 => array(
                  'name'    => $user->lang['mc_stats'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_mediacenter_stats', false),
                  ),                            
			              
			  4 => array(
                  'name'    => $user->lang['mc_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_mediacenter_cfg', false),
                  ),
			  5 => array(
                  'name'    => $user->lang['mc_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_mediacenter_import', false),
                  ),

              
             
      );
$massedit_action_dropdown = array(

			'delete'  	  => $user->lang['mc_delete'],
			'import'	=> $user->lang['mc_import'],

		);

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
	'MC_ADMIN_MENU'       				=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),


	'DL_MASSEDIT_ACTION'            	=> 'downloads.php' . $SID.'&mode=massedit',
	'DL_AD_GO'           			 	=> $user->lang['mc_go'],

	'DL_ALL_MARKED'						=> $user->lang['mc_all_marked'],
	'DL_AD_SELECT_ALL'					=> $user->lang['mc_select_all'],
	'DL_AD_DESELECT_ALL'				=> $user->lang['mc_deselect_all'],
	'MASSEDIT_ACTION_DROPDOWN'      	=> $khrml->DropDown('mc_mass_option', $massedit_action_dropdown, 'import', '', '', 'input', 'massedit_action_drpdwn'),

	'S_NO_FILES'						=> $s_no_files,
	'DL_ERROR'			       			=> $error_msg,
	'L_IMPORT_HEADLINE'					=> $user->lang['mc_import_headline'],
	'L_NAME'							=> $user->lang['mc_name'],
	'L_DESC'							=> $user->lang['mc_description'],
	'L_DATE'							=> $user->lang['mc_date'],
	'L_CATEGORY'						=> $user->lang['mc_category'],
	
	'L_SIZE'							=> $user->lang['dl_filesize_headline'],
	
	'L_TYPE'							=> $user->lang['mc_type'],
	'L_ACTION'							=> $user->lang['mc_action'],
	'L_NO_FILES'						=> $user->lang['mc_import_no_files'],
	'L_IMPORT_INFO'						=> $user->lang['mc_import_info'],
	'L_DELETE'							=> $user->lang['mc_delete'],
	'L_DURATION'						=> $user->lang['mc_duration'],
	'IMPORT_FOLDER'						=> $pcache->FileLink('videos', 'mediacenter'),
	'FOOTCOUNT'							=> sprintf($user->lang['mc_import_footcount'], count($files)),
	'SID'								=> $SID,
	$red 								=> '_red',
	
	'UPDATE_BOX'              			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  				=> $rbvcheck->OutputHTML(),
	'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "edit.php".$SID."&ref=acp&id='+id+'", '750', '400', 'downloads.php'),
	'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "upload.php".$SID."&ref=acp&catid='+catid+'", '750', '400', 'downloads.php'),

	

));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_mediacenter']." - ".$user->lang['mc_import_headline'],
	'template_path'			=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 		=> 'admin/import.html',
	'display'       		=> true)
  );

?>
