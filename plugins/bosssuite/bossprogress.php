<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-05-04 17:38:10 +0200 (Mon, 04 May 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 4749 $
 *
 * $Id: bossprogress.php 4749 2009-05-04 15:38:10Z sz3 $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'bosssuite');

$eqdkp_root_path = './../../';
include_once ($eqdkp_root_path . 'common.php');

global $user;

$user->check_auth('u_bosssuite_bp_view');

if ( !$pm->check(PLUGIN_INSTALLED, 'bosssuite') )
{
	message_die('BossSuite plugin not installed.');
}
  
# Check whether the current game is supported
####################################################
// new mgs class
require(dirname(__FILE__).'/include/bsmgs.class.php');
$mybsmgs = new BSMGS();

if (!$mybsmgs->game_supported('bossbase')){
  $bcout = '<table width=100% class="borderless" cellspacing="0" cellpadding="2">
		        <tr><th colspan="2" align="center">BossCounter</th></tr>'."\n".
	         '<td>GAME NOT SUPPORTED!</td></tr></table>';
	$bchout = '<table cellpadding=2 cellspacing=0 border=0 width='.$BKtablewidth.' align=center>'."\n".
	          '<tr><td>GAME NOT SUPPORTED</td></tr></table>';
}else{
 
  $bs_image_suffix = null;
  $bs_image_map = null;
  
  function import_image_config(){ 
  global $bs_image_suffix, $bs_image_map, $mybsmgs;
    $mapfile = dirname(__FILE__)."/games/".$mybsmgs->get_current_game()."/image_config.php";
    if(file_exists($mapfile)){
      include($mapfile);
      $bs_image_suffix = $suffix;
      $bs_image_map = $image_map;
    }
  }
  
  import_image_config();

  # Get configuration data
  ####################################################
  $mybsmgs->load_game_specific_language('bossbase');
  require(dirname(__FILE__).'/include/bssql.class.php');
  $mybpsql = new BSSQL();

  $sbzone = $mybpsql->get_bzone('bossprogress');  
  
  $bb_conf = $mybpsql->get_config('bossbase');
  $bp_conf = $mybpsql->get_config('bossprogress');

  # Get data
  ####################################################
  $data = $mybpsql->get_data($bb_conf, $sbzone);
  
  # Get output
  ####################################################
  switch ($bp_conf['style']){
  	case 0:	require_once(dirname(__FILE__).'/include/bp_styles/bp_style.php');
            $bpout = bp_html_get_zoneinfo_bp($bp_conf, $data, $sbzone);
  			break; 	
  	case 1: require_once(dirname(__FILE__).'/include/bp_styles/bp_style_simple.php');
            $bpout = bp_html_get_zoneinfo_bps($bp_conf, $data, $sbzone);
  			break;
  	case 2: require_once(dirname(__FILE__).'/include/bp_styles/rp_2_column.php');
            $bpout = bp_html_get_zoneinfo_rp2r($bp_conf, $data, $sbzone);
  			break;
  	case 3: require_once(dirname(__FILE__).'/include/bp_styles/rp_3_column.php');
            $bpout = bp_html_get_zoneinfo_rp3r($bp_conf, $data, $sbzone);
  			break;
  }

}
//Framework include
include_once($eqdkp_root_path . 'plugins/bosssuite/include/libloader.inc.php');

# Assign Vars
####################################################
$tpl->assign_vars(array (
	'BOSSKILLVV' => $bpout,
	'JS_ABOUT' => $jquery->Dialog_URL('About', $user->lang['bs_about_header'], 'about.php', '500', '600'),
	'L_CREDITS' => $user->lang['bs_credits_p1'].$pm->get_data('bosssuite', 'version').$user->lang['bs_credits_p2'],
	'BS_INFO_IMG' => 'images/credits/info.png',
));

$eqdkp->set_vars(array (
	'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['bp_um_link'],
	'template_path' => $pm->get_data('bosssuite', 'template_path'),
	'template_file' => 'bossprogress.html',
	'display' => true)
	);
	
?>
