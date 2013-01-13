<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: lang_convert.php 2963 2008-11-03 12:24:11Z wallenium $
 *
 * Chinese Simplified translated by 雪夜之狼@Feathermoon（羽月）,CN3
 * Email:xionglingfeng@Gmail.com
 */
 
if ( !defined('EQDKP_INC') ){
header('HTTP/1.0 404 Not Found');exit;
}

// The Class Name Convertion array
$classconversion = array(
/*'eq' => array(
'Raufbold'=> 'Bruiser',
'M鰊ch' => 'Monk',
'W鋍hter' => 'Guardian',
'Schattenritter'=> 'ShadowKnight',
'Erzwinger' => 'Conjuror',
'Zauberer'=> 'Wizard',
'Hexenmeister'=> 'Warlock',
'Nekromant' => 'Necromancer',
'Elementalist'=> 'Conjuror',
'Templer' => 'Templar',
'W鋜ter'=> 'Warden',
'Sch鋘der'=> 'Defiler',
'Mystiker'=> 'Predator',
'Abenteurer'=> 'Swashbuckler',
'Brigand' => 'Brigand',
'Klages鋘ger' => 'Dirge',
'Troubadour'=> 'Troubador',
'Waldl鋟fer'=> 'Ranger',
'Krieger' => 'Warrior'
),*/
'wow' => array(
'德鲁伊'	=>	'Druid',
'圣骑士'	=>	'Paladin',
'术士'	=>	'Warlock',
'猎人'	=>	'Hunter',
'战士'	=>	'Warrior',
'法师'	=>	'Mage',
'牧师'	=>	'Priest',
'潜行者'	=>	'Rogue',
'萨满祭司'	=>	'Shaman',
'未知'	=>	'Unknown'
),
/*'lotro' => array(
'Barde' => 'Minstrel',
'Hauptmann' => 'Captain',
'J鋑er' => 'Hunter',
'Kundiger'=> 'Lore-master',
'Schurke' => 'Burglar',
'W鋍hter' => 'Guardian',
'Waffenmeister' => 'Champion'
),*/
/*'aoc' => array(
'Assassin'=> 'Assassin',
'Barbar'=> 'Barbarian',
'Waldl鋟fer'=> 'Ranger',
'Eroberer'=> 'Conqueror',
'W鋍hter' => 'Dark Templar',
'Dunkler Templer' => 'Guardian',
'D鋗onologe'=> 'Demonologist',
'Herold des Xotli'=> 'Herald of Xotli',
'Nekromant' => 'Necromancer',
'Mitrapriester' => 'Priest of Mitra',
'B鋜enschamane' => 'Bear Shaman',
'Vollstrecker Sets'=> 'Tempest of Set',
)*/
);

// The Racename convertion Array
$raceconversion = array(
/*'eq' => array(
'Barbar'=> 'Barbarian',
'Hochelf' => 'High Elf',
'Dunkelelf' => 'Dark Elf',
'Waldelf' => 'Wood Elf',
'Halbelf' => 'Half Elf',
'Kerraner'=> 'Kerra',
'Oger'=> 'Ogre',
'Froschlog' => 'Frog',
'Erudit'=> 'Erudite',
'Halbling'=> 'Halfling',
'Fee' => 'Fay'
),*/
'wow' => array(
'未知'	=>	'Unknown',
'侏儒'	=>	'Gnome',
'人类'	=>	'Human',
'矮人'	=>	'Dwarf',
'暗夜精灵'	=>	'Night Elf',
'德莱尼'	=>	'Draenei',
'亡灵'	=>	'Undead', 
'兽人'	=>	'Orc',
'牛头人'	=>	'Tauren',
'巨魔'	=>	'Troll',
'血精灵'	=>	'Blood Elf',

),
/*'aoc' => array(
'Aquilonier'=> 'Aquilonian',
'Cimmerier' => 'Cimmerian',
'Stygier' => 'Stygian'
)*/
);

?>
