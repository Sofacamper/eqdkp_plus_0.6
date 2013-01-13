<?PHP
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_mediacenter_media');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');

$hosting_mode = ($_HMODE) ? true : false;


if ($in->get('ref') == "acp"){
	$ref = "admin/media.php".$SID;
} else {
	$ref = "file.php".$SID."&id=".$in->get('id');
};

// Delete one download
if ($in->get('mode') == 'delete'){
	$mcclass->delete_one_link($in->get('id'));
	$error_msg = "<script>parent.window.location.href = '".$ref."';</script>";
};

if ($in->get('mode') == 'del_pi'){
	
	$link_cache = $pdc->get('plugin.mediacenter.media.'.$in->get('id'),false,true);
	if (!$link_cache){
		$data_query = $db->query('SELECT * FROM __mediacenter_media WHERE id='.$db->escape($in->get('id')));
		$data = $db->fetch_record($data_query);
		$pdc->put('plugin.mediacenter.media.'.$elem,$data,86400,false,true);
	} else{
		$data = $link_cache;
	};
	
	if ($data['thumbnail']){
				if (file_exists($pcache->FilePath('thumbs/'.$data['thumbnail'], 'mediacenter'))) {
					unlink($pcache->FilePath('thumbs/'.$data['thumbnail'], 'mediacenter'));
				};
	};
			
	if ($data['preview_image']){
				if (file_exists($pcache->FilePath('thumbs_b/'.$data['preview_image'], 'mediacenter'))) {
					unlink($pcache->FilePath('thumbs_b/'.$data['preview_image'], 'mediacenter'));
				};
	};
	
	$db->query("UPDATE __mediacenter_media SET
				preview_image='',
				thumbnail='',
				date=NOW()
  				WHERE id=".$db->escape($in->get('id')));
		
	//Delete Cache
	$pdc->del_suffix('plugin.mediacenter');

	

}

//If form has been submitted
if ($in->get('submit') != ""){

	//Images
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
				
				$sql = $db->query("UPDATE __mediacenter_media SET :params WHERE id='".$db->escape($in->get('id'))."'", array(
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
					
				));
			}
			
			
		} else {
			$error_msg = $user->lang['mc_ad_fields_empty'];
		}
	
	//Video-Links
	} else {

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
			$sevenload = preg_match("#(^|[\n ])([\w]+?://[\w.]+?\.sevenload\.com/videos/)([\w]+?)(/[\w-]+)(^[\t <\n\r\]\[])*#is", $link);
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
				
				if ($in->get('is_previewimage') == "true"){
					$preview_image = $in->get('previewimage');
				
				}else {
				
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
			}	
			


			
			$sql = $db->query("UPDATE __mediacenter_media SET :params WHERE id='".$db->escape($in->get('id'))."'", array(
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

				
			));
						
		$pdc->del_prefix('plugin.mediacenter');
		$error_msg = "<script>parent.window.location.href = '".urldecode($ref)."';</script>";
		
		
		} else {
		
			$error_msg = $user->lang['mc_fields_empty'];
		
		};
	
	
	}; //END Video-Links
	

}; //END if submit


//Create Category-Dropdown

$cat_dropdown = $db->query("SELECT * FROM __mediacenter_categories");
$cat_dropdown = $db->fetch_record_set($cat_dropdown);
foreach ($cat_dropdown as $elem){
	$cat_array[$elem['category_id']] = sanitize($elem['category_name']);
}

$data_query = $db->query('SELECT * FROM __mediacenter_media WHERE id='.$db->escape($in->get('id')));
$data = $db->fetch_record($data_query);

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
	'L_STORY'							=> $user->lang['mc_story'],
	'L_HIDE_STORY'						=> $user->lang['mc_hide_story'],
	'L_SHOW_STORY'						=> $user->lang['mc_show_story'],
	
	'S_YOUTUBE'							=> ($data['extension'] == "youtube") ? true : false,
	
	//VALUES
	'NAME' 								=> sanitize($data['name']),
	'DESC' 								=> sanitize($data['description']),
	'URL' 								=> sanitize($data['url']),
	'NAME' 								=> sanitize($data['name']),
	'S_PREVIEWIMAGE' 					=> ($data['preview_image'] != "") ? true : false,
	'PREVIEWIMAGE'						=> sanitize($data['preview_image']),
	'DURATION'							=> sanitize(date("i:s", $data['duration'])),
	'TAGS'								=> sanitize($data['tags']),
	'ID'								=> sanitize($data['id']),
	'STORY'								=> sanitize($data['story']),
	'VIDEO_EDITOR' 						=> $jquery->wysiwyg('story'),
	
	'ROW_CLASS'                			=> $eqdkp->switch_row_class(),
	'CATEGORY_SELECT'               	=> $khrml->DropDown('category', $cat_array, $data['category']),
	'MC_ERROR'			       			=> $error_msg,
	
	
	'JS_FIELDS_EMPTY'					=> $jquery->Dialog_Alert('fields_empty', $user->lang['mc_fields_empty'], $user->lang['mc_fields_empty_title'],'300', '36'),
	'REFFERRER'							=> ($in->get('ref') == "acp") ? "acp" : "",
		
	'S_HMODE'							=> $hosting_mode,
	
));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['mc_create_video'],
	'template_path'			=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 		=> 'edit.html',
	'gen_simple_header'  	=> true,
	'display'       		=> true)
  );


?>