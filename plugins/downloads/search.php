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

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Download-plugin is not installed.'); };

// Check user permission
$user->check_auth('u_downloads_view');
//Check admin-permissions for switches
($user->check_auth('u_downloads_upload', false) == true) 	? $u_is_admin_upload 	= true : $u_is_admin_upload = false;
($user->check_auth('a_downloads_', false) == true) 			? $u_is_admin 			= true : $u_is_admin	 	= false;

//Include commen download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');
include_once($eqdkp_root_path . 'plugins/downloads/include/libloader.inc.php');

$categories_disabled = ($conf['disable_categories'] == 1) ? true : false;

// Admin-Action: Delete one download
if ($in->get('mode') == 'delete'){
	$error_msg = delete_one_link($in->get('id'));
};


//Sort
$order = $in->get('o', '0.0');
$red = 'RED'.str_replace('.', '', $order);

$sort_order = array(	
		0 => array('date desc', 'date'),
		1 => array('file_type', 'file_type desc'),
		2 => array('name', 'name desc'),
		3 => array('description', 'description desc'),
		4 => array('file_size', 'file_size desc'),
		5 => array('views', 'views desc'),
		6 => array('rating', 'rating desc'),
		7 => array('category', 'category desc'),
);

$current_order = switch_order($sort_order);
		
//Search through all downloads	
$search_value = $in->get('search_value', '');

//If there's no search-value
if ($search_value == "" || $search_value == $user->lang['dl_search_inputvalue']){
	$error_msg = $user->lang['dl_search_no_value'];
	$dl_s_no_value = true;
}
else {	//If there's a search-value
	
	
	//Total search-results
	$total_results = "SELECT count(*) FROM __downloads_links WHERE url LIKE '%".$db->escape($search_value)."%' OR description LIKE '%".$db->escape($search_value)."%' OR name LIKE '%".$db->escape($search_value)."%' OR mirrors LIKE '%".$db->escape($search_value)."%'";
	if ( $user->data['user_id'] == ANONYMOUS ){ $total_results .= " AND permission = '1'";};
	$total_results .= " ORDER BY ".$db->escape($current_order['sql']);
	$total_results = $db->query_first($total_results);

	
	$start = $in->get('start', 0);
	//Select the search-results; with pagination
	$downloads_query = "SELECT * FROM __downloads_links WHERE url LIKE '%".$db->escape($search_value)."%' OR description LIKE '%".$db->escape($search_value)."%' OR name LIKE '%".$db->escape($search_value)."%' OR mirrors LIKE '%".$db->escape($search_value)."%'";
	if ( $user->data['user_id'] == ANONYMOUS ){ $downloads_query .= " AND permission = '1'";};
	$downloads_query .= " ORDER BY ".$db->escape($current_order['sql']);
	$downloads_query .= " LIMIT ".$db->escape($start).",".$db->escape($conf['items_per_page']);
	
	$links = $db->query($downloads_query);
	
	//Create Pagination
	$pagination = generate_pagination('search.php'.$SID.'&amp;o='.sanitize($order).'&amp;search_value='.urldecode(sanitize($search_value)), $total_results, $conf['items_per_page'], $start);	
	
	//Get Category-Name
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.downloads.categories',false,true);
	if (!$categories_cache) {
		$categories_query = $db->query("SELECT * FROM __downloads_categories");
		$cat_row = $db->fetch_record_set($categories_query);
		$pdc->put('plugin.downloads.categories',$cat_row,86400,false,true);
	} else{
		$cat_row = $categories_cache;
	};
	
	//Build cat-array
	if (is_array($cat_row)){
		foreach($cat_row as $elem) {
    		$cat[$elem['category_id']] = $elem['category_name'];
		};
	};
	
	if ($total_results <1){
		$error_msg = sprintf($user->lang['dl_search_no_matches'], $search_value);
		$dl_s_no_downloads = true;
	} else {
		$error_msg = sprintf($user->lang['dl_search_matches'], $search_value, $total_results);
		$dl_s_no_downloads = false;
	}
	

		while ($row = $db->fetch_record($links)){
			$tpl->assign_block_vars('link_list', array(
 				'DL_LINK_NAME'			=> sanitize($row['name']),
				'DL_LINK_DESC'			=> $html->ToolTip(sanitize($row['description'], 55), sanitize($dlclass->wrapText($row['description'], 55))),
				'DL_LINK_VIEWS' 		=> sanitize($row['views']),
				'DL_LINK_ID' 			=> sanitize($row['id']),
				'DL_LINK_RATING' 		=> sanitize($row['rating']),
				'DL_LINK_CAT' 			=> sanitize($cat[$row['category']]),
				'DL_LINK_CATID' 		=> sanitize($row['category']),
				'DL_LINK_SIZE' 			=> ($dlclass->human_size(sanitize($row['file_size']), 2, false) == "0 Bytes") ? '' : $dlclass->human_size(sanitize($row['file_size']), 2, false),
				'DL_ROW_CLASS'			=> $eqdkp->switch_row_class(),
				'DL_LINK_EXT_IMAGE'		=> $dlclass->extension_image(sanitize($row['file_type'])),
				'DL_L_DOWNLOAD_IT'		=> sanitize(sprintf($user->lang['dl_l_download_it'], $row['name'])),
						
				));
		};
	
			switch ($in->get('error')){
				case "1": $error_msg = $user->lang['dl_ad_update_success'];
					break;
				case "2": $error_msg = $user->lang['dl_ad_delete_success'];
					break;
				case "3": $error_msg = $user->lang['dl_ad_upload_success'];
					break;
			}
	}
	
	
	$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['dl_upload_headline'],
                  'link'    => 'javascript:uploadDialog('.sanitize($cat['category_id']).')',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('u_downloads_upload', false),
                  ),
              1 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'admin/downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
			 3 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'admin/statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              4 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'admin/settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
              
             
      );
	
	//Template Vars
	$tpl->assign_vars(array(			
			'DL_HEADLINE'       				=> $user->lang['dl_index_headline'],
			'DL_S_NO_DOWNLOADS'					=> $dl_s_no_downloads,
			'DL_S_NO_VALUE'						=> $dl_s_no_value,
			'DL_S_CATEGORIES_DISABLED'			=> $categories_disabled,
			'DL_NO_DOWNLOADS'					=> sanitize(sprintf($user->lang['dl_search_no_matches'], $search_value)),
			'DL_SEARCH_MATCHES'					=> sanitize(sprintf($user->lang['dl_search_matches'], $search_value, $total_results)),
			'DL_SEARCH_FOOTCOUNT'				=> sanitize(sprintf($user->lang['dl_search_footcount'], $total_results, $conf['items_per_page'])),
			'DL_PAGINATION'						=> $pagination,
			
			'DL_SEARCH_VALUE'       			=> sanitize($search_value),
			'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],
			'DL_SEARCH_HEADLINE'       			=> $user->lang['dl_search_headline'],
			'DL_CAT_HEADLINE'       			=> $user->lang['dl_cat_headline'],
			'DL_TYPE_HEADLINE'       			=> $user->lang['dl_type_headline'],
			'DL_NAME_HEADLINE'       			=> $user->lang['dl_name_headline'],
			'DL_DESC_HEADLINE'       			=> $user->lang['dl_desc_headline'],
			'DL_VIEWS_HEADLINE'       			=> $user->lang['dl_views_headline'],
			'DL_SIZE_HEADLINE'       			=> $user->lang['dl_filesize_headline'],
			'DL_RATING_HEADLINE'       			=> $user->lang['dl_rating_headline'],
			'DL_ACTION_HEADLINE'				=> $user->lang['dl_action_headline'],
			'DL_UPLOAD_HEADLINE'				=> $user->lang['dl_upload_headline'],
			
			'DL_L_CREDITS'      				=> 'Credits',
			'DL_L_COPYRIGHT'    				=> $dlclass->Copyright(),
			'DL_OV_ERROR'						=> $error_msg,
			'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
			'DL_IS_ADMIN'						=> $u_is_admin,
			

			$red 								=> '_red',

			'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "admin/upload.php".$SID, '750', '400', 'search.php'.$SID.'&search_value='.sanitize($search_value)),
			'ADMIN_MENU'       					=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
				
		
	
		));
	
	// Init the Template Shit
	$eqdkp->set_vars(array(
		'page_title'           => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_search_headline'].": ".sanitize($search_value),
		'template_path'        => $pm->get_data('downloads', 'template_path'),
		'template_file'        => 'search.html',
		'display'              => true)
    );



?>