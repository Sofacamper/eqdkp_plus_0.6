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
define('PLUGIN', 'gallery');   // Must be set!
$eqdkp_root_path = './../../../'; // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once('../include/libloader.inc.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }


// Check user permission
$user->check_auth('a_gallery_cat');

// Change the Title and the Comment
if (isset ($_POST['cat_update_id'])){
  $title_up_query = $db->query("UPDATE __gallery_categories SET category_name = '".$in->get('cat_up_name')."' WHERE category_id = ".$in->get('cat_update_id', 0));
  $descr_up_query = $db->query("UPDATE __gallery_categories SET category_comment = '".$in->get('cat_up_description')."' WHERE category_id = ".$in->get('cat_update_id', 0));
}

// Move the Category up
if (isset($_GET['cat_up'])){
  $cat_up_query = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id");
  while ($prev_cat = $db->fetch_record($cat_up_query)) {
  	if ($prev_cat['category_id'] < $_GET['cat_up']) {
   	  $prev_category = $prev_cat['category_id'];
    } else {
      $last_category = $prev_cat['category_id'];
    }
  }
  $temp_last_cat = $last_category + 1;
  $temp_last_category = $db->query("UPDATE __gallery_categories SET category_id = '".$temp_last_cat."' WHERE category_id = '".$prev_category."'");
  $temp_pic_last = $db->query("UPDATE __gallery_pictures SET category = '".$temp_last_cat."' WHERE category = '".$prev_category."'");
  
  $temp_up_category = $db->query("UPDATE __gallery_categories SET category_id = '".$prev_category."' WHERE category_id = '".$_GET['cat_up']."'");
  $temp_pic_up = $db->query("UPDATE __gallery_pictures SET category = '".$prev_category."' WHERE category = '".$_GET['cat_up']."'");
    
  $temp_down_category = $db->query("UPDATE __gallery_categories SET category_id = '".$_GET['cat_up']."' WHERE category_id = '".$temp_last_cat."'");
  $temp_pic_down = $db->query("UPDATE __gallery_pictures SET category = '".$_GET['cat_up']."' WHERE category = '".$temp_last_cat."'");
  $_GET['cat_up'] = '';
}

// Move the Category down
if (isset($_GET['cat_down'])){
  $cat_down_query = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id DESC");
  while ($next_cat = $db->fetch_record($cat_down_query)) {
  	if ($next_cat['category_id'] > $_GET['cat_down']) {
   	  $next_category = $next_cat['category_id'];
    } else {
      $last_cat_query = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id DESC");
      $last_category = $db->fetch_record ($last_cat_query);
    }
  }
  $temp_last_cat = $last_category['category_id'] + 1;
  $temp_last_category = $db->query("UPDATE __gallery_categories SET category_id = '".$temp_last_cat."' WHERE category_id = '".$next_category."'");
  $temp_pic_last = $db->query("UPDATE __gallery_pictures SET category = '".$temp_last_cat."' WHERE category = '".$next_category."'");

  $temp_up_category = $db->query("UPDATE __gallery_categories SET category_id = '".$next_category."' WHERE category_id = '".$_GET['cat_down']."'");
  $temp_pic_up = $db->query("UPDATE __gallery_pictures SET category = '".$next_category."' WHERE category = '".$_GET['cat_down']."'");

  $temp_down_category = $db->query("UPDATE __gallery_categories SET category_id = '".$_GET['cat_down']."' WHERE category_id = '".$temp_last_cat."'");
  $temp_pic_down = $db->query("UPDATE __gallery_pictures SET category = '".$_GET['cat_down']."' WHERE category = '".$temp_last_cat."'");
  $_GET['cat_down'] = '';
}

// Operations

if($_POST['cat_name'] != ''){
	$new_cat = $db->query("INSERT INTO __gallery_categories (category_name, category_comment) VALUES ('".htmlentities(strip_tags($_POST['cat_name']), ENT_QUOTES)."', '".htmlentities(strip_tags($_POST['cat_comment']), ENT_QUOTES)."')");
	$op_output .= '<i>'.$_POST['cat_name'].'</i>'.$user->lang['gl_ad_created'];
} 

if($_GET['delete'] != '') {
  if ($_GET['delete'] == 'all'){
	  $del_cat_query = $db->query("SELECT * FROM __gallery_categories");
  	$del_cat = $db->fetch_record($del_cat_query);
    $file_list_query = $db->query("SELECT * FROM __gallery_pictures");  
  } else {
	  $del_cat_query = $db->query("SELECT * FROM __gallery_categories WHERE category_id = " . $in->get('delete', 0));
  	$del_cat = $db->fetch_record($del_cat_query);
    $file_list_query = $db->query("SELECT * FROM __gallery_pictures WHERE category = " . $in->get('delete', 0));
  }
  
  while ($file_list = $db->fetch_record($file_list_query)){
    unlink("../upload/".$file_list['filename']);
    unlink("../upload/mthumb/".$file_list['filename']);
    unlink("../upload/thumb/".$file_list['filename']);
    
    $del_entry = $db->query("DELETE FROM __gallery_pictures WHERE id = " . $file_list['id']);
  }

	if($_GET['delete'] == 'all'){
    $delete = $db->query("DELETE FROM __gallery_categories");  
	  $op_output .= $user->lang['gl_ad_cat_all_deleted'];
  } else {
    $delete = $db->query("DELETE FROM __gallery_categories WHERE category_id = " . $in->get('delete', 0));
	  $op_output .= '<i>'.$del_cat['category_name'].'</i>'.$user->lang['gl_ad_deleted'];
  }
}

// Output

$result = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id");
$counter = 0;

	while ($row = $db->fetch_record($result)){
	// create the up/down buttons
	if ($counter != 0){
	 $up_button = '<a href = "categories.php?cat_up='.$row['category_id'].'"><img src="../images/uparrow.png"></a>';
  } else {
    $up_button = '<img src="../images/uparrow_grey.png">';
  }
  
  $nextcount = $counter+1;
  $down_query = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id LIMIT " . $nextcount . ", 1");
  $down_res = $db->fetch_record($down_query);
  if ($down_res != '') {
  	$down_button = '<a href = "categories.php?cat_down='.$row['category_id'].'"><img src="../images/downarrow.png"></a>';
  } else {
    $down_button = '<img src="../images/downarrow_grey.png">';
  }

  //Count the Pictures in the Category
  $pic_count_query = $db->query("SELECT * FROM __gallery_pictures WHERE category=".$row['category_id']);
  $pic_count = $db->num_rows($pic_count_query);
  
		$tpl->assign_block_vars('cat_list', array(
			'ROW_CLASS'	   => $eqdkp->switch_row_class(),
			'LIST'         => $row['category_name'],
			'DESCRIPTION'  => $row['category_comment'],
			'PIC_COUNT'    => $pic_count,
			'UP_BUTTON'    => $up_button,
			'DOWN_BUTTON'  => $down_button,
			'CAT_ID'       => $row['category_id'],
		));
	$counter = $counter+1;
	}
	
	// Create the 'Delete All'-Link
  $catcheck_query = $db->query("SELECT * FROM __gallery_categories");
  $catcheck = $db->fetch_record($catcheck_query);
  if ($catcheck['category_id'] != ''){
    $category_check = $user->lang['gl_ad_cat_delall'];
  } else {
    $category_check = '';
  }

// Get the value of the shown Thumbnails
  $thumb_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'mthumbs'");
  $thumb = $db->fetch_record($thumb_query);

// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);


// Send the Output to the template Files.
$tpl->assign_vars(array(
	'GL_AD_CATEGORIES'       => $user->lang['gl_ad_categories'],
	'GL_AD_CATEGORY'         => $user->lang['gl_up_category'],
	'GL_AD_ADD_COMMENT'      => $user->lang['gl_ad_input_comment'],
	'GL_AD_MANAGE_PICTURES'  => $user->lang['gl_ad_manage_pictures'].":",
	'GL_AD_MANAGE_CAT_ORDER' => $user->lang['gl_ad_manage_cat_order'],
	'GL_AD_UPDATE_TITLE'     => $user->lang['gl_ad_manage_cat_update'],
	'GL_AD_CREATE'           => $user->lang['gl_ad_new_cat'],
	'GL_AD_SEND'             => $user->lang['gl_ad_send'],
	'GL_AD_RESET'            => $user->lang['gl_ad_reset'],
	'GL_AD_CAT_DELALL'       => $category_check,

	'ROW_CLASS'              => $eqdkp->switch_row_class(),
	'GL_AD_STATUS'           => $op_output,

	'L_COPYRIGHT'            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
	'L_CREDITS'   => $jquery->Dialog_URL('GLAbout', $user->lang['gl_about_header'], '../about.php', '400', '300'),
));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'     => $user->lang['gl_ad_manage_categories_ov'],
	'template_path'  => $pm->get_data('gallery', 'template_path'),
	'template_file'  => 'admin/gallery.html',
	'display'        => true)
  );

?>