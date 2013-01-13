<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-07-11 16:25:04 +0200 (Sun, 11 Jul 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2010 EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 8345 $
 * 
 * $Id: module.php 8345 2010-07-11 14:25:04Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['mycontent'] = array(
			'name'           => 'Custom Content Module',
			'path'           => 'mycontent',
			'version'        => '1.1.0',
			'author'         => 'WalleniuM',
			'contact'        => 'http://www.eqdkp-plus.com',
			'description'    => 'Output a custom content',
			'positions'      => array('left1', 'left2', 'right', 'middle'),
			'signedin'       => '0',
			'install'        => array(
									'autoenable'        => '0',
									'defaultposition'   => 'right',
									'defaultnumber'     => '7',
								),
    );

$portal_settings['mycontent'] = array(
  'pk_mycontent_useroutput'     => array(
        'name'      => 'pk_mycontent_useroutput',
        'language'  => 'pk_mycontent_useroutput',
        'property'  => 'textarea',
        'size'      => '30',
        'cols'      => '4',
        'help'      => '',
        'codeinput' => true,
      ),
  'pk_mycontent_headtext'     => array(
        'name'      => 'pk_mycontent_headtext',
        'language'  => 'pk_mycontent_headtext',
        'property'  => 'text',
        'size'      => '30',
        'help'      => '',
      )
);

if(!function_exists(mycontent_module)){
	function mycontent_module(){
		global $eqdkp , $user , $tpl, $db, $plang, $conf_plus;
		
		// Set the header
		if($conf_plus['pk_mycontent_headtext']){
			$out = "<script>document.getElementById('txtmycontent').innerHTML = '".addslashes($conf_plus['pk_mycontent_headtext'])."'</script>";
		}
		$out .= html_entity_decode(htmlspecialchars_decode($conf_plus['pk_mycontent_useroutput']));
		return $out;
	}
}
?>