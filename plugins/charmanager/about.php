<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-08-17 01:47:56 +0200 (So, 17 Aug 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 2560 $
 * 
 * $Id: about.php 2560 2008-08-16 23:47:56Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = '../../';
include_once('include/common.php');

if (!$pm->check(PLUGIN_INSTALLED, 'charmanager')) { message_die($user->lang['is_not_installed']); }

  // Build the data in arrays. Thats easier than editing the template file every time.
   $additions = array(
      'CharManager Logo'     => ' by Cattiebrie',
      'Icons'								=> ' Nuvola Package for Linux',
  );
        
         foreach ($additions as $key => $value)
        {
            $tpl->assign_block_vars('addition_row', array(
                'KEY'    => $key,
                'VALUE' => $value,
                )
            );
        }

$tpl->assign_vars(array(
    'I_ITEM_NAME'               => 'uc_logo.png',
    
    'D_AUTHOR_NAME'             => $pm->plugins['charmanager']->copyright,
    'D_AUTHOR_CITY'             => 'Germany',
    'D_WEB_URL'                 => 'eqdkp-plus.com',
    'L_CREATED_BY'              => $user->lang['uc_created by'],
    'L_CONTACT_INFO'            => $user->lang['uc_contact_info'],
    'L_URL_PERSONAL'            => $user->lang['uc_url_personal'],
    'L_URL_WEB'                 => $user->lang['uc_url_web'],
    'L_ADDITONS'                => $user->lang['uc_additions'] ,
    'L_TXT_DEVTEAM'							=> $user->lang['uc_created_devteam'],
    'L_DEVTEAM'									=> $user->lang['uc_copyright'],
    'L_VERSION'                 => $pm->get_data('charmanager', 'version'),
    'L_BUILD'                   => $pm->plugins['charmanager']->build,
    'L_STATUS'                  => $pm->plugins['charmanager']->vstatus,
    'L_YEARR'										=> strftime("%Y", time()),
));


$eqdkp->set_vars(array(
	'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['ts'],
	'template_file' => 'about.html',
	'template_path' => $pm->get_data('charmanager', 'template_path'),
	'display'       => true)
);
?>
