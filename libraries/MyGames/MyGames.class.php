<?php
 /*
 * Project:     eqdkpPLUS Libraries: pluginCore
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-03-13 12:33:17 +0100 (Sun, 13 Mar 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:pluginCore
 * @version     $Rev: 10000 $
 * 
 * $Id: MyGames.class.php 10000 2011-03-13 11:33:17Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("MyGames")) {
  class MyGames
  {
    var $gamelist = array(
                      'wow'           => 'wow',
                      'wow_german'    => 'wow',
                      'wow_english'   => 'wow',
                      
                      'lotro'         => 'lotro',
                      'lotro_german'  => 'lotro',
                      'lotro_english' => 'lotro',
                      
                      'vanguard-soh'  => 'vanguard',
                      'everquest'     => 'eq',
                      'everquest2'    => 'eq',
                      'aoc'           => 'aoc',
                      'aion'          => 'aion',
                      'atlantica'	=> 'atlantica',
                      'fxii'					=> 'fxii',
                      'runesofmagic'	=> 'runesofmagic',
                      'tr'						=> 'tr',
                      'warhammer'			=> 'warhammer',
                      'rift'		=> 'rift',
                      'allods'		=> 'allods',
                      'daoc'		=> 'daoc',
                      'tera'		=> 'tera',
                      'swtor'		=> 'swtor',
                      'ffxiv'		=> 'ffxiv',
                    );
    
    // Get the actual game name
    function MyGames($gname=''){
      global $eqdkp;
      $gname = ($gname) ? strtolower($gname) : strtolower($eqdkp->config['default_game']);
    	$this->game = $this->gamelist[$gname];
    	return ($this->game) ? $this->game : $gname;
    }
  
    // Return the game name
    function GameName(){
      return $this->game;
    }
  }
}
?>
