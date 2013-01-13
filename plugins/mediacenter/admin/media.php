<?PHP
/*************************************************\
*             mediacenter 4 EQdkp plus              *
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
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_mediacenter_media');


//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');


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

//Until now there are now errors ;-)
$error = false;

//Massedits
if ($in->get('mode') == 'massedit'){


  if ($in->get('mc_mass_option') == 'delete' && $in->get('mass_repair_but') == "" && $in->get('mass_confirm_but') == ""){
	
		foreach ($in->getArray('links', 'int') as $elem) {
			
			//Get if the download is just a link or a local file
			//Cache: plugin.mediacenter.links.{ID}
			$link_cache = $pdc->get('plugin.mediacenter.media.'.$elem,false,true);
			if (!$link_cache){
				$data_query = $db->query("SELECT local_filename FROM __mediacenter_media WHERE id='".$db->escape($elem)."'");
				$data = $db->fetch_record($data_query);
				$pdc->put('plugin.mediacenter.links.'.$elem,$data,86400,false,true);
			} else{
				$data = $link_cache;
			};

			if ($data['local_filename']){
				if (file_exists($pcache->FilePath('files/'.$data['local_filename'], 'mediacenter'))) {
					unlink($pcache->FilePath('files/'.$data['local_filename'], 'mediacenter'));
				};
			};
			
			
			if ($data['thumbnail']){
				if (file_exists($pcache->FilePath('thumbs/'.$data['thumbnail'], 'mediacenter'))) {
					unlink($pcache->FilePath('thumbs/'.$data['thumbnail'], 'mediacenter'));
				};
			};
			
			if ($data['preview_image']){
				if (file_exists($pcache->FilePath('thumbs/'.$data['preview_image'], 'mediacenter'))) {
					unlink($pcache->FilePath('thumbs/'.$data['preview_image'], 'mediacenter'));
				};
			};
			
			
			$del_query = $db->query("DELETE FROM __mediacenter_media WHERE id='".$db->escape($elem)."'");
		}
		System_Message($user->lang['mc_delete_success'],'EQdkp-plus MediaCenter','green');
		
		//Delete Cache
		$pdc->del_suffix('plugin.mediacenter');
  	};

	if ($in->get('mc_mass_option') == 'move' && is_numeric($in->get('target_cat'))){

		foreach ($in->getArray('links', 'int') as $elem) {
			$sql = $db->query("UPDATE __mediacenter_media SET category = '".$db->escape($in->get('target_cat'))."' WHERE id ='".$db->escape($elem)."'"); 
		};
		//Delete Cache
		$pdc->del_suffix('plugin.mediacenter');
		System_Message($user->lang['mc_move_success'],'EQdkp-plus MediaCenter','green');
	}
	
	if ($in->get('mass_confirm_but') != ""){
		foreach ($in->getArray('links', 'int') as $elem) {
			$sql = $db->query("UPDATE __mediacenter_media SET status = '1' WHERE id ='".$db->escape($elem)."'"); 
		};
	}
	
	if ($in->get('mass_repair_but') != ""){
		foreach ($in->getArray('links', 'int') as $elem) {
			$sql = $db->query("UPDATE __mediacenter_media SET reported = NULL WHERE id ='".$db->escape($elem)."'"); 
		};
	}
}


// Delete one download
if ($in->get('mode') =='delete'){
	System_Message($mcclass->delete_one_link($in->get('id')),'EQdkp-plus MediaCenter','green');
	$pdc->del_suffix('plugin.mediacenter');
}

// Confirm one download
if ($in->get('mode') =='confirm' && is_numeric($in->get('id'))){
	$db->query("UPDATE __mediacenter_media SET status = '1' WHERE id ='".$db->escape($in->get('id'))."'");
	$pdc->del_suffix('plugin.mediacenter');
	
}

// Repair one download
if ($in->get('mode') =='repaired' && is_numeric($in->get('id'))){
	$db->query("UPDATE __mediacenter_media SET reported = NULL WHERE id ='".$db->escape($in->get('id'))."'");
	$pdc->del_suffix('plugin.mediacenter');
	
}


//Sort Categories
$order = $in->get('o', '0.0');
$red = 'RED'.str_replace('.', '', $order);

$sort_order = array(
	0 => array('date desc', 'date'),			
    1 => array('file_type', 'file_type desc'),
    2 => array('description', 'description desc'),
    3 => array('views', 'views desc'),
	4 => array('permission', 'permission desc'),
	5 => array('file_size desc', 'file_size'),
);

$current_order = switch_order($sort_order);

// Select reported Videos
$reported_query = $db->query('SELECT * FROM __mediacenter_media WHERE reported !="" ORDER BY '.$db->escape($current_order['sql']));
$reported_count = $db->affected_rows();
$reported_data = $db->fetch_record_set($reported_query);


			if (is_array($reported_data)) {
				foreach ($reported_data as $elem){
						$tpl->assign_block_vars('reported_list', array(
 						'LINK_NAME'				=> sanitize($elem['name']),
						'LINK_DESC' 			=> $html->ToolTip(sanitize($elem['description']), $mcclass->wrapText(sanitize($elem['description']), 60)),
						'LINK_VIEWS' 			=> sanitize($elem['views']),
						'LINK_DATE' 			=> sanitize($elem['date']),
						'LINK_ID' 				=> sanitize($elem['id']),
						'LINK_DELETE_URL' 		=> 'media.php' . $SID.'&mode=delete&id='.sanitize($elem['id']),
						'LINK_REPAIR_URL' 		=> 'media.php' . $SID.'&mode=repaired&id='.sanitize($elem['id']),
						'ROW_CLASS'				=> $eqdkp->switch_row_class(),
						'L_TITLE_LINK_DELETE'	=> $user->lang['mc_delete'],
						'L_TITLE_LINK_EDIT'		=> $user->lang['mc_edit'],
						
						));
				}; //End foreach
			};
			
// Select reported Videos
$unconfirmed_query = $db->query("SELECT * FROM __mediacenter_media WHERE status ='0' ORDER BY ".$db->escape($current_order['sql']));
$unconfirmed_count = $db->affected_rows();
$unconfirmed_data = $db->fetch_record_set($unconfirmed_query);


			if (is_array($unconfirmed_data)) {
				foreach ($unconfirmed_data as $elem){
						$tpl->assign_block_vars('unconfirmed_list', array(
 						'LINK_NAME'				=> sanitize($elem['name']),
						'LINK_DESC' 			=> $html->ToolTip(sanitize($elem['description']), $mcclass->wrapText(sanitize($elem['description']), 60)),
						'LINK_VIEWS' 			=> sanitize($elem['views']),
						'LINK_DATE' 			=> sanitize($elem['date']),
						'LINK_ID' 				=> sanitize($elem['id']),
						'LINK_DELETE_URL' 		=> 'media.php' . $SID.'&mode=delete&id='.sanitize($elem['id']),
						'LINK_CONFIRM_URL' 		=> 'media.php' . $SID.'&mode=confirm&id='.sanitize($elem['id']),
						'ROW_CLASS'				=> $eqdkp->switch_row_class(),
						'L_TITLE_LINK_DELETE'	=> $user->lang['mc_delete'],
						'L_TITLE_LINK_EDIT'		=> $user->lang['mc_edit'],
						
						));
				}; //End foreach
			};			
$tpl->assign_vars(array(
			
			'L_NAME_HEADLINE'			=> $user->lang['mc_name'],
			'L_DESC_HEADLINE'			=> $user->lang['mc_description'],
			'L_DATE_HEADLINE'			=> $user->lang['mc_date'],
			'L_VIEWS_HEADLINE'			=> $user->lang['mc_views'],
			'L_ACTION_HEADLINE'			=> $user->lang['mc_action'],
			'L_REPORTED'				=> $user->lang['mc_reported'],
			'L_UNCONFIRMED'				=> $user->lang['mc_unconfirmed'],
			'L_CONFIRM'					=> $user->lang['mc_confirm'],
			'S_REPORTED'				=> ($reported_count > 0) ? true : false,
			'S_UNCONFIRMED'				=> ($unconfirmed_count > 0) ? true : false,
			
		));



// List Categories


		//Cache: plugin.mediacenter.categories
		$categories_cache = $pdc->get('plugin.mediacenter.categories',false,true);
		if (!$categories_cache){
			$result = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid");
			$result = $db->fetch_record_set($result);
			$pdc->put('plugin.mediacenter.categories',$result,86400,false,true);

		} else{
			$result = $categories_cache;
		};
	
	if (count($result) == 0){$if_cats_exist = false; $error_msg = $user->lang['mc_no_categories'];} else {$if_cats_exist = true;};
	

	if (is_array($result)) {
	foreach ($result as $row){
			
			//Get all mediacenter in a category
			//Cache: plugin.mediacenter.links_in_cat.{CATID}.{ORDER}
			$links_in_cat_cache = $pdc->get('plugin.mediacenter.media_in_cat.'.$row['category_id'].".o".$order,false,true);
			if (!$links_in_cat_cache){
				$links = $db->query("SELECT * FROM __mediacenter_media WHERE category = ".$db->escape($row['category_id'])." AND status = '1' ORDER BY ".$db->escape($current_order['sql']));
				$dlrow = $db->fetch_record_set($links);
				$pdc->put('plugin.mediacenter.media_in_cat.'.$row['category_id'].".o".$order,$dlrow,86400,false,true);
			} else{
				$dlrow = $links_in_cat_cache;
			};
			

		$dlcount = count($dlrow);
		$nolinks = true;
		if ($dlcount > 0){$nolinks = false;}


		$mediacenter_footcount = sprintf($user->lang['mc_videos_in_cat_footcount'], $dlcount);

		
		$tpl->assign_block_vars('index_list', array(
								
 			'CAT_ID'					=> sanitize($row['category_id']),
			'CAT_NAME'					=> sanitize($row['category_name']),
			'CAT_COMMENT'				=> sanitize($row['category_comment']),
			'CAT_FILECOUNT'				=> sanitize($dlcount),
			'CAT_VIEWS'					=> sanitize($views),
			'CAT_FOOTCOUNT'				=> sanitize($mediacenter_footcount),
			'S_NOLINKS'					=> sanitize($nolinks),
			
			'L_CAT_HEADLINE'			=> $user->lang['mc_category'],	
			'L_NOLINKS'					=> $user->lang['mc_cat_novideos'],

			'L_NAME_HEADLINE'			=> $user->lang['mc_name'],
			'L_DESC_HEADLINE'			=> $user->lang['mc_description'],
			'L_DATE_HEADLINE'			=> $user->lang['mc_date'],
			'L_VIEWS_HEADLINE'			=> $user->lang['mc_views'],
			'L_ACTION_HEADLINE'			=> $user->lang['mc_action'],
			
		));

			if (is_array($dlrow)) {
				foreach ($dlrow as $elem){
						$tpl->assign_block_vars('index_list.link_list', array(
 						'LINK_NAME'				=> sanitize($elem['name']),
						'LINK_DESC' 			=> $html->ToolTip(sanitize($elem['description']), $mcclass->wrapText(sanitize($elem['description']), 60)),
						'LINK_VIEWS' 			=> sanitize($elem['views']),
						'LINK_DATE' 			=> sanitize($elem['date']),
						'LINK_ID' 				=> sanitize($elem['id']),
						'LINK_DELETE_URL' 		=> 'media.php' . $SID.'&mode=delete&id='.sanitize($elem['id']),
						'ROW_CLASS'				=> $eqdkp->switch_row_class(),
						'L_TITLE_LINK_DELETE'	=> $user->lang['mc_delete'],
						'L_TITLE_LINK_EDIT'		=> $user->lang['mc_edit'],
						'LINK_REPORTED'		=> (unserialize($elem['reported'])) ? '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_l_reported_info'].'">' : '',
						));
				}; //End foreach
			};

			
			
			
	}; //End foreach


};


//If an error with the download occures
			switch ($in->get('error')){
				case "1": System_Message($user->lang['mc_ad_update_success'],'mediacenter 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['mc_ad_delete_success'],'mediacenter 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['mc_ad_upload_success'],'mediacenter 4 EQdkp-plus','green');
					break;
			}

$massedit_action_dropdown = array(

			'delete'  	  	=> $user->lang['mc_delete'],
			'move'			=>$user->lang['mc_move']

		);

foreach($result as $elem){
	$target_cat_array[$elem['category_id']] = $elem['category_name'];
}
	
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
	

// Send the Output to the template Files.
$tpl->assign_vars(array(

	
	'ROW_CLASS'                			=> $eqdkp->switch_row_class(),
	'L_MANAGE_VIDEOS'                   => $user->lang['mc_manage_videos'],
	'L_VIDEOS'                    		=> $user->lang['mc_videos'],
	'MASSEDIT_ACTION'            		=> 'media.php' . $SID.'&mode=massedit',
	'L_GO'           			 		=> $user->lang['mc_go'],
	'L_TO'			       				=> $user->lang['mc_to'],
	'L_ALL_MARKED'						=> $user->lang['mc_all_marked'],
	'L_SELECT_ALL'						=> $user->lang['mc_select_all'],
	'L_DESELECT_ALL'					=> $user->lang['mc_deselect_all'],
	
	'MASSEDIT_ACTION_DROPDOWN'      	=> $khrml->DropDown('mc_mass_option', $massedit_action_dropdown, 'confirm', '', 'onChange="mc_check_action_dropdown()"', 'input', 'massedit_action_drpdwn'),
	
	'TARGET_CAT_DROPDOWN'  => $khrml->DropDown('target_cat', $target_cat_array, '', '', '', 'input', 'target_drpdwn'),
	
	'MC_ERROR'			       			=> $error_msg,
	

	'SID'								=> $SID,
	$red 								=> '_red',
	'S_IF_CATS'							=> $if_cats_exist,

	'L_SEARCH_INPUTVALUE'				=> $user->lang['mc_search_inputvalue'],
	'UPDATE_BOX'              			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  				=> $rbvcheck->OutputHTML(),
	'MC_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['mc_edit'], "../edit.php".$SID."&ref=acp&id='+id+'", '750', '400', 'media.php'),
	'MC_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['mc_upload'], "../upload.php".$SID."&ref=acp&cat='+catid+'", '750', '400', 'media.php'),
	'MC_ADMIN_MENU'       	=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),

	

));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['mediacenter']." - ".$user->lang['mc_ad_manage_media_ov'],
	'template_path'			=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 		=> 'admin/media.html',
	'display'       		=> true)
  );

?>
