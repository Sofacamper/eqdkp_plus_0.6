<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-03-11 11:33:48 +0100 (Wed, 11 Mar 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: ghoschdi $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4193 $
 * 
 * $Id: module.php 4193 2009-03-11 10:33:48Z ghoschdi $
 */


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['teamspeak'] = array(
			'name'			    => 'Teamspeak Module',
			'path'			    => 'teamspeak',
			'version'		    => '1.2.0',
			'author'        => 'Corgan and Team',
			'contact'		    => 'http://www.eqdkp-plus.com',
			'description'   => 'Teamspeak Server Information',
			'positions'     => array('left1', 'left2', 'right'),
      'install'       => array(
                            'autoenable'        => '0',
                            'defaultposition'   => 'right',
                            'defaultnumber'     => '5',
                          ),
    );

$portal_settings['teamspeak'] = array(
  'pk_ts_title'     => array(
        'name'      => 'pk_ts_title',
        'language'  => 'pk_set_ts_title',
        'property'  => 'text',
        'size'      => '40',
      ),
  'pk_ts_serverAddress'     => array(
        'name'      => 'pk_ts_serverAddress',
        'language'  => 'pk_set_ts_serverAddress',
        'property'  => 'text',
        'size'      => '40',
      ),
  'pk_ts_serverQueryPort'     => array(
        'name'      => 'pk_ts_serverQueryPort',
        'language'  => 'pk_set_ts_serverQueryPort',
        'property'  => 'text',
        'size'      => '6',
      ),
  'pk_ts_serverUDPPort'     => array(
        'name'      => 'pk_ts_serverUDPPort',
        'language'  => 'pk_set_ts_serverUDPPort',
        'property'  => 'text',
        'size'      => '6',
      ),
  'pk_ts_serverPasswort'     => array(
        'name'      => 'pk_ts_serverPasswort',
        'language'  => 'pk_set_ts_serverPasswort',
        'property'  => 'text',
        'size'      => '20',
      ),

  'pk_ts_channelflags'     => array(
          'name'      => 'pk_ts_channelflags',
          'language'  => 'pk_set_ts_channelflags',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_userstatus'     => array(
          'name'      => 'pk_ts_userstatus',
          'language'  => 'pk_set_ts_userstatus',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_showchannel'     => array(
          'name'      => 'pk_ts_showchannel',
          'language'  => 'pk_set_ts_showchannel',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_showEmptychannel'     => array(
          'name'      => 'pk_ts_showEmptychannel',
          'language'  => 'pk_set_ts_showEmptychannel',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_overlib_mouseover'     => array(
          'name'      => 'pk_ts_overlib_mouseover',
          'language'  => 'pk_set_ts_overlib_mouseover',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_joinable'     => array(
          'name'      => 'pk_ts_joinable',
          'language'  => 'pk_set_ts_joinable',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_joinableMember'     => array(
          'name'      => 'pk_ts_joinableMember',
          'language'  => 'pk_set_ts_joinableMember',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_shortmemnames'     => array(
          'name'      => 'pk_ts_shortmemnames',
          'language'  => 'pk_ts_shortmemnames',
          'property'  => 'checkbox',
          'size'      => false,
          'options'   => false,
        ),
  'pk_ts_memnamelength'     => array(
        'name'      => 'pk_ts_memnamelength',
        'language'  => 'pk_ts_memnamelength',
        'property'  => 'text',
        'size'      => '10',
      ),
   'pk_ts_channel_name'		=> array(
      	'name'		=> 'pk_ts_channel_name',
      	'language'	=> 'pk_ts_channel_name',
      	'property'	=> 'text',
      	'codeinput' => true,
      	'size'		=> '40',
      	'help'		=> 'pk_ts_channel_name_help',
      ),
);

if(!function_exists(teamspeak_module)){
  function teamspeak_module(){
  		global $tpl, $eqdkp,$eqdkp_root_path,$conf_plus ,$user, $plang;
  
  		include_once($eqdkp_root_path . 'portal/teamspeak/TeamSpeakViewer/TS_Viewer.class.php');
  
  
  		$tss2info 	= new tss2info;
  		$tss2info->sitetitle = $conf_plus['pk_ts_title'];
  		$tss2info->serverAddress = $conf_plus['pk_ts_serverAddress'];
  		$tss2info->serverQueryPort = $conf_plus['pk_ts_serverQueryPort'];
  		$tss2info->serverUDPPort = $conf_plus['pk_ts_serverUDPPort'];
  		$tss2info->serverPasswort = $conf_plus['pk_ts_serverPasswort'];
  		$tss2info->rpath = $eqdkp_root_path;
  
  		$tss2info->TS_channelflags_ausgabe   = (isset($conf_plus['pk_ts_channelflags'])) ? $conf_plus['pk_ts_channelflags'] : 0 ;
  		$tss2info->TS_userstatus_ausgabe     = (isset($conf_plus['pk_ts_userstatus'])) ? $conf_plus['pk_ts_userstatus'] : 0 ;
  		$tss2info->TS_channel_anzeigen       = (isset($conf_plus['pk_ts_showchannel'])) ? $conf_plus['pk_ts_showchannel'] : 1 ;
  		$tss2info->TS_leerchannel_anzeigen   = (isset($conf_plus['pk_ts_showEmptychannel'])) ? $conf_plus['pk_ts_showEmptychannel'] : 0 ;
  		$tss2info->TS_overlib_mouseover      = (isset($conf_plus['pk_ts_overlib_mouseover'])) ? $conf_plus['pk_ts_overlib_mouseover'] : 0 ;
  		
  		if ( $user->data['user_id'] != ANONYMOUS )
  		{
  			$tss2info->alternativer_nick     = $user->data['username'];
  			$tss2info->joinable				 = (isset($conf_plus['pk_ts_joinable'])) ? $conf_plus['pk_ts_joinable'] : 0 ;
  		}else
  		{
  			if (!$conf_plus['pk_ts_joinableMember'] ==1)
  			{
  				$tss2info->joinable			= (isset($conf_plus['pk_ts_joinable'])) ? $conf_plus['pk_ts_joinable'] : 0 ;
  			}
  		}
  
  			$htmlout 	= @$tss2info->getInfo();	
  		
  
  		if (!isset($htmlout)) 
  		{
  			$htmlout = $user->lang['voice_error'].
  			"<br>".$conf_plus['pk_ts_serverAddress'].":".$conf_plus['pk_ts_serverQueryPort'];
  		}
  		$out .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
  		$out .= '<tr class=row1><td>';
  		$out .= $htmlout ;
  		$out .= '</td></tr>';
  		$out .= '</table>';

  		return $out;
  }
}
?>
