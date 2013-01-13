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
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'gallery');      // Must be set!
$eqdkp_root_path = './../../../'; // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once('../include/libloader.inc.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }

// Check user permission
$user->check_auth('a_gallery_pics');

// Delete Picture
if ($_GET['del_id']!=''){
  $file_query = $db->query("SELECT * FROM __gallery_pictures WHERE id = " . $in->get('del_id'));
  $file_list = $db->fetch_record($file_query);
  unlink($pcache->FilePath('upload/'.$file_list['filename'], 'gallery'));
  unlink($pcache->FilePath('upload/mthumb/'.$file_list['filename'], 'gallery'));
  unlink($pcache->FilePath('upload/thumb/'.$file_list['filename'], 'gallery'));
  $cat_check_query = $db->query("SELECT * FROM __gallery_pictures WHERE category = " . $file_list['category']);
  $cat_count = $db->num_rows($cat_check_query);
  if ($cat_count <= 1){
    $_GET['cat_id'] = 'all';
  } else {
    $_GET['cat_id'] = $file_list['category'];
  }
  
  $del_query = $db->query("DELETE FROM __gallery_pictures WHERE id=" . $in->get('del_id'));
  $del_query = $db->query("DELETE FROM __comments WHERE attach_id=" . $in->get('del_id') . " AND page='gallery'");
}


// Update the picture data
if ($_POST['pid'] != ''){
  $update_query = $db->query("UPDATE __gallery_pictures SET 
    description='".htmlentities(strip_tags($_POST['description']), ENT_QUOTES)."',
    comment='".htmlentities(strip_tags($_POST['comment']), ENT_QUOTES)."',
    category='".$_POST['new_cat']."'
  WHERE id=".$in->get('pid'));
  $_GET['cat_id'] = $_POST['new_cat'];
}

// Set the ID to the first available Pic, if no one is given
if (!isset($_GET['pid'])){
  if (isset($_GET['cat_id']) AND $_GET['cat_id'] != 'all') {
  	$first_query = $db->query('SELECT * FROM __gallery_pictures WHERE category = '.$in->get('cat_id'));
  } else {
    $first_query = $db->query('SELECT * FROM __gallery_pictures');
  }
  $firstpic = $db->fetch_record($first_query);
  $_GET['pid'] = $firstpic['id'];
}

if ($firstpic['filename'] != '' or isset($_GET['pid'])){
  // Get the Picture Data
  $picture_query = $db->query('SELECT * FROM __gallery_pictures WHERE id = '.$in->get('pid'));
  $picture = $db->fetch_record($picture_query);

  // read out the Username
  if ($picture['user_id'] == '-1') {
    $uname['username'] = 'Anonymous';
  } else {
    $uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $picture['user_id'] . ";");
  	$uname = $db->fetch_record($uresult);
  }
	  
  // Get the current Category Data
  $cat_query = $db->query('SELECT * FROM __gallery_categories WHERE category_id = '.$picture['category']);
  $cat = $db->fetch_record($cat_query);

  // Create the Previous-/Next-Button
  if (is_numeric($_GET['cat_id'])){
    $prev_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id < ". $in->get('pid', 0)." AND category=".$in->get('cat_id', 0));
   	while($prev = $db->fetch_record($prev_sql)){
  		$prev_pic = '<a href="pictures.php?pid='.$prev['id'].'&cat_id='.$_GET['cat_id'].'"><img src="../images/leftarrow.png"></a>';
  	}
  } else {
    $prev_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id < ". $in->get('pid', 0));
   	while($prev = $db->fetch_record($prev_sql)){
  		$prev_pic = '<a href="pictures.php?pid='.$prev['id'].'"><img src="../images/leftarrow.png"></a>';
  	}
  }
  if ($prev_pic == ''){
  	$prev_pic = '<img src="../images/leftarrow_grey.png">';
  }

  if (is_numeric($_GET['cat_id'])){
    $next_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id > ". $in->get('pid', 0)." AND category=".$in->get('cat_id'));
    $next_pid = $db->fetch_record($next_sql);
    $next_pic = '<a href="pictures.php?pid='.$next_pid['id'].'&cat_id='.$_GET['cat_id'].'"><img src="../images/rightarrow.png"></a>';
  } else {
    $next_sql = $db->query("SELECT * FROM __gallery_pictures WHERE id > ". $in->get('pid', 0) ." ORDER BY id");
    $next_pid = $db->fetch_record($next_sql);
    $next_pic = '<a href="pictures.php?pid='.$next_pid['id'].'"><img src="../images/rightarrow.png"></a>';
  }
  if(!isset($next_pid['id'])){
  	$next_pic = '<img src="../images/rightarrow_grey.png">';
  }

  // Create the Category List
  $catlist_query = $db->query('SELECT * FROM __gallery_categories');
  $new_cat = '<select class="input" name="new_cat">';
  while ($new_catlist = $db->fetch_record($catlist_query)){
      if ($new_catlist['category_id'] == $picture['category']){
        $new_cat .= '<option value="'.$new_catlist['category_id'].'" selected="selected">'.$new_catlist['category_name'].'</option>';
      } else {
        $new_cat .= '<option value="'.$new_catlist['category_id'].'">'.$new_catlist['category_name'].'</option>';
      }  
  }
  $new_cat .= '</select>';

  // Get some Picture Details
  $picsize = getimagesize($pcache->FilePath('upload/'.$picture['filename'], 'gallery'));
  $pic_width = $picsize[0];
  $pic_height = $picsize[1];

  // Import the Paging funktion
  include '../include/ov_function.php';
  $mthumb_view = paging();

  if (isset($_GET['cat_id'])){
    $cat_selection = catselect($_GET['cat_id']);
  } else {
    $cat_selection = catselect('all');
  }

  // Set alternative Name if no Name is given 
 
  $picture_description = $picture['description'];
 
  if ($picture['description'] == "") {
  	 $picture_description = substr($picture['filename'], 0, -strlen($picture['id'])-5);
  }

  // Get the Version
	$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
	$vers = $db->fetch_record($vers_query);

  $tpl->assign_vars(array(
  	'GL_HEADLINE'                    =>	$user->lang['gl_index_headline'],
  	'GL_HEADLINE_CAT'                =>	$user->lang['gl_up_category'].$cat['category_name'],
  	'GL_AD_PIC_DEL_TITLE'            => $user->lang['gl_ad_pic_del_title'],
	  'GL_AD_PIC_DELCHECK'             => $user->lang['gl_ad_pic_delcheck'],
    'GL_AD_PIC_DEL_YES'              => $user->lang['gl_ad_pic_del_yes'],
  	'GL_AD_PIC_DEL_NO'               => $user->lang['gl_ad_pic_del_no'],
    'GL_SHORT_UP_FROM'               =>	$user->lang['gl_short_up_from'],
  	'GL_FILE_NAME'                   => $user->lang['gl_up_filename'],
  	'GL_DESCRIPTION'                 => $user->lang['gl_up_description'],
    'GL_UP_FROM'                     => $user->lang['gl_up_from'],
  	'GL_UP_DATE'                     => $user->lang['gl_up_date'],
  	'GL_NEW_CATEGORY'                => $user->lang['gl_up_category'],
  	'GL_PIC_RESOLUTION'              => $user->lang['gl_pic_resolution'],
	'GL_CREDITS'   => $jquery->Dialog_URL('GLAbout', $user->lang['gl_about_header'], '../about.php', '400', '300'),
	'GL_COPYRIGHT'            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
   	'GL_ROW_CLASS'                   => $eqdkp->switch_row_class(),
  	'GL_FILENAME'                    => $picture['filename'],
  	'GL_UPLOAD'                      => $cat_selection,
  	'GL_PREV'                        => $prev_pic,
  	'GL_TITLE_HTML'                  =>	htmlentities(strip_tags($picture_description), ENT_QUOTES),	
  	'GL_PIC_ID'                      =>	$picture['id'],
  	'GL_TITLE'                       => $picture['description'],
  	'GL_PIC_WIDTH'                   => $pic_width,
  	'GL_PIC_HEIGHT'                  => $pic_height,
  	'GL_UNAME'                       => $uname['username'],
  	'GL_DESCRIPTION_CONTENT_NOBREAK' => $comment,
  	'GL_NEXT'                        => $next_pic,
  	'GL_DESCRIPTION_CONTENT'         => $picture['comment'],
  	'GL_DATE'                        => $picture['date'],
  	'GL_NEW_CAT'                     => $new_cat,
  	'GL_RES_OUT'                     => $mthumb_view,
  	'GL_BACK_BTN'                    => $user->lang['gl_back_btn'],
  	'GL_DIRNAME'                     => $pcache->FolderPath('upload', 'gallery'),

  // Image-Resizer*/	
    'S_IMG_RESIZE_ENABLE'            => 'true',
   	'S_MAX_POST_IMG_RESIZE_WIDTH'    => '450',
   	'S_IMG_RESIZE_WARNING'           => $user->lang['air_img_resize_warning'] , 
   	'S_IMG_WARNING_ACTIVE'           => 'false', 
   	'S_LYTEBOX_THEME'                => 'grey',
   	'S_LYTEBOX_AUTO_RESIZE'          => '1',
   	'S_LYTEBOX_ANIMATION'            => '1', 
  ));


  // Init the Template Shit
  $eqdkp->set_vars(array(
  	'page_title'     => $user->lang['gl_ad_manage_pictures_ov'],
  	'template_path'  => $pm->get_data('gallery', 'template_path'),
  	'template_file'  => 'admin/pictures.html',
  	'display'        => true)
    );
} else {

  // Page for the empty Gallery
  $tpl->assign_vars(array(
    'GL_AD_PIC_NOPICS'  => $user->lang['gl_ad_pic_nopics'],
    ));

  $eqdkp->set_vars(array(
  	'page_title'     => $user->lang['gl_ad_manage_pictures_ov'],
  	'template_path'  => $pm->get_data('gallery', 'template_path'),
  	'template_file'  => 'admin/no_pictures.html',
  	'display'        => true)
    );
}
?>