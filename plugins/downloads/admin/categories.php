<?PHP
  /**********************************************************************************\
  * Project:   Downloads for EQdkp-Plus                                              *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Project-Start:	 05/2009                                                         *
  * Author:    GodMod                                                                *
  * Copyright: 2009 GodMod                                                           *
  * Link:      http://eqdkp-plus.com/forum                                           *
  * Package:   Downloads                                                             *
  \**********************************************************************************/
  
  //Thanks to BadTwin for this Code (from the Gallery)

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Downloads-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_downloads_cat');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');

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


$cats_disabled = ($conf['disable_categories'] == 1) ? true : false;

if ($cats_disabled == true){

	$tpl->assign_vars(array(
	'DL_AD_CATS_DISABLED'            	=> $user->lang['dl_ad_cats_disabled'],
	));
	
}
else {

	// Change the Title and the Comment and the Permission
	if ($in->get('cat_update_id') != ""){
  		$title_up_query = $db->query("UPDATE __downloads_categories SET category_name = '".$db->escape($in->get('cat_up_name'))."' WHERE category_id = ".$db->escape($in->get('cat_update_id')));
 		$descr_up_query = $db->query("UPDATE __downloads_categories SET category_comment = '".$db->escape($in->get('cat_up_description'))."' WHERE category_id = ".$db->escape($in->get('cat_update_id')));
   		$perm_up_query = $db->query("UPDATE __downloads_categories SET category_permission = '".$db->escape($in->get('cat_up_permission'))."' WHERE category_id = ".$db->escape($in->get('cat_update_id')));
		//Delete Cache
		$pdc->del_suffix('plugin.downloads');
	}


	// Move the Category up
	if ($in->get('cat_up') != ""){
  		$cat_up_query = $db->query("SELECT * FROM __downloads_categories ORDER BY category_sortid");
  		while ($prev_cat = $db->fetch_record($cat_up_query)) {
  			if ($prev_cat['category_sortid'] < $in->get('cat_up')) {
   	  		$prev_category = $prev_cat['category_sortid'];
    		} else {
      		$last_category = $prev_cat['category_sortid'];
    		}
  		}
  		$temp_last_cat = $last_category + 1;
  		$temp_last_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($temp_last_cat)."' WHERE category_sortid = '".$db->escape($prev_category)."'");

  		$temp_up_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($prev_category)."' WHERE category_sortid = '".$db->escape($in->get('cat_up'))."'");

  		$temp_down_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($in->get('cat_up'))."' WHERE category_sortid = '".$db->escape($temp_last_cat)."'");
	//Delete Cache
	$pdc->del_suffix('plugin.downloads');
	};

	// Move the Category down
	if ($in->get('cat_down') != ""){
  		$cat_down_query = $db->query("SELECT * FROM __downloads_categories ORDER BY category_sortid DESC");
  		while ($next_cat = $db->fetch_record($cat_down_query)) {
  			if ($next_cat['category_sortid'] > $in->get('cat_down')) {
   	  		$next_category = $next_cat['category_sortid'];
    		} else {
      		$last_cat_query = $db->query("SELECT * FROM __downloads_categories ORDER BY category_sortid DESC");
      		$last_category = $db->fetch_record ($last_cat_query);
    		}
  		}
  		$temp_last_cat = $last_category['category_sortid'] + 1;
  		$temp_last_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($temp_last_cat)."' WHERE category_sortid = '".$db->escape($next_category)."'");

  		$temp_up_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($next_category)."' WHERE category_sortid = '".$db->escape($in->get('cat_down'))."'");

  		$temp_down_category = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($in->get('cat_down'))."' WHERE category_sortid = '".$db->escape($temp_last_cat)."'");
	//Delete Cache
	$pdc->del_suffix('plugin.downloads');
	};

	
	// Create new Category
	if($in->get('cat_name') != ''){
		//Delete Cache
		$pdc->del_suffix('plugin.downloads');
		$new_cat = $db->query("INSERT INTO __downloads_categories (category_name, category_comment, category_permission) VALUES 
			('".$db->escape($in->get('cat_name'))."', 
			'".$db->escape($in->get('cat_comment'))."', 
			'".$db->escape($in->get('cat_permission'))."')");
		$catid = $db->sql_lastid();
		$set_sort_id = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($catid)."' WHERE category_id = '".$db->escape($catid)."'");
		$op_output .= '<i>'.sanitize($in->get('cat_name')).'</i>'.$user->lang['dl_ad_created'];
		
	};

	//Delete Categories
	if($in->get('delete') != "") {
		
		if($in->get('delete') == "all") {
		
			$file_list_query = $db->query("SELECT * FROM __downloads_links");
  
  			while ($file_list = $db->fetch_record($file_list_query)){ 
    		
				$dlclass->delete_one_link($file_list['id']);
  			};
		
			$delete = $db->query("DELETE FROM __downloads_categories");
			$op_output .= $user->lang['dl_ad_cat_all_deleted'];
			//Delete Cache
			$pdc->del_suffix('plugin.downloads');
		
		} else {
		
			$del_cat_query = $db->query("SELECT * FROM __downloads_categories WHERE category_id = " . $db->escape($in->get('delete')));
  			$del_cat = $db->fetch_record($del_cat_query);
    		$file_list_query = $db->query("SELECT * FROM __downloads_links WHERE category = " . $db->escape($in->get('delete')));
  
  			while ($file_list = $db->fetch_record($file_list_query)){ 
    		
				$dlclass->delete_one_link($file_list['id']);
  			};
		
			$delete = $db->query("DELETE FROM __downloads_categories WHERE category_id = " . $db->escape($in->get('delete')));
			$op_output .= '<i>'.sanitize($del_cat['category_name']).'</i>'.$user->lang['dl_ad_deleted'];
			//Delete Cache
			$pdc->del_suffix('plugin.downloads');					
		
		};
	  		
		
	};

	// Output
	//====================
	
	//Get all categories
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.downloads.categories',false,true);
	if (!$categories_cache){
		$result = $db->query("SELECT * FROM __downloads_categories ORDER BY category_sortid");
		$result = $db->fetch_record_set($result);
		$pdc->put('plugin.downloads.categories',$result,86400,false,true);

	} else{
		$result = $categories_cache;
	};
	
	$counter = 0;
	if (is_array($result)) {
	foreach ($result as $row){
		// create the up/down buttons
		if ($counter != 0){
	 		$up_button = '<a href = "categories.php?cat_up='.sanitize($row['category_sortid']).'"><img src="../images/uparrow.png"></a>';
  		} else {
    		$up_button = '<img src="../images/uparrow_grey.png">';
  		}
  
 		$nextcount = $counter+1;
  		$down_query = $db->query("SELECT * FROM __downloads_categories ORDER BY category_sortid LIMIT " . $db->escape($nextcount) . ", 1");
  		$down_res = $db->fetch_record($down_query);
  		if ($down_res != '') {
  			$down_button = '<a href = "categories.php?cat_down='.$row['category_sortid'].'"><img src="../images/downarrow.png"></a>';
 		} else {
   			$down_button = '<img src="../images/downarrow_grey.png">';
  		}

  		//Count the Downloads in the Category
		//Cache: plugin.downloads.links_in_cat.{CATID}
		$links_in_cat_cache = $pdc->get('plugin.downloads.links_in_cat.'.$row['category_id'],false,true);
		if (!$links_in_cat_cache){
			$pic_count_query = $db->query("SELECT * FROM __downloads_links WHERE category=".$db->escape($row['category_id']));
			$pic_count_query = $db->fetch_record_set($pic_count_query);
			$pdc->put('plugin.downloads.links_in_cat.'.$row['category_id'],$pic_count_query,86400,false,true);
		} else{
			$pic_count_query = $links_in_cat_cache;
		};
			
  		$pic_count = count($pic_count_query);
  
 		$cat_footcount = sprintf($user->lang['dl_ad_cat_footcount'], $nextcount);
  
		$tpl->assign_block_vars('cat_list', array(
			'ROW_CLASS'	   				=> $eqdkp->switch_row_class(),
			'LIST'         				=> sanitize($row['category_name']),
			'DESCRIPTION'  				=> sanitize($row['category_comment']),
			'DL_PERM_EDIT_SELECT_0'     => ($row['category_permission'] == 0) ? 'selected="selected"' : '',
			'DL_PERM_EDIT_SELECT_1'     => ($row['category_permission'] == 1) ? 'selected="selected"' : '',
			'DL_PERM_PUBLIC'            => $user->lang['dl_perm_public'],
			'DL_PERM_REGISTERED'        => $user->lang['dl_perm_registered'],
			'PIC_COUNT'    				=> sanitize($pic_count),
			'UP_BUTTON'    				=> $up_button,
			'DOWN_BUTTON'  				=> $down_button,
			'CAT_ID'       				=> sanitize($row['category_id']),
		));
	$counter = $counter+1;
	}
	}; //End foreach
	
	// Create the 'Delete All'-Link
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.downloads.categories',false,true);	
	if (!$categories_cache){
		$catcheck_query = $db->query("SELECT * FROM __downloads_categories");
  		$catcheck = $db->fetch_record_set($catcheck_query);
		$pdc->put('plugin.downloads.categories',$catcheck,86400,false,true);
	} else{
		$catcheck = $categories_cache;
	};

	$category_check = (count($catcheck) == 0) ? true : false;

}; //END if categories aren't disabled

//Admin-Menu	
$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['dl_ad_cat_delall'],
                  'link'    => "categories.php".$SID."delete=all",
                  'img'     => 'delete.png',
                  'perm'    => (count($catcheck) == 0) ? false : true,
                  ),
              1 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
			  3 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              4 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			  5 => array(
                  'name'    => $user->lang['dl_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_downloads_import', false),
                  ),
			  
              
             
);

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_CREDITS'              	=> 'Credits',					
	'ABOUT_HEADER'           	=> $user->lang['dl_about_header'],
	'DL_COPYRIGHT'            	=> $dlclass->Copyright(),
	'ROW_CLASS'              	=> $eqdkp->switch_row_class(),	
	'DL_AD_CATEGORIES'       	=> $user->lang['dl_ad_categories'],
	'DL_AD_CATEGORY'         	=> $user->lang['dl_up_category'],
	'DL_AD_CAT_FOOTCOUNT'		=> sanitize($cat_footcount),
	'DL_PERM_HEADLINE'         	=> $user->lang['dl_perm_headline'],
	'DL_AD_ADD_COMMENT'      	=> $user->lang['dl_ad_input_comment'],
	'DL_AD_MANAGE_LINKS'  		=> $user->lang['dl_ad_manage_links'].":",
	'DL_AD_MANAGE_CAT_ORDER' 	=> $user->lang['dl_ad_manage_cat_order'],
	'DL_AD_UPDATE_TITLE'     	=> $user->lang['dl_ad_manage_cat_update'],
	'DL_AD_CREATE'           	=> $user->lang['dl_ad_new_cat'],
	'DL_AD_SEND'             	=> $user->lang['dl_ad_send'],
	'DL_AD_RESET'            	=> $user->lang['dl_ad_reset'],
	'DL_AD_CAT_DELALL'       	=> $user->lang['dl_ad_cat_delall'],
	'DL_S_NOCATS'      		 	=> sanitize($category_check),
	'DL_S_CATS_DIABLED'      	=> sanitize($cats_disabled),
	'DL_AD_STATUS'           	=> sanitize($op_output),	
	'DL_PERM_PUBLIC'            => $user->lang['dl_perm_public'],
	'DL_PERM_REGISTERED'        => $user->lang['dl_perm_registered'],
	'DL_AD_NO_CATS'            	=> $user->lang['dl_ad_no_cats'],
	'DL_AD_FIELDS_EMPTY'      	=> $user->lang['dl_ad_fields_empty'],
	'DL_DELETE'					=> $user->lang['dl_ad_delete'],
	'DL_CANCEL'					=> $user->lang['dl_ad_cancel'],
	'UPDATE_BOX'              	=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  		=> $rbvcheck->OutputHTML(),
	'DL_JS_FIELDS_EMPTY'		=> $jquery->Dialog_Alert('fields_empty', $user->lang['dl_ad_fields_empty'], $user->lang['dl_ad_fields_empty_title'],'300', '36'),
	'DL_JS_ABOUT'				=> $jquery->Dialog_URL('About', $user->lang['dl_about_header'], '../about.php', '500', '350'),
	'ADMIN_MENU'       			=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
	'DL_DELETE_WARN'    		=> $user->lang['dl_cat_delete_warn'],
	
	
));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_ad_manage_categories_ov'],
	'template_path'		=> $pm->get_data('downloads', 'template_path'),
	'template_file' 	=> 'admin/categories.html',
	'display'       	=> true)
  );

?>