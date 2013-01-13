
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
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }

// Check user permission
$user->check_auth('u_mediacenter_upload');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');

if ($in->get('ref') == "acp"){
	$ref = "admin/media.php".$SID;
} else {
	$ref = "index.php".$SID;
};

//IF Video has uploaded successfully to youtube
//STEP 3
if ($in->get('status') == 200){
		
		$id = $in->get('id');
		$fid = $in->get($eqdkp->config['cookie_name'] . '_mclid');
		
		$sql = $db->query("UPDATE __mediacenter_media SET :params WHERE id = '".$db->escape($fid)."'", array(
				'url' 			=> "http://www.youtube.com/watch?v=".$id,				
			));
		$pdc->del_prefix('plugin.mediacenter');

	
		$tpl->assign_vars(array(
			"S_YOUTUBE_STEP_3"		=> true,
			"S_YOUTUBE"				=> true,
			"YOUTUBE_VIDEO_URL"		=> "http://www.youtube.com/watch?v=".$id,
		));
		
};
	
//If form has been submitted
if ($in->get('submit') != ""){
	
	//STEP 2
	if ($in->get('mode') == "youtube"){
	
	$devcode = "AI39si65yJe8qL2xVsKFQyLMQ4Aml7BT-Y0zvOHOh7UfE3DaGaC6eg6zA4PPWoOOTFGMfEiJs4Q7-XBLqedaLL8BX_EsBMiT8Q";
	
	
	$yt_pw = $in->get('yt_pw');
	$yt_nick = $in->get('yt_nick');
	$auth = $mcclass->youtube_login($yt_nick, $yt_pw, "test");

	if ( $auth[0] == false) {
		System_Message($user->lang['mc_youtube_auth_failed'],'EQdkp-MediaCenter','red');
		
	} else {
		$auth = $auth[1];

		//Save into DB
		$status = ($conf['admin_activation'] == 1) ? 0 : 1;
		$status = ($user->check_auth('a_mediacenter_', false) == true) ? 1 : $status;
			
			
			$sql = $db->query("INSERT INTO __mediacenter_media :params", array(
				'url' 			=> "",
				'name' 			=> $in->get('name'),
				'extension' 	=> "youtube",
				'type' 			=> "video",
				'description' 	=> $in->get('description'),
				'category' 		=> $in->get('category'),
				'date' 			=> date("Y-m-d H:i:s"),
				'user_id' 		=> $user->data['user_id'],
				'tags' 			=> $in->get('tags'),
				'story' 		=> $in->get('story2'),
				'status'		=> $status,
				
			));
			$last_id = $db->insert_id();
			$pdc->del_prefix('plugin.mediacenter');
			$test = setcookie($eqdkp->config['cookie_name'] . '_mclid', $last_id, 0, $eqdkp->config['cookie_path'], $eqdkp->config['cookie_domain']);

			$upload_token = $mcclass->youtube_uploadToken($in->get('name'), $in->get('description'), "People", $in->get('tags'), $auth, $devcode);


			if (!$upload_token[0]){
				System_Message($user->lang['mc_youtube_auth_failed'],'EQdkp-MediaCenter','red');
				
			} else {
				$tpl->assign_vars(array(
					"S_YOUTUBE_STEP_2"		=> true,
					"S_YOUTUBE"				=> true,
					"YOUTUBE_UPLOAD_TOKEN"	=> sanitize($upload_token[1][1]),
					"YOUTUBE_UPLOAD_FORM"	=> sanitize($upload_token[1][0]).'?nexturl='.$mcclass->generate_dkp_link().'plugins/mediacenter/upload.php',
					
				));
			}



}

	
}



	//Images - not used atm
	if ($in->get('mode') == "image"){
	
		if ($in->get('name') != ""){
			
			$filename = $_FILES['file']['name'];
			$upload_status = $mcclass->upload_file("file","jpg, jpeg, gif, png",0, true, "files/");
				
				switch ($upload_status) {
					case "0": $error_msg = $user->lang['mc_ad_fields_empty'];
						break;
					case "1": $error_msg = $user->lang['mc_ad_upload_file_exists'];
						break;
					case "2": $error_msg = $user->lang['mc_ad_upload_fail_file'];
						break;
					default:
						$upload = true;
						$extension = strtolower(substr($filename, strrpos($filename, '.') + 1));
				}
			
			$thumbnail_status = $mcclass->create_thumbnail($upload_status, "files","thumbs", $height="70", $width="200");
									if ($thumbnail_status != "1"){
										$thumbnail = $thumbnail_status; 
									};
						
			$preview_image_status = $mcclass->create_thumbnail($upload_status, "files","thumbs_b", $height="380", $width="500");

									if ($preview_image_status != "1"){
										$preview_image = $preview_image_status; 
									};
			
			if ($upload == true){
				$status = ($conf['admin_activation'] == 1) ? 0 : 1;
				
				$sql = $db->query("INSERT INTO __mediacenter_media :params", array(
					'url' => $filename,
					'name' => $in->get('name'),
					'extension' => $extension,
					'type' => 'image',
					'description' => $in->get('description'),
					'category' => $in->get('category'),
					'date' => date("Y-m-d H:i:s"),
					'user_id' => $user->data['user_id'],
					'thumbnail' => $thumbnail,
					'preview_image' => $preview_image,
					'local_filename' => $upload_status,
					'tags' => $in->get('tags'),
					'status'	=> $status,
					
				));
				
				redirect('plugins/mediacenter/'.$ref);
			}
			
			
		} else {
			$error_msg = $user->lang['mc_ad_fields_empty'];
		}
	

	};
	
	
	
	
	//Video-Mode
	if ($in->get('mode') == "video") {

		if ($in->get('name') != "" && $in->get('url') != ""){
			$filename = $in->get('url');
			$file_type = strtolower(substr($filename, strrpos($filename, '.') + 1));
			if ((strlen($file_type) < 2) || strlen($file_type) > 5 ){$file_type = "unknown";};

			$link = $in->get('url');
			$duration = $in->get('duration');
			$duration_parts = explode(':',$duration);
			$duration = $duration_parts[0]*60 + $duration_parts[1];


			$type = "video";
				
				
			$youtube =  preg_match("#(^|[\n ])([\w]+?://)(www\.youtube|youtube)(\.[\w\.]+?/watch\?v=)([\w-]+)([&][\w=+&;%]*)*(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($youtube == 1) ? "youtube" : $file_type;
			$dailymotion = preg_match("#(^|[\n])([\w]+?://)(www\.dailymotion|dailymotion)(.*?/)*#is", $link);
			$extension = ($dailymotion == 1) ? "dailymotion" : $extension;
			$googlevideo = preg_match("#(^|[\n ])([\w]+?://video\.google\.[\w\.]+?/videoplay\?docid=)([\w-]+)([&][\w=+&;-]*)*(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($googlevideo == 1) ? "googlevideo" : $extension;
			$myvideo = preg_match("#(^|[\n ])([\w]+?://)(www\.myvideo|myvideo)(\.[\w\.]+?/watch/)([\w]+)(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($myvideo == 1) ? "myvideo" : $extension;
			$clipfish = preg_match("#(^|[\n ])([\w]+?://)(www\.clipfish|clipfish)(\.[\w\.]+?/player\.php\?videoid=)([\w%]+)([&][\w=+&;]*)*(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($clipfish == 1) ? "clipfish" : $extension;
			$sevenload = preg_match("#(^|[\n ])([\w]+?://[\w.]+?\.sevenload\.com/videos/)([\w]+?)(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($sevenload == 1) ? "sevenload" : $extension;
			$metacafe = preg_match("#(^|[\n ])([\w]+?://)(www\.metacafe|metacafe)(\.com/watch/)([\w]+?)(/)([\w-]+?)(/)(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($metacafe == 1) ? "metacafe" : $extension;
			$streetfire = preg_match("#(^|[\n ])([\w]+?://videos\.streetfire\.net/.*?/)([\w-]+?)(\.htm)(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($streetfire == 1) ? "streetfire" : $extension;
			$wegame = preg_match("#(^|[\n ])([\w]+?://)(www\.wegame|wegame)(\.com/watch/)([\w-]+)(/)(^[\t <\n\r\]\[])*#is", $link);
			$extension = ($wegame == 1) ? "wegame" : $extension;
			
			
			switch ($extension) {
			
				case "youtube": 
					$video_id = preg_replace("#(^|[\n ])([\w]+?://)(www\.youtube|youtube)(\.[\w\.]+?/watch\?v=)([\w-]+)([&][\w=+&;%]*)*(^[\t <\n\r\]\[])*#is", '\\5', $link);
					$result = $mcclass->get_youtube_feed($video_id);
					$duration = $result[2];
					$thumbnail = $result[0];
					$preview_image = $result[1];
															
					$no_preview_image = true;
				break;
				
				case "dailymotion":
					$video_id =  $mcclass->dailymotion_extract($link);
					$thumbnail = 'http://www.dailymotion.com/thumbnail/video/'.$video_id;
					$preview_image = 'http://www.dailymotion.com/thumbnail/video/'.$video_id;
					
					$no_preview_image = true;
					
				break;
				
				case "myvideo":
					$video_id = preg_replace("#(^|[\n ])([\w]+?://)(www\.myvideo|myvideo)(\.[\w\.]+?/watch/)([\w]+)(.*?/)([\w]+)(^[\t <\n\r\]\[])*#is", '\\5', $link);
					$result = $mcclass->get_myvideo_feed((int)$video_id);
					$duration = $result[2];
					$thumbnail = $result[0];
					$preview_image = $result[1];
															
					$no_preview_image = true;
				break;
				
				case "sevenload":
					$video_id = preg_replace("#(^|[\n ])([\w]+?://[\w.]+?\.sevenload\.com/videos/)([\w]+?)(^[\t <\n\r\]\[])*#is", '\\3', $link);
					$video_id = substr($video_id, 0, 7);
					$thumbnail = $preview_image = "http://de.sevenload.com/tn/".$video_id."/120x90";
															
					$no_preview_image = true;
				break;
				
				
					
				
			} //END switch extension
			
			//Upload of preview-image	
			if ($no_preview_image == false){
				
				if ($_FILES['previewimage']['name'] != ""){
						$preview_status = $mcclass->upload_file("previewimage","jpg, gif, png, jpeg",0, true, "thumbs_b/");
							switch ($preview_status) {
								case "0": $preview_image = ""; break;
								case "1": $preview_image = ""; break;
								case "2": $preview_image = ""; break;
								default: $preview_image  = $preview_status;
							
									$thumbnail_status = $mcclass->create_thumbnail($preview_image, "thumbs_b","thumbs", $height="70", $width="200");
									if ($thumbnail_status != "1"){
										$thumbnail = $thumbnail_status; 
									};
									
									$preview_image_status = $mcclass->create_thumbnail($preview_image, "thumbs_b","thumbs_b", $height="380", $width="500");
									if ($preview_image_status != "1"){
										$preview_image = $preview_image_status; 
									};
							
							} //close switch
				} //close if
			}	
			
			$status = ($conf['admin_activation'] == 1) ? 0 : 1;
			$status = ($user->check_auth('a_mediacenter_', false) == true) ? 1 : $status;
			
			
			$sql = $db->query("INSERT INTO __mediacenter_media :params", array(
				'url' 			=> $link,
				'name' 			=> $in->get('name'),
				'extension' 	=> $extension,
				'type' 			=> $type,
				'description' 	=> $in->get('description'),
				'duration' 		=> $duration,
				'category' 		=> $in->get('category'),
				'date' 			=> date("Y-m-d H:i:s"),
				'user_id' 		=> $user->data['user_id'],
				'thumbnail' 	=> $thumbnail,
				'preview_image' => $preview_image,
				'tags' 			=> $in->get('tags'),
				'story' 		=> $in->get('story'),
				'status'		=> $status,
				
			));
			$pdc->del_prefix('plugin.mediacenter');

			$error_msg = "<script>parent.window.location.href = '".urldecode($ref)."';</script>";
						

			
		} else {
		
			System_Message($user->lang['mc_fields_empty'],'EQdkp-MediaCenter','red');
		
		};
	
	
	}; //END Video-Links
	

}; //END if submit


//Create Category-Dropdown

$cat_dropdown = $db->query("SELECT * FROM __mediacenter_categories");
$cat_dropdown = $db->fetch_record_set($cat_dropdown);
foreach ($cat_dropdown as $elem){
	$cat_array[$elem['category_id']] = sanitize($elem['category_name']);
}

$usersetting = $db->query("SELECT * FROM __mediacenter_userconfig WHERE user_id = '".$db->escape($user->data['user_id'])."'");
$usersetting = $db->fetch_record_set($usersetting);
	if (is_array($usersetting)){
		foreach ($usersetting as $elem){
			$uconf[$elem['config_name']] = $elem['config_value'];
		};
	};	
	

// Send the Output to the template Files.
$tpl->assign_vars(array(
	
	'L_INSERT_VIDEO'					=> $user->lang['mc_create_video'],
	'L_INSERT_IMAGE'					=> $user->lang['mc_create_images'],
	'L_CATEGORY'						=> $user->lang['mc_category'],
	'L_NAME'							=> $user->lang['mc_name'],
	'L_DESCRIPTION'						=> $user->lang['mc_description'],
	'L_DURATION'						=> $user->lang['mc_duration'],
	'L_TAGS'							=> $user->lang['mc_tags'],
	'L_PREVIEW_IMAGE'					=> $user->lang['mc_preview_image'],
	'L_URL'								=> $user->lang['mc_url'],
	'L_SELECT_FILE'						=> $user->lang['mc_select_file'],
	'L_SAVE'							=> $user->lang['mc_save'],
	'L_RESET'							=> $user->lang['mc_reset'],
	'YOUTUBE_NICK'						=> $uconf['yt_nick'],
	'L_YOUTUBE_NICK'					=> $user->lang['mc_youtube_nick'],
	'L_YOUTUBE_PW'						=> $user->lang['mc_youtube_pw'],
	'L_YOUTUBE_UPLOAD'					=> $user->lang['mc_youtube_upload'],
	'L_NEXT_STEP'						=> $user->lang['mc_next_step'],	
	'L_UPLOAD'							=> $user->lang['mc_upload'],
	'L_SELECT_FILE'						=> $user->lang['mc_select_file'],
	'L_YOUTUBE_UPLOAD_SUCCESS'			=> $user->lang['mc_youtube_upload_success'],
	'VIDEO_EDITOR' 						=> $jquery->wysiwyg('story'),
	'YT_EDITOR' 						=> $jquery->wysiwyg('story2'),
	'L_STORY'							=> $user->lang['mc_story'],
	'L_HIDE_STORY'						=> $user->lang['mc_hide_story'],
	'L_SHOW_STORY'						=> $user->lang['mc_show_story'],
	'S_NO_CATS'							=> (count($cat_array) == 0 ) ? true : false,
	'L_NO_CATS'							=> $user->lang['mc_no_categories'],

	'ROW_CLASS'                			=> $eqdkp->switch_row_class(),
	'CATEGORY_SELECT'               	=> $khrml->DropDown('category', $cat_array, $in->get('cat')),
	'MC_ERROR'			       			=> $error_msg,
	
	
	'JS_FIELDS_EMPTY'					=> $jquery->Dialog_Alert('fields_empty', $user->lang['mc_fields_empty'], $user->lang['mc_fields_empty_title'],'300', '36'),
	'REFFERRER'							=> ($in->get('ref') == "acp") ? "acp" : "",
	'JS_TAB'							=> $jquery->Tab_header('upload_tabs'),
	
	'S_HMODE'							=> $hosting_mode,
	
));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['mc_create_video'],
	'template_path'			=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 		=> 'upload.html',
	'gen_simple_header'  	=> true,
	'display'       		=> true)
  );

?>
