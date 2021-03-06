<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-09-25 18:08:29 +0200 (Sun, 25 Sep 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 11320 $
 * 
 * $Id: index.php 11320 2011-09-25 16:08:29Z wallenium $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

class Manage_Game
{
  var $gamename = 'LOTRO';
  var $maxlevel = 75;
  var $version  = '1.3';

  function do_it($install,$lang)
  {
    global $db;
    if($lang == 'de')
    {
      $classes = array(
        array('Unknown', 'leichte R�stung',0,75),
        array('Barde', 'mittlere R�stung',0,75),
        array('Hauptmann', 'schwere R�stung',0,75),
        array('J�ger', 'mittlere R�stung',0,75),
        array('Kundiger', 'leichte R�stung',0,75),
        array('Schurke', 'mittlere R�stung',0,75),
        array('W�chter', 'schwere R�stung',0,75),
        array('Waffenmeister', 'schwere R�stung',0,75),
        array('Runenbewahrer', 'leichte R�stung',0,75),
        array('H�ter', 'mittlere R�stung',0,75)
      );

      $races = array(
        'Unknown',
        'Mensch',
        'Hobbit',
        'Elb',
        'Zwerg'
      );

      $factions = array(
        'Normal',
        'MonsterPlay'
      );
    }
    else
    {
      $classes = array(
        array('Unknown', 'Light Armour',0,75),
        array('Minstrel', 'Medium Armour',0,75),
        array('Captain', 'Heavy Armour',0,75),
        array('Hunter', 'Medium Armour',0,75),
        array('Lore-master', 'Light Armour',0,75),
        array('Burglar', 'Medium Armour',0,75),
        array('Guardian', 'Heavy Armour',0,75),
        array('Champion', 'Heavy Armour',0,75),
        array('Runekeeper', 'Light Armour',0,75),
        array('Warden', 'Medium Armour',0,75)
      );

      $races = array(
        'Unknown',
        'Man',
        'Hobbit',
        'Elf',
        'Dwarf'
      );

      $factions = array(
        'Normal',
        'MonsterPlay'
      );
    }
    
    // The Class colors
    $classColorsCSS = array(
          'Minstrel'      => '#FFCC33',
          'Captain'    		=> '#0033CC',
          'Hunter'     		=> '#006600',
          'Lore-master'   => '#00CCFF',
          'Burglar'    		=> '#444444',
          'Guardian'      => '#990000',
          'Champion'      => '#CC3300',
          //'Runekeeper'    => '#1a3caa',
          //'Warden'        => '#FFF468',
        );
    
    //lets do some tweak on the templates dependent on the game
    $aq =  array();

    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='bc_header3.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='/logo/logo_plus.gif' WHERE logo_path='/logo/logo_wow.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='logo_wow.gif' ;" );
    array_push($aq, "UPDATE __style_config SET logo_path='lotro_header_01.gif' WHERE style_id=31 or style_id=32  ;" );

    //Do this SQL Query NOT if the Eqdkp is installed -> only @ the first install
    if($install)
    {
    }

    //Itemstats
    array_push($aq, "UPDATE __plus_config SET config_value = '1' WHERE config_name = 'pk_itemstats' ;");
    array_push($aq, "UPDATE __plus_config SET config_value = '0' WHERE config_name = 'pk_is_autosearch' ;");       
    array_push($aq, "UPDATE __plus_config SET config_value = 'allakhazam' WHERE config_name = 'pk_is_webdb' ;");

    // this is the fix stuff.. instert the new information
    // into the database. moved it to a new class, its easier to
    // handle
    $gmanager = new GameManagerPlus();
    $game_config = array(
      'classes'       => $classes,
      'races'         => $races,
      'factions'      => $factions,
      'class_colors'  => $classColorsCSS,
      'max_level'     => $this->maxlevel,
      'add_sql'       => $aq,
      'version'       => $this->version,
    );
    
    $gmanager->ChangeGame($this->gamename, $game_config, $lang);
   }
}

?>
