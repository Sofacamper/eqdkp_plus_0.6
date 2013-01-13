<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-08-03 23:18:58 +0200 (Mon, 03 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 5424 $
 * 
 * $Id: convertion.class.php 5424 2009-08-03 21:18:58Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

class Convertions
{

  // Init the class
  function Convertions(){
    global $eqdkp_root_path, $rpclass, $user, $eqdkp;
    $selected_game = $rpclass->SelectedGame();
    
    // Load all languages for the LANG=> EN Array
    if ($dir = @opendir($eqdkp_root_path.'plugins/raidplan/language/') ){
      while ( $file = @readdir($dir) ){
        if ( (!is_file($eqdkp_root_path.'plugins/raidplan/language/' . $file)) && (!is_link($eqdkp_root_path.'plugins/raidplan/language/' . $file)) && $this->valid_folder($file)) {
          $langarry[] = $file;
        }
      }
    }
    foreach($langarry as $usrlang){
      if($usrlang != 'english'){
        if(is_file($eqdkp_root_path.'plugins/raidplan/language/'.$usrlang.'/lang_convert.php')){
          include($eqdkp_root_path.'plugins/raidplan/language/'.$usrlang.'/lang_convert.php');
          if($raceconversion[$selected_game]){
            $this->raceconvert  = (!is_array($this->raceconvert)) ? $raceconversion[$selected_game] : array_merge($this->raceconvert, $raceconversion[$selected_game]);
          }
          if($classconversion[$selected_game]){
          $this->classconvert = (!is_array($this->classconvert)) ? $classconversion[$selected_game] : array_merge($this->classconvert, $classconversion[$selected_game]);
          }
        }
      }
    }
    $this->LANGtoEN = $this->classconvert;

    // Set the array for EN => LANG
    $languagetoUse = ($user->data['user_lang']) ? $user->data['user_lang'] : $eqdkp->config['default_lang'];
    if($languagetoUse && $languagetoUse != 'english'){
      include($eqdkp_root_path.'plugins/raidplan/language/'.$languagetoUse.'/lang_convert.php');
      if($classconversion[$selected_game]){
        $this->ENtoLANG = array_flip($classconversion[$selected_game]);
      }
    }
  }
  
  // Class name to EN
  function classname($classname, $tolanguage=''){
    if($tolanguage){
      $tmpclassname = $this->ENtoLANG[$classname];
    }else{
      $tmpclassname = str_replace(" ", "", $this->LANGtoEN[$classname]);
    }
    return ($tmpclassname) ? $tmpclassname : str_replace(" ", "", $classname);
  }
  
  function racesname($racesname){
    $tmpracename   = $this->raceconvert[$racesname];
    return ($tmpracename) ? $tmpracename : $racesname;
  }
  
  function valid_folder($path){
    $ignore = array('.', '..', '.svn', 'CVS', 'cache', 'install', 'index.html', '.htaccess');

    if ( !is_file($path) && !is_link($path) && !in_array(basename($path), $ignore) ){
        return true;
    }
    return false;
  }
}

?>
