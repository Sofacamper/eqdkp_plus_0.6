<?php
 /*
 * Project:     eqdkpPLUS Libraries: Armory
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2010-12-15 17:55:26 +0100 (Wed, 15 Dec 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:armory
 * @version     $Rev: 9377 $
 * 
 * $Id: armory.convert.php 9377 2010-12-15 16:55:26Z wallenium $
 */

$ac_trans = array(
  'classes' => array(
      1   => '12',	// warrior
      2   => '13',	// paladin
      3   => '4',	// hunter
      4   => '2',	// rogue
      5   => '6',	// priest
      6   => '20',	// DK
      7   => '9',	// shaman
      8   => '11',	// mage
      9   => '10',	// warlock
      11  => '7',	// druid
  ),
  'races' => array(
      '1'	=> 2,	// human
      '2'	=> 7,	// orc
      '3'	=> 3,	// dwarf
      '4'	=> 4,	// night elf
      '5'	=> 6,	// undead
      '6'	=> 8,	// tauren
      '7'	=> 1,	// gnome
      '8'	=> 5,	// troll
      '9'	=> 12,	// goblin
      '10'	=> 10,	// blood elf
      '11'	=> 9,	// draenei
      '22'	=> 11,	// worgen
  ),
  'gender' => array(
      '0'   => 'Male',
      '1' 	=> 'Female',
  ),

  'months' => array(
  	// german
    'de_de' => array(
      'Januar'        => 'January',
      'Februar'       => 'February',
      'März'          => 'March',
      'MÃ¤rz'         => 'March',
      'April'         => 'April',
      'Mai'           => 'May',
      'Juni'          => 'June',
      'Juli'          => 'July',
      'August'        => 'August',
      'September'     => 'September',
      'Oktober'       => 'October',
      'November'      => 'November',
      'Dezember'      => 'December',
    ),
    'fr_fr' => array(
    ),
  )
);
?>
