<?php
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

// Global Strings
$lang['gallery'] = 'EQdkp-Plus Gallery';
$lang['gl_about_header'] = 'About the Gallery';
$lang['gl_created_devteam'] = ' by  Badtwin & Lunary';

// About Window
$lang['gl_additionals'] = 'Additionals to Gallery';

// Install and Menu entrys
$lang['gl_view'] = 'Gallery';
$lang['gl_upload'] = 'Upload';
$lang['gl_shortdesc']			= 'A Simple Gallery for EQdkp-Plus';
$lang['gl_description']			= 'Simple Gallery with Comments for EQdkp-Plus';
$lang['gl_ad_manage_categories'] = 'Categories';
$lang['gl_ad_manage_pictures'] = 'Pictures';
$lang['gl_ad_manage_categories_ov']	= 'Manage Categories';
$lang['gl_ad_manage_pictures_ov']	= 'Manage Pictures';
//$lang['gl_ad_manage_comments']		= 'Kommentare';
//$lang['gl_ad_manage_comments_ov']		= 'Kommentare verwalten';
$lang['gl_ad_manage_config'] = 'Config';
$lang['gl_ad_manage_config_ov'] = 'Config Settings';

// Admin Categories
$lang['gl_ad_created'] = ' has been created.';
$lang['gl_ad_deleted'] = ' has been deleted.';
$lang['gl_ad_categories'] = 'Existing Categories';
$lang['gl_up_category'] = 'Categorie: ';
$lang['gl_ad_input_comment'] = 'Description: ';
//$lang['gl_ad_manage_pictures'] = '';
$lang['gl_ad_manage_cat_order'] = 'Order';
$lang['gl_ad_manage_cat_update'] = 'Update';
$lang['gl_ad_new_cat'] = 'Create new Category';
$lang['gl_ad_send'] = 'create';
$lang['gl_ad_reset'] = 'reset';
//$lang['gl_ad_manage_categories_ov'] = '';
$lang['gl_ad_cat_delall']			= 'Remove all Categories';
$lang['gl_ad_cat_all_deleted'] = 'All Categories have been removed!';

// Admin Config
$lang['gl_ad_conf_gen'] = 'Common';
$lang['gl_ad_conf_shown_mthumbs'] = 'Shown Thumbnails per Page:';
$lang['gl_ad_conf_shown_cats'] = 'Shown Categories per Page:';
$lang['gl_ad_textstamp_f'] = 'Shown text in the external view:';
$lang['gl_ad_conf_extern'] = 'Should the Pictures be visible from an external Source?';
$lang['gl_ad_conf_limit'] = 'Activate 1-MB limit for uploaded files?';

// Admin Pictures
$lang['gl_index_headline'] = 'Gallery - Index';
//gl_up_category
$lang['gl_ad_pic_del_title'] = 'delete?';
$lang['gl_ad_pic_delcheck'] = 'Do you really want to delete this Picture?';
$lang['gl_ad_pic_del_yes'] = 'Yes';
$lang['gl_ad_pic_del_no'] = 'No';
$lang['gl_short_up_from'] = 'by ';
$lang['gl_up_filename'] = 'Filename: ';
$lang['gl_up_description'] = 'Description: ';
$lang['gl_up_from'] = 'Uploaded by: ';
$lang['gl_up_date'] = 'Uploaded on: ';
//gl_up_category
$lang['gl_pic_resolution'] = 'Resolution: ';
$lang['gl_ad_pic_nopics'] = 'There are no Pictures in the Gallery!';

// Gallery Insertpic
$lang['gl_up_suc_text1'] = 'The picture has been saved as ';
$lang['gl_up_suc_text2'] = ' .';
$lang['gl_up_suc_status'] = 'File saved.';
$lang['gl_up_fail_toobig'] = 'The picture filesize must not be bigger than 1 MB.';
$lang['gl_up_fail_status'] = 'THE PICTURE HAS <b>NOT</b> BEEN UPLOADET!';
$lang['gl_up_fail_format'] = 'Only Pictures in JPG-, PNG- and GIF-Format are allowed.';
//gl_up_fail_status
//gl_index_headline
$lang['gl_up_header'] = 'Upload';
//gl_up_filename
$lang['gl_up_size'] = 'Filesize: ';
$lang['gl_up_cat'] = 'Category: ';
$lang['gl_up_comment'] = 'Description: ';
$lang['gl_up_user'] = 'Username:';
//gl_up_date
$lang['gl_up_new'] = 'Upload a new Picture';
$lang['gl_up_btt'] = 'Back to Topic';
//air_img_resize_warning

// Category View
$lang['gl_index_upload'] = 'Upload';
//gl_index_headline
//gl_up_category
//gl_up_comment
$lang['gl_cat_picture_count'] = 'Number of Pics:';
$lang['gl_cat_note'] = 'Please click on the category-name to view the first picture of this category or click a picture to view the random picture directly.';
$lang['gl_no_cat_created'] = 'ERROR - No Category created yet!';
$lang['gl_no_gdlib2'] = 'ERROR - GDLib2 is not installed on this server!';
$lang['gl_back_btn'] = 'Back';

// gl_picview
$lang['gl_pic_descript_warn'] = '[no description available]';
//gl_index_upload
//gl_short_up_from
//gl_index_headline
//gl_up_category
//gl_short_up_from
//gl_up_filename
//gl_up_description
//gl_up_from
//gl_up_date
$lang['gl_index_hits'] = 'Visited:';
//gl_pic_resolution
$lang['gl_pic_url'] = 'Picture URL: ';
$lang['gl_pic_bbc'] = 'BB-Code (for Forums): ';
$lang['gl_pic_rating'] = 'Rating:';
//air_img_resize_warning

// Upload View
$lang['gl_up_not_writeable'] = 'The Upload-Folder is not writeable!! (chmod 0777)';
$lang['gl_thumb_not_writeable'] = 'The thumb-Folder is not writeable!! (chmod 0777)';
$lang['gl_mthumb_not_writeable'] = 'The mthumb-Folder is not writeable!! (chmod 0777)';
$lang['gl_thumb_error_creating_folder'] = 'Error while creating subfolder!!';
//gl_index_headline
//gl_up_header
$lang['gl_up_file'] = 'File: ';
$lang['gl_up_name'] = 'Name: ';
//gl_up_category
//gl_up_description
$lang['gl_info'] = 'Info';
$lang['gl_filename_describe']			= 'Click to select the file you want to upload.<br /><br />Allowed formats are "*.jpg", "*.gif", "*.png"';
$lang['gl_name_describe']			= 'Choose a name, that will be shown for the picture. If you don\'t write down a name, the filename will be selected.';
$lang['gl_cat_describe']			= 'Select the category where you want to save your picture.';
$lang['gl_describtion_describe']			= 'Here you can write down further details of the picture.<br />BBCode and HTML is not allowed here.';
$lang['gl_up_send'] = 'send';
$lang['gl_up_reset'] = 'reset';

// Include
$lang['gl_ov_no_pics_in_cat'] = 'No Pictures found in the selected Category.';
//gl_pic_descript_warn
//gl_up_description
//gl_up_from
//gl_up_date
//gl_index_hits
$lang['gl_pic_comm_head'] = 'Comments';
//gl_pic_resolution
$lang['ad_sel_all_cat'] = 'All Categories';

// Portal Module
$lang['gl_ad_selall'] = 'All Pictures';
//gl_up_cat
//gl_up_description
//gl_up_from
//gl_up_date
//gl_index_hits
//gl_pic_resolution
$lang['pm_potm_maxwidth'] = 'Maximum Width (100-200 px):';
$lang['pm_potm_maxheight'] = 'Maximale Height (100-200 px):';
$lang['picofthemoment'] = 'Pic of the Moment';
$lang['pm_potm_catshow'] = 'Show only Category:';
$lang['pm_potm_tooltip']	=	'Show Tooltip?';
?>
