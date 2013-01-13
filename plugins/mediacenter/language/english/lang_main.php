<?php
// Global Strings
$lang['mediacenter'] 				= 'EQdkp-Plus MediaCenter';
$lang['mc_mediacenter'] 			= 'EQdkp-Plus MediaCenter';
$lang['mc_about_header'] 			= 'About the MediaCenter-Plugin';
$lang['mc_created_devteam'] 		= ' by GodMod';
$lang['mc_additionals'] 			= 'Additionals to this Plugin';
$lang['mc_licence'] 				= 'Licence';
$lang['mc_shortdesc']				= 'A Video-Portal for EQDKPlus';
$lang['mc_description']				= 'A Video-Portal for EQDKPlus';
$lang['mc_alpha_status'] 			= '<b>This is an not finished ALPHA-Version and NOT intended for productive use!</b>';
$lang['mc_beta_status'] 			= '<b>This is an BETA-Version and NOT intended for productive use!</b><br>Report Bugs to our <a href="http://eqdkp-plus.com/forum">Forum</a>.';

//Import
$lang['mc_import_headline'] 				= 'Import Videos';
$lang['mc_import'] 							= 'Import';
$lang['mc_import_no_files'] 				= 'There are no videos to import.';
$lang['mc_import_info'] 					= 'Files to import have to be the format flv, wma or wmv, avi or divx and uploaded to following folder:';
$lang['mc_import_success'] 					= 'The selected videos have been imported successfully.';
$lang['mc_import_footcount'] 				= '... found %d Video(s) to import';

// Install and Menu entrys
$lang['mc_mediacenter_short'] 		= 'Videos';
$lang['mc_settings']				= "Settings";
$lang['mc_stats']					= "Statistics";
$lang['mc_view']					= "View";
$lang['mc_upload']					= "Upload";
$lang['mc_categories']				= "Categories";
$lang['mc_category']				= "Category";
$lang['mc_manage_videos']			= "Manage Videos";
$lang['mc_manage_categories']		= "Manage Categories";
$lang['mc_config']					= "Settings";

$lang['mc_admin_todo']				= "There are unfinished tasks. Click <a href='".$eqdkp_root_path."plugins/mediacenter/admin/media.php'>here</a> to go to the Video-Management.";
$lang['mc_more_infos']				= 'More Informationen';
$lang['mc_less_infos']				= 'Less Informationen';
$lang['mc_show_story']				= 'Show Editor for the story for this video';
$lang['mc_story']					= 'Story for this video';
$lang['mc_hide_story']				= 'Hide Editor for the story for this video';

$lang['mc_video_not_supported']		= 'Unfortunatelly, this Video-format could not be supported.';
$lang['mc_contain_videos']			= 'Contains %d Videos';
$lang['mc_type']					= 'Type';
$lang['mc_type_video']				= 'Video';
$lang['mc_type_image']				= 'Image';
$lang['mc_videos']					= 'Videos';
$lang['mc_duration']				= 'Duration';
$lang['mc_tags']					= 'Tags';
$lang['mc_votes']					= 'Ratings';
$lang['mc_search'] 					= "Search";
$lang['mc_search_inputvalue'] 		= "Search...";
$lang['mc_newest']					= "Newest";
$lang['mc_most_viewed']				= "Most Viewed";
$lang['mc_best_rated']				= "Top Rated";
$lang['mc_preview_image']			= 'Preview Image';
$lang['mc_url']						= 'URL';
$lang['mc_select_file']				= 'Select File';
$lang['mc_information']				= 'Informationen';
$lang['mc_reportmail_subject']		= 'Reported Video';

//Settings
$lang['mc_config_saved_success']	= "Settings have been saved successfully.";
$lang['mc_updatecheck'] 			= 'Enable check for new Plugin Versions:';
$lang['mc_save'] 					= 'Save';
$lang['mc_force_db_update']			= 'Force Datebase-Update';
$lang['mc_force_db_update_warn']    = 'Should the Database Version be resettet? You will be able to update the table after that again!';
$lang['mc_extended_settings']    	= 'Advanced Settings';
$lang['mc_view_settings']			= 'Display Settings';
$lang['mc_show_link_tab'] 			= 'Show Link to the Video-Section on tab-menu:';
$lang['mc_items_per_page'] 			= 'Videos per Page:';
$lang['mc_single_vote'] 			= 'User only can rate one time per download:';
$lang['mc_enable_comments'] 		= 'Enable Comments:';
$lang['mc_admin_activation'] 		= 'Admin must confirm new Videos: ';
$lang['mc_prune_statistics'] 		= 'Prune Statistic-Data older than x days:';
$lang['mc_enable_statistics'] 		= 'Enable Statistics';
$lang['mc_reset_statistics'] 		= 'Delete Statistics';
$lang['mc_default_view'] 			= 'Default view';
$lang['mc_disable_reportmail'] 		= 'Disable Report-Email for Admin:';

//Help
$lang['mc_help_dbupdate']			= 'If you\'re upgrading from a previos Alpha/Beta Version, you need to force an update by hand. Click on the button behind the Version to force a n update of the Database tables.<br> Some update steps might fail if you\'re updating an alpha/beta, thats because of the existing changes in the database.';
$lang['mc_help_comments']			= 'User can submit their opinion about a Download. (This does not imfluence the rating-function!)';
$lang['mc_help_statistics']			= 'Enables detailed view-statistics over time.';
$lang['mc_help_prune_statistics']	= 'Prunes automatically all statistic-data older than x days. Leave empty to disable this function.';
$lang['mc_help_admin_activation']	= 'An Administrator has to confirm Videos added by user before they will apear in the media-center.';


$lang['mc_delete_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/ok.png">The selected Videos have been deleted successfully.';
$lang['mc_move_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/ok.png">The selected Videos have been moved successfully.';
$lang['mc_no_categories'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/credits/info.png">There are no categories. Please create a new Category at the  <a href="'.$eqdkp_root_path.'plugins/mediacenter/admin/categories.php">Category-Management</a>.';
$lang['mc_videos_in_cat_footcount']   	= "... found %1\$d Video(s) in this Category";
$lang['mc_catfootcount']   			= "... found %1\$d Categories";
$lang['mc_cat_novideos']   			= "There are no Videos in this Category.";
$lang['mc_delete'] 					= 'Delete';
$lang['mc_cancel'] 					= 'Cancel';
$lang['mc_reset'] 					= 'Reset';
$lang['mc_update'] 					= 'Update';
$lang['mc_edit'] 					= 'Edit';
$lang['mc_move'] 					= 'Move';
$lang['mc_to'] 						= 'to';
$lang['mc_go'] 						= 'Go';
$lang['mc_order'] 					= 'Order';
$lang['mc_select_all'] 				= 'Check all';
$lang['mc_deselect_all'] 			= 'Uncheck all';
$lang['mc_all_marked'] 				= 'marked...';
$lang['mc_upload'] 					= 'Insert Video';
$lang['mc_uploader'] 				= 'Uploader';
$lang['mc_uploaded'] 				= 'Uploaded on';
$lang['mc_action'] 					= 'Action';
$lang['mc_name'] 					= 'Name';
$lang['mc_description'] 			= 'Description';
$lang['mc_date'] 					= 'Date';
$lang['mc_views'] 					= 'Views';
$lang['mc_category_created'] 		= 'Category "<i>%s</i>" has been created successfully.';
$lang['mc_category_deleted'] 		= 'Category "<i>%s</i>" has been created successfully';
$lang['mc_category_updated'] 		= 'Category "<i>%s</i>" has been updated successfully.';
$lang['mc_all_categories_deleted'] 	= 'All Categories have been deleted.';
$lang['mc_categoy_delete_warn']		= 'The selected category still contains files. By deleting the category, they will be deleted, too.';
$lang['mc_create_category']			= 'Create new Category';
$lang['mc_create_video']			= 'Insert Video';
$lang['mc_edit_video']				= 'Edit Video';
$lang['mc_delete_video']			= 'Delete Video';
$lang['mc_create_images']			= 'Image-Upload';
$lang['mc_fields_empty'] 			= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png">Please fill out all required fields.';
$lang['mc_fields_empty_title'] 		= 'Some required fields are empty';

$lang['mc_more_from'] 				= 'More from';
$lang['mc_more_from_cat'] 			= 'More from the Category';
$lang['mc_related_videos'] 			= 'Related Videos';
$lang['mc_rating'] 					= 'Rating';
$lang['mc_ratings'] 				= 'Ratings';
$lang['mc_reset_ratings'] 			= 'Reset Ratings';
$lang['mc_embed'] 					= 'Embed';
$lang['mc_embed_info'] 				= 'If you want to embed this Video to your site, use to following code:';
$lang['mc_share'] 					= 'Share';
$lang['mc_report'] 					= 'Flag Video';
$lang['mc_bookmark'] 				= 'Bookmark';
$lang['mc_report_info'] 			= 'If this video is broken or violates guidelines, report it with to the administrator.';
$lang['mc_reported'] 				= 'Reported Videos';
$lang['mc_reported_info'] 			= '<b>This Video has been reported.</b> (<a href="%s">Click here to remove this message</a>)';
$lang['mc_reporter'] 				= 'Reported by:';
$lang['mc_reason'] 					= 'Reason:';
$lang['mc_unconfirmed'] 			= 'Unconfirmed Videos';
$lang['mc_confirm'] 				= 'Confirm';

$lang['mc_share_info'] 				= 'To link this Video in a forum, use the following BB-Code:';

//Statistics
$lang['mc_month_1'] = "January";
$lang['mc_month_2'] = "February";
$lang['mc_month_3'] = "March";
$lang['mc_month_4'] = "April";
$lang['mc_month_5'] = "May";
$lang['mc_month_6'] = "June";
$lang['mc_month_7'] = "July";
$lang['mc_month_8'] = "August";
$lang['mc_month_9'] = "September";
$lang['mc_month_10'] = "October";
$lang['mc_month_11'] = "November";
$lang['mc_month_12'] = "December";

$lang['mc_month_short_1'] = "Jan";
$lang['mc_month_short_2'] = "Feb";
$lang['mc_month_short_3'] = "Mar";
$lang['mc_month_short_4'] = "Apr";
$lang['mc_month_short_5'] = "May";
$lang['mc_month_short_6'] = "June";
$lang['mc_month_short_7'] = "July";
$lang['mc_month_short_8'] = "Aug";
$lang['mc_month_short_9'] = "Sept";
$lang['mc_month_short_10'] = "Oct";
$lang['mc_month_short_11'] = "Nov";
$lang['mc_month_short_12'] = "Dec";

$lang['mc_month'] = "Month";
$lang['mc_year'] = "Year";
$lang['mc_total'] = "Total";
$lang['mc_select_time'] = "Select Period";
$lang['mc_filter'] = "Filter by";
$lang['mc_load'] = "Load";


$lang['mc_stats_caching_info'] = "Due to caching this data may be old up to 24 hours. (<a href=\"statistics.php?do=del_cache\">Delete Cache</a>)";
$lang['mc_stats_deactivated'] = '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/credits/info.png"><b>The statistics are disabled. You can enable it <a href="'.$eqdkp_root_path.'plugins/mediacenter/admin/settings.php">here</a>.</b>';

$lang['mc_admin_menu'] 			= 'Admin-Menu  <img src="'.$eqdkp_root_path.'plugins/mediacenter/images/down.png">';
$lang['mc_view_menu'] 			= 'View  <img src="'.$eqdkp_root_path.'plugins/mediacenter/images/down.png">';
$lang['mc_view_details'] 			= 'List';
$lang['mc_view_thumbs'] 			= 'Images';

$lang['mc_search_no_matches'] 		= 'Your search for <b>"%s"</b> did not get any results.';
$lang['mc_search_matches'] 			= "%2\$s Results for <b>\"%1\$s\"</b> :";
$lang['mc_search_footcount'] 		= "... found %1\$d Video(s) / %2\$d per Page";
$lang['mc_search_no_value'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png">No search-term entered. Please enter a search-term.';


//Usersettings
$lang['mc_usersettings'] 		= 'MediaCenter-Settings';
$lang['mc_youtube_nick'] 		= 'Youtube-Username';
$lang['mc_youtube_pw'] 			= 'Youtube-Password';
$lang['mc_youtube_upload'] 		= 'Youtube-Upload';
$lang['mc_next_step'] 			= 'Continue';
$lang['mc_youtube_upload_success'] 	= 'The Video has been uploaded successfully to Video. After the Video has been confirmed from Youtube, the MediaCenter receives the last informations. <br><br>You can watch your Video on Youtube here:';
$lang['mc_youtube_auth_failed'] 	= 'Youtube-login failed. Please check your username and password.';

//Portal Module
$lang['votm'] 		= 'Video of the Moment';
$lang['pm_votm_tooltip'] = "Show Tooltip";
$lang['pm_votm_novideos'] = "There are no Videos.";
?>