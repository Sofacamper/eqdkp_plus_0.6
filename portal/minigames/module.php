<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-10-27 14:44:54 +0100 (Mo, 27 Okt 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 2896 $
 * 
 * $Id: module.php 2896 2008-10-27 13:44:54Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['minigames'] = array(
			'name'           => 'Mini Games Module',
			'path'           => 'minigames',
			'version'        => '1.0.0',
			'author'         => 'Corgan',
			'contact'        => 'http://www.eqdkp-plus.com',
			'description'    => 'Show mini-games ranking and activity from your guild',
			'positions'      => array('left1', 'left2', 'right'),
      		'signedin'       => '0',
      		'install'        => array(
			                            'autoenable'        => '0',
			                            'defaultposition'   => 'right',
			                            'defaultnumber'     => '14',
			                            ),
    );

$portal_settings['minigames'] = array(
    'pk_show_topPlayer'     => array(
        'name'      => 'pk_show_topPlayer',
        'language'  => 'pk_show_topPlayer',
        'property'  => 'checkbox',
        'size'      => '30',
        'help'      => '',
      ),
    'pk_show_activity'     => array(
        'name'      => 'pk_show_activity',
        'language'  => 'pk_show_activity',
        'property'  => 'checkbox',
        'size'      => '30',
        'help'      => '',
      ),
    'pk_show_slider'     => array(
        'name'      => 'pk_show_slider',
        'language'  => 'pk_show_slider',
        'property'  => 'checkbox',
        'size'      => '30',
        'help'      => '',
      ),
);

if(!function_exists(minigames_module))
{
  function minigames_module()
  {
  	global $eqdkp , $user , $tpl, $db, $plang, $conf_plus, $_HMODE;    
	$url=base64_encode("http://".$eqdkp->config['server_name'].$eqdkp->config['server_path']) ;

	$userLang = getUserLanguage();	
	switch ($userLang) {		
		case "german":
	        $suffix = "de" ;
	        break;
	    case "english":
	         $suffix = "en" ;
	        break;
	    case "english-us":
	         $suffix = "en" ;
	        break;
	    default:    
	    	$suffix = "de" ;
	}
		
	$topPlayer = ($_HMODE) ? "http://$suffix.allvatar.popmog.com/cobrands/25/cobrand_partner_pages/243?partner_uid=$url" : "http://$suffix.eqdkp.popmog.com/cobrands/25/cobrand_partner_pages/243?partner_uid=$url";
	$activ = ($_HMODE) ? "http://$suffix.allvatar.popmog.com/cobrands/25/cobrand_partner_pages/245?partner_uid=$url" : "http://$suffix.eqdkp.popmog.com/cobrands/25/cobrand_partner_pages/245?partner_uid=$url";
	$slider = ($_HMODE) ? "http://$suffix.allvatar.popmog.com/cobrands/25/cobrand_partner_pages/265?partner_uid=$url" : "http://$suffix.eqdkp.popmog.com/cobrands/25/cobrand_partner_pages/265?partner_uid=$url";
	#$achivements = ($_HMODE) ? "http://$suffix.allvatar.popmog.com/cobrands/25/cobrand_partner_pages/269?partner_uid=$url" : "http://$suffix.eqdkp.popmog.com/cobrands/25/cobrand_partner_pages/269?partner_uid=$url";
	
	$out .= ($conf_plus['pk_show_topPlayer']) ?  '<b>'.$user->lang['pm_player'] . '</b><br><iframe src="'.$topPlayer.'" frameborder="0" width="180" height="470" allowtransparency="true" SCROLLING=NO>  </iframe> <br>' : '';
	$out .= ($conf_plus['pk_show_activity']) ? '<b>'.$user->lang['pm_activity'] . '</b><br><iframe src="'.$activ.'" frameborder="0" width="180" height="620" allowtransparency="true" SCROLLING=NO>  </iframe> <br>' : '' ;	
	$out .=  ($conf_plus['pk_show_slider']) ? '<iframe src="'.$slider.'" frameborder="0" width="300" height="195" allowtransparency="true" SCROLLING=NO>  </iframe> <br>' : '';	
	#$out .= '<iframe src="'.$achivements.'" frameborder="0" width="180" height="620" allowtransparency="true" SCROLLING=NO>  </iframe> <br>';
	if(strlen($out)<1) {$out='<iframe src="'.$slider.'" frameborder="0" width="300" height="195" allowtransparency="true" SCROLLING=NO>  </iframe> <br>';}
	
    return $out;
  }
}
?>
