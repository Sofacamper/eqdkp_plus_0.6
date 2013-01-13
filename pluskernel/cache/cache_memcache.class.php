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
 * $Id: cache_memcache.class.php 5171 2009-07-04 22:11:24Z sz3 $
 */
 
if ( !defined('EQDKP_INC') ) {
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_memcache" ) ) {
	class cache_memcache implements plus_datacache{

		var $default_ttl = 60;
		var $server = 'localhost';
    var $memcache;
		var $prefix;
			
		function cache_memcache(){
      $this->memcache = new Memcache;
		  $this->memcache->connect($this->server, 11211);
    }
		
		function init($param_array){
			$this->prefix = $param_array['prefix'];
    }
    
		function put($key, $data, $ttl='',$noprefix=false,$compress=false){
			$key = ($noprefix) ? $key : $this->prefix.'.'.$key ;			
			$flags = ($compress) ? MEMCACHE_COMPRESSED : false ;
			$ttl = ($ttl == '') ?  $this->default_ttl : $ttl ;			        		
          	        	
      return $this->memcache->set($key, $data, $flags, $ttl);				
		}
		
		function get($key,$noprefix=false,$compress=false){
			$key = ($noprefix) ? $key : $this->prefix.'.'.$key ;		
			$flags = ($compress) ? MEMCACHE_COMPRESSED : 0 ;			
			$retval = $this->memcache->get($key, $flags);
       
			return ($retval == false) ? null : $retval;
		}
		
		function del($key){
      $this->memcache->delete($key);		
			return true;
		}
		
		function del_prefix($prefix){
		  return $this->memcache->flush();
    }
    
  	function del_suffix($suffix){
			return $this->memcache->flush();
		}			
		
  	function cleanup(){
			return $this->memcache->flush();
  	}
  
    function flush(){
      return $this->memcache->flush(); 
    }
  
  	function listing(){
    		//implement method which returns a listing (key, size, ttl) of all cache entries
  	}
      	
  }//end class
}//end if
?>