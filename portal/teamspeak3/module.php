<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2010
 * Date:        $Date: 2010-02-08 12:24:51 +0100 (Mon, 08 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: Sylna $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7186 $
 * 
 * $Id: module.php 7186 2010-02-08 11:24:51Z Sylna $
 */


if ( !defined('EQDKP_INC') ){ 
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['teamspeak3'] = array(
			'name'			=> 'Teamspeak3 Module',
			'path'			=> 'teamspeak3',
			'version'		=> '0.6',
			'author'        => 'Sylna',
			'contact'		=> 'http://www.eqdkp-plus.com',
			'description'   => 'Teamspeak3 Server Information',
			'positions'     => array('left1', 'left2', 'right'),
			'install'       => array(
								'autoenable'        => '0',
								'defaultposition'   => 'left2',
								'defaultnumber'     => '15',
							),
    );

$portal_settings['teamspeak3'] = array(
	'pk_ts3_ip'		=> array(
		'name'		=> 'pk_ts3_ip',
		'language'	=> 'lang_pk_ts3_ip',
		'property'	=> 'text',
		'size'		=> '15',
		'help'		=> '',
	),
	'pk_ts3_port'		=> array(
		'name'		=> 'pk_ts3_port',
		'language'	=> 'lang_pk_ts3_port',
		'property'	=> 'text',
		'size'		=> '5',
		'help'		=> '',
	),
	'pk_ts3_telnetport'		=> array(
		'name'		=> 'pk_ts3_telnetport',
		'language'	=> 'lang_pk_ts3_telnetport',
		'property'	=> 'text',
		'size'		=> '5',
		'help'		=> '',
	),
	'pk_ts3_id'		=> array(
		'name'		=> 'pk_ts3_id',
		'language'	=> 'lang_pk_ts3_id',
		'property'	=> 'text',
		'size'		=> '2',
		'help'		=> 'lang_help_pk_ts3_id',
	),
	'pk_ts3_cache'		=> array(
		'name'		=> 'pk_ts3_cache',
		'language'	=> 'lang_pk_ts3_cache',
		'property'	=> 'text',
		'size'		=> '2',
		'help'		=> 'lang_help_pk_ts3_cache',
	),
	'pk_ts3_banner'		=> array(
		'name'		=> 'pk_ts3_banner',
		'language'	=> 'lang_pk_ts3_banner',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_join'		=> array(
		'name'		=> 'pk_ts3_join',
		'language'	=> 'lang_pk_ts3_join',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_jointext'		=> array(
		'name'		=> 'pk_ts3_jointext',
		'language'	=> 'lang_pk_ts3_jointext',
		'property'	=> 'text',
		'size'		=> '30',
		'help'		=> '',
	),
	'pk_ts3_legend'		=> array(
		'name'		=> 'pk_ts3_legend',
		'language'	=> 'lang_pk_ts3_legend',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_cut_names'		=> array(
		'name'		=> 'pk_ts3_cut_names',
		'language'	=> 'lang_pk_ts3_cut_names',
		'property'	=> 'text',
		'size'		=> '2',
		'help'		=> 'lang_help_pk_ts3_cut_names',
	),
	'pk_ts3_cut_channel'		=> array(
		'name'		=> 'pk_ts3_cut_channel',
		'language'	=> 'lang_pk_ts3_cut_channel',
		'property'	=> 'text',
		'size'		=> '2',
		'help'		=> 'lang_help_pk_ts3_cut_channel',
	),
	'pk_only_populated_channel'		=> array(
		'name'		=> 'pk_only_populated_channel',
		'language'	=> 'lang_pk_only_populated_channel',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_useron'		=> array(
		'name'		=> 'pk_ts3_useron',
		'language'	=> 'lang_pk_ts3_useron',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats'		=> array(
		'name'		=> 'pk_ts3_stats',
		'language'	=> 'lang_pk_ts3_stats',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats_showos'		=> array(
		'name'		=> 'pk_ts3_stats_showos',
		'language'	=> 'lang_pk_ts3_stats_showos',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats_version'		=> array(
		'name'		=> 'pk_ts3_stats_version',
		'language'	=> 'lang_pk_ts3_stats_version',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats_numchan'		=> array(
		'name'		=> 'pk_ts3_stats_numchan',
		'language'	=> 'lang_pk_ts3_stats_numchan',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats_uptime'		=> array(
		'name'		=> 'pk_ts3_stats_uptime',
		'language'	=> 'lang_pk_ts3_stats_uptime',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_stats_install'		=> array(
		'name'		=> 'pk_ts3_stats_install',
		'language'	=> 'lang_pk_ts3_stats_install',
		'property'	=> 'checkbox',
		'size'		=> false,
		'options'	=> false,
		'help'		=> '',
	),
	'pk_ts3_timeout'		=> array(
		'name'		=> 'pk_ts3_timeout',
		'language'	=> 'lang_pk_ts3_timeout',
		'property'	=> 'text',
		'size'		=> '20',
		'help'		=> 'lang_help_pk_ts3_timeout',
	),
);

if(!function_exists(teamspeak3_module)){
  function teamspeak3_module(){
  		global $tpl, $eqdkp, $eqdkp_root_path, $conf_plus ,$user, $plang, $pdc;
		
		$cachetime = ($conf_plus['pk_ts3_cache'] == '') ? '30' : $conf_plus['pk_ts3_cache']; //default cachetime = 30 seconds
		if ($_HMODE) {$cachetime = '90';} //fix to 90 seconds in hosting mode
				
		$htmlout = $pdc->get('portal.modul.ts3.outputdata', false, true);
		if ((!$htmlout) or $cachetime == '0'){
			include_once($eqdkp_root_path . 'portal/teamspeak3/TeamSpeakViewer/Ts3Viewer.php');
			
			$ts3v = new Ts3Viewer($conf_plus);

			if ($ts3v->connect()) {
				$ts3v->query();
				$ts3v->disconnect();
			}
			
			$htmlout = $ts3v->gethtml();
			
			unset($ts3v);

			if ($cachetime >= '1') {$pdc->put('portal.modul.ts3.outputdata', $htmlout, $cachetime, false, true);}
		}
		
  		$out .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
  		$out .= '<tr class=row1><td>';
  		$out .= utf8_decode($htmlout);
  		$out .= '</td></tr>';
  		$out .= '</table>';
		
  		return $out;
  }
}
?>
