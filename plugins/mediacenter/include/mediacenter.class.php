<?php
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

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


if (!class_exists("MediaCenterClass")){
  class MediaCenterClass 
  {
   function videoBBcode($text){
		global $pm, $eqdkp_root_path;
		
		$text = preg_replace_callback('/\[video\](.*?)\[\/video\]/msi', array($this,'bbcode2html'), $text);
		$text = preg_replace_callback('/\[movie\](.*?)\[\/movie\]/msi', array($this,'bbcode2html'), $text);
		return $text;
		
	}
  
  
  
  
function bbcode2html($id){
	 global $db, $pdc;
	 
	$id = sanitize($id[1]);

	if (is_numeric($id)){
		//Get all information about the download
		//Cache: plugin.downloads.links.{ID}
		$link_cache = $pdc->get('plugin.mediacenter.media.'.$id,false,true);
		if (!$link_cache) {
			$download_query = "SELECT * from __mediacenter_media WHERE id = '".$db->escape($id)."'";
			$download_query = $db->query($download_query);
			$media = $db->fetch_record($download_query);
			$pdc->put('plugin.mediacenter.media.'.$id,$media,86400,false,true);
		} else{
			$media = $link_cache;
		};
		
		if ($media){
			
			$local = ($media['local_filename'] != "") ? true : false;
			//Generate the player
			$output = $this->generate_player(sanitize($media['extension']), sanitize($media['url']), sanitize($media['preview_image']), $media['type'], $local);
			return $output;
			
		} else {
			return;
		}
		
	}
} //END function bbcode2html 
  
 //Function for deleting a file
function delete_one_link($id){
	
	global $user, $db, $pcache, $lang, $pdc;
	
	$user->check_auth('a_mediacenter_media');

	//Cache: plugin.mediacenter.links.{ID}
	$link_cache = $pdc->get('plugin.mediacenter.media.'.$id,false,true);
	if (!$link_cache){
		$data_query = $db->query('SELECT * FROM __mediacenter_media WHERE id='.$db->escape((int)$id));
		$data = $db->fetch_record($data_query);
		$pdc->put('plugin.mediacenter.media.'.$id,$data,86400,false,true);
	} else{
		$data = $link_cache;
	};
	
	if ($data['local_filename']){
		if (file_exists($pcache->FilePath('videos/'.$data['local_filename'], 'mediacenter'))) {
			unlink($pcache->FilePath('videos/'.$data['local_filename'], 'mediacenter'));
		};
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
	
	$del_query = $db->query("DELETE FROM __mediacenter_media WHERE id='" .$db->escape((int)$id)."'");
	
	//Delete Cache
	$pdc->del_prefix('plugin.mediacenter');
	
	return $error_msg = $user->lang['mc_delete_success'];
	

} 
  
function show_thumbnail($thumb, $type, $extension){
	global $pcache, $eqdkp_root_path;
	
	switch ($type) {
		case "video":
					if ($thumb != ""){
						switch($extension){
							case "youtube": $thumbnail = $thumb;
							break;
							case "dailymotion" : $thumbnail = $thumb;
							break;
							case "myvideo" : $thumbnail = $thumb;
							break;
							case "sevenload" : $thumbnail = $thumb;
							break;
							default: $thumbnail = $pcache->FilePath('thumbs/'.$thumb, 'mediacenter');
						}

								
					} else {
						$thumbnail = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_video.png";
					};
		
		break;
		
		case "image": 
					if ($thumb != ""){

						$thumbnail = $pcache->FilePath('thumbs/'.$thumb, 'mediacenter');
								
					} else {
						$thumbnail = $eqdkp_root_path."plugins/mediacenter/images/thumb_dummy_image.png";
					};
		break;
	
	}
	
	return sanitize($thumbnail);
}


function create_thumbnail($image, $folder = "images", $dest_folder = "thumbs", $height="200", $width="200", $fix_width = false){
	global $pcache;

$pic_name = $image;	
$image = $pcache->FolderPath($folder, 'mediacenter').$image;
$size = GetImageSize($image);

if($size[2] != 0){

			//CREATE THUMBNAILS
			$thumb_path = $pcache->FolderPath($dest_folder, 'mediacenter');
			$pic_width = $size[0];
			$pic_height = $size[1];
			
			if ($pic_width >= $pic_height){
				$thumb_width = $width;
				$thumb_height = intval($pic_height*$thumb_width/$pic_width);

			} else {
				$thumb_height = $height;
				$thumb_width = intval($pic_width*$thumb_height/$pic_height);

			}
			// GIF
			if ($size[2] == 1){
				$picture = ImageCreateFromGIF($image);
				$thumbnail = ImageCreateTrueColor($thumb_width, $thumb_height);
				$white = imageColorAllocate ($thumbnail, 0, 0, 0);
				$trans = imagecolortransparent($thumbnail,$white);
				ImageCopyResampled($thumbnail, $picture, 0, 0, 0, 0, $thumb_width, $thumb_height, $pic_width, $pic_height);
				ImageGIF($thumbnail, $thumb_path.$pic_name);
				//chmod($thumb_path.$pic_name,0644);
			}
			// JPG
			if ($size[2] == 2){
				$picture = ImageCreateFromJPEG($image);
				$thumbnail = ImageCreateTrueColor($thumb_width, $thumb_height);
				ImageCopyResampled($thumbnail, $picture, 0, 0, 0, 0, $thumb_width, $thumb_height, $pic_width, $pic_height);
				ImageJPEG($thumbnail, $thumb_path.$pic_name);
				//chmod($thumb_path.$pic_name,0644);


			}
			// PNG
			if ($size[2] == 3){
				$picture = ImageCreateFromPNG($image);
				$thumbnail = ImageCreateTrueColor($thumb_width, $thumb_height);
				$white = imageColorAllocate ($thumbnail, 0, 0, 0);
				$trans = imagecolortransparent($thumbnail,$white);
				ImageCopyResampled($thumbnail, $picture, 0, 0, 0, 0, $thumb_width, $thumb_height, $pic_width, $pic_height);
				ImagePNG($thumbnail, $thumb_path.$pic_name);
				//chmod($thumb_path.$pic_name,0644);

			}
			
			return $pic_name;


} else {
	return 1; //1 = Not an Image
}

} //END function

// Transform special filname character
function trans_chars($text){
  			$text = preg_replace("/[^A-Za-z0-9_.]/", "", str_replace(array("ä","ö","ü","ß","Ä","Ö","Ü"),array("ae","oe","ue","ss","Ae","Oe","Ue"), $text));
return $text;
}


	function youtube_uploadToken($title, $desc, $cat, $keywords, $auth, $key){
	
	$xml = '<?xml version="1.0"?><entry xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:yt="http://gdata.youtube.com/schemas/2007"><media:group><media:title type="plain">'.$title.'</media:title><media:description type="plain">'.$desc.'</media:description><media:category scheme="http://gdata.youtube.com/schemas/2007/categories.cat">'.$cat.'</media:category><media:keywords>'.$keywords.'</media:keywords></media:group></entry>';
	
		$fp = fsockopen("gdata.youtube.com", 80, $errno, $errstr, 30);
	if (!$fp) {
    	return array(false, "$errstr ($errno)");
		
	} else {
		$out = "POST /action/GetUploadToken HTTP/1.1\r\n";
		$out .= "Host: gdata.youtube.com\r\n";
		$out .= "Authorization: GoogleLogin auth=".$auth."\r\n";
		$out .= "GData-Version: 2\r\n";
		$out .= "X-GData-Key: key=".$key."\r\n";
		$out .= "Content-Length: ".strlen($xml)."\r\n";
		$out .= "Content-Type: application/atom+xml; charset=UTF-8\r\n";
		$out .= "Connection: Close\r\n\r\n";
		$out .= $xml;
	  	
		$return = "";
		fwrite($fp, $out);
		while (!feof($fp)) {
			$return .= fgets($fp);
		}
		fclose($fp);
		
		$body = explode("\n",$return);
		if ( strpos( $return," 200 ") === false)
        {
            return array(false,$return);
        }
        
		preg_match("/<url>(.*)<\/url>/i",$return,$url);
        preg_match("/<token>(.*)<\/token>/i",$return,$token);
		
		return array(true, array($url[1],$token[1]));
	}
	
	
	}
	
	
	 function youtube_login($user, $pass, $source)
    {
        
	$fp = fsockopen("ssl://google.com", 443, $errno, $errstr, 30);
	if (!$fp) {
    	return array(false, "$errstr ($errno)");
		
	} else {
		$body = "Email=".urlencode($user)."&Passwd=".urlencode($pass)."&service=youtube&source=".urlencode($source);
		$out = "POST /youtube/accounts/ClientLogin HTTP/1.1\r\n";
		$out .= "Host: www.google.com\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: ".strlen($body)."\r\n";
		$out .= "Connection: Close\r\n\r\n";
		$out .= $body;
	  	$return = "";
		fwrite($fp, $out);
		while (!feof($fp)) {
			$return .= fgets($fp);
		}
		fclose($fp);
		
		$body = explode("\n",$return);		

			if ( strpos( $return," 200 ") === false)
        {	
            return array(false,substr($body[13],6));
        }
				
				foreach ($body as $line){
					if (strpos( $line,"Auth=") !== false){
						$auth = substr($line, 5);
						return array(true, $auth);
					} 
				}
		
	}
		
		

    }


function get_youtube_feed($video_id){

	$feed_url = "http://gdata.youtube.com/feeds/api/videos/".$video_id;
			
					$xml = simplexml_load_file(utf8_encode($feed_url));
					if ($xml){
						$media = $xml->children('http://search.yahoo.com/mrss/');
			  
						// get video thumbnail
						$attrs = $media->group->thumbnail[0]->attributes();
						$thumbnail = $attrs['url']; 
						
										// get video thumbnail
						$attrs = $media->group->thumbnail[3]->attributes();
						$preview_image = $attrs['url']; 
					
						// get <yt:duration> node for video length
						$yt = $media->children('http://gdata.youtube.com/schemas/2007');
						$attrs = $yt->duration->attributes();
						$length = $attrs['seconds'];
					} else {
						return false;
					};
					
					$duration = $length;
					
					return array($thumbnail, $preview_image, $duration);
}

function get_myvideo_feed($video_id){

$feed_url = "https://api.myvideo.de/api2_rest.php?method=myvideo.videos.get_details&dev_id=58a34a451e4575f35e193bb03ade9a66&website_id=25b12abd5336272e056ee8232ed9874f&movie_id=".$video_id."&player_size=1";
											
											
					$xml = simplexml_load_file(utf8_encode($feed_url));
					if ($xml){
						$preview_image = $thumbnail = $xml->myvideo->movie->movie_thumbnail;
						$duration = $xml->myvideo->movie->movie_length;
					} else {
						return false;
					};
										
					return array($thumbnail, $preview_image, $duration);
}



//Function for uploading files
function upload_file($fieldname, $allowed_extensions, $debug=0, $hash_filename=true, $folder="files/"){
	global $user, $pcache;
		
	$user->check_auth('u_mediacenter_upload');
	
	$filename = $_FILES[$fieldname]['name'];
	
	if($filename != ""){
			$disallowed_extensions = array('php', 'cgi', 'php4', 'php5', 'py', 'js', 'htm', 'html', 'css');
	
			// Transform special filname character
  			$filename = $this->trans_chars($filename);

			$allowed_extensions = preg_split('/, */', strtolower($allowed_extensions));
			$file_type = substr($filename, strrpos($filename, '.') + 1);
			$extension_check = in_array(strtolower($file_type), $allowed_extensions);
			if ($extension_check){
				$extension_check = !in_array(strtolower($file_type), $disallowed_extensions);
			}
			
			//Debug
			if ($debug == 1){

				if ($extension_check == false) {echo "<br>Status: Extension is not allowed";};
				if ($this->check_content($fieldname) == false) {echo "<br>Status: The File contains disallowed content";};
			}
			
			//Check the file
			$extension_check = ($extension_check == true) ? true : false;
			$forbidden_content_check = ($this->check_content($fieldname) == true) ? true : false;

			//If all checks are true
			if ($extension_check == true && $forbidden_content_check == true){
								
				if ($hash_filename == true){
					$file_hash = md5(mt_rand()).".".$file_type;
				} else {
					$file_hash  = $filename;
				}
	
				if (file_exists($pcache->FolderPath('mediacenter/'.$folder).$file_hash)) {	
					return 1;
				} 
	
				else {
					$pcache->FileMove($_FILES[$fieldname]['tmp_name'], $pcache->FolderPath('mediacenter/'.$folder).$file_hash);
					return $file_hash;
				}
			}
			else {
				return 2;
			}
	
	
	}
	else {
		return 0;
	}

	
}
	// Check the first 256 bytes for forbidden content
	function check_content($fieldname){
		
		$disallowed = "body|head|html|img|plaintext|a href|pre|script|table|title|php";
		$disallowed_content = explode('|', $disallowed);
		if (empty($disallowed_content))
		{
			return false;
		}
		
		
		
		$fp = @fopen($_FILES[$fieldname]['tmp_name'], 'rb');

		if ($fp !== false)
		{
			$ie_mime_relevant = fread($fp, 256);
			fclose($fp);
			foreach ($disallowed_content as $forbidden)
			{
				if (stripos($ie_mime_relevant, '<' . $forbidden) !== false)
				{
					return false;
				}
			}
		}
		return true;
	}	
	
	
	

	

function show_categories(){
	global $db, $pcache, $pdc, $eqdkp_root_path, $SID, $user, $html;
	
	$output = $pdc->get('plugin.mediacenter.show_cats',false,true);
	if (!$output){
			$cat_query = $db->query("SELECT * FROM __mediacenter_categories ORDER BY category_sortid ASC");
			$cat_number = $db->affected_rows();
			$cat_data = $db->fetch_record_set($cat_query);
			$anzahl_cats = ($cat_number < 3) ? 3 : $cat_number;
			
			
			$output = '<style>.scroll-content { width: '.($anzahl_cats*305).'px; float: left; }</style>';
			$output .= '<div style="width:915px">
		
		<div class="scroll-pane ui-widget ui-widget-header ui-corner-all">
			<div class="scroll-content">';
			
			if (is_array($cat_data)){
				
				$media_query = $db->query("SELECT * FROM __mediacenter_media WHERE status='1' ORDER BY RAND()");
				$media_data = $db->fetch_record_set($media_query);
				foreach ($media_data as $data){
					$media[$data['category']][$data['id']] = $data;
				}
				if (is_array($cat_data)){
				foreach ($cat_data as $row){
					$count = count($media[$row['category_id']]);
					$cat_thumbnail = "";
					foreach ($media[$row['category_id']] as $value=>$key){
		
						if ($key['thumbnail'] != ""){
							if ($key['extension'] == "youtube" || $key['extension'] == "dailymotion" || $key['extension'] == "myvideo" || $key['extension'] == "sevenload"){
								$cat_thumbnail = '<img src="'.sanitize($key['thumbnail']).'" height="66">';
							} else {
								$cat_thumbnail = '<img src="'.sanitize($pcache->FilePath('thumbs/'.$key['thumbnail'], 'mediacenter')).'" height="66" style="max-width:90px;">';
							};
							
							break;
						}
					}
					
					$output .= '<div class="scroll-content-item ui-state-focus" ><table><tr>';
					$output .= '<td><a href="list.php'.$SID.'&cat='.sanitize($row['category_id']).'">'.$cat_thumbnail.'</a></td>';
					$output .= '<td valign="top"><a href="list.php?cat='.sanitize($row['category_id']).'"><b>'.sanitize($row['category_name']).'</b><br>'.$html->ToolTip(sanitize($row['category_comment']), $this->wrapText(sanitize($row['category_comment']), 30)).'<br><em>'.sprintf($user->lang['mc_contain_videos'], sanitize($count)).'</em></a></td>';	
					$output .= '</tr></table></div>'; 
					
				}
				}
			}
			$output .= '</div>
			<div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">
				<div class="scroll-bar"></div>
			</div>
		</div>
		</div>';
		$pdc->put('plugin.mediacenter.show_cats',$output,86400,false,true);
	}
	


	return $output;

}
function dailymotion_extract($embed = '') {
  if (preg_match('@dailymotion\.com/swf/([^"\&]+)@i', $embed, $matches)) {
    return $matches[1];
  }
  if (preg_match('@dailymotion\.com@i', $embed, $matches)) {
      if (preg_match_all('@/([^/_]+)_@i', $embed, $matches)) {
      $id = array_pop($matches[1]);
      if (!is_array($id) && $id != NULL) {
          return $id;
      }
    }
  }
  return array();
}

//spielt wmv, wma, mp3; benötigt silverlight
function wmv_player ($file, $preview_image){
	global $eqdkp_root_path;
return "	<div name=\"mediaspace_".md5($file)."\" id=\"mediaspace_".md5($file)."\"></div>
	<script type='text/javascript' src=\"".$eqdkp_root_path."plugins/mediacenter/include/wmvplayer/silverlight.js\"></script>
	<script type='text/javascript' src=\"".$eqdkp_root_path."plugins/mediacenter/include/wmvplayer/wmvplayer.js\"></script>
	<script type=\"text/javascript\">
		var cnt = document.getElementById(\"mediaspace_".md5($file)."\");
		var src = '".$eqdkp_root_path."plugins/mediacenter/include/wmvplayer/wmvplayer.xaml';
		var cfg = {
			file:'".sanitize($file)."',
			image:'".sanitize($preview_image)."',
			height:'400',
			width:'600'
		};
		var ply = new jeroenwijering.Player(cnt,src,cfg);
	</script>
";
}

//spielt nur flvs, images und audios
//type =youtube, sound, image, video
function flv_player ($file, $preview_image ='', $type = '') {
	global $eqdkp_root_path;

	
	return "<p id='flv_player_".md5($file)."'></p>
<script type='text/javascript' src='".$eqdkp_root_path."plugins/mediacenter/include/flvplayer/swfobject.js'></script>
<script type='text/javascript'>
var s1 = new SWFObject('".$eqdkp_root_path."plugins/mediacenter/include/flvplayer/player.swf','player','600','400','9');
s1.addParam('allowfullscreen','true');
s1.addParam('allowscriptaccess','always');
s1.addParam('flashvars','file=".sanitize($file)."&image=".sanitize($preview_image)."');
s1.addParam('wmode','transparent');
s1.write('flv_player_".md5($file)."');
</script> ";
}

function divx_player($file, $preview_image ='', $type = ''){
	return ' <object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="600" height="400" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">

 <param name="custommode" value="none" />
	<param name="previewImage" value="'.sanitize($preview_image).'" />
  <param name="autoPlay" value="false" />
  <param name="src" value="'.sanitize($file).'" />
	<param name="wmode" value="opaque" />

<embed type="video/divx" src="'.sanitize($file).'" custommode="none" width="600" height="400" autoPlay="false"  pluginspage="http://go.divx.com/plugin/download/" wmode="opaque">
</embed>
</object>

';
}

function youtube_player($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.youtube|youtube)(\.[\w\.]+?/watch\?v=)([\w-]+)([&][\w=+&;%]*)*(^[\t <\n\r\]\[])*#is", 'http://www.youtube.com/v/\\5&hl=de_DE&fs=1&rel=0&hd=1', $file);
return '<object height="400" width="600"><param name="movie" value="'.$link.'" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="wmode" value="opaque" /><embed src="'.$link.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" height="400" width="600"></embed></object>';
}

function dailymotion_player($file, $preview_image ='', $type = ''){
$link = $this->dailymotion_extract($file);
$link = 'http://www.dailymotion.com/swf/'.$link;
return '<object width="600" height="400" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="movie" value="'.sanitize($link).'&related=0"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="'.sanitize($link).'&related=0" type="application/x-shockwave-flash" width="600" height="400" allowFullScreen="true" allowScriptAccess="always" wmode="transparent"></embed></object>';
}

function googlevideo_player($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://video\.google\.[\w\.]+?/videoplay\?docid=)([\w-]+)([&][\w=+&;-]*)*(^[\t <\n\r\]\[])*#is", 'http://video.google.com/googleplayer.swf?docId=\\3', $file);
return '<object><param name="wmode" value="transparent"></param><embed style="width:600px; height:400px;" id="VideoPlayback" type="application/x-shockwave-flash" wmode="transparent" src="'.sanitize($link).'" flashvars=""></embed></object>';
 }
 
function myvideo_player($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.myvideo|myvideo)(\.[\w\.]+?/watch/)([\w]+)(^[\t <\n\r\]\[])*#is", '\\5', $file);
$link = "http://www.myvideo.de/movie/".(int)$link;
return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="600" height="400" style="width:600px;height:400px;"><param name="movie" value="'.sanitize($link).'"></param><param name="wmode" value="transparent"></param><embed src="'.sanitize($link).'" height="400" width="600" type="application/x-shockwave-flash" wmode="transparent"></embed></object>';
}

function clipfish_player($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.clipfish|clipfish)(\.[\w\.]+?/player\.php\?videoid=)([\w%]+)([&][\w=+&;]*)*(^[\t <\n\r\]\[])*#is", 'http://www.clipfish.de/videoplayer.swf?as=0&videoid=\\5&r=1"', $file);
return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="600" height="400" id="player" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="'.sanitize($link).'" /><param name="wmode" value="transparent"><embed src="'.sanitize($link).'" width="600" height="400" name="player" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
}

function sevenload_player ($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://[\w.]+?\.sevenload\.com/videos/)([\w]+?)(^[\t <\n\r\]\[])*#is", '\\3', $file);
$link = substr($link, 0, 7);
$link = 'http://de.sevenload.com/pl/'.$link.'/600x400/swf';

return '<object width="600" height="400"><param name="FlashVars" value="slxml=de.sevenload.com"/><param name="movie" value="'.sanitize($link).'" /><embed src="'.sanitize($link).'" type="application/x-shockwave-flash" width="600" height="400" FlashVars="slxml=de.sevenload.com"></embed></object>';
}

function metacafe_player ($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.metacafe|metacafe)(\.com/watch/)([\w]+?)(/)([\w-]+?)(/)(^[\t <\n\r\]\[])*#is", 'http://www.metacafe.com/fplayer/\\5/\\7.swf', $file);
return '<embed src="'.sanitize($link).'" width="600" height="400" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>';
}

function streetfire_player ($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://videos\.streetfire\.net/.*?/)([\w-]+?)(\.htm)(^[\t <\n\r\]\[])*#is", '\\3', $file);
return '<embed src="http://videos.streetfire.net/vidiac.swf" FlashVars="video='.sanitize($link).'" quality="high" bgcolor="#ffffff" width="600" height="400" name="ePlayer" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
}

function wegame_player ($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.wegame|wegame)(\.com/watch/)([\w-]+)(/)(^[\t <\n\r\]\[])*#is", 'http://www.wegame.com/player/video/\\5', $file);
return '<object width="480" height="387"><param name="movie" value="http://www.wegame.com/static/flash/player.swf?xmlrequest='.sanitize($link).'></param><param name="flashVars" value="xmlrequest='.sanitize($link).'&embedPlayer=true"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.wegame.com/static/flash/player.swf?xmlrequest='.sanitize($link).'&embedPlayer=true" type="application/x-shockwave-flash" allowfullscreen="true" width="600" height="400"></embed></object>';
}

/*
function wegame_player ($file, $preview_image ='', $type = ''){
$link = preg_replace("#(^|[\n ])([\w]+?://)(www\.wegame|wegame)(\.com/watch/)([\w-]+)(/)(^[\t <\n\r\]\[])*#is", 'http://www.wegame.com/static/flash/player2.swf?tag=\\5', $file);
return '<object width="600" height="400"><param name="movie" value="'.$file.'"> </param><param name="wmode" value="transparent"></param><embed src="'.sanitize($link).'" type="application/x-shockwave-flash" wmode="transparent" width="600" height="400"></embed></object>';
}

*/


function unknown_player($file, $preview_image ='', $type = ''){
global $user;
return '<table class="errortable" border="0" cellpadding="2" cellspacing="0" width="100%">
          								<tbody><tr>
          								  
          								  <td class="row1">'.$user->lang['mc_video_not_supported'].'</td>
          								</tr>
                        </tbody></table>';
}

function image_player($file, $preview_image ='', $type = ''){
	global $pcache;
return '<a href="'.$pcache->FilePath('thumbs_b/'.$file, 'mediacenter').'" rel="lytebox"><img src="'.$pcache->FilePath('thumbs_b/'.$file, 'mediacenter').'" name="resize_h"></a>';
}


function generate_player($extension, $file, $preview_image, $type='video', $local = false){
	global $pcache;
	$player_array = array(
		
		"youtube" 		=> "youtube_player",
		
		"wma"			=> "wmv_player",
		"wmv"			=> "wmv_player",
		
		"mp3"			=> "flv_player",
		"flv"			=> "flv_player",
		"swf"			=> "flv_player",
		
		"avi"			=> "divx_player",
		"divx"		=> "divx_player",
		"mkv"			=> "divx_player",
		
		"dailymotion"	=> "dailymotion_player",
		"googlevideo"	=> "googlevideo_player",
		"myvideo"		=> "myvideo_player",
		"clipfish"		=> "clipfish_player",
		"sevenload"		=> "sevenload_player",
		"metacafe"		=> "metacafe_player",
		"streetfire"	=> "streetfire_player",
		"wegame"		=> "wegame_player",
		"image"			=> "image_player",
		
		"unknown"		=> "unknown_player",
	
	);
	

	
	switch ($type){
		case "video":	if ($file != ""){$player_function = $player_array[$extension];} else {$player_function = $player_array['unknown'];};
		break;
		case "image":	$player_function = $player_array['image'];
		break;
	}
	if ($local){
		
		if (!file_exists($pcache->FilePath('videos/'.$file, 'mediacenter'))) {
			$player_function = $player_array['unknown'];
		}
		
		switch($player_function){
			case "flv_player": $file = $this->generate_dkp_link().$pcache->FileLink('videos/'.$file, 'mediacenter');
			break;
			case "divx_player": $file = $this->generate_dkp_link().$pcache->FileLink('videos/'.$file, 'mediacenter');
			break;
			default: $file = $pcache->FilePath('videos/'.$file, 'mediacenter');
		
		}
		

	}
	$preview_image = $this->generate_dkp_link().$pcache->FileLink('thumbs_b/'.$preview_image, 'mediacenter');
	$player = $this->$player_function($file, $preview_image, $type);

	return $player;
}


// Build the CopyRight
  function Copyright(){
      global $pm, $user;
      $mc_version = $pm->get_data('mediacenter', 'version');
	  
      $mc_status  = (strtolower($pm->plugins['mediacenter']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['mediacenter']->vstatus.' ';
	  $act_year = date("Y", time());
	  $mc_copyyear = ( $act_year == 2009) ? $act_year : '2009 - '.$act_year;
      return $user->lang['mc_mediacenter'].' '.$mc_version.$mc_status.' &copy; '.$mc_copyyear.' by '.$pm->plugins['mediacenter']->copyright;
  }

function update_config($config_value, $config_name){
	global $db;
	
	$updatequery = $db->query("UPDATE __mediacenter_config SET config_value = '".$db->escape($config_value)."' WHERE config_name = '".$db->escape($config_name)."'");
}


function is_checked($value){
	
	return ( $value == 1 ) ? ' checked="checked"' : '';
}
  
function wrapText($text,$len=30)
 {
		$croplen = $len ;
		$ret = "";
		for($i=0;;$i++)
		{
			if ($i == strlen($text)) {
				break;
			}

			$ret .= $text[$i];
			if ($i >= $len)
			{
				if ($text[$i] == " ")
				{
					$ret .= str_replace(' ', '...', $text[$i] );
					break;
					$len = $len+$croplen;
				}

			}
		}
		return $ret ;
}

function wrapDesc($text,$len=30)

 {global $user;
 $text = $this->replace_uri($text);
		$croplen = $len ;
		$ret = "";
		for($i=0;;$i++)
		{
			if ($i == strlen($text)) {
				break;
			}

			$ret .= $text[$i];
			if ($i >= $len)
			{
				if ($text[$i] == " ")
				{
					$ret .= str_replace(' ', '...', $text[$i] );
					$ret .='  <a href="#" onClick="extend_infos()" id="info_link">('.$user->lang['mc_more_infos'].')</a><div style="display:none" id="info_area">'.substr($text, $i).'<br><a href="#" onClick="hide_infos()">('.$user->lang['mc_less_infos'].')</a></div>' ;
					
					break;
					$len = $len+$croplen;
					
				}

			}
		}
		
		return $ret;
}
 
 function replace_uri($str) {
  $pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#sm';
  return preg_replace($pattern,"\\1<a href=\"\\2\\3\">\\2\\3</a>\\4",$str);
}

  function getDaysOfMonth($month,$year) {
  $schalt = $year % 4 == 0 && $year % 100 != 0;

  if($month <= 7) {
    if($month == 2) {
      return $schalt ? 29 : 28;
    }

    if($month % 2 == 0) {
      return 30;
    }

    return 31;
  }

  if($month % 2 == 0) {
    return 31;
  }
  return 30;
}

 

function createGraph($a_daten)
	{	
	global $eqdkp_root_path, $user;
	include_once($eqdkp_root_path . 'pluskernel/include/GoogleGraph.class.php');
		$count_values = count($a_daten);
		
		$max = 0;
		$_a_daten = array();
		
		//Suche den Maxwert
		foreach ($a_daten as $value)
		{
			if($value > $max)
			{$max = $value;}
		}

		//Array umdrehen und Wert glätten auf max 97 da die
		//goddammedfuckshice google api im normalen modus nur 100 werte kann
		foreach ($a_daten as $value)
		{
			if ($max > 0)
			{
				$offset= $max / 99 ;
				if ($offset > 0)
				{
				$_a_daten[]= $value/ $offset ;
				}
			}
		}

		//Create Object
		$graph = new GoogleGraph();

		//Graph
		$graph->Graph->setType('line');
		$graph->Graph->setSubtype('chart');
		$graph->Graph->setSize(700, 300);

		$graph->Graph->setAxis(); //no arguments means all on
		
		$x_grid = (string)round((100/($count_values-1)),6);
		$x_grid = str_replace(",", ".", $x_grid);

		$graph->Graph->setGridLines($x_grid, round((100/$max),0), 1, 0);

		//Background
		$graph->Graph->addFill('chart', '#000000', 'solid');
		$graph->Graph->addFill('background', '#FFFFFF', 'gradient', '#000000', 90, 0.5, 0);


		$graph->Graph->setAxisRange('2,0,'.$max.'|1,1,'.$count_values.'|0,1,'.$count_values.'|3,0,'.$max);
		
		switch ($count_values){
			case "12" :
							$label_pos_array[] = 0;

							for ($i=1; $i<=12; $i++){
								$label_array[] = $user->lang['mc_month_short_'.$i];	
								$label_pos_array[] = $i;
							}

							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addAxisLabel();
							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addLabelPosition($label_pos_array);
							$label_pos_array2 = $label_pos_array;
							$label_pos_array2[0] = 1;
							$graph->Graph->addLabelPosition($label_pos_array2);
				break;
			

		}
				
		
		//Lines
		$graph->Graph->setLineColors(array('#FF0000', '#00FF00', '#0000FF'));
		for ($i=0; $i<$count_values; $i++){
			$graph->Graph->addShapeMarker(array('square', '000000', '0', $i, '3'));
		}

		//Data
		$graph->Data->addData($_a_daten);

		//Output Graph
		$img = $graph->printGraph();
		return $img;

	}

 	function generate_dkp_link(){
		global $eqdkp;
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    	$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    	$server_name = trim($eqdkp->config['server_name']);
    	$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
      	$dkp_link = 'http://' . $server_name . $server_port . $script_name;
		return $dkp_link;
	}
  
  } //END class
} //END if
?>