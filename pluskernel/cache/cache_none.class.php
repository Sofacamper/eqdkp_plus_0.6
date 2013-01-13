<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-06-11 16:32:38 +0200 (Do, 11 Jun 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5051 $
 * 
 * $Id: cache_mysql_sq.class.php 5051 2009-06-11 14:32:38Z sz3 $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_none" ) ) {
  class cache_none implements plus_datacache{
      	
    function init($params_array){
    }
    
    function put($key, $value, $ttl=''){
    	return false;
    }
    
    function get($key){
      return null;
    }
    
    function del($key){
      return null;   
    }
    
    function del_prefix($prefix){
      return null;
    }
        
    function del_suffix($suffix){
      return null;
    }
    
    function cleanup(){
      return null;
    }
    
    function flush(){
      return null;
    }
    
    function listing(){
      return null;
    }
        
  }//end class
}//end if
?>