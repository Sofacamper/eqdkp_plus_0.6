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




	// Change the Title and the Comment
	if ($in->get('cat_update_id') != ""){
  		$title_up_query = $db->query("UPDATE __mediacenter_categories SET category_name = '".$db->escape($in->get('cat_up_name'))."' WHERE category_id = ".$db->escape($in->get('cat_update_id')));
 		$descr_up_query = $db->query("UPDATE __mediacenter_categories SET category_comment = '".$db->escape($in->get('cat_up_description'))."' WHERE category_id = ".$db->escape($in->get('cat_update_id')));
   		
		//Delete Cache
		$pdc->del_prefix('plugin.mediacenter');
		System_Message(sprintf($user->lang['mc_category_updated'], sanitize($in->get('cat_up_name'))),'EQDKPlus MediaCenter','green');
	}


	// Move the Category up
	if ($in->get('cat_up') != ""){
  		$cat_up_query = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid");
  		while ($prev_cat = $db->fetch_record($cat_up_query)) {
  			if ($prev_cat['category_sortid'] < $in->get('cat_up')) {
   	  		$prev_category = $prev_cat['category_sortid'];
    		} else {
      		$last_category = $prev_cat['category_sortid'];
    		}
  		}
  		$temp_last_cat = $last_category + 1;
  		$temp_last_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($temp_last_cat)."' WHERE category_sortid = '".$db->escape($prev_category)."'");

  		$temp_up_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($prev_category)."' WHERE category_sortid = '".$db->escape($in->get('cat_up'))."'");

  		$temp_down_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($in->get('cat_up'))."' WHERE category_sortid = '".$db->escape($temp_last_cat)."'");
	//Delete Cache
	$pdc->del_prefix('plugin.mediacenter');
	};

	// Move the Category down
	if ($in->get('cat_down') != ""){
  		$cat_down_query = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid DESC");
  		while ($next_cat = $db->fetch_record($cat_down_query)) {
  			if ($next_cat['category_sortid'] > $in->get('cat_down')) {
   	  		$next_category = $next_cat['category_sortid'];
    		} else {
      		$last_cat_query = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid DESC");
      		$last_category = $db->fetch_record ($last_cat_query);
    		}
  		}
  		$temp_last_cat = $last_category['category_sortid'] + 1;
  		$temp_last_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($temp_last_cat)."' WHERE category_sortid = '".$db->escape($next_category)."'");

  		$temp_up_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($next_category)."' WHERE category_sortid = '".$db->escape($in->get('cat_down'))."'");

  		$temp_down_category = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($in->get('cat_down'))."' WHERE category_sortid = '".$db->escape($temp_last_cat)."'");
	//Delete Cache
	$pdc->del_prefix('plugin.mediacenter');
	};

	
	// Create new Category
	if($in->get('cat_name') != ''){
		//Delete Cache
		$pdc->del_prefix('plugin.mediacenter');
		$new_cat = $db->query("INSERT INTO __mediacenter_categories (category_name, category_comment) VALUES 
			('".$db->escape($in->get('cat_name'))."', 
			'".$db->escape($in->get('cat_comment'))."')");
		$catid = $db->sql_lastid();
		$set_sort_id = $db->query("UPDATE __mediacenter_categories SET category_sortid = '".$db->escape($catid)."' WHERE category_id = '".$db->escape($catid)."'");
		System_Message(sprintf($user->lang['mc_category_created'], sanitize($in->get('cat_name'))),'EQDKPlus MediaCenter','green');																			

	};

	//Delete Categories
	if($in->get('delete') != "") {
		
		if($in->get('delete') == "all") {
		
			$file_list_query = $db->query("SELECT * FROM __mediacenter_media");
  
			$delete = $db->query("DELETE FROM __mediacenter_media");
			$delete = $db->query("DELETE FROM __mediacenter_categories");
			
			System_Message($user->lang['mc_all_categories_deleted'],'EQDKPlus MediaCenter','green');
			//Delete Cache
			$pdc->del_prefix('plugin.mediacenter');
		
		} else {
		
			$delete = $db->query("DELETE FROM __mediacenter_media WHERE category = " . $db->escape($in->get('delete')));
			$delete = $db->query("DELETE FROM __mediacenter_categories WHERE category_id = " . $db->escape($in->get('delete')));
			System_Message(sprintf($user->lang['mc_category_deleted'], sanitize($del_cat['category_name'])),'EQDKPlus MediaCenter','green');	
			//Delete Cache
			$pdc->del_prefix('plugin.mediacenter');					
		
		};
	  		
		
	};

	// Output
	//====================
	
	//Get all categories
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.mediacenter.categories',false,true);
	if (!$categories_cache){
		$result = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid");
		$result = $db->fetch_record_set($result);
		$pdc->put('plugin.mediacenter.categories',$result,86400,false,true);

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
  		$down_query = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid LIMIT " . $db->escape($nextcount) . ", 1");
  		$down_res = $db->fetch_record($down_query);
  		if ($down_res != '') {
  			$down_button = '<a href = "categories.php?cat_down='.$row['category_sortid'].'"><img src="../images/downarrow.png"></a>';
 		} else {
   			$down_button = '<img src="../images/downarrow_grey.png">';
  		}

  		//Count the Downloads in the Category
		//Cache: plugin.downloads.links_in_cat.{CATID}
		$links_in_cat_cache = $pdc->get('plugin.mediacenter.media_in_cat.'.$row['category_id'],false,true);
		if (!$links_in_cat_cache){
			$pic_count_query = $db->query("SELECT * FROM __mediacenter_media WHERE category=".$db->escape($row['category_id']));
			$pic_count_query = $db->fetch_record_set($pic_count_query);
			$pdc->put('plugin.mediacenter.media_in_cat.'.$row['category_id'],$pic_count_query,86400,false,true);
		} else{
			$pic_count_query = $links_in_cat_cache;
		};
			
  		$pic_count = count($pic_count_query);
  
 		$cat_footcount = sprintf($user->lang['mc_cat_footcount'], $nextcount);
  
		$tpl->assign_block_vars('cat_list', array(
			'ROW_CLASS'	   				=> $eqdkp->switch_row_class(),
			'LIST'         				=> sanitize($row['category_name']),
			'DESCRIPTION'  				=> sanitize($row['category_comment']),
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
	$categories_cache = $pdc->get('plugin.mediacenter.categories',false,true);	
	if (!$categories_cache){
		$catcheck_query = $db->query("SELECT * FROM __mediacenter_categories");
  		$catcheck = $db->fetch_record_set($catcheck_query);
		$pdc->put('plugin.mediacenter.categories',$catcheck,86400,false,true);
	} else{
		$catcheck = $categories_cache;
	};

	$category_check = (count($catcheck) == 0) ? true : false;

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

	'ROW_CLASS'              	=> $eqdkp->switch_row_class(),	
	'L_MANAGE_CATEGORIES'       => $user->lang['mc_manage_categories'],
	'L_CATEGORY'         		=> $user->lang['mc_category'],
	'L_DESCRIPTION'         	=> $user->lang['mc_description'],
	'L_VIDEOS'         			=> $user->lang['mc_videos'],
	'L_ORDER'         			=> $user->lang['mc_order'],
	'L_NO_CATS'         		=> $user->lang['mc_no_categories'],
	
	'L_CATEGORY'         		=> $user->lang['mc_category'],
	'L_CAT_FOOTCOUNT'			=> sanitize($cat_footcount),
	'L_CREATE_CATEGORY'         => $user->lang['mc_create_category'],
	
	'S_NOCATS'      		 	=> sanitize($category_check),
	
	'L_DELETE'					=> $user->lang['mc_delete'],
	'L_CANCEL'					=> $user->lang['mc_cancel'],
	'L_SAVE'					=> $user->lang['mc_save'],
	'L_RESET'					=> $user->lang['mc_reset'],
	'L_UPDATE'					=> $user->lang['mc_update'],
	'UPDATE_BOX'              	=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  		=> $rbvcheck->OutputHTML(),
	'JS_FIELDS_EMPTY'			=> $jquery->Dialog_Alert('fields_empty', $user->lang['mc_fields_empty'], $user->lang['mc_fields_empty_title'],'300', '36'),
	'MC_ADMIN_MENU'       		=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),

	'L_DELETE_WARN'    			=> $user->lang['mc_categoy_delete_warn'],
	
	
));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['mediacenter']." - ".$user->lang['mc_manage_categories'],
	'template_path'		=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 	=> 'admin/categories.html',
	'display'       	=> true)
  );

?>