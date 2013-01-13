<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-07-29 13:09:49 +0200 (Di, 29 Jul 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2453 $
 * 
 * $Id: raidplan_plugin_class.php 2453 2008-07-29 11:09:49Z wallenium $
 */
 
if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// The Class Name Convertion array
$classconversion = array(
    'eq' => array(
        'Bruiser'        => 'Bruiser',
        'Монах'           => 'Monk',
        'Страж'         => 'Guardian',
        'Рыцарь тьмы'  => 'ShadowKnight',
        'Erzwinger'       => 'Conjuror',
        'Волшебник'        => 'Wizard',
        'Колдун'    => 'Warlock',
        'Некромант'       => 'Necromancer',
        'Conjuror'    => 'Conjuror',
        'Templar'         => 'Templar',
       	'Warden'          => 'Warden',
        'Defiler'        => 'Defiler',
        'Хищник'        => 'Predator',
        'Swashbuckler'      => 'Swashbuckler',
        'Brigand'         => 'Brigand',
        'Dirge'     => 'Dirge',
        'Трубадур'      => 'Troubador',
        'Ranger'      => 'Ranger',
        'Воин'         => 'Warrior'
      ),
    'wow' => array(
        'Друид'          => 'Druid',
        'Паладин'         => 'Paladin',
        'Колдун'    => 'Warlock',
        'Охотник'           => 'Hunter',
        'Воин'         => 'Warrior',
        'Маг'          => 'Mage',
        'Жрец'        => 'Priest',
        'Разбойник'         => 'Rogue',
        'Шаман'        => 'Shaman'
      ),
    /*'lotro' => array(
        'Barde'           => 'Minstrel',
        'Hauptmann'       => 'Captain',
        'Jдger'           => 'Hunter',
        'Kundiger'        => 'Lore-master',
        'Schurke'         => 'Burglar',
        'Wдchter'         => 'Guardian',
        'Waffenmeister'   => 'Champion'
      ),
    'aoc' => array(
        'Assassin'        => 'Assassin',
        'Barbar'          => 'Barbarian',
        'Ranger'      => 'Ranger',
        'Conqueror'        => 'Conqueror',
        'Dark Templar'         => 'Dark Templar',
        'Guardian' => 'Guardian',
        'Demonologist'      => 'Demonologist',
        'Herald of Xotli'=> 'Herald of Xotli',
        'Nekromant'       => 'Necromancer',
        'Priest of Mitra'   => 'Priest of Mitra',
        'Bear Shaman'   => 'Bear Shaman',
        'Tempest of Set'=> 'Tempest of Set',
      )*/
    );

// The Racename convertion Array
$raceconversion = array(
    /*'eq' => array(
        'Barbar'          => 'Barbarian',
        'Hochelf'         => 'High Elf',
        'Dunkelelf'       => 'Dark Elf',
        'Waldelf'         => 'Wood Elf',
        'Halbelf'         => 'Half Elf',
        'Kerraner'        => 'Kerra',
        'Oger'            => 'Ogre',
        'Froschlog'       => 'Frog',
        'Erudit'          => 'Erudite',
        'Halbling'        => 'Halfling',
        'Fee'             => 'Fay'
      ),*/
    'wow' => array(
        'Неизвестно'       => 'Unknown',
  			'Гном'            => 'Gnome',
        'Человек'          => 'Human',
        'Дварф'           => 'Dwarf',
        'Ночной Эльф'        => 'Night Elf',
        'Нежить'         => 'Undead',
        'Орк'             => 'Orc',
        'Таурен'           => 'Tauren'
      ),
    /*'aoc' => array(
        'Aquilonier'      => 'Aquilonian',
        'Cimmerier'       => 'Cimmerian',
        'Stygier'         => 'Stygian'
      )*/
    );

?>
