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
 * $Id: export.class.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

class RaidplanExport {
  
  function generate($name=false){
    global $eqdkp_root_path;
    $export_array[] = "----";
    // Search for plugins and make sure they are registered
    if ( $dir = opendir($eqdkp_root_path . 'plugins/raidplan/modules/export/plugins/') ){
      while ( $d_plugin_code = @readdir($dir) ){
        $cwd = $eqdkp_root_path.'plugins/raidplan/modules/export/plugins/'.$d_plugin_code; // regenerate the link to the 'plugin'
        if((@is_file($cwd)) && $this->CheckFolders($d_plugin_code)){  // check if valid
          if($name){
            include($cwd);
            $export_array[$rpexport_plugin[$d_plugin_code]['function']] = $rpexport_plugin[$d_plugin_code]['name'];  // add to array
          }else{
            $export_array[$d_plugin_code] = $d_plugin_code;  // add to array
          }
        }
      }
    }
    return $export_array;
  }
  
  function SelectOutput(){
    global $db, $eqdkp_root_path, $user, $SID, $conf;
    $output = $user->lang['rp_export_text'];
    return $output;
  }
  
  function CheckFolders($file){
    $ignore = array('.', '..', '.svn', 'CVS', 'cache', 'install', 'index.html', '.htaccess');
    if ( !is_file($path) && !is_link($path) && !in_array(basename($path), $ignore) ){
      return true;
    }
    return false;
  }
}
?>
