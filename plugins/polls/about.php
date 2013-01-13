<?PHP
/*********************************************************************************\
* Project:	EQdkp-Plus																														*
* License:	Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/											*
* ------------------------------------------------------------------------------- *
* Polls 4 EQdkp plus																															*
* ------------------------------------------------------------------------------- *
* Project Start: 10/2009																													*
* Author: BadTwin																																	*
* Copyright: Andreas (BadTwin) Schrottenbaum																			*
* Link: http://badtwin.dyndns.org																									*
* Version: 0.0.1																																	*
\*********************************************************************************/


define('EQDKP_INC', true);
define('PLUGIN', 'polls');
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path . 'common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'polls')) {
	message_die('The Poll plugin is not installed.');
}

// Build the data in arrays. Thats easier than editing the template file every time.
$additions = array(
	'Design & Code by'    => 'BadTwin',
	'Credits-Logo'				=> '<a href="http://macleo.de">MacLeo</a>',
	'Icons'								=> 'Nuvola Package',
	'Special Thanks to'  	=> 'the whole EQdkp-Plus team',
);

foreach ($additions as $key => $value){
  $tpl->assign_block_vars('addition_row', array(
    'PO_KEY'    => $key,
    'PO_VALUE'  => $value,
    )
  );
}

$po_status  = (strtolower($pm->plugins['polls']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['polls']->vstatus.' ';

$act_year = date("Y", time());

$tpl->assign_vars(array(
  'PO_LOGO'					=> 'images/logo.png',
  'PO_VERSION'			=> $pm->get_data('polls', 'version').$po_status,
  'PO_DEVTEAM'			=> 'Copyright',
  'PO_YEARR'				=> ( $act_year == 2009) ? $act_year : '2009 - '.$act_year,
  'PO_TXT_DEVTEAM'	=> 'BadTwin',
  'PO_URL_WEB'			=> 'Web',
  'PO_WEB_URL'			=> 'www.eqdkp-plus.com',
  'PO_ADDITONS'			=> $user->lang['po_additionals'],
  'PO_LICENCE'			=> $user->lang['po_licence'],
));


$eqdkp->set_vars(array(
  'page_title'    => $user->lang['polls'],
  'template_file' => 'about.html',
  'template_path' => $pm->get_data('polls', 'template_path'),
  'display'       => true)
);
?>