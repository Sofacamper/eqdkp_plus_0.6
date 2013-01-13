<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-07-05 00:11:24 +0200 (Sun, 05 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5171 $
 * 
 * $Id: cache_xcache.class.php 5171 2009-07-04 22:11:24Z sz3 $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_xcache" ) ) {
  class cache_xcache implements plus_datacache{

		var $default_ttl = 60;
		
		function init($param_array){
    
    }
		
		function put($key, $data, $ttl=null){
      if($ttl == null)
        $ttl = $this->default_ttl;
			xcache_set($key, $data, $ttl);
      return true;				
		}

		function get($key){
      return xcache_get($key);
		}
		
		function del($key){
      xcache_unset($key);		
			return true;
		}
		
		function del_prefix($prefix){
    
    }
		
		function del_suffix($suffix){
    
    }
    
    function cleanup(){
      //implement method to delete outdated cache entries
    }
    
    function flush(){
      //implement method to flush all entries 
    }
    
    function listing(){
      //implement method which returns a listing (key, size, ttl) of all cache entries
    }
    
  }//end class
}//end if
?>