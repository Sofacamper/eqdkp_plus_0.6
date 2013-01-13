<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-09-30 22:32:18 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 12159 $
 * 
 * $Id: convertion.php 12159 2012-09-30 20:32:18Z wallenium $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

// Convert the Classnames to english
$classconvert_array = array(
  'german'  => array(
        "Druide"          	=> "Druid",
        "Hexenmeister"    	=> "Warlock",
        "J�ger"           	=> "Hunter",
        "Krieger"         	=> "Warrior",
        "Magier"          	=> "Mage",
        "Paladin"         	=> "Paladin",
        "Priester"        	=> "Priest",
        "Schurke"         	=> "Rogue",
        "Schamane"        	=> "Shaman",
        "Todesritter"     	=> "Death Knight",
		"M�nch"     		=> "Monk",
  ),
  'french'  => array(
        "Druide"          	=> "Druid",
        "D�moniste"    			=> "Warlock",
        "Chasseur"          => "Hunter",
        "Guerrier"         	=> "Warrior",
        "Mage"          		=> "Mage",
        "Paladin"         	=> "Paladin",
        "Pr�tre"        		=> "Priest",
        "Voleur"         		=> "Rogue",
        "Chaman"        		=> "Shaman",
        "Chevalier de la mort"     => "Death Knight"
  ),
  'russian'  => array(
        "���������"       	=> "Druid",
        "�������"           => "Warlock",
        "����"              => "Hunter",
        "�����"             => "Warrior",
        "�����"             => "Mage",
        "������"            => "Paladin",
        "���"               => "Priest",
        "����"              => "Rogue",
        "�������"           => "Shaman",
        "Todesritter"     	=> "Death Knight",
  ),
  'spanish'  => array(
        "Druida"          	=> "Druid",
        "Brujo"    					=> "Warlock",
        "Cazador"         	=> "Hunter",
        "Guerrero"         	=> "Warrior",
        "Mago"          		=> "Mage",
        "Palad�n"         	=> "Paladin",
        "Sacerdote"        	=> "Priest",
        "P�caro"         		=> "Rogue",
        "Cham�n"       			=> "Shaman",
        "Caballero de la muerte"     => "Death Knight"
  ),
);

?>
