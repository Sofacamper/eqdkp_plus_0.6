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
($user->check_auth('a_downloads_', false) == true) 			? $u_is_admin 			= true : $u_is_admin	 	= false;
($user->check_auth('a_downloads_cat', false) == true) 		? $u_is_admin_cat 		= true : $u_is_admin_cat 	= false;
($user->check_auth('a_downloads_links', false) == true) 	? $u_is_admin_links 	= true : $u_is_admin_linka 	= false;
($user->check_auth('u_downloads_upload', false) == true) 	? $u_is_admin_upload 	= true : $u_is_admin_upload = false;

//Include commen download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');

$categories_disabled = ($conf['disable_categories'] == 1) ? true : false;

// Admin-Action: Delete one download
if ($in->get('mode') == 'delete'){

	System_Message($dlclass->delete_one_link($in->get('id')),'Downloads 4 EQdkp-plus','green');
					
};

//Check if traffic has been resetted in this month
$timestamp = mktime(0,0,0,date('n'),0,date('Y'));
if ($conf['traffic_reset'] < $timestamp){
	$pdc->del_suffix('plugin.downloads');
	$dlclass->update_config(0, traffic_month);
	$dlclass->update_config($timestamp, traffic_reset);

};

//===========================================================================
//Mode: Categories
if (is_numeric($in->get('cat'))){
	
	//If categories are disabled: redirect to the overview-page
	if ($conf['disable_categories'] == true){redirect("plugins/downloads/downloads.php".$SID);};	
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
	);

	$current_order = switch_order($sort_order);
		
	//Get all information about the Category
	//Cache: plugin.downloads.categories.{CATID}.{USERID}
	$categories_cache = $pdc->get('plugin.downloads.categories.'.$in->get('cat').".u".$user->data['user_id'],false,true);
	if (!$categories_cache) {
		$cat_query = "SELECT * FROM __downloads_categories WHERE category_id = ".$db->escape($in->get('cat'));
		if ( $user->data['user_id'] == ANONYMOUS ){ $cat_query .= " AND category_permission = '1'";};
		$cat_query = $db->query($cat_query);
		$cat = $db->fetch_record($cat_query);
		$pdc->put('plugin.downloads.categories.'.$in->get('cat').".u".$user->data['user_id'],$cat,86400,false,true);
	} else{
		$cat = $categories_cache;
	};
	

	if (!$cat){$error_msg = $user->lang['dl_l_cat_not_existing'];};
	
	//Pagination
	$start = $in->get('start', 0);
	
	//Get number of all downloads in a categorie
	//Cache: plugin.downloads.links_in_cat.total.{CATID}.{USERID}
	$links_in_cat_total_cache = $pdc->get('plugin.downloads.links_in_cat.total.'.$in->get('cat').".u".$user->data['user_id'],false,true);
	if (!$links_in_cat_total_cache) {
		$total_query = "SELECT count(*) FROM __downloads_links WHERE category = ".$db->escape($in->get('cat'));
		if ( $user->data['user_id'] == ANONYMOUS ){ $total_query .= " AND permission = '1'";};
		$total_downloads = $db->query_first($total_query);
		$pdc->put('plugin.downloads.links_in_cat.total.'.$in->get('cat').".u".$user->data['user_id'],$total_downloads,86400,false,true);
	} else{
		$total_downloads = $links_in_cat_total_cache;
	};
			
	$pagination = generate_pagination('downloads.php'.$SID.'&amp;cat='.$in->get('cat').'&amp;o='.sanitize($order), $total_downloads, $conf['items_per_page'], $start);
	
	//Get all downloads in a cetegory
	//Cache: plugin.downloads.links_in_cat.{START}.{ITEMS_PER_PAGE}.{ORDER}.{USERID}
	$links_in_cat_cache = $pdc->get('plugin.downloads.links_in_cat.'.$in->get('cat').".s".$start.".i".$conf['items_per_page'].".o".$order.".u".$user->data['user_id'],false,true);
	if (!$links_in_cat_cache) {
		$downloads_query = "SELECT * FROM __downloads_links WHERE category = ".$db->escape($in->get('cat'));
		if ( $user->data['user_id'] == ANONYMOUS ){ $downloads_query .= " AND permission = '1'";};
		$downloads_query .= " ORDER BY ".$db->escape($current_order['sql']);
		$downloads_query .= " LIMIT ".$db->escape($start).",".$db->escape($conf['items_per_page']);
	
		$links = $db->query($downloads_query);
		$links = $db->fetch_record_set($links);
		$pdc->put('plugin.downloads.links_in_cat.'.$in->get('cat').".s".$start.".i".$conf['items_per_page'].".o".$order.".u".$user->data['user_id'],$links,86400,false,true);
	} else{
		$links = $links_in_cat_cache;
	};
	
	
	$downloads_footcount = sprintf($user->lang['dl_cat_footcount'], $total_downloads, sanitize($conf['items_per_page']));
		
	$dl_s_no_downloads = ($total_downloads < 1) ? true : false;
	
	if (is_array($links)){
		//Foreach data-row
		foreach ($links as $elem){
			$tpl->assign_block_vars('link_list', array(
 				'DL_LINK_NAME'			=> sanitize($elem['name']),
				'DL_LINK_DESC'			=> $html->ToolTip(sanitize($elem['description'], 50), sanitize($dlclass->wrapText($elem['description'], 50))),
				'DL_LINK_VIEWS' 		=> sanitize($elem['views']),
				'DL_LINK_ID' 			=> sanitize($elem['id']),
				'DL_LINK_RATING' 		=> sanitize($elem['rating']),
				'DL_LINK_SIZE' 			=> ($dlclass->human_size(sanitize($elem['file_size']), 2, false) == "0 Bytes") ? '' : $dlclass->human_size(sanitize($elem['file_size']), 2, false),
				'DL_ROW_CLASS'			=> $eqdkp->switch_row_class(),
				'DL_LINK_EXT_IMAGE'		=> $dlclass->extension_image(sanitize($elem['file_type'])),
				'DL_L_DOWNLOAD_IT'		=> sanitize(sprintf($user->lang['dl_l_download_it'], $elem['name'])),
				'DL_LINK_REPORTED'		=> (unserialize($elem['reported'])) ? '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png" title="'.$user->lang['dl_l_reported_info'].'">' : '',
						
				));
		}; //END foreach data-row
	};
	
			switch ($in->get('error')){
				case "1": System_Message($user->lang['dl_ad_update_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['dl_ad_upload_success'],'Downloads 4 EQdkp-plus','green');
					break;
			}
	      // Dropdown Menu Array
      
	  $admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['dl_upload_headline'],
                  'link'    => 'javascript:uploadDialog('.sanitize($cat['category_id']).')',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('u_downloads_upload', false),
                  ),
			  1 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'admin/statistics.php'.$SID.'&cat='.sanitize($cat['category_id']),
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),  
              2 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              3 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'admin/downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
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
			'DL_NO_DOWNLOADS'					=> $user->lang['dl_cat_nolinks'],
			'DL_S_CAT_NOT_EXISTING'				=> (!$cat) ? true : false,
			'DL_PAGINATION'						=> $pagination,
			
			'DL_CAT_NAME'       				=> sanitize($cat['category_name']),
			'DL_CAT_ID'							=> sanitize($cat['category_id']),
			'DL_CAT_HEADLINE'       			=> $user->lang['dl_cat_headline'],
			'DL_TYPE_HEADLINE'       			=> $user->lang['dl_type_headline'],
			'DL_NAME_HEADLINE'       			=> $user->lang['dl_name_headline'],
			'DL_DESC_HEADLINE'       			=> $user->lang['dl_desc_headline'],
			'DL_VIEWS_HEADLINE'       			=> $user->lang['dl_views_headline'],
			'DL_SIZE_HEADLINE'       			=> $user->lang['dl_filesize_headline'],
			'DL_RATING_HEADLINE'       			=> $user->lang['dl_rating_headline'],
			'DL_ACTION_HEADLINE'				=> $user->lang['dl_action_headline'],
			'DL_UPLOAD_HEADLINE'				=> $user->lang['dl_upload_headline'],
			'DL_CAT_FOOTCOUNT'					=> sanitize($downloads_footcount),
			
			'DL_OV_ERROR'						=> $error_msg,
			'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
			'DL_IS_ADMIN_LINK'					=> $u_is_admin_links,
			'DL_IS_ADMIN_CAT'					=> $u_is_admin_cat,
			'DL_IS_ADMIN'						=> $u_is_admin,
			$red 								=> '_red',
			'DL_TITLE_LINK_DELETE'				=> $user->lang['dl_title_link_delete'],
			'DL_TITLE_LINK_EDIT'				=> $user->lang['dl_title_link_edit'],
			'DL_TITLE_CAT_EDIT'					=> $user->lang['dl_title_cat_edit'],
			'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],

			'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "admin/edit.php".$SID."&id='+id+'&catid=".sanitize($cat['category_id']), '750', '400', 'downloads.php'.$SID.'&cat='.sanitize($cat['category_id'])),
			'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "admin/upload.php".$SID."&catid=".sanitize($cat['category_id']), '750', '400', 'downloads.php'.$SID.'&cat='.sanitize($cat['category_id'])),
			'ADMIN_MENU'       			 => $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
		
	
		));
	
	// Init the Template Shit for the categories
	$eqdkp->set_vars(array(
		'page_title'           => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_cat_headline'].": ".$cat['category_name'],
		'template_path'        => $pm->get_data('downloads', 'template_path'),
		'template_file'        => 'category.html',
		'display'              => true)
    );



}	//END Mode:Categories


else{  //If Mode is not categories
	//======================================================================
	//Mode: File
	if (is_numeric($in->get('file'))){
	
		//Comment-System
		if ($conf['enable_comments'] == 1){
	
			if(is_object($pcomments) && $pcomments->version > '1.0.3'){
				$comm_settings = array(
					'attach_id' => $in->get('file'), 
					'page'      => 'downloads',
					'auth'      => 'a_downloads_',
				);
	  
				$pcomments->SetVars($comm_settings);
				$tpl->assign_vars(array(
					'ENABLE_COMMENTS'     => true,
					'COMMENTS'            => $pcomments->Show(),
				));
    
			};
		
		};
	

		if ($in->get('mode') == 'del_votes' && $u_is_admin_links == true){
				
				$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", array(
					        	'rating_points'			=> 0,
					        	'votes'					=> 0,
					        	'rating'				=> 0,
								'voted_users'			=> "",
							));
				$pdc->del_suffix('plugin.downloads.links');
		};
		
		if ($in->get('mode') == 'repaired' && $u_is_admin_links == true){
				
				$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", array(
					        	'reported'			=> "",
							));
				$pdc->del_suffix('plugin.downloads.links');
		};
			
		//Vote-mode
		if ($in->get('mode') == 'vote'){
			
			if ($in->get('vote')){
		
				$out = $pdc->get('plugin.downloads.links.'.$in->get('file').".u".$user->data['user_id'],false,true);
				if (!$out) {
					$vote_query = "SELECT * FROM __downloads_links WHERE id = '".$db->escape($in->get('file',0))."'";
					if ( $user->data['user_id'] == ANONYMOUS ){ $vote_query .= " AND permission = '1'";};
					$vote_query = $db->query($vote_query);
					$vote = $db->fetch_record($vote_query);
					$pdc->put('plugin.downloads.links.'.$in->get('file').".u".$user->data['user_id'],$vote,86400,false,true);
				} else{
					$vote = $out;
				};

				if ($vote){
					if ($conf['single_vote'] == 1){
						
						$users_voted = unserialize($vote['voted_users']);

						if (!$users_voted[$user->data['user_id']]){
							$users_voted[$user->data['user_id']] = $in->get('vote');
							$rating_points = $vote['rating_points'] + $in->get('vote');
							$votes = $vote['votes'] + 1;
							$rating = round($rating_points / $votes);

							$pdc->del_suffix('plugin.downloads.links');
						
							$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", array(
					        	'rating_points'	=> $db->escape($rating_points),
					        	'votes'					=> $db->escape($votes),
					        	'rating'				=> $db->escape($rating),
								'voted_users'			=> serialize($users_voted),
							));
						};
						
						
						
						
					} else {
					
						$rating_points = $vote['rating_points'] + $in->get('vote');
						$votes = $vote['votes'] + 1;
						$rating = round($rating_points / $votes);

						$pdc->del_suffix('plugin.downloads.links');
					
						$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", array(
					        'rating_points'	=> $db->escape($rating_points),
					        'votes'					=> $db->escape($votes),
					        'rating'				=> $db->escape($rating),
						));
					
					}
					
					
				};

				
				die($jquery->StarRatingValue('vote', $rating));
			}else{	
				die();
			}
		};
		
		

		//Get all information about the download
		//Cache: plugin.downloads.links.{ID}.{USERID}
		$link_cache = $pdc->get('plugin.downloads.links.'.$in->get('file').".u".$user->data['user_id'],false,true);
		if (!$link_cache) {
			$download_query = "SELECT * from __downloads_links WHERE id = '".$db->escape($in->get('file'))."'";
			if ( $user->data['user_id'] == ANONYMOUS ){ $download_query .= " AND permission = '1'";};
			$download_query = $db->query($download_query);
			$download = $db->fetch_record($download_query);
			$pdc->put('plugin.downloads.links.'.$in->get('file').".u".$user->data['user_id'],$download,86400,false,true);
		} else{
			$download = $link_cache;
		};
		$download_count = (!$download) ? 0 : 1;
		
		
	
	
		//Get all information about the Category
		//Cache: plugin.downloads.categories.{ID}
		$category_cache = $pdc->get('plugin.downloads.categories.'.$download['category'],false,true);
		if (!$category_cache) {
			$cat_query = "SELECT * FROM __downloads_categories WHERE category_id = '".$db->escape($download['category'])."'";
			$cat_query = $db->query($cat_query);
			$cat = $db->fetch_record($cat_query);
			$pdc->put('plugin.downloads.categories.'.$download['category'],$cat,86400,false,true);
		} else{
			$cat = $category_cache;
		};
		
		//unregistered User doesn't access download if category is only for registered users
		if ($conf['disable_categories'] == 0){
			if ($user->data['user_id'] == ANONYMOUS){
				if ($cat['category_permission'] == 0){
					$download_count = 0;
				}
			}
		}	
		
		if ($download_count < 1){$error_msg = $user->lang['dl_index_error_perm'];};

		//Get Uploader
		$uploader_query = $db->query("SELECT * from __users WHERE user_id = '".$db->escape($download['user_id'])."'");
		$uploader = $db->fetch_record($uploader_query);


		//Get Mirrors
		if ($download['mirrors'] != ""){
			$s_mirrors = true;
			$mirror_parts = explode("|", $download['mirrors']);
			$mirrors = "<ul>";
			$reported_mirrors = unserialize($download['reported']);
			$i = 0;
				foreach ($mirror_parts as $elem){
				
					if($elem != ""){
					$mirror_array = parse_url($elem);
					
						$i = $i + 1;
					
						$tpl->assign_block_vars('mirror_list', array(
							"MIRROR" => "<a href=\"download.php?id=".sanitize($download['id'])."&mirror=".$i."\" target=\"_blank\">".sanitize(sprintf($user->lang['dl_l_mirrors_download'], $i)).": ".$mirror_array['scheme']."://".$mirror_array['host']."</a>",
							"MIRROR_ID" => $i,
							"ROW_CLASS" => $eqdkp->switch_row_class(),
							"MIRROR_REPORTED"	=> (isset($reported_mirrors[$i-1])) ? '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png" title="'.$user->lang['dl_l_reported_info'].'">' : ''
						));
						
											
					}
				}
	
		}
		
		//Make preview image
		if ($download['preview_image'] != ""){
			
			$tpl->assign_vars(array(
				"DL_S_PREVIEWIMAGE" => true,
				"DL_PREVIEW_IMAGE"  => '<a href="'.$pcache->FilePath('thumbs/'.$download['preview_image'], 'downloads').'" rel="lytebox" class="image-resize"><img src="'.$pcache->FilePath('thumbs/'.$download['preview_image'], 'downloads').'"></s>',
				'DL_PREVIEW'						=> $user->lang['dl_l_preview'],
				
				
				//Lightbox-things
 				'S_IMG_RESIZE_ENABLE'          => true,
 				'S_MAX_POST_IMG_RESIZE_WIDTH'  => 400,
 				'S_IMG_RESIZE_WARNING'         => ($user->lang['air_img_resize_warning']) ? $user->lang['air_img_resize_warning'] : '', 
 				'S_IMG_WARNING_ACTIVE'         =>  'true' , 
 				'S_LYTEBOX_THEME'              => ($conf_plus['pk_air_lytebox_theme']) ? $conf_plus['pk_air_lytebox_theme'] : 'grey',
 				'S_LYTEBOX_AUTO_RESIZE'        => 1,
 				'S_LYTEBOX_ANIMATION'          => ($conf_plus['pk_air_lytebox_animation']) ? 1 : 0, 
 		
 		));	
			
			
		}
		
		
		
		//Switch Success-/Error-Message
			switch ($in->get('error')){
				case "1": System_Message($user->lang['dl_ad_update_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['dl_ad_upload_success'],'Downloads 4 EQdkp-plus','green');
					break;
			}
		
			  $admin_optionsarray = array(

				  
				  0 => array(
                  'name'    => $user->lang['dl_title_link_edit'],
                  'link'    => 'javascript:editDialog();',
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
				  
				  1 => array(
                  'name'    => $user->lang['dl_title_link_delete'],
                  'link'    => 'downloads.php'.$SID.'&cat='.sanitize($cat['category_id']).'&mode=delete&id='.sanitize($download['id']),
                  'img'     => 'delete.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
			  
			  2 => array(
                  'name'    => $user->lang['dl_upload_headline'],
                  'link'    => 'javascript:uploadDialog('.sanitize($cat['category_id']).')',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('u_downloads_upload', false),
                  ),
			  3 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'admin/statistics.php'.$SID.'&file='.sanitize($download['id']),
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              4 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              5 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'admin/downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
              6 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'admin/settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			  
              
             
      );

		
		//If Download or Mirror has been reported
		if (unserialize($download['reported'])){
		
			$tpl->assign_vars(array(			
				'DL_S_REPORTED' => true,
				'DL_L_REPORTED' => sprintf($user->lang['dl_l_reported'],"downloads.php".$SID."&file=".sanitize($download['id'])."&mode=repaired"),
			));

			foreach (unserialize($download['reported']) as $elem=>$key){

				if ($elem['dl']){
					$tpl->assign_vars(array(
						
						"DL_MAIN_REPORTED_IMAGE" => '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png" title="'.$user->lang['dl_l_reported_info'].'">',									   
					));
					if ($key){
					$tpl->assign_block_vars('reported_list', array(
						
						"MIRROR" => $user->lang['dl_desc_headline'].": ".$key,									   
					));
					};
				} else {
					$mirror = $user->lang['dl_l_mirror'].": ".$mirror_parts[$elem];
					$mirror .= ($key) ? '; '.$user->lang['dl_desc_headline'].": ".$key : '';
					$tpl->assign_block_vars('reported_list', array(
						"MIRROR" => $mirror,										   
					));
				}

			}
			
		};
		

		
		
		$myRatings = array(
			'1'		=> '1 '.$user->lang['dl_l_bad'],
			'2'		=> '2',
			'3'		=> '3',
			'4'		=> '4',
			'5'		=> '5',
			'6'		=> '6',
			'7'		=> '7',
			'8'		=> '8',
			'9'		=> '9',
			'10'	=> '10 '.$user->lang['dl_l_good'],
		);
		
		if ($conf['single_vote'] == 1){
			$users_voted = unserialize($download['voted_users']);
			$u_has_voted = (!$users_voted[$user->data['user_id']]) ? false : true;
			
		} else {
			$u_has_voted = false;
		}
						
		
		// Send the Output to the template Files.
		$tpl->assign_vars(array(			
			'DL_HEADLINE'       				=> $user->lang['dl_index_headline'],
			'DL_CAT_NAME'       				=> $user->lang['dl_cat_headline'].": ".$cat['category_name'],
			'DL_CAT_ID'							=> sanitize($cat['category_id']),
			'DL_S_DOWNLOAD_NOT_EXISTING'		=> ($download_count < 1) ? true : false,
			'DL_S_CATEGORIES_DISABLED'			=> $categories_disabled,
			'DL_S_MIRRORS'						=> $s_mirrors,
			
			'DL_STAR_RATING'				=> $jquery->StarRating('vote', $myRatings,'downloads.php'.$SID.'&file='.sanitize($download['id']).'&mode=vote',sanitize($download['rating']), $u_has_voted),
	
			'DL_S_LOCALFILE'					=> ($download['local_filename'] != "") ? true : false,
			'DL_S_HAS_VOTED'					=> ($in->get('mode') == "vote") ? true : false,
			'DL_DOWNLOAD_ID'					=> sanitize($download['id']),
			'DL_DOWNLOAD_NAME'					=> sanitize($download['name']),
			'DL_DOWNLOAD_DESC'					=> sanitize($download['description']),
			'DL_DOWNLOAD_FILENAME'				=> sanitize($download['url']),
			'DL_DOWNLOAD_MD5'					=> ($download['local_filename'] !="") ? md5_file($pcache->FilePath('files/'.$download['local_filename'], 'downloads')) : '',
			'DL_DOWNLOAD_SIZE'					=> $dlclass->human_size(sanitize($download['file_size'])),
			'DL_DOWNLOAD_RATING_OV'				=> sanitize($download['rating']),
			'DL_DOWNLOAD_VOTES'					=> sanitize($download['votes']),
			'DL_DOWNLOAD_UPLOADER'				=> sanitize($uploader['username']),
			'DL_DOWNLOAD_UPLOAD_DATE'			=> sanitize($download['date']),
			'DL_DOWNLOAD_VIEWS'					=> sanitize($download['views']),
			'DL_DOWNLOAD_TRAFFIC'				=> $dlclass->human_size(sanitize($download['traffic'])),
			'DL_UPLOAD_HEADLINE'				=> $user->lang['dl_upload_headline'],
			'DL_ACTION_HEADLINE'				=> $user->lang['dl_action_headline'],
			'DL_TITLE_LINK_DELETE'				=> $user->lang['dl_title_link_delete'],
			'DL_TITLE_LINK_EDIT'				=> $user->lang['dl_title_link_edit'],
			
			'DL_L_DELETE_VOTES'					=> $user->lang['dl_l_delete_votes'],
			'DL_UP_SEND'               			=> $user->lang['dl_up_send'],
			'DL_L_NAME'							=> $user->lang['dl_name_headline'],
			'DL_L_DESC'							=> $user->lang['dl_desc_headline'],
			'DL_L_FILENAME'						=> $user->lang['dl_l_filename'],
			'DL_L_MD5'							=> $user->lang['dl_l_md5'],
			'DL_L_FILESIZE'						=> $user->lang['dl_l_filesize'],
			'DL_L_RATING_OV'					=> $user->lang['dl_rating_headline'],
			'DL_L_RATING'						=> $user->lang['dl_l_rating'],
			'DL_L_UPLOADER'						=> $user->lang['dl_l_uploader'],
			'DL_L_UPLOAD_DATE'					=> $user->lang['dl_l_upload_date'],
			'DL_L_TRAFFIC'						=> $user->lang['dl_l_traffic'],
			'DL_L_VIEWS'						=> $user->lang['dl_l_views'],
			'DL_L_MIRRORS'						=> $user->lang['dl_l_mirrors'],
			'DL_FILE_HEADLINE'					=> $user->lang['dl_file_info_headline'],
			'DL_L_GOOD'							=> $user->lang['dl_l_good'],
			'DL_L_BAD'							=> $user->lang['dl_l_bad'],
			'DL_L_SELECT_RATING'				=> $user->lang['dl_l_select_rating'],
			'DL_L_THANKS_FOR_RATING'			=> $user->lang['dl_l_thanks_for_rating'],
			'DL_L_VOTES'						=> $user->lang['dl_l_votes'],
			'DL_L_DOWNLOAD_IT'					=> sanitize(sprintf($user->lang['dl_l_download_it'], $download['name'])),
			'DL_L_DOWNLOAD'						=> $user->lang['dl_l_download'],
			'DL_L_REPORT_DEAD_LINK'				=> $user->lang['dl_l_report_dead_link'],
			'DL_S_USER_LOGGED_IN'				=> ($user->data['user_id'] == ANONYMOUS) ? false : true,

			'DL_OV_ERROR'						=> $error_msg,
			'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
			'DL_IS_ADMIN_LINK'					=> $u_is_admin_links,
			'DL_IS_ADMIN_CAT'					=> $u_is_admin_cat,
			'DL_IS_ADMIN'						=> $u_is_admin,
			$red 								=> '_red',
			'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],
			'DL_JS_TAB'							=> $jquery->Tab_header('download_tabs'),
			'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "admin/edit.php".$SID."&id=".sanitize($download['id']).'&file='.sanitize($download['id']), '750', '400', 'downloads.php'.$SID.'&file='.sanitize($download['id'])),
			'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "admin/upload.php".$SID."&catid=". sanitize($cat['category_id']), '750', '400', 'downloads.php'.$SID.'&file='.sanitize($download['id'])),
			'DL_JS_REPORT'						=> $jquery->Dialog_URL('Report', $user->lang['dl_l_report_dead_link'], 'report.php'.$SID.'&file='.sanitize($download['id'])."&mirror='+mirror+'", '580', '170'),
			'ADMIN_MENU'       			 => $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
	
		));


		//Get related downloads
		if ($download['related_downloads'] != ""){

			$related_parts = explode("|", $download['related_downloads']);
			
			$i = 0;
			//Foreach related download
			foreach ($related_parts as $elem){
					
					//Get Information about the related download
					//Cache: plugin.downloads.links.{ID}.{USERID}
					$link_cache = $pdc->get('plugin.downloads.links.'.$elem.".u".$user->data['user_id'],false,true);
					if (!$link_cache) {
						$related_query = "SELECT * FROM __downloads_links WHERE id='".$db->escape($elem)."'";
						if ( $user->data['user_id'] == ANONYMOUS ){ $related_query .= " AND permission = '1'";};
						$related_query = $db->query($related_query);
						$row = $db->fetch_record();
						$pdc->put('plugin.downloads.links.'.$elem.".u".$user->data['user_id'],$row,86400,false,true);
					} else{
					$row = $link_cache;
					};
				
				
				if ($row){
					$i = $i + 1;
					$tpl->assign_block_vars('related_list', array(
						'DL_LINK_NAME'			=> sanitize($row['name']),
						'DL_LINK_DESC'			=> sanitize($row['description']),
						'DL_LINK_VIEWS' 		=> sanitize($row['views']),
						'DL_LINK_ID' 			=> sanitize($row['id']),
						'DL_LINK_RATING' 		=> sanitize($row['rating']),
						'DL_LINK_SIZE' 			=> $dlclass->human_size(sanitize($row['file_size'])),
						'DL_ROW_CLASS'			=> $eqdkp->switch_row_class(),
						'DL_LINK_EXT_IMAGE'		=> $dlclass->extension_image(sanitize($row['file_type'])),
						'DL_TITLE_DOWNLOAD_IT'	=> sanitize(sprintf($user->lang['dl_l_download_it'], $row['name'])),
						'DL_LINK_REPORTED'		=> (unserialize($row['reported'])) ? '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png" title="'.$user->lang['dl_l_reported_info'].'">' : '',
				
					));
				}
				
			} //END foreach related download
		

			$related_links_footcount = sprintf($user->lang['dl_related_links_footcount'],$i);
			
			//Template Vars
			$tpl->assign_vars(array(
				
				'DL_RELATED_DLS_HEADLINE'       	=> $user->lang['dl_related_links_headline'],
				'DL_S_RELATED_DOWNLOADS'			=> true,
				'DL_TYPE_HEADLINE'       			=> $user->lang['dl_type_headline'],
				'DL_NAME_HEADLINE'       			=> $user->lang['dl_name_headline'],
				'DL_DESC_HEADLINE'       			=> $user->lang['dl_desc_headline'],
				'DL_VIEWS_HEADLINE'       			=> $user->lang['dl_views_headline'],
				'DL_SIZE_HEADLINE'       			=> $user->lang['dl_filesize_headline'],
				'DL_RATING_HEADLINE'       			=> $user->lang['dl_rating_headline'],
				'DL_RELATED_DLS_FOOTCOUNT'			=> sanitize($related_links_footcount),
			));
			
		} else { //If there are no related downloads
		
			$tpl->assign_vars(array(			
			'DL_S_RELATED_DOWNLOADS'       		=> false,
			));
		};

		// Init the Template Shit
		$eqdkp->set_vars(array(
			'page_title'           => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_file_headline'].": ".sanitize($download['name']),
			'template_path'        => $pm->get_data('downloads', 'template_path'),
			'template_file'        => 'file.html',
			'display'              => true,
			));


	}
	//==============================================================================
	else {	//Just normal Overview

		if ($conf['enable_statistics_ov'] == 1){
		
		//Create Statistics
		//---------------------
		
		//If User is unregistered
		if ( $user->data['user_id'] == ANONYMOUS ){
			
			//If Categories are enabled
			if ($conf['disable_categories'] == 1){
				
				//Top5-Downloads
				//Cache: plugin.downloads.stat.top5.{USERID}
				$stat_top5_cache = $pdc->get('plugin.downloads.stat.top5.u'.$user->data['user_id'],false,true);
				if (!$stat_top5_cache) {
					$top_dls_query = $db->query("SELECT * FROM __downloads_links WHERE permission = '1' ORDER BY views DESC LIMIT 5");
					$top_dls_query = $db->fetch_record_set($top_dls_query);
					$pdc->put('plugin.downloads.stat.top5.u'.$user->data['user_id'],$top_dls_query,86400,false,true);
				} else{
					$top_dls_query = $stat_top5_cache;
				};
			
				//Last Uploaded Download
				//Cache: plugin.downloads.stat.lastupload.{USERID}
				$stat_lastupload_cache = $pdc->get('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],false,true);
				if (!$stat_lastupload_cache) {
					$last_upload = $db->query("SELECT * FROM __downloads_links WHERE permission = '1' ORDER BY date DESC LIMIT 1");
					$last_upload = $db->fetch_record($last_upload_query);
					$pdc->put('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],$last_upload,86400,false,true);
				} else{
					$last_upload = $stat_lastupload_cache;
				};
			
				//Number of total downloads
				//Cache: plugin.downloads.stat.totallinks.{USERID}
				$stat_totallinks_cache = $pdc->get('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],false,true);
				if (!$stat_totallinks_cache) {
					$total_downloads = $db->query_first("SELECT count(*) FROM __downloads_links WHERE permission = '1'");
					$pdc->put('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],$total_downloads,86400,false,true);
				} else{
					$total_downloads = $stat_totallinks_cache;
				};

				//Number of total Categories
				$total_categories = "";
			
			
			} else {
				
				//Top5-Downloads
				//Cache: plugin.downloads.stat.top5.{USERID}
				$stat_top5_cache = $pdc->get('plugin.downloads.stat.top5.u'.$user->data['user_id'],false,true);
				if (!$stat_top5_cache) {
					$top_dls_query = $db->query("SELECT * FROM __downloads_links, __downloads_categories WHERE __downloads_links.permission=1 AND __downloads_links.category = __downloads_categories.category_id AND __downloads_categories.category_permission=1 ORDER BY views DESC LIMIT 5");
					$top_dls_query = $db->fetch_record_set($top_dls_query);
					$pdc->put('plugin.downloads.stat.top5.u'.$user->data['user_id'],$top_dls_query,86400,false,true);
				} else{
					$top_dls_query = $stat_top5_cache;
				};
			
				//Last Uploaded Download
				//Cache: plugin.downloads.stat.lastupload.{USERID}
				$stat_lastupload_cache = $pdc->get('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],false,true);
				if (!$stat_lastupload_cache) {
					$last_upload = $db->query("SELECT * FROM __downloads_links, __downloads_categories WHERE __downloads_links.permission=1 AND __downloads_links.category = __downloads_categories.category_id AND __downloads_categories.category_permission=1 ORDER BY date DESC LIMIT 1");
					$last_upload = $db->fetch_record($last_upload_query);
					$pdc->put('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],$last_upload,86400,false,true);
				} else{
					$last_upload = $stat_lastupload_cache;
				};
			
				//Number of total downloads
				//Cache: plugin.downloads.stat.totallinks.{USERID}
				$stat_totallinks_cache = $pdc->get('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],false,true);
				if (!$stat_totallinks_cache) {
					$total_downloads = $db->query_first("SELECT count(*) FROM __downloads_links, __downloads_categories WHERE __downloads_links.permission=1 AND __downloads_links.category = __downloads_categories.category_id AND __downloads_categories.category_permission=1");
					$pdc->put('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],$total_downloads,86400,false,true);
				} else{
					$total_downloads = $stat_totallinks_cache;
				};
				
					
				//Number of total Categories
				//Cache: plugin.downloads.stat.totalcats.{USERID}
				$stat_totalcats_cache = $pdc->get('plugin.downloads.stat.totalcats.u'.$user->data['user_id'],false,true);
				if (!$stat_totalcats_cache) {
					$total_categories = $db->query_first("SELECT count(*) FROM __downloads_categories WHERE category_permission = '1'");
					$pdc->put('plugin.downloads.stat.totalcats.u'.$user->data['user_id'],$total_categories,86400,false,true);
				} else{
					$total_categories = $stat_totalcats_cache;
				};
				
			
			};
			
		
		//If User is registered
		} else {
		
			//Top5-Downloads
			//Cache: plugin.downloads.stat.top5.{USERID}
			$stat_top5_cache = $pdc->get('plugin.downloads.stat.top5.u'.$user->data['user_id'],false,true);
			if (!$stat_top5_cache) {
				$top_dls_query = $db->query("SELECT * FROM __downloads_links ORDER BY views DESC LIMIT 5");
				$top_dls_query = $db->fetch_record_set($top_dls_query);
				$pdc->put('plugin.downloads.stat.top5.u'.$user->data['user_id'],$top_dls_query,86400,false,true);
			} else{
				$top_dls_query = $stat_top5_cache;
			};

			//Last Uploaded File
			//Cache: plugin.downloads.stat.lastupload.{USERID}
			$stat_lastupload_cache = $pdc->get('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],false,true);
			if (!$stat_lastupload_cache) {
				$last_upload_query = $db->query("SELECT * FROM __downloads_links ORDER BY DATE DESC LIMIT 1");
				$last_upload = $db->fetch_record($last_upload_query);
				$pdc->put('plugin.downloads.stat.lastupload.u'.$user->data['user_id'],$last_upload,86400,false,true);
			} else{
				$last_upload = $stat_lastupload_cache;
			};

			//Number of total downloads
			//Cache: plugin.downloads.stat.totallinks.{USERID}

			$stat_totallinks_cache = $pdc->get('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],false,true);
			if (!$stat_totallinks_cache) {
				$total_downloads = $db->query_first("SELECT count(*) FROM __downloads_links");
				$pdc->put('plugin.downloads.stat.totallinks.u'.$user->data['user_id'],$total_downloads,86400,false,true);
			} else{
				$total_downloads = $stat_totallinks_cache;
			};
			
			//Number of total Categories
			if ($conf['disable_categories'] == 0){ //IF categories are enabled
				
				//Cache: plugin.downloads.stat.totalcats.{USERID}
				$stat_totalcats_cache = $pdc->get('plugin.downloads.stat.totalcats.u'.$user->data['user_id'],false,true);
				if (!$stat_totalcats_cache) {
					$total_categories = $db->query_first("SELECT count(*) FROM __downloads_categories");
					$pdc->put('plugin.downloads.stat.totalcats.u'.$user->data['user_id'],$total_categories,86400,false,true);
				} else{
					$total_categories = $stat_totalcats_cache;
				};					
			} else {
				$total_categories = "";
			}; //ENDIF
			
			

		
		} //END if user ist registered
		

		//Traffics
		$traffic_month = $conf['traffic_month'];
		
		if ($conf['traffic_limit'] != ''){
			if ($conf['traffic_limit'] == 0){
				$traffic_limit = " (".$user->lang['dl_l_traffic_of']." ".$dlclass->human_size($conf['traffic_month']).")";
			}
			else {
				if ($conf['traffic_month'] >= $conf['traffic_limit']){
					$traffic_limit = " (".$user->lang['dl_l_traffic_of']." ".$dlclass->human_size($conf['traffic_month']).")";
				} else {
					$traffic_limit = " (".$user->lang['dl_l_traffic_of']." ".$dlclass->human_size($conf['traffic_limit']).")";
				};

			}
		}

		$traffic_total = $conf['traffic_total'];
		
		//Bring TOP5-Downloads to template
		$i = 1;
		if (is_array($top_dls_query)) {
			foreach ($top_dls_query as $elem){

				$tpl->assign_block_vars('stat_topfive', array(					
					'DL_STAT_TOP_PLACE'				=> $i,
					'DL_STAT_TOP_ID'				=> sanitize($elem['id']),
					'DL_STAT_TOP_NAME'				=> sanitize($elem['name']),
					'DL_STAT_TOP_VIEWS'				=> sanitize($elem['views']),
					'DL_STAT_TOP_ROW'				=> $eqdkp->switch_row_class(),

				));
				$i++;
			};
		}
			
		//Bring statistics to template
		$tpl->assign_vars(array(	
			'DL_S_SHOW_STATISTICS'		=> true,					
			'DL_STAT_TOTAL_DLS'			=> sanitize($total_downloads),
			'DL_STAT_TOTAL_CATS'		=> sanitize($total_categories),
			'DL_STAT_TRAFFIC_MONTH'		=> sanitize($dlclass->human_size($traffic_month).$traffic_limit),
			'DL_STAT_TRAFFIC_TOTAL'		=> sanitize($dlclass->human_size($traffic_total)),
			'DL_STAT_LAST_UPLOADED'		=> sanitize($last_upload['name']),
			'DL_STAT_LAST_UPLOADED_ID'	=> sanitize($last_upload['id']),
			'DL_STAT_HEADLINE'			=> $user->lang['dl_stat_headline'],
			'DL_TOP5_HEADLINE'			=> $user->lang['dl_top5_headline'],
			'DL_L_CATS'					=> $user->lang['dl_l_cats'],
			'DL_L_FILES'				=> $user->lang['dl_l_files'],
			'DL_L_TRAFFIC_MONTH'		=> $user->lang['dl_l_traffic_month'],
			'DL_L_TRAFFIC_TOTAL'		=> $user->lang['dl_l_traffic_total'],
			'DL_L_LAST_UPLOADED'		=> $user->lang['dl_l_last_uploaded'],
		));
		
		}; //END if statistics are enabled



		//IF Categories are enabled
		if($categories_disabled == false){
		
		
			//Create new category
			if(($in->get('cat_name') != '') && ($in->get('cat_submit') != '')){
				if ($user->check_auth('a_downloads_cat', false) == true){
					//Delete Cache
					$pdc->del_suffix('plugin.downloads');
					
					$new_cat = $db->query("INSERT INTO __downloads_categories (category_name, category_comment, category_permission) VALUES 
										('".$db->escape($in->get('cat_name'))."', 
										'".$db->escape($in->get('cat_comment'))."', 
										'".$db->escape($in->get('cat_permission'))."')");
					
					$catid = $db->sql_lastid();
					$set_sort_id = $db->query("UPDATE __downloads_categories SET category_sortid = '".$db->escape($catid)."' WHERE category_id = '".$db->escape($catid)."'");
					$error_msg = sprintf($user->lang['dl_ov_category_created'], sanitize($in->get('cat_name'))); 
		
				};
			}; //END create new category
		
		
		
		
			//Sort
			$order = $in->get('o', '0.0');
			$red = 'RED'.str_replace('.', '', $order);

			$sort_order = array(
				0 => array('date desc', 'date'),			
				1 => array('file_type', 'file_type desc'),
				2 => array('description', 'description desc'),
				3 => array('views', 'views desc'),
				4 => array('file_size desc', 'file_size'),
			);

			$current_order = switch_order($sort_order);
			
			//List Categories if they are enabled
			//Cache: plugin.downloads.categories.{USERID}
			$categories_cache = $pdc->get('plugin.downloads.categories.u'.$user->data['user_id'],false,true);
			if (!$categories_cache) {
				$cat_query = "SELECT * FROM __downloads_categories";
				if ( $user->data['user_id'] == ANONYMOUS ){ $cat_query .= " WHERE category_permission = '1'";};
				$cat_query .= " ORDER BY category_sortid";
				$cat_query = $db->query($cat_query);
				$cat_row = $db->fetch_record_set($cat_query);
				
				$pdc->put('plugin.downloads.categories.u'.$user->data['user_id'],$cat_row,86400,false,true);
			} else{
				$cat_row = $categories_cache;
			};
			

			$cat_count = count($cat_row);
	
			$dl_s_no_cats = ($cat_count < 1) ? true : false;	
			if (is_array($cat_row)) {
			foreach ($cat_row as $elem){
		
				$dls = 0;
				$views = 0;

				//Get Number of Downloads in a Category
				//Cache: plugin.downloads.links_in_cat.{CATID}.{USERID}
				$links_in_cat_cache = $pdc->get('plugin.downloads.links_in_cat.'.$elem['category_id'].".u".$user->data['user_id'],false,true);
				if (!$links_in_cat_cache) {
					$dls_query = "SELECT * from __downloads_links WHERE category = '".$db->escape($elem['category_id'])."'";
					if ( $user->data['user_id'] == ANONYMOUS ){ $dls_query .= " AND permission = '1'";};
					$dls_query = $db->query($dls_query);
					$result = $db->fetch_record_set($dls_query);
					$pdc->put('plugin.downloads.links_in_cat.'.$elem['category_id'].".u".$user->data['user_id'],$result,86400,false,true);
				} else{
					$result = $links_in_cat_cache;
				};
				$dls = $dls + count($result);
				
				//How often the downloads in a category have been downloaded
				if (is_array($result)){
					foreach ($result as $element){
						$views = $views + $element['views'];
					}
				};
		
				$tpl->assign_block_vars('cat_list', array(
					'DL_IS_ADMIN_CAT'			=> $u_is_admin_cat,
					'DL_CAT_DESC'				=> sanitize($elem['category_name']),
					'DL_CAT_ID'					=> sanitize($elem['category_id']),
					'DL_CAT_FILES'				=> sanitize($dls),
					'DL_CAT_VIEWS'				=> sanitize($views),
					'DL_CAT_ROW'				=> $eqdkp->switch_row_class(),
				));

			}; //END foreach
			};

			//If an error with the download occures
			switch ($in->get('error')){
				case "1": System_Message($user->lang['dl_ad_update_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['dl_ad_upload_success'],'Downloads 4 EQdkp-plus','green');
					break;
			}
		  	 
			 
			 $upload_permission = $user->check_auth('u_downloads_upload', false);
			 if ($upload_permission == true){
				$upload_permission = ($dl_s_no_cats == true) ? false : true;
			}			
			
			$admin_optionsarray = array(
		  			  			  
			  0 => array(
                  'name'    => $user->lang['dl_upload_headline'],
                  'link'    => 'javascript:uploadDialog()',
                  'img'     => 'new.png',
                  'perm'    => $upload_permission,
                  ),
			  1 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'admin/statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
             3 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'admin/downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
             4 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'admin/settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			 
              
             
      );

			//If there are no visible categories
			($catcount == 0) ? $nocat = true : $nocat = false;	

			// Send the Output to the template Files.
			$tpl->assign_vars(array(			
				'DL_S_CATEGORIES_DISABLED'			=> $categories_disabled,
				
				//For Creating a new Category
				'DL_PERM_PUBLIC'            		=> $user->lang['dl_perm_public'],
				'DL_PERM_REGISTERED'        		=> $user->lang['dl_perm_registered'],
				'DL_AD_CATEGORY'         			=> $user->lang['dl_up_category'],
				'DL_AD_CREATE'           			=> $user->lang['dl_ad_new_cat'],
				'DL_DESC_HEADLINE'       			=> $user->lang['dl_desc_headline'],
				'DL_PERM_HEADLINE'         			=> $user->lang['dl_perm_headline'],
				'DL_AD_SEND'             			=> $user->lang['dl_ad_send'],
				'DL_AD_RESET'            			=> $user->lang['dl_ad_reset'],
				'DL_JS_FIELDS_EMPTY'				=> $jquery->Dialog_Alert('fields_empty', $user->lang['dl_ad_fields_empty'], $user->lang['dl_ad_fields_empty_title'],'300', '36'),
				
				'DL_HEADLINE'       				=> $user->lang['dl_index_headline'],
				'DL_CATS_HEADLINE'       			=> $user->lang['dl_l_cats'],
				'DL_CAT_HEADLINE'       			=> $user->lang['dl_cat_headline'],
				'DL_FILES_HEADLINE'       			=> $user->lang['dl_files_headline'],
				'DL_VIEWS_HEADLINE'       			=> $user->lang['dl_views_headline'],
				'DL_UPLOAD_HEADLINE'       			=> $user->lang['dl_upload_headline'],
				'DL_CAT_FOOTCOUNT'					=> sanitize(sprintf($user->lang['dl_ov_footcount'], $total_downloads, $cat_count)),
				'DL_OV_ERROR'						=> $error_msg,
				'DL_IS_ADMIN'						=> $u_is_admin,
				'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
				'DL_IS_ADMIN_LINK'					=> $u_is_admin_links,
				'DL_IS_ADMIN_CAT'					=> $u_is_admin_cat,

				$red 								=> '_red',
				'DL_S_NO_CATS'      				=> $dl_s_no_cats,
				'DL_NO_CATS'						=> $user->lang['dl_no_cats'],
				'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],

				'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "admin/edit.php".$SID."&id='+id+'", '750', '400', 'downloads.php'.$SID),
				'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "admin/upload.php".$SID, '750', '400', 'downloads.php'.$SID),
				'ADMIN_MENU'       			 => $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
			));
		}
		else { //Else Categories are disabled
		
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
			);
			
			$current_order = switch_order($sort_order);
			
			//Pagination
			$start = $in->get('start', 0);
			
			//Get number of all downloads
			//Cache: plugin.downloads.links.total.{USERID}
			$links_total_cache = $pdc->get('plugin.downloads.links.total.u'.$user->data['user_id'],false,true);
			if (!$links_total_cache) {
				$total_query = "SELECT count(*) FROM __downloads_links";
				if ( $user->data['user_id'] == ANONYMOUS ){ $total_query .= " WHERE permission = '1'";};
			
				$total_downloads = $db->query_first($total_query);
			
				$pdc->put('plugin.downloads.links.total.u'.$user->data['user_id'],$total_downloads,86400,false,true);
			} else{
				$total_downloads = $links_total_cache;
			};
			
			//Create Pagination
			$pagination = generate_pagination('downloads.php'.$SID.'&amp;o='.sanitize($order), $total_downloads, $conf['items_per_page'], $start);
			
			//Get all Downloads
			//Cache: plugin.downloads.links.{START}.{ITEMS_PER_PAGE}.{ORDER}.{USER_ID}
			$links_cache = $pdc->get('plugin.downloads.links.s'.$start.'.i'.$conf['items_per_page'].'.o'.$order.'.u'.$user->data['user_id'],false,true);
			if (!$links_cache) {
				$downloads_query = "SELECT * FROM __downloads_links";
				if ( $user->data['user_id'] == ANONYMOUS ){ $downloads_query .= " WHERE permission = '1'";};
				$downloads_query .= " ORDER BY ".$db->escape($current_order['sql']);
				$downloads_query .= " LIMIT ".$db->escape($start).",".$db->escape($conf['items_per_page']);
				$links = $db->query($downloads_query);
				$row = $db->fetch_record_set($links);
			
				$pdc->put('plugin.downloads.links.s'.$start.'.i'.$conf['items_per_page'].'.o'.$order.'.u'.$user->data['user_id'],$row,86400,false,true);
			} else{
				$row = $links_cache;
			};
			
			
			$downloads_footcount = sprintf($user->lang['dl_cat_footcount_catsdisabled'], $total_downloads, $conf['items_per_page']);
			
			$dl_s_no_downloads = ($total_downloads < 1) ? true : false;
		
			foreach ($row as $elem){
				$tpl->assign_block_vars('link_list', array(
					'DL_LINK_NAME'			=> sanitize($elem['name']),
					'DL_LINK_DESC'			=> $html->ToolTip(sanitize($elem['description'], 55), sanitize($dlclass->wrapText($elem['description'], 55))),
					'DL_LINK_VIEWS' 		=> sanitize($elem['views']),
					'DL_LINK_ID' 			=> sanitize($elem['id']),
					'DL_LINK_RATING' 		=> sanitize($elem['rating']),
					'DL_LINK_SIZE' 			=> sanitize($dlclass->human_size($elem['file_size'])),
					'DL_ROW_CLASS'			=> $eqdkp->switch_row_class(),
					'DL_LINK_EXT_IMAGE'		=> $dlclass->extension_image(sanitize($elem['file_type'])),
					'DL_L_DOWNLOAD_IT'		=> sanitize(sprintf($user->lang['dl_l_download_it'], $elem['name'])),
					'DL_LINK_REPORTED'		=> (unserialize($elem['reported'])) ? '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png" title="'.$user->lang['dl_l_reported_info'].'">' : '',
						
				));
			};
			
			switch ($in->get('error')){
				case "1": System_Message($user->lang['dl_ad_update_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "2": System_Message($user->lang['dl_ad_delete_success'],'Downloads 4 EQdkp-plus','green');
					break;
				case "3": System_Message($user->lang['dl_ad_upload_success'],'Downloads 4 EQdkp-plus','green');
					break;
			}
			
			$admin_optionsarray = array(
				0 => array(
                  'name'    => $user->lang['dl_upload_headline'],
                  'link'    => 'javascript:uploadDialog()',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('u_downloads_upload', false),
                  ),
				1 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'admin/statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              2 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "admin/categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
             3 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'admin/downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
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
				'DL_S_CATEGORIES_DISABLED'			=> $categories_disabled,
				'DL_HEADLINE'       				=> $user->lang['dl_index_headline'],
				'DL_S_NO_DOWNLOADS'					=> $dl_s_no_downloads,
				'DL_NO_DOWNLOADS'					=> "<i>".$user->lang['pm_lu_nolinks']."</i>",
				'DL_PAGINATION'						=> $pagination,
			
				'DL_TYPE_HEADLINE'       			=> $user->lang['dl_type_headline'],
				'DL_NAME_HEADLINE'       			=> $user->lang['dl_name_headline'],
				'DL_DESC_HEADLINE'       			=> $user->lang['dl_desc_headline'],
				'DL_VIEWS_HEADLINE'       			=> $user->lang['dl_views_headline'],
				'DL_SIZE_HEADLINE'       			=> $user->lang['dl_filesize_headline'],
				'DL_RATING_HEADLINE'       			=> $user->lang['dl_rating_headline'],
				'DL_ACTION_HEADLINE'				=> $user->lang['dl_action_headline'],
				'DL_UPLOAD_HEADLINE'				=> $user->lang['dl_upload_headline'],
				'DL_CAT_FOOTCOUNT'					=> sanitize($downloads_footcount),	
			
				'DL_OV_ERROR'						=> $error_msg,
				'DL_IS_ADMIN_UPLOAD'				=> $u_is_admin_upload,
				'DL_IS_ADMIN_LINK'					=> $u_is_admin_links,
				'DL_IS_ADMIN_CAT'					=> $u_is_admin_cat,
				'DL_IS_ADMIN'						=> $u_is_admin,

				$red 								=> '_red',
				'DL_TITLE_LINK_DELETE'				=> $user->lang['dl_title_link_delete'],
				'DL_TITLE_LINK_EDIT'				=> $user->lang['dl_title_link_edit'],
				'DL_TITLE_CAT_EDIT'					=> $user->lang['dl_title_cat_edit'],
				'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],

				'DL_JS_EDIT'						=> $jquery->Dialog_URL('editDownload', $user->lang['dl_title_link_edit'], "admin/edit.php".$SID."&id='+id+'", '750', '400', 'downloads.php'.$SID),
				'DL_JS_UPLOAD'						=> $jquery->Dialog_URL('uploadDialog', $user->lang['dl_upload_headline'], "admin/upload.php".$SID, '750', '400', 'downloads.php'.$SID),
				'ADMIN_MENU'       			 		=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),
			));
		
		
		} //END if Categories are disabled


		// Init the Template Shit
		$eqdkp->set_vars(array(
			'page_title'           => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline'],
			'template_path'        => $pm->get_data('downloads', 'template_path'),
			'template_file'        => 'overview.html',
			'display'              => true)
		);




	} //END Just normal overview
} //END if mode ist not categories



?>