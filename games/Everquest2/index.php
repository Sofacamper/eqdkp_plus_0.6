<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-05-11 12:29:10 +0200 (Fri, 11 May 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 11785 $
 * 
 * $Id: index.php 11785 2012-05-11 10:29:10Z wallenium $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

class Manage_Game
{
  var $gamename = 'Everquest2';
  var $maxlevel = 92;
  var $version  = '1.6';

  function do_it($install,$lang)
  {
    global $db;
    if($lang == 'de')
	{
	  $classes = array(
        array('Beliebig','Beliebig',0,0),
        array('Assassine', 'Kette',1,92),
        array('Berserker', 'Platte',1,92),
        array('Brigant', 'Kette',1,92),
        array('Raufbold', 'Leder',1,92),
        array('Erzwinger', 'Stoff',1,92),
        array('Elementalist', 'Stoff',1,92),
        array('Sch�nder', 'Kette',1,92),
        array('Klages�nger', 'Kette',1,92),
        array('Furie', 'Leder',1,92),
        array('W�chter', 'Platte',1,92),
        array('Thaumaturgist', 'Stoff',1,92),
        array('Inquisitor', 'Platte',1,92),
        array('M�nch', 'Leder',1,92),
        array('Mystiker', 'Kette',1,92),
        array('Nekromant', 'Stoff',1,92),
        array('Paladin', 'Platte',1,92),
        array('Waldl�ufer', 'Kette',1,92),
        array('Schattenritter', 'Platte',1,92),
        array('S�belrassler', 'Kette',1,92),
        array('Templer', 'Platte',1,92),
        array('Troubadour', 'Kette',1,92),
        array('W�rter', 'Leder',1,92),
        array('Hexenmeister', 'Stoff',1,92),
        array('Zauberer', 'Stoff',1,92),
        array('Bestienf�rst', 'Leder',1,92)
      );

      $races = array(
        'Unbekannt',
        'Sarnak',
        'Gnom',
        'Mensch',
        'Barbar',
        'Zwerg',
        'Hochelf',
        'Dunkelelf',
        'Waldelf',
        'Halbelf',
        'Kerraner',
        'Troll',
        'Oger',
        'Froschlok',
        'Erudit',
        'Iksar',
        'Rattonga',
        'Halbling',
        'Arasai',
        'Fee',
        'Vampir'
      );

      $factions = array(
        'Gut',
        'B�se',
        'Neutral'
      );

      // The Class colors
      $classColorsCSS = array(
        'Beliebig'        => '#E1E1E1',
        'Assassine'       => '#E1E100',
        'Berserker'       => '#E10000',
        'Brigant'         => '#E1E100',
        'Raufbold'        => '#E10000',
        'Erzwinger'       => '#0000E1',
        'Elementalist'    => '#0000E1',
        'Sch�nder'        => '#00E100',
        'Klages�nger'     => '#E1E100',
        'Furie'           => '#00E100',
        'W�chter'         => '#E10000',
        'Thaumaturgist'   => '#0000E1',
        'Inquisitor'      => '#00E100',
        'M�nch'           => '#E10000',
        'Mystiker'        => '#00E100',
        'Nekromant'       => '#0000E1',
        'Paladin'         => '#E10000',
        'Waldl�ufer'      => '#E1E100',
        'Schattenritter'  => '#E10000',
        'S�belrassler'    => '#E1E100',
        'Templer'         => '#00E100',
        'Troubadour'      => '#E1E100',
        'W�rter'          => '#00E100',
        'Hexenmeister'    => '#0000E1',
        'Zauberer'        => '#0000E1',
        'Bestienf�rst'    => '#E1E100',
      );
    } else {
	  $classes = array(
        array('Unknown','Unknown',0,0),
        array('Assassin', 'Medium',1,92),
        array('Berserker', 'Heavy',1,92),
        array('Brigand', 'Medium',1,92),
        array('Bruiser', 'Light',1,92),
        array('Coercer', 'VeryLight',1,92),
        array('Conjuror', 'VeryLight',1,92),
        array('Defiler', 'Medium',1,92),
        array('Dirge', 'Medium',1,92),
        array('Fury', 'Light',1,92),
        array('Guardian', 'Heavy',1,92),
        array('Illusionist', 'VeryLight',1,92),
        array('Inquisitor', 'Heavy',1,92),
        array('Monk', 'Light',1,92),
        array('Mystic', 'Medium',1,92),
        array('Necromancer', 'VeryLight',1,92),
        array('Paladin', 'Heavy',1,92),
        array('Ranger', 'Medium',1,92),
        array('Shadowknight', 'Heavy',1,92),
        array('Swashbuckler', 'Medium',1,92),
        array('Templar', 'Heavy',1,92),
        array('Troubador', 'Medium',1,92),
        array('Warden', 'Light',1,92),
        array('Warlock', 'VeryLight',1,92),
        array('Wizard', 'VeryLight',1,92),
        array('Beastlord', 'Medium',1,92)
      );

      $races = array(
        'Unknown',
        'Sarnak',
        'Gnome',
        'Human',
        'Barbarian',
        'Dwarf',
        'High Elf',
        'Dark Elf',
        'Wood Elf',
        'Half Elf',
        'Kerra',
        'Troll',
        'Ogre',
        'Froglok',
        'Erudite',
        'Iksar',
        'Ratonga',
        'Halfling',
        'Arasai',
        'Fae',
        'Freeblood'
      );

      $factions = array(
        'Good',
        'Evil',
        'Neutral'
      );

      // The Class colors
      $classColorsCSS = array(
        'Unknown'       => '#E1E1E1',
        'Assassin'      => '#E1E100',
        'Berserker'     => '#E10000',
        'Brigand'       => '#E1E100',
        'Bruiser'       => '#E10000',
        'Coercer'       => '#0000E1',
        'Conjuror'      => '#0000E1',
        'Defiler'       => '#00E100',
        'Dirge'         => '#E1E100',
        'Fury'          => '#00E100',
        'Guardian'      => '#E10000',
        'Illusionist'   => '#0000E1',
        'Inquisitor'    => '#00E100',
        'Monk'          => '#E10000',
        'Mystic'        => '#00E100',
        'Necromancer'   => '#0000E1',
        'Paladin'       => '#E10000',
        'Ranger'        => '#E1E100',
        'Shadowknight'  => '#E10000',
        'Swashbuckler'  => '#E1E100',
        'Templar'       => '#00E100',
        'Troubador'     => '#E1E100',
        'Warden'        => '#00E100',
        'Warlock'       => '#0000E1',
        'Wizard'        => '#0000E1',
        'Beastlord'     => '#E1E100',
      );
	}
    
    //lets do some tweak on the templates dependent on the game
    $aq =  array();
    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='bc_header3.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='/logo/logo_plus.gif' WHERE logo_path='/logo/logo_wow.gif' ;");
    array_push($aq, "UPDATE __style_config SET logo_path='logo_plus.gif' WHERE logo_path='logo_wow.gif' ;" );

    //Do this SQL Query NOT if the Eqdkp is installed -> only @ the first install
    if($install)
    {
	   	array_push($aq, "UPDATE __config SET config_value = 32 WHERE config_name='default_style' ;");
    	array_push($aq, "UPDATE __users SET user_style = '32' ;");
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
      'class_colors'  => $classColorsCSS,
      'max_level'     => $this->maxlevel,
      'add_sql'       => $aq,
      'version'       => $this->version,
    );
    
    $gmanager->ChangeGame($this->gamename, $game_config, $lang);
   }
}

?>
