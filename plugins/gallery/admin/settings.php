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
include_once('../include/common.php');  // Must be set!

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }


// Check user permission
$user->check_auth('a_gallery_cat');

// Check if the Update Check should ne enabled or disabled...
$updchk_enbled = ( $row['gl_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('gallery', '', 'gallery_config', 'include');

// The Version Information
$versionthing   = array(
      'name' => 'gallery',
      'version' => $pm->get_data('gallery', 'version'),
      'build' => $pm->plugins['gallery']->build,
      'vstatus' => $pm->plugins['gallery']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output � DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing);
$rbvcheck->PerformUpdateCheck();


// Change the Amount of the shown Thumbnails and the Categories in the Overview
if(isset ($_POST['shownthumbs'])){
  $thumb_up_query = $db->query("UPDATE __gallery_config SET config_value = " . $_POST['shownthumbs'] . " WHERE config_name = 'mthumbs'");
}

if(is_numeric($_POST['showncategories'])){
  $cat_shown_update = $db->query("UPDATE __gallery_config SET config_value = '" . $_POST['showncategories'] ."' WHERE config_name = 'categories'");
}

if (isset ($_POST['submit'])){
  if ($_POST['extern'] == 1){
    $extern_update = $db->query("UPDATE __gallery_config SET config_value = '1' WHERE config_name = 'extern'");
  } else {
    $extern_update = $db->query("UPDATE __gallery_config SET config_value = '0' WHERE config_name = 'extern'");    
  }
  if ($_POST['limit'] == 1){
  	$db->query("UPDATE __gallery_config SET config_value = '1' WHERE config_name = 'limit'");
  } else {
  	$db->query("UPDATE __gallery_config SET config_value = '0' WHERE config_name = 'limit'");
  }
  $db->query("UPDATE __gallery_config SET config_value = '".$_POST['textstamp']."' WHERE config_name = 'textstamp'");
}

// Get the value of the shown Thumbnails
  $thumb_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'mthumbs'");
  $thumb = $db->fetch_record($thumb_query);
  $counter = 3;
  
// Create the Dropdown
  $dd = '
      <select name="shownthumbs">';
    while ($counter <= 30) {
    	if ($counter != $thumb['config_value']){
        $dd .= '<option>'.$counter.'</option>';
      } else {
        $dd .= '<option selected="selected">'.$counter.'</option>';
      }
      $counter = $counter+3;
    }
  $dd .= '</select>';
  
// Get the Value of the shown Categories
  $cat_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'categories'");
  $cat = $db->fetch_record($cat_query);

// Get the Value of the Extern-Check
  $extern_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'extern'");
  $extern = $db->fetch_record($extern_query);
  if ($extern['config_value'] == 1){
    $gl_ad_conf_extern_checkbox = '<input type="checkbox" name="extern" value="1" checked="checked">';
  } else {
    $gl_ad_conf_extern_checkbox = '<input type="checkbox" name="extern" value="1">';
  }
  
// Get the same for the Filesize-Limit
$limit_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'limit'");
  $limit = $db->fetch_record($limit_query);
  if ($limit['config_value'] == 1){
    $gl_ad_conf_limit_checkbox = '<input type="checkbox" name="limit" value="1" checked="checked">';
  } else {
    $gl_ad_conf_limit_checkbox = '<input type="checkbox" name="limit" value="1">';
  }
  
// Get the Value of the Textstamp
  $textstamp_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'textstamp'");
  $textstamp = $db->fetch_record($textstamp_query);
  $textstamp_input = '<input type="text" name="textstamp" class="input" value="'.$textstamp['config_value'].'">';

// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);
  
// Send the Output to the template Files.
$tpl->assign_vars(array(
	'GL_AD_CONF_GEN'           => $user->lang['gl_ad_conf_gen'],
	'GL_AD_CONF_SHOWN_MTHUMBS' => $user->lang['gl_ad_conf_shown_mthumbs'],
	'GL_AD_CONF_SHOWN_CATS'    => $user->lang['gl_ad_conf_shown_cats'],
	'GL_AD_CONF_EXTERN'        => $user->lang['gl_ad_conf_extern'],
	'GL_AD_CONF_YES'           => $user->lang['gl_ad_conf_yes'],
	'GL_AD_CONF_NO'            => $user->lang['gl_ad_conf_no'],
	'GL_AD_CONF_LIMIT'		   => $user->lang['gl_ad_conf_limit'],
	'GL_AD_TEXTSTAMP_F'        => $user->lang['gl_ad_textstamp_f'],
	'GL_UP_SEND'               => $user->lang['gl_up_send'],
	'L_CREDITS'   => $jquery->Dialog_URL('GLAbout',  $user->lang['gl_about_header'], '../about.php', '400', '300'),
	'L_COPYRIGHT'	            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
	'ROW_CLASS'                => $eqdkp->switch_row_class(),
	'GL_AD_TEXTSTAMP_INPUT'    => $textstamp_input,
	'GL_AD_CONF_EXTERN_CHECKBOX' => $gl_ad_conf_extern_checkbox,
	'GL_AD_CONF_LIMIT_CHECKBOX' => $gl_ad_conf_limit_checkbox,
	'GL_AD_CONF_SHOWN_THUMBS'  => $dd,
	'GL_AD_CONF_SHOWN_CATS_TO' => $cat['config_value'],
	
	'UPDATE_BOX'              => $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  => $rbvcheck->OutputHTML(),

));



// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 	=> $user->lang['gl_ad_manage_config_ov'],
	'template_path'	=> $pm->get_data('gallery', 'template_path'),
	'template_file' => 'admin/config.html',
	'display'       => true)
  );

?>