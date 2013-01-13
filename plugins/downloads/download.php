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
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/


// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Download-plugin is not installed.'); };

// Check user permission
$user->check_auth('u_downloads_view');

//Include commen download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');

//Get Information about the download
//Cache: plugin.downloads.links.{ID}
$link_cache = $pdc->get('plugin.downloads.links.'.$in->get('id'),false,true);
if (!$link_cache) {
	$result = $db->query("SELECT * FROM __downloads_links WHERE id = '".$db->escape((int)$in->get('id'))."'");
	$row = $db->fetch_record($result);
	$pdc->put('plugin.downloads.links.'.$in->get('id'),$row,86400,false,true);
} else{
	$row = $link_cache;
};

if (!$row){ $error_msg = $user->lang['dl_index_error_perm'];
} else {


//Check permission
if ($user->data['user_id'] == ANONYMOUS) {

	if ($conf['disable_categories'] == 1){

		$permission = ($row['permission'] == 1) ? true : false;
	
	} else {
		//Check permission of the category - it overrides the download-permission
		//Cache: plugin.downloads.categories.{CATID}
		$category_cache = $pdc->get('plugin.downloads.categories.'.$row['category'],false,true);
		if (!$category_cache) {
			$cat_query = $db->query("SELECT * FROM __downloads_categories WHERE category_id = '".$db->escape($row['category'])."'");
			$cat_result = $db->fetch_record($cat_query);
			$pdc->put('plugin.downloads.categories.'.$row['category'],$cat_result,86400,false,true);
		} else{
			$cat_result = $category_cache;
		};
		
		
			if (($row['permission'] == 1) && ($cat_result['category_permission'] == 1)){
				$permission = true;
			} else {
				$permission = false;
			}
	
	};

} else {

	$permission = true;

};



	//Check if download is restricted and the user has the permission to download it
	if ( $permission == false ) {
		$error_msg = $user->lang['dl_index_error_perm'];
	}
	else {
	

		if ($in->get('mirror') != ""){
		
				$mirror_parts = explode("|", $row['mirrors']);
				$mirror = $mirror_parts[$in->get('mirror')-1];
				if ($mirror == ""){
					$error_msg = $user->lang['dl_index_error_perm'];
				}
				else {
					//Delete Cache
					$pdc->del_suffix('plugin.downloads');	
					$update_counter = $db->query("UPDATE __downloads_links SET
					views='".$db->escape($row['views'] + 1)."'
  					WHERE id=".$db->escape((int)$in->get('id')));
					redirect($mirror, false, true);
				}
		} 
		else {
		
			//If the download is a local file
			if ($row['local_filename'] != ""){
			

				if (($conf['traffic_limit'] != "") && ($conf['traffic_limit'] < ($conf['month'] + $row['file_size']) || ($conf['traffic_limit'] == 0))){
					$error_msg = $user->lang['dl_index_error_traffic']; $traffic_error = true;

				} 
				else {
					
					if (($conf['enable_recaptcha'] == 1) && ($user->data['user_id'] == ANONYMOUS)){
					
						if ($in->get('captcha_submit') != ""){
							
							$captcha = new recaptcha;
							$response = $captcha -> recaptcha_check_answer ($conf_plus['lib_recaptcha_pkey'],
                                  $_SERVER["REMOTE_ADDR"],
                                  $in->get('recaptcha_challenge_field'),
								  $in->get('recaptcha_response_field'));
							
							if ($response->is_valid) {
								
								//Delete Cache
   								$pdc->del_suffix('plugin.downloads');
								
								$update_counter = $db->query("UPDATE __downloads_links SET
								views='".$db->escape($row['views'] + 1)."'
  								WHERE id=".$db->escape((int)$in->get('id')));
					
								$update_traffic_file = $db->query("UPDATE __downloads_links SET
								traffic='".$db->escape($row['traffic'] + $row['file_size'])."'
  								WHERE id=".$db->escape((int)$in->get('id')));
								
								if ($conf['enable_statistics'] == 1) {
									$log = $db->query("INSERT INTO __downloads_stats :params", array(
					        			'fileID'				=> $db->escape($in->get('id')),
					        			'category'				=> $db->escape($row['category']),
					        			'date'					=> $db->escape(date("Y-m-d")),
									));
								};
				
								$new_traffic_month = $conf['traffic_month'] + $row['file_size'];
								$dlclass->update_config($new_traffic_month, "traffic_month");
					
								$new_traffic_total = $conf['traffic_total'] + $row['file_size'];
								$dlclass->update_config($new_traffic_total, "traffic_total");
	
								$local_filename = $pcache->FilePath('files/'.$row['local_filename'], 'downloads');
								if (file_exists($local_filename)) {
									header('Content-Type: application/octet-stream');
    								header('Content-Length: '.filesize($local_filename));
    								header('Content-Disposition: attachment; filename="'.sanitize($row['url']).'"');
    								header('Content-Transfer-Encoding: binary');
    				
									readfile($local_filename);
								}
								else {
								$error_msg = $user->lang['dl_ov_download_file_not_found'];
								};

 							 } else {

    							$error_msg = sprintf($user->lang['dl_captcha_wrong'], sanitize($row['name']));
								$show_captcha = true;
 				 			}
		
						}
						else {
						
								$error_msg = sprintf($user->lang['dl_captcha'], sanitize($row['name']));
								$show_captcha = true;
						
						}

					
					}
					else {
						//Delete Cache
   						$pdc->del_suffix('plugin.downloads');
						
						$update_counter = $db->query("UPDATE __downloads_links SET
						views='".$db->escape($row['views'] + 1)."'
  						WHERE id=".$db->escape((int)$in->get('id')));
					
						$update_traffic_file = $db->query("UPDATE __downloads_links SET
						traffic='".$db->escape($row['traffic'] + $row['file_size'])."'
  						WHERE id=".$db->escape((int)$in->get('id')));
						if ($conf['enable_statistics'] == 1) {
						$log = $db->query("INSERT INTO __downloads_stats :params", array(
					        	'fileID'				=> $db->escape($in->get('id')),
					        	'category'				=> $db->escape($row['category']),
					        	'date'					=> $db->escape(date("Y-m-d")),
						));						
						}
						$new_traffic_month = $conf['traffic_month'] + $row['file_size'];
						$dlclass->update_config($new_traffic_month, "traffic_month");
					
						$new_traffic_total = $conf['traffic_total'] + $row['file_size'];
						$dlclass->update_config($new_traffic_total, "traffic_total");
	
						$local_filename = $pcache->FilePath('files/'.$row['local_filename'], 'downloads');
						if (file_exists($local_filename)) {
							header('Content-Type: application/force-download');
    						header('Content-Length: '.filesize($local_filename));
    						header('Content-Disposition: attachment; filename="'.sanitize($row['url']).'"');
    						header('Content-Transfer-Encoding: binary');
    				
							readfile($local_filename);
						}
						else {
							$error_msg = $user->lang['dl_ov_download_file_not_found'];
						};
					}
    				
				}
			}

			else { //If the download is not a local file
				
				//Delete Cache
   				$pdc->del_suffix('plugin.downloads');

				$update_counter = $db->query("UPDATE __downloads_links SET
				views='".$db->escape($row['views'] + 1)."'
  				WHERE id=".$db->escape((int)$in->get('id')));
				if ($conf['enable_statistics'] == 1) {
				$log = $db->query("INSERT INTO __downloads_stats :params", array(
					        	'fileID'				=> $db->escape($in->get('id')),
					        	'category'				=> $db->escape($row['category']),
					        	'date'					=> $db->escape(date("Y-m-d")),
				));
				}
				redirect($row['url'], false, true);
	
			}
		}
	}

}

if ($error_msg != "")
{
	if ($conf['enable_mirrors'] == true){
	//Get Mirrors
		if (($traffic_error == true) && ($row['mirrors'] != "")){

			$s_mirrors = true;
			$mirror_parts = explode("|", $row['mirrors']);
			$mirrors = "<ul>";
			$i = 0;
				foreach ($mirror_parts as $elem){
				
					if($elem != ""){
					$mirror_array = parse_url($elem);
					
						$i = $i + 1;
					
						$tpl->assign_block_vars('mirror_list', array(
							"MIRROR" => "<a href=\"download.php?id=".sanitize($row['id'])."&mirror=".$i."\" target=\"_blank\">".sanitize(sprintf($user->lang['dl_l_mirrors_download'], $i)).": ".$mirror_array['scheme']."://".$mirror_array['host']."</a>",
							"MIRROR_ID" => $i,
							"ROW_CLASS" => $eqdkp->switch_row_class(),
							
						));
						
											
					}
				}
	
		
	
		}
	}

	if ($show_captcha == true){
		$captcha2 = new recaptcha;

		$tpl->assign_vars(array(								
			'DL_S_SHOW_CAPTCHA'					=> true,
			'DL_CAPTCHA'						=> $captcha2->GenerateCaptcha(),
			'DL_JS_FIELDS_EMPTY'				=> $jquery->Dialog_Alert('fields_empty', $user->lang['dl_ad_fields_empty'], $user->lang['dl_ad_fields_empty_title'],'300', '36'),
			
		));
	
	}
	
	// Send the Output to the template Files.
	$tpl->assign_vars(array(								
		'DL_HEADLINE'       				=> $user->lang['dl_index_headline'],
		'DL_L_CREDITS'      				=> 'Credits',
		'DL_L_COPYRIGHT'    				=> $dlclass->Copyright(),
		'DL_OV_ERROR'						=> $error_msg,
		'DL_DOWNLOAD_MIRRORS'				=> $user->lang['dl_l_mirrors'],
		'DL_S_MIRRORS'						=> $s_mirrors,
		'DL_DOWNLOAD_NAME'					=> $user->lang['dl_file_headline'].": ".sanitize($row['name']),
		'DL_DOWNLOAD_ID'					=> sanitize($row['id']),
		'DL_S_DL_EXISTS'					=> $permission,
		'DL_L_SEARCH_INPUTVALUE'			=> $user->lang['dl_search_inputvalue'],
		'DL_JS_ABOUT'						=> $jquery->Dialog_URL('About', $user->lang['dl_about_header'], 'about.php', '500', '350'),
		'SID'								=> $SID,	
	));



	// Init the Template Shit
	$eqdkp->set_vars(array(
		'page_title'           				=> sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline'],
		'template_path'       				=> $pm->get_data('downloads', 'template_path'),
		'template_file'        				=> 'download.html',
		'display'              				=> true
	));

};


?>