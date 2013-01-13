<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       01.07.2009
 * Date:        $Date: 2010-06-30 13:16:44 +0200 (Wed, 30 Jun 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 8263 $
 *
 * $Id: index.php 8263 2010-06-30 11:16:44Z corgan $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

class Manage_Game
{
  var $gamename = 'STO';
  var $maxlevel = 80;
  var $version  = '1.0';

  function do_it($install,$lang)
  {
    global $db;
   	$aq =  array();
    
   	if($lang == 'de')
    {
      $classes = array(
      	array('Unknown', '',0,50,0),  
      	array('Ingenieur', '',0,50,1),  
      	array('Wissenschaftler', '',0,50,2),  
      	array('Taktiker', '',0,50,3),  
      );

      $races = array(
			'Unknown',
		  	'Menschen',
			'Vulkanier',
			'Bajoraner',
			'Bolian',
			'Benzite ',			
			'Betazoiden',
			'Andorian',
			'Saurian',	
			'Trill',
			'Tellariten',
			'Ferengi',
			'Pakled',
			//Klingon Empire
			'Orioner',
			'Gorn',
			'Nausicaaner',
			'Lethean',
			//Shared
			'Klingonen',
			'Liberated Borg',
			'Alien',        
      );

      $factions = array(
        'F�deration',
        'Klingonisches Reich'
      );

      }
    else
    {
      $classes = array(
      	array('Unknown', '',0,80,0),  
        array('Engineering', '',0,80,1),  
      	array('Science', '',0,80,2),  
      	array('Rune Tactical', '',0,80,3),  
      );

      $races = array(
    	//Federation
			'Unknown',
		  'Human',
			'Vulcan',
			'Bajoran',
			'Bolian',
			'Benzite ',			
			'Betazoid',
			'Andorian',
			'Saurian',	
			'Trill',
			'Tellarite',
			'Ferengi',
			'Pakled',
			//Klingon Empire
			'Orion',
			'Gorn',
			'Nausicaan',
			'Lethean',
			//Shared
			'Klingon',
			'Liberated Borg',
			'Alien'
      );

      $factions = array(
        'Federation',
        'Klingon Empire'
      );
    }
    //Itemstats    
    
    //lets do some tweak on the templates dependent on the game
    $aq =  array();
    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='bc_header3.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='/logo/logo_plus.gif' WHERE logo_path='/logo/logo_wow.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='logo_wow.gif' ;" );
	    
    //Do this SQL Query NOT if the Eqdkp is installed -> only @ the first install
    if($install)
    {
    }

    //Itemstats
    array_push($aq, "UPDATE __plus_config SET config_value = '0' WHERE config_name = 'pk_itemstats' ;");
    array_push($aq, "UPDATE __plus_config SET config_value = '0' WHERE config_name = 'pk_is_autosearch' ;");

    // this is the fix stuff.. instert the new information
    // into the database. moved it to a new class, its easier to
    // handle    
    $gmanager = new GameManagerPlus();
    $game_config = array(
      'classes'       => $classes,
      'races'         => $races,
      'factions'      => $factions,
      'class_colors'  => false,
      'max_level'     => $this->maxlevel,
      'add_sql'       => $aq,
      'version'       => $this->version,
    );
    
    $gmanager->ChangeGame($this->gamename, $game_config, $lang);
   }
}

?>