<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-12-13 14:45:43 +0100 (Sun, 13 Dec 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 6700 $
 *
 * $Id: info.php 6700 2009-12-13 13:45:43Z godmod $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'info');

$eqdkp_root_path = './../../';

include_once($eqdkp_root_path . 'common.php');
include_once ('./include/functions.php');
include_once($eqdkp_root_path . 'itemstats/eqdkp_itemstats.php');
global $user;

$user->check_auth('u_information_view');

if (!$pm->check(PLUGIN_INSTALLED, 'info')) {
	message_die('The Infopages plugin is not installed.');
}

//Default output for invalid requests
$info_page_data['page_content'] = "Invalid page id!";
$info_page_data['page_title'] = "Invalid page id!";


//Check for correct page
if (isset ($_GET['page'])){
	$info_page_id = intval(urldecode($_GET['page']));
	$info_pages = getPageList();

	if (array_key_exists($info_page_id, $info_pages)){
		$info_page_data = getPageData($info_page_id);
	}
	
	if(!allowedToViewPage($info_page_data['page_visibility']))
	 message_die('You\'re not allowed to view this page.');
	 
	if ($info_page_data['page_ise'] == 1){
		$info_page_data['page_content'] = itemstats_parse(html_entity_decode($info_page_data['page_content']));
	}
}

$tpl->assign_vars(array(
	  'INFO_PAGE_CONTENT' => $info_page_data['page_content'],
	  'INFO_PAGE_TITLE' => $info_page_data['page_title'], 
	  'EDITED' => $user->lang['info_edit_user'].getUserName($info_page_data['page_edit_user']).$user->lang['info_edit_date'].date($user->style['date_notime_long'], $info_page_data['page_edit_date']),
	  'CREDITS'		=> $user->lang['info_cp'] . $pm->get_data('info', 'version') . $user->lang['info_cp2']
	  )
);

$eqdkp->set_vars(array(
   'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$info_page_data['page_title'],
   'template_path' => $pm->get_data('info', 'template_path'),
   'template_file' => 'info.html',
   'display' => true)
);

?>
