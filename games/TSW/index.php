<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       24.01.2011
 * Date:        $Date: 2012-08-30 01:46:59 +0200 (Thu, 30 Aug 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 12001 $
 *
 * $Id: index.php 12001 2012-08-29 23:46:59Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class Manage_Game
{
	var $gamename	= 'TSW';
	var $maxlevel	= 0;
	var $version	= '1.0';

	function do_it($install,$lang){
		global $db;
		$aq =  array();

		if($lang == 'de'){
			$classes = array(
				array('Unknown', '',0,0,0),
				array('Magier', '',0,0,1),
				array('Schütze', '',0,0,2),
				array('Nahkämpfer', '',0,0,3),
			);

			$races = array(
				'Unknown',
				'Mensch'
			);

			$factions = array(
				'Illuminaten',
				'Drachen',
				'Templer'
			);
		}else{
			$classes = array(
				array('Unknown', '',0,0,0),
				array('Caster', '',0,0,1),
				array('Gunner', '',0,0,2),
				array('Melee', '',0,0,3),
			);
			$races = array(
				'Unknown',
				'Human'
			);

			$factions = array(
				'Illuminati',
				'Dragons',
				'Templars'
			);
		}

		//lets do some tweak on the templates dependent on the game
		$aq =  array();
			array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='bc_header3.gif' ;");
			array_push($aq, "UPDATE __style_config SET logo_path='/logo/logo_plus.gif' WHERE logo_path='/logo/logo_wow.gif' ;");
			array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='logo_wow.gif' ;" );

		//Do this SQL Query NOT if the Eqdkp is installed -> only @ the first install
		if($install){
		}

		$classColorsCSS = array(
          'Caster'      	=> '#ff0000',
          'Gunner'    		=> '#800080',
          'Melee'     		=> '#ffff00',
        );

		//Itemstats
		array_push($aq, "UPDATE __plus_config SET config_value = '0' WHERE config_name = 'pk_itemstats' ;");
		array_push($aq, "UPDATE __plus_config SET config_value = '0' WHERE config_name = 'pk_is_autosearch' ;");

		// this is the fix stuff.. instert the new information
		// into the database. moved it to a new class, its easier to
		// handle
		$gmanager = new GameManagerPlus();
		$game_config = array(
			'classes'		=> $classes,
			'races'			=> $races,
			'factions'		=> $factions,
			'class_colors'  => $classColorsCSS,
			'max_level'		=> $this->maxlevel,
			'add_sql'		=> $aq,
			'version'		=> $this->version,
		);
		$gmanager->ChangeGame($this->gamename, $game_config, $lang);
	}
}
?>