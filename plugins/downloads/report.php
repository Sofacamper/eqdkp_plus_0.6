<?PHP
  /**********************************************************************************\
  * Project:   Downloads for EQdkp-Plus                                              *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Project-Start:	 05/2009                                                         *
  * Author:    GodMod                                                                *
  * Copyright: 2009 GodMod                                                           *
  * Link:      http://eqdkp-plus.com/forum                                           *
  * Package:   Downloads                                                             *
  \**********************************************************************************/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!

//Load global plugin functions
include_once($eqdkp_root_path . 'plugins/downloads/include/libloader.inc.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Download-plugin is not installed.'); };

// Check user permission
$user->check_auth('u_downloads_view');

$redirect_url = "downloads.php".$SID."&file=".sanitize($in->get('file'));
	

if ($in->get('submit') != ""){

	if ( $user->data['user_id'] != ANONYMOUS ){
	
	//Get Download
	//Cache: plugin.downloads.links.{ID}
	$links_cache = $pdc->get('plugin.downloads.links.'.$in->get('file'),false,true);
	if (!$links_cache) {
		$download_query = $db->query("SELECT * FROM __downloads_links WHERE id = '".$db->escape($in->get('file'))."'");
		$download = $db->fetch_record($download_query);
		$pdc->put('plugin.downloads.links.'.$in->get('file'),$download,86400,false,true);
	} else{
		$download = $links_cache;
	};
	
	//Query: get E-Mail-Adress from the uploader
	$user_email_query = $db->query("SELECT user_email, username FROM __users WHERE user_id = '".$db->escape($download['user_id'])."'");
	$user_email_count = $db->affected_rows();
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
      	
	$user_email = $db->fetch_record($user_email_query);
	$user_name = $user_email['username'];
	$user_email = $user_email['user_email'];
		
	//Look if the download or mirror is reported - don't make it twice...
	$reported = unserialize($download['reported']);
	$is_reported = false;
	if ($in->get('mirror') != 0){
		$is_reported = ($reported[$in->get('mirror')-1]) ? true : false;
	} else {
		$is_reported = (isset($reported['dl'])) ? true : false;
	};
	
	if ($is_reported == false){


		if ($in->get('mirror') != 0){
			
			$mirror_parts = explode("|", $download['mirrors']);
			$mirror = $mirror_parts[$in->get('mirror')-1];
			$download['name'] = $download['name'].", ".$user->lang['dl_l_mirrors'].': '.$mirror;
			$reported_array = unserialize($download['reported']);
			$reported_array[$in->get('mirror')-1] = $in->get('dl_comment');
		
			$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", 				
				array(
					'reported'	=> serialize($reported_array),
					        	
				));
		
		} else {
		
			$reported_array = unserialize($download['reported']);
			$reported_array['dl'] = $in->get('dl_comment');
		
			$update_query = $db->query("UPDATE __downloads_links SET :params WHERE id = '".$db->escape($in->get('file',0))."'", 				
				array(
					  'reported'	=> serialize($reported_array),
					        	
				));
	
		}
	
		//delete Cache
		$pdc->del_suffix('plugin.downloads.links');
	
		//If the uploader is reachable
		if ($user_email_count > 0){
		
	
			$bodyvars = array(
			  'USERNAME'    		=> $user_name,
			  'DL_NAME'   			=> $download['name'],
			  'DL_LINK'   			=> $DKP_server_url."plugins/downloads/downloads.php?id=".sanitize($download['id']),
			  'DATE'  				=> date("Y-m-d H:i"),
			  'REPORTER'    		=> sanitize($user->data[username]),
			  'REPORTER_COMMENT'	=> sanitize($in->get('dl_comment')),
			  'GUILDNAME'			=> $eqdkp->config['guildtag'],
			);

        	$mail->SendMailFromAdmin($user_email, $user->lang['dl_report_email_subject'], 'report.html', $bodyvars, $conf_plus['lib_email_method']);

		};
	
		
		$admin_email = $eqdkp->config['admin_email'];
		
		//Send the mail to the admin
		if ($admin_email != $user_email) {
			
			$bodyvars = array(
			  'USERNAME'    		=> "Administrator",
			  'DL_NAME'   			=> $download['name'],
			  'DL_LINK'   			=> $DKP_server_url."plugins/downloads/downloads.php?id=".sanitize($download['id']),
			  'DATE'  				=> date("Y-m-d H:i"),
			  'REPORTER'    		=> sanitize($user->data[username]),
			  'REPORTER_COMMENT'	=> sanitize($in->get('dl_comment')),
			  'GUILDNAME'			=> $eqdkp->config['guildtag'],
			);
		

        	$mail->SendMailFromAdmin($admin_email, $user->lang['dl_report_email_subject'], 'report.html', $bodyvars, $conf_plus['lib_email_method']);
		};
	
	}; //END if is_reported == false
	
	$error_msg = "<script>parent.window.location.href = '".$redirect_url."';</script>";
	
	}; //Close if user if logged in
	
} //Close if submit
	


// Send the Output to the template Files.
$tpl->assign_vars(array(
	'DL_UP_SEND'               			=> $user->lang['dl_up_send'],
	'DL_REPORT_DEAD_LINK_INFO'			=> $user->lang['dl_l_report_dead_link_info'],
	'DL_REPORT_DEAD_LINK'				=> $user->lang['dl_l_report_dead_link'],
	'SID'								=> $SID,
	'DL_AD_RESET'						=> $user->lang['dl_ad_reset'],
	'DL_OV_ERROR'						=> $error_msg,
	'DL_S_USER_LOGGED_IN'				=> ( $user->data['user_id'] != ANONYMOUS ) ? true : false,
	'DL_USER_NOT_LOGGED_IN'				=> $user->lang['dl_report_not_logged_in'],
			
));




// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['dl_ad_manage_config_ov'],
	'template_path'			=> $pm->get_data('downloads', 'template_path'),
	'template_file' 		=> 'report.html',
	'gen_simple_header'  	=> true,
	'display'       		=> true)
  );

?>