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

//Comments
if ($conf['enable_comments'] == 1){
	if(is_object($pcomments) && $pcomments->version > '1.0.3'){
			$comm_settings = array(
				'attach_id' => $in->get('id'), 
				'page'      => 'mediacenter',
				'auth'      => 'a_mediacenter_',
			);
		  
			$pcomments->SetVars($comm_settings);
				$tpl->assign_vars(array(
					'ENABLE_COMMENTS'     => true,
					'COMMENTS'            => $pcomments->Show(),
			));
		
	};
}

//Reset Votes
if ($in->get('mode') == 'del_votes' && $u_is_admin == true){
				
	$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", array(
					'rating_points'			=> 0,
					'votes'					=> 0,
					'rating'				=> 0,
					'user_voted'			=> "",
				));
	$pdc->del_prefix('plugin.mediacenter.media');
};

//Repair Download
if ($in->get('mode') == 'repaired' && $u_is_admin == true){
		
	$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", array(
					'reported'			=> "",
				));
	$pdc->del_prefix('plugin.mediacenter.media');
};
		
//Vote-mode
if ($in->get('mode') == 'vote'){
	
	if ($in->get('vote')){

		$vote_query = "SELECT * FROM __mediacenter_media WHERE id = '".$db->escape($in->get('id',0))."'";
		$vote_query = $db->query($vote_query);
		$vote = $db->fetch_record($vote_query);

		if ($vote){
			if ($conf['single_vote'] == 1){
				
				$users_voted = unserialize($vote['voted_users']);

				if (!$users_voted[$user->data['user_id']]){
					$users_voted[$user->data['user_id']] = $in->get('vote');
					$rating_points = $vote['rating_points'] + $in->get('vote');
					$votes = $vote['votes'] + 1;
					$rating = round($rating_points / $votes);
					

					$pdc->del_prefix('plugin.mediacenter.media');
				
					$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", array(
						'rating_points'			=> $db->escape($rating_points),
						'votes'					=> $db->escape($votes),
						'rating'				=> $db->escape($rating),
						'user_voted'			=> serialize($users_voted),
					));
					
				};
				
			} else {
			
				$rating_points = $vote['rating_points'] + $in->get('vote');
				$votes = $vote['votes'] + 1;
				$rating = round($rating_points / $votes);
				

			
				$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", array(
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


//REPORT the Video
if ($in->get('report_but') != ""){

	if ( $user->data['user_id'] != ANONYMOUS ){
	
		$download_query = $db->query("SELECT * FROM __mediacenter_media WHERE id = '".$db->escape($in->get('id'))."'");
		$download = $db->fetch_record($download_query);
		
		$options = array(
				'sender_mail'		=> $conf_plus['lib_email_sender_email'],
				'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
				'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
				'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
				'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
				'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
		);
		
		
		
		$mail = new MyMailer($options);
		$DKP_server_url = $mail->BuildLink();
						
		//Look if the download or mirror is reported - don't make it twice...
		$reported = unserialize($download['reported']);
	
		$is_reported = ($reported != "") ? true : false;
	
		
		if ($is_reported == false){
	
			
				$reported_array = unserialize($download['reported']);
				$reported_array[$user->data['user_id']] = $in->get('dl_comment');
			
				$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", 				
					array(
						  'reported'	=> serialize($reported_array),
									
					));
		
			
				$bodyvars = array(
				  'USERNAME'    		=> "Administrator",
				  'DL_NAME'   			=> $download['name'],
				  'DL_LINK'   			=> $DKP_server_url."plugins/mediacenter/file.php?id=".sanitize($download['id']),
				  'DATE'  				=> date("Y-m-d H:i"),
				  'REPORTER'    		=> sanitize($user->data['username']),
				  'REPORTER_COMMENT'	=> sanitize($in->get('dl_comment')),
				  'GUILDNAME'			=> $eqdkp->config['guildtag'],
				);
				
				//Send mail to the admin - if activated
				if ($conf['disable_reportmail'] != 1){
					$mail->SendMailFromAdmin($eqdkp->config['admin_email'], $user->lang['mc_reportmail_subject'], 'report.html', $bodyvars, $conf_plus['lib_email_method']);
				}
	
		
		}; //END if is_reported == false
	
	
	}; //Close if user if logged in
	
} //Close if submit

//CLOSE REPORT


//Get Data of the video
$data_query = $db->query("SELECT * FROM __mediacenter_media WHERE id='".$db->escape($in->get('id'))."'");
$media = $db->fetch_record($data_query);

if ($media['preview_image'] == "" && $media['extension'] == "youtube"){

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
			
			$media['duration'] = $duration;
		}
				
			
}

//Redirect user if Video is not confirmed
if (($media['status'] == 0 && $u_is_admin == false) || !$media){redirect('plugins/mediacenter/');}

//Count up the views
$update_query = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($in->get('id',0))."'", array(
					        	'views'			=> $media['views'] + 1,
							));
$media['views'] = $media['views'] + 1;

$date = date("Y-m");

//Statistics if enabled
if ($conf['enable_statistics']){

	$stat_query = $db->query("SELECT * FROM __mediacenter_stats WHERE fileID='".$db->escape($in->get('id'))."' AND date = '".$date."-01'");
	$stat_data = $db->fetch_record($stat_query);
	if ($stat_data){
		$stat_array = unserialize($stat_data['count']);
	
		$stat_array[date("d")] = $stat_array[date("d")] + 1;
		$db->query("UPDATE __mediacenter_stats SET :params WHERE id ='".$db->escape($stat_data['ID'])."'", array(
									'count'					=> serialize($stat_array),
							));		
		
		
	} else {
		$stat_array[date("d")] =  1;
		$db->query("INSERT INTO __mediacenter_stats :params", array(
									'fileID'				=> $in->get('id'),
									'category'				=> $media['category'],
									'date'					=> date("Y-m")."-01",
									'count'					=> serialize($stat_array),
							));		
	}
}

//Get more videos from the category
$more_cat_query = $db->query("SELECT * FROM __mediacenter_media WHERE category = '".$db->escape($media['category'])."'AND id != '".$db->escape($in->get('id'))."' LIMIT 10");
$more_cat = $db->fetch_record_set($more_cat_query);

if (is_array($more_cat)){

	foreach($more_cat as $elem){
	
		$tpl->assign_block_vars('more_from_cat_list', array(
			"THUMBNAIL" => 			$mcclass->show_thumbnail(sanitize($elem['thumbnail']), $elem['type'], $elem['extension']),
			"NAME"		=>			sanitize($elem['name']),
			"ID"		=>			sanitize($elem['id']),
			"ROW_CLASS" =>			$eqdkp->switch_row_class(),
			"RATING"		=>		sanitize($elem['rating']),
															
		));
	};

};

//Get more videos from the uploader
$more_uploader_query = $db->query("SELECT * FROM __mediacenter_media WHERE user_id = '".$db->escape($media['user_id'])."'AND id != '".$db->escape($in->get('id'))."' LIMIT 10");
$more_uploader = $db->fetch_record_set($more_uploader_query);

if (is_array($more_uploader)){

	foreach($more_uploader as $elem){
	
		$tpl->assign_block_vars('more_from_uploader_list', array(
			"THUMBNAIL" => 			$mcclass->show_thumbnail(sanitize($elem['thumbnail']), $elem['type'], $elem['extension']),
			"NAME"		=>			sanitize($elem['name']),
			"RATING"		=>		sanitize($elem['rating']),
			"ID"		=>			sanitize($elem['id']),
			"ROW_CLASS" =>			$eqdkp->switch_row_class(),
															
		));
	};

};



//Get related videos
$related_query = $db->query("SELECT * FROM __mediacenter_media WHERE MATCH(name, tags) AGAINST('".trim($db->escape($media['name']).' '.$db->escape($tags))."' IN BOOLEAN MODE) AND id != '".$db->escape($in->get('id'))."' LIMIT 10; ");
$related_query = $db->fetch_record_set($related_query);

if (is_array($related_query)){

	foreach($related_query as $elem){
	
		$tpl->assign_block_vars('related_list', array(
			"THUMBNAIL" => 			$mcclass->show_thumbnail(sanitize($elem['thumbnail']), $elem['type'], $elem['extension']),
			"NAME"		=>			sanitize($elem['name']),
			"ID"		=>			sanitize($elem['id']),
			"ROW_CLASS" =>			$eqdkp->switch_row_class(),
			"RATING"		=>		sanitize($elem['rating']),
															
		));
	};

};

//Get name of Uploader
$uploader_query = $db->query("SELECT * from __users WHERE user_id = '".$db->escape($media['user_id'])."'");
$uploader = $db->fetch_record($uploader_query);

//Make Tags clickable
$tags_array = explode(',',sanitize($media['tags']));
$tags = "";
foreach($tags_array as $elem){
	$elem = trim($elem);
	if ($elem != ""){
		$tags .= '<a href="search.php'.$SID.'&tag='.rawurlencode($elem).'">'.$elem.'</a>, ';
	}
}

//Get Name of Category
$cat_query = $db->query("SELECT * FROM __mediacenter_categories WHERE category_id = '".$db->escape($media['category'])."'");
$cat_data = $db->fetch_record($cat_query);

$local = ($media['local_filename'] != "") ? true : false;
//Generate the player
$player = $mcclass->generate_player(sanitize($media['extension']), sanitize($media['url']), sanitize($media['preview_image']), $media['type'], $local);

//Rating-Options
$myRatings = array(
	'1'		=> '1 '.$user->lang['mc_l_bad'],
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'	=> '10 '.$user->lang['mc_l_good'],
);
		
//If Download or Mirror has been reported
if (unserialize($media['reported'])){
	
	$tpl->assign_vars(array(			
		'S_REPORTED' => true,
		'L_REPORTED' => sprintf($user->lang['mc_reported_info'],"file.php".$SID."&id=".sanitize($media['id'])."&mode=repaired"),
		"REPORTED_IMAGE" => '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png" title="'.$user->lang['mc_reported_info'].'">',	
	));

	foreach (unserialize($media['reported']) as $elem=>$key){
			$reporter_query = $db->query("SELECT * from __users WHERE user_id = '".$db->escape($elem)."'");
			$reporter = $db->fetch_record($reporter_query);

			$tpl->assign_block_vars('reported_list', array(
				"CONTENT" =>  $user->lang['mc_reporter']." ".$reporter['username'].", ".$user->lang['mc_reason']." ".$key,										   
			));


	}
	
};
		

//Prevent more votes from one user if it is disabled			
if ($conf['single_vote'] == 1){
	$users_voted = unserialize($media['user_voted']);
	$u_has_voted = (!$users_voted[$user->data['user_id']]) ? false : true;	
} else {
	$u_has_voted = false;
}

//Admin-Menu
 $admin_optionsarray = array(
	  0 => array(
		  'name'    => $user->lang['mc_confirm'],
		  'link'    => 'admin/media.php'.$SID.'&mode=confirm&id='.sanitize($media['id']),
		  'img'     => 'ok.png',
		  'perm'    => ($media['status'] == 0) ? true : false,
		  ),
	  
	  1 => array(
		  'name'    => $user->lang['mc_edit_video'],
		  'link'    => 'javascript:editDialog()',
		  'img'     => 'edit.png',
		  'perm'    => $user->check_auth('a_mediacenter_media', false),
		  ),
	  2 => array(
		  'name'    => $user->lang['mc_delete_video'],
		  'link'    => 'index.php'.$SID.'&delete='.sanitize($media['id']),
		  'img'     => 'delete.png',
		  'perm'    => $user->check_auth('a_mediacenter_media', false),
		  ),
	  
	 3 => array(
		  'name'    => $user->lang['mc_create_video'],
		  'link'    => 'javascript:uploadDialog()',
		  'img'     => 'new.png',
		  'perm'    => $user->check_auth('u_mediacenter_upload', false),
		  ),
	 4 => array(
		  'name'    => $user->lang['mc_stats'],
		  'link'    => 'admin/statistics.php'.$SID,
		  'img'     => 'statistics.png',
		  'perm'    => $user->check_auth('a_mediacenter_stats', false),
		  ),  
	  5 => array(
		  'name'    => $user->lang['mc_manage_categories'],
		  'link'    => "admin/categories.php".$SID,
		  'img'     => 'cat_edit.png',
		  'perm'    => $user->check_auth('a_mediacenter_media', false),
		  ),
	  6 => array(
		  'name'    => $user->lang['mc_manage_videos'],
		  'link'    => 'admin/media.php'.$SID,
		  'img'     => 'edit.png',
		  'perm'    => $user->check_auth('a_mediacenter_media', false),
		  ),
				  
	  7 => array(
		  'name'    => $user->lang['mc_config'],
		  'link'    => 'admin/settings.php'.$SID,
		  'img'     => 'wrench.png',
		  'perm'    => $user->check_auth('a_mediacenter_cfg', false),
		  ),

	  
	 
);

//Get Number of Comments
$comment_count = $db->query_first("SELECT count(*) FROM __comments WHERE attach_id='".$db->escape($in->get('id'))."' AND page='mediacenter'");

$bbclass = new BBcode();
$bbclass->SetSmiliePath($eqdkp_root_path."libraries/jquery/images/editor/icons");
$html_story = $bbclass->MyEmoticons(sanitize($media['story'])); //Emoticons
$html_story = $bbclass->toHTML($html_story); //Make normal BB-Codes into HTML
		
//Template Vars
$tpl->assign_vars(array(			
			
			'SHOW_CATEGORIES'		=> $mcclass->show_categories(),
			'MEDIA_PLAYER'			=> $player,
			'MEDIA_NAME'			=> sanitize($media['name']),
			'MEDIA_DESC'			=> $mcclass->wrapDesc(sanitize($media['description']), 60),
			'MEDIA_DATE'			=> sanitize($media['date']),
			'MEDIA_UPLOADER'		=> sanitize($uploader['username']),
			'MEDIA_VIEWS'			=> sanitize($media['views']),
			'MEDIA_RATING'			=> sanitize($media['rating']),
			'MEDIA_VOTES'			=> sanitize($media['votes']),
			'MEDIA_ID'				=> sanitize($media['id']),
			'MEDIA_CAT_ID'			=> sanitize($media['category']),
			'MEDIA_CAT'				=> sanitize($cat_data['category_name']),
			'MEDIA_STORY'			=> $html_story,
			'MEDIA_TAGS'			=> $tags,
			'MEDIA_COMMENT_COUNT'	=> sanitize($comment_count),
			'MC_L_MEDIACENTER'		=> $user->lang['mc_mediacenter_short'],
			'MC_L_SEARCH_INPUTVALUE' => $user->lang['mc_search_inputvalue'],
			'MC_IS_ADMIN'			=> $u_is_admin,
			'MC_IS_UPLOADER'		=> $u_is_uploader,
			'MC_L_UPLOAD'			=> $user->lang['mc_create_video'],
			'L_INFORMATION'			=> $user->lang['mc_information'],
			'L_DESC'				=> $user->lang['mc_description'],
			'L_UPLOADED'			=> $user->lang['mc_uploaded'],
			'L_UPLOADER'			=> $user->lang['mc_uploader'],
			'L_TAGS'				=> $user->lang['mc_tags'],
			'L_MORE_FROM'			=> $user->lang['mc_more_from'],
			'L_MORE_FROM_CAT'		=> $user->lang['mc_more_from_cat'],
			'L_RELATED_VIDEOS'		=> $user->lang['mc_related_videos'],
			'L_VIEWS'				=> $user->lang['mc_views'],
			'L_VOTES'				=> $user->lang['mc_ratings'],
			'L_RESET_RATINGS'		=> $user->lang['mc_reset_ratings'],
			'L_BOOKMARK'			=> $user->lang['mc_bookmark'],
			'L_SHARE'				=> $user->lang['mc_share'],
			'L_REPORT'				=> $user->lang['mc_report'],
			'L_EMBED'				=> $user->lang['mc_embed'],
			'L_EMBED_INFO'			=> $user->lang['mc_embed_info'],
			'L_RESET'				=> $user->lang['mc_reset'],
			'L_REPORT_INFO'			=> $user->lang['mc_report_info'],
			'L_SHARE_INFO'			=> $user->lang['mc_share_info'],
			'L_STORY'				=> $user->lang['mc_story'],
			'L_COMMENTS'			=> $user->lang['news_comments'],

			'MC_S_STORY'			=> ($media['story'] != "") ? true : false,
			'EMBED_CODE'			=> htmlspecialchars($mcclass->generate_player(sanitize($media['extension']), sanitize($media['url']), sanitize($media['preview_image']), $media['type'])),
			'DKP_URL'				=> $mcclass->generate_dkp_link(),
			
			'MC_ADMIN_MENU'       	=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),
			'MC_JS_UPLOAD'			=> $jquery->Dialog_URL('uploadDialog', $user->lang['mc_create_video'], "upload.php".$SID, '750', '400'),
			'MC_JS_EDIT'			=> $jquery->Dialog_URL('editDialog', $user->lang['mc_edit_video'], "edit.php".$SID.'&id='.sanitize($media['id']), '750', '400'),
			
			//JavaScripts
			'JS_ACTION_TABS'	=> $jquery->Tab_header('mc_action_tabs'),
			'MEDIA_RATING_STARS'			=> $jquery->StarRating('vote', $myRatings,'file.php'.$SID.'&id='.sanitize($in->get('id')).'&mode=vote',sanitize($media['rating']),  $u_has_voted),
			'MC_OV_ERROR'		=> $error_msg,
			
			// Image-Resizer	
  	'S_IMG_RESIZE_ENABLE'         => 'true',
 	'S_MAX_POST_IMG_RESIZE_WIDTH' => '550',
 	'S_IMG_RESIZE_WARNING'        => $user->lang['air_img_resize_warning'] , 
 	'S_IMG_WARNING_ACTIVE'        => 'false', 
 	'S_LYTEBOX_THEME'             => 'grey',
 	'S_LYTEBOX_AUTO_RESIZE'       => '1',
 	'S_LYTEBOX_ANIMATION'         => '1', 
			
				
		
	
));
	

//Init the Template Shit for the categories
$eqdkp->set_vars(array(
			'page_title'        => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['mc_mediacenter_short']." - ".$user->lang['mc_type_video'].": ".sanitize($media['name']),
			'template_path'     => $pm->get_data('mediacenter', 'template_path'),
			'template_file'     => 'file.html',
			'display'           => true)
);

?>