<?PHP
/*
* Project:     EQdkp-Plus
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* ----------------------------------------------------------------------------------
* Gallery 4 EQdkp plus
* ---------------------------------------------------------------------------------- 
* Project Start: 09/2008
* Author: BadTwin
* Copyright: Andreas (BadTwin) Schrottenbaum
* Link: http:// bloody-midnight.eu
* Version: 2.0.11
* ----------------------------------------------------------------------------------
* Based on the HelloWorld Plugin by Wallenium
*
* $Id: $
*/


// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'gallery');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once('include/libloader.inc.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }

// Check user permission
$user->check_auth('u_gallery_view');

// Comment System
	if(is_object($pcomments) && $pcomments->version > '1.0.3'){
      $comm_settings = array(
          'attach_id' => $_GET['pid'], 
          'page'      => 'gallery',
          'auth'      => 'a_gallery_'
      );
		$pcomments->SetVars($comm_settings);
		$tpl->assign_vars(array(
			'ENABLE_COMMENTS'     => true,
			'COMMENTS'            => $pcomments->Show(),
      ));
    }

// The Star Rating thing
if ($in->get('mode', '') == 'vote'){
	if ($in->get('vote')){
		$out = $pdc->get('plugin.gallery.vote.'.$in->get('pic').".u".$user->data['user_id']);
		if (!$out) {
			$vote_query = "SELECT * FROM __gallery_pictures WHERE id = '".$db->escape($in->get('pic',0))."'";
			$vote_query = $db->query($vote_query);
			$vote = $db->fetch_record($vote_query);
			$pdc->put('plugin.gallery.vote.'.$in->get('pic').".u".$user->data['user_id'], $vote, 86400);
		} else{
			$vote = $out;
		};

		if ($vote){
			$users_voted = unserialize($vote['voted_users']);

				if (!$users_voted[$user->data['user_id']]){
					$users_voted[$user->data['user_id']] = $in->get('vote');
					$rating_points = $vote['rating_points'] + $in->get('vote');
					$votes = $vote['votes'] + 1;
					$rating = round($rating_points / $votes);

					$pdc->del_suffix('plugin.gallery.vote');
				
					$update_query = $db->query("UPDATE __gallery_pictures SET :params WHERE id = '".$db->escape($in->get('pic',0))."'", array(
						'rating_points'	=> $db->escape($rating_points),
						'votes'					=> $db->escape($votes),
						'rating'				=> $db->escape($rating),
						'voted_users'		=> serialize($users_voted),
					));
				};
		};
		die($jquery->StarRatingValue('vote', $rating));
	}else{	
		die();
	}
};

$ratings = array(
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'	=> '10',
);

if (is_numeric($_GET['pid'])){
	$result = $db->query("SELECT * FROM __gallery_pictures WHERE id = '" . $in->get('pid', 0) . "';");
	$picture = $db->fetch_record($result);
	
	$prev_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id < ". $in->get('pid', 0)." AND category = ".$picture['category']);
		while($prev = $db->fetch_record($prev_sql)){
			$prev_pic = '<a href="picview.php?pid='.$prev['id'].'"><img src="images/leftarrow.png"></a>';
		}
		if ($prev_pic == ''){
			$prev_pic = '<img src="images/leftarrow_grey.png">';
		}

	$next_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id > ". $in->get('pid', 0)." AND category = ".$picture['category']);
	$next_pid = $db->fetch_record($next_sql);
	$next_pic = '<a href="picview.php?pid='.$next_pid['id'].'"><img src="images/rightarrow.png"></a>';
	if($next_pid['id'] == ''){
		$next_pic = '<img src="images/rightarrow_grey.png">';
	}

	$uresult = $db->query("SELECT username FROM __users users WHERE user_id=" . $picture['user_id'] . ";");
	$uname = $db->fetch_record($uresult);
	
	// Get Category Name
	$cat = $db->query("SELECT category_name FROM __gallery_categories WHERE category_id='" . $picture['category'] . "';");
	$cat_name = $db->fetch_record($cat);

	// Get image size
	$picsize = getimagesize($pcache->FilePath('upload/'.$picture['filename'], 'gallery'));
	$pic_width = $picsize[0];
	$pic_height = $picsize[1];

  // Get uploader's name
  if ($picture['user_id'] == '-1'){
    $uname['username'] = 'Anonymous';
  } else {
  	$uresult = $db->query("SELECT username FROM __users users WHERE user_id=" . $picture['user_id'] . ";");
	  $uname = $db->fetch_record($uresult);
	}
	
	$views_new = $picture['views']+1;
	$result = $db->query("UPDATE __gallery_pictures SET views = " . $views_new . " WHERE id = " . $in->get('pid', 0));
	
	if ($picture['comment'] == ''){
		$picture['comment'] = $user->lang['gl_pic_descript_warn'];
	}
		$comment = htmlentities($picture['comment'], ENT_QUOTES);
		$comment_nl = preg_replace("/\r/",'',$comment);
		$comment_breaks = preg_replace("/\n/",'<br>',$comment_nl);
		$comment_html_fix = str_replace('&amp;','&', $comment_breaks);
}

// Upload Button & My Pics Link
	if ($user->check_auth('u_gallery_upload', false)){
		$upload_button='<td style="text-align: center; width: 33%"><a href="mypics.php">My Pics</a></td><td style="text-align: right; width: 33%">
				      <form action="upload.php" method="GET">
        			<input type="hidden" value="'.$picture['category'].'" name="category">
							<input type="submit" value="'.$user->lang['gl_index_upload'].'" class="mainoption">
						  </form></td>';
	}
	
// Lytebox Array - START

	$lb_sql = $db->query('SELECT * FROM __gallery_pictures WHERE category = '. $picture['category']);
//	$gl_lb_arr1 = $db->fetch_record($lb_arr);
//	$gl_lb_arr = $gl_lb_arr1['filename'];
  while ($mthumb_output = $db->fetch_record($lb_sql)){
		  if ($mthumb_output['comment'] == ''){
		      $mthumb_output['comment'] = $user->lang['gl_pic_descript_warn'];
	    }
			$thumb_comment = htmlentities($mthumb_output['comment'], ENT_QUOTES);
			$thumb_comment_breaks = preg_replace("/\r/",'',$thumb_comment);
			$thumb_comment = preg_replace("/\n/",'<br>',$thumb_comment_breaks);
      
		// Get Image Size
		$picsize = getimagesize($pcache->FilePath('upload/'.$mthumb_output['filename'], 'gallery'));
		$tpic_width = $picsize[0];
		$tpic_height = $picsize[1];

		// Picture Description
    	if ($mthumb_output['description'] == ''){
    	 $resize_title = substr($mthumb_output['filename'], 0, -strlen($picture['id'])-5);
		  } else {
		   $resize_title = $mthumb_output['description'];
	    }
     
		// read out the Username
		$uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $mthumb_output['user_id'] . ";");
		$lbuname = $db->fetch_record($uresult);

		// Active Picture Highlighting
	    if ($mthumb_output['id'] != $_GET['pid']){
			$gl_lb_arr .= '<a href="'.$pcache->FilePath('upload/'.$mthumb_output['filename'], 'gallery').'" rel="lytebox['.$cat_name['category_name'].']" title="'.$resize_title.'&nbsp; &lt;span style=&quot;font-weight: normal;&quot;&gt;('.$tpic_width.' x '.$tpic_height.') '.$user->lang['gl_short_up_from'].$lbuname['username'].'&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-weight: normal;&quot;&gt;'.$thumb_comment_breaks.'&lt;/span&gt;"></a>';
		}	
	}
// Litebox Array - END


// Picture Description
if ($picture['description'] == ''){
	$title = substr($picture['filename'], 0, -strlen($picture['id'])-5);
} else {
	$title = $picture['description'];
}

// Import the Category View
$_GET['cat_id'] = $picture['category'];
include 'include/ov_function.php';
$cat_selection = paging('user');

// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);

if ($user->data['user_id'] == ANONYMOUS){
	$u_has_voted = true;
} else {
	$users_voted = unserialize($picture['voted_users']);
	$u_has_voted = (!$users_voted[$user->data['user_id']]) ? false : true;
}

// Send the Output to the template Files.

$tpl->assign_vars(array(
	'GL_HEADLINE'                    =>	$user->lang['gl_index_headline'],
	'GL_HEADLINE_CAT'                =>	$user->lang['gl_up_category'].$cat_name['category_name'],
  'GL_SHORT_UP_FROM'               =>	$user->lang['gl_short_up_from'],
	'GL_FILE_NAME'                   =>	$user->lang['gl_up_filename'],
	'GL_DESCRIPTION'                 => $user->lang['gl_up_description'],
  'GL_UP_FROM'                     =>	$user->lang['gl_up_from'],
	'GL_UP_DATE'                     =>	$user->lang['gl_up_date'],
	'GL_HITS'                        =>	$user->lang['gl_index_hits'],
	'GL_PIC_RESOLUTION'              =>	$user->lang['gl_pic_resolution'],
	'GL_URL_FLAG'                    =>	$user->lang['gl_pic_url'],
	'GL_BB_FLAG'                     =>	$user->lang['gl_pic_bbc'],
  'GL_ABOUT_HEADER'                => $user->lang['gl_about_header'],
  'GL_RATING'											 => $user->lang['gl_pic_rating'],
	'L_COPYRIGHT'            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
	'L_CREDITS'   => $jquery->Dialog_URL('GLAbout', $user->lang['gl_about_header'], 'about.php', '400', '300'),
	'GL_TITLE'                       =>	$title,
	'GL_UPLOAD'                      =>	$upload_button,
	'GL_PREV'                        =>	$prev_pic,
	'GL_FILENAME'                    =>	$picture['filename'],
	'GL_CATEGORY'                    => $cat_name['category_name'],
	'GL_PIC_WIDTH'                   =>	$pic_width,
	'GL_PIC_HEIGHT'                  =>	$pic_height,
	'GL_UNAME'                       =>	$uname['username'],
	'GL_DESCRIPTION_CONTENT_NOBREAK' => $comment,
	'GL_NEXT'                        =>	$next_pic,
	'GL_DESCRIPTION_CONTENT_HTML_FIX'=> $comment_html_fix,
	'GL_DATE'                        =>	$picture['date'],
	'GL_VISITS'                      =>	$picture['views'],
	'GL_URL_STREAM'                  =>	dirname($_SERVER['HTTP_REFERER']).'/showpic.php?filename='.$picture['filename'],
	'GL_BB_STREAM'                   =>	'[img]'.dirname($_SERVER['HTTP_REFERER']).'/showpic.php?filename='.$picture['filename'].'[/img]',
	'GL_RES_OUT'                     =>	$cat_selection,
	'GL_LB_ARR'                      => $gl_lb_arr,
	'GL_ROW_CLASS'                   =>	$eqdkp->switch_row_class(),
	'GL_DIRNAME'                     => $pcache->FolderPath('upload', 'gallery'),
	
	'GL_STARRATING'									 => $jquery->StarRating('vote', $ratings,'picview.php'.$SID.'&pic='.sanitize($picture['id']).'&mode=vote',sanitize($picture['rating']), $u_has_voted),
// Image-Resizer	
  'S_IMG_RESIZE_ENABLE'         => 'true',
 	'S_MAX_POST_IMG_RESIZE_WIDTH' => '450',
 	'S_IMG_RESIZE_WARNING'        => $user->lang['air_img_resize_warning'] , 
 	'S_IMG_WARNING_ACTIVE'        => 'false', 
 	'S_LYTEBOX_THEME'             => 'grey',
 	'S_LYTEBOX_AUTO_RESIZE'       => '1',
 	'S_LYTEBOX_ANIMATION'         => '1', 
));

// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'           => $user->lang['gallery'],
	'template_path'        => $pm->get_data('gallery', 'template_path'),
	'template_file'        => 'picview.html',
	'display'              => true)
  );

?>
