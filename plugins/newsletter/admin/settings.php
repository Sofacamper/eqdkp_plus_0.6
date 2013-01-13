<?PHP 
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 1.3.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'newsletter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'newsletter')) { message_die('The Newsletter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_newsletter_');

// Check if the Update Check should ne enabled or disabled... In this case always enabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('newsletter', 'nl_', 'newsletter_config', 'include');

// The Version Information
$versionthing   = array(
      'name' 	=> 'newsletter',
      'version' => $pm->get_data('newsletter', 'version'),
      'build' 	=> $pm->plugins['newsletter']->build,
      'vstatus' => $pm->plugins['newsletter']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing);
$rbvcheck->PerformUpdateCheck();

// reset the version (to force an update)
if($in->get('version') == 'reset'){
	$gupdater ->DeleteVersionString();
	redirect('plugins/'.PLUGIN.'/admin/settings.php'.$SID);
}
// delete all mails from the queue
if($in->get('queue') == 'reset'){
	$sql = $db->query("DELETE FROM __newsletter_queue");
	//Success-Message
	System_Message($user->lang['nl_success_queue_cleared'],'Newsletter-Manager','green');
	
}

//Save Settings
if ($in->get('submit') != ""){
	
	//Update Updatecheck
	$nlclass->update_config($in->get('bridge_items', ''), "bridge_items");
	$nlclass->update_config($in->get('bridge_preselected', 0), "bridge_preselected");
	$nlclass->update_config($in->get('style_font_color', ''), "style_font_color");
	$nlclass->update_config($in->get('style_bg_color', ''), "style_bg_color");
	$nlclass->update_config($in->get('style_css', ''), "style_css");
	$nlclass->update_config($in->get('signature', ''), "signature");
	$nlclass->update_config($in->get('public_subscribe', 0), "public_subscribe");
	$nlclass->update_config($in->get('public_archive', 0), "public_archive");
	$nlclass->update_config($in->get('enable_updatecheck', 0), "enable_updatecheck");

				
	//Success-Message
	System_Message($user->lang['nl_success_settings_saved'],'Newsletter-Manager','green');
	
	//Delete Cache
	$pdc->del('plugin.newsletter.config');
 
};

//======================================
//Output-Section

//Load the plugin-config
//Cache: plugin.newsletter.config
  	$config_cache = $pdc->get('plugin.newsletter.config',false,true);
	if (!$config_cache){
		$config_query = $db->query("SELECT * FROM __newsletter_config");
		$config_data = $db->fetch_record_set($config_query);
		$db->free_result($config_query);
		$pdc->put('plugin.newsletter.config',$config_data,86400,false,true);
	} else{
		$config_data = $config_cache;
	};
			
	if (is_array($config_data)){
		foreach ($config_data as $elem){
			$conf[$elem['config_name']] = $elem['config_value'];
		};
	};

// mail methods
$mailmethod_dropdwn = array(
	'mail'      => $user->lang['nl_mail_mail'],
	'sendmail'  => $user->lang['nl_mail_sendmail'],
	'smtp'      => $user->lang['nl_mail_smtp'],
);
  
	$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID,
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              1 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              2 => array(
                  'name'    => $user->lang['nl_ad_archive'],
                  'link'    => 'archive.php'.$SID,
                  'img'     => 'archive.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              3 => array(
                  'name'    => $user->lang['nl_templates'],
                  'link'    => 'templates.php'.$SID,
                  'img'     => 'styles.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),

              
             
      );
	
// Send the Output to the template Files.
$tpl->assign_vars(array(
	'L_CONF_GEN'           					=> $user->lang['nl_general_settings'],
	'L_CONF_VIEW'           				=> $user->lang['nl_view_settings'],
	'L_CONF_EXTENDED'           			=> $user->lang['nl_extended_settings'],
	'L_DBVERSION'							=> $user->lang['nl_dbversion'],
	'L_FORCE_DBUPDATE'						=> $user->lang['nl_force_update'],
	'L_UPDATECHECK'							=> $user->lang['nl_updatecheck'],
	'NL_VERSION'   							=> $conf['nl_inst_version'].' ['.$pm->plugins['downloads']->vstatus.']',
	'NL_JS_FORCE_UPD'    					=> $jquery->Dialog_Confirm('ForceUpdate', $user->lang['nl_dbupdate_warn'], "window.location = 'settings.php?version=reset';"),
	'NL_JS_FORCE_DEL_QUEUE'    				=> $jquery->Dialog_Confirm('ForceDelQueue', $user->lang['nl_delqueue_warn'], "window.location = 'settings.php?queue=reset';"),
	
	'L_BUTTON_SAVE'							=> $user->lang['nl_button_save'],
	'L_BUTTON_RESET'						=> $user->lang['nl_button_reset'],
	'L_SIGNATURE'							=> $user->lang['nl_signature'],
	'L_PUBLIC_ARCHIVE'						=> $user->lang['nl_public_archive'],
	'L_PUBLIC_SUBSCRIBE'					=> $user->lang['nl_public_subscribe'],
	'L_DELETE_QUEUE'						=> $user->lang['nl_delete_queue'],
	'L_BG_COLOR'							=> $user->lang['nl_bg_color'],
	'L_FONT_COLOR'							=> $user->lang['nl_font_color'],
	'L_CSS_STYLE'							=> $user->lang['nl_css_style'],
	'L_BRIDGE_ITEMS'						=> $user->lang['nl_bridge_items'],
	'L_BRIDGE_PRESELECT'					=> $user->lang['nl_bridge_preselect'],
	
	'ADMIN_MENU'		=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),		  
	
	
	//Values
	 'UPDATE_CHECK'       					=> $nlclass->is_checked($conf['enable_updatecheck']),	 
	 'PUBLIC_SUBSCRIBE'       				=> $nlclass->is_checked($conf['public_subscribe']),
	 'PUBLIC_ARCHIVE'       				=> $nlclass->is_checked($conf['public_archive']),
	 'BRIDGE_PRESELECT'       				=> $nlclass->is_checked($conf['bridge_preselected']),
	 'SIGNATURE'       						=> sanitize($conf['signature']),	
	 'CSS_STYLE'   							=> sanitize($conf['style_css']),
	 'BRIDGE_ITEMS'   						=> sanitize($conf['bridge_items']),
	
	//Tooltips
	'NL_HELP_DEBUG'							=> $khrml->HTMLTooltip($user->lang['dl_help_debug'], 'pk_tt_help'),
	'JS_CP_BGCOLOR'  						=> $jquery->colorpicker('style_bg_color', $conf['style_bg_color']),
	'JS_CP_COLOR'  						 	=> $jquery->colorpicker('style_font_color', $conf['style_font_color']),
	
	
	//Common things
	'ABOUT_HEADER'             				=> $user->lang['nl_about_header'],	
	'NL_CREDITS'                			=> 'Credits',
	'NL_COPYRIGHT'              			=> $nlclass->Copyright(),
	'NL_JS_ABOUT'							=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
	
	'UPDATE_BOX'              				=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  					=> $rbvcheck->OutputHTML(),

));		


// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 						=> $nlclass->GeneratePageTitle($user->lang['config']),
	'template_path'							=> $pm->get_data('newsletter', 'template_path'),
	'template_file' 						=> 'admin/settings.html',
	'display'       						=> true)
);

?>