<?php
/*
* Project:     EQdkp-Plus
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* ----------------------------------------------------------------------------------
* Gallery 4 EQdkp plus
* ---------------------------------------------------------------------------------- 
* Project Start: 09/2008
* Author: BadTwin
* Copyright: Andreas (BadTwin) Schrottenbaum
* Link: http:// bloody-midnight.eu
* Version: 2.0.11
* ----------------------------------------------------------------------------------
* Based on the HelloWorld Plugin by Wallenium
*
* $Id: $
*/

  
  // Configuration
  $myPluginID       = 'gallery';         // Plugin ID, p.e. 'raidplan'
  $myPluginIncludes = 'include';   // Includes Folder of Plugin

  // DO NOT CHANGE
  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  } 
  
  // EQDKP PLUS 0.7.x ++
  if(is_object($libloader)){
    $libloader->CheckLibVersion('Libraries', false, $pm->plugins[$myPluginID]->fwversion);
    $khrml = $html; $khrml->SetPluginName($myPluginID);
  
  // EQDKP PLUS 0.6.3.1 ++
  }else{
    if(!file_exists($eqdkp_root_path . 'libraries/libraries.php')){
      message_die((($user->lang['libloader_notfound']) ? $user->lang['libloader_notfound'] : 'Library Loader not available! Check if the "eqdkp/libraries/" folder is uploaded correctly'));
    }
    require_once($eqdkp_root_path . 'libraries/libraries.php');
    $libloader  = new libraries();
    $libloader->CheckLibVersion('Libraries',false, $pm->plugins[$myPluginID]->fwversion);
    $jquery = $jqueryp; $khrml = new myHTML($myPluginID);
  }
?>