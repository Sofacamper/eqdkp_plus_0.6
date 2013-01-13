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


//Ajax-Mode: xml
if (($in->get('mode') == "ajax") && (is_numeric($in->get('id')))){
		
		//Cache: plugin.mediacenter.media.{ID}
		$media = $pdc->get('plugin.mediacenter.media.'.$in->get('id'),false,true);
		if (!$media){
			$media_query = $db->query("SELECT * FROM __mediacenter_media WHERE id='".$db->escape($in->get('id'))."'");
			$media = $db->fetch_record($media_query);
			$db->free_result($media_query);
			$pdc->put('plugin.mediacenter.media.'.$in->get('id'),$media,86400,false,true);
		};
			
	
		
		
		switch($media['type']){
			case "video" :
					$type = $user->lang['mc_type_video'];
					if ($media['duration'] != ""){$duration = date("i:s", sanitize($media['duration']));};
					switch($media['extension']){
						case "youtube" : 
						
							if ($media['preview_image'] != ""){
								$preview_image = $media['preview_image'];
								
							} else {
				
									$video_id = preg_replace("#(^|[\n ])([\w]+?://)(www\.youtube|youtube)(\.[\w\.]+?/watch\?v=)([\w-]+)([&][\w=+&;%]*)*(^[\t <\n\r\]\[])*#is", '\\5', $media['url']);
									if ($video_id){
										$result = $mcclass->get_youtube_feed($video_id);
										$duration = $result[2];
										$thumbnail = $result[0];
										$preview_image = $result[1];
										$sql = $db->query("UPDATE __mediacenter_media SET :params WHERE id='".$db->escape($media['id'])."'", array(
										'duration' 		=> $duration,
										'thumbnail' 	=> $thumbnail,
										'preview_image' => $preview_image,						
										));
									}
								
								if ($result == false){
									$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
								}	
							
							}
						
						break;
						
						case "dailymotion" :
							
							if ($media['preview_image'] != ""){	
								$preview_image = $media['preview_image'];							
							} else {
								$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
							}
						
						break;
						
						case "myvideo" :
							
							if ($media['preview_image'] != ""){	
								$preview_image = $media['preview_image'];							
							} else {
								$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
							}
						
						break;
						
						case "sevenload" :
							
							if ($media['preview_image'] != ""){	
								$preview_image = $media['preview_image'];							
							} else {
								$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
							}
						
						break;
						
						default: 
						
							if ($media['preview_image'] != ""){	
								$preview_image = $pcache->FilePath('thumbs_b/'.$media['preview_image'], 'mediacenter');							
							} else {
								$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
							}
					
					}
					

			break;
			
			case "image" : 
						if ($media['preview_image'] != ""){
							$preview_image = $pcache->FilePath('thumbs_b/'.$media['preview_image'], 'mediacenter');
						} else {
							$preview_image = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_image.png";
						}
						$type = $user->lang['mc_type_image'];
						$duration = sanitize($media['duration']);
		
		}
					
		header("Content-type: text/xml");
		header("Cache-Control: no-cache"); 
		echo '<?xml version="1.0"?><labels><label id="1"><id>'.sanitize($media['id']).'</id><name>'.utf8_encode(sanitize($media['name'])).'</name><desc>'.utf8_encode($mcclass->wrapText(sanitize($media['description']), 60)).'</desc><preview_img>'.utf8_encode(sanitize($preview_image)).'</preview_img><type>'.sanitize($type).'</type><type_clean>'.$media['type'].'</type_clean><duration>'.sanitize($duration).'</duration><rating>'.sanitize($media['rating']).'</rating><votes>('.sanitize($media['votes']).' '.$user->lang['mc_votes'].')</votes></label></labels>';
		die();
		
}; //END Ajax-Mode

//Delete a video
if (is_numeric($in->get('delete'))){	
	$mcclass->delete_one_link($in->get('delete'));	
}

//Get newest Ones
$newest_query = $pdc->get('plugin.mediacenter.newest',false,true);
if (!$newest_query){
	$newest_query = $db->query("SELECT * FROM __mediacenter_media WHERE status='1' ORDER BY date DESC LIMIT 0,9");
	$newest_query = $db->fetch_record_set($newest_query);
	$db->free_result($newest_query);
	$pdc->put('plugin.mediacenter.newest',$newest_query,86400,false,true);
};
		
if (is_array($newest_query)){
	foreach ($newest_query as $row){
		
		$tpl->assign_block_vars('newest_list', array(
				'ID'			=> sanitize($row['id']),									 
 				'NAME'			=> sanitize($row['name']),
				'DATE'			=> sanitize($row['date']),
				'DESC'			=> sanitize($row['description']),
				'RATING'		=> sanitize($row['rating']),
				'CATEGORY'		=> sanitize($row['category']),
				'THUMBNAIL'		=> $mcclass->show_thumbnail(sanitize($row['thumbnail']), $row['type'], $row['extension']),
				'REPORTED'		=> ($row['reported'] != "" && $u_is_admin == true) ? '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_l_reported_info'].'">' : "",
						
		));
	}
}

//Get best ratest ones
$best_rated_query = $pdc->get('plugin.mediacenter.bestrated',false,true);
if (!$best_rated_query){
	$best_rated_query = $db->query("SELECT * FROM __mediacenter_media WHERE status='1' ORDER BY rating DESC LIMIT 0,9");
	$best_rated_query = $db->fetch_record_set($best_rated_query);
	$db->free_result($best_rated_query);
	$pdc->put('plugin.mediacenter.bestrated',$best_rated_query,86400,false,true);
};

if (is_array($best_rated_query)){
	foreach ($best_rated_query as $row){
		
		
		
		$tpl->assign_block_vars('bestrated_list', array(
				'ID'			=> sanitize($row['id']),									 
 				'NAME'			=> sanitize($row['name']),
				'DESC'			=> sanitize($row['description']),
				'RATING'		=> sanitize($row['rating']),
				'CATEGORY'		=> sanitize($row['category']),
				'THUMBNAIL'		=> $mcclass->show_thumbnail(sanitize($row['thumbnail']), $row['type'],$row['extension']),
				'REPORTED'		=> ($row['reported'] != "" && $u_is_admin == true) ? '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_l_reported_info'].'">' : "",
						
		));
	}
}

//Get most viewed ones
$most_viewed_query = $pdc->get('plugin.mediacenter.mostviewed',false,true);
if (!$most_viewed_query){
	$most_viewed_query = $db->query("SELECT * FROM __mediacenter_media WHERE status='1' ORDER BY views DESC LIMIT 0,9");
	$most_viewed_query = $db->fetch_record_set($most_viewed_query);
	$db->free_result($most_viewed_query);
	$pdc->put('plugin.mediacenter.mostviewed',$most_viewed_query,86400,false,true);
};

if (is_array($most_viewed_query)){
	foreach ($most_viewed_query as $row){
		
		$tpl->assign_block_vars('mostviewed_list', array(
 				'ID'			=> sanitize($row['id']),									 
 				'NAME'			=> sanitize($row['name']),
				'DESC'			=> sanitize($row['description']),
				'RATING'		=> sanitize($row['rating']),
				'CATEGORY'		=> sanitize($row['category']),
				'THUMBNAIL'		=> $mcclass->show_thumbnail(sanitize($row['thumbnail']), $row['type'], $row['extension']),
				'REPORTED'		=> ($row['reported'] != "" && $u_is_admin == true) ? '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_l_reported_info'].'">' : "",
				
						
		));
	}
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

//Template Vars
$tpl->assign_vars(array(			
			
			'MC_CATEGORIES'			=> $mcclass->show_categories(),
			'MC_JS_TAB_MEDIA' 		=> $jquery->Tab_header('media_tabs'),
			'MC_START_ID'			=> ($newest_query[0]['id']) ? $newest_query[0]['id'] : 0,
			'MC_L_MEDIACENTER'		=> $user->lang['mc_mediacenter_short'],
			'MC_L_SEARCH_INPUTVALUE' => $user->lang['mc_search_inputvalue'],
			'MC_L_NEWEST'			=> $user->lang['mc_newest'],
			'MC_L_BESTRATED'		=> $user->lang['mc_best_rated'],
			'MC_L_MOSTVIEWED'		=> $user->lang['mc_most_viewed'],
			'MC_IS_ADMIN'			=> $u_is_admin,
			'MC_IS_UPLOADER'		=> $u_is_uploader,
			'MC_L_UPLOAD'			=> $user->lang['mc_create_video'],
			
			'MC_ADMIN_MENU'       	=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),
			'MC_JS_UPLOAD'			=> $jquery->Dialog_URL('uploadDialog', $user->lang['mc_create_video'], "upload.php".$SID, '750', '400', 'index.php'.$SID),
			
	// Image-Resizer	
    'S_IMG_RESIZE_ENABLE'         => 'true',
 	'S_MAX_POST_IMG_RESIZE_WIDTH' => '450',
 	'S_IMG_RESIZE_WARNING'        => $user->lang['air_img_resize_warning'] , 
 	'S_IMG_WARNING_ACTIVE'        => 'false', 
 	'S_LYTEBOX_THEME'             => 'grey',
 	'S_LYTEBOX_AUTO_RESIZE'       => '1',
 	'S_LYTEBOX_ANIMATION'         => '1', 

		
				
		
	
));
	

//Init the Template Shit for the categories
$eqdkp->set_vars(array(
			'page_title'        => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['mc_mediacenter_short'],
			'template_path'     => $pm->get_data('mediacenter', 'template_path'),
			'template_file'     => 'overview.html',
			'display'           => true)
);

?>