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

	//Ajax-Mode: xml
	if (($in->get('mode') == "ajax") && (is_numeric($in->get('template')))){
			
		$template_cache = $pdc->get('plugin.newsletter.templates.'.$in->get('template'),false,true);
		if (!$template_cache){
			$template_query = $db->query("SELECT * FROM __newsletter_templates WHERE id='".$db->escape($in->get('template'))."'");
			$template = $db->fetch_record($template_query);
			$db->free_result($template_query);
			$pdc->put('plugin.newsletter.templates.'.$in->get('template'),$template,86400,false,true);
		} else{
			$template = $template_cache;
		};
	
		$signature = (isset($template['signature'])) ? $template['signature'] : 1;
		
		header("Content-type: text/xml");
		header("Cache-Control: no-cache"); 
		echo '<?xml version="1.0"?><labels><label id="1"><newbody>'.utf8_encode(sanitize($template['body'])).'</newbody><subject>'.utf8_encode(sanitize($template['subject'])).'</subject><signature>'.sanitize($signature).'</signature></label></labels>';
		die();
		
	};

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

if ($in->get('mode')){
	//Template-Mode
	if (($in->get('mode') == "preview") && (is_numeric($in->get('id')))){
		
		//Load the template
		$template_cache = $pdc->get('plugin.newsletter.templates.'.$in->get('id'),false,true);
		if (!$template_cache){
			$template_query = $db->query("SELECT * FROM __newsletter_templates WHERE id='".$db->escape($in->get('id'))."'");
			$template = $db->fetch_record($template_query);
			$db->free_result($template_query);
			$pdc->put('plugin.newsletter.templates.'.$in->get('id'),$template,86400,false,true);
		} else{
			$template = $template_cache;
		};
		
		$bbclass = new BBcode();
		$bbclass->ChangeLightBoxStatus(true); 
		$bbclass->SetSmiliePath("../../../libraries/jquery/images/editor/icons/");
		
		$body = $bbclass->MyEmoticons(sanitize($template['body']));
		$body = $nlclass->toHTML($body);
		$body = $nl_bbcode->parse($body);
		$body = $bbclass->toHTML($body);
		if ($conf['style_bg_color'] != "" || $conf['style_font_color'] != ""){
			$bg_color = ($conf['style_bg_color'] != "") ? $conf['style_bg_color'] : "#ffffff";
			$font_color = ($conf['style_font_color'] != "") ? $conf['style_font_color'] : "#000000";
			
			$body = "<style> body { background-color:".sanitize($bg_color)."; color:".sanitize($font_color).";} </style>\n".$body;

		};
		if ($conf['style_css'] != ""){
			$body = "<style> ".sanitize($conf['style_css'])." </style>\n".$body;
		}
		echo "<html><head></head><body>";
		echo $body;
		echo "</body>";
		echo "</html>";
	};
	


} else {
  
	// Delete templates
	if (($in->get('doDelete') != "") && ($in->getArray('template', 'int') != "")){
	
		foreach ($in->getArray('template', 'int') as $elem) {
		
			if ($elem != 0){
				$del_query = $db->query("DELETE FROM __newsletter_templates WHERE id='".$db->escape($elem)."'");
			}
		};
	
	$pdc->del_prefix('plugin.newsletter.templates');
	};

	if ($in->get('mode') == "delete" && is_numeric($in->get('id'))){

		if ($in->get('id') != 0){
			$del_query = $db->query("DELETE FROM __newsletter_templates WHERE id='".$db->escape($in->get('id'))."'");
		};
		$pdc->del_prefix('plugin.newsletter.templates');
	};

	

	$template_cache = $pdc->get('plugin.newsletter.templates',false,true);
	if (!$template_cache){
		$template_query = $db->query("SELECT * FROM __newsletter_templates");
		$template = $db->fetch_record_set($template_query);
		$db->free_result($template_query);
		$pdc->put('plugin.newsletter.templates',$template,86400,false,true);
	} else{
		$template = $template_cache;
	};
	
	if (is_array($template)){
		foreach ($template as $row){
			
			$tpl->assign_block_vars('template_row', array(
		          'ROW_CLASS' 		=> $eqdkp->switch_row_class(),
		          'ID'              => $row['id'],
				  'NAME'            => $row['name'],
			));
			
		};
	};
	$templates_total = count($template);
	
$admin_optionsarray = array(
              0 => array(
                  'name'    => $user->lang['nl_addtemplate'],
                  'link'    => 'javascript:NewTemplate()',
                  'img'     => 'new.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
			  1 => array(
                  'name'    => $user->lang['nl_send'],
                  'link'    => 'send.php'.$SID,
                  'img'     => 'nl_logo.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              2 => array(
                  'name'    => $user->lang['nl_manage'],
                  'link'    => "newsletters.php".$SID,
                  'img'     => 'user_add.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              3 => array(
                  'name'    => $user->lang['nl_ad_archive'],
                  'link'    => 'archive.php'.$SID,
                  'img'     => 'archive.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
 
              4 => array(
                  'name'    => $user->lang['nl_settings'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_newsletter_manage', false),
                  ),
              
             
      );

    $tpl->assign_vars(array(

              
              'JS_ADD_TEMPLATE' 	=> $jquery->Dialog_URL('nlAddTemplate', $user->lang['nl_addtemplate'], "templates_add.php".$SID, '850', '600', 'templates.php'),
              'JS_EDIT_TEMPLA'  	=> $jquery->Dialog_URL('nlEditTemplate', $user->lang['nl_edittemplate'], "templates_edit.php".$SID."&id='+tid+'", '850', '600', 'templates.php'),
			  'JS_PREVIEW'			=> $jquery->Dialog_URL('nlPreview', $user->lang['nl_preview'].": '+name+'", "templates.php".$SID."&mode=preview&id='+id+'", '800', '600'),
              'S_NO_TEMPLATES'		=> ($templates_total < 1) ? true : false,
			  'L_NO_TEMPLATES'		=> $user->lang['nl_no_templates'],
              'L_DELETE'        	=> $user->lang['nl_button_delete'],
              'L_TEMPLATENAME'  	=> $user->lang['nl_templatename'],
			  'L_ACTION'  			=> $user->lang['nl_action'],
			  'L_PREVIEW'  			=> $user->lang['nl_preview'],
              'L_ADDTEMPLATE'   	=> $user->lang['nl_addtemplate'],
              'L_EDITTEMPLATE'  	=> $user->lang['nl_edittemplate'],
			  'L_DELETETEMPLATE'  	=> $user->lang['nl_deletetemplate'],
			  'L_MANAGE_TEMPLATES' 	=> $user->lang['nl_templates'],
			  
			  'ADMIN_MENU'		=> $jquery->DropDownMenu("admin_menu", "nl_colortab",  $admin_optionsarray, "plugins/newsletter/images/",$user->lang['nl_admin_menu']),		  
			  
			  
			 //Common things
			'ABOUT_HEADER'          => $user->lang['dl_about_header'],	
			'NL_CREDITS'            => 'Credits',
			'NL_COPYRIGHT'          => $nlclass->Copyright(),
			'NL_JS_ABOUT'			=> $jquery->Dialog_URL('About', $user->lang['nl_about_header'], '../about.php', '500', '350'),
	
			'UPDATE_BOX'           	=> $gupdater->OutputHTML(),
			'UPDCHECK_BOX'		  	=> $rbvcheck->OutputHTML(),

			  
		));
    
    $eqdkp->set_vars(array(
	    	'page_title'             => $nlclass->GeneratePageTitle($user->lang['nl_templates']),
			'template_path' 	     => $pm->get_data('newsletter', 'template_path'),
			'template_file'          => 'admin/template.html',
			'display'                => true)
    );
}
?>