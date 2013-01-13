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
	var $gamename	= 'GW2';
	var $maxlevel	= 80;
	var $version	= '1.0';

	function do_it($install,$lang){
		global $db;
		$aq =  array();

		if($lang == 'de'){
			$classes = array(
				array('Unknown', '',0,80,0),
				array('Mesmer', '',0,80,1),
				array('Ingenieur', '',0,80,2),
				array('Dieb', '',0,80,3),
				array('Wächter', '',0,80,4),
				array('Nekromant', '',0,80,5),
				array('Waldläufer', '',0,80,6),
				array('Elementarmagier', '',0,80,7),
				array('Krieger', '',0,80,8),
			);

			$races = array(
				'Unknown',
				'Sylvari',
				'Norn',
				'Charr',
				'Asura',
				'Menschen',
			);

			$factions = array(
			'Normal',
			'MonsterPlay'
			);
		}else{
			$classes = array(
				array('Unknown', '',0,80,0),
				array('Mesmer', '',0,80,1),
				array('Engineer', '',0,80,2),
				array('Thief', '',0,80,3),
				array('Guardian', '',0,80,4),
				array('Necromancer', '',0,80,5),
				array('Ranger', '',0,80,6),
				array('Elementalist', '',0,80,7),
				array('Warrior', '',0,80,8),
			);
			$races = array(
				'Unknown',
				'Sylvari',
				'Norn',
				'Charr',
				'Asura',
				'Human',
			);
			
			$factions = array(
			'Normal',
			'MonsterPlay'
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
          'Mesmer'      	=> '#E18FF1',
          'Engineer'    	=> '#C97840',
          'Thief'     		=> '#69444B',
          'Guardian'    	=> '#6CB6CF',
		  'Necromancer'     => '#5B8C79',
          'Ranger'    		=> '#91BC51',
          'Elementalist'    => '#E68C84',
          'Warrior'    		=> '#DFBA74',
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