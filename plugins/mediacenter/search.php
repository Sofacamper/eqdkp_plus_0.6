<?PHP
/*************************************************\
*             MediaCenter 4 EQdkp plus            *
* ----------------------------------------------- *
* Project Start: 08/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1                                  *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); };

// Check user permission
$user->check_auth('u_mediacenter_view');
//Check admin-permissions for switches
$u_is_admin = ($user->check_auth('a_mediacenter_', false) == true) ? true : false;
$u_is_uploader = ($user->check_auth('u_mediacenter_upload', false) == true) ? true : false;

//Include commen download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');

// Admin-Action: Delete one download
if ($in->get('mode') == 'delete'){
	$error_msg = $mcclass->delete_one_link($in->get('id'));
};

//View
if ($in->get('view') == ""){ $view = $conf['default_view'];} else {$view = $in->get('view');};

//Sort
$order = $in->get('o', '0.0');
$red = 'RED'.str_replace('.', '', $order);

$sort_order = array(	
		0 => array('date desc', 'date'),
		1 => array('name', 'name desc'),
		2 => array('description', 'description desc'),
		3 => array('views', 'views desc'),
		4 => array('duration', 'duration desc'),
		5 => array('rating', 'rating desc'),
		6 => array('category', 'category desc'),
		
);

$current_order = switch_order($sort_order);
		
//Search through all downloads	
$search_value = $in->get('search_value', '');

//If there's no search-value
if (($search_value == "" && $in->get('tag') == "") || $search_value == $user->lang['mc_search_inputvalue']){
	$error_msg = $user->lang['mc_search_no_value'];
	$s_no_value = true;
}
else {	//If there's a search-value
	
	if ($in->get('tag') != ""){
		$search_value = rawurldecode($in->get('tag'));
		//Total search-results
		$total_results = "SELECT count(*) FROM __mediacenter_media WHERE MATCH(tags) AGAINST('".$db->escape($search_value)."' IN BOOLEAN MODE)";
		$total_results .= " ORDER BY ".$db->escape($current_order['sql']);
		$total_results = $db->query_first($total_results);
		
		$start = $in->get('start', 0);
		//Select the search-results; with pagination
		$downloads_query = "SELECT * FROM __mediacenter_media WHERE MATCH(tags) AGAINST('".$db->escape($search_value)."' IN BOOLEAN MODE)";	
		$downloads_query .= " ORDER BY ".$db->escape($current_order['sql']);
		$downloads_query .= " LIMIT ".$db->escape($start).",".$db->escape($conf['items_per_page']);
		$links = $db->query($downloads_query);
	
	
	} else {
		//Total search-results
		$total_results = "SELECT count(*) FROM __mediacenter_media WHERE MATCH(name, tags, description) AGAINST('".$db->escape($search_value)."' IN BOOLEAN MODE)";
		$total_results .= " ORDER BY ".$db->escape($current_order['sql']);
		$total_results = $db->query_first($total_results);
	
		
		$start = $in->get('start', 0);
		//Select the search-results; with pagination
		$downloads_query = "SELECT * FROM __mediacenter_media WHERE MATCH(name, tags, description) AGAINST('".$db->escape($search_value)."' IN BOOLEAN MODE)";	
		$downloads_query .= " ORDER BY ".$db->escape($current_order['sql']);
		$downloads_query .= " LIMIT ".$db->escape($start).",".$db->escape($conf['items_per_page']);
		
		$links = $db->query($downloads_query);
	}
	
	
	//Create Pagination
	$value = ($in->get('tag') != "") ? "tag" : "search_value";
	$pagination = generate_pagination('search.php'.$SID.'&amp;o='.sanitize($order).'&amp;'.$value.'='.urldecode(sanitize($search_value)).'&amp;view='.sanitize($view), $total_results, $conf['items_per_page'], $start);	
	
	//Get Category-Name
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.mediacenter.categories',false,true);
	if (!$categories_cache) {
		$categories_query = $db->query("SELECT * FROM __mediacenter_categories");
		$cat_row = $db->fetch_record_set($categories_query);
		$pdc->put('plugin.mediacenter.categories',$cat_row,86400,false,true);
	} else{
		$cat_row = $categories_cache;
	};
	
	//Build cat-array
	if (is_array($cat_row)){
		foreach($cat_row as $elem) {
    		$cat[$elem['category_id']] = $elem['category_name'];
		};
	};
	//Get Uploader
		$uploader_query = $db->query("SELECT * FROM __users");
		while ($row = $db->fetch_record($uploader_query)){
			$uploader[$row['user_id']] = $row['username'];		
		}

	if ($total_results <1){
		$error_msg = sprintf($user->lang['mc_search_no_matches'], $search_value);
		$dl_s_no_downloads = true;
	} else {
		$error_msg = sprintf($user->lang['mc_search_matches'], $search_value, $total_results);
		$dl_s_no_downloads = false;
	}
	

		while ($row = $db->fetch_record($links)){
			
				
			$tpl->assign_block_vars('link_list', array(
 				'ID'			=> sanitize($row['id']),									 
 				'NAME'			=> sanitize($row['name']),
				'DESC'			=> $html->ToolTip(sanitize($row['description']), $mcclass->wrapText(sanitize($row['description']), 80)),
				'DESC_SMALL'	=> $html->ToolTip(sanitize($row['description']), sanitize($row['name'])),
				'DATE'			=> sanitize($row['date']),
				'VIEWS'			=> sanitize($row['views']),
				'RATING'		=> sanitize($row['rating']),
				'CATEGORY'		=> $cat[sanitize($row['category'])],
				'UPLOADER'		=> sanitize($uploader[$row['user_id']]),
				'CAT_ID'		=> sanitize($row['category']),
				'THUMBNAIL'		=> $mcclass->show_thumbnail($row['thumbnail'],$row['type'], $row['extension']),
				'ROW_CLASS'		=> $eqdkp->switch_row_class(),
				'DURATION'		=> date("i:s", sanitize($row['duration'])),
				'REPORTED'		=> ($row['reported'] != "" && $u_is_admin == true) ? '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_l_reported_info'].'">' : "",
				
						
				));
		};
	
	}
	
	
		 $admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['mc_create_video'],
                  'link'    => 'javascript:uploadDialog()',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('u_mediacenter_upload', false),
                  ),
			  1 => array(
                  'name'    => $user->lang['mc_stats'],
                  'link'    => 'admin/statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_mediacenter_stats', false),
                  ),  
              2 => array(
                  'name'    => $user->lang['mc_manage_categories'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
              3 => array(
                  'name'    => $user->lang['mc_manage_videos'],
                  'link'    => 'admin/media.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			              
			  4 => array(
                  'name'    => $user->lang['mc_config'],
                  'link'    => 'admin/settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_mediacenter_cfg', false),
                  ),

              
             
      );
		 
		 $view_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['mc_view_details'],
                  'link'    => 'search.php'.$SID.'&search_value='.sanitize($search_value).'&view=0',
                  'img'     => 'list.png',
                  'perm'    => true,
                  ),
			  1 => array(
                  'name'    => $user->lang['mc_view_thumbs'],
                  'link'    => 'search.php'.$SID.'&search_value='.sanitize($search_value).'&view=1',
                  'img'     => 'thumbs.png',
                  'perm'    => true,
                  ),  
             

              
             
      ); 
	
	//Template Vars
	$tpl->assign_vars(array(			

			'MC_PAGINATION'						=> $pagination,
			'MC_SEARCH_VALUE'       			=> sanitize($search_value),
			'MC_OV_ERROR'						=> $error_msg,
			'MC_L_MEDIACENTER'					=> $user->lang['mc_mediacenter_short'],
			'MC_L_SEARCH_INPUTVALUE' 			=> $user->lang['mc_search_inputvalue'],
			'MC_L_SEARCH' 						=> $user->lang['mc_search'],
			'L_NAME' 							=> $user->lang['mc_name'],
			'L_DESC' 							=> $user->lang['mc_description'],
			'L_DURATION' 						=> $user->lang['mc_duration'],
			'L_CATEGORY' 						=> $user->lang['mc_category'],
			'L_DATE' 							=> $user->lang['mc_date'],
			'L_RATING' 							=> $user->lang['mc_rating'],
			'L_VIEWS' 							=> $user->lang['mc_views'],
			'MC_IS_ADMIN'						=> $u_is_admin,
			'MC_IS_UPLOADER'					=> $u_is_uploader,
			
			'MC_S_VIEW_'.$view					=> true,
			'MC_VIEW_MENU'       				=> $jquery->DropDownMenu("view_menu", "dl_colortab",  $view_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_view_menu']),
			
			'MC_S_NO_VALUE' 					=> $s_no_value,
			'MC_S_NO_MEDIA' 					=> $dl_s_no_downloads,
			'MC_SEARCH_FOOTCOUNT'				=> sanitize(sprintf($user->lang['mc_search_footcount'], $total_results, $conf['items_per_page'])),

			$red 								=> '_red',

			'MC_ADMIN_MENU'       				=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),
			'MC_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['mc_create_video'], "upload.php".$SID, '750', '400', 'index.php'.$SID),
				
		
	
		));
	
	// Init the Template Shit
	$eqdkp->set_vars(array(
		'page_title'           => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['mc_mediacenter_short']." - ".$user->lang['mc_search'].": ".sanitize($search_value),
		'template_path'        => $pm->get_data('mediacenter', 'template_path'),
		'template_file'        => 'search.html',
		'display'              => true)
    );



?>