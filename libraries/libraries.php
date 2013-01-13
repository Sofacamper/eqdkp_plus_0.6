<?php
 /*
 * Project:     eqdkpPLUS Library Manager
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-10-12 17:19:45 +0200 (Mon, 12 Oct 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries
 * @version     $Rev: 6074 $
 *
 * $Id: libraries.php 6074 2009-10-12 15:19:45Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("libraries")) {
  class libraries {
    static protected $version = '2.0.0';
    static protected $libdirs = array();

    public function __construct(){
      global $eqdkp_root_path;
      if ( $dir = opendir($eqdkp_root_path . 'libraries/') ){
        while ( $d_lib_code = @readdir($dir) ){
          $cwd = $eqdkp_root_path . 'libraries/'.$d_lib_code.'/'.$d_lib_code.'.class.php'; // regenerate the link to the 'plugin'
          if((@is_file($cwd)) && valid_folder($d_lib_code)){  // check if valid
              @array_push(self::$libdirs, $d_lib_code);  // add to array
          }
        }
      }
      //guarantee, that other autoload functions can be registered without overwriting this one
      spl_autoload_register(array('libraries','autoloader'));
    }

    public static function autoloader($class_name) {
      global $eqdkp_root_path;
      if(in_array($class_name, self::$libdirs)){
        $myFile = $eqdkp_root_path.'libraries/'.$class_name.'/'.$class_name.'.class.php';
        if(file_exists($myFile)){
          if (!class_exists($class_name)){
          	require_once($myFile);
          }
        }
      }
    }

    public function CheckLibVersion($libname, $version_lib, $version_req, $libversion=0){
      global $user;
      $version_lib    = ($version_lib) ? $version_lib :  self::$version;
      $libversion     = ($libversion != '0') ? $libversion : $version_req;

      // The static Language Files:
      $download_link  = 'https://sourceforge.net/project/showfiles.php?group_id=167016&package_id=301378';
      $langLibMain    = "Library Loader too old! Version ".$version_req." or higher required.
                        <br/> Download: <a href='".$download_link."' target='blank'>Libraries Download</a><br/>
                        Please download, and overwrite the existing 'eqdkp/libraries/' folder with the one you downloaded.";
      $langLibPlug    = "The Library Module '".$libname."' is outdated. Version ".$version_req." or higher is required.
                        This is included in the Libraries ".$libversion." or higher. Your current Libraries Version is ".self::$version."<br/>
                        Download: <a href='".$download_link."' target='blank'>Libraries Download</a><br/>
                        Please download, and overwrite the existing 'eqdkp/libraries/' folder with the one you downloaded!";

      if($version_lib < $version_req){
        if($libname == 'Libraries'){
          $libnothere_txt = ($user->lang['libloader_tooold']) ? sprintf($user->lang['libloader_tooold'], $version_req, $download_link, self::$version) : $langLibMain;
        }else{
          $libnothere_txt = ($user->lang['libloader_tooold_plug']) ? sprintf($user->lang['libloader_tooold_plug'], $libname, $version_req, $download_link, $libversion, self::$version) : $langLibPlug;
        }
        message_die($libnothere_txt);
      }
    }

    public function get_version(){
      return self::$version;
    }
  }
}
?>