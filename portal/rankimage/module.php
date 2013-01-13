<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-03-24 15:07:09 +0100 (Tue, 24 Mar 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4377 $
 *
 * $Id: module.php 4377 2009-03-24 14:07:09Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['rankimage'] = array(
	'name'			    => 'RankImage Module',
	'path'			    => 'rankimage',
	'version'		    => '1.0.2',
	'author'        => 'Corgan',
	'contact'		    => 'http://www.eqdkp-plus.com',
	'description'   => 'Rank Images',
	'positions'     => array('left1', 'left2', 'right'),
	'install'       => array(
								'autoenable'        => '0',
								'defaultposition'   => 'right',
								'defaultnumber'     => '4', ),
    );

$portal_settings['rankimage'] = array(
      'pk_ts_ranking_link'     => array(
        'name'      => 'pk_ts_ranking_link',
        'language'  => 'pk_ts_ranking_link',
        'property'  => 'textarea',
        'size'      => '30',
      ),
      'uc_servername'     => array(
        'name'      => 'pk_ts_ranking_url',
        'language'  => 'pk_ts_ranking_url',
        'property'  => 'textarea',
        'size'      => '30',
        'options'   => false,
      ),
      'pk_ts_bosskillers' => array(
        'name'      => 'pk_ts_bosskillers',
        'language'  => 'pk_ts_bosskillers',
        'property'  => 'checkbox',
        'options'   => false,
      ),
);

if(!function_exists(rankimage_module))
{
  function rankimage_module(){
  	global $tpl, $eqdkp,$eqdkp_root_path,$conf_plus ,$user;

  		if (isset($conf_plus['pk_ts_ranking_url']) && isset($conf_plus['pk_ts_ranking_link']))
  		{
  			$out .= '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="noborder">';
  			$out .= '<tr ><td align=center>';

  			if($conf_plus['pk_ts_bosskillers']==true)
  			{
  				$pyyri_tmp=$conf_plus['pk_ts_ranking_url'];
  				$pyyri_tmp=str_replace('&lt;', '<', $pyyri_tmp);
  				$pyyri_tmp=str_replace('&gt;', '>', $pyyri_tmp);
  				$pyyri_tmp=str_replace('&quot;', '"', $pyyri_tmp);
  				$out .= $pyyri_tmp;
  			} else
  			{
	  			if(strlen($conf_plus['pk_ts_ranking_link'] > 0) || $conf_plus['pk_ts_ranking_link'])
	  			{
	  				$out .=  '<a href="'.$conf_plus['pk_ts_ranking_link'].'" target=_blank> <img src="'.$conf_plus['pk_ts_ranking_url'].'"> </a>';
	  			}else
	  			{
	  				$out .=  '<img src="'.$conf_plus['pk_ts_ranking_url'].'">';
	  			}
	  		}
	    		$out .= '</td></tr>';
	    		$out .= '</table>';
	    		return $out;

	    }
  }
}
?>