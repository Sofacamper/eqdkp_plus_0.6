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


define('EQDKP_INC', true);
define('PLUGIN', 'gallery');
$eqdkp_root_path = '../../';
include_once($eqdkp_root_path . 'common.php');

if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }

// Build the data in arrays. Thats easier than editing the template file every time.
$additions = array(
  'Design & Ideas by' => ' Lunary',
  'Code by'           => ' Badtwin',
);
        
foreach ($additions as $key => $value){
  $tpl->assign_block_vars('addition_row', array(
    'GL_KEY'    => $key,
    'GL_VALUE'  => $value,
    )
  );
}

// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);

$tpl->assign_vars(array(
  'GL_I_ITEM_NAME'    => 'credits/gl_logo.png',
  'GL_L_VERSION'      => $vers['config_value'],
  'GL_L_DEVTEAM'			=> 'Copyright',
  'GL_L_YEARR'				=> '2009',
  'GL_L_TXT_DEVTEAM'  => 'Badtwin & Lunary',
  'GL_L_URL_WEB'      => 'Web',
  'GL_D_WEB_URL'      => 'badtwin.dyndns.org',
  'GL_L_ADDITONS'     => $user->lang['gl_additionals'],
));


$eqdkp->set_vars(array(
  'page_title'    => $user->lang['gallery'],
  'template_file' => 'about.html',
  'template_path' => $pm->get_data('gallery', 'template_path'),
  'display'       => true)
);
?>
