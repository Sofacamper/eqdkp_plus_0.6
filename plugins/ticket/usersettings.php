<?php
 /*
 * Project:     EQdkp Ticket
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-02-28 16:47:21 +0100 (Sa, 28 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2007 Achaz, Maintained by PLUS Dev Team
 * @link        http://eqdkp-plus.com
 * @package     ticket
 * @version     $Rev: 4028 $
 * 
 * $Id: common.php 4028 2009-02-28 15:47:21Z wallenium $
 */
 
// EQdkp required files/vars
define('EQDKP_INC', true);
define('PLUGIN', 'ticket');
$eqdkp_root_path = './../../';
include_once ('includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'ticket')) { message_die('The Ticket plugin is not installed.'); }

//old check against guests accessing this page directly
$user->check_auth('u_ticket_submit');

//with the option added in 0.08 we need more security
if ($user->data['username']=="") {
message_die($user->lang['ticket_accdenied']); }


// Get the plugin
$ticket = $pm->get_plugin('ticket');


//---------
global $table_prefix;
global $db, $eqdkp, $user, $tpl, $pm;
global $SID;

$user_set = $db->query_first('SELECT count(*) FROM __ticket_userconfig WHERE user_id = ' . $user->data['user_id']);

// Save this shit
if ($_POST['issavebu']){
  // global config
  
      if($user_set != 0){
        $query = $db->build_query('UPDATE', array(
               'email' => $_POST['email'],
               'color' => $_POST['color'],
               ));
    	$worked=$db->query('UPDATE __ticket_userconfig SET ' . $query . ' WHERE user_id =' . $user->data['user_id']);
    	//if(!$worked){
		//return array("info" => "set_undeletion_failed", "info_id" => $session_id);
    	//}
       } else {
        $query = $db->build_query('INSERT', array(
               'user_id' =>  $user->data['user_id'],
               'email' => $_POST['email'],
               'color' => $_POST['color'],
               ));
    	$worked=$db->query('INSERT INTO __ticket_userconfig' . $query);
    	//if(!$worked){
		//return array("info" => "set_undeletion_failed", "info_id" => $session_id);
    	//}
       }
      if ($lala){
    echo($user->lang['is_conf_saved']);
	}
} 

//----------------

    //get Conf
             $sql = 'SELECT * FROM __ticket_config';
             $settings_result = $db->query($sql);
             while($roww = $db->fetch_record($settings_result)) {
             $conf[$roww['config_name']] = $roww['config_value'];
             }

//set user_set another time so the changes applied by the post_saving take affect even if a new db entry had to be created
$user_set = $db->query_first('SELECT count(*) FROM __ticket_userconfig WHERE user_id = ' . $user->data['user_id']);
	    	     
if($user_set != 0){
             $sql = 'SELECT * FROM __ticket_userconfig WHERE user_id =' .  $user->data['user_id'];
             if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data: Config-Table', '', __FILE__, __LINE__, $sql); }
             while($roww = $db->fetch_record($settings_result)) {
                 $crow['email']=$roww['email'];
                 $crow['color']=$roww['color'];
             }
} else {
    $crow['email']=1;
    $crow['color']=$conf['ticket_default_user_color'];
}


//------------------------
//
// Populate the fields
//
$tpl->assign_vars(array(
            // Form vars
           'F_CONFIG'        => 'usersettings.php' . $SID,
           'EMAIL' => ( $crow['email'] == 1 ) ? ' checked="checked"' : '',
           'COLORPICK_USER'        => $jquery->colorpicker('color', $crow['color']),

            // Form values
            'ROW_CLASS' => $eqdkp->switch_row_class(),

            // Language
            'L_SUBMIT'        => $user->lang['submit'],
            'L_RESET'         => $user->lang['reset'],
            'L_EMAIL' => $user->lang['ticket_email'],
            'L_EMAIL_NOTE' => $user->lang['ticket_email_note'],
            'L_COLOR' => $user->lang['ticket_color'],
      
            'L_GENERAL'       => $user->lang['ticket_settings_header'],
      
      )
		);
    
    $eqdkp->set_vars(array(
		'page_title'             => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': Settings',
		'template_path' 	       => $pm->get_data('ticket', 'template_path'),
		'template_file'          => 'settings.html',
		'display'                => true)
    );
    

?>
